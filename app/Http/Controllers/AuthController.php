<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $role = Auth::user()->role;
            if ($role === 'etudiant' || $role === 'chef' || $role === 'prof') return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'etudiant') {
                return redirect()->route('dashboard');
            }

            if ($user->role === 'prof') {
                return redirect()->route('dashboard');
            }

            // chef de programme
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis sont incorrects.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // ── Afficher l'inscription ──
    public function showRegister()
    {
        $filieres = \App\Models\Filiere::orderBy('nom')->get();
        $niveaux  = \App\Models\Niveau::all()->unique('libelle')->values();
        return view('auth.register', compact('filieres', 'niveaux'));
    }

    // ── Gérer l'inscription ──
    public function register(Request $request)
    {
        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:etudiant,prof',
        ];

        if ($request->role === 'etudiant') {
            $rules['filiere_id'] = 'required|exists:filieres,id';
            $rules['niveau_id']  = 'required|exists:niveaux,id';
        } else {
            $rules['filiere_ids'] = 'required|array|min:1';
            $rules['filiere_ids.*'] = 'exists:filieres,id';
        }

        $request->validate($rules);

        $userData = [
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role'     => $request->role,
        ];

        if ($request->role === 'etudiant') {
            $userData['filiere_id'] = $request->filiere_id;
            $userData['niveau_id']  = $request->niveau_id;
        } else {
            // Créer l'enseignant correspondant
            $parts = explode(' ', trim($request->name));
            $nom = array_pop($parts);
            $prenom = implode(' ', $parts) ?: $nom;

            $enseignant = \App\Models\Enseignant::create([
                'prenom' => $prenom,
                'nom'    => $nom,
                'email'  => $request->email,
            ]);

            $userData['enseignant_id'] = $enseignant->id;
            $userData['filiere_ids']   = $request->filiere_ids;
            // set primary filiere_id to the first selected one
            $userData['filiere_id']    = $request->filiere_ids[0];
        }

        $user = \App\Models\User::create($userData);

        Auth::login($user);

        // Redirection selon le rôle après inscription
        if ($user->role === 'etudiant' || $user->role === 'chef' || $user->role === 'prof') {
            return redirect()->route('dashboard');
        }
    }

    // ── Page de profil ──
    public function showProfile()
    {
        $user = Auth::user();
        $filieres = \App\Models\Filiere::orderBy('nom')->get();
        $niveaux  = \App\Models\Niveau::all()->unique('libelle')->values();
        return view('dashboard.profile', compact('user', 'filieres', 'niveaux'));
    }

    // ── Mise à jour du profil ──
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }

        if ($user->role === 'etudiant') {
            $rules['filiere_id'] = 'required|exists:filieres,id';
            $rules['niveau_id']  = 'required|exists:niveaux,id';
        }

        $request->validate($rules);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        if ($user->role === 'etudiant') {
            $user->filiere_id = $request->filiere_id;
            $user->niveau_id  = $request->niveau_id;
        }

        // Si c'est un prof, on met aussi à jour la table Enseignants pour que le nom change directement dans l'EDT
        if ($user->role === 'prof' && $user->enseignant_id) {
            $parts = explode(' ', trim($request->name));
            $nom = array_pop($parts);
            $prenom = implode(' ', $parts) ?: $nom;

            $enseignant = \App\Models\Enseignant::find($user->enseignant_id);
            if ($enseignant) {
                $enseignant->update([
                    'prenom' => $prenom,
                    'nom'    => $nom,
                    'email'  => $request->email,
                ]);
            }
        }

        $user->save();

        return back()->with('success', 'Votre profil a été mis à jour avec succès !');
    }
}
