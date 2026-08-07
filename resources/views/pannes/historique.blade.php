<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon historique</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #F5F5F3; color: #2B2B2B; }
        .header { background: #E85D0C; color: #fff; padding: 18px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; }
        .header h1 { font-size: 18px; font-weight: 600; }
        .header-actions { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }
        .btn-new { background: #fff; color: #E85D0C; text-decoration: none; font-size: 13px; font-weight: 600; padding: 8px 14px; border-radius: 20px; display: inline-flex; align-items: center; }
        .btn-link { background: rgba(255,255,255,0.2); color: #fff; text-decoration: none; font-size: 13px; font-weight: 600; padding: 8px 14px; border-radius: 20px; display: inline-flex; align-items: center; }
        .btn-link:hover { background: rgba(255,255,255,0.28); }
        .container { max-width: 480px; margin: 0 auto; padding: 20px 16px 40px; }
        .empty { text-align: center; color: #999; padding: 60px 20px; font-size: 15px; }
        .empty-icon { font-size: 48px; margin-bottom: 12px; }
        .carte { background: #fff; border-radius: 10px; padding: 16px; margin-bottom: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); border-left: 4px solid #1E7E34; }
        .carte-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; }
        .carte-type { font-weight: 600; font-size: 15px; }
        .badge { font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 20px; background: #E6F4EA; color: #1E7E34; }
        .carte-desc { font-size: 13px; color: #666; margin-bottom: 8px; line-height: 1.4; }
        .carte-date { font-size: 12px; color: #999; }
        .count-tag { display: inline-block; background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 20px; font-size: 13px; margin-left: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Mon historique <span class="count-tag">{{ $signalements->count() }}</span></h1>
        <div class="header-actions">
            <a href="{{ route('pannes.index') }}" class="btn-new">Mes signalements</a>
            <a href="{{ route('pannes.create') }}" class="btn-new">Signaler une panne</a>
            <a href="{{ route('profile.edit') }}" class="btn-link">Profil</a>
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-link" style="border:none;">Deconnexion</button>
            </form>
        </div>
    </div>

    <div class="container">
        @forelse ($signalements as $signalement)
            <div class="carte">
                <div class="carte-top">
                    <span class="carte-type">{{ $signalement->typePanne->libelle }}</span>
                    <span class="badge">✓ Clôturé</span>
                </div>
                <p class="carte-desc">{{ $signalement->description }}</p>
                <p class="carte-date">
                    Signalé le {{ $signalement->created_at->format('d/m/Y à H:i') }}
                    @if ($signalement->adresse) · {{ $signalement->adresse }} @endif
                </p>
            </div>
        @empty
            <div class="empty">
                <div class="empty-icon">📋</div>
                Aucun signalement clôturé pour le moment.
            </div>
        @endforelse
    </div>
</body>
</html>
