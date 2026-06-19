<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\EmploiDuTemps;
use App\Models\Enseignant;
use App\Models\Filiere;
use App\Models\Modification;
use App\Models\NotificationApp;
use App\Models\Niveau;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Auto-apply any pending migrations (handles cases where artisan can't be run manually)
        try {
            $needsMigration = !Schema::hasColumn('matieres', 'filiere_id')
                           || !Schema::hasColumn('emplois_du_temps', 'semestre')
                           || !Schema::hasTable('notifications_app');
            if ($needsMigration) {
                Artisan::call('migrate', ['--force' => true]);
            }
        } catch (\Exception $e) {
            // Silently continue if DB is unavailable during boot
        }
    }

    // ── Page principale du tableau de bord ──
    public function index()
    {
        $filieres = Filiere::orderBy('nom')->get();
        return view('dashboard.index', compact('filieres'));
    }

    // ── API : annonces actives (JSON) ──
    public function apiAnnonces()
    {
        $user = auth()->user();
        $query = Annonce::actives()->with(['filiere', 'niveau']);

        if ($user && $user->role === 'etudiant') {
            // Filtre par filière uniquement si l'étudiant en a une
            if ($user->filiere_id) {
                $query->where(function($q) use ($user) {
                    $q->where('filiere_id', $user->filiere_id)
                      ->orWhereNull('filiere_id');
                });
            }
            // Filtre par niveau uniquement si l'étudiant en a un
            if ($user->niveau_id) {
                $query->where(function($q) use ($user) {
                    $q->where('niveau_id', $user->niveau_id)
                      ->orWhereNull('niveau_id');
                });
            }
        } elseif ($user && ($user->role === 'chef' || $user->role === 'prof')) {
            $userFiliereIds = $user->filiere_ids ?? ($user->filiere_id ? [$user->filiere_id] : []);
            // S'assurer que filiere_id principal est toujours inclus
            if ($user->filiere_id && !in_array($user->filiere_id, $userFiliereIds)) {
                $userFiliereIds[] = $user->filiere_id;
            }
            if (!empty($userFiliereIds)) {
                $query->where(function($q) use ($userFiliereIds) {
                    $q->whereIn('filiere_id', $userFiliereIds)
                      ->orWhereNull('filiere_id');
                });
            }
            // Si aucune filière assignée → pas de filtre (voir toutes les annonces)
        }

        $annonces = $query->orderByDesc('urgent')
            ->orderByDesc('created_at')
            ->take(30)
            ->get();

        return response()->json($annonces);
    }

    // ── API : modifications récentes (JSON) ──
    public function apiModifications()
    {
        $user = auth()->user();
        $query = Modification::with(['emploi.matiere', 'emploi.filiere', 'emploi.niveau']);

        if ($user && $user->role === 'etudiant') {
            $query->whereHas('emploi', function($q) use ($user) {
                $q->where('filiere_id', $user->filiere_id)
                  ->where('niveau_id', $user->niveau_id);
            });
        } elseif ($user && ($user->role === 'chef' || $user->role === 'prof')) {
            $userFiliereIds = $user->filiere_ids ?? ($user->filiere_id ? [$user->filiere_id] : []);
            if (!empty($userFiliereIds)) {
                $query->whereHas('emploi', function($q) use ($userFiliereIds) {
                    $q->whereIn('filiere_id', $userFiliereIds);
                });
            }
        }

        $modifications = $query->orderByDesc('date_modif')
            ->take(20)
            ->get();

        return response()->json($modifications);
    }

    // ── Page admin pour publier des annonces ──
    public function adminIndex()
    {
        $userFiliereId  = auth()->user()->filiere_id;
        $userFiliereIds = auth()->user()->filiere_ids ?? ($userFiliereId ? [$userFiliereId] : []);
        // Toujours inclure le filiere_id principal dans la liste
        if ($userFiliereId && !in_array($userFiliereId, $userFiliereIds)) {
            $userFiliereIds[] = $userFiliereId;
        }

        // Afficher toutes les annonces du département ET les annonces globales
        // Si aucune filière assignée à l'admin, afficher toutes les annonces
        $annoncesQuery = Annonce::with(['filiere', 'niveau']);
        if (!empty($userFiliereIds)) {
            $annoncesQuery->where(function($q) use ($userFiliereIds) {
                $q->whereIn('filiere_id', $userFiliereIds)
                  ->orWhereNull('filiere_id');
            });
        }
        $annonces = $annoncesQuery->orderByDesc('created_at')->get();

        $filieres = Filiere::orderBy('nom')->get();
        $niveaux  = Niveau::all()->unique('libelle')->values();
        
        $emploisQuery = EmploiDuTemps::with(['matiere', 'filiere', 'niveau', 'enseignant']);
        if (!empty($userFiliereIds)) {
            $emploisQuery->whereIn('filiere_id', $userFiliereIds);
        }
        $emplois = $emploisQuery->orderBy('jour')->orderBy('heure_debut')->get();

        $modifications = Modification::with(['emploi.matiere', 'emploi.filiere', 'emploi.niveau'])
            ->whereHas('emploi', function($q) use ($userFiliereIds) {
                if (!empty($userFiliereIds)) {
                    $q->whereIn('filiere_id', $userFiliereIds);
                }
            })
            ->orderByDesc('date_modif')
            ->take(30)
            ->get();

        // Data for Chef EDT form
        $matieresQuery = \App\Models\Matiere::with(['filiere', 'niveau'])->orderBy('nom');
        if (!empty($userFiliereIds)) {
            $matieresQuery->where(function($q) use ($userFiliereIds) {
                $q->whereIn('filiere_id', $userFiliereIds)->orWhereNull('filiere_id');
            });
        }
        $matieres = $matieresQuery->get();
        $enseignants = \App\Models\Enseignant::whereHas('user', function($q) use ($userFiliereIds) { $q->whereIn('filiere_id', $userFiliereIds); })->with('user')->orderBy('nom')->get();
        $salles = \App\Models\Salle::orderBy('nom')->get();

        return view('dashboard.admin', compact('annonces', 'filieres', 'niveaux', 'emplois', 'modifications', 'matieres', 'enseignants', 'salles', 'userFiliereId', 'userFiliereIds'));
    }

    // ── Page professeur – afficher les cours assignés ──
    public function professorIndex()
    {
        $user = auth()->user();
        $enseignantId = $user->enseignant_id;
        $emplois = EmploiDuTemps::with(['matiere', 'filiere', 'niveau', 'salle'])
                    ->where('enseignant_id', $enseignantId)
                    ->orderBy('jour')
                    ->orderBy('heure_debut')
                    ->get();

        // Notifications for this professor
        $notifications = [];
        $unreadCount = 0;
        try {
            if (Schema::hasTable('notifications_app')) {
                // Get unread count before marking them as read
                $unreadCount = NotificationApp::forUser($user->id)->unread()->count();
                
                // Fetch the latest 15 notifications (read and unread) to show in the dropdown history
                $notifications = NotificationApp::forUser($user->id)
                    ->orderByDesc('created_at')
                    ->take(15)
                    ->get();
                    
                // Mark all unread as read since they are opening the dashboard
                if ($unreadCount > 0) {
                    NotificationApp::forUser($user->id)->unread()->update(['read_at' => now()]);
                }
            }
        } catch (\Exception $e) { /* table may not exist yet */ }

        return view('dashboard.professor', compact('emplois', 'notifications', 'unreadCount'));
    }


    // ── Afficher le formulaire d'édition d'un cours assigné au prof ──
    public function professorEdit($id)
    {
        $user = auth()->user();
        $enseignantId = $user->enseignant_id;
        // Vérifier que le cours appartient bien à ce professeur
        $emploi = EmploiDuTemps::where('id', $id)
                    ->where('enseignant_id', $enseignantId)
                    ->with(['matiere', 'filiere', 'niveau', 'salle'])
                    ->firstOrFail();
        $matieres = \App\Models\Matiere::orderBy('nom')->get();
        $filieres = \App\Models\Filiere::orderBy('nom')->get();
        $niveaux = \App\Models\Niveau::all()->unique('libelle')->values();
        $salles = \App\Models\Salle::orderBy('nom')->get();
        return view('dashboard.professor-edit', compact('emploi', 'matieres', 'filieres', 'niveaux', 'salles'));
    }

    // ── Mettre à jour le cours assigné au prof ──
    public function professorUpdate(Request $request, $id)
    {
        $user = auth()->user();
        $enseignantId = $user->enseignant_id;

        // Vérifier que le cours appartient bien à ce professeur
        $emploi = EmploiDuTemps::where('id', $id)
                    ->where('enseignant_id', $enseignantId)
                    ->firstOrFail();

        $request->validate([
            'salle_id'     => 'required|exists:salles,id',
            'jour'         => 'required|string',
            'heure_debut'  => 'required',
            'heure_fin'    => 'required',
            'motif'        => 'nullable|string|max:255',
        ]);

        // Mémoriser les anciennes valeurs pour la modification
        $ancienJour = $emploi->jour;
        $ancienneHeure = $emploi->heure_debut;

        // Mettre à jour l'emploi du temps
        $emploi->update([
            'salle_id'     => $request->salle_id,
            'jour'         => $request->jour,
            'heure_debut'  => $request->heure_debut,
            'heure_fin'    => $request->heure_fin,
        ]);

        // Créer la modification d'EDT
        Modification::create([
            'emploi_id'      => $emploi->id,
            'ancien_jour'    => $ancienJour,
            'ancienne_heure' => $ancienneHeure,
            'nouveau_jour'   => $request->jour,
            'nouvelle_heure' => $request->heure_debut,
            'motif'          => $request->motif ?? 'Changement d\'horaire par l\'enseignant.',
            'date_modif'     => now(),
        ]);

        // Notifier les étudiants du même département/niveau (scoped)
        try {
            $matiere = $emploi->matiere?->nom ?? 'cours';
            $ancienCreneau  = "{$ancienJour} à " . substr($ancienneHeure, 0, 5);
            $nouveauCreneau = "{$request->jour} à " . substr($request->heure_debut, 0, 5);

            NotificationService::notifierEtudiants(
                $emploi->filiere_id,
                $emploi->niveau_id,
                '⚠️ Changement d\'horaire',
                "Le cours de {$matiere} prévu le {$ancienCreneau} est déplacé au {$nouveauCreneau} par votre enseignant.",
                'warning',
                ['emploi_id' => $emploi->id]
            );
        } catch (\Exception $e) { /* silent */ }

        return redirect()->route('professor.cours')
                         ->with('status', 'Cours mis à jour et étudiants notifiés avec succès.');
    }

    // ── Créer un Emploi du Temps (Chef uniquement) ──
    public function storeEdt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'niveau_id'     => 'required|exists:niveaux,id',
            'semestre'      => 'required|string',
            'matiere_id'    => 'required|exists:matieres,id',
            'enseignant_id' => 'required|exists:enseignants,id',
            'salle_id'      => 'required|exists:salles,id',
            'jour'          => 'required|string',
            'heure_debut'   => 'required',
            'heure_fin'     => 'required',
        ], [
            'niveau_id.required'     => 'Veuillez sélectionner un niveau (Licence + Semestre).',
            'matiere_id.required'    => 'Veuillez sélectionner une matière.',
            'enseignant_id.required' => 'Veuillez sélectionner un enseignant.',
            'salle_id.required'      => 'Veuillez sélectionner une salle.',
            'heure_debut.required'   => 'Veuillez indiquer l\'heure de début.',
            'heure_fin.required'     => 'Veuillez indiquer l\'heure de fin.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.dashboard', '#saisie-edt')
                ->withErrors($validator, 'edt')
                ->withInput();
        }

        $data = $request->all();
        $data['filiere_id'] = auth()->user()->filiere_id;
        $emploi = EmploiDuTemps::create($data);

        // ── Notifier l'enseignant de son assignation ──
        try {
            $enseignant = Enseignant::find($request->enseignant_id);
            if ($enseignant) {
                $profUser = User::where('enseignant_id', $enseignant->id)->first();
                if ($profUser) {
                    $matiere = \App\Models\Matiere::find($request->matiere_id);
                    $niveau  = Niveau::find($request->niveau_id);
                    $salle   = \App\Models\Salle::find($request->salle_id);

                    NotificationApp::create([
                        'user_id' => $profUser->id,
                        'type'    => 'success',
                        'titre'   => '📚 Nouveau cours assigné',
                        'message' => sprintf(
                            'Vous avez été assigné à "%s" (%s — %s) le %s de %s à %s en salle %s.',
                            $matiere->nom ?? 'cours',
                            $niveau->libelle ?? '',
                            $request->semestre,
                            $request->jour,
                            substr($request->heure_debut, 0, 5),
                            substr($request->heure_fin, 0, 5),
                            $salle->nom ?? ''
                        ),
                        'data' => [
                            'emploi_id'   => $emploi->id,
                            'matiere'     => $matiere->nom ?? '',
                            'niveau'      => $niveau->libelle ?? '',
                            'semestre'    => $request->semestre,
                            'jour'        => $request->jour,
                            'heure_debut' => $request->heure_debut,
                            'heure_fin'   => $request->heure_fin,
                            'salle'       => $salle->nom ?? '',
                        ],
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Notification failed silently — course was still saved
        }

        return redirect()->route('admin.dashboard', '#saisie-edt')->with('success_edt', 'Cours ajouté avec succès à l\'emploi du temps !');
    }

    // ── Supprimer un cours de l'EDT (Chef uniquement) ──
    public function destroyEdt(EmploiDuTemps $emploi)
    {
        if (auth()->user()->filiere_id && $emploi->filiere_id !== auth()->user()->filiere_id) {
            abort(403, 'Vous ne pouvez supprimer que les cours de votre propre département.');
        }
        $emploi->delete();
        return redirect()->route('admin.dashboard', '#saisie-edt')->with('success_edt', 'Cours supprimé.');
    }

    // ── Créer un Enseignant (Chef uniquement) ──
    public function storeEnseignant(Request $request)
    {
        $request->validate([
            'prenom'      => 'required|string|max:100',
            'nom'         => 'required|string|max:100',
            'email'       => 'required|email|unique:users,email|unique:enseignants,email',
            'password'    => 'required|string|min:6',
            'filiere_ids' => 'required|array|min:1',
            'filiere_ids.*' => 'exists:filieres,id',
        ]);

        // 1. Créer l'enseignant
        $enseignant = \App\Models\Enseignant::create([
            'prenom' => $request->prenom,
            'nom'    => $request->nom,
            'email'  => $request->email,
        ]);

        // 2. Créer le compte utilisateur correspondant
        \App\Models\User::create([
            'name'          => $request->prenom . ' ' . $request->nom,
            'email'         => $request->email,
            'password'      => \Illuminate\Support\Facades\Hash::make($request->password),
            'role'          => 'prof',
            'enseignant_id' => $enseignant->id,
            'filiere_id'    => $request->filiere_ids[0],
            'filiere_ids'   => $request->filiere_ids,
        ]);

        return redirect()->route('admin.dashboard', '#enseignants')->with('success_enseignant', 'Enseignant créé et compte utilisateur activé avec succès !');
    }

    // ── Créer une annonce ──
    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'titre'      => 'required|string|max:255',
            'message'    => 'required|string',
            'type'       => 'required|in:info,warning,danger,success',
            'auteur'     => 'nullable|string|max:100',
            'niveau_id'  => 'nullable|exists:niveaux,id',
            'urgent'     => 'nullable',  // La checkbox envoie "1" ou rien — boolean() gère les deux
            'expires_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.dashboard')
                ->withErrors($validator)
                ->withInput();
        }

        $user = auth()->user();
        Annonce::create([
            'titre'      => $request->titre,
            'message'    => $request->message,
            'type'       => $request->type ?? 'info',
            'auteur'     => $request->filled('auteur') ? $request->auteur : null,
            // Rattacher au département de l'utilisateur connecté
            'filiere_id' => $user->filiere_id ?? null,
            'niveau_id'  => $request->niveau_id ?: null,
            'urgent'     => $request->boolean('urgent'),
            'expires_at' => $request->expires_at ?: null,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Annonce publiée avec succès !');
    }

    // ── Supprimer une annonce ──
    public function destroy(Annonce $annonce)
    {
        $annonce->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Annonce supprimée.');
    }

    // ── Créer une modification d'emploi du temps ──
    public function storeModification(Request $request)
    {
        $request->validate([
            'emploi_id'      => 'required|exists:emplois_du_temps,id',
            'nouveau_jour'   => 'required|string',
            'nouvelle_heure' => 'required',
            'motif'          => 'nullable|string|max:255',
        ]);

        $emploi = EmploiDuTemps::with(['matiere', 'filiere', 'niveau'])->findOrFail($request->emploi_id);

        Modification::create([
            'emploi_id'      => $emploi->id,
            'ancien_jour'    => $emploi->jour,
            'ancienne_heure' => $emploi->heure_debut,
            'nouveau_jour'   => $request->nouveau_jour,
            'nouvelle_heure' => $request->nouvelle_heure,
            'motif'          => $request->motif,
            'date_modif'     => now(),
        ]);

        // Notifier les étudiants et l'enseignant concernés (scoped au département)
        try {
            $matiere  = $emploi->matiere?->nom ?? 'cours';
            $ancienCreneau = "{$emploi->jour} à " . substr($emploi->heure_debut, 0, 5);
            $nouveauCreneau = "{$request->nouveau_jour} à " . substr($request->nouvelle_heure, 0, 5);
            $motifTxt = $request->motif ? " Motif : {$request->motif}." : '';

            NotificationService::notifierEtudiants(
                $emploi->filiere_id,
                $emploi->niveau_id,
                '⚠️ Changement d\'horaire',
                "Le cours de {$matiere} prévu le {$ancienCreneau} est déplacé au {$nouveauCreneau}.{$motifTxt}",
                'warning',
                ['emploi_id' => $emploi->id]
            );

            NotificationService::notifierEnseignant(
                $emploi->enseignant_id,
                '⚠️ Modification de votre cours',
                "Votre cours de {$matiere} a été modifié par le chef de programme. Nouveau créneau : {$nouveauCreneau}.{$motifTxt}",
                'warning',
                ['emploi_id' => $emploi->id]
            );
        } catch (\Exception $e) { /* silent */ }

        return redirect()->route('admin.dashboard', '#modifications')
            ->with('success_modif', 'Modification publiée et étudiants notifiés avec succès.');
    }

    // ── Supprimer une modification ──
    public function destroyModification(Modification $modification)
    {
        $modification->delete();
        return redirect()->route('admin.dashboard', '#modifications')
            ->with('success_modif', 'Modification supprimée.');
    }

    // ── Créer une Matière (Chef uniquement) ──
    public function storeMatiere(Request $request)
    {
        $filiereId = auth()->user()->filiere_id;

        $validator = Validator::make($request->all(), [
            'nom'       => 'required|string|max:150',
            // Code unique UNIQUEMENT dans la même filière
            'code'      => [
                'required', 'string', 'max:50',
                Rule::unique('matieres', 'code')->where('filiere_id', $filiereId),
            ],
            'credits'   => 'required|integer|min:1',
            'niveau_id' => 'required|exists:niveaux,id',
            'semestre'  => 'required|string|in:S1,S2,S3,S4,S5,S6',
        ], [
            'nom.required'      => 'Le nom de la matière est obligatoire.',
            'code.required'     => 'Le code de la matière est obligatoire.',
            'code.unique'       => 'Ce code existe déjà pour une matière de votre département.',
            'credits.required'  => 'Le nombre de crédits est obligatoire.',
            'niveau_id.required'=> 'Veuillez sélectionner un niveau.',
            'semestre.required' => 'Veuillez sélectionner un semestre.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.dashboard', '#matieres')
                ->withErrors($validator, 'matiere')
                ->withInput();
        }

        \App\Models\Matiere::create([
            'nom'        => $request->nom,
            'code'       => $request->code,
            'credits'    => $request->credits,
            'filiere_id' => $filiereId,
            'niveau_id'  => $request->niveau_id,
            'semestre'   => $request->semestre,
        ]);

        return redirect()->route('admin.dashboard', '#matieres')->with('success_matiere', 'Matière créée avec succès !');
    }
}
