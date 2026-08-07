<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques — Superviseur</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #F5F5F3; color: #2B2B2B; }
        .header { background: linear-gradient(135deg, #1565C0, #1976D2); color: #fff; padding: 24px 20px; }
        .header h1 { font-size: 19px; font-weight: 600; margin-bottom: 4px; }
        .header p { font-size: 13px; opacity: 0.85; }
        .header-actions { margin-top: 14px; display: flex; gap: 8px; }
        .btn-back { background: rgba(255,255,255,0.2); color: #fff; text-decoration: none; font-size: 13px; padding: 8px 14px; border-radius: 20px; }
        .container { max-width: 640px; margin: 0 auto; padding: 20px 16px 40px; }
        .section-title { font-size: 14px; font-weight: 600; color: #555; margin: 24px 0 12px; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Stats grid */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 8px; }
        .stat-card { background: #fff; border-radius: 12px; padding: 16px 12px; text-align: center; box-shadow: 0 1px 4px rgba(0,0,0,0.06); }
        .stat-num { font-size: 28px; font-weight: 700; margin-bottom: 4px; }
        .stat-label { font-size: 12px; color: #777; }
        .stat-total { background: #1976D2; color: #fff; }
        .stat-total .stat-label { color: rgba(255,255,255,0.8); }
        .num-attente { color: #E85D0C; }
        .num-affecte { color: #1976D2; }
        .num-cours { color: #A98600; }
        .num-termine { color: #7B1FA2; }
        .num-cloture { color: #1E7E34; }

        /* Bar chart */
        .bar-section { background: #fff; border-radius: 12px; padding: 18px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); margin-bottom: 16px; }
        .bar-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
        .bar-label { width: 150px; font-size: 13px; color: #444; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .bar-track { flex: 1; background: #EEE; border-radius: 8px; height: 14px; overflow: hidden; }
        .bar-fill { height: 100%; border-radius: 8px; background: linear-gradient(90deg, #1976D2, #42A5F5); transition: width 0.5s; }
        .bar-val { font-size: 13px; font-weight: 700; color: #1976D2; width: 30px; text-align: right; }

        /* Techniciens */
        .tech-list { display: flex; flex-direction: column; gap: 10px; }
        .tech-card { background: #fff; border-radius: 10px; padding: 14px 16px; display: flex; align-items: center; gap: 14px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); }
        .tech-avatar { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #1565C0, #42A5F5); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 16px; font-weight: 700; }
        .tech-name { font-size: 14px; font-weight: 600; }
        .tech-count { font-size: 12px; color: #888; }
        .tech-badge { margin-left: auto; background: #E3F2FD; color: #1976D2; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Statistiques</h1>
        <p>Vue globale des signalements</p>
        <div class="header-actions">
            <a href="{{ route('superviseur.index') }}" class="btn-back">← Signalements</a>
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-back" style="background: rgba(255,255,255,0.2);">Deconnexion</button>
            </form>
        </div>
    </div>

    <div class="container">

        <!-- Total -->
        <div class="stats-grid" style="margin-top: 8px;">
            <div class="stat-card stat-total" style="grid-column: span 3;">
                <div class="stat-num">{{ $total }}</div>
                <div class="stat-label">Total signalements</div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-num num-attente">{{ $enAttente }}</div>
                <div class="stat-label">En attente</div>
            </div>
            <div class="stat-card">
                <div class="stat-num num-affecte">{{ $affecte }}</div>
                <div class="stat-label">Affectés</div>
            </div>
            <div class="stat-card">
                <div class="stat-num num-cours">{{ $enCours }}</div>
                <div class="stat-label">En cours</div>
            </div>
            <div class="stat-card">
                <div class="stat-num num-termine">{{ $termine }}</div>
                <div class="stat-label">Terminés</div>
            </div>
            <div class="stat-card">
                <div class="stat-num num-cloture">{{ $cloture }}</div>
                <div class="stat-label">Clôturés</div>
            </div>
        </div>

        <!-- Par type de panne -->
        <p class="section-title">Par type de panne</p>
        <div class="bar-section">
            @forelse ($parType as $item)
                @php $barWidth = $total > 0 ? round($item->total / $total * 100) : 0; @endphp
                <div class="bar-row">
                    <span class="bar-label" title="{{ $item->typePanne->libelle ?? 'Inconnu' }}">
                        {{ $item->typePanne->libelle ?? 'Inconnu' }}
                    </span>
                    <div class="bar-track">
                        <div class="bar-fill" data-width="{{ $barWidth }}"></div>
                    </div>
                    <span class="bar-val">{{ $item->total }}</span>
                </div>
            @empty
                <p style="color:#999; font-size:13px;">Aucune donnée</p>
            @endforelse
        </div>

        <script>
            document.querySelectorAll('.bar-fill').forEach(function(el) {
                const width = el.dataset.width;
                if (width !== undefined) {
                    el.style.width = width + '%';
                }
            });
        </script>

        <!-- Techniciens -->
        <p class="section-title">Techniciens</p>
        <div class="tech-list">
            @forelse ($techniciens as $tech)
                <div class="tech-card">
                    <div class="tech-avatar">{{ strtoupper(substr($tech->name, 0, 1)) }}</div>
                    <div>
                        <div class="tech-name">{{ $tech->name }}</div>
                        <div class="tech-count">{{ $tech->missions_count }} mission(s) assignée(s)</div>
                    </div>
                    <span class="tech-badge">{{ $tech->missions_count }}</span>
                </div>
            @empty
                <p style="color:#999; font-size:13px;">Aucun technicien</p>
            @endforelse
        </div>
    </div>
</body>
</html>
