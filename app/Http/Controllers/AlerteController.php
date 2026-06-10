<?php

namespace App\Http\Controllers;

use App\Models\Modification;
use Illuminate\Http\Request;

class AlerteController extends Controller
{
    public function index()
    {
        $alertes = Modification::with(['emploi.matiere', 'emploi.filiere', 'emploi.niveau'])
            ->where('date_modif', '>=', now()->subHours(48))
            ->orderBy('date_modif', 'desc')
            ->get();

        return response()->json($alertes);
    }
}
