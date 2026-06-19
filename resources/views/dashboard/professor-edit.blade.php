<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier le cours — UGANC</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; background: #060D1A; color: #E2E8F0; min-height: 100vh; }

    .prof-header {
      background: linear-gradient(135deg, #0D1B2A 0%, #3B82F6 100%);
      padding: 0 32px; height: 70px;
      display: flex; align-items: center; justify-content: space-between;
      border-bottom: 1px solid rgba(255,255,255,.08);
      box-shadow: 0 4px 30px rgba(0,0,0,.5);
      position: sticky; top: 0; z-index: 100;
    }
    .prof-header h1 { color: #fff; font-size: 17px; font-weight: 700; }
    .nav-link {
      display: flex; align-items: center; gap: 6px;
      padding: 8px 16px; border-radius: 50px;
      font-size: 12.5px; font-weight: 500; color: rgba(255,255,255,.8);
      text-decoration: none; border: 1px solid rgba(255,255,255,.15);
      transition: all .2s;
    }
    .nav-link:hover { background: rgba(255,255,255,.1); color: #fff; }

    .main-container {
      max-width: 600px; margin: 40px auto;
      padding: 0 20px;
    }

    .card {
      background: #0F1A2A;
      border: 1px solid rgba(255,255,255,.07);
      border-radius: 20px;
      padding: 32px;
      box-shadow: 0 20px 50px rgba(0,0,0,.3);
    }

    .title {
      font-size: 20px; font-weight: 800; color: #fff;
      margin-bottom: 24px; display: flex; align-items: center; gap: 10px;
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
    .field input:focus, .field select:focus { border-color: #3B82F6; }

    .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

    .btn {
      width: 100%; padding: 13px;
      background: linear-gradient(135deg, #3B82F6, #1D4ED8);
      color: #fff; border: none; border-radius: 12px;
      font-size: 14px; font-weight: 700; cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      transition: opacity .2s, transform .2s;
    }
    .btn:hover { opacity: .9; transform: translateY(-1px); }

    .back-link {
      display: inline-flex; align-items: center; gap: 6px;
      color: #94A3B8; text-decoration: none;
      font-size: 13.5px; font-weight: 600;
      transition: color .2s;
    }
    .back-link:hover { color: #fff; }
  </style>
</head>
<body>

<header class="prof-header">
  <div>
    <h1>👨‍🏫 Espace Enseignant</h1>
    <p>Modifier le cours</p>
  </div>
  <div style="display:flex;gap:8px;align-items:center">
    <a href="{{ route('professor.cours') }}" class="nav-link">
      <i data-lucide="arrow-left" style="width:14px;height:14px"></i> Retour à mes cours
    </a>
  </div>
</header>

<div class="main-container">
  <div class="card">
    <div class="title">
      <i data-lucide="edit-3" style="width:22px;height:22px;color:#3B82F6"></i>
      Modifier les détails du cours
    </div>

    @if (session('status'))
      <div style="background:rgba(16,185,129,.12); border:1px solid rgba(16,185,129,.25); border-radius:12px; padding:12px 16px; color:#6EE7B7; font-size:13.5px; margin-bottom:20px; display:flex; align-items:center; gap:8px;">
        <i data-lucide="check-circle" style="width:16px;height:16px"></i>
        {{ session('status') }}
      </div>
    @endif

    <form method="POST" action="{{ route('professor.cours.update', $emploi->id) }}">
      @csrf
      @method('PUT')
      <input type="hidden" name="enseignant_id" value="{{ $emploi->enseignant_id }}">

      <!-- Informations fixes du cours assigné (Lecture seule) -->
      <div class="field" style="opacity: 0.8;">
        <label>Matière</label>
        <div style="background: rgba(255,255,255,0.05); padding: 12px 14px; border: 1.5px solid rgba(255,255,255,0.05); border-radius: 12px; color: #94A3B8; font-weight: 500;">
          📚 {{ $emploi->matiere->nom ?? 'Matière inconnue' }}
        </div>
      </div>

      <div class="field" style="opacity: 0.8;">
        <label>Département / Filière</label>
        <div style="background: rgba(255,255,255,0.05); padding: 12px 14px; border: 1.5px solid rgba(255,255,255,0.05); border-radius: 12px; color: #94A3B8; font-weight: 500;">
          🎓 {{ $emploi->filiere->nom ?? 'Département inconnu' }}
        </div>
      </div>

      <div class="field" style="opacity: 0.8;">
        <label>Niveau / Licence</label>
        <div style="background: rgba(255,255,255,0.05); padding: 12px 14px; border: 1.5px solid rgba(255,255,255,0.05); border-radius: 12px; color: #94A3B8; font-weight: 500;">
          ⚡ {{ $emploi->niveau->libelle ?? 'Niveau inconnu' }}
        </div>
      </div>

      <!-- Informations modifiables par l'enseignant -->
      <div class="field">
        <label for="salle_id">Salle *</label>
        <select name="salle_id" id="salle_id" required>
          @foreach($salles as $s)
            <option value="{{ $s->id }}" {{ $emploi->salle_id == $s->id ? 'selected' : '' }}>{{ $s->nom }}</option>
          @endforeach
        </select>
      </div>

      <div class="field">
        <label for="jour">Jour *</label>
        <select name="jour" id="jour" required>
          @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'] as $j)
            <option value="{{ $j }}" {{ $emploi->jour === $j ? 'selected' : '' }}>{{ $j }}</option>
          @endforeach
        </select>
      </div>

      <div class="field-row">
        <div class="field">
          <label for="heure_debut">Heure de début *</label>
          <input type="time" name="heure_debut" id="heure_debut" value="{{ substr($emploi->heure_debut, 0, 5) }}" required>
        </div>
        <div class="field">
          <label for="heure_fin">Heure de fin *</label>
          <input type="time" name="heure_fin" id="heure_fin" value="{{ substr($emploi->heure_fin, 0, 5) }}" required>
        </div>
      </div>

      <div class="field">
        <label for="motif">Motif du changement *</label>
        <input type="text" name="motif" id="motif" placeholder="Ex: Panne de courant, réunion pédagogique, absence..." required>
        <span style="font-size: 11px; color: #64748B; margin-top: 4px; display: block;">
          Ce motif sera visible par les étudiants sur leur tableau de bord.
        </span>
      </div>

      <button type="submit" class="btn">
        <i data-lucide="save" style="width:16px;height:16px"></i>
        Mettre à jour et Notifier
      </button>
    </form>

    <div style="margin-top: 24px; text-align: center;">
      <a href="{{ route('professor.cours') }}" class="back-link">
        <i data-lucide="arrow-left" style="width:14px;height:14px"></i>
        Retour aux cours
      </a>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
</body>
</html>
