<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use App\Models\Enseignant;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function index()
    {
        // Le chatbot est accessible à tous les rôles via le lien "Chatbot" du menu
        return view('chatbot.index');
    }

    public function getFilieres()
    {
        return response()->json(Filiere::all());
    }

    public function getNiveaux($filiereId)
    {
        // On retourne tous les niveaux de manière unique (L1, L2, L3)
        // puisque le filtre filiere est maintenant géré implicitement par le combo Filiere + Niveau
        $niveaux = \App\Models\Niveau::all()->unique('libelle')->values();
        return response()->json($niveaux);
    }

    public function getEnseignants()
    {
        return response()->json(Enseignant::orderBy('nom')->get());
    }
}
