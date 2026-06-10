<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use App\Models\Enseignant;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chatbot.index');
    }

    public function getFilieres()
    {
        return response()->json(Filiere::all());
    }

    public function getNiveaux($filiereId)
    {
        $filiere = Filiere::with('niveaux')->findOrFail($filiereId);
        return response()->json($filiere->niveaux);
    }

    public function getEnseignants()
    {
        return response()->json(Enseignant::orderBy('nom')->get());
    }
}
