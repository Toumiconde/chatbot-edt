<?php

namespace App\Http\Controllers;

use App\Models\Niveau;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ProgressionController extends Controller
{
    // GET /api/chef/niveaux — niveaux de la filière du chef connecté
    public function niveaux()
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'chef') {
            return response()->json(['error' => 'Accès refusé.'], 403);
        }

        $niveaux = Niveau::where('filiere_id', $user->filiere_id)
            ->orderBy('libelle')
            ->get()
            ->map(fn($n) => [
                'id'      => $n->id,
                'libelle' => $n->libelle,
                'count'   => User::where('role', 'etudiant')
                                 ->where('filiere_id', $user->filiere_id)
                                 ->where('niveau_id', $n->id)
                                 ->count(),
            ]);

        return response()->json($niveaux);
    }

    // POST /api/chef/progression — faire passer les étudiants d'un niveau au suivant
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'chef') {
            return response()->json(['error' => 'Accès refusé. Réservé aux chefs de programme.'], 403);
        }

        $request->validate([
            'niveau_actuel_id'  => 'required|exists:niveaux,id',
            'niveau_suivant_id' => 'required|exists:niveaux,id|different:niveau_actuel_id',
        ]);

        $filiereId       = $user->filiere_id;
        $niveauActuelId  = (int) $request->niveau_actuel_id;
        $niveauSuivantId = (int) $request->niveau_suivant_id;

        // Les deux niveaux doivent appartenir au département du chef
        $niveauActuel  = Niveau::where('id', $niveauActuelId)->where('filiere_id', $filiereId)->first();
        $niveauSuivant = Niveau::where('id', $niveauSuivantId)->where('filiere_id', $filiereId)->first();

        if (!$niveauActuel || !$niveauSuivant) {
            return response()->json([
                'error' => 'Ces niveaux n\'appartiennent pas à votre département.',
            ], 403);
        }

        // Récupérer les étudiants concernés avant de les modifier
        $etudiants = User::where('role', 'etudiant')
            ->where('filiere_id', $filiereId)
            ->where('niveau_id', $niveauActuelId)
            ->get();

        if ($etudiants->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => "Aucun étudiant en {$niveauActuel->libelle} dans votre département.",
                'count'   => 0,
            ]);
        }

        // Faire progresser tous les étudiants
        User::where('role', 'etudiant')
            ->where('filiere_id', $filiereId)
            ->where('niveau_id', $niveauActuelId)
            ->update(['niveau_id' => $niveauSuivantId]);

        // Notifier chaque étudiant individuellement
        foreach ($etudiants as $etudiant) {
            NotificationService::notifierUser(
                $etudiant->id,
                '🎓 Passage en ' . $niveauSuivant->libelle,
                "Félicitations {$etudiant->name} ! Vous avez été inscrit en {$niveauSuivant->libelle}. "
                . "Votre nouvel emploi du temps est disponible dans l'application.",
                'success',
                [
                    'niveau_precedent' => $niveauActuel->libelle,
                    'niveau_suivant'   => $niveauSuivant->libelle,
                    'niveau_id'        => $niveauSuivantId,
                ]
            );
        }

        $count = $etudiants->count();

        return response()->json([
            'success' => true,
            'message' => "{$count} étudiant(s) passé(s) de {$niveauActuel->libelle} en {$niveauSuivant->libelle} avec succès.",
            'count'   => $count,
        ]);
    }
}
