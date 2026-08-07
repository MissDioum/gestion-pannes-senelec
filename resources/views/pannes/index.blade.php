<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes signalements</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #F5F5F3;
            color: #2B2B2B;
        }
        .header {
            background: #E85D0C;
            color: #fff;
            padding: 18px 20px;
            font-size: 18px;
            font-weight: 600;
            text-align: center;
            position: relative;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }
        .btn-new {
            background: #fff;
            color: #E85D0C;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            padding: 8px 14px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .header-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            width: 100%;
            max-width: 460px;
        }
        .header-utility {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
            justify-content: flex-end;
            width: 100%;
        }
        .btn-link {
            background: rgba(255,255,255,0.2);
            color: #fff;
            border: none;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-link:hover { background: rgba(255,255,255,0.28); }
        .notif-bar {
            background: #fff;
            border-radius: 10px;
            padding: 14px 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            margin-bottom: 16px;
        }
        .notif-toggle {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
            background: transparent;
            font-size: 14px;
            font-weight: 600;
            color: #2B2B2B;
            cursor: pointer;
            padding: 0;
        }
        .notif-badge {
            background: #E85D0C;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 20px;
        }
        .notif-list { display: none; margin-top: 14px; }
        .notif-list.open { display: block; }
        .notif-item { padding: 10px 0; border-top: 1px solid #F4F4F4; font-size: 13px; color: #444; }
        .notif-item.non-lue { color: #E85D0C; font-weight: 700; }
        .notif-dot { display: inline-block; width: 8px; height: 8px; background: #E85D0C; border-radius: 50%; margin-right: 8px; }
        .btn-mark-read { margin-top: 12px; border: none; background: none; color: #E85D0C; font-size: 12px; font-weight: 700; cursor: pointer; padding: 0; }
        .notif-toggle {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
            background: transparent;
            font-size: 14px;
            font-weight: 600;
            color: #2B2B2B;
            cursor: pointer;
            padding: 0;
        }
        .notif-badge {
            background: #E85D0C;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 20px;
        }
        .notif-list {
            display: none;
            margin-top: 14px;
        }
        .notif-list.open {
            display: block;
        }
        .notif-item {
            padding: 10px 0;
            border-top: 1px solid #F4F4F4;
            font-size: 13px;
            color: #444;
        }
        .notif-item.non-lue {
            color: #E85D0C;
            font-weight: 700;
        }
        .notif-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: #E85D0C;
            border-radius: 50%;
            margin-right: 8px;
        }
        .btn-mark-read {
            margin-top: 12px;
            border: none;
            background: none;
            color: #E85D0C;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            padding: 0;
        }
        .container {
            max-width: 480px;
            margin: 0 auto;
            padding: 20px 16px 40px;
        }
        .alert-success {
            background: #E6F4EA;
            color: #1E7E34;
            border: 1px solid #B7E1C1;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
        }
        .empty {
            text-align: center;
            color: #999;
            padding: 40px 20px;
            font-size: 14px;
        }
        .carte {
            background: #fff;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            display: block;
            text-decoration: none;
            color: inherit;
        }
        .carte-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }
        .carte-type {
            font-weight: 600;
            font-size: 15px;
            color: #2B2B2B;
        }
        .badge {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            white-space: nowrap;
        }
        .badge-en_attente { background: #FFF3E0; color: #E85D0C; }
        .badge-affecte { background: #E3F2FD; color: #1976D2; }
        .badge-en_cours { background: #FFF9C4; color: #A98600; }
        .badge-cloture { background: #E6F4EA; color: #1E7E34; }
        .carte-desc {
            font-size: 13px;
            color: #666;
            margin-bottom: 8px;
            line-height: 1.4;
        }
        .carte-date {
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>Mes signalements</div>
        <div class="header-actions">
            <a href="{{ route('pannes.create') }}" class="btn-new">+ Nouveau</a>
            <a href="{{ route('pannes.historique') }}" class="btn-new">Historique</a>
            <a href="{{ route('profile.edit') }}" class="btn-new">Profil</a>
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-link">Deconnexion</button>
            </form>
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
                    <form action="{{ route('pannes.notifs.lues') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-mark-read">Tout marquer comme lu</button>
                    </form>
                @endif
            </div>
        </div>

        @forelse ($signalements as $signalement)
            <a href="{{ route('pannes.show', $signalement->id) }}" class="carte">
                <div class="carte-top">
                    <span class="carte-type">{{ $signalement->typePanne->libelle }}</span>
                    <span class="badge badge-{{ $signalement->statut }}">
                        {{ str_replace('_', ' ', $signalement->statut) }}
                    </span>
                </div>
                <p class="carte-desc">{{ Str::limit($signalement->description, 60) }}</p>
                <p class="carte-date">{{ $signalement->created_at->format('d/m/Y à H:i') }}</p>
            </a>
        @empty
            <div class="empty">
                Aucun signalement pour le moment.<br>
                <a href="{{ route('pannes.create') }}" style="color:#E85D0C;">Signaler une panne</a>
            </div>
        @endforelse
    </div>

    <script>
    function toggleNotifs() {
        document.getElementById('notifList').classList.toggle('open');
    }
    </script>
</body>
</html>
