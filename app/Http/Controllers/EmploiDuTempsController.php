<?php

namespace App\Http\Controllers;

use App\Models\EmploiDuTemps;
use Illuminate\Http\Request;

class EmploiDuTempsController extends Controller
{
    public function parJour(Request $request)
    {
        $request->validate([
            'filiere_id' => 'required|integer|exists:filieres,id',
            'niveau_id'  => 'required|integer|exists:niveaux,id',
            'jour'       => 'required|in:Lundi,Mardi,Mercredi,Jeudi,Vendredi',
        ]);

        $cours = EmploiDuTemps::with(['matiere', 'enseignant', 'salle'])
            ->where('filiere_id', $request->filiere_id)
            ->where('niveau_id', $request->niveau_id)
            ->where('jour', $request->jour)
            ->orderBy('heure_debut')
            ->get();

        return response()->json($cours);
    }

    public function parSemaine(Request $request)
    {
        $request->validate([
            'filiere_id' => 'required|integer|exists:filieres,id',
            'niveau_id'  => 'required|integer|exists:niveaux,id',
        ]);

        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
        $planning = [];

        foreach ($jours as $jour) {
            $planning[$jour] = EmploiDuTemps::with(['matiere', 'enseignant', 'salle'])
                ->where('filiere_id', $request->filiere_id)
                ->where('niveau_id', $request->niveau_id)
                ->where('jour', $jour)
                ->orderBy('heure_debut')
                ->get();
        }

        return response()->json($planning);
    }

    public function parEnseignant(Request $request)
    {
        $request->validate([
            'enseignant_id' => 'required|integer|exists:enseignants,id',
        ]);

        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
        $planning = [];

        foreach ($jours as $jour) {
            $planning[$jour] = EmploiDuTemps::with(['matiere', 'filiere', 'niveau', 'salle'])
                ->where('enseignant_id', $request->enseignant_id)
                ->where('jour', $jour)
                ->orderBy('heure_debut')
                ->get();
        }

        return response()->json($planning);
    }
}
