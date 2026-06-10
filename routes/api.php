<?php

use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\EmploiDuTempsController;
use App\Http\Controllers\AlerteController;
use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Route;

Route::get('/filieres',            [ChatbotController::class, 'getFilieres']);
Route::get('/niveaux/{filiereId}', [ChatbotController::class, 'getNiveaux']);
Route::get('/enseignants',         [ChatbotController::class, 'getEnseignants']);

Route::get('/edt/jour',            [EmploiDuTempsController::class, 'parJour']);
Route::get('/edt/semaine',         [EmploiDuTempsController::class, 'parSemaine']);
Route::get('/edt/enseignant',      [EmploiDuTempsController::class, 'parEnseignant']);

Route::get('/alertes',             [AlerteController::class, 'index']);

Route::get('/pdf',                 [PDFController::class, 'generer']);
