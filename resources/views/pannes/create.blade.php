<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signaler une panne</title>
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
            max-width: 420px;
            margin: 0 auto;
            padding: 24px 20px 40px;
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
        .alert-error {
            background: #FDECEC;
            color: #C0392B;
            border: 1px solid #F5B7B1;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
        }
        .alert-error ul { padding-left: 18px; }
        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #555;
            margin-bottom: 6px;
            margin-top: 16px;
        }
        select, textarea, input[type="text"], input[type="file"] {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #DDD;
            border-radius: 8px;
            font-size: 14px;
            background: #fff;
            font-family: inherit;
        }
        select:focus, textarea:focus, input:focus {
            outline: none;
            border-color: #E85D0C;
        }
        textarea { resize: vertical; min-height: 90px; }
        .localisation-box {
            background: #fff;
            border: 1px solid #DDD;
            border-radius: 8px;
            padding: 14px;
            margin-top: 8px;
        }
        .btn-gps {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: none;
            border: none;
            color: #E85D0C;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            padding: 0;
        }
        .btn-gps:hover { text-decoration: underline; }
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
        #statut-localisation {
            font-size: 12px;
            color: #777;
            margin-top: 8px;
        }
        .adresse-fallback { margin-top: 14px; }
        .adresse-fallback label { margin-top: 0; font-weight: 500; color: #888; font-size: 12px; }
        .btn-submit {
            width: 100%;
            background: #E85D0C;
            color: #fff;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            margin-top: 28px;
            cursor: pointer;
        }
        .btn-submit:hover { background: #CC5209; }
        .footer-link {
            text-align: center;
            margin-top: 16px;
            font-size: 13px;
        }
        .footer-link a { color: #E85D0C; text-decoration: none; }
    </style>
</head>
<body>
    <div class="header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
        <div>Signaler une panne</div>
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
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pannes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label for="type_panne_id">Type de panne</label>
            <select name="type_panne_id" id="type_panne_id" required>
                <option value="">-- Sélectionner --</option>
                @foreach ($typesPannes as $type)
                    <option value="{{ $type->id }}">{{ $type->libelle }}</option>
                @endforeach
            </select>

            <label for="description">Description</label>
            <textarea name="description" id="description" placeholder="Décrivez la panne..." required></textarea>

            <label>Localisation</label>
            <div class="localisation-box">
                <button type="button" class="btn-gps" id="btn-localiser">
                    📍 Cliquez sur l'icône pour utiliser votre GPS
                </button>
                <p id="statut-localisation"></p>

                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">

                <div class="adresse-fallback">
                    <label for="adresse">Votre adresse (si le GPS ne fonctionne pas)</label>
                    <input type="text" name="adresse" id="adresse" placeholder="Ex: Sacré-Cœur 3, Dakar">
                </div>
            </div>

            <label for="photo">Photo (optionnel)</label>
            <input type="file" name="photo" id="photo" accept="image/*">

            <button type="submit" class="btn-submit">Envoyer le signalement</button>
        </form>

        <div class="footer-link">
            <a href="{{ route('pannes.index') }}">Voir mes signalements</a>
        </div>
    </div>

    <script>
        document.getElementById('btn-localiser').addEventListener('click', function () {
            const statut = document.getElementById('statut-localisation');

            if (!navigator.geolocation) {
                statut.textContent = "La géolocalisation n'est pas supportée par votre navigateur.";
                return;
            }

            statut.textContent = "Localisation en cours...";

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                    statut.textContent = "Position récupérée avec succès.";
                },
                function () {
                    statut.textContent = "Impossible de récupérer votre position. Renseignez une adresse.";
                }
            );
        });
    </script>
</body>
</html>
