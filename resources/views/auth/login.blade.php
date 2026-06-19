<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion — UGANC</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #0D1B2A 0%, #1B4FD8 50%, #7C3AED 100%);
      min-height: 100vh;
      display: flex; align-items: center; justify-content: center;
      padding: 20px;
    }

    .login-card {
      width: 100%; max-width: 420px;
      background: rgba(15,26,42,.85);
      backdrop-filter: blur(40px);
      border: 1px solid rgba(255,255,255,.1);
      border-radius: 24px;
      padding: 40px 32px;
      box-shadow: 0 40px 80px rgba(0,0,0,.5);
    }

    .logo-wrap {
      text-align: center; margin-bottom: 28px;
    }
    .logo-icon {
      width: 60px; height: 60px;
      background: linear-gradient(135deg, #1B4FD8, #7C3AED);
      border-radius: 18px;
      display: inline-flex; align-items: center; justify-content: center;
      margin-bottom: 14px;
      box-shadow: 0 8px 30px rgba(27,79,216,.4);
    }
    .logo-icon svg { color: #fff; }

    .logo-wrap h1 {
      color: #fff; font-size: 20px; font-weight: 800;
      letter-spacing: -.02em; margin-bottom: 4px;
    }
    .logo-wrap p { color: rgba(255,255,255,.45); font-size: 12.5px; }

    .role-badges {
      display: flex; justify-content: center; gap: 8px;
      margin-bottom: 24px;
    }
    .role-badge {
      background: rgba(255,255,255,.06);
      border: 1px solid rgba(255,255,255,.1);
      border-radius: 50px; padding: 4px 12px;
      font-size: 10.5px; font-weight: 600;
      display: flex; align-items: center; gap: 4px;
    }
    .role-badge.chef  { color: #FCD34D; border-color: rgba(252,211,77,.25); }
    .role-badge.prof  { color: #60A5FA; border-color: rgba(96,165,250,.25); }
    .role-badge.etu   { color: #6EE7B7; border-color: rgba(110,231,183,.25); }

    .field { margin-bottom: 18px; }
    .field label {
      display: block; font-size: 12px; font-weight: 600;
      color: #94A3B8; margin-bottom: 6px;
      text-transform: uppercase; letter-spacing: .04em;
    }
    .field .input-wrap {
      position: relative;
    }
    .field .input-wrap svg {
      position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
      color: #475569; width: 16px; height: 16px;
    }
    .field input {
      width: 100%; padding: 12px 14px 12px 42px;
      background: #162032;
      border: 1.5px solid rgba(255,255,255,.08);
      border-radius: 12px;
      color: #E2E8F0; font-size: 14px; font-family: 'Inter', sans-serif;
      outline: none; transition: border-color .2s;
    }
    .field input:focus { border-color: #7C3AED; }
    .field input::placeholder { color: #334155; }

    .remember-row {
      display: flex; align-items: center; gap: 8px;
      margin-bottom: 20px;
    }
    .remember-row input[type=checkbox] {
      width: 16px; height: 16px; accent-color: #7C3AED; cursor: pointer;
    }
    .remember-row label { color: #94A3B8; font-size: 12.5px; cursor: pointer; }

    .login-btn {
      width: 100%; padding: 13px;
      background: linear-gradient(135deg, #1B4FD8, #7C3AED);
      color: #fff; border: none; border-radius: 12px;
      font-size: 14px; font-weight: 700; cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      transition: opacity .2s, transform .2s;
    }
    .login-btn:hover { opacity: .9; transform: translateY(-1px); }

    .error-box {
      background: rgba(239,68,68,.12);
      border: 1px solid rgba(239,68,68,.25);
      border-radius: 12px; padding: 10px 14px;
      color: #FCA5A5; font-size: 12.5px; font-weight: 500;
      margin-bottom: 18px;
      display: flex; align-items: center; gap: 8px;
    }

    .back-link {
      text-align: center; margin-top: 20px;
    }
    .back-link a {
      color: rgba(255,255,255,.4); font-size: 12px; text-decoration: none;
      display: inline-flex; align-items: center; gap: 4px;
      transition: color .2s;
    }
    .back-link a:hover { color: rgba(255,255,255,.7); }

    .demo-accounts {
      margin-top: 24px; padding-top: 20px;
      border-top: 1px solid rgba(255,255,255,.06);
    }
    .demo-title {
      font-size: 11px; font-weight: 700; color: #475569;
      text-transform: uppercase; letter-spacing: .06em;
      margin-bottom: 10px; text-align: center;
    }
    .demo-row {
      display: flex; align-items: center; gap: 8px;
      background: rgba(255,255,255,.04);
      border: 1px solid rgba(255,255,255,.06);
      border-radius: 10px; padding: 8px 12px;
      margin-bottom: 6px; cursor: pointer;
      transition: background .2s;
    }
    .demo-row:hover { background: rgba(255,255,255,.08); }
    .demo-role { font-size: 11px; font-weight: 700; min-width: 90px; }
    .demo-email { font-size: 11.5px; color: #64748B; flex: 1; }
    .demo-fill { font-size: 10px; color: #7C3AED; font-weight: 600; }
  </style>
</head>
<body>

<div class="login-card">
  <div class="logo-wrap">
    <div class="logo-icon">
      <i data-lucide="graduation-cap" style="width:28px;height:28px"></i>
    </div>
    <h1>Chatbot EDT — UGANC</h1>
    <p>Connectez-vous pour accéder à votre espace</p>
  </div>

  <div class="role-badges">
    <span class="role-badge chef">👑 Chef de prog.</span>
    <span class="role-badge prof">👨‍🏫 Professeur</span>
    <span class="role-badge etu">🎓 Étudiant</span>
  </div>

  @if ($errors->any())
    <div class="error-box">
      <i data-lucide="alert-triangle" style="width:15px;height:15px;flex-shrink:0"></i>
      {{ $errors->first() }}
    </div>
  @endif

  <form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="field">
      <label>Adresse email</label>
      <div class="input-wrap">
        <i data-lucide="mail"></i>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="votre@email.com" required autofocus>
      </div>
    </div>

    <div class="field">
      <label>Mot de passe</label>
      <div class="input-wrap">
        <i data-lucide="lock"></i>
        <input type="password" name="password" placeholder="••••••••" required>
      </div>
    </div>

    <div class="remember-row">
      <input type="checkbox" name="remember" id="remember">
      <label for="remember">Se souvenir de moi</label>
    </div>

    <button type="submit" class="login-btn">
      <i data-lucide="log-in" style="width:16px;height:16px"></i>
      Se connecter
    </button>
  </form>

        <div class="back-link" style="margin-top:8px; text-align:right;">
          <a href="{{ route('password.request') }}" style="color:#60A5FA; font-weight:600;">Mot de passe oublié ?</a>
        </div>

  <div class="back-link">
    <a href="/chatbot">
      <i data-lucide="arrow-left" style="width:12px;height:12px"></i>
      Retour au chatbot
    </a>
  </div>

  <!-- Comptes de démonstration -->
  <div class="demo-accounts">
    <div class="demo-title">Comptes de démonstration</div>
    <div class="demo-row" onclick="fillLogin('chef.ntic@uganc.edu.gn','password')">
      <span class="demo-role" style="color:#FCD34D">👑 Chef NTIC</span>
      <span class="demo-email">chef.ntic@uganc.edu.gn</span>
      <span class="demo-fill">Remplir →</span>
    </div>
    <div class="demo-row" onclick="fillLogin('chef.dl@uganc.edu.gn','password')">
      <span class="demo-role" style="color:#F59E0B">👑 Chef DL</span>
      <span class="demo-email">chef.dl@uganc.edu.gn</span>
      <span class="demo-fill">Remplir →</span>
    </div>
    <div class="demo-row" onclick="fillLogin('prof@uganc.edu.gn','password')">
      <span class="demo-role" style="color:#60A5FA">👨‍🏫 Prof</span>
      <span class="demo-email">prof@uganc.edu.gn</span>
      <span class="demo-fill">Remplir →</span>
    </div>
    <div class="demo-row" onclick="fillLogin('etudiant@uganc.edu.gn','password')">
      <span class="demo-role" style="color:#6EE7B7">🎓 Étudiant</span>
      <span class="demo-email">etudiant@uganc.edu.gn</span>
      <span class="demo-fill">Remplir →</span>
    </div>
  </div>
<div class="back-link" style="margin-top:12px; text-align:center;">
    <a href="{{ route('register') }}" style="color:#60A5FA; font-weight:600;">Créer un compte</a>
</div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => lucide.createIcons());

  function fillLogin(email, pwd) {
    document.querySelector('input[name=email]').value = email;
    document.querySelector('input[name=password]').value = pwd;
  }
</script>
</body>
</html>
