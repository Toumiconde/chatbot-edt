<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Tableau de bord en temps réel — emplois du temps, alertes et annonces UGANC">
  <title>Tableau de bord — UGANC</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Inter', sans-serif;
      background: #060D1A;
      color: #E2E8F0;
      min-height: 100vh;
    }

    /* ══════════ HEADER ══════════ */
    .board-header {
      background: linear-gradient(135deg, #0D1B2A 0%, #1B4FD8 100%);
      padding: 0 32px;
      height: 70px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid rgba(255,255,255,.08);
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 4px 30px rgba(0,0,0,.5);
    }
    .header-left { display: flex; align-items: center; gap: 14px; }
    .header-logo {
      width: 42px; height: 42px;
      background: rgba(255,255,255,.12);
      border: 1.5px solid rgba(255,255,255,.2);
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
    }
    .header-logo svg { color: #fff; }
    .header-title h1 { color: #fff; font-size: 17px; font-weight: 700; letter-spacing: -.02em; }
    .header-title p  { color: rgba(255,255,255,.5); font-size: 11.5px; }
    .header-right { display: flex; align-items: center; gap: 16px; }
    .live-clock {
      background: rgba(255,255,255,.08);
      border: 1px solid rgba(255,255,255,.12);
      border-radius: 50px;
      padding: 6px 14px;
      font-size: 13px; font-weight: 600; color: #fff;
      font-variant-numeric: tabular-nums;
      letter-spacing: .02em;
    }
    .refresh-badge {
      display: flex; align-items: center; gap: 6px;
      background: rgba(74,222,128,.12);
      border: 1px solid rgba(74,222,128,.25);
      border-radius: 50px;
      padding: 5px 12px;
      font-size: 11.5px; color: #4ADE80; font-weight: 500;
    }
    .refresh-dot {
      width: 7px; height: 7px; background: #4ADE80;
      border-radius: 50%; animation: pulse 2s infinite;
    }
    @keyframes pulse {
      0%,100% { opacity: 1; box-shadow: 0 0 0 0 rgba(74,222,128,.4); }
      50%      { opacity: .7; box-shadow: 0 0 0 5px rgba(74,222,128,0); }
    }
    .nav-links { display: flex; gap: 8px; }
    .nav-link {
      display: flex; align-items: center; gap: 6px;
      padding: 7px 14px;
      border-radius: 50px;
      font-size: 12.5px; font-weight: 500; color: rgba(255,255,255,.7);
      text-decoration: none;
      border: 1px solid transparent;
      transition: all .2s;
    }
    .nav-link:hover {
      background: rgba(255,255,255,.1);
      border-color: rgba(255,255,255,.15);
      color: #fff;
    }
    .nav-link.active {
      background: rgba(255,255,255,.15);
      border-color: rgba(255,255,255,.2);
      color: #fff;
    }

    /* ══════════ TICKER URGENT ══════════ */
    #urgent-ticker {
      background: #7F1D1D;
      border-bottom: 1px solid #991B1B;
      padding: 10px 32px;
      display: flex; align-items: center; gap: 12px;
      overflow: hidden;
    }
    #urgent-ticker.hidden { display: none; }
    .ticker-label {
      display: flex; align-items: center; gap: 6px;
      background: #EF4444; color: #fff;
      padding: 3px 10px; border-radius: 50px;
      font-size: 11px; font-weight: 700;
      text-transform: uppercase; letter-spacing: .06em;
      flex-shrink: 0;
    }
    .ticker-content {
      flex: 1; overflow: hidden;
      font-size: 13px; color: #FCA5A5; font-weight: 500;
    }
    .ticker-inner {
      display: flex; gap: 60px;
      animation: ticker 30s linear infinite;
      white-space: nowrap;
    }
    @keyframes ticker {
      from { transform: translateX(0); }
      to   { transform: translateX(-50%); }
    }

    /* ══════════ MAIN GRID ══════════ */
    .board-main {
      padding: 28px 32px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
      max-width: 1400px;
      margin: 0 auto;
    }
    @media (max-width: 900px) {
      .board-main { grid-template-columns: 1fr; padding: 16px; }
      .board-header { padding: 0 16px; }
      #urgent-ticker { padding: 10px 16px; }
    }

    /* ══════════ COLONNES ══════════ */
    .board-col { display: flex; flex-direction: column; gap: 16px; }

    .col-header {
      display: flex; align-items: center; justify-content: space-between;
    }
    .col-title {
      display: flex; align-items: center; gap: 10px;
      font-size: 15px; font-weight: 700; color: #F1F5F9;
    }
    .col-title .icon-wrap {
      width: 34px; height: 34px;
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
    }
    .icon-wrap.blue  { background: rgba(27,79,216,.2); }
    .icon-wrap.amber { background: rgba(245,158,11,.2); }
    .icon-wrap.red   { background: rgba(239,68,68,.2); }
    .icon-wrap.green { background: rgba(16,185,129,.2); }
    .icon-wrap.blue  svg { color: #60A5FA; }
    .icon-wrap.amber svg { color: #FCD34D; }
    .icon-wrap.red   svg { color: #FCA5A5; }
    .icon-wrap.green svg { color: #6EE7B7; }

    .count-badge {
      background: rgba(255,255,255,.08);
      border: 1px solid rgba(255,255,255,.1);
      border-radius: 50px;
      padding: 2px 10px;
      font-size: 11.5px; color: #94A3B8; font-weight: 500;
    }

    /* ══════════ CARDS ══════════ */
    .card-list { display: flex; flex-direction: column; gap: 10px; }

    .card {
      background: #0F1A2A;
      border: 1px solid rgba(255,255,255,.07);
      border-radius: 16px;
      padding: 16px 18px;
      transition: border-color .2s, transform .2s;
      animation: cardIn .35s ease;
      position: relative;
      overflow: hidden;
    }
    .card::before {
      content: '';
      position: absolute; top: 0; left: 0;
      width: 4px; height: 100%;
      border-radius: 4px 0 0 4px;
    }
    .card.info    { border-left: none; } .card.info::before    { background: #3B82F6; }
    .card.warning { border-left: none; } .card.warning::before { background: #F59E0B; }
    .card.danger  { border-left: none; } .card.danger::before  { background: #EF4444; }
    .card.success { border-left: none; } .card.success::before { background: #10B981; }
    .card.modif   { border-left: none; } .card.modif::before   { background: #8B5CF6; }

    .card:hover { border-color: rgba(255,255,255,.14); transform: translateY(-2px); }

    @keyframes cardIn {
      from { opacity: 0; transform: translateY(10px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .card-top {
      display: flex; align-items: flex-start; justify-content: space-between;
      gap: 10px; margin-bottom: 8px;
    }
    .card-titre {
      font-size: 14px; font-weight: 700; color: #F1F5F9;
      line-height: 1.3;
    }
    .urgent-pin {
      display: flex; align-items: center; gap: 4px;
      background: #7F1D1D;
      border: 1px solid #991B1B;
      border-radius: 50px;
      padding: 2px 9px;
      font-size: 10px; font-weight: 700; color: #FCA5A5;
      text-transform: uppercase; letter-spacing: .05em;
      flex-shrink: 0;
      animation: urgentBlink 1.5s ease-in-out infinite;
    }
    @keyframes urgentBlink {
      0%,100% { opacity: 1; }
      50%      { opacity: .6; }
    }

    .card-message {
      font-size: 13px; color: #94A3B8; line-height: 1.6; margin-bottom: 10px;
    }
    .card-meta {
      display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
    }
    .meta-pill {
      display: flex; align-items: center; gap: 4px;
      font-size: 11px; color: #64748B; font-weight: 500;
    }
    .meta-pill svg { color: #475569; }
    .meta-tag {
      background: rgba(255,255,255,.06);
      border: 1px solid rgba(255,255,255,.08);
      padding: 2px 8px; border-radius: 50px;
      font-size: 10.5px; color: #7C8FA6; font-weight: 600;
    }

    /* Modification card */
    .modif-change {
      display: flex; align-items: center; gap: 8px;
      margin: 8px 0; flex-wrap: wrap;
    }
    .modif-before {
      background: rgba(239,68,68,.12);
      border: 1px solid rgba(239,68,68,.25);
      padding: 4px 10px; border-radius: 8px;
      font-size: 12px; color: #FCA5A5; font-weight: 600;
      text-decoration: line-through;
    }
    .modif-arrow { color: #6B7280; }
    .modif-after {
      background: rgba(16,185,129,.12);
      border: 1px solid rgba(16,185,129,.25);
      padding: 4px 10px; border-radius: 8px;
      font-size: 12px; color: #6EE7B7; font-weight: 600;
    }

    /* ══════════ EMPTY STATE ══════════ */
    .empty-state {
      background: #0F1A2A;
      border: 1.5px dashed rgba(255,255,255,.08);
      border-radius: 16px;
      padding: 32px 20px;
      text-align: center;
      display: flex; flex-direction: column; align-items: center; gap: 10px;
    }
    .empty-state svg { color: #1E3A5F; }
    .empty-state p { color: #334155; font-size: 13px; }

    /* ══════════ LOADING ══════════ */
    .loading-state {
      display: flex; flex-direction: column; gap: 10px;
    }
    .skel {
      background: linear-gradient(90deg, #0F1A2A 25%, #162032 50%, #0F1A2A 75%);
      background-size: 200% 100%;
      animation: shimmer 1.4s infinite;
      border-radius: 16px; height: 90px;
    }
    @keyframes shimmer {
      from { background-position: 200% 0; }
      to   { background-position: -200% 0; }
    }

    /* ══════════ LAST UPDATE ══════════ */
    .last-update {
      text-align: center; padding: 12px;
      font-size: 11.5px; color: #334155;
    }

    /* ══════════ ADMIN BTN FLOAT ══════════ */
    .admin-fab {
      position: fixed; bottom: 28px; right: 28px;
      width: 52px; height: 52px;
      background: linear-gradient(135deg, #1B4FD8, #0EA5E9);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      text-decoration: none;
      box-shadow: 0 8px 30px rgba(27,79,216,.45);
      transition: transform .2s, box-shadow .2s;
      z-index: 50;
    }
    .admin-fab:hover {
      transform: scale(1.1) rotate(10deg);
      box-shadow: 0 12px 40px rgba(27,79,216,.6);
    }
    .admin-fab svg { color: #fff; }
  </style>
</head>
<body>

<!-- ═══ HEADER ═══ -->
<header class="board-header">
  <div class="header-left">
    <div class="header-logo">
      <i data-lucide="layout-dashboard" style="width:20px;height:20px"></i>
    </div>
    <div class="header-title">
      <h1>Tableau de bord — UGANC</h1>
      <p>Emplois du temps · Alertes · Annonces en temps réel</p>
    </div>
  </div>
  <div class="header-right" style="display:flex; align-items:center; gap: 12px;">
    <div class="nav-links">
      @auth
        @if(auth()->user()->isProf())
          <a href="{{ route('professor.cours') }}" class="nav-link">
            <i data-lucide="book-open" style="width:14px;height:14px"></i> Mes cours
          </a>
        @endif
        @if(auth()->user()->isChef() || auth()->user()->isProf())
          <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i data-lucide="settings" style="width:14px;height:14px"></i> Administration
          </a>
        @endif
      @endauth
      <a href="/chatbot" class="nav-link">
        <i data-lucide="message-circle" style="width:14px;height:14px"></i> Chatbot
      </a>
      <a href="/tableau-de-bord" class="nav-link active">
        <i data-lucide="layout-dashboard" style="width:14px;height:14px"></i> Tableau de bord
      </a>
      @auth
      <a href="{{ route('profile') }}" class="nav-link">
        <i data-lucide="user" style="width:14px;height:14px"></i> Mon Profil
      </a>
      @endauth
    </div>
    
    @auth
    <form method="POST" action="{{ route('logout') }}" style="margin:0">
      @csrf
      <button type="submit" class="nav-link" style="background:transparent;cursor:pointer;color:#FCA5A5;border:none;padding:7px 14px;display:flex;align-items:center;gap:6px;">
        <i data-lucide="log-out" style="width:14px;height:14px"></i> Déconnexion
      </button>
    </form>
    @endauth

    <!-- Cloche de notification interactive (Étudiants) -->
    <div style="position:relative; margin-left:8px;">
      <button id="bell-btn" style="background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12); border-radius:10px; cursor:pointer; color:#fff; display:flex; align-items:center; justify-content:center; position:relative; width:38px; height:38px; transition: background .2s;">
        <i data-lucide="bell" style="width:18px;height:18px;"></i>
        <span id="bell-badge" style="display:none; position:absolute; top:3px; right:3px; width:8px; height:8px; background:#EF4444; border-radius:50%; border:1.5px solid #060D1A;"></span>
      </button>
      <!-- Dropdown -->
      <div id="bell-dropdown" style="display:none; position:absolute; right:0; top:46px; width:340px; background:#0F1A2A; border:1px solid rgba(255,255,255,.1); border-radius:16px; box-shadow:0 15px 35px rgba(0,0,0,.6); padding:16px; z-index:200; backdrop-filter: blur(10px);">
        <div style="font-weight:700; font-size:14px; margin-bottom:12px; display:flex; justify-content:space-between; align-items:center; border-bottom: 1px solid rgba(255,255,255,.08); padding-bottom:8px;">
          <span style="color:#fff">Mises à jour récentes</span>
          <span id="bell-count" style="display:none; font-size:11px; background:#3B82F6; color:#fff; padding:2px 8px; border-radius:20px; font-weight:600;">0 nouvelle</span>
        </div>
        <div id="bell-items" style="max-height:280px; overflow-y:auto; padding-right:4px;">
          <div style="color:#64748B; font-size:13px; text-align:center; padding:24px 0;">
            <i data-lucide="check" style="width:24px;height:24px;margin-bottom:8px;color:#3B82F6;display:block;margin:0 auto 8px;"></i>
            Aucune mise à jour récente
          </div>
        </div>
      </div>
    </div>

    <div class="live-clock" id="clock" style="margin-left:8px;">--:--:--</div>
    <div class="refresh-badge">
      <div class="refresh-dot"></div>
      <span>En direct</span>
    </div>
  </div>
</header>

<!-- ═══ TICKER URGENCES ═══ -->
<div id="urgent-ticker" class="hidden">
  <div class="ticker-label">
    <i data-lucide="zap" style="width:12px;height:12px"></i> URGENT
  </div>
  <div class="ticker-content">
    <div class="ticker-inner" id="ticker-text"></div>
  </div>
</div>

<!-- ═══ MAIN ═══ -->
<main class="board-main">

  <!-- COLONNE GAUCHE : Annonces -->
  <div class="board-col">
    <div class="col-header">
      <div class="col-title">
        <div class="icon-wrap amber">
          <i data-lucide="megaphone" style="width:16px;height:16px"></i>
        </div>
        Annonces & Communications
      </div>
      <span class="count-badge" id="count-annonces">0</span>
    </div>
    <div class="card-list" id="list-annonces">
      <div class="loading-state">
        <div class="skel"></div>
        <div class="skel" style="height:70px"></div>
        <div class="skel" style="height:110px"></div>
      </div>
    </div>
  </div>

  <!-- COLONNE DROITE : Modifications EDT -->
  <div class="board-col">
    <div class="col-header">
      <div class="col-title">
        <div class="icon-wrap red">
          <i data-lucide="clock-4" style="width:16px;height:16px"></i>
        </div>
        Modifications d'horaires
      </div>
      <span class="count-badge" id="count-modifs">0</span>
    </div>
    <div class="card-list" id="list-modifs">
      <div class="loading-state">
        <div class="skel"></div>
        <div class="skel"></div>
        <div class="skel" style="height:70px"></div>
      </div>
    </div>
  </div>

</main>

<div class="last-update">Dernière mise à jour : <span id="last-update-time">—</span></div>

<!-- Bouton flottant admin -->
<a href="/admin/dashboard" class="admin-fab" title="Espace administrateur">
  <i data-lucide="settings" style="width:22px;height:22px"></i>
</a>

<script>
// ══════════════════════════════════════════
// HORLOGE EN TEMPS RÉEL
// ══════════════════════════════════════════
function startClock() {
  const el = document.getElementById('clock');
  setInterval(() => {
    const now = new Date();
    el.textContent = now.toLocaleTimeString('fr-FR');
  }, 1000);
}

// ══════════════════════════════════════════
// UTILITAIRES
// ══════════════════════════════════════════
function fmtHeure(h) { return h ? String(h).substring(0, 5) : '--:--'; }

function timeAgo(dateStr) {
  const diff = Math.floor((Date.now() - new Date(dateStr)) / 1000);
  if (diff < 60)    return 'à l\'instant';
  if (diff < 3600)  return `il y a ${Math.floor(diff/60)} min`;
  if (diff < 86400) return `il y a ${Math.floor(diff/3600)} h`;
  return `il y a ${Math.floor(diff/86400)} j`;
}

// ══════════════════════════════════════════
// TICKER URGENT
// ══════════════════════════════════════════
function updateTicker(urgents) {
  const ticker = document.getElementById('urgent-ticker');
  const text   = document.getElementById('ticker-text');
  if (!urgents.length) { ticker.classList.add('hidden'); return; }

  ticker.classList.remove('hidden');
  const content = urgents.map(a => `🔴 ${a.titre} : ${a.message}`).join('   —   ');
  // Doubler le texte pour boucle infinie
  text.textContent = content + '   —   ' + content;
  lucide.createIcons();
}

// ══════════════════════════════════════════
// RENDU DES ANNONCES
// ══════════════════════════════════════════
function renderAnnonces(annonces) {
  const list = document.getElementById('list-annonces');
  document.getElementById('count-annonces').textContent = annonces.length;

  if (!annonces.length) {
    list.innerHTML = `
      <div class="empty-state">
        <i data-lucide="bell-off" style="width:36px;height:36px"></i>
        <p>Aucune annonce pour le moment</p>
      </div>`;
    lucide.createIcons();
    return;
  }

  const typeIcon = { info: 'info', warning: 'alert-triangle', danger: 'alert-octagon', success: 'check-circle-2' };

  list.innerHTML = annonces.map(a => `
    <div class="card ${a.type}">
      <div class="card-top">
        <div class="card-titre">
          <i data-lucide="${typeIcon[a.type] || 'info'}" style="width:14px;height:14px;margin-right:6px;vertical-align:middle"></i>
          ${a.titre}
        </div>
        ${a.urgent ? '<span class="urgent-pin"><i data-lucide="zap" style="width:10px;height:10px"></i> Urgent</span>' : ''}
      </div>
      <div class="card-message">${a.message}</div>
      <div class="card-meta">
        ${a.auteur ? `<div class="meta-pill"><i data-lucide="user" style="width:11px;height:11px"></i> ${a.auteur}</div>` : ''}
        ${a.filiere ? `<span class="meta-tag">${a.filiere.nom}</span>` : '<span class="meta-tag">Toutes filières</span>'}
        ${a.niveau  ? `<span class="meta-tag">${a.niveau.libelle}</span>` : ''}
        <div class="meta-pill" style="margin-left:auto">
          <i data-lucide="clock" style="width:11px;height:11px"></i>
          ${timeAgo(a.created_at)}
        </div>
      </div>
    </div>
  `).join('');

  lucide.createIcons();
  updateTicker(annonces.filter(a => a.urgent));
}

// ══════════════════════════════════════════
// RENDU DES MODIFICATIONS EDT
// ══════════════════════════════════════════
function renderModifications(modifs) {
  const list = document.getElementById('list-modifs');
  document.getElementById('count-modifs').textContent = modifs.length;

  if (!modifs.length) {
    list.innerHTML = `
      <div class="empty-state">
        <i data-lucide="check-circle-2" style="width:36px;height:36px"></i>
        <p>Aucune modification d'horaire récente</p>
      </div>`;
    lucide.createIcons();
    return;
  }

  list.innerHTML = modifs.map(m => {
    const edt     = m.emploi || {};
    const matiere = edt.matiere?.nom   || 'Cours';
    const filiere = edt.filiere?.nom   || '';
    const niveau  = edt.niveau?.libelle || '';

    return `
      <div class="card modif">
        <div class="card-top">
          <div class="card-titre">
            <i data-lucide="book-open" style="width:14px;height:14px;margin-right:6px;vertical-align:middle"></i>
            ${matiere}
          </div>
          <div class="meta-pill">
            <i data-lucide="clock" style="width:11px;height:11px"></i>
            ${timeAgo(m.date_modif || m.created_at)}
          </div>
        </div>
        <div class="modif-change">
          <span class="modif-before">${m.ancien_jour || '?'} ${fmtHeure(m.ancienne_heure)}</span>
          <span class="modif-arrow">→</span>
          <span class="modif-after">${m.nouveau_jour || '?'} ${fmtHeure(m.nouvelle_heure)}</span>
        </div>
        <div class="card-meta">
          ${filiere ? `<span class="meta-tag">${filiere}</span>` : ''}
          ${niveau  ? `<span class="meta-tag">${niveau}</span>`  : ''}
          ${m.motif ? `<div class="meta-pill"><i data-lucide="message-square" style="width:11px;height:11px"></i> ${m.motif}</div>` : ''}
        </div>
      </div>
    `;
  }).join('');

  lucide.createIcons();
}

// ══════════════════════════════════════════
// MISE À JOUR DE LA CLOCHE DE NOTIFICATION
// ══════════════════════════════════════════
function updateBellDropdown(annonces, modifs) {
  const itemsContainer = document.getElementById('bell-items');
  const badge = document.getElementById('bell-badge');
  const countSpan = document.getElementById('bell-count');
  if (!itemsContainer) return;

  // Combiner les annonces et les modifications
  let allNotifs = [];

  annonces.forEach(a => {
    allNotifs.push({
      id: 'ann_' + a.id,
      date: new Date(a.created_at || a.date_modif),
      title: a.urgent ? '🔴 Annonce urgente' : '📢 Nouvelle Annonce',
      text: `${a.auteur ? a.auteur + ' : ' : ''}${a.message}`,
      icon: 'megaphone',
      color: a.urgent ? '#EF4444' : '#3B82F6'
    });
  });

  modifs.forEach(m => {
    const matiere = m.emploi && m.emploi.matiere ? m.emploi.matiere.nom : 'cours';
    allNotifs.push({
      id: 'mod_' + m.id,
      date: new Date(m.created_at || m.date_modif),
      title: '📅 Changement d\'horaire',
      text: `Le cours de ${matiere} est déplacé du ${m.ancien_jour} au ${m.nouveau_jour}.`,
      icon: 'calendar-clock',
      color: '#F59E0B'
    });
  });

  // Trier du plus récent au plus ancien
  allNotifs.sort((a, b) => b.date - a.date);

  // Garder les 6 dernières
  allNotifs = allNotifs.slice(0, 6);

  if (allNotifs.length === 0) {
    itemsContainer.innerHTML = `
      <div style="color:#64748B; font-size:13px; text-align:center; padding:24px 0;">
        <i data-lucide="check" style="width:24px;height:24px;margin-bottom:8px;color:#10B981;display:block;margin:0 auto 8px;"></i>
        Aucune mise à jour récente
      </div>
    `;
    badge.style.display = 'none';
    countSpan.style.display = 'none';
    lucide.createIcons();
    return;
  }

  // Déterminer s'il y a de nouvelles notifications non lues
  const lastSeen = localStorage.getItem('last_notif_seen') || '0';
  const newestTime = allNotifs[0].date.getTime();
  let unreadCount = 0;

  allNotifs.forEach(n => {
    if (n.date.getTime() > parseInt(lastSeen)) {
      unreadCount++;
    }
  });

  if (unreadCount > 0) {
    badge.style.display = 'block';
    countSpan.style.display = 'inline-block';
    countSpan.textContent = `${unreadCount} nouvelle${unreadCount > 1 ? 's' : ''}`;
  } else {
    badge.style.display = 'none';
    countSpan.style.display = 'none';
  }

  // Générer le HTML
  itemsContainer.innerHTML = allNotifs.map(n => {
    return `
      <div style="border-bottom:1px solid rgba(255,255,255,.05); padding:10px 0; font-size:12.5px; line-height:1.4;">
        <div style="font-weight:700; color:${n.color}; margin-bottom:4px; display:flex; align-items:center; gap:6px;">
          <i data-lucide="${n.icon}" style="width:12px;height:12px;color:${n.color}"></i>
          ${n.title}
        </div>
        <div style="color:#94A3B8;">${n.text}</div>
        <div style="font-size:10px; color:#64748B; margin-top:6px; text-align:right;">${timeAgo(n.date)}</div>
      </div>
    `;
  }).join('');

  itemsContainer.setAttribute('data-newest-time', newestTime);
  lucide.createIcons();
}

// ══════════════════════════════════════════
// CHARGEMENT & POLLING
// ══════════════════════════════════════════
async function loadAll() {
  try {
    const [resA, resM] = await Promise.all([
      fetch('/board/annonces'),
      fetch('/board/modifications'),
    ]);
    const annonces = await resA.json();
    const modifs   = await resM.json();

    renderAnnonces(annonces);
    renderModifications(modifs);
    updateBellDropdown(annonces, modifs);

    const now = new Date();
    document.getElementById('last-update-time').textContent =
      now.toLocaleTimeString('fr-FR');
  } catch (e) {
    console.error('Erreur de chargement :', e);
  }
}

// ══════════════════════════════════════════
// DÉMARRAGE
// ══════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
  lucide.createIcons();
  startClock();
  loadAll();

  // Gestion de la cloche
  const bellBtn = document.getElementById('bell-btn');
  const bellDropdown = document.getElementById('bell-dropdown');
  const bellBadge = document.getElementById('bell-badge');
  const bellCount = document.getElementById('bell-count');
  const itemsContainer = document.getElementById('bell-items');

  if (bellBtn && bellDropdown) {
    bellBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpening = bellDropdown.style.display !== 'block';
      bellDropdown.style.display = isOpening ? 'block' : 'none';

      if (isOpening) {
        // Enregistrer la date du plus récent comme "vu"
        const newestTime = itemsContainer.getAttribute('data-newest-time');
        if (newestTime) {
          localStorage.setItem('last_notif_seen', newestTime);
        }
        bellBadge.style.display = 'none';
        bellCount.style.display = 'none';
      }
    });

    document.addEventListener('click', () => {
      bellDropdown.style.display = 'none';
    });

    bellDropdown.addEventListener('click', (e) => {
      e.stopPropagation();
    });
  }

  // Rafraîchissement automatique toutes les 30 secondes
  setInterval(loadAll, 30_000);
});
</script>
</body>
</html>
