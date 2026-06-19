<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialiser le mot de passe</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {box-sizing:border-box;margin:0;padding:0;}
        body {font-family:'Inter',sans-serif;background:linear-gradient(135deg,#0D1B2A,#1B4FD8,#7C3AED);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;}
        .card {background:rgba(255,255,255,.08);backdrop-filter:blur(12px);border-radius:20px;padding:2.5rem;max-width:420px;width:100%;box-shadow:0 20px 40px rgba(0,0,0,.3);}
        h1 {color:#fff;font-size:1.8rem;margin-bottom:1rem;text-align:center;}
        .field {margin-bottom:1.2rem;}
        label {display:block;color:#94A3B8;font-weight:600;margin-bottom:.4rem;}
        .input-wrap {position:relative;}
        .input-wrap i[data-lucide] {position:absolute;left:.8rem;top:50%;transform:translateY(-50%);color:#475569;}
        input {width:100%;padding:.75rem 1rem .75rem 2.5rem;border:none;border-radius:8px;background:#162032;color:#E2E8F0;outline:none;}
        input:focus {border:1px solid #7C3AED;background:#1a2b3c;}
        .btn {width:100%;padding:.75rem;background:#4e9af1;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;transition:.2s;}
        .btn:hover {background:#3a7dcf;}
        .error {color:#ff6b6b;font-size:.9rem;margin-top:.3rem;}
        .status {color:#71ff71;font-size:.9rem;margin-bottom:.5rem;text-align:center;}
        .back-link {margin-top:1rem;text-align:center;}
        .back-link a {color:#60A5FA;text-decoration:none;}
    </style>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div class="card">
        <h1>Réinitialiser le mot de passe</h1>
        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="field">
                <label for="email">Adresse e‑mail</label>
                <div class="input-wrap">
                    <i data-lucide="mail"></i>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="votre@email.com">
                </div>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="field">
                <label>Code de vérification</label>
                <div class="code-display" style="background:#162032;color:#E2E8F0;padding:.75rem 1rem;border-radius:8px;font-size:1.2rem;text-align:center;">
                    {{ $token }}
                </div>
                <input type="hidden" name="token" value="{{ $token }}">
            </div>
            <div class="field">
                <label for="password">Nouveau mot de passe</label>
                <div class="input-wrap">
                    <i data-lucide="lock"></i>
                    <input type="password" name="password" id="password" required placeholder="••••••••">
                </div>
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="field">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <div class="input-wrap">
                    <i data-lucide="lock"></i>
                    <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="••••••••">
                </div>
            </div>
            <button type="submit" class="btn">Mettre à jour le mot de passe</button>
        </form>
        <div class="back-link">
            <a href="{{ route('login') }}">← Retour à la connexion</a>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>
