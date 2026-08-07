<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vue globale</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #F5F5F3; color: #2B2B2B; }
        .header { background: #1976D2; color: #fff; padding: 18px 20px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 18px; font-weight: 600; }
        .nav-links { display: flex; gap: 8px; }
        .nav-links a { color: #fff; text-decoration: none; font-size: 13px; font-weight: 600; padding: 8px 14px; border-radius: 20px; background: rgba(255,255,255,0.15); display: inline-flex; align-items: center; justify-content: center; }
        .nav-links a.active { background: #fff; color: #1976D2; }
        .btn-link { display: inline-flex; align-items: center; justify-content: center; padding: 8px 14px; border-radius: 20px; background: rgba(255,255,255,0.15); color: #fff; border: none; text-decoration: none; font-size: 13px; font-weight: 600; cursor: pointer; }
        .btn-link:hover { background: rgba(255,255,255,0.28); }
        .container { max-width: 900px; margin: 0 auto; padding: 20px 16px 40px; }
        .section-title { font-size: 13px; font-weight: 600; color: #999; text-transform: uppercase; margin: 20px 0 10px; }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
        .stat-card { background: #fff; border-radius: 10px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); text-align: center; }
        .stat-num { font-size: 26px; font-weight: 700; }
        .stat-label { font-size: 12px; color: #888; margin-top: 4px; }
        .stat-en_attente .stat-num { color: #E85D0C; }
        .stat-affecte .stat-num { color: #1976D2; }
        .stat-en_cours .stat-num { color: #A98600; }
        .stat-cloture .stat-num { color: #1E7E34; }
        .roles-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .role-card { background: #fff; border-radius: 10px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); text-align: center; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.06); margin-top: 8px; }
        th, td { padding: 10px 14px; text-align: left; font-size: 13px; }
        th { background: #F5F5F3; color: #666; font-size: 11px; text-transform: uppercase; }
        tr:not(:last-child) td { border-bottom: 1px solid #EEE; }
        .badge { font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 20px; }
        .badge-en_attente { background: #FFF3E0; color: #E85D0C; }
        .badge-affecte { background: #E3F2FD; color: #1976D2; }
        .badge-en_cours { background: #FFF9C4; color: #A98600; }
        .badge-cloture { background: #E6F4EA; color: #1E7E34; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SENELEC - Administrateur</h1>
        <div class="nav-links">
            <a href="{{ route('admin.dashboard') }}" class="active">Vue globale</a>
            <a href="{{ route('admin.index') }}">Utilisateurs</a>
            <a href="{{ route('admin.export') }}">Exporter</a>
            <a href="{{ route('profile.edit') }}">Profil</a>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn-link">Deconnexion</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="section-title">Signalements</div>
        <div class="stats-grid">
            <div class="stat-card stat-en_attente">
                <div class="stat-num">{{ $enAttente }}</div>
                <div class="stat-label">En attente</div>
            </div>
            <div class="stat-card stat-affecte">
                <div class="stat-num">{{ $affecte }}</div>
                <div class="stat-label">Affectes</div>
            </div>
            <div class="stat-card stat-en_cours">
                <div class="stat-num">{{ $enCours }}</div>
                <div class="stat-label">En cours</div>
            </div>
            <div class="stat-card stat-cloture">
                <div class="stat-num">{{ $cloture }}</div>
                <div class="stat-label">Clotures</div>
            </div>
        </div>

        <div class="section-title">Utilisateurs ({{ $totalAbonnes + $totalTechniciens + $totalSuperviseurs }} au total)</div>
        <div class="roles-grid">
            <div class="role-card">
                <div class="stat-num">{{ $totalAbonnes }}</div>
                <div class="stat-label">Abonnes</div>
            </div>
            <div class="role-card">
                <div class="stat-num">{{ $totalSuperviseurs }}</div>
                <div class="stat-label">Superviseurs</div>
            </div>
            <div class="role-card">
                <div class="stat-num">{{ $totalTechniciens }}</div>
                <div class="stat-label">Techniciens</div>
            </div>
        </div>

        <div class="section-title">Derniers signalements</div>
        <table>
            <tr>
                <th>Type</th>
                <th>Abonne</th>
                <th>Statut</th>
                <th>Date</th>
            </tr>
            @foreach ($dernierSignalements as $s)
                <tr>
                    <td>{{ $s->typePanne->libelle }}</td>
                    <td>{{ $s->user->name }}</td>
                    <td><span class="badge badge-{{ $s->statut }}">{{ str_replace('_', ' ', $s->statut) }}</span></td>
                    <td>{{ $s->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</body>
</html>
