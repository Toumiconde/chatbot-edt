<?php

namespace App\Services;

use App\Models\EmploiDuTemps;
use App\Models\NotificationApp;
use App\Models\User;

class NotificationService
{
    // Notifier tous les étudiants d'une filière (et optionnellement d'un niveau)
    public static function notifierEtudiants(
        int $filiereId, ?int $niveauId,
        string $titre, string $message,
        string $type = 'warning', array $data = []
    ): int {
        $query = User::where('role', 'etudiant')->where('filiere_id', $filiereId);
        if ($niveauId) $query->where('niveau_id', $niveauId);

        $count = 0;
        foreach ($query->get() as $etudiant) {
            NotificationApp::create([
                'user_id' => $etudiant->id,
                'type'    => $type,
                'titre'   => $titre,
                'message' => $message,
                'data'    => $data,
            ]);
            $count++;
        }
        return $count;
    }

    // Notifier un enseignant via son enseignant_id
    public static function notifierEnseignant(
        int $enseignantId,
        string $titre, string $message,
        string $type = 'info', array $data = []
    ): void {
        $profUser = User::where('enseignant_id', $enseignantId)->first();
        if (!$profUser) return;

        NotificationApp::create([
            'user_id' => $profUser->id,
            'type'    => $type,
            'titre'   => $titre,
            'message' => $message,
            'data'    => $data,
        ]);
    }

    // Notifier tous les enseignants qui ont des cours dans une filière
    public static function notifierEnseignantsFiliere(
        int $filiereId,
        string $titre, string $message,
        string $type = 'info', array $data = []
    ): void {
        $enseignantIds = EmploiDuTemps::where('filiere_id', $filiereId)
            ->pluck('enseignant_id')
            ->unique();

        $profs = User::where('role', 'prof')
            ->whereIn('enseignant_id', $enseignantIds)
            ->get();

        foreach ($profs as $prof) {
            NotificationApp::create([
                'user_id' => $prof->id,
                'type'    => $type,
                'titre'   => $titre,
                'message' => $message,
                'data'    => $data,
            ]);
        }
    }

    // Notifier un utilisateur précis par son user_id
    public static function notifierUser(
        int $userId,
        string $titre, string $message,
        string $type = 'info', array $data = []
    ): void {
        NotificationApp::create([
            'user_id' => $userId,
            'type'    => $type,
            'titre'   => $titre,
            'message' => $message,
            'data'    => $data,
        ]);
    }
}
