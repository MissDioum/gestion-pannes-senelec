<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail du signalement</title>
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
        }
        .container {
            max-width: 480px;
            margin: 0 auto;
            padding: 20px 16px 40px;
        }
        .carte {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        .badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            margin-bottom: 16px;
            transition: background 0.3s, color 0.3s;
        }
        .badge-en_attente { background: #FFF3E0; color: #E85D0C; }
        .badge-affecte { background: #E3F2FD; color: #1976D2; }
        .badge-en_cours { background: #FFF9C4; color: #A98600; }
        .badge-cloture { background: #E6F4EA; color: #1E7E34; }
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
        .champ {
            margin-bottom: 16px;
        }
        .champ-label {
            font-size: 12px;
            font-weight: 600;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .champ-valeur {
            font-size: 15px;
            color: #2B2B2B;
        }
        .photo {
            width: 100%;
            border-radius: 10px;
            margin-top: 8px;
        }
        .footer-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #E85D0C;
            text-decoration: none;
        }
        .maj-info {
            text-align: center;
            font-size: 11px;
            color: #AAA;
            margin-top: 12px;
        }
    </style>
</head>
<body>
    <div class="header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
        <div>Détail du signalement</div>
        <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
            <a href="{{ route('pannes.index') }}" class="btn-new">Mes signalements</a>
            <a href="{{ route('profile.edit') }}" class="btn-new">Profil</a>
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-link">Deconnexion</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="carte">
            <span class="badge badge-{{ $signalement->statut }}" id="badge-statut">
                {{ str_replace('_', ' ', $signalement->statut) }}
            </span>

            <div class="champ">
                <div class="champ-label">Type de panne</div>
                <div class="champ-valeur">{{ $signalement->typePanne->libelle }}</div>
            </div>

            <div class="champ">
                <div class="champ-label">Description</div>
                <div class="champ-valeur">{{ $signalement->description }}</div>
            </div>

            <div class="champ">
                <div class="champ-label">Date du signalement</div>
                <div class="champ-valeur">{{ $signalement->created_at->format('d/m/Y à H:i') }}</div>
            </div>

            @if ($signalement->latitude && $signalement->longitude)
                <div class="champ">
                    <div class="champ-label">Position GPS</div>
                    <div class="champ-valeur">{{ $signalement->latitude }}, {{ $signalement->longitude }}</div>
                </div>
            @endif

            @if ($signalement->adresse)
                <div class="champ">
                    <div class="champ-label">Adresse</div>
                    <div class="champ-valeur">{{ $signalement->adresse }}</div>
                </div>
            @endif

            @if ($signalement->photo)
                <div class="champ">
                    <div class="champ-label">Photo</div>
                    <img src="{{ Storage::url($signalement->photo) }}" alt="Photo de la panne" class="photo">
                </div>
            @endif
        </div>

        <p class="maj-info" id="maj-info">Mise à jour automatique du statut activée</p>

        <a href="{{ route('pannes.index') }}" class="footer-link">← Retour à mes signalements</a>
    </div>

    <script>
        const signalementId = Number('{{ $signalement->id }}');
        const badge = document.getElementById('badge-statut');
        const majInfo = document.getElementById('maj-info');

        function libelleAffiche(statut) {
            return statut.replace('_', ' ');
        }

        function verifierStatut() {
            fetch(`/pannes/${signalementId}/statut`)
                .then(response => response.json())
                .then(data => {
                    const nouveauStatut = data.statut;
                    const statutActuel = badge.className.replace('badge badge-', '').trim();

                    if (nouveauStatut !== statutActuel) {
                        badge.className = 'badge badge-' + nouveauStatut;
                        badge.textContent = libelleAffiche(nouveauStatut);
                        majInfo.textContent = 'Statut mis à jour à ' + new Date().toLocaleTimeString('fr-FR');
                    }
                })
                .catch(() => {
                    majInfo.textContent = 'Impossible de vérifier le statut pour le moment';
                });
        }

        setInterval(verifierStatut, 8000);
    </script>
</body>
</html>
