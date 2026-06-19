<?php

namespace App\Http\Controllers;

use App\Mail\ResetCodeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Show form where user enters their email to receive a reset code.
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Validate email, generate a 6‑digit code, store it and send it by e‑mail.
     */
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email = $request->email;
        // Generate a 6‑digit numeric code
        $code = random_int(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(15);

        // Store (or replace) the record in password_resets table
        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            [
                'token'      => $code,
                'created_at' => $expiresAt,
            ]
        );

        // Send the code via e‑mail
        Mail::to($email)->send(new ResetCodeMail($code));

        return redirect()->route('password.reset', ['token' => $code])
                         ->with('status', 'Un code de vérification a été envoyé à votre e‑mail.');
    }

    /**
     * Show the form where the user can enter the code and new password.
     */
    public function showResetForm($token)
    {
        // The token is just the 6‑digit code; we keep it in the URL for simplicity.
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Verify the code and reset the password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:users,email',
            'token'    => 'required|digits:6',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Retrieve the stored reset record
        $record = DB::table('password_resets')
                    ->where('email', $request->email)
                    ->where('token', $request->token)
                    ->first();

        if (!$record) {
            return back()->withErrors(['code' => 'Le code est invalide ou expiré.']);
        }

        // Check expiration (15 minutes from generation)
        if (Carbon::parse($record->created_at)->lt(Carbon::now())) {
            return back()->withErrors(['code' => 'Le code a expiré.']);
        }

        // Update user password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Clean up the reset record
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Votre mot de passe a été mis à jour. Vous pouvez maintenant vous connecter.');
    }
}
?>
