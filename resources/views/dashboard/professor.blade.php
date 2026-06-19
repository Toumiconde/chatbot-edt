<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Espace Enseignant — UGANC</title>
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
    .prof-header p  { color: rgba(255,255,255,.5); font-size: 11.5px; }
    .nav-link {
      display: flex; align-items: center; gap: 6px;
      padding: 8px 16px; border-radius: 50px;
      font-size: 12.5px; font-weight: 500; color: rgba(255,255,255,.8);
      text-decoration: none; border: 1px solid rgba(255,255,255,.15);
      transition: all .2s;
    }
    .nav-link:hover { background: rgba(255,255,255,.1); color: #fff; }

    .main-container {
      max-width: 900px; margin: 40px auto;
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

    .course-item {
      background: #162032;
      border: 1px solid rgba(255,255,255,.06);
      border-radius: 14px; padding: 20px;
      display: flex; align-items: center; justify-content: space-between;
      margin-bottom: 16px; transition: border-color .2s, transform .2s;
      border-left: 4px solid #3B82F6;
    }
    .course-item:hover {
      border-color: rgba(59,130,246,.4);
      transform: translateY(-2px);
    }

    .course-details { flex: 1; }
    .course-title { font-size: 16px; font-weight: 700; color: #F1F5F9; margin-bottom: 6px; }
    .course-meta { display: flex; gap: 16px; color: #94A3B8; font-size: 13px; }
    .course-meta span { display: flex; align-items: center; gap: 6px; }

    .edit-btn {
      display: inline-flex; align-items: center; gap: 8px;
      background: linear-gradient(135deg, #3B82F6, #1D4ED8);
      color: #fff; border: none; border-radius: 10px;
      padding: 10px 18px; font-size: 13.5px; font-weight: 600;
      text-decoration: none; cursor: pointer;
      transition: opacity .2s, transform .2s;
    }
    .edit-btn:hover { opacity: .9; transform: translateY(-1px); }

    .status-alert {
      background: rgba(16,185,129,.12);
      border: 1px solid rgba(16,185,129,.25);
      border-radius: 12px; padding: 12px 16px;
      color: #6EE7B7; font-size: 13.5px;
      margin-bottom: 24px;
      display: flex; align-items: center; gap: 8px;
    }

    .empty {
      text-align: center; padding: 40px;
      color: #64748B; font-size: 14px;
    }
  </style>
</head>
<body>

<header class="prof-header">
  <div>
    <h1>👨‍🏫 Espace Enseignant</h1>
    <p>Connecté en tant que : <strong>{{ auth()->user()->name }}</strong></p>
  </div>
  <div style="display:flex;gap:12px;align-items:center">
    <!-- Cloche de notification interactive -->
    <div style="position:relative;">
      <button id="bell-btn" style="background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12); border-radius:10px; cursor:pointer; color:#fff; display:flex; align-items:center; justify-content:center; position:relative; width:38px; height:38px; transition: background .2s;">
        <i data-lucide="bell" style="width:18px;height:18px;"></i>
        @if(isset($unreadCount) && $unreadCount > 0)
          <span style="position:absolute; top:3px; right:3px; width:8px; height:8px; background:#EF4444; border-radius:50%; border:1.5px solid #060D1A;"></span>
        @endif
      </button>
      <!-- Dropdown -->
      <div id="bell-dropdown" style="display:none; position:absolute; right:0; top:46px; width:340px; background:#0F1A2A; border:1px solid rgba(255,255,255,.1); border-radius:16px; box-shadow:0 15px 35px rgba(0,0,0,.6); padding:16px; z-index:200; backdrop-filter: blur(10px);">
        <div style="font-weight:700; font-size:14px; margin-bottom:12px; display:flex; justify-content:space-between; align-items:center; border-bottom: 1px solid rgba(255,255,255,.08); padding-bottom:8px;">
          <span style="color:#fff">Historique & Notifications</span>
          @if(isset($unreadCount) && $unreadCount > 0)
            <span style="font-size:11px; background:#10B981; color:#fff; padding:2px 8px; border-radius:20px; font-weight:600;">{{ $unreadCount }} nouvelles</span>
          @endif
        </div>
        <div style="max-height:280px; overflow-y:auto; padding-right:4px;">
          @forelse($notifications as $notif)
            <div style="border-bottom:1px solid rgba(255,255,255,.05); padding:10px 0; font-size:12.5px; line-height:1.4;">
              <div style="font-weight:700; color:#10B981; margin-bottom:4px; display:flex; align-items:center; gap:6px;">
                <i data-lucide="check-circle" style="width:12px;height:12px;"></i>
                {{ $notif->titre }}
              </div>
              <div style="color:#94A3B8;">{{ $notif->message }}</div>
              <div style="font-size:10px; color:#64748B; margin-top:6px; text-align:right;">{{ $notif->created_at->diffForHumans() }}</div>
            </div>
          @empty
            <div style="color:#64748B; font-size:13px; text-align:center; padding:24px 0;">
              <i data-lucide="check" style="width:24px;height:24px;margin-bottom:8px;color:#3B82F6;display:block;margin:0 auto 8px;"></i>
              Aucune nouvelle notification
            </div>
          @endforelse
        </div>
      </div>
    </div>

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
      <button type="submit" class="nav-link" style="background:transparent;cursor:pointer;color:#FCA5A5;border:1px solid rgba(252,165,165,.25)">
        <i data-lucide="log-out" style="width:14px;height:14px"></i> Déconnexion
      </button>
    </form>
  </div>
</header>

<div class="main-container">
  <div class="card">
    <div class="title">
      <i data-lucide="book-open" style="width:22px;height:22px;color:#3B82F6"></i>
      Mes Cours Assignés
    </div>

    @if (session('status'))
      <div class="status-alert">
        <i data-lucide="check-circle" style="width:16px;height:16px;color:#10B981"></i>
        {{ session('status') }}
      </div>
    @endif

    @if (isset($unreadCount) && $unreadCount > 0 && !empty($notifications))
      <div style="margin-bottom: 24px;">
        @foreach($notifications->take($unreadCount) as $notif)
          <div style="background: rgba(16,185,129,.12); border: 1px solid rgba(16,185,129,.3); border-left: 4px solid #10B981; border-radius: 14px; padding: 16px 20px; margin-bottom: 12px; display:flex; gap:14px; align-items:flex-start; animation: cardIn .35s ease;">
            <div style="width:36px;height:36px;border-radius:10px;background:rgba(16,185,129,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <i data-lucide="bell" style="width:18px;height:18px;color:#10B981"></i>
            </div>
            <div style="flex:1">
              <div style="font-size:14px;font-weight:700;color:#6EE7B7;margin-bottom:4px">{{ $notif->titre }}</div>
              <div style="font-size:13px;color:#A7F3D0;line-height:1.5">{{ $notif->message }}</div>
              <div style="font-size:11px;color:#6EE7B7;margin-top:6px;opacity:.7">{{ $notif->created_at->diffForHumans() }}</div>
            </div>
          </div>
        @endforeach
      </div>
    @endif

    <div class="courses-list">
      @forelse($emplois as $emp)
        <div class="course-item">
          <div class="course-details">
            <div class="course-title">
              📚 {{ $emp->matiere->nom ?? 'Matière inconnue' }} 
              <span style="font-size:12px; font-weight:500; opacity:0.8; margin-left:8px;">
                ({{ $emp->niveau->libelle ?? 'Niveau inconnu' }})
              </span>
            </div>
            <div class="course-meta">
              <span>
                <i data-lucide="calendar" style="width:14px;height:14px"></i>
                {{ $emp->jour }}
              </span>
              <span>
                <i data-lucide="clock" style="width:14px;height:14px"></i>
                {{ substr($emp->heure_debut, 0, 5) }} - {{ substr($emp->heure_fin, 0, 5) }}
              </span>
              <span>
                <i data-lucide="map-pin" style="width:14px;height:14px"></i>
                {{ $emp->salle->nom ?? 'Non définie' }}
              </span>
              @if($emp->semestre)
                <span>
                  <i data-lucide="layers" style="width:14px;height:14px"></i>
                  {{ $emp->semestre }}
                </span>
              @endif
            </div>
          </div>
          <!-- Bouton modifier retiré pour des raisons de sécurité -->
        </div>
      @empty
        <div class="empty">
          <i data-lucide="info" style="width:32px;height:32px;margin-bottom:12px;color:#4B5563"></i>
          <p>Aucun cours ne vous a été assigné pour le moment.</p>
        </div>
      @endforelse
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();

    const bellBtn = document.getElementById('bell-btn');
    const bellDropdown = document.getElementById('bell-dropdown');

    if (bellBtn && bellDropdown) {
      bellBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        bellDropdown.style.display = bellDropdown.style.display === 'block' ? 'none' : 'block';
      });

      document.addEventListener('click', () => {
        bellDropdown.style.display = 'none';
      });

      bellDropdown.addEventListener('click', (e) => {
        e.stopPropagation();
      });
    }
  });
</script>
</body>
</html>
