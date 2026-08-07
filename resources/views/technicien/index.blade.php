<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes missions</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #F5F5F3; color: #2B2B2B; }
        .header { background: linear-gradient(135deg, #1565C0, #1976D2); color: #fff; padding: 24px 20px; }
        .header-top { display: flex; justify-content: space-between; align-items: flex-start; }
        .header h1 { font-size: 19px; font-weight: 600; margin-bottom: 2px; }
        .header p { font-size: 13px; opacity: 0.85; }
        .btn-link { display: inline-flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.2); color: #fff; border: none; padding: 10px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; min-height: 38px; cursor: pointer; text-decoration: none; }
        .btn-link:hover { background: rgba(255,255,255,0.28); }
        .btn-logout { display: inline-flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.2); color: #fff; border: none; padding: 6px 12px; border-radius: 20px; font-size: 12px; cursor: pointer; line-height: 1; min-height: 36px; }
        .btn-logout:hover { background: rgba(255,255,255,0.3); }
        .stats { display: flex; gap: 10px; margin-top: 16px; }
        .stat { flex: 1; background: rgba(255,255,255,0.15); border-radius: 10px; padding: 12px 8px; text-align: center; color: #fff; }
        .stat-num { font-size: 22px; font-weight: 700; }
        .stat-label { font-size: 11px; opacity: 0.85; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px 16px 40px; }
        .alert-success { background: #E6F4EA; color: #1E7E34; border: 1px solid #B7E1C1; padding: 10px 14px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
        .empty { text-align: center; color: #999; padding: 40px 20px; font-size: 14px; }
        .carte { background: #fff; border-radius: 10px; padding: 16px; margin-bottom: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); border-left: 4px solid #CCC; }
        .carte.statut-affecte { border-left-color: #1976D2; }
        .carte.statut-en_cours { border-left-color: #E8A800; }
        .carte.statut-termine { border-left-color: #7B1FA2; }
        .carte.statut-cloture { border-left-color: #1E7E34; }
        .carte-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; }
        .carte-type { font-weight: 600; font-size: 15px; }
        .badge { font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 20px; white-space: nowrap; }
        .badge-affecte { background: #E3F2FD; color: #1976D2; }
        .badge-en_cours { background: #FFF9C4; color: #A98600; }
        .badge-termine { background: #F3E5F5; color: #7B1FA2; }
        .badge-cloture { background: #E6F4EA; color: #1E7E34; }
        .carte-desc { font-size: 13px; color: #666; margin-bottom: 6px; }
        .carte-meta { font-size: 12px; color: #999; margin-bottom: 12px; }
        .actions { display: flex; gap: 8px; }
        .btn { flex: 1; border: none; padding: 10px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; }
        .btn-maj { background: #FFF3E0; color: #E85D0C; }
        .btn-maj:hover { background: #FFE4C4; }
        .btn-refuser { background: #FDE8E8; color: #C0392B; }
        .btn-refuser:hover { background: #FACACA; }
        .btn-cloturer { background: #E3F2FD; color: #1976D2; }
        .btn-cloturer:hover { background: #C8E4FB; }
        .notif-bar { background: #fff; border-bottom: 1px solid #EEE; padding: 0 16px; }
        .notif-toggle { display: flex; align-items: center; gap: 8px; padding: 12px 0; cursor: pointer; font-size: 14px; font-weight: 600; color: #333; border: none; background: none; width: 100%; }
        .notif-badge { background: #E85D0C; color: #fff; font-size: 11px; font-weight: 700; padding: 2px 7px; border-radius: 20px; }
        .notif-list { display: none; padding-bottom: 10px; }
        .notif-list.open { display: block; }
        .notif-item { padding: 10px 0; border-top: 1px solid #F5F5F3; font-size: 13px; }
        .notif-item.non-lue { color: #1976D2; font-weight: 500; }
        .notif-dot { display: inline-block; width: 7px; height: 7px; background: #E85D0C; border-radius: 50%; margin-right: 6px; }
        .btn-mark-read { font-size: 12px; color: #1976D2; background: none; border: none; cursor: pointer; margin-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-top">
            <div>
                <h1>Bonjour, {{ auth()->user()->name }}</h1>
                <p>Technicien SENELEC</p>
            </div>
            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                <a href="{{ route('profile.edit') }}" class="btn-link">Profil</a>
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn-logout">Deconnexion</button>
                </form>
            </div>
        </div>
        <div class="stats">
            <div class="stat">
                <div class="stat-num">{{ $enAttente }}</div>
                <div class="stat-label">En attente</div>
            </div>
            <div class="stat">
                <div class="stat-num">{{ $enCours }}</div>
                <div class="stat-label">En cours</div>
            </div>
            <div class="stat">
                <div class="stat-num">{{ $terminees }}</div>
                <div class="stat-label">Terminees</div>
            </div>
            <div class="stat">
                <div class="stat-num">{{ $clotures }}</div>
                <div class="stat-label">Clotures</div>
            </div>
        </div>
    </div>

    <div class="notif-bar">
        <button class="notif-toggle" onclick="toggleNotifs()">
            Notifications
            @if ($nonLues > 0)
                <span class="notif-badge">{{ $nonLues }}</span>
            @endif
            <span style="margin-left:auto; font-size:12px; color:#999;">v</span>
        </button>
        <div class="notif-list" id="notifList">
            @forelse ($notifications as $notif)
                <div class="notif-item {{ $notif->lu ? '' : 'non-lue' }}">
                    @if (!$notif->lu) <span class="notif-dot"></span> @endif
                    {{ $notif->message }}
                    <span style="color:#BBB; font-size:11px; margin-left:6px;">{{ $notif->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <p style="color:#999; font-size:13px; padding: 8px 0;">Aucune notification.</p>
            @endforelse
            @if ($nonLues > 0)
                <form action="{{ route('technicien.notifs.lues') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-mark-read">Tout marquer comme lu</button>
                </form>
            @endif
        </div>
    </div>

    <script>
    function toggleNotifs() {
        document.getElementById('notifList').classList.toggle('open');
    }
    </script>

    <div class="container">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @forelse ($interventions as $intervention)
            @php $signalement = $intervention->signalement; @endphp
            <div class="carte statut-{{ $signalement->statut }}">
                <div class="carte-top">
                    <span class="carte-type">{{ $signalement->typePanne->libelle }}</span>
                    <span class="badge badge-{{ $signalement->statut }}">
                        {{ str_replace('_', ' ', $signalement->statut) }}
                    </span>
                </div>
                <p class="carte-desc">{{ $signalement->description }}</p>
                <p class="carte-meta">
                    {{ $signalement->user->name }}
                    @if ($signalement->adresse) — {{ $signalement->adresse }} @endif
                    · {{ $intervention->date_affectation ? \Carbon\Carbon::parse($intervention->date_affectation)->diffForHumans() : '—' }}
                </p>

                <div class="actions">
                    @if ($signalement->statut === 'affecte')
                        <form action="{{ route('technicien.mettreAJour', $intervention->id) }}" method="POST" style="flex:1;">
                            @csrf
                            <button type="submit" class="btn btn-maj" style="width:100%;">Demarrer</button>
                        </form>
                        <form action="{{ route('technicien.refuser', $intervention->id) }}" method="POST" style="flex:1;" onsubmit="return confirm('Refuser cette mission ?');">
                            @csrf
                            <button type="submit" class="btn btn-refuser" style="width:100%;">Refuser</button>
                        </form>
                    @endif

                    @if ($signalement->statut === 'en_cours')
                        <form action="{{ route('technicien.terminer', $intervention->id) }}" method="POST" style="flex:1;">
                            @csrf
                            <button type="submit" class="btn btn-cloturer" style="width:100%;">Marquer terminee</button>
                        </form>
                    @endif

                    @if ($signalement->statut === 'termine')
                        <span style="font-size:12px; color:#7B1FA2; font-style:italic;">En attente de cloture par le superviseur</span>
                    @endif

                    @if ($signalement->statut === 'cloture')
                        <span style="font-size:12px; color:#1E7E34; font-style:italic;">Clôturé</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty">Aucune mission assignee pour le moment.</div>
        @endforelse
    </div>
</body>
</html>
