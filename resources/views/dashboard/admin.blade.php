<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin — Tableau de bord UGANC</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; background: #060D1A; color: #E2E8F0; min-height: 100vh; }

    .admin-header {
      background: linear-gradient(135deg, #0D1B2A 0%, #7C3AED 100%);
      padding: 0 32px; height: 70px;
      display: flex; align-items: center; justify-content: space-between;
      border-bottom: 1px solid rgba(255,255,255,.08);
      box-shadow: 0 4px 30px rgba(0,0,0,.5);
      position: sticky; top: 0; z-index: 100;
    }
    .admin-header h1 { color: #fff; font-size: 17px; font-weight: 700; }
    .admin-header p  { color: rgba(255,255,255,.5); font-size: 11.5px; }
    .nav-link {
      display: flex; align-items: center; gap: 6px;
      padding: 8px 16px; border-radius: 50px;
      font-size: 12.5px; font-weight: 500; color: rgba(255,255,255,.8);
      text-decoration: none; border: 1px solid rgba(255,255,255,.15);
      transition: all .2s;
    }
    .nav-link:hover { background: rgba(255,255,255,.1); color: #fff; }

    .admin-wrap {
      max-width: 1400px; margin: 0 auto;
      padding: 32px;
      display: grid; grid-template-columns: 420px 1fr; gap: 28px;
    }
    @media (max-width: 900px) {
      .admin-wrap { grid-template-columns: 1fr; padding: 16px; }
    }

    /* ── SECTION MODIFICATIONS ── */
    .section-modif {
      max-width: 1400px; margin: 0 auto 32px;
      padding: 0 32px;
      display: grid; grid-template-columns: 420px 1fr; gap: 28px;
    }
    @media (max-width: 900px) {
      .section-modif { grid-template-columns: 1fr; padding: 0 16px 16px; }
    }

    .section-title {
      max-width: 1400px; margin: 0 auto 16px;
      padding: 0 32px;
      font-size: 18px; font-weight: 800; color: #F1F5F9;
      display: flex; align-items: center; gap: 10px;
    }
    @media (max-width: 900px) { .section-title { padding: 0 16px; } }
    .section-title::before {
      content: '';
      display: inline-block;
      width: 4px; height: 22px;
      background: linear-gradient(to bottom, #F59E0B, #EF4444);
      border-radius: 4px;
    }

    .modif-item {
      background: #162032;
      border: 1px solid rgba(255,255,255,.06);
      border-radius: 14px; padding: 14px 16px;
      display: flex; align-items: flex-start; gap: 12px;
      margin-bottom: 10px; transition: border-color .2s;
      border-left: 4px solid #8B5CF6;
    }
    .modif-item:hover { border-color: rgba(139,92,246,.4); }
    .modif-change {
      display: flex; align-items: center; gap: 8px; margin: 4px 0 6px;
      flex-wrap: wrap;
    }
    .modif-old {
      background: rgba(239,68,68,.12); border: 1px solid rgba(239,68,68,.25);
      padding: 3px 9px; border-radius: 8px;
      font-size: 11.5px; color: #FCA5A5; font-weight: 600;
      text-decoration: line-through;
    }
    .modif-arrow { color: #6B7280; font-size: 14px; }
    .modif-new {
      background: rgba(16,185,129,.12); border: 1px solid rgba(16,185,129,.25);
      padding: 3px 9px; border-radius: 8px;
      font-size: 11.5px; color: #6EE7B7; font-weight: 600;
    }
    .course-select option { background: #162032; color: #E2E8F0; }
    .form-title-amber svg { color: #F59E0B; }
    .flash-green {
      background: rgba(16,185,129,.12);
      border: 1px solid rgba(16,185,129,.25);
      border-radius: 12px; padding: 12px 16px;
      color: #6EE7B7; font-size: 13px; font-weight: 500;
      display: flex; align-items: center; gap: 8px;
      margin-bottom: 16px;
    }

    /* ── FORM ── */
    .form-card {
      background: #0F1A2A;
      border: 1px solid rgba(255,255,255,.07);
      border-radius: 20px;
      padding: 24px;
      position: sticky; top: 90px; align-self: start;
    }
    .form-title {
      font-size: 15px; font-weight: 700; color: #F1F5F9;
      display: flex; align-items: center; gap: 8px;
      margin-bottom: 20px;
    }
    .form-title svg { color: #A78BFA; }

    .field { margin-bottom: 16px; }
    .field label {
      display: block; font-size: 12px; font-weight: 600;
      color: #94A3B8; margin-bottom: 6px; letter-spacing: .03em;
      text-transform: uppercase;
    }
    .field input, .field select, .field textarea {
      width: 100%; padding: 10px 14px;
      background: #162032;
      border: 1.5px solid rgba(255,255,255,.08);
      border-radius: 12px;
      color: #E2E8F0; font-size: 13.5px; font-family: 'Inter', sans-serif;
      outline: none; transition: border-color .15s;
    }
    .field textarea { resize: vertical; min-height: 80px; }
    .field input:focus, .field select:focus, .field textarea:focus {
      border-color: #7C3AED;
    }
    .field select option { background: #162032; }

    .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

    .toggle-wrap {
      display: flex; align-items: center; gap: 10px;
      background: #162032;
      border: 1.5px solid rgba(255,255,255,.08);
      border-radius: 12px; padding: 10px 14px;
      cursor: pointer;
    }
    .toggle-wrap input[type=checkbox] {
      width: 18px; height: 18px; cursor: pointer;
      accent-color: #EF4444;
    }
    .toggle-label { font-size: 13px; color: #94A3B8; }

    .submit-btn {
      width: 100%; padding: 12px;
      background: linear-gradient(135deg, #7C3AED, #4F46E5);
      color: #fff; border: none; border-radius: 12px;
      font-size: 14px; font-weight: 600; cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      transition: opacity .2s, transform .2s;
    }
    .submit-btn:hover { opacity: .9; transform: translateY(-1px); }

    /* ── ALERT SUCCESS ── */
    .flash {
      background: rgba(16,185,129,.12);
      border: 1px solid rgba(16,185,129,.25);
      border-radius: 12px; padding: 12px 16px;
      color: #6EE7B7; font-size: 13px; font-weight: 500;
      display: flex; align-items: center; gap: 8px;
      margin-bottom: 16px;
    }

    /* ── LIST ── */
    .list-card {
      background: #0F1A2A;
      border: 1px solid rgba(255,255,255,.07);
      border-radius: 20px; padding: 24px;
    }
    .list-title {
      font-size: 15px; font-weight: 700; color: #F1F5F9;
      display: flex; align-items: center; gap: 8px;
      margin-bottom: 20px;
    }
    .list-title svg { color: #60A5FA; }

    .ann-item {
      background: #162032;
      border: 1px solid rgba(255,255,255,.06);
      border-radius: 14px; padding: 14px 16px;
      display: flex; align-items: flex-start; gap: 12px;
      margin-bottom: 10px; transition: border-color .2s;
    }
    .ann-item:hover { border-color: rgba(255,255,255,.12); }
    .ann-type-dot {
      width: 10px; height: 10px; border-radius: 50%;
      flex-shrink: 0; margin-top: 5px;
    }
    .dot-info    { background: #3B82F6; }
    .dot-warning { background: #F59E0B; }
    .dot-danger  { background: #EF4444; }
    .dot-success { background: #10B981; }

    .ann-body { flex: 1; }
    .ann-titre { font-size: 13.5px; font-weight: 700; color: #F1F5F9; }
    .ann-msg   { font-size: 12.5px; color: #64748B; margin-top: 2px; line-height: 1.5; }
    .ann-meta  { display: flex; gap: 8px; margin-top: 6px; flex-wrap: wrap; }
    .ann-tag {
      background: rgba(255,255,255,.06);
      border: 1px solid rgba(255,255,255,.08);
      padding: 2px 8px; border-radius: 50px;
      font-size: 10.5px; color: #64748B; font-weight: 500;
    }
    .urgent-dot {
      background: rgba(239,68,68,.15);
      border-color: rgba(239,68,68,.3);
      color: #FCA5A5;
    }

    .del-btn {
      flex-shrink: 0; background: none; border: none;
      cursor: pointer; color: #334155; transition: color .2s;
      padding: 4px;
    }
    .del-btn:hover { color: #EF4444; }

    .empty {
      text-align: center; padding: 28px;
      color: #334155; font-size: 13px;
    }
  </style>
</head>
<body>

<header class="admin-header">
  <div>
    <h1>⚙️ Espace Administrateur</h1>
    <p>Connecté en tant que : <strong>{{ auth()->user()->name }}</strong> 
       @if(auth()->user()->isChef()) (Chef de programme) @elseif(auth()->user()->isProf()) (Professeur) @endif
    </p>
  </div>
  <div style="display:flex;gap:8px;align-items:center">
    @if(auth()->user()->isProf())
      <a href="{{ route('professor.cours') }}" class="nav-link">
        <i data-lucide="book-open" style="width:14px;height:14px"></i> Mes cours
      </a>
    @endif
    <a href="/tableau-de-bord" class="nav-link">
      <i data-lucide="layout-dashboard" style="width:14px;height:14px"></i> Tableau de bord
    </a>
    <a href="/chatbot" class="nav-link">
      <i data-lucide="message-circle" style="width:14px;height:14px"></i> Chatbot
    </a>
    <a href="{{ route('profile') }}" class="nav-link">
      <i data-lucide="user" style="width:14px;height:14px"></i> Mon Profil
    </a>
    <form method="POST" action="{{ route('logout') }}" style="margin:0">
      @csrf
      <button type="submit" class="nav-link" style="background:transparent;cursor:pointer;color:#FCA5A5">
        <i data-lucide="log-out" style="width:14px;height:14px"></i> Déconnexion
      </button>
    </form>
  </div>
</header>

<div class="admin-wrap">

  <!-- ═══ FORMULAIRE ═══ -->
  <div class="form-card">
    <div class="form-title">
      <i data-lucide="megaphone" style="width:18px;height:18px"></i>
      Publier une annonce
    </div>

    @if(session('success'))
      <div class="flash">
        <i data-lucide="check-circle-2" style="width:16px;height:16px"></i>
        {{ session('success') }}
      </div>
    @endif

    @if ($errors->hasBag('default') && $errors->getBag('default')->any())
      <div class="flash" style="background: rgba(239,68,68,.12); border: 1px solid rgba(239,68,68,.25); color: #FCA5A5; flex-direction: column; align-items: flex-start; gap: 4px;">
        <div style="display: flex; align-items: center; gap: 8px;">
          <i data-lucide="alert-triangle" style="width:16px;height:16px"></i>
          <strong>Veuillez corriger les erreurs suivantes :</strong>
        </div>
        <ul style="margin-left: 24px; font-size: 12px; margin-top: 4px;">
          @foreach ($errors->getBag('default')->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.annonces.store') }}">
      @csrf

      <div class="field">
        <label>Titre de l'annonce *</label>
        <input type="text" name="titre" placeholder="Ex : Prof DIALLO absent aujourd'hui" required>
      </div>

      <div class="field">
        <label>Message *</label>
        <textarea name="message" placeholder="Détails de l'annonce : heure, salle de remplacement, consignes…" required></textarea>
      </div>

      <div class="field-row">
        <div class="field">
          <label>Type</label>
          <select name="type">
            <option value="info">ℹ️ Information</option>
            <option value="warning">⚠️ Avertissement</option>
            <option value="danger">🔴 Cours annulé</option>
            <option value="success">✅ Bonne nouvelle</option>
          </select>
        </div>
        <div class="field">
          <label>Enseignant concerné (Auteur)</label>
          <select name="auteur">
            <option value="">— Aucun (Annonce administrative) —</option>
            @foreach($enseignants as $ens)
              <option value="M. {{ $ens->nom }}">{{ $ens->prenom }} {{ $ens->nom }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="field-row">
        <div class="field">
          <label>Niveau (optionnel)</label>
          <select name="niveau_id">
            <option value="">— Tous les niveaux —</option>
            @foreach($niveaux as $n)
              <option value="{{ $n->id }}">{{ $n->libelle }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="field">
        <label>Expiration automatique (optionnel)</label>
        <input type="datetime-local" name="expires_at">
        <small style="color: #94A3B8; font-size: 11px; display: block; margin-top: 4px; line-height: 1.4;">
          ⚠️ <strong>Laissez vide</strong> si vous voulez que l'annonce reste affichée indéfiniment.<br>
          Si vous mettez une date, l'annonce disparaîtra du tableau de bord étudiant à cette date précise.
        </small>
      </div>

      <div class="field">
        <label class="toggle-wrap" style="cursor:pointer">
          <input type="checkbox" name="urgent" value="1">
          <span class="toggle-label">🔴 Marquer comme <strong>URGENT</strong> (apparaît dans le bandeau déroulant)</span>
        </label>
      </div>

      <button type="submit" class="submit-btn">
        <i data-lucide="send" style="width:15px;height:15px"></i>
        Publier l'annonce
      </button>
    </form>
  </div>

  <!-- ═══ LISTE DES ANNONCES ═══ -->
  <div class="list-card">
    <div class="list-title">
      <i data-lucide="list" style="width:18px;height:18px"></i>
      Annonces publiées ({{ $annonces->count() }})
    </div>

    @forelse($annonces as $annonce)
      <div class="ann-item">
        <div class="ann-type-dot dot-{{ $annonce->type }}"></div>
        <div class="ann-body">
          <div class="ann-titre">{{ $annonce->titre }}</div>
          <div class="ann-msg">{{ Str::limit($annonce->message, 120) }}</div>
          <div class="ann-meta">
            @if($annonce->urgent)
              <span class="ann-tag urgent-dot">🔴 Urgent</span>
            @endif
            @if($annonce->auteur)
              <span class="ann-tag">👤 {{ $annonce->auteur }}</span>
            @endif
            @if($annonce->filiere)
              <span class="ann-tag">{{ $annonce->filiere->nom }}</span>
            @else
              <span class="ann-tag">Toutes filières</span>
            @endif
            @if($annonce->niveau)
              <span class="ann-tag">{{ $annonce->niveau->libelle }}</span>
            @endif
            <span class="ann-tag">🕐 {{ $annonce->created_at->diffForHumans() }}</span>
            @if($annonce->expires_at)
              @if($annonce->expires_at->isPast())
                <span class="ann-tag" style="background: rgba(239,68,68,.12); border-color: rgba(239,68,68,.25); color: #FCA5A5;">🔴 Expirée (Masquée)</span>
              @else
                <span class="ann-tag">⏱ Expire {{ $annonce->expires_at->format('d/m à H:i') }}</span>
              @endif
            @endif
          </div>
        </div>
        <form method="POST" action="{{ route('admin.annonces.destroy', $annonce) }}"
              onsubmit="return confirm('Supprimer cette annonce ?')">
          @csrf @method('DELETE')
          <button type="submit" class="del-btn" title="Supprimer">
            <i data-lucide="trash-2" style="width:16px;height:16px"></i>
          </button>
        </form>
      </div>
    @empty
      <div class="empty">Aucune annonce publiée pour l'instant.</div>
    @endforelse
  </div>

</div>

<!-- ═══════════════════════════════════════════════════════ -->
<!-- ═══ SECTION : MODIFIER UN COURS ═══ -->
<!-- ═══════════════════════════════════════════════════════ -->

<div class="section-title" id="modifications">
  <i data-lucide="calendar-clock" style="width:20px;height:20px;color:#F59E0B"></i>
  Modifier un cours — Notifier les étudiants
</div>

<div class="section-modif">

  <!-- ── Formulaire de modification ── -->
  <div class="form-card">
    <div class="form-title form-title-amber">
      <i data-lucide="clock-4" style="width:18px;height:18px"></i>
      Enregistrer un changement d'horaire
    </div>

    @if(session('success_modif'))
      <div class="flash-green">
        <i data-lucide="check-circle-2" style="width:16px;height:16px"></i>
        {{ session('success_modif') }}
      </div>
    @endif

    <form method="POST" action="{{ route('admin.modifications.store') }}">
      @csrf

      <div class="field">
        <label>Cours à modifier *</label>
        <select name="emploi_id" class="course-select" required>
          <option value="">— Sélectionnez un cours —</option>
          @foreach($emplois as $emp)
            <option value="{{ $emp->id }}">
              {{ $emp->jour }} {{ substr($emp->heure_debut, 0, 5) }}-{{ substr($emp->heure_fin, 0, 5) }}
              — {{ $emp->matiere->nom ?? '?' }}
              @if($emp->filiere) ({{ $emp->filiere->nom }}) @endif
              @if($emp->niveau) {{ $emp->niveau->libelle }} @endif
            </option>
          @endforeach
        </select>
        <small style="color:#94A3B8;font-size:11px;display:block;margin-top:4px">
          Sélectionnez le cours concerné par le changement d'horaire.
        </small>
      </div>

      <div class="field-row">
        <div class="field">
          <label>Nouveau jour *</label>
          <select name="nouveau_jour" required>
            <option value="Lundi">Lundi</option>
            <option value="Mardi">Mardi</option>
            <option value="Mercredi">Mercredi</option>
            <option value="Jeudi">Jeudi</option>
            <option value="Vendredi">Vendredi</option>
            <option value="Samedi">Samedi</option>
          </select>
        </div>
        <div class="field">
          <label>Nouvelle heure d'arrivée *</label>
          <input type="time" name="nouvelle_heure" required placeholder="Ex : 10:30">
        </div>
      </div>

      <div class="field">
        <label>Motif / Raison (optionnel)</label>
        <input type="text" name="motif" placeholder="Ex : Salle indisponible, déplacement du prof…">
      </div>

      <button type="submit" class="submit-btn" style="background: linear-gradient(135deg,#F59E0B,#EF4444)">
        <i data-lucide="send" style="width:15px;height:15px"></i>
        Publier la modification
      </button>
    </form>
  </div>

  <!-- ── Historique des modifications ── -->
  <div class="list-card">
    <div class="list-title">
      <i data-lucide="clock-4" style="width:18px;height:18px;color:#F59E0B"></i>
      Modifications publiées ({{ $modifications->count() }})
    </div>

    @forelse($modifications as $mod)
      <div class="modif-item">
        <div style="flex:1">
          <div style="font-size:13.5px;font-weight:700;color:#F1F5F9;margin-bottom:4px">
            📚 {{ $mod->emploi->matiere->nom ?? 'Cours inconnu' }}
            @if($mod->emploi->filiere)
              <span class="ann-tag" style="margin-left:6px">{{ $mod->emploi->filiere->nom }}</span>
            @endif
            @if($mod->emploi->niveau)
              <span class="ann-tag">{{ $mod->emploi->niveau->libelle }}</span>
            @endif
          </div>
          <div class="modif-change">
            <span class="modif-old">{{ $mod->ancien_jour }} à {{ substr($mod->ancienne_heure, 0, 5) }}</span>
            <span class="modif-arrow">→</span>
            <span class="modif-new">{{ $mod->nouveau_jour }} à {{ substr($mod->nouvelle_heure, 0, 5) }}</span>
          </div>
          <div style="display:flex;gap:8px;flex-wrap:wrap">
            @if($mod->motif)
              <span class="ann-tag">💬 {{ $mod->motif }}</span>
            @endif
            <span class="ann-tag">🕐 {{ $mod->date_modif->diffForHumans() }}</span>
          </div>
        </div>
        <form method="POST" action="{{ route('admin.modifications.destroy', $mod) }}"
              onsubmit="return confirm('Supprimer cette modification ?')">
          @csrf @method('DELETE')
          <button type="submit" class="del-btn" title="Supprimer">
            <i data-lucide="trash-2" style="width:16px;height:16px"></i>
          </button>
        </form>
      </div>
    @empty
      <div class="empty">Aucune modification d'horaire enregistrée.</div>
    @endforelse
  </div>

  </div>

</div>

@if(auth()->user()->isChef())
<!-- ═══════════════════════════════════════════════════════ -->
<!-- ═══ SECTION : SAISIE DE L'EMPLOI DU TEMPS (CHEF) ═══ -->
<!-- ═══════════════════════════════════════════════════════ -->
<div class="section-title" id="saisie-edt" style="margin-top:20px;">
  <i data-lucide="calendar-plus" style="width:20px;height:20px;color:#10B981"></i>
  Saisie de l'Emploi du Temps (Chef de Programme)
</div>

<div class="section-modif">
  <!-- Formulaire de création EDT -->
  <div class="form-card">
    <div class="form-title" style="color:#10B981">
      <i data-lucide="plus-circle" style="width:18px;height:18px"></i>
      Ajouter un cours
    </div>

    @if(session('success_edt'))
      <div class="flash-green">
        <i data-lucide="check-circle-2" style="width:16px;height:16px"></i>
        {{ session('success_edt') }}
      </div>
    @endif

    @if ($errors->hasBag('edt') && $errors->getBag('edt')->any())
      <div class="flash" style="background: rgba(239,68,68,.12); border: 1px solid rgba(239,68,68,.25); color: #FCA5A5; flex-direction: column; align-items: flex-start; gap: 4px;">
        <div style="display: flex; align-items: center; gap: 8px;">
          <i data-lucide="alert-triangle" style="width:16px;height:16px"></i>
          <strong>Erreur — Veuillez compléter le formulaire :</strong>
        </div>
        <ul style="margin-left: 24px; font-size: 12px; margin-top: 4px;">
          @foreach ($errors->getBag('edt')->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.edt.store') }}">
      @csrf

      <input type="hidden" id="niveau_id_hidden" name="niveau_id" value="" required>

      <div class="field-row">
        <div class="field">
          <label>Licence *</label>
          <select id="licence_select" required>
            <option value="">— Choisir la Licence —</option>
            <option value="L1">Licence 1 (L1)</option>
            <option value="L2">Licence 2 (L2)</option>
            <option value="L3">Licence 3 (L3)</option>
          </select>
        </div>
        <div class="field">
          <label>Semestre *</label>
          <select id="semestre_select" name="semestre" required>
            <option value="">— Choisir le Semestre —</option>
          </select>
        </div>
        <div class="field">
          <label>Module *</label>
          <select id="module_select" required>
            <option value="">— Choisir le Module —</option>
          </select>
        </div>
      </div>

      <div class="field-row">
        <div class="field">
          <label>Matière *</label>
          <select id="matiere_select_input" name="matiere_id" required>
            <option value="">— Choisir la Matière —</option>
            @foreach($matieres as $m) <option value="{{ $m->id }}">{{ $m->nom }}</option> @endforeach
          </select>
        </div>
        <div class="field">
          <label>Enseignant *</label>
          <select name="enseignant_id" required>
            @foreach($enseignants as $e) <option value="{{ $e->id }}">{{ $e->prenom }} {{ $e->nom }}</option> @endforeach
          </select>
        </div>
      </div>

      <div class="field-row">
        <div class="field">
          <label>Salle *</label>
          <select name="salle_id" required>
            @foreach($salles as $s) <option value="{{ $s->id }}">{{ $s->nom }}</option> @endforeach
          </select>
        </div>
        <div class="field">
          <label>Jour *</label>
          <select name="jour" required>
            <option value="Lundi">Lundi</option>
            <option value="Mardi">Mardi</option>
            <option value="Mercredi">Mercredi</option>
            <option value="Jeudi">Jeudi</option>
            <option value="Vendredi">Vendredi</option>
            <option value="Samedi">Samedi</option>
          </select>
        </div>
      </div>

      <div class="field-row">
        <div class="field">
          <label>Heure de début *</label>
          <input type="time" name="heure_debut" required>
        </div>
        <div class="field">
          <label>Heure de fin *</label>
          <input type="time" name="heure_fin" required>
        </div>
      </div>

      <button type="submit" class="submit-btn" style="background: linear-gradient(135deg,#10B981,#059669)">
        <i data-lucide="save" style="width:15px;height:15px"></i>
        Enregistrer le cours
      </button>
    </form>
  </div>

  <!-- Liste des cours existants pour suppression -->
  <div class="list-card">
    <div class="list-title" style="color:#10B981">
      <i data-lucide="list" style="width:18px;height:18px"></i>
      Cours enregistrés ({{ $emplois->count() }})
    </div>
    <div style="max-height:600px;overflow-y:auto;padding-right:8px">
@php
    // Remove duplicate entries for same course (matiere, niveau, semestre, jour, heure)
    $uniqueEmplois = $emplois->unique(function($item) {
        return $item->matiere_id . '_' . $item->niveau_id . '_' . $item->semestre . '_' . $item->jour . '_' . $item->heure_debut;
    });
@endphp
@forelse($uniqueEmplois as $emp)
    <div class="modif-item" style="border-left-color:#10B981">
        <div style="flex:1">
            <div style="font-size:13.5px;font-weight:700;color:#F1F5F9;margin-bottom:4px">
                📚 {{ $emp->matiere->nom ?? 'Inconnu' }} {{ $emp->niveau->libelle ?? '' }} - {{ $emp->semestre ?? 'Semestre N/A' }}
                @if($emp->filiere) ({{ $emp->filiere->nom }}) @endif
            </div>
            <div style="font-size:12px;color:#94A3B8;margin-bottom:4px">
                👨‍🏫 {{ $emp->enseignant->prenom ?? '' }} {{ $emp->enseignant->nom ?? '' }}
            </div>
            <div class="modif-change">
                <span class="modif-new" style="background:rgba(16,185,129,.1)">
                    {{ $emp->jour }} : {{ substr($emp->heure_debut, 0, 5) }} - {{ substr($emp->heure_fin, 0, 5) }}
                </span>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.edt.destroy', $emp) }}"
                onsubmit="return confirm('Voulez-vous vraiment supprimer ce cours ?')">
            @csrf @method('DELETE')
            <button type="submit" class="del-btn" title="Supprimer">
              <i data-lucide="trash-2" style="width:16px;height:16px"></i>
            </button>
          </form>
        </div>
      @empty
        <div class="empty">Aucun cours dans l'emploi du temps.</div>
      @endforelse
    </div>
  </div>
</div>

<div class="section-title" id="enseignants" style="margin-top:40px;">
  <i data-lucide="users" style="width:20px;height:20px;color:#3B82F6"></i>
  Gestion des Enseignants (Chef de Programme)
</div>

<div class="section-modif">
  <!-- Formulaire de création Enseignant -->
  <div class="form-card">
    <div class="form-title" style="color:#3B82F6">
      <i data-lucide="user-plus" style="width:18px;height:18px"></i>
      Ajouter un Enseignant
    </div>

    @if(session('success_enseignant'))
      <div class="flash" style="background:rgba(59,130,246,.12); border-color:rgba(59,130,246,.25); color:#93C5FD">
        <i data-lucide="check-circle-2" style="width:16px;height:16px"></i>
        {{ session('success_enseignant') }}
      </div>
    @endif

    <form method="POST" action="{{ route('admin.enseignants.store') }}">
      @csrf

      <div class="field-row">
        <div class="field">
          <label>Prénom *</label>
          <input type="text" name="prenom" placeholder="Ex : Abdoulaye" required>
        </div>
        <div class="field">
          <label>Nom *</label>
          <input type="text" name="nom" placeholder="Ex : Diallo" required>
        </div>
      </div>

      <div class="field">
        <label>Adresse Email *</label>
        <input type="email" name="email" placeholder="prof.diallo@uganc.edu.gn" required>
      </div>

      <div class="field">
        <label>Mot de passe temporaire *</label>
        <input type="password" name="password" placeholder="••••••••" required>
      </div>

      <div class="field">
        <label>Département(s) d'affectation *</label>
        <div style="display:flex; gap:16px; margin-top:8px;">
          @foreach($filieres as $f)
            <label style="display:flex; align-items:center; gap:8px; background:rgba(255,255,255,.03); padding:10px 14px; border-radius:10px; border:1px solid rgba(255,255,255,.06); flex:1; cursor:pointer">
              <input type="checkbox" name="filiere_ids[]" value="{{ $f->id }}" checked style="width:16px; height:16px; accent-color:#3B82F6">
              <span style="font-size:13px; color:#E2E8F0; font-weight:500;">{{ $f->code }}</span>
            </label>
          @endforeach
        </div>
      </div>

      <button type="submit" class="submit-btn" style="background: linear-gradient(135deg,#3B82F6,#2563EB)">
        <i data-lucide="user-plus" style="width:15px;height:15px"></i>
        Enregistrer et activer le compte
      </button>
    </form>
  </div>

  <!-- Liste des enseignants existants -->
  <div class="list-card">
    <div class="list-title" style="color:#3B82F6">
      <i data-lucide="users" style="width:18px;height:18px"></i>
      Enseignants enregistrés ({{ $enseignants->count() }})
    </div>
    <div style="max-height:600px;overflow-y:auto;padding-right:8px">
      @forelse($enseignants as $ens)
        <div class="modif-item" style="border-left-color:#3B82F6">
          <div style="flex:1">
            <div style="font-size:13.5px;font-weight:700;color:#F1F5F9;margin-bottom:4px">
              👨‍🏫 {{ $ens->prenom }} {{ $ens->nom }}
            </div>
            <div style="font-size:12px;color:#94A3B8;margin-bottom:4px">
              ✉️ {{ $ens->email }}
            </div>
            <div class="modif-change" style="margin-bottom:8px;">
              <span class="modif-new" style="background:rgba(59,130,246,.1); color:#93C5FD">
                Affecté à : 
                @if($ens->user && is_array($ens->user->filiere_ids))
                  {{ implode(' & ', \App\Models\Filiere::whereIn('id', $ens->user->filiere_ids)->pluck('code')->toArray()) }}
                @elseif($ens->user && $ens->user->filiere)
                  {{ $ens->user->filiere->code }}
                @else
                  Aucun
                @endif
              </span>
            </div>

            @php
              $profCours = $emplois->where('enseignant_id', $ens->id);
            @endphp
            @if($profCours->isNotEmpty())
              <div style="margin-top: 8px; font-size: 11.5px; color: #94A3B8;">
                <strong style="color: #60A5FA; display:block; margin-bottom:4px;">📚 Cours assignés :</strong>
                <ul style="list-style: none; padding-left: 0; margin-top: 4px;">
                  @foreach($profCours as $c)
                    <li style="background: rgba(255,255,255,0.03); padding: 4px 8px; border-radius: 6px; margin-bottom: 4px; border: 1px solid rgba(255,255,255,0.05); color:#F1F5F9">
                      {{ $c->matiere->nom ?? 'Inconnu' }} ({{ $c->niveau->libelle ?? '' }} - {{ $c->semestre }}) — {{ $c->jour }} {{ substr($c->heure_debut, 0, 5) }}-{{ substr($c->heure_fin, 0, 5) }}
                    </li>
                  @endforeach
                </ul>
              </div>
            @else
              <div style="margin-top: 8px; font-size: 11.5px; color: #64748B; font-style: italic;">
                Aucun cours assigné.
              </div>
            @endif
          </div>
        </div>
      @empty
        <div class="empty">Aucun enseignant enregistré.</div>
      @endforelse
    </div>
  </div>
  <!-- ⚙️ Gestion des Matières ⚙️ -->
  <div class="section-title" id="matieres" style="margin-top:40px;">
    <i data-lucide="book" style="width:20px;height:20px;color:#10B981"></i>
    Gestion des Matières (Chef de Programme)
  </div>

  <div class="section-modif">
    <!-- Formulaire de création Matière -->
    <div class="form-card">
      <div class="form-title" style="color:#10B981">
        <i data-lucide="plus-square" style="width:18px;height:18px"></i>
        Ajouter une Matière
      </div>

      @if(session('success_matiere'))
        <div class="flash-green">
          <i data-lucide="check-circle-2" style="width:16px;height:16px"></i>
          {{ session('success_matiere') }}
        </div>
      @endif

      @if ($errors->hasBag('matiere') && $errors->getBag('matiere')->any())
        <div class="flash" style="background: rgba(239,68,68,.12); border: 1px solid rgba(239,68,68,.25); color: #FCA5A5; flex-direction: column; align-items: flex-start; gap: 4px;">
          <div style="display: flex; align-items: center; gap: 8px;">
            <i data-lucide="alert-triangle" style="width:16px;height:16px"></i>
            <strong>Erreur — Matière :</strong>
          </div>
          <ul style="margin-left: 24px; font-size: 12px; margin-top: 4px;">
            @foreach ($errors->getBag('matiere')->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('admin.matieres.store') }}">
        @csrf

        <div class="field">
          <label>Nom de la matière *</label>
          <input type="text" name="nom" placeholder="Ex : Algorithmique Avancée" required>
        </div>

        <div class="field-row">
          <div class="field">
            <label>Code *</label>
            <input type="text" name="code" placeholder="Ex : ALGO-L1" required>
          </div>
          <div class="field">
            <label>Nombre de Crédits *</label>
            <input type="number" name="credits" min="1" max="10" value="3" required>
          </div>
        </div>

        <div class="field-row">
          <div class="field">
            <label>Licence / Niveau *</label>
            <select name="niveau_id" required>
              @foreach($niveaux as $n)
                <option value="{{ $n->id }}">{{ $n->libelle }}</option>
              @endforeach
            </select>
          </div>
          <div class="field">
            <label>Semestre *</label>
            <select name="semestre" required>
              <option value="S1">Semestre S1</option>
              <option value="S2">Semestre S2</option>
              <option value="S3">Semestre S3</option>
              <option value="S4">Semestre S4</option>
              <option value="S5">Semestre S5</option>
              <option value="S6">Semestre S6</option>
            </select>
          </div>
        </div>

        <button type="submit" class="submit-btn" style="background: linear-gradient(135deg,#10B981,#059669)">
          <i data-lucide="plus-circle" style="width:15px;height:15px"></i>
          Enregistrer la matière
        </button>
      </form>
    </div>

    <!-- Liste des matières existantes -->
    <div class="list-card">
      <div class="list-title" style="color:#10B981">
        <i data-lucide="book-open" style="width:18px;height:18px"></i>
        Matières enregistrées ({{ $matieres->count() }})
      </div>
      <div style="max-height:600px;overflow-y:auto;padding-right:8px">
        @forelse($matieres as $mat)
          <div class="modif-item" style="border-left-color:#10B981">
            <div style="flex:1">
              <div style="font-size:13.5px;font-weight:700;color:#F1F5F9;margin-bottom:4px">
                📚 {{ $mat->nom }} ({{ $mat->code }})
              </div>
              <div style="font-size:12px;color:#94A3B8;margin-bottom:4px">
                Crédits: {{ $mat->credits }} | Niveau: {{ $mat->niveau->libelle ?? 'N/A' }} | Semestre: {{ $mat->semestre ?? 'N/A' }}
              </div>
            </div>
          </div>
        @empty
          <div class="empty">Aucune matière enregistrée pour ce département.</div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endif

<script>
  document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();

    const licenceSelect = document.getElementById('licence_select');
    const semestreSelect = document.getElementById('semestre_select');
    const moduleSelect = document.getElementById('module_select');
    const niveauIdHidden = document.getElementById('niveau_id_hidden');
    const matiereSelectInput = document.getElementById('matiere_select_input');

    if (licenceSelect && semestreSelect && moduleSelect && niveauIdHidden) {
      const chefFiliereId = {{ auth()->user()->filiere_id ?? 'null' }};
      const niveaux = @json($niveaux);
      const matieres = @json($matieres);

      const semestresByLicence = {
        'L1': ['S1', 'S2'],
        'L2': ['S3', 'S4'],
        'L3': ['S5', 'S6']
      };

      const modulesBySemestre = {
        'S1': 'M1',
        'S2': 'M2',
        'S3': 'M3',
        'S4': 'M4',
        'S5': 'M5',
        'S6': 'M6'
      };

      function filterMatieres() {
        if (!matiereSelectInput) return;
        const selectedNiveauId = niveauIdHidden.value;
        const selectedSemestre = semestreSelect.value;

        // Reset
        matiereSelectInput.innerHTML = '<option value="">— Choisir la Matière —</option>';

        if (!selectedNiveauId || !selectedSemestre) {
          return;
        }

        // Filter matieres uniquement par niveau (licence)
        const filtered = matieres.filter(m => 
          m.niveau_id == selectedNiveauId
        );

        filtered.forEach(m => {
          const opt = document.createElement('option');
          opt.value = m.id;
          opt.textContent = m.nom + ' (' + m.code + ')';
          matiereSelectInput.appendChild(opt);
        });
      }

      licenceSelect.addEventListener('change', function() {
        const licence = this.value;
        
        // Reset semestre and module
        semestreSelect.innerHTML = '<option value="">— Choisir le Semestre —</option>';
        moduleSelect.innerHTML = '<option value="">— Choisir le Module —</option>';
        niveauIdHidden.value = '';

        if (!licence) {
          filterMatieres();
          return;
        }

        // Find matching niveau ID for this licence
        const matchedNiveau = niveaux.find(n => 
          n.libelle.startsWith(licence)
        );

        if (matchedNiveau) {
          niveauIdHidden.value = matchedNiveau.id;
        }

        // Populate Semestres
        const sems = semestresByLicence[licence] || [];
        sems.forEach(s => {
          const opt = document.createElement('option');
          opt.value = s;
          opt.textContent = 'Semestre ' + s.substring(1);
          semestreSelect.appendChild(opt);
        });

        filterMatieres();
      });

      semestreSelect.addEventListener('change', function() {
        const sem = this.value;
        moduleSelect.innerHTML = '<option value="">— Choisir le Module —</option>';

        if (!sem) {
          filterMatieres();
          return;
        }

        const mod = modulesBySemestre[sem];
        if (mod) {
          const opt = document.createElement('option');
          opt.value = mod;
          opt.textContent = 'Module ' + mod.substring(1);
          opt.selected = true;
          moduleSelect.appendChild(opt);
        }

        filterMatieres();
      });
    }
  });
</script>
</body>
</html>
