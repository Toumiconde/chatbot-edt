<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialiser le mot de passe</title>
    <meta name="description" content="Formulaire de demande de réinitialisation du mot de passe.">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #1e1e2f, #2a2a40); color: #fff; display: flex; justify-content: center; align-items: center; height: 100vh; margin:0;}
        .card {background: rgba(255,255,255,0.08); backdrop-filter: blur(10px); border-radius: 12px; padding: 2rem; width: 360px;}
        h1 {font-size: 1.5rem; margin-bottom: 1rem; text-align:center;}
        .field {margin-bottom: 1rem;}
        label {display:block; margin-bottom:.5rem; font-weight:600;}
        .input-wrap {position:relative;}
        input, select {width:100%; padding: .75rem .5rem .75rem 2.5rem; border:none; border-radius:6px; background:rgba(255,255,255,0.12); color:#fff;}
        input:focus, select:focus {outline:none; background:rgba(255,255,255,0.2);}
        i[data-lucide] {position:absolute; left:.75rem; top:50%; transform:translateY(-50%); color:#bbb;}
        .btn {width:100%; padding:.75rem; background:#4e9af1; border:none; border-radius:6px; color:#fff; font-weight:600; cursor:pointer; transition:background .2s;}
        .btn:hover {background:#3a7dcf;}
        .error {color:#ff6b6b; font-size:.9rem; margin-top:.25rem;}
        .status {color:#71ff71; font-size:.9rem; margin-bottom:.5rem; text-align:center;}
        a {color:#4e9af1; text-decoration:none;}
    </style>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div class="card">
        <h1>Mot de passe oublié</h1>
        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="field">
                <label for="email">Adresse e‑mail</label>
                <div class="input-wrap">
                    <i data-lucide="mail"></i>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus placeholder="votre@email.com">
                </div>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn">Envoyer le code</button>
        </form>
        <div style="margin-top:1rem; text-align:center;">
            <a href="{{ route('login') }}">← Retour à la connexion</a>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>
