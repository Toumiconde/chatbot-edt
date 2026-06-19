<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    // Forgot password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetCode'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

// Routes protégées par authentification
Route::middleware('auth')->group(function () {
    // Redirection automatique à la racine vers le tableau de bord selon le rôle
    Route::get('/', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->role === 'etudiant' || $user->role === 'chef' || $user->role === 'prof') {
            return redirect()->route('dashboard');
        }
    });

    // Chatbot
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot');
    // Profile Management
    Route::get('/profil', [AuthController::class, 'showProfile'])->name('profile');
    Route::post('/profil', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Tableau de bord public (Étudiants, Profs, Chef)
    Route::get('/tableau-de-bord', [DashboardController::class, 'index'])->name('dashboard');

    // Admin (Chef et Profs)
    Route::middleware('role:chef,prof')->group(function () {
        Route::get('/admin/dashboard',          [DashboardController::class, 'adminIndex'])->name('admin.dashboard');
        Route::post('/admin/annonces',          [DashboardController::class, 'store'])->name('admin.annonces.store');
        Route::delete('/admin/annonces/{annonce}', [DashboardController::class, 'destroy'])->name('admin.annonces.destroy');
        Route::post('/admin/modifications',              [DashboardController::class, 'storeModification'])->name('admin.modifications.store');
        Route::delete('/admin/modifications/{modification}', [DashboardController::class, 'destroyModification'])->name('admin.modifications.destroy');

        // Chef uniquement
        Route::middleware('role:chef')->group(function () {
            Route::post('/admin/edt', [DashboardController::class, 'storeEdt'])->name('admin.edt.store');
            Route::delete('/admin/edt/{emploi}', [DashboardController::class, 'destroyEdt'])->name('admin.edt.destroy');
            Route::post('/admin/enseignants', [DashboardController::class, 'storeEnseignant'])->name('admin.enseignants.store');
            Route::post('/admin/matieres', [DashboardController::class, 'storeMatiere'])->name('admin.matieres.store');
        });
    });
});
    // ── Routes professeurs (vue et édition de leurs cours) ──
    Route::middleware('auth')->group(function () {
        Route::middleware('role:prof')->group(function () {
            Route::get('/professor/cours', [DashboardController::class, 'professorIndex'])->name('professor.cours');
            // Modifications désactivées pour des raisons de sécurité
            // Route::get('/professor/cours/{id}/edit', [DashboardController::class, 'professorEdit'])->name('professor.cours.edit');
            // Route::put('/professor/cours/{id}', [DashboardController::class, 'professorUpdate'])->name('professor.cours.update');
        });
    });

// API JSON pour le tableau de bord (polling)
Route::middleware('auth')->group(function () {
    Route::get('/board/annonces',      [DashboardController::class, 'apiAnnonces'])->name('board.annonces');
    Route::get('/board/modifications', [DashboardController::class, 'apiModifications'])->name('board.modifications');
});
