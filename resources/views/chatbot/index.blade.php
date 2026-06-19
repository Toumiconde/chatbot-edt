<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Chatbot EDT — UGANC</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <!-- Lucide Icons — icônes professionnelles SVG -->
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    * { font-family: 'Inter', sans-serif; box-sizing: border-box; margin: 0; padding: 0; }
 
    body {
      background: linear-gradient(135deg, #0D1B2A 0%, #1B4FD8 100%);
      min-height: 100vh;
      display: flex; align-items: center; justify-content: center;
      padding: 16px;
    }
 
    /* ── CONTENEUR ── */
    .chat-container {
      width: 100%; max-width: 500px;
      height: 88vh; max-height: 740px;
      background: #F8FAFF;
      border-radius: 28px;
      box-shadow: 0 40px 100px rgba(0,0,0,.4);
      display: flex; flex-direction: column;
      overflow: hidden;
    }
 
    /* ── HEADER ── */
    .chat-header {
      background: linear-gradient(135deg, #0D1B2A 0%, #1B4FD8 100%);
      padding: 16px 20px;
      display: flex; align-items: center; gap: 12px;
      flex-shrink: 0;
    }
 
    .header-logo {
      width: 44px; height: 44px;
      background: rgba(255,255,255,.12);
      border: 1.5px solid rgba(255,255,255,.25);
      border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
 
    .header-logo svg { color: #fff; }
 
    .header-info h1 {
      color: #fff; font-size: 15px; font-weight: 700; margin-bottom: 2px;
    }
 
    .header-info p { color: rgba(255,255,255,.55); font-size: 11.5px; }
 
    .header-status {
      margin-left: auto;
      display: flex; align-items: center; gap: 5px;
    }
 
    .status-dot {
      width: 8px; height: 8px; background: #4ADE80;
      border-radius: 50%; animation: pulse 2s infinite;
    }
 
    @keyframes pulse {
      0%,100% { opacity: 1; box-shadow: 0 0 0 0 rgba(74,222,128,.4); }
      50%      { opacity: .7; box-shadow: 0 0 0 4px rgba(74,222,128,0); }
    }
 
    .status-text { color: rgba(255,255,255,.6); font-size: 11px; font-weight: 500; }
 
    /* ── BREADCRUMB ── */
    #breadcrumb {
      padding: 8px 16px;
      background: #EFF6FF;
      border-bottom: 1px solid #DBEAFE;
      display: flex; align-items: center; gap: 6px;
      font-size: 11.5px; color: #3B82F6; font-weight: 500;
      flex-shrink: 0; flex-wrap: wrap;
      min-height: 34px;
    }
 
    #breadcrumb span { display: flex; align-items: center; gap: 3px; }
    #breadcrumb .sep { color: #93C5FD; font-size: 10px; }
 
    /* ── CORPS CHAT ── */
    #chat-body {
      flex: 1; overflow-y: auto;
      padding: 16px 14px;
      display: flex; flex-direction: column; gap: 10px;
      scroll-behavior: smooth;
      background: #F8FAFF;
    }
 
    #chat-body::-webkit-scrollbar { width: 3px; }
    #chat-body::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 2px; }
 
    /* ── BULLES ── */
    .msg-bot {
      display: flex; align-items: flex-end; gap: 8px;
      animation: msgIn .22s ease;
    }
 
    .msg-user {
      display: flex; justify-content: flex-end;
      animation: msgIn .22s ease;
    }
 
    @keyframes msgIn {
      from { opacity: 0; transform: translateY(6px); }
      to   { opacity: 1; transform: translateY(0); }
    }
 
    .bot-av {
      width: 30px; height: 30px;
      background: linear-gradient(135deg, #1B4FD8, #0EA5E9);
      border-radius: 50%; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center;
    }
 
    .bot-av svg { color: #fff; }
 
    .bubble-bot {
      background: #fff;
      border: 1px solid #E2E8F0;
      border-radius: 18px 18px 18px 4px;
      padding: 10px 14px;
      max-width: 80%;
      font-size: 13.5px; color: #1E293B; line-height: 1.6;
      box-shadow: 0 1px 4px rgba(0,0,0,.05);
    }
 
    .bubble-user {
      background: linear-gradient(135deg, #1B4FD8, #0EA5E9);
      border-radius: 18px 18px 4px 18px;
      padding: 10px 14px;
      max-width: 80%;
      font-size: 13.5px; color: #fff; line-height: 1.6;
      box-shadow: 0 3px 10px rgba(27,79,216,.3);
    }
 
    /* ── ALERT CARD ── */
    .alert-card {
      background: #FFFBEB;
      border: 1px solid #FCD34D;
      border-left: 4px solid #F59E0B;
      border-radius: 12px;
      padding: 10px 14px;
      font-size: 12.5px; color: #78350F;
      animation: msgIn .22s ease;
      display: flex; flex-direction: column; gap: 4px;
    }
 
    .alert-card .ac-title {
      display: flex; align-items: center; gap: 6px;
      font-weight: 600; font-size: 13px; color: #92400E;
    }
 
    .alert-card .ac-body {
      display: flex; align-items: center; gap: 6px; flex-wrap: wrap;
    }
 
    .ac-tag {
      background: #FEF3C7; border: 1px solid #FCD34D;
      padding: 2px 8px; border-radius: 50px;
      font-size: 11px; font-weight: 600; color: #92400E;
    }
 
    .ac-arrow { color: #F59E0B; }
    .ac-motif { font-size: 11.5px; color: #B45309; display: flex; align-items: center; gap: 4px; }
 
    /* ── TABLEAU ── */
    .table-wrapper {
      background: #fff;
      border: 1px solid #E2E8F0;
      border-radius: 14px; overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,.05);
      animation: msgIn .22s ease;
      overflow-x: auto;
    }
 
    .edt-table { width: 100%; border-collapse: collapse; font-size: 12px; }
 
    .edt-table thead tr {
      background: linear-gradient(135deg, #0D1B2A 0%, #1B4FD8 100%);
    }
 
    .edt-table thead th {
      padding: 9px 10px; text-align: left;
      color: #fff; font-size: 11px; font-weight: 600;
      letter-spacing: .03em; white-space: nowrap;
    }
 
    .edt-table thead th .th-inner {
      display: flex; align-items: center; gap: 5px;
    }
 
    .edt-table tbody tr:nth-child(even) { background: #F0F6FF; }
    .edt-table tbody tr { transition: background .15s; }
    .edt-table tbody tr:hover { background: #DBEAFE; }
 
    .edt-table tbody td {
      padding: 8px 10px; color: #334155;
      border-bottom: 1px solid #F1F5F9;
      white-space: nowrap;
    }
 
    .td-heure {
      font-weight: 700; color: #1B4FD8;
      font-family: 'Courier New', monospace; font-size: 11px;
      display: flex; align-items: center; gap: 4px;
    }
 
    .td-matiere { font-weight: 600; color: #0F172A; }
    .td-enseignant { display: flex; align-items: center; gap: 5px; }
    .td-salle { display: flex; align-items: center; gap: 4px; color: #64748B; }
 
    /* ── DAY LABEL ── */
    .day-label {
      display: flex; align-items: center; gap: 8px;
      font-size: 11px; font-weight: 700;
      color: #64748B; letter-spacing: .08em;
      text-transform: uppercase; margin: 4px 0;
    }
    .day-label::before, .day-label::after {
      content: ''; flex: 1; height: 1px; background: #E2E8F0;
    }
 
    /* ── EMPTY ── */
    .empty-msg {
      text-align: center; padding: 20px 16px;
      color: #94A3B8; font-size: 13px;
      background: #F8FAFF;
      border: 1.5px dashed #CBD5E1;
      border-radius: 14px;
      animation: msgIn .22s ease;
      display: flex; flex-direction: column; align-items: center; gap: 8px;
    }
 
    .empty-msg svg { color: #CBD5E1; }
 
    /* ── LOADER ── */
    .loader-wrap { display: flex; align-items: flex-end; gap: 8px; }
    .loader-bubble {
      background: #fff;
      border: 1px solid #E2E8F0;
      border-radius: 18px 18px 18px 4px;
      padding: 13px 16px;
      display: flex; gap: 4px; align-items: center;
      box-shadow: 0 1px 4px rgba(0,0,0,.05);
    }
    .dot {
      width: 7px; height: 7px; background: #94A3B8;
      border-radius: 50%; animation: bounce .9s infinite;
    }
    .dot:nth-child(2) { animation-delay: .15s; }
    .dot:nth-child(3) { animation-delay: .3s; }
 
    @keyframes bounce {
      0%,80%,100% { transform: translateY(0); }
      40%          { transform: translateY(-6px); }
    }
 
    /* ── ZONE OPTIONS ── */
    #chat-options {
      padding: 10px 14px 12px;
      background: #fff;
      border-top: 1px solid #F1F5F9;
      display: flex; flex-wrap: wrap; gap: 6px;
      flex-shrink: 0; min-height: 54px; align-items: center;
    }
 
    /* ── ZONE SAISIE TEXTE ── */
    #chat-input-zone {
      padding: 10px 14px;
      background: #fff;
      border-top: 1px solid #F1F5F9;
      display: flex; align-items: center; gap: 8px;
      flex-shrink: 0;
    }
 
    #chat-input {
      flex: 1;
      border: 1.5px solid #E2E8F0;
      border-radius: 50px;
      padding: 9px 16px;
      font-size: 13px;
      color: #1E293B;
      outline: none;
      transition: border-color .15s;
      background: #F8FAFF;
    }
 
    #chat-input:focus {
      border-color: #1B4FD8;
      background: #fff;
    }
 
    #chat-input::placeholder { color: #94A3B8; }
 
    #chat-send {
      width: 38px; height: 38px;
      border-radius: 50%;
      border: none;
      background: linear-gradient(135deg, #1B4FD8, #0EA5E9);
      color: #fff;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer;
      transition: transform .15s, box-shadow .15s;
      flex-shrink: 0;
    }
 
    #chat-send:hover {
      transform: scale(1.06);
      box-shadow: 0 4px 14px rgba(27,79,216,.35);
    }
 
    #chat-send:active { transform: scale(.96); }
 
    /* ── SUGGESTIONS RAPIDES ── */
    .quick-suggestions {
      display: flex; gap: 6px; flex-wrap: wrap;
      padding: 0 14px 8px;
      background: #fff;
    }
 
    .quick-tag {
      font-size: 11px; color: #94A3B8;
      background: #F1F5F9;
      border: 1px solid #E2E8F0;
      border-radius: 50px;
      padding: 3px 10px;
      cursor: pointer;
      transition: all .15s;
      display: flex; align-items: center; gap: 4px;
    }
 
    .quick-tag:hover {
      background: #DBEAFE; color: #1B4FD8; border-color: #93C5FD;
    }
 
    /* ── BOUTONS ── */
    .opt-btn {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 7px 14px;
      border-radius: 50px;
      font-size: 12.5px; font-weight: 500;
      cursor: pointer;
      transition: all .18s ease;
      white-space: nowrap;
      border: 1.5px solid #1B4FD8;
      background: #fff; color: #1B4FD8;
    }
 
    .opt-btn svg { flex-shrink: 0; }
 
    .opt-btn:hover {
      background: #1B4FD8; color: #fff;
      transform: translateY(-1px);
      box-shadow: 0 4px 14px rgba(27,79,216,.3);
    }
 
    .opt-btn:active { transform: translateY(0); }
 
    .opt-btn.primary { background: #1B4FD8; color: #fff; }
    .opt-btn.primary:hover { background: #1438A0; }
 
    .opt-btn.success { border-color: #10B981; color: #10B981; }
    .opt-btn.success:hover { background: #10B981; color: #fff; box-shadow: 0 4px 14px rgba(16,185,129,.3); }
 
    .opt-btn.danger { border-color: #EF4444; color: #EF4444; }
    .opt-btn.danger:hover { background: #EF4444; color: #fff; box-shadow: 0 4px 14px rgba(239,68,68,.3); }
 
    .opt-btn.warn { border-color: #F59E0B; color: #F59E0B; }
    .opt-btn.warn:hover { background: #F59E0B; color: #fff; }
 
    /* ── RESPONSIVE MOBILE ── */
    @media (max-width: 520px) {
      body { padding: 0; align-items: flex-end; }
      .chat-container {
        max-width: 100%; height: 100vh; max-height: 100vh;
        border-radius: 24px 24px 0 0;
      }
      .opt-btn { font-size: 11.5px; padding: 6px 10px; }
      .quick-tag { font-size: 10px; padding: 3px 8px; }
      .edt-table { font-size: 11px; }
      .edt-table thead th, .edt-table tbody td { padding: 6px 7px; }
    }
    @media (max-width: 380px) {
      .opt-btn { font-size: 10.5px; padding: 5px 8px; }
    }

    /* ── TOAST NOTIFICATION ── */
    #toast {
      position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%) translateY(60px);
      background: #0F172A; color: #fff;
      padding: 10px 20px; border-radius: 50px;
      font-size: 13px; font-weight: 500;
      box-shadow: 0 8px 30px rgba(0,0,0,.3);
      transition: transform .3s cubic-bezier(.34,1.56,.64,1), opacity .3s;
      opacity: 0; pointer-events: none; z-index: 9999;
      display: flex; align-items: center; gap: 8px;
    }
    #toast.show {
      transform: translateX(-50%) translateY(0);
      opacity: 1;
    }
  </style>
</head>
<body>
 
<div class="chat-container">
 
  <!-- ═══ HEADER ═══ -->
  <div class="chat-header">
    <div class="header-logo">
      <i data-lucide="graduation-cap" style="width:22px;height:22px"></i>
    </div>
    <div class="header-info">
      <h1>Chatbot EDT — UGANC</h1>
      <p>Centre Informatique · Groupe 6</p>
    </div>
    <div class="header-status">
      <div class="status-dot"></div>
      <span class="status-text">En ligne</span>
      <!-- Cloche notifications -->
      @auth
      <div style="position:relative;margin-left:8px;">
        <button id="notif-btn" onclick="toggleNotifDropdown()" title="Notifications" style="
          width:30px;height:30px;
          background:rgba(255,255,255,.12);
          border:1px solid rgba(255,255,255,.2);
          border-radius:50%;
          display:flex;align-items:center;justify-content:center;
          cursor:pointer;transition:background .2s;
        " onmouseover="this.style.background='rgba(255,255,255,.22)'" onmouseout="this.style.background='rgba(255,255,255,.12)'">
          <i data-lucide="bell" style="width:14px;height:14px;color:#fff"></i>
        </button>
        <span id="notif-badge" style="
          display:none;position:absolute;top:-4px;right:-4px;
          background:#EF4444;color:#fff;
          font-size:9px;font-weight:700;
          width:16px;height:16px;border-radius:50%;
          display:none;align-items:center;justify-content:center;
          border:1.5px solid #1B4FD8;
        ">0</span>
        <!-- Dropdown notifications -->
        <div id="notif-dropdown" style="
          display:none;position:absolute;top:38px;right:0;
          width:300px;max-height:360px;overflow-y:auto;
          background:#fff;border-radius:14px;
          box-shadow:0 8px 30px rgba(0,0,0,.18);
          border:1px solid #E2E8F0;z-index:9999;
        "></div>
      </div>
      @endauth

      <a href="/tableau-de-bord" title="Tableau de bord" style="
        margin-left:8px;
        width:30px;height:30px;
        background:rgba(255,255,255,.12);
        border:1px solid rgba(255,255,255,.2);
        border-radius:50%;
        display:flex;align-items:center;justify-content:center;
        text-decoration:none;
        transition:background .2s;
      " onmouseover="this.style.background='rgba(255,255,255,.22)'" onmouseout="this.style.background='rgba(255,255,255,.12)'">
        <i data-lucide="layout-dashboard" style="width:14px;height:14px;color:#fff"></i>
      </a>

      @auth
      <form method="POST" action="{{ route('logout') }}" style="margin:0; margin-left:4px;">
        @csrf
        <button type="submit" title="Se déconnecter" style="
          width:30px;height:30px;
          background:rgba(239,68,68,.12);
          border:1px solid rgba(239,68,68,.25);
          border-radius:50%;
          display:flex;align-items:center;justify-content:center;
          text-decoration:none;
          cursor:pointer;
          transition:background .2s;
        " onmouseover="this.style.background='rgba(239,68,68,.25)'" onmouseout="this.style.background='rgba(239,68,68,.12)'">
          <i data-lucide="log-out" style="width:14px;height:14px;color:#FCA5A5"></i>
        </button>
      </form>
      @endauth
    </div>
  </div>
 
  <!-- ═══ BREADCRUMB ═══ -->
  <div id="breadcrumb">
    <span><i data-lucide="home" style="width:12px;height:12px"></i> Accueil</span>
  </div>
 
  <!-- ═══ CORPS ═══ -->
  <div id="chat-body"></div>
 
  <!-- ═══ SUGGESTIONS RAPIDES ═══ -->
  <div class="quick-suggestions" id="quick-suggestions"></div>
 
  <!-- ═══ OPTIONS (boutons) ═══ -->
  <div id="chat-options"></div>
 
  <!-- ═══ SAISIE TEXTE ═══ -->
  <div id="chat-input-zone">
    <input type="text" id="chat-input" placeholder="Écrivez ici… ex : edt, alertes, lundi">
    <button id="chat-send">
      <i data-lucide="send" style="width:16px;height:16px"></i>
    </button>
  </div>
 
</div>
 
<script>
// ─────────────────────────────────────────
// VARIABLES GLOBALES
// ─────────────────────────────────────────
let userRole            = {!! auth()->check() ? json_encode(auth()->user()->role) : 'null' !!};
let userName            = {!! auth()->check() ? json_encode(auth()->user()->name) : 'null' !!};
let selectedFiliereId   = {!! auth()->check() && auth()->user()->filiere_id ? auth()->user()->filiere_id : 'null' !!};
let selectedFiliereNom  = {!! auth()->check() && auth()->user()->filiere ? json_encode(auth()->user()->filiere->nom) : 'null' !!};
let selectedNiveauId    = {!! auth()->check() && auth()->user()->role === 'etudiant' && auth()->user()->niveau_id ? auth()->user()->niveau_id : 'null' !!};
let selectedNiveauNom   = {!! auth()->check() && auth()->user()->role === 'etudiant' && auth()->user()->niveau ? json_encode(auth()->user()->niveau->libelle) : 'null' !!};
let profEnseignantId    = {!! auth()->check() && auth()->user()->role === 'prof' ? auth()->user()->enseignant_id : 'null' !!};
let selectedJour        = null;
let selectedEnseignant  = null;
let conversationHistory = [];
 
// ─────────────────────────────────────────
// BREADCRUMB — fil d'ariane en haut
// ─────────────────────────────────────────
function updateBreadcrumb(steps) {
  const bc = document.getElementById('breadcrumb');
  bc.innerHTML = steps.map((s, i) =>
    `<span>${s}</span>${i < steps.length - 1 ? '<span class="sep">›</span>' : ''}`
  ).join('');
  lucide.createIcons();
}
 
// ─────────────────────────────────────────
// UTILITAIRES
// ─────────────────────────────────────────
function addMessage(texte, type = 'bot') {
  const body = document.getElementById('chat-body');
  const wrap = document.createElement('div');
  if (type === 'bot') {
    wrap.className = 'msg-bot';
    wrap.innerHTML = `
      <div class="bot-av">
        <i data-lucide="bot" style="width:15px;height:15px"></i>
      </div>
      <div class="bubble-bot">${texte}</div>`;
  } else {
    wrap.className = 'msg-user';
    wrap.innerHTML = `<div class="bubble-user">${texte}</div>`;
  }
  body.appendChild(wrap);
  body.scrollTop = body.scrollHeight;
  lucide.createIcons();
}
 
function showOptions(options) {
  const zone = document.getElementById('chat-options');
  zone.innerHTML = '';
  options.forEach(opt => {
    const btn = document.createElement('button');
    btn.className = 'opt-btn' + (opt.style ? ' ' + opt.style : '');
    btn.innerHTML = (opt.icon ? `<i data-lucide="${opt.icon}" style="width:13px;height:13px"></i>` : '') + opt.label;
    btn.onclick = opt.action;
    zone.appendChild(btn);
  });
  lucide.createIcons();
}
 
function showLoader() {
  const body = document.getElementById('chat-body');
  const div = document.createElement('div');
  div.id = 'loader';
  div.className = 'loader-wrap';
  div.innerHTML = `
    <div class="bot-av">
      <i data-lucide="bot" style="width:15px;height:15px"></i>
    </div>
    <div class="loader-bubble">
      <div class="dot"></div><div class="dot"></div><div class="dot"></div>
    </div>`;
  body.appendChild(div);
  body.scrollTop = body.scrollHeight;
  lucide.createIcons();
}
 
function hideLoader() {
  const l = document.getElementById('loader');
  if (l) l.remove();
}
 
function fmtHeure(h) { return h ? h.substring(0, 5) : '--:--'; }
 
// ─────────────────────────────────────────
// TABLEAU EDT
// ─────────────────────────────────────────
function afficherTableau(cours) {
  const body = document.getElementById('chat-body');
  const wrap = document.createElement('div');
  wrap.className = 'table-wrapper';
 
  let rows = '';
  cours.forEach(c => {
    rows += `<tr>
      <td>
        <div class="td-heure">
          <i data-lucide="clock" style="width:11px;height:11px;color:#1B4FD8"></i>
          ${fmtHeure(c.heure_debut)}–${fmtHeure(c.heure_fin)}
        </div>
      </td>
      <td class="td-matiere">${c.matiere ? c.matiere.nom : '—'}</td>
      <td>
        <div class="td-enseignant">
          <i data-lucide="user" style="width:11px;height:11px;color:#64748B"></i>
          ${c.enseignant ? c.enseignant.prenom + ' ' + c.enseignant.nom : '—'}
        </div>
      </td>
      <td>
        <div class="td-salle">
          <i data-lucide="door-open" style="width:11px;height:11px"></i>
          ${c.salle ? c.salle.nom : '—'}
        </div>
      </td>
    </tr>`;
  });
 
  wrap.innerHTML = `
    <table class="edt-table">
      <thead><tr>
        <th><div class="th-inner"><i data-lucide="clock" style="width:12px;height:12px"></i>Horaire</div></th>
        <th><div class="th-inner"><i data-lucide="book-open" style="width:12px;height:12px"></i>Matière</div></th>
        <th><div class="th-inner"><i data-lucide="user" style="width:12px;height:12px"></i>Enseignant</div></th>
        <th><div class="th-inner"><i data-lucide="map-pin" style="width:12px;height:12px"></i>Salle</div></th>
      </tr></thead>
      <tbody>${rows}</tbody>
    </table>`;
 
  body.appendChild(wrap);
  body.scrollTop = body.scrollHeight;
  lucide.createIcons();
}
 
// ─────────────────────────────────────────
// ACCUEIL
// ─────────────────────────────────────────
function accueil() {
  selectedFiliereId = selectedFiliereNom = selectedNiveauId =
  selectedNiveauNom = selectedJour = selectedEnseignant = null;
  conversationHistory = [];
  updateBreadcrumb([
    '<i data-lucide="home" style="width:12px;height:12px"></i> Accueil'
  ]);
  showQuickSuggestions([
    { label: 'edt',     icon: 'calendar' },
    { label: 'alertes', icon: 'bell' },
    { label: 'aide',    icon: 'help-circle' },
  ]);
  addMessage('Que souhaitez-vous faire ?');
  showOptionsAccueilRapide();
}
 
// ─────────────────────────────────────────
// ÉTAPE 1 — Filières
// GET /api/filieres → [{ id, nom, code }]
// ─────────────────────────────────────────
function chargerFilieres() {
  updateBreadcrumb([
    '<i data-lucide="home" style="width:12px;height:12px"></i> Accueil',
    '<i data-lucide="layers" style="width:12px;height:12px"></i> Filière'
  ]);
  showLoader();
  fetch('/api/filieres')
    .then(r => r.json())
    .then(filieres => {
      hideLoader();
      addMessage('Choisissez votre filière :');
      showOptions(filieres.map(f => ({
        label: f.nom, icon: 'graduation-cap',
        action: () => choisirFiliere(f.id, f.nom)
      })));
    })
    .catch(() => { hideLoader(); addMessage('❌ Erreur serveur. Vérifiez que php artisan serve est lancé.'); });
}
 
// ─────────────────────────────────────────
// ÉTAPE 2 — Niveaux
// GET /api/niveaux/{id} → [{ id, libelle }]
// ─────────────────────────────────────────
function choisirFiliere(id, nom) {
  selectedFiliereId = id; selectedFiliereNom = nom;
  addMessage(nom, 'user');
  updateBreadcrumb([
    '<i data-lucide="home" style="width:12px;height:12px"></i> Accueil',
    `<i data-lucide="layers" style="width:12px;height:12px"></i> ${nom}`,
    '<i data-lucide="hash" style="width:12px;height:12px"></i> Niveau'
  ]);
  showLoader();
  fetch('/api/niveaux/' + id)
    .then(r => r.json())
    .then(niveaux => {
      hideLoader();
      addMessage('Choisissez votre niveau :');
      showOptions(niveaux.map(n => ({
        label: n.libelle, icon: 'hash',
        action: () => choisirNiveau(n.id, n.libelle)
      })));
    });
}
 
// ─────────────────────────────────────────
// ÉTAPE 3 — Période
// ─────────────────────────────────────────
function choisirNiveau(id, libelle) {
  selectedNiveauId = id; selectedNiveauNom = libelle;
  addMessage(libelle, 'user');
  updateBreadcrumb([
    '<i data-lucide="home" style="width:12px;height:12px"></i> Accueil',
    `<i data-lucide="layers" style="width:12px;height:12px"></i> ${selectedFiliereNom}`,
    `<i data-lucide="hash" style="width:12px;height:12px"></i> ${libelle}`,
    '<i data-lucide="calendar-days" style="width:12px;height:12px"></i> Période'
  ]);
  addMessage(`Parfait ! Que souhaitez-vous consulter pour <strong>${selectedFiliereNom} — ${libelle}</strong> ?`);
  showQuickSuggestions([
    { label: 'semestriel', icon: 'layout-list' },
    { label: 'semaine',    icon: 'calendar-range' },
    { label: 'pdf',        icon: 'download' },
  ]);
  showOptions([
    { label: '📋 EDT Semestriel complet', icon: 'layout-list',   action: chargerSemesterComplet, style: 'primary' },
    { label: '📅 Par jour',               icon: 'calendar-days', action: afficherChoixJour },
    { label: '📆 Semaine courante',        icon: 'calendar-range', action: chargerSemaine },
  ]);
}
 
// ─────────────────────────────────────────
// ÉTAPE 4a — Choix du jour
// ─────────────────────────────────────────
function afficherChoixJour() {
  addMessage('Choisissez le jour :');
  showOptions(['Lundi','Mardi','Mercredi','Jeudi','Vendredi'].map(j => ({
    label: j, icon: 'sun', action: () => chargerJour(j)
  })));
}
 
// ─────────────────────────────────────────
// ÉTAPE 5a — EDT d'un jour
// GET /api/edt/jour?filiere_id=&niveau_id=&jour=
// ─────────────────────────────────────────
function chargerJour(jour) {
  selectedJour = jour;
  addMessage(jour, 'user');
  showLoader();
  const params = new URLSearchParams({ filiere_id: selectedFiliereId, niveau_id: selectedNiveauId, jour });
  fetch('/api/edt/jour?' + params)
    .then(r => r.json())
    .then(cours => {
      hideLoader();
      if (!Array.isArray(cours) || cours.length === 0) {
        const div = document.createElement('div');
        div.className = 'empty-msg';
        div.innerHTML = `<i data-lucide="moon" style="width:28px;height:28px"></i><span>Aucun cours prévu ce <strong>${jour}</strong>.</span>`;
        document.getElementById('chat-body').appendChild(div);
        lucide.createIcons();
      } else {
        addMessage(`EDT du <strong>${jour}</strong> — ${selectedFiliereNom} ${selectedNiveauNom} :`);
        afficherTableau(cours);
      }
      showOptions([
        { label: 'Télécharger PDF', icon: 'download',  action: () => telechargerPDF(jour), style: 'success' },
        { label: 'Retour accueil',  icon: 'home',      action: accueil,                    style: 'danger'  },
      ]);
    })
    .catch(() => { hideLoader(); addMessage('❌ Erreur lors du chargement.'); });
}
 
// ─────────────────────────────────────────
// ÉTAPE 4b — EDT semaine complète
// GET /api/edt/semaine?filiere_id=&niveau_id=
// ─────────────────────────────────────────
function chargerSemaine() {
  addMessage('Semaine complète', 'user');
  showLoader();
  const params = new URLSearchParams({ filiere_id: selectedFiliereId, niveau_id: selectedNiveauId });
  fetch('/api/edt/semaine?' + params)
    .then(r => r.json())
    .then(data => {
      hideLoader();
      addMessage(`EDT semaine — ${selectedFiliereNom} ${selectedNiveauNom} :`);
      const jours = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi'];
      let total = 0;
      jours.forEach(jour => {
        if (data[jour] && data[jour].length > 0) {
          total += data[jour].length;
          const body = document.getElementById('chat-body');
          const lbl = document.createElement('div');
          lbl.className = 'day-label';
          lbl.textContent = jour;
          body.appendChild(lbl);
          afficherTableau(data[jour]);
        }
      });
      if (total === 0) {
        const div = document.createElement('div');
        div.className = 'empty-msg';
        div.innerHTML = `<i data-lucide="moon" style="width:28px;height:28px"></i><span>Aucun cours cette semaine.</span>`;
        document.getElementById('chat-body').appendChild(div);
        lucide.createIcons();
      }
      showOptions([
        { label: 'Télécharger PDF', icon: 'download', action: () => telechargerPDF(), style: 'success' },
        { label: 'Retour accueil',  icon: 'home',     action: accueil,                style: 'danger'  },
      ]);
    });
}

// ─────────────────────────────────────────
// EDT SEMESTRIEL COMPLET — tous les cours
// par jour pour la filière/niveau choisis
// ─────────────────────────────────────────
function chargerSemesterComplet() {
  addMessage('📋 EDT Semestriel complet', 'user');
  updateBreadcrumb([
    '<i data-lucide="home" style="width:12px;height:12px"></i> Accueil',
    `<i data-lucide="layers" style="width:12px;height:12px"></i> ${selectedFiliereNom}`,
    `<i data-lucide="hash" style="width:12px;height:12px"></i> ${selectedNiveauNom}`,
    '<i data-lucide="layout-list" style="width:12px;height:12px"></i> Semestriel'
  ]);
  showLoader();
  const params = new URLSearchParams({ filiere_id: selectedFiliereId, niveau_id: selectedNiveauId });
  fetch('/api/edt/semaine?' + params)
    .then(r => r.json())
    .then(data => {
      hideLoader();
      const jours = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi'];
      const body = document.getElementById('chat-body');

      // En-tête de présentation
      const header = document.createElement('div');
      header.style.cssText = `
        background: linear-gradient(135deg,#1B4FD8,#7C3AED);
        border-radius: 16px; padding: 16px 18px; margin: 8px 0;
        color: #fff; font-size: 13px;
      `;
      let totalCours = 0;
      jours.forEach(j => { if (data[j]) totalCours += data[j].length; });
      header.innerHTML = `
        <div style="font-size:15px;font-weight:800;margin-bottom:4px">
          📋 Emploi du temps semestriel
        </div>
        <div style="opacity:.8;font-size:12px">${selectedFiliereNom} — ${selectedNiveauNom}</div>
        <div style="margin-top:8px;display:flex;gap:12px;flex-wrap:wrap">
          <span style="background:rgba(255,255,255,.15);padding:3px 10px;border-radius:50px;font-size:11px">
            📚 ${totalCours} cours au total
          </span>
          <span style="background:rgba(255,255,255,.15);padding:3px 10px;border-radius:50px;font-size:11px">
            📅 ${jours.filter(j => data[j] && data[j].length > 0).length} jours actifs
          </span>
        </div>
      `;
      body.appendChild(header);

      // Afficher chaque jour
      let hasAny = false;
      jours.forEach(jour => {
        if (!data[jour] || data[jour].length === 0) return;
        hasAny = true;

        // Label du jour avec style premium
        const dayBanner = document.createElement('div');
        dayBanner.style.cssText = `
          display:flex; align-items:center; gap:8px;
          background:#EFF6FF; border-left:4px solid #1B4FD8;
          border-radius:0 10px 10px 0; padding:8px 14px;
          margin: 10px 0 6px;
          font-size:12px; font-weight:700; color:#1B4FD8;
        `;
        dayBanner.innerHTML = `<i data-lucide="sun" style="width:13px;height:13px"></i> ${jour.toUpperCase()}`;
        body.appendChild(dayBanner);
        lucide.createIcons();

        afficherTableau(data[jour]);
      });

      if (!hasAny) {
        const empty = document.createElement('div');
        empty.className = 'empty-msg';
        empty.innerHTML = `<i data-lucide="moon" style="width:28px;height:28px"></i><span>Aucun cours enregistré pour cette promotion.</span>`;
        body.appendChild(empty);
        lucide.createIcons();
      }

      body.scrollTop = body.scrollHeight;
      showOptions([
        { label: '⬇️ Télécharger PDF', icon: 'download', action: () => telechargerPDF(), style: 'success' },
        { label: '🏠 Retour accueil',  icon: 'home',     action: accueil,               style: 'danger'  },
      ]);
    })
    .catch(() => { hideLoader(); addMessage('❌ Erreur lors du chargement de l\'EDT semestriel.'); });
}

// ─────────────────────────────────────────
// ALERTES
// GET /api/alertes → [{ ancien_jour, ancienne_heure, nouveau_jour, nouvelle_heure, motif, emploi_du_temps }]
// ─────────────────────────────────────────
function chargerAlertes() {
  addMessage('Alertes modifications', 'user');
  updateBreadcrumb([
    '<i data-lucide="home" style="width:12px;height:12px"></i> Accueil',
    '<i data-lucide="bell" style="width:12px;height:12px"></i> Alertes'
  ]);
  showLoader();
  fetch('/api/alertes')
    .then(r => r.json())
    .then(alertes => {
      hideLoader();
      if (!alertes.length) {
        addMessage('✅ Aucune modification dans les 48 dernières heures.');
      } else {
        addMessage(`${alertes.length} modification(s) récente(s) :`);
        const body = document.getElementById('chat-body');
        alertes.forEach(a => {
          const card = document.createElement('div');
          card.className = 'alert-card';
          const mat = a.emploi_du_temps?.matiere?.nom || 'Cours';
          const fil = a.emploi_du_temps?.filiere?.nom || '';
          card.innerHTML = `
            <div class="ac-title">
              <i data-lucide="alert-triangle" style="width:14px;height:14px;color:#F59E0B"></i>
              ${mat} ${fil ? '— ' + fil : ''}
            </div>
            <div class="ac-body">
              <span class="ac-tag">${a.ancien_jour} ${fmtHeure(a.ancienne_heure)}</span>
              <i data-lucide="arrow-right" class="ac-arrow" style="width:14px;height:14px"></i>
              <span class="ac-tag">${a.nouveau_jour} ${fmtHeure(a.nouvelle_heure)}</span>
            </div>
            ${a.motif ? `<div class="ac-motif"><i data-lucide="message-circle" style="width:12px;height:12px"></i>${a.motif}</div>` : ''}
          `;
          body.appendChild(card);
          body.scrollTop = body.scrollHeight;
        });
        lucide.createIcons();
      }
      showOptions([{ label: 'Retour accueil', icon: 'home', action: accueil, style: 'primary' }]);
    });
}
 
// ─────────────────────────────────────────
// ENSEIGNANTS
// GET /api/enseignants → [{ id, nom, prenom }]
// ─────────────────────────────────────────
function chargerEnseignants() {
  addMessage('Vue Enseignant', 'user');
  updateBreadcrumb([
    '<i data-lucide="home" style="width:12px;height:12px"></i> Accueil',
    '<i data-lucide="user-check" style="width:12px;height:12px"></i> Enseignant'
  ]);
  showLoader();
  fetch('/api/enseignants')
    .then(r => r.json())
    .then(list => {
      hideLoader();
      addMessage('Choisissez un enseignant :');
      showOptions(list.map(e => ({
        label: e.prenom + ' ' + e.nom, icon: 'user',
        action: () => chargerPlanningEnseignant(e.id, e.prenom + ' ' + e.nom)
      })));
    });
}
 
// ─────────────────────────────────────────
// PLANNING ENSEIGNANT
// GET /api/edt/enseignant?enseignant_id=
// ─────────────────────────────────────────
function chargerPlanningEnseignant(id, nom) {
  selectedEnseignant = id;
  addMessage(nom, 'user');
  showLoader();
  fetch('/api/edt/enseignant?' + new URLSearchParams({ enseignant_id: id }))
    .then(r => r.json())
    .then(cours => {
      hideLoader();
      if (!cours.length) {
        addMessage(`Aucun cours cette semaine pour ${nom}.`);
      } else {
        addMessage(`Planning de <strong>${nom}</strong> :`);
        const body = document.getElementById('chat-body');
        const wrap = document.createElement('div');
        wrap.className = 'table-wrapper';
        let rows = '';
        cours.forEach(c => {
          rows += `<tr>
            <td><div class="td-heure">
              <i data-lucide="clock" style="width:11px;height:11px;color:#1B4FD8"></i>
              ${fmtHeure(c.heure_debut)}–${fmtHeure(c.heure_fin)}
            </div></td>
            <td style="font-weight:700;color:#1B4FD8">
              <div style="display:flex;align-items:center;gap:4px">
                <i data-lucide="sun" style="width:11px;height:11px"></i>${c.jour}
              </div>
            </td>
            <td class="td-matiere">${c.matiere?.nom || '—'}</td>
            <td>${c.filiere?.code || '—'} ${c.niveau?.libelle || ''}</td>
            <td><div class="td-salle">
              <i data-lucide="door-open" style="width:11px;height:11px"></i>${c.salle?.nom || '—'}
            </div></td>
          </tr>`;
        });
        wrap.innerHTML = `
          <table class="edt-table">
            <thead><tr>
              <th><div class="th-inner"><i data-lucide="clock" style="width:12px;height:12px"></i>Horaire</div></th>
              <th><div class="th-inner"><i data-lucide="sun" style="width:12px;height:12px"></i>Jour</div></th>
              <th><div class="th-inner"><i data-lucide="book-open" style="width:12px;height:12px"></i>Matière</div></th>
              <th><div class="th-inner"><i data-lucide="users" style="width:12px;height:12px"></i>Groupe</div></th>
              <th><div class="th-inner"><i data-lucide="map-pin" style="width:12px;height:12px"></i>Salle</div></th>
            </tr></thead>
            <tbody>${rows}</tbody>
          </table>`;
        body.appendChild(wrap);
        body.scrollTop = body.scrollHeight;
        lucide.createIcons();
      }
      showOptions([
        { label: '⬇️ Télécharger', icon: 'download', action: () => telechargerPDFProf(id, nom), style: 'primary' },
        { label: 'Retour accueil', icon: 'home', action: accueil, style: 'danger' },
      ]);
    });
}
 

 
// ─────────────────────────────────────────
// SUGGESTIONS RAPIDES — affichées au-dessus
// du champ de saisie selon le contexte
// ─────────────────────────────────────────
function showQuickSuggestions(tags) {
  const zone = document.getElementById('quick-suggestions');
  zone.innerHTML = '';
  tags.forEach(t => {
    const el = document.createElement('div');
    el.className = 'quick-tag';
    el.innerHTML = `<i data-lucide="${t.icon}" style="width:11px;height:11px"></i>${t.label}`;
    el.onclick = () => {
      document.getElementById('chat-input').value = t.label;
      envoyerMessage();
    };
    zone.appendChild(el);
  });
  lucide.createIcons();
}
 
// ─────────────────────────────────────────
// SAISIE LIBRE — traitée par l'IA Gemini
// ─────────────────────────────────────────
function envoyerMessage() {
  const input = document.getElementById('chat-input');
  const texte = input.value.trim();
  if (!texte) return;

  addMessage(texte, 'user');
  input.value = '';

  conversationHistory.push({ role: 'user', content: texte });

  askAI(texte);
}

async function askAI(texte) {
  showLoader();
  try {
    const res = await fetch('/api/chatbot/ai', {
      method: 'POST',
      headers: {
        'Content-Type':  'application/json',
        'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content,
      },
      body: JSON.stringify({
        message:       texte,
        history:       conversationHistory.slice(-10),
        filiere_id:    selectedFiliereId,
        niveau_id:     selectedNiveauId,
        enseignant_id: profEnseignantId,
      }),
    });

    const data = await res.json();
    hideLoader();

    if (data.message) {
      addMessage(data.message);
      conversationHistory.push({ role: 'assistant', content: data.message });
      // Garde l'historique à 20 éléments max (10 échanges)
      if (conversationHistory.length > 20) conversationHistory = conversationHistory.slice(-20);
    }

    if (data.action && data.action !== 'none') {
      setTimeout(() => executeAction(data.action, data.params || {}), 250);
    }

  } catch (err) {
    hideLoader();
    addMessage('❌ Impossible de contacter l\'assistant. Vérifiez votre connexion et réessayez.');
  }
}

// ─────────────────────────────────────────
// EXÉCUTION DES ACTIONS IA
// Déclenché par la réponse de Gemini.
// N'ajoute pas de bulle "user" (déjà affichée).
// ─────────────────────────────────────────
function executeAction(action, params) {
  const body = document.getElementById('chat-body');

  // L'IA peut envoyer filiere_id/niveau_id dans les params — on les utilise directement
  if (params?.filiere_id)  selectedFiliereId  = params.filiere_id;
  if (params?.filiere_nom) selectedFiliereNom = params.filiere_nom;
  if (params?.niveau_id)   selectedNiveauId   = params.niveau_id;
  if (params?.niveau_nom)  selectedNiveauNom  = params.niveau_nom;

  if ((action === 'show_edt_jour' || action === 'show_edt_semaine' || action === 'show_edt_semestriel' || action === 'download_pdf') &&
      (!selectedFiliereId || !selectedNiveauId)) {
    addMessage('Veuillez d\'abord sélectionner votre filière et votre niveau via les boutons ci-dessous.');
    chargerFilieres();
    return;
  }

  switch (action) {

    case 'show_edt_jour': {
      const jour = params.jour || 'Lundi';
      selectedJour = jour;
      showLoader();
      fetch('/api/edt/jour?' + new URLSearchParams({ filiere_id: selectedFiliereId, niveau_id: selectedNiveauId, jour }))
        .then(r => r.json())
        .then(cours => {
          hideLoader();
          if (!cours.length) {
            const div = document.createElement('div');
            div.className = 'empty-msg';
            div.innerHTML = `<i data-lucide="moon" style="width:28px;height:28px"></i><span>Aucun cours prévu ce <strong>${jour}</strong>.</span>`;
            body.appendChild(div); lucide.createIcons();
          } else {
            afficherTableau(cours);
          }
          showOptions([
            { label: '⬇️ PDF', icon: 'download', action: () => telechargerPDF(jour), style: 'success' },
            { label: '🏠 Accueil', icon: 'home', action: accueil, style: 'danger' },
          ]);
        })
        .catch(() => { hideLoader(); addMessage('❌ Erreur lors du chargement.'); });
      break;
    }

    case 'show_edt_semaine': {
      showLoader();
      fetch('/api/edt/semaine?' + new URLSearchParams({ filiere_id: selectedFiliereId, niveau_id: selectedNiveauId }))
        .then(r => r.json())
        .then(data => {
          hideLoader();
          const jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
          let total = 0;
          jours.forEach(j => {
            if (data[j]?.length) {
              total++;
              const lbl = document.createElement('div');
              lbl.className = 'day-label';
              lbl.textContent = j;
              body.appendChild(lbl);
              afficherTableau(data[j]);
            }
          });
          if (!total) {
            const div = document.createElement('div');
            div.className = 'empty-msg';
            div.innerHTML = `<i data-lucide="moon" style="width:28px;height:28px"></i><span>Aucun cours cette semaine.</span>`;
            body.appendChild(div); lucide.createIcons();
          }
          body.scrollTop = body.scrollHeight;
          showOptions([
            { label: '⬇️ PDF', icon: 'download', action: () => telechargerPDF(), style: 'success' },
            { label: '🏠 Accueil', icon: 'home', action: accueil, style: 'danger' },
          ]);
        })
        .catch(() => { hideLoader(); addMessage('❌ Erreur lors du chargement.'); });
      break;
    }

    case 'show_edt_semestriel': {
      showLoader();
      fetch('/api/edt/semaine?' + new URLSearchParams({ filiere_id: selectedFiliereId, niveau_id: selectedNiveauId }))
        .then(r => r.json())
        .then(data => {
          hideLoader();
          const jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
          let totalCours = 0;
          jours.forEach(j => { if (data[j]) totalCours += data[j].length; });

          const header = document.createElement('div');
          header.style.cssText = 'background:linear-gradient(135deg,#1B4FD8,#7C3AED);border-radius:16px;padding:16px 18px;margin:8px 0;color:#fff;font-size:13px;';
          header.innerHTML = `
            <div style="font-size:15px;font-weight:800;margin-bottom:4px">📋 Emploi du temps semestriel</div>
            <div style="opacity:.8;font-size:12px">${selectedFiliereNom || ''} — ${selectedNiveauNom || ''}</div>
            <div style="margin-top:8px;display:flex;gap:12px;flex-wrap:wrap">
              <span style="background:rgba(255,255,255,.15);padding:3px 10px;border-radius:50px;font-size:11px">📚 ${totalCours} cours</span>
              <span style="background:rgba(255,255,255,.15);padding:3px 10px;border-radius:50px;font-size:11px">📅 ${jours.filter(j => data[j]?.length).length} jours actifs</span>
            </div>`;
          body.appendChild(header);

          let hasAny = false;
          jours.forEach(jour => {
            if (!data[jour]?.length) return;
            hasAny = true;
            const banner = document.createElement('div');
            banner.style.cssText = 'display:flex;align-items:center;gap:8px;background:#EFF6FF;border-left:4px solid #1B4FD8;border-radius:0 10px 10px 0;padding:8px 14px;margin:10px 0 6px;font-size:12px;font-weight:700;color:#1B4FD8;';
            banner.innerHTML = `<i data-lucide="sun" style="width:13px;height:13px"></i> ${jour.toUpperCase()}`;
            body.appendChild(banner);
            lucide.createIcons();
            afficherTableau(data[jour]);
          });

          if (!hasAny) {
            const div = document.createElement('div');
            div.className = 'empty-msg';
            div.innerHTML = `<i data-lucide="moon" style="width:28px;height:28px"></i><span>Aucun cours enregistré.</span>`;
            body.appendChild(div); lucide.createIcons();
          }
          body.scrollTop = body.scrollHeight;
          showOptions([
            { label: '⬇️ PDF', icon: 'download', action: () => telechargerPDF(), style: 'success' },
            { label: '🏠 Accueil', icon: 'home', action: accueil, style: 'danger' },
          ]);
        })
        .catch(() => { hideLoader(); addMessage('❌ Erreur lors du chargement.'); });
      break;
    }

    case 'show_alertes': {
      showLoader();
      fetch('/api/alertes')
        .then(r => r.json())
        .then(alertes => {
          hideLoader();
          if (!alertes.length) {
            addMessage('✅ Aucune modification dans les 48 dernières heures.');
          } else {
            alertes.forEach(a => {
              const card = document.createElement('div');
              card.className = 'alert-card';
              const mat = a.emploi_du_temps?.matiere?.nom || 'Cours';
              card.innerHTML = `
                <div class="ac-title">
                  <i data-lucide="alert-triangle" style="width:14px;height:14px;color:#F59E0B"></i>
                  ${mat}
                </div>
                <div class="ac-body">
                  <span class="ac-tag">${a.ancien_jour} ${fmtHeure(a.ancienne_heure)}</span>
                  <i data-lucide="arrow-right" class="ac-arrow" style="width:14px;height:14px"></i>
                  <span class="ac-tag">${a.nouveau_jour} ${fmtHeure(a.nouvelle_heure)}</span>
                </div>
                ${a.motif ? `<div class="ac-motif"><i data-lucide="message-circle" style="width:12px;height:12px"></i>${a.motif}</div>` : ''}`;
              body.appendChild(card);
              body.scrollTop = body.scrollHeight;
            });
            lucide.createIcons();
          }
          showOptions([{ label: '🏠 Accueil', icon: 'home', action: accueil, style: 'primary' }]);
        })
        .catch(() => { hideLoader(); addMessage('❌ Erreur lors du chargement.'); });
      break;
    }

    case 'show_planning_enseignant': {
      if (params?.enseignant_id) {
        // L'IA a fourni l'ID directement → on charge sans passer par le dropdown
        const nom = params.enseignant_nom || params.nom || 'Enseignant';
        chargerPlanningEnseignant(params.enseignant_id, nom);
      } else {
        // Sinon, afficher le sélecteur
        chargerEnseignants();
      }
      break;
    }

    case 'download_pdf': {
      if (userRole === 'prof' && profEnseignantId) {
        telechargerPDFProf(profEnseignantId, userName);
      } else {
        telechargerPDF(params?.jour || null);
      }
      break;
    }
  }
}
 
// ─────────────────────────────────────────
// TOAST — notification rapide non-intrusive
// ─────────────────────────────────────────
function showToast(msg, durationMs = 2800) {
  let t = document.getElementById('toast');
  if (!t) {
    t = document.createElement('div');
    t.id = 'toast';
    document.body.appendChild(t);
  }
  t.innerHTML = msg;
  t.classList.add('show');
  clearTimeout(t._timer);
  t._timer = setTimeout(() => t.classList.remove('show'), durationMs);
}

// Affiche les options principales sans réinitialiser le contexte
function showOptionsAccueilRapide() {
  showOptions([
    { label: '📋 Mon EDT semestriel',  icon: 'layout-list', action: chargerFilieres,   style: 'primary' },
    { label: '🔔 Alertes horaires',    icon: 'bell',        action: chargerAlertes                      },
    { label: '👨‍🏫 Planning enseignant', icon: 'user-check',  action: chargerEnseignants                  },
  ]);
}
 
// ─────────────────────────────────────────
// TÉLÉCHARGEMENT PDF
// GET /api/pdf?filiere_id=&niveau_id=[&jour=]
// ─────────────────────────────────────────
function telechargerPDF(jour = null) {
  if (!selectedFiliereId || !selectedNiveauId) {
    addMessage('❌ Veuillez d\'abord choisir une filière et un niveau.');
    return;
  }

  const params = new URLSearchParams({
    filiere_id: selectedFiliereId,
    niveau_id:  selectedNiveauId,
  });
  if (jour) params.append('jour', jour);

  addMessage('⏳ Génération du PDF en cours…');

  fetch('/api/pdf?' + params)
    .then(response => {
      if (!response.ok) throw new Error('Erreur serveur : ' + response.status);
      return response.blob();
    })
    .then(blob => {
      const url  = window.URL.createObjectURL(blob);
      const a    = document.createElement('a');
      a.href     = url;
      a.download = jour
        ? `EDT_${selectedFiliereNom}_${selectedNiveauNom}_${jour}.pdf`
        : `EDT_${selectedFiliereNom}_${selectedNiveauNom}_Semaine.pdf`;
      document.body.appendChild(a);
      a.click();
      a.remove();
      window.URL.revokeObjectURL(url);
      showToast('✅ PDF téléchargé avec succès !');
    })
    .catch(err => {
      addMessage('❌ Impossible de générer le PDF : ' + err.message);
    });
}

// ─────────────────────────────────────────
// TÉLÉCHARGEMENT PDF (PROFESSEUR)
// ─────────────────────────────────────────
function telechargerPDFProf(id, nom = 'enseignant') {
  addMessage('⏳ Génération de votre planning en cours…');
  fetch('/api/pdf?' + new URLSearchParams({ enseignant_id: id }))
    .then(response => {
      if (!response.ok) throw new Error('Erreur serveur : ' + response.status);
      return response.blob();
    })
    .then(blob => {
      const url  = window.URL.createObjectURL(blob);
      const a    = document.createElement('a');
      a.href     = url;
      const safeName = nom.replace(/[^a-zA-Z0-9]/g, '_');
      a.download = `Planning_${safeName}.pdf`;
      document.body.appendChild(a);
      a.click();
      a.remove();
      window.URL.revokeObjectURL(url);
      showToast('✅ Planning téléchargé avec succès !');
    })
    .catch(err => {
      addMessage('❌ Impossible de générer le PDF : ' + err.message);
    });
}

// ─────────────────────────────────────────
// ÉVÉNEMENTS — champ de saisie
// ─────────────────────────────────────────
document.getElementById('chat-send').addEventListener('click', envoyerMessage);
document.getElementById('chat-input').addEventListener('keypress', e => {
  if (e.key === 'Enter') envoyerMessage();
});

// ─────────────────────────────────────────
// NOTIFICATIONS — polling toutes les 30s
// ─────────────────────────────────────────
let cachedNotifs = [];

async function fetchNotifications() {
  if (!userRole || userRole === 'null') return;
  try {
    const res  = await fetch('/api/notifications');
    const data = await res.json();
    cachedNotifs = data.notifications || [];
    const count = data.unread_count || 0;
    const badge = document.getElementById('notif-badge');
    if (badge) {
      badge.textContent = count;
      badge.style.display = count > 0 ? 'flex' : 'none';
    }
  } catch(e) {}
}

function toggleNotifDropdown() {
  const dropdown = document.getElementById('notif-dropdown');
  if (!dropdown) return;
  const visible = dropdown.style.display === 'block';
  dropdown.style.display = visible ? 'none' : 'block';
  if (!visible) {
    renderNotifDropdown();
    // Marquer tout comme lu
    fetch('/api/notifications/read-all', {
      method: 'PUT',
      headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    }).then(() => {
      const badge = document.getElementById('notif-badge');
      if (badge) badge.style.display = 'none';
    });
  }
}

function renderNotifDropdown() {
  const dropdown = document.getElementById('notif-dropdown');
  if (!dropdown) return;

  const typeColor = { success:'#10B981', warning:'#F59E0B', info:'#3B82F6', danger:'#EF4444' };

  if (!cachedNotifs.length) {
    dropdown.innerHTML = `
      <div style="padding:20px;text-align:center;color:#94A3B8;font-size:13px;">
        <div style="font-size:24px;margin-bottom:6px">🔔</div>
        Aucune notification
      </div>`;
    return;
  }

  dropdown.innerHTML = `
    <div style="padding:10px 14px 8px;border-bottom:1px solid #F1F5F9;display:flex;justify-content:space-between;align-items:center;">
      <span style="font-size:12px;font-weight:700;color:#1E293B;">Notifications</span>
      <span style="font-size:11px;color:#94A3B8;">${cachedNotifs.length} récentes</span>
    </div>
    ${cachedNotifs.map(n => `
      <div style="padding:10px 14px;border-bottom:1px solid #F8FAFF;${n.unread ? 'background:#F0F6FF;' : ''}">
        <div style="display:flex;align-items:flex-start;gap:8px;">
          <span style="width:8px;height:8px;border-radius:50%;background:${typeColor[n.type] || '#94A3B8'};flex-shrink:0;margin-top:5px;"></span>
          <div style="flex:1;min-width:0;">
            <div style="font-size:12px;font-weight:600;color:#1E293B;margin-bottom:2px;">${n.titre}</div>
            <div style="font-size:11.5px;color:#475569;line-height:1.5;">${n.message}</div>
            <div style="font-size:10px;color:#94A3B8;margin-top:3px;">${n.date}</div>
          </div>
        </div>
      </div>
    `).join('')}`;
}

// Fermer le dropdown si on clique ailleurs
document.addEventListener('click', e => {
  const btn      = document.getElementById('notif-btn');
  const dropdown = document.getElementById('notif-dropdown');
  if (dropdown && btn && !btn.contains(e.target) && !dropdown.contains(e.target)) {
    dropdown.style.display = 'none';
  }
});

// Lancer le polling au démarrage
fetchNotifications();
setInterval(fetchNotifications, 30000);
 
// ─────────────────────────────────────────
// DÉMARRAGE
// ─────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  lucide.createIcons();
  
  if (!userRole || userRole === 'null') {
    // Non connecté ou pas de rôle
    showQuickSuggestions([
      { label: 'edt',     icon: 'calendar' },
      { label: 'alertes', icon: 'bell' },
      { label: 'aide',    icon: 'help-circle' },
    ]);
    addMessage('Bonjour 👋 Je suis votre assistant emploi du temps de l\'UGANC.');
    setTimeout(() => {
      addMessage('Sélectionnez votre <strong>département (filière)</strong> pour voir l\'emploi du temps semestriel, les alertes de modification ou le planning d\'un enseignant.');
      setTimeout(() => {
        showOptionsAccueilRapide();
      }, 400);
    }, 400);
    return;
  }

  // Rôle : Étudiant
  if (userRole === 'etudiant') {
    if (selectedFiliereId && selectedNiveauId) {
      showQuickSuggestions([
        { label: 'semestriel', icon: 'layout-list' },
        { label: 'semaine',    icon: 'calendar-range' },
        { label: 'alertes',    icon: 'bell' },
      ]);
      addMessage(`Bonjour <strong>${userName}</strong> 👋 Je suis votre assistant emploi du temps de l'UGANC.`);
      setTimeout(() => {
        addMessage(`Votre profil étudiant est configuré pour le département <strong>${selectedFiliereNom}</strong> (${selectedNiveauNom}). Que souhaitez-vous consulter ?`);
        setTimeout(() => {
          showOptions([
            { label: '📋 Mon EDT semestriel',  icon: 'layout-list', action: chargerSemesterComplet,   style: 'primary' },
            { label: '📅 Par jour',            icon: 'calendar-days', action: afficherChoixJour },
            { label: '📆 Semaine courante',    icon: 'calendar-range', action: chargerSemaine },
            { label: '🔔 Alertes horaires',    icon: 'bell',        action: chargerAlertes                      },
            { label: '👨‍🏫 Planning enseignant', icon: 'user-check',  action: chargerEnseignants                  },
          ]);
        }, 400);
      }, 400);
    } else {
      addMessage(`Bonjour <strong>${userName}</strong> 👋 Votre profil étudiant n'est pas encore complètement configuré.`);
      setTimeout(() => { showOptionsAccueilRapide(); }, 400);
    }
  } 
  // Rôle : Professeur
  else if (userRole === 'prof') {
    showQuickSuggestions([
      { label: 'mes cours', icon: 'book-open' },
      { label: 'alertes',   icon: 'bell' },
    ]);
    addMessage(`Bonjour Professeur <strong>${userName}</strong> 👋 Je suis votre assistant UGANC.`);
    setTimeout(() => {
      addMessage(`Que souhaitez-vous faire aujourd'hui ?`);
      setTimeout(() => {
        showOptions([
          { label: '📚 Mes cours assignés', icon: 'book-open', action: () => window.location.href='/tableau-de-bord', style: 'primary' },
          { label: '📆 Mon planning', icon: 'calendar-range', action: () => chargerPlanningEnseignant(profEnseignantId, userName) },
          { label: '🔔 Dernières alertes', icon: 'bell', action: chargerAlertes },
        ]);
      }, 400);
    }, 400);
  }
  // Rôle : Chef de programme
  else if (userRole === 'chef') {
    showQuickSuggestions([
      { label: 'département', icon: 'building' },
      { label: 'alertes',     icon: 'bell' },
    ]);
    addMessage(`Bonjour Chef de programme <strong>${userName}</strong> 👋`);
    setTimeout(() => {
      addMessage(`Votre espace administratif pour <strong>${selectedFiliereNom || 'votre département'}</strong> est prêt. Que souhaitez-vous consulter ?`);
      setTimeout(() => {
        showOptions([
          { label: '⚙️ Gérer le département', icon: 'settings', action: () => window.location.href='/tableau-de-bord', style: 'primary' },
          { label: '📋 Emplois du temps', icon: 'calendar-days', action: showOptionsAccueilRapide },
          { label: '🔔 Dernières alertes', icon: 'bell', action: chargerAlertes },
          { label: '👨‍🏫 Planning enseignant', icon: 'users', action: chargerEnseignants },
        ]);
      }, 400);
    }, 400);
  }
});
</script>
</body>
</html>