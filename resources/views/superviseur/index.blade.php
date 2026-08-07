<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signalements à affecter</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #F5F5F3; color: #2B2B2B; }
        .header { background: linear-gradient(135deg, #1565C0, #1976D2); color: #fff; padding: 24px 20px; }
        .header h1 { font-size: 19px; font-weight: 600; margin-bottom: 2px; }
        .header p { font-size: 13px; opacity: 0.85; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px 16px 40px; }
        .alert-success { background: #E6F4EA; color: #1E7E34; border: 1px solid #B7E1C1; padding: 10px 14px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
        .empty { text-align: center; color: #999; padding: 40px 20px; font-size: 14px; }
        .section-title { font-size: 15px; margin: 24px 0 12px; color: #555; font-weight: 600; }
        .carte { background: #fff; border-radius: 10px; padding: 16px; margin-bottom: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); border-left: 4px solid #E85D0C; }
        .carte-top { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .carte-type { font-weight: 600; font-size: 15px; }
        .badge { font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 20px; background: #FFF3E0; color: #E85D0C; }
        .carte-desc { font-size: 13px; color: #666; margin-bottom: 8px; }
        .carte-meta { font-size: 12px; color: #999; margin-bottom: 12px; }
        select { width: 100%; padding: 10px; border: 1px solid #DDD; border-radius: 8px; margin-bottom: 10px; font-size: 13px; }
        .btn-affecter { width: 100%; background: #1976D2; color: #fff; border: none; padding: 10px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }
        .btn-affecter:hover { background: #1565C0; }
        .btn-link { display: inline-flex; align-items: center; justify-content: center; min-height: 38px; background: rgba(255,255,255,0.2); color: #fff; border: none; padding: 10px 16px; border-radius: 20px; font-size: 13px; text-decoration: none; cursor: pointer; }
        .btn-link:hover { background: rgba(255,255,255,0.28); }
        .btn-logout { display: inline-flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.2); color: #fff; border: none; padding: 6px 12px; border-radius: 20px; font-size: 12px; line-height: 1; cursor: pointer; min-height: 36px; }
        .btn-logout:hover { background: rgba(255,255,255,0.3); }
        .header-actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
        .notif-bar { background: #fff; border-radius: 10px; padding: 14px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); margin-bottom: 16px; }
        .notif-toggle { width: 100%; display: flex; justify-content: space-between; align-items: center; border: none; background: transparent; font-size: 14px; font-weight: 600; color: #2B2B2B; cursor: pointer; padding: 0; }
        .notif-badge { background: #E85D0C; color: #fff; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 20px; }
        .notif-list { display: none; margin-top: 14px; }
        .notif-list.open { display: block; }
        .notif-item { padding: 10px 0; border-top: 1px solid #F4F4F4; font-size: 13px; color: #444; }
        .notif-item.non-lue { color: #E85D0C; font-weight: 700; }
        .notif-dot { display: inline-block; width: 8px; height: 8px; background: #E85D0C; border-radius: 50%; margin-right: 8px; }
        .btn-mark-read { margin-top: 12px; border: none; background: none; color: #E85D0C; font-size: 12px; font-weight: 700; cursor: pointer; padding: 0; }
    </style>
</head>
<body>
    <div class="header">
         <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px; flex-wrap:wrap;">
             <div>
                 <h1>Signalements a affecter</h1>
                 <p>Superviseur SENELEC</p>
             </div>
             <div class="header-actions">
                 <a href="{{ route('superviseur.index') }}" class="btn-link">Signalements</a>
                 <a href="{{ route('superviseur.statistiques') }}" class="btn-link">Statistiques</a>
                 <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                     @csrf
                     <button type="submit" class="btn-logout">Deconnexion</button>
                 </form>
                 <a href="{{ route('profile.edit') }}" class="btn-link">Profil</a>
             </div>
         </div>
    </div>

    <div class="container">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="notif-bar">
            <button class="notif-toggle" type="button" onclick="toggleNotifs()">
                Notifications
                @if ($nonLues > 0)
                    <span class="notif-badge">{{ $nonLues }}</span>
                @endif
            </button>
            <div class="notif-list" id="notifList">
                @forelse ($notifications as $notif)
                    <div class="notif-item {{ $notif->lu ? '' : 'non-lue' }}">
                        @if (!$notif->lu) <span class="notif-dot"></span> @endif
                        {{ $notif->message }}
                    </div>
                @empty
                    <div class="notif-item">Aucune notification pour le moment.</div>
                @endforelse

                @if ($nonLues > 0)
                    <form action="{{ route('superviseur.notifs.lues') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-mark-read">Tout marquer comme lu</button>
                    </form>
                @endif
            </div>
        </div>

        @forelse ($signalements as $signalement)
            <div class="carte">
                <div class="carte-top">
                    <span class="carte-type">{{ $signalement->typePanne->libelle }}</span>
                    <span class="badge">en attente</span>
                </div>
                <p class="carte-desc">{{ $signalement->description }}</p>
                <p class="carte-meta">
                    {{ $signalement->user->name }}
                    @if ($signalement->adresse) — {{ $signalement->adresse }} @endif
                    · {{ $signalement->created_at->diffForHumans() }}
                </p>

                <form action="{{ route('superviseur.affecter', $signalement->id) }}" method="POST">
                    @csrf
                    <select name="technicien_id" required>
                        <option value="">-- Choisir un technicien --</option>
                        @foreach ($techniciens as $technicien)
                            <option value="{{ $technicien->id }}">{{ $technicien->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-affecter">Affecter</button>
                </form>
            </div>
        @empty
            <div class="empty">Aucun signalement en attente.</div>
        @endforelse

        @if ($aCloturer->count())
            <h2 class="section-title">Interventions terminées — à clôturer</h2>

            @foreach ($aCloturer as $signalement)
                <div class="carte" style="border-left-color:#1E7E34;">
                    <div class="carte-top">
                        <span class="carte-type">{{ $signalement->typePanne->libelle }}</span>
                        <span class="badge" style="background:#E6F4EA; color:#1E7E34;">terminée</span>
                    </div>
                    <p class="carte-desc">{{ $signalement->description }}</p>
                    <p class="carte-meta">
                        {{ $signalement->user->name }}
                        @if ($signalement->adresse) — {{ $signalement->adresse }} @endif
                    </p>

                    <form action="{{ route('superviseur.cloturer', $signalement->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-affecter" style="background:#1E7E34;">Clôturer</button>
                    </form>
                </div>
            @endforeach
        @endif
    </div>

    <script>
    function toggleNotifs() {
        document.getElementById('notifList').classList.toggle('open');
    }
    </script>
</body>
</html>
