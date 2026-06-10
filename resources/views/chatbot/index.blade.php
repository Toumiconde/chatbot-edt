<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot EDT — UGANC</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-lg overflow-hidden">
        {{-- Header --}}
        <div class="bg-green-700 text-white px-6 py-4">
            <h1 class="text-xl font-bold">Chatbot EDT — Centre Informatique UGANC</h1>
            <p class="text-sm opacity-80">Groupe 6 — 2024/2025</p>
        </div>

        {{-- Messages --}}
        <div id="messages" class="flex flex-col gap-3 p-4 h-96 overflow-y-auto bg-gray-50">
            <div class="self-start bg-green-100 text-green-900 rounded-xl px-4 py-2 text-sm max-w-xs">
                Bonjour ! Je suis votre assistant EDT. Choisissez une option ci-dessous.
            </div>
        </div>

        {{-- Boutons d'actions rapides --}}
        <div id="actions" class="flex flex-wrap gap-2 px-4 py-3 border-t border-gray-200 bg-white">
            <button onclick="chargerFilieres()" class="bg-green-600 hover:bg-green-700 text-white text-sm px-3 py-1.5 rounded-lg">
                Voir l'EDT
            </button>
            <button onclick="voirEnseignants()" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1.5 rounded-lg">
                EDT Enseignant
            </button>
            <button onclick="voirAlertes()" class="bg-orange-500 hover:bg-orange-600 text-white text-sm px-3 py-1.5 rounded-lg">
                Alertes (48h)
            </button>
        </div>

        {{-- Sélecteurs dynamiques --}}
        <div id="selectors" class="px-4 pb-4 space-y-2 bg-white"></div>
    </div>

<script>
const BASE = '/api';

function ajouterMessage(texte, type = 'bot') {
    const div = document.createElement('div');
    div.className = type === 'bot'
        ? 'self-start bg-green-100 text-green-900 rounded-xl px-4 py-2 text-sm max-w-xs'
        : 'self-end bg-green-600 text-white rounded-xl px-4 py-2 text-sm max-w-xs';
    div.innerHTML = texte;
    document.getElementById('messages').appendChild(div);
    div.scrollIntoView({ behavior: 'smooth' });
}

function viderSelectors() {
    document.getElementById('selectors').innerHTML = '';
}

async function chargerFilieres() {
    viderSelectors();
    ajouterMessage('Chargement des filières...', 'user');
    const res = await fetch(`${BASE}/filieres`);
    const filieres = await res.json();

    let html = '<label class="text-sm font-semibold text-gray-700">Choisissez une filière :</label>';
    html += '<select id="sel-filiere" class="w-full border rounded-lg px-3 py-2 text-sm" onchange="chargerNiveaux(this.value)">';
    html += '<option value="">-- Filière --</option>';
    filieres.forEach(f => { html += `<option value="${f.id}">${f.nom}</option>`; });
    html += '</select>';
    document.getElementById('selectors').innerHTML = html;
}

async function chargerNiveaux(filiereId) {
    if (!filiereId) return;
    const sel = document.getElementById('selectors');
    const existing = document.getElementById('sel-niveau-wrap');
    if (existing) existing.remove();

    const res = await fetch(`${BASE}/niveaux/${filiereId}`);
    const niveaux = await res.json();

    const wrap = document.createElement('div');
    wrap.id = 'sel-niveau-wrap';
    wrap.innerHTML = `
        <label class="text-sm font-semibold text-gray-700">Niveau :</label>
        <select id="sel-niveau" class="w-full border rounded-lg px-3 py-2 text-sm">
            <option value="">-- Niveau --</option>
            ${niveaux.map(n => `<option value="${n.id}">${n.libelle}</option>`).join('')}
        </select>
        <label class="text-sm font-semibold text-gray-700 mt-2 block">Affichage :</label>
        <div class="flex gap-2 mt-1">
            <button onclick="afficherJour()" class="flex-1 bg-green-600 text-white text-sm py-1.5 rounded-lg hover:bg-green-700">Par jour</button>
            <button onclick="afficherSemaine()" class="flex-1 bg-blue-600 text-white text-sm py-1.5 rounded-lg hover:bg-blue-700">Semaine complète</button>
            <button onclick="telechargerPDF()" class="flex-1 bg-red-600 text-white text-sm py-1.5 rounded-lg hover:bg-red-700">PDF</button>
        </div>`;
    sel.appendChild(wrap);
}

function afficherJour() {
    const filiereId = document.getElementById('sel-filiere').value;
    const niveauId  = document.getElementById('sel-niveau').value;
    if (!filiereId || !niveauId) { ajouterMessage('Veuillez choisir une filière et un niveau.'); return; }

    const jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
    const wrap = document.getElementById('sel-niveau-wrap');
    const existing = document.getElementById('sel-jour-wrap');
    if (existing) existing.remove();

    const div = document.createElement('div');
    div.id = 'sel-jour-wrap';
    div.innerHTML = `
        <label class="text-sm font-semibold text-gray-700">Jour :</label>
        <select id="sel-jour" class="w-full border rounded-lg px-3 py-2 text-sm">
            <option value="">-- Jour --</option>
            ${jours.map(j => `<option value="${j}">${j}</option>`).join('')}
        </select>
        <button onclick="fetchJour(${filiereId}, ${niveauId})" class="mt-2 w-full bg-green-600 text-white text-sm py-2 rounded-lg hover:bg-green-700">Afficher</button>`;
    document.getElementById('selectors').appendChild(div);
}

async function fetchJour(filiereId, niveauId) {
    const jour = document.getElementById('sel-jour').value;
    if (!jour) { ajouterMessage('Veuillez choisir un jour.'); return; }

    const res = await fetch(`${BASE}/edt/jour?filiere_id=${filiereId}&niveau_id=${niveauId}&jour=${jour}`);
    const cours = await res.json();

    if (!cours.length) { ajouterMessage(`Aucun cours le <strong>${jour}</strong>.`); return; }

    let msg = `<strong>Cours du ${jour} :</strong><br><ul class="list-disc ml-4 mt-1">`;
    cours.forEach(c => {
        msg += `<li>${c.heure_debut.slice(0,5)}-${c.heure_fin.slice(0,5)} : ${c.matiere.nom} (${c.enseignant.prenom} ${c.enseignant.nom}) — ${c.salle.nom}</li>`;
    });
    msg += '</ul>';
    ajouterMessage(msg);
}

async function afficherSemaine() {
    const filiereId = document.getElementById('sel-filiere').value;
    const niveauId  = document.getElementById('sel-niveau').value;
    if (!filiereId || !niveauId) { ajouterMessage('Veuillez choisir une filière et un niveau.'); return; }

    const res = await fetch(`${BASE}/edt/semaine?filiere_id=${filiereId}&niveau_id=${niveauId}`);
    const planning = await res.json();

    let msg = '<strong>Planning de la semaine :</strong>';
    for (const [jour, cours] of Object.entries(planning)) {
        if (!cours.length) continue;
        msg += `<br><strong>${jour} :</strong><ul class="list-disc ml-4">`;
        cours.forEach(c => {
            msg += `<li>${c.heure_debut.slice(0,5)}-${c.heure_fin.slice(0,5)} : ${c.matiere.nom} — ${c.salle.nom}</li>`;
        });
        msg += '</ul>';
    }
    ajouterMessage(msg);
}

function telechargerPDF() {
    const filiereId = document.getElementById('sel-filiere').value;
    const niveauId  = document.getElementById('sel-niveau').value;
    if (!filiereId || !niveauId) { ajouterMessage('Veuillez choisir une filière et un niveau.'); return; }
    window.location.href = `${BASE}/pdf?filiere_id=${filiereId}&niveau_id=${niveauId}`;
    ajouterMessage('Téléchargement du PDF en cours...');
}

async function voirEnseignants() {
    viderSelectors();
    const res = await fetch(`${BASE}/enseignants`);
    const enseignants = await res.json();

    let html = '<label class="text-sm font-semibold text-gray-700">Choisissez un enseignant :</label>';
    html += '<select id="sel-ens" class="w-full border rounded-lg px-3 py-2 text-sm">';
    html += '<option value="">-- Enseignant --</option>';
    enseignants.forEach(e => { html += `<option value="${e.id}">${e.prenom} ${e.nom}</option>`; });
    html += '</select>';
    html += `<button onclick="fetchEnseignant()" class="mt-2 w-full bg-blue-600 text-white text-sm py-2 rounded-lg hover:bg-blue-700">Voir planning</button>`;
    document.getElementById('selectors').innerHTML = html;
}

async function fetchEnseignant() {
    const eid = document.getElementById('sel-ens').value;
    if (!eid) { ajouterMessage('Veuillez choisir un enseignant.'); return; }

    const res = await fetch(`${BASE}/edt/enseignant?enseignant_id=${eid}`);
    const planning = await res.json();

    let msg = '<strong>Planning de l\'enseignant :</strong>';
    let vide = true;
    for (const [jour, cours] of Object.entries(planning)) {
        if (!cours.length) continue;
        vide = false;
        msg += `<br><strong>${jour} :</strong><ul class="list-disc ml-4">`;
        cours.forEach(c => {
            msg += `<li>${c.heure_debut.slice(0,5)}-${c.heure_fin.slice(0,5)} : ${c.matiere.nom} (${c.filiere.code} ${c.niveau.libelle}) — ${c.salle.nom}</li>`;
        });
        msg += '</ul>';
    }
    if (vide) msg = 'Aucun cours trouvé pour cet enseignant.';
    ajouterMessage(msg);
}

async function voirAlertes() {
    viderSelectors();
    const res = await fetch(`${BASE}/alertes`);
    const alertes = await res.json();

    if (!alertes.length) {
        ajouterMessage('Aucune modification dans les dernières 48h.');
        return;
    }

    let msg = '<strong>Modifications récentes (48h) :</strong><ul class="list-disc ml-4 mt-1">';
    alertes.forEach(a => {
        const matiere = a.emploi?.matiere?.nom ?? 'Cours';
        msg += `<li><strong>${matiere}</strong> : ${a.ancien_jour} ${a.ancienne_heure?.slice(0,5) ?? ''} → ${a.nouveau_jour} ${a.nouvelle_heure?.slice(0,5) ?? ''}</li>`;
        if (a.motif) msg += `<li class="ml-4 text-gray-500 list-none">Motif : ${a.motif}</li>`;
    });
    msg += '</ul>';
    ajouterMessage(msg);
}
</script>
</body>
</html>
