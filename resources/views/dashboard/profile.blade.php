<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mon Profil — UGANC</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Inter', sans-serif;
      background: #0B0F19;
      color: #E2E8F0;
      min-height: 100vh;
      display: flex; flex-direction: column;
    }

    /* ═══ Glassmorphism Header ═══ */
    .profile-header {
      background: rgba(15,23,42,.7);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(255,255,255,.06);
      padding: 16px 40px;
      display: flex; align-items: center; justify-content: space-between;
      position: sticky; top: 0; z-index: 100;
    }
    .header-logo {
      display: flex; align-items: center; gap: 10px;
    }
    .logo-box {
      width: 38px; height: 38px;
      background: linear-gradient(135deg, #1B4FD8, #7C3AED);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      box-shadow: 0 4px 15px rgba(27,79,216,.3);
    }
    .logo-box svg { color: #fff; }
    .header-title h1 {
      font-size: 16px; font-weight: 800; color: #fff;
    }
    .header-title p {
      font-size: 11px; color: #94A3B8;
    }

    .nav-links a {
      color: #94A3B8; text-decoration: none; font-size: 13px; font-weight: 600;
      display: inline-flex; align-items: center; gap: 6px;
      padding: 8px 16px; border-radius: 8px;
      transition: background .2s, color .2s;
    }
    .nav-links a:hover {
      background: rgba(255,255,255,.04); color: #fff;
    }

    /* ═══ Main container ═══ */
    .main-container {
      max-width: 600px; width: 90%;
      margin: 40px auto;
      background: rgba(15,23,42,.65);
      border: 1px solid rgba(255,255,255,.08);
      border-radius: 20px;
      padding: 32px;
      box-shadow: 0 20px 50px rgba(0,0,0,.3);
    }

    .section-title {
      font-size: 18px; font-weight: 800; color: #fff;
      margin-bottom: 24px; display: flex; align-items: center; gap: 8px;
    }

    .field { margin-bottom: 18px; }
    .field label {
      display: block; font-size: 12px; font-weight: 600;
      color: #94A3B8; margin-bottom: 6px;
      text-transform: uppercase; letter-spacing: .04em;
    }
    .field input, .field select {
      width: 100%; padding: 12px 14px;
      background: #101625;
      border: 1.5px solid rgba(255,255,255,.08);
      border-radius: 12px;
      color: #E2E8F0; font-size: 14px; font-family: 'Inter', sans-serif;
      outline: none; transition: border-color .2s;
    }
    .field input:focus, .field select:focus { border-color: #7C3AED; }

    .checkbox-group {
      display: flex; gap: 16px; margin-top: 8px;
    }
    .checkbox-item {
      display: flex; align-items: center; gap: 8px;
      background: rgba(255,255,255,.03);
      padding: 10px 14px; border-radius: 10px;
      border: 1px solid rgba(255,255,255,.06);
      flex: 1;
    }
    .checkbox-item input {
      width: 16px; height: 16px; accent-color: #7C3AED;
    }
    .checkbox-item span {
      font-size: 13px; color: #E2E8F0; font-weight: 500;
    }

    .alert-success {
      background: rgba(16,185,129,.12);
      border: 1px solid rgba(16,185,129,.25);
      border-radius: 12px; padding: 12px 16px;
      color: #A7F3D0; font-size: 13.5px;
      margin-bottom: 20px;
      display: flex; align-items: center; gap: 8px;
    }

    .alert-error {
      background: rgba(239,68,68,.12);
      border: 1px solid rgba(239,68,68,.25);
      border-radius: 12px; padding: 12px 16px;
      color: #FCA5A5; font-size: 13.5px;
      margin-bottom: 20px;
      display: flex; align-items: center; gap: 8px;
    }

    .submit-btn {
      width: 100%; padding: 13px;
      background: linear-gradient(135deg, #1B4FD8, #7C3AED);
      color: #fff; border: none; border-radius: 12px;
      font-size: 14px; font-weight: 700; cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      transition: opacity .2s, transform .2s;
    }
    .submit-btn:hover { opacity: .9; transform: translateY(-1px); }

    .badge {
      display: inline-block; padding: 4px 10px; border-radius: 50px;
      font-size: 11px; font-weight: 700; text-transform: uppercase;
      margin-left: 8px;
    }
    .badge.chef { background: rgba(252,211,77,.15); color: #FCD34D; }
    .badge.prof { background: rgba(96,165,250,.15); color: #60A5FA; }
    .badge.etudiant { background: rgba(110,231,183,.15); color: #6EE7B7; }
  </style>
</head>
<body>

<!-- HEADER -->
<header class="profile-header">
  <div class="header-left">
    <div class="header-logo">
      <div class="logo-box">
        <i data-lucide="user" style="width:20px;height:20px"></i>
      </div>
      <div class="header-title">
        <h1>Mon Profil</h1>
        <p>Gérez vos informations personnelles</p>
      </div>
    </div>
  </div>

  <div class="nav-links">
    @if($user->role === 'etudiant')
      <a href="{{ route('dashboard') }}">
        <i data-lucide="arrow-left" style="width:14px;height:14px"></i> Retour au tableau de bord
      </a>
    @else
      <a href="{{ route('admin.dashboard') }}">
        <i data-lucide="arrow-left" style="width:14px;height:14px"></i> Retour à l'administration
      </a>
    @endif
  </div>
</header>

<!-- MAIN FORM -->
<div class="main-container">
  <div class="section-title">
    <i data-lucide="edit-3" style="width:20px;height:20px;color:#7C3AED"></i>
    Modifier vos informations
    <span class="badge {{ $user->role }}">{{ $user->role }}</span>
  </div>

  @if (session('success'))
    <div class="alert-success">
      <i data-lucide="check-circle" style="width:16px;height:16px;color:#10B981"></i>
      {{ session('success') }}
    </div>
  @endif

  @if ($errors->any())
    <div class="alert-error">
      <i data-lucide="alert-triangle" style="width:16px;height:16px;color:#EF4444"></i>
      {{ $errors->first() }}
    </div>
  @endif

  <form method="POST" action="{{ route('profile.update') }}">
    @csrf

    <div class="field">
      <label>Nom et Prénom</label>
      <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
    </div>

    <div class="field">
      <label>Adresse Email</label>
      <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
    </div>

    <!-- Informations Édudiant -->
    @if($user->role === 'etudiant')
      <div class="field">
        <label>Département (Filière)</label>
        <select name="filiere_id" required>
          @foreach($filieres as $f)
            <option value="{{ $f->id }}" {{ old('filiere_id', $user->filiere_id) == $f->id ? 'selected' : '' }}>
              {{ $f->nom }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="field">
        <label>Niveau (Licence)</label>
        <select name="niveau_id" required>
          @foreach($niveaux as $n)
            <option value="{{ $n->id }}" {{ old('niveau_id', $user->niveau_id) == $n->id ? 'selected' : '' }}>
              {{ $n->libelle }}
            </option>
          @endforeach
        </select>
      </div>
    @endif

    <!-- Informations Professeur -->
    @if($user->role === 'prof')
      <div class="field">
        <label>Département(s) d'affectation (Lecture seule)</label>
        <div class="checkbox-group">
          @foreach($filieres as $f)
            <label class="checkbox-item" style="opacity: 0.8;">
              <input type="checkbox" disabled 
                {{ is_array($user->filiere_ids) && in_array($f->id, $user->filiere_ids) ? 'checked' : '' }}>
              <span>{{ $f->nom }} ({{ $f->code }})</span>
            </label>
          @endforeach
        </div>
        <small style="color: #94A3B8; display:block; margin-top:6px;">
          * Contactez le chef de programme pour modifier vos départements d'affectation.
        </small>
      </div>
    @endif

    <div style="margin: 24px 0 16px 0; border-top: 1px solid rgba(255,255,255,.06); padding-top: 20px;">
      <h3 style="font-size:14px; font-weight:700; color:#fff; margin-bottom:12px">Modifier le mot de passe (optionnel)</h3>
    </div>

    <div class="field">
      <label>Nouveau mot de passe</label>
      <input type="password" name="password" placeholder="Laissez vide pour ne pas changer">
    </div>

    <div class="field">
      <label>Confirmer le nouveau mot de passe</label>
      <input type="password" name="password_confirmation" placeholder="Laissez vide pour ne pas changer">
    </div>

    <button type="submit" class="submit-btn">
      <i data-lucide="save" style="width:16px;height:16px"></i>
      Enregistrer les modifications
    </button>
  </form>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
</body>
</html>
