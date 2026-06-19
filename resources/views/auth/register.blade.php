<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription — UGANC</title>
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

    .register-card {
      width: 100%; max-width: 480px;
      background: rgba(15,26,42,.85);
      backdrop-filter: blur(40px);
      border: 1px solid rgba(255,255,255,.1);
      border-radius: 24px;
      padding: 36px 32px;
      box-shadow: 0 40px 80px rgba(0,0,0,.5);
    }

    .logo-wrap {
      text-align: center; margin-bottom: 24px;
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

    .field { margin-bottom: 16px; }
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
    .field input, .field select {
      width: 100%; padding: 12px 14px 12px 42px;
      background: #162032;
      border: 1.5px solid rgba(255,255,255,.08);
      border-radius: 12px;
      color: #E2E8F0; font-size: 14px; font-family: 'Inter', sans-serif;
      outline: none; transition: border-color .2s;
    }
    .field select {
      padding-left: 42px;
      appearance: none;
      background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
      background-repeat: no-repeat;
      background-position: right 14px center;
      background-size: 16px;
    }
    .field input:focus, .field select:focus { border-color: #7C3AED; }
    .field input::placeholder { color: #334155; }

    .checkbox-group {
      display: flex; gap: 16px; margin-top: 8px;
    }
    .checkbox-item {
      display: flex; align-items: center; gap: 8px;
      background: rgba(255,255,255,.03);
      padding: 10px 14px; border-radius: 10px;
      border: 1px solid rgba(255,255,255,.06);
      flex: 1; cursor: pointer;
    }
    .checkbox-item input {
      width: 16px; height: 16px; accent-color: #7C3AED;
    }
    .checkbox-item span {
      font-size: 13px; color: #E2E8F0; font-weight: 500;
    }

    .register-btn {
      width: 100%; padding: 13px;
      background: linear-gradient(135deg, #1B4FD8, #7C3AED);
      color: #fff; border: none; border-radius: 12px;
      font-size: 14px; font-weight: 700; cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      transition: opacity .2s, transform .2s;
      margin-top: 20px;
    }
    .register-btn:hover { opacity: .9; transform: translateY(-1px); }

    .error-box {
      background: rgba(239,68,68,.12);
      border: 1px solid rgba(239,68,68,.25);
      border-radius: 12px; padding: 10px 14px;
      color: #FCA5A5; font-size: 12.5px; font-weight: 500;
      margin-bottom: 18px;
      display: flex; align-items: center; gap: 8px;
    }

    .back-link {
      text-align: center; margin-top: 18px;
      font-size: 13px; color: rgba(255,255,255,.45);
    }
    .back-link a {
      color: #60A5FA; text-decoration: none; font-weight: 600;
    }
    .back-link a:hover { text-decoration: underline; }
  </style>
</head>
<body>

<div class="register-card">
  <div class="logo-wrap">
    <div class="logo-icon">
      <i data-lucide="user-plus" style="width:28px;height:28px"></i>
    </div>
    <h1>Créer un compte — UGANC</h1>
    <p>Rejoignez la plateforme de gestion d'EDT</p>
  </div>

  @if ($errors->any())
    <div class="error-box">
      <i data-lucide="alert-triangle" style="width:15px;height:15px;flex-shrink:0"></i>
      {{ $errors->first() }}
    </div>
  @endif

  <form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="field">
      <label>Nom et Prénom *</label>
      <div class="input-wrap">
        <i data-lucide="user"></i>
        <input type="text" name="name" value="{{ old('name') }}" placeholder="Ex : Abdoulaye Diallo" required autofocus>
      </div>
    </div>

    <div class="field">
      <label>Adresse email *</label>
      <div class="input-wrap">
        <i data-lucide="mail"></i>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="votre@email.com" required>
      </div>
    </div>

    <div class="field">
      <label>Mot de passe *</label>
      <div class="input-wrap">
        <i data-lucide="lock"></i>
        <input type="password" name="password" placeholder="••••••••" required>
      </div>
    </div>

    <div class="field">
      <label>Confirmer le mot de passe *</label>
      <div class="input-wrap">
        <i data-lucide="lock"></i>
        <input type="password" name="password_confirmation" placeholder="••••••••" required>
      </div>
    </div>

    <div class="field">
      <label>Votre Rôle *</label>
      <div class="input-wrap">
        <i data-lucide="users"></i>
        <select name="role" id="role-select" required>
          <option value="etudiant" {{ old('role') == 'etudiant' ? 'selected' : '' }}>🎓 Étudiant</option>
          <option value="prof" {{ old('role') == 'prof' ? 'selected' : '' }}>👨‍🏫 Enseignant (Professeur)</option>
        </select>
      </div>
    </div>

    <!-- ══ Zone Étudiant ══ -->
    <div id="section-etudiant">
      <div class="field">
        <label>Département *</label>
        <div class="input-wrap">
          <i data-lucide="layers"></i>
          <select name="filiere_id" id="filiere-select">
            @foreach($filieres as $f)
              <option value="{{ $f->id }}" {{ old('filiere_id') == $f->id ? 'selected' : '' }}>{{ $f->nom }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="field">
        <label>Niveau (Licence) *</label>
        <div class="input-wrap">
          <i data-lucide="hash"></i>
          <select name="niveau_id" id="niveau-select">
            @foreach($niveaux as $n)
                <option value="{{ $n->id }}" {{ old('niveau_id') == $n->id ? 'selected' : '' }}>{{ $n->libelle }}</option>
            @endforeach
          </select>
        </div>
      </div>
        <div class="field" id="semestre-field" style="display:none;">
            <label>Semestre *</label>
            <div class="input-wrap">
                <i data-lucide="calendar"></i>
                <select name="semestre" id="semestre-select" required>
                    @for ($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" {{ old('semestre') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>

    <!-- ══ Zone Professeur ══ -->
    <div id="section-prof" style="display: none;">
      <div class="field">
        <label>Département(s) d'affectation *</label>
        <div class="checkbox-group">
          @foreach($filieres as $f)
            <label class="checkbox-item">
              <input type="checkbox" name="filiere_ids[]" value="{{ $f->id }}" 
                {{ is_array(old('filiere_ids')) && in_array($f->id, old('filiere_ids')) ? 'checked' : '' }}>
              <span>{{ $f->code }}</span>
            </label>
          @endforeach
        </div>
      </div>
    </div>

    <button type="submit" class="register-btn">
      <i data-lucide="user-plus" style="width:16px;height:16px"></i>
      Créer mon compte
    </button>
  </form>

  <div class="back-link">
    Déjà un compte ? <a href="{{ route('login') }}">Connectez-vous ici</a>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();

    const roleSelect = document.getElementById('role-select');
    const secEtudiant = document.getElementById('section-etudiant');
    const secProf = document.getElementById('section-prof');

    // Selectors to require/unrequire fields dynamically
    const filiereSel = document.getElementById('filiere-select');
    const niveauSel = document.getElementById('niveau-select');
    const profCheckboxes = document.querySelectorAll('input[name="filiere_ids[]"]');

    function filterNiveaux() {
      // Les niveaux sont maintenant uniques, pas besoin de filtrer par filière
    }

    function toggleSections() {
      if (roleSelect.value === 'etudiant') {
        secEtudiant.style.display = 'block';
        secProf.style.display = 'none';

        filiereSel.disabled = false;
        niveauSel.disabled = false;
        profCheckboxes.forEach(cb => cb.disabled = true);
        filterNiveaux();
      } else {
        secEtudiant.style.display = 'none';
        secProf.style.display = 'block';

        filiereSel.disabled = true;
        niveauSel.disabled = true;
        profCheckboxes.forEach(cb => cb.disabled = false);
      }
    }

    roleSelect.addEventListener('change', toggleSections);
    filiereSel.addEventListener('change', filterNiveaux);
    toggleSections(); // Run on startup to match old input
    filterNiveaux(); // Apply filter on load if filiere already selected
  });
</script>
</body>
</html>
