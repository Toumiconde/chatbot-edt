<?php

namespace App\Http\Controllers;

use App\Models\EmploiDuTemps;
use App\Models\Filiere;
use App\Models\Niveau;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function generer(Request $request)
    {
        $request->validate([
            'filiere_id' => 'required|integer|exists:filieres,id',
            'niveau_id'  => 'required|integer|exists:niveaux,id',
        ]);

        $filiere = Filiere::findOrFail($request->filiere_id);
        $niveau  = Niveau::findOrFail($request->niveau_id);

        $query = EmploiDuTemps::with(['matiere', 'enseignant', 'salle'])
            ->where('filiere_id', $request->filiere_id)
            ->where('niveau_id', $request->niveau_id);

        if ($request->filled('jour')) {
            $query->where('jour', $request->jour);
            $titre = "EDT {$filiere->code} - {$niveau->libelle} - {$request->jour}";
        } else {
            $titre = "EDT {$filiere->code} - {$niveau->libelle} - Semaine complète";
        }

        $cours = $query->orderBy('jour')->orderBy('heure_debut')->get();

        $pdf = Pdf::loadView('pdf.emploi_du_temps', compact('cours', 'filiere', 'niveau', 'titre'));

        return $pdf->download('emploi_du_temps.pdf');
    }
}
