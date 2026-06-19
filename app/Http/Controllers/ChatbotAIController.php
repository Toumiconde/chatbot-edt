<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\EmploiDuTemps;
use App\Models\Filiere;
use App\Models\Modification;
use App\Models\Niveau;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotAIController extends Controller
{
    public function ask(Request $request)
    {
        $message      = $request->input('message', '');
        $history      = $request->input('history', []);
        $filiereId    = $request->input('filiere_id');
        $niveauId     = $request->input('niveau_id');
        $enseignantId = $request->input('enseignant_id');

        $user = auth()->user();

        if (!$filiereId    && $user?->filiere_id)    $filiereId    = $user->filiere_id;
        if (!$niveauId     && $user?->niveau_id)     $niveauId     = $user->niveau_id;
        if (!$enseignantId && $user?->enseignant_id) $enseignantId = $user->enseignant_id;

        $edt         = $this->fetchEdt($filiereId, $niveauId, $enseignantId);
        $alertes     = $this->fetchAlertes($filiereId);
        $annonces    = $this->fetchAnnonces($filiereId);
        $filieres    = $this->fetchFilieres();
        $niveaux     = $this->fetchNiveaux();
        $stats       = $this->fetchStats();
        $enseignants = $this->fetchEnseignants($filiereId);
        $etudiants   = $this->fetchEtudiants($filiereId);
        $chefs       = $this->fetchChefs();

        // Recherche web si la question n'est pas liée à l'application
        $webResults = $this->needsWebSearch($message)
            ? $this->searchWeb($message)
            : null;

        $systemPrompt = $this->buildSystemPrompt($user, $edt, $alertes, $annonces, $filieres, $niveaux, $stats, $enseignants, $etudiants, $chefs, $webResults);

        // Construire les messages (format OpenAI / Groq)
        $messages = [['role' => 'system', 'content' => $systemPrompt]];
        foreach (array_slice($history, -10) as $h) {
            $messages[] = [
                'role'    => $h['role'] === 'assistant' ? 'assistant' : 'user',
                'content' => $h['content'],
            ];
        }
        $messages[] = ['role' => 'user', 'content' => $message];

        $apiKey = config('services.gemini.key');

        $res = Http::timeout(30)->withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model'           => 'llama-3.1-8b-instant',
            'messages'        => $messages,
            'temperature'     => 0.7,
            'max_tokens'      => 600,
            'response_format' => ['type' => 'json_object'],
        ]);

        if (!$res->ok()) {
            $erreur = $res->json('error.message') ?? $res->body();
            return response()->json([
                'message' => 'ERREUR : ' . $erreur,
                'action'  => 'none',
            ]);
        }

        $text   = $res->json('choices.0.message.content');
        $result = json_decode($text, true);

        if (!is_array($result) || !isset($result['message'])) {
            $result = ['message' => $text ?? 'Réponse invalide.', 'action' => 'none'];
        }

        return response()->json($result);
    }

    private function fetchEdt(?int $filiereId, ?int $niveauId, ?int $enseignantId): array
    {
        if (!$filiereId && !$enseignantId) return [];

        $query = EmploiDuTemps::with(['matiere', 'enseignant', 'salle', 'niveau', 'filiere']);

        if ($enseignantId) {
            $query->where('enseignant_id', $enseignantId);
        } else {
            $query->where('filiere_id', $filiereId);
            if ($niveauId) $query->where('niveau_id', $niveauId);
        }

        return $query->orderBy('jour')->orderBy('heure_debut')->get()->map(fn($e) => [
            'jour'       => $e->jour,
            'debut'      => substr($e->heure_debut, 0, 5),
            'fin'        => substr($e->heure_fin, 0, 5),
            'matiere'    => $e->matiere?->nom,
            'enseignant' => $e->enseignant ? "{$e->enseignant->prenom} {$e->enseignant->nom}" : null,
            'salle'      => $e->salle?->nom,
            'filiere'    => $e->filiere?->nom,
            'filiere_id' => $e->filiere_id,
            'niveau'     => $e->niveau?->libelle,
            'niveau_id'  => $e->niveau_id,
        ])->toArray();
    }

    private function fetchAlertes(?int $filiereId): array
    {
        $query = Modification::with(['emploi.matiere', 'emploi.filiere'])
            ->where('created_at', '>=', now()->subHours(72));

        if ($filiereId) {
            $query->whereHas('emploi', fn($q) => $q->where('filiere_id', $filiereId));
        }

        return $query->latest()->take(5)->get()->map(fn($m) => [
            'matiere' => $m->emploi?->matiere?->nom,
            'ancien'  => "{$m->ancien_jour} " . substr($m->ancienne_heure, 0, 5),
            'nouveau' => "{$m->nouveau_jour} " . substr($m->nouvelle_heure, 0, 5),
            'motif'   => $m->motif,
            'date'    => $m->created_at->format('d/m H:i'),
        ])->toArray();
    }

    private function fetchAnnonces(?int $filiereId): array
    {
        return Annonce::where(function ($q) use ($filiereId) {
            if ($filiereId) {
                $q->where('filiere_id', $filiereId)->orWhereNull('filiere_id');
            }
        })->latest()->take(3)->get()->map(fn($a) => [
            'titre'   => $a->titre,
            'message' => $a->message,
            'date'    => $a->created_at->format('d/m'),
        ])->toArray();
    }

    // Détecte si la question concerne l'app ou le monde extérieur
    private function needsWebSearch(string $message): bool
    {
        $appKeywords = [
            'edt', 'emploi', 'cours', 'filiere', 'filière', 'niveau', 'semestre',
            'etudiant', 'étudiant', 'enseignant', 'prof', 'professeur', 'salle',
            'matiere', 'matière', 'planning', 'horaire', 'alerte', 'modification',
            'ntic', 'dl', 'département', 'departement', 'chef', 'programme',
            'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'semaine',
            'l1', 'l2', 'l3', 'licence', 'pdf', 'telecharger', 'télécharger',
            'uganc', 'université gamal', 'conakry', 'annonce', 'notification',
        ];

        $lower = mb_strtolower($message);
        foreach ($appKeywords as $kw) {
            if (str_contains($lower, $kw)) return false;
        }

        // Si la question est très courte (salutation, merci) → pas de recherche
        if (str_word_count($message) <= 3) return false;

        return true;
    }

    // Recherche web via Tavily
    private function searchWeb(string $query): ?string
    {
        $apiKey = config('services.tavily.key');
        if (!$apiKey) return null;

        try {
            $res = Http::timeout(8)->post('https://api.tavily.com/search', [
                'api_key'      => $apiKey,
                'query'        => $query,
                'max_results'  => 4,
                'search_depth' => 'basic',
                'include_answer' => true,
            ]);

            if (!$res->ok()) return null;

            $data    = $res->json();
            $summary = $data['answer'] ?? null;
            $results = $data['results'] ?? [];

            $lines = [];
            if ($summary) $lines[] = "Résumé : {$summary}";

            foreach (array_slice($results, 0, 3) as $r) {
                $lines[] = "• {$r['title']} : " . mb_substr($r['content'] ?? '', 0, 200);
            }

            return empty($lines) ? null : implode("\n", $lines);

        } catch (\Exception $e) {
            return null;
        }
    }

    private function fetchEtudiants(?int $filiereId): array
    {
        $query = User::where('role', 'etudiant')
            ->with(['filiere', 'niveau'])
            ->orderBy('name');

        if ($filiereId) $query->where('filiere_id', $filiereId);

        return $query->get()->map(fn($u) => [
            'nom'     => $u->name,
            'filiere' => $u->filiere?->nom ?? 'N/A',
            'niveau'  => $u->niveau?->libelle ?? 'N/A',
        ])->toArray();
    }

    private function fetchChefs(): array
    {
        return User::where('role', 'chef')
            ->with('filiere')
            ->orderBy('name')
            ->get()
            ->map(fn($u) => [
                'nom'     => $u->name,
                'filiere' => $u->filiere?->nom ?? 'N/A',
            ])->toArray();
    }

    private function fetchEnseignants(?int $filiereId): array
    {
        return \App\Models\Enseignant::orderBy('nom')
            ->get()
            ->map(fn($e) => ['nom' => $e->nom, 'prenom' => $e->prenom])
            ->toArray();
    }

    private function fetchStats(): array
    {
        return [
            'etudiants' => User::where('role', 'etudiant')->count(),
            'profs'     => User::where('role', 'prof')->count(),
            'chefs'     => User::where('role', 'chef')->count(),
            'total_edt' => EmploiDuTemps::count(),
            'filieres'  => Filiere::count(),
        ];
    }

    private function fetchFilieres(): array
    {
        return Filiere::all()->map(fn($f) => [
            'id'  => $f->id,
            'nom' => $f->nom,
            'code' => $f->code,
        ])->toArray();
    }

    private function fetchNiveaux(): array
    {
        return Niveau::with('filiere')->get()->map(fn($n) => [
            'id'         => $n->id,
            'libelle'    => $n->libelle,
            'filiere_id' => $n->filiere_id,
            'filiere'    => $n->filiere?->nom,
        ])->toArray();
    }

    private function buildSystemPrompt($user, array $edt, array $alertes, array $annonces, array $filieres, array $niveaux, array $stats = [], array $enseignants = [], array $etudiants = [], array $chefs = [], ?string $webResults = null): string
    {
        $role    = $user?->role             ?? 'visiteur';
        $name    = $user?->name             ?? 'Visiteur';
        $filiere = $user?->filiere?->nom    ?? 'N/A';
        $niveau  = $user?->niveau?->libelle ?? 'N/A';

        $jours = ['Sunday'=>'Dimanche','Monday'=>'Lundi','Tuesday'=>'Mardi',
                  'Wednesday'=>'Mercredi','Thursday'=>'Jeudi','Friday'=>'Vendredi','Saturday'=>'Samedi'];
        $jourActuel  = $jours[now()->format('l')] ?? now()->format('l');
        $heureActuel = now()->format('H:i');
        $dateActuel  = now()->format('d/m/Y');

        // Format compact : "id:nom" pour réduire les tokens
        $filieresCompact    = implode(', ', array_map(fn($f) => "{$f['id']}:{$f['nom']}", $filieres));
        $niveauxCompact     = implode(', ', array_map(fn($n) => "{$n['id']}:{$n['libelle']}(filiere:{$n['filiere_id']})", $niveaux));
        $enseignantsCompact = empty($enseignants)
            ? 'Aucun'
            : implode(' | ', array_map(fn($e) => "{$e['prenom']} {$e['nom']}", $enseignants));
        $etudiantsCompact   = empty($etudiants)
            ? 'Aucun'
            : implode(' | ', array_map(fn($e) => "{$e['nom']} ({$e['filiere']}/{$e['niveau']})", $etudiants));
        $chefsCompact       = empty($chefs)
            ? 'Aucun'
            : implode(' | ', array_map(fn($c) => "{$c['nom']} ({$c['filiere']})", $chefs));

        // EDT compact : max 20 entrées, format lisible
        $edtLines = empty($edt) ? ['Aucun cours'] : array_map(
            fn($e) => "{$e['jour']} {$e['debut']}-{$e['fin']}: {$e['matiere']} | {$e['enseignant']} | {$e['salle']} [f:{$e['filiere_id']},n:{$e['niveau_id']}]",
            array_slice($edt, 0, 20)
        );
        $edtCompact = implode("\n", $edtLines);

        $alertesCompact = empty($alertes) ? 'Aucune' : implode(' | ', array_map(
            fn($a) => "{$a['matiere']}: {$a['ancien']} → {$a['nouveau']}",
            $alertes
        ));

        $webSection = $webResults
            ? "\nRÉSULTATS WEB (informations récentes issues d'internet) :\n{$webResults}\n→ Utilise ces résultats pour répondre à la question mondiale de l'utilisateur."
            : '';

        return <<<PROMPT
You are the AI assistant for UGANC timetable system (Conakry, Guinea), Groupe 6.
Reply in the SAME LANGUAGE as the user (French → French, English → English). Be short and helpful.

NOW: {$dateActuel} | {$jourActuel} | {$heureActuel}
USER: {$name} | role:{$role} | dept:{$filiere} | level:{$niveau}

DEPARTMENTS (id:name): {$filieresCompact}
LEVELS (id:label(filiere_id)): {$niveauxCompact}

TIMETABLE (live from DB):
{$edtCompact}

ALERTS: {$alertesCompact}

DONNÉES COMPLÈTES DE LA BASE DE DONNÉES :

Enseignants : {$enseignantsCompact}
Étudiants : {$etudiantsCompact}
Chefs de programme : {$chefsCompact}

Statistiques : {$stats['etudiants']} étudiants | {$stats['profs']} enseignants | {$stats['chefs']} chefs | {$stats['total_edt']} cours planifiés | {$stats['filieres']} départements
{$webSection}
ACTIONS :
- show_edt_jour → params: {jour:"Lundi", filiere_id:X, niveau_id:Y} — TOUS les cours d'un jour avec tous les enseignants
- show_edt_semaine → params: {filiere_id:X, niveau_id:Y} — tous les cours de la semaine
- show_edt_semestriel → params: {filiere_id:X, niveau_id:Y}
- show_alertes → pas de params
- show_planning_enseignant → params: {enseignant_id:X} — UNIQUEMENT quand l'utilisateur demande LE planning d'UN enseignant nommé précisément
- download_pdf → params: {filiere_id:X, niveau_id:Y}
- none → tous les autres cas

RÈGLES :
1. Pour toute question sur l'APPLICATION (étudiants, profs, cours, EDT, département) → utilise UNIQUEMENT les données ci-dessus.
2. Pour les questions générales (universités, histoire, culture, Guinée, conseils d'études, etc.) → utilise tes connaissances générales + les résultats web si disponibles.
3. Fais correspondre les noms de département/niveau à leurs IDs dans les params quand tu déclenches une action.
4. "Qui enseigne aujourd'hui/ce matin ?" → consulte l'EDT pour {$jourActuel} à {$heureActuel}, réponds directement (action:none).
5. NE JAMAIS inventer des données spécifiques à l'application qui ne sont pas dans le contexte.
6. Rôles dans l'app : chef=responsable de département, prof=enseignant, etudiant=élève.
7. "voir tous les profs", "voir tout en même temps", "tous les cours du lundi/mardi/..." → utilise TOUJOURS show_edt_jour avec le jour concerné. Ne jamais répondre avec autre chose que du contenu sur l'EDT dans ce contexte.
8. Si le message précédent parlait de cours/horaires, la suite de la conversation porte sur les cours/horaires — reste dans ce contexte même si le message semble ambigu.
9. JAMAIS de réponse médicale, géographique ou hors-sujet si la conversation porte sur l'EDT ou les enseignants.

Reply ONLY with this JSON:
{"message":"...","action":"none","params":{}}
PROMPT;
    }
}
