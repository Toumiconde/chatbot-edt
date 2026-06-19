<?php

use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ChatbotAIController;
use App\Http\Controllers\EmploiDuTempsController;
use App\Http\Controllers\AlerteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ProgressionController;
use Illuminate\Support\Facades\Route;

Route::get('/filieres',            [ChatbotController::class, 'getFilieres']);
Route::get('/niveaux/{filiereId}', [ChatbotController::class, 'getNiveaux']);
Route::get('/enseignants',         [ChatbotController::class, 'getEnseignants']);

Route::get('/edt/jour',            [EmploiDuTempsController::class, 'parJour']);
Route::get('/edt/semaine',         [EmploiDuTempsController::class, 'parSemaine']);
Route::get('/edt/enseignant',      [EmploiDuTempsController::class, 'parEnseignant']);

Route::get('/alertes',             [AlerteController::class, 'index']);

Route::get('/pdf',                 [PDFController::class, 'generer']);

Route::post('/chatbot/ai',         [ChatbotAIController::class, 'ask']);

// Notifications (utilisateur connecté)
Route::get('/notifications',               [NotificationController::class, 'index']);
Route::put('/notifications/read-all',      [NotificationController::class, 'readAll']);
Route::put('/notifications/{id}/read',     [NotificationController::class, 'read']);

// Progression d'année (chef uniquement)
Route::get('/chef/niveaux',                [ProgressionController::class, 'niveaux']);
Route::post('/chef/progression',           [ProgressionController::class, 'store']);

Route::get('/chatbot/recent-info', function(\Illuminate\Http\Request $request) {
    $filiereId = $request->get('filiere_id');
    $niveauId = $request->get('niveau_id');

    $response = [];

    // 1. Annonces récentes
    $annonces = \App\Models\Annonce::where(function($q) use ($filiereId) {
            if ($filiereId) {
                $q->where('filiere_id', $filiereId)->orWhereNull('filiere_id');
            }
        })
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    foreach ($annonces as $a) {
        $response[] = [
            'type' => 'annonce',
            'date' => $a->created_at->toISOString(),
            'message' => "Le prof <strong>" . ($a->auteur ?? 'de votre département') . "</strong> a dit : \"" . $a->message . "\" (Annonce: " . $a->titre . ")",
        ];
    }

    // 2. EDT récents (créés par le Chef de programme)
    $query = \App\Models\EmploiDuTemps::with(['matiere', 'enseignant', 'filiere']);
    if ($filiereId) {
        $query->where('filiere_id', $filiereId);
    }
    if ($niveauId) {
        $query->where('niveau_id', $niveauId);
    }
    $edts = $query->orderBy('created_at', 'desc')->take(5)->get();
    foreach ($edts as $e) {
        $filiereNom = $e->filiere ? $e->filiere->code : '';
        $response[] = [
            'type' => 'edt',
            'date' => $e->created_at->toISOString(),
            'message' => "Votre chef de programme " . ($filiereNom ? "de " . $filiereNom : "") . " a planifié le cours de <strong>" . ($e->matiere->nom ?? 'Inconnu') . "</strong> le " . $e->jour . " de " . substr($e->heure_debut, 0, 5) . " à " . substr($e->heure_fin, 0, 5) . " avec " . ($e->enseignant ? $e->enseignant->prenom . " " . $e->enseignant->nom : 'un enseignant') . ".",
        ];
    }

    // 3. Modifications récentes
    $modifsQuery = \App\Models\Modification::with(['emploi.matiere', 'emploi.filiere']);
    if ($filiereId) {
        $modifsQuery->whereHas('emploi', function($q) use ($filiereId) {
            $q->where('filiere_id', $filiereId);
        });
    }
    $modifs = $modifsQuery->orderBy('created_at', 'desc')->take(5)->get();
    foreach ($modifs as $m) {
        if (!$m->emploi) continue;
        $mat = $m->emploi->matiere->nom ?? 'cours';
        $response[] = [
            'type' => 'modification',
            'date' => $m->created_at->toISOString(),
            'message' => "Il y a un changement d'horaire : le cours de <strong>" . $mat . "</strong> prévu le " . $m->ancien_jour . " à " . substr($m->ancienne_heure, 0, 5) . " est déplacé au " . $m->nouveau_jour . " à " . substr($m->nouvelle_heure, 0, 5) . " (Motif: " . ($m->motif ?? 'non précisé') . ").",
        ];
    }

    // Trier par date décroissante
    usort($response, function($a, $b) {
        return strcmp($b['date'], $a['date']);
    });

    return response()->json(array_slice($response, 0, 10));
});
