<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #F5F5F3; color: #2B2B2B; }
        .header { background: #1976D2; color: #fff; padding: 18px 20px; font-size: 18px; font-weight: 600; text-align: center; }
        .container { max-width: 420px; margin: 0 auto; padding: 24px 20px 40px; }
        .alert-error { background: #FDECEC; color: #C0392B; border: 1px solid #F5B7B1; padding: 10px 14px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
        .alert-error ul { padding-left: 18px; }
        label { display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 6px; margin-top: 16px; }
        select, input[type="text"], input[type="email"], input[type="password"] {
            width: 100%; padding: 12px 14px; border: 1px solid #DDD; border-radius: 8px; font-size: 14px; background: #fff; font-family: inherit;
        }
        select:focus, input:focus { outline: none; border-color: #1976D2; }
        .btn-submit { width: 100%; background: #1976D2; color: #fff; border: none; padding: 14px; border-radius: 8px; font-size: 15px; font-weight: 600; margin-top: 28px; cursor: pointer; }
        .btn-submit:hover { background: #1565C0; }
        .footer-link { display: block; text-align: center; margin-top: 16px; font-size: 13px; color: #1976D2; text-decoration: none; }
    </style>
</head>
<body>
    <div class="header">Créer un compte</div>

    <div class="container">
        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.store') }}" method="POST">
            @csrf

            <label for="name">Nom complet</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>

            <label for="telephone">Téléphone (optionnel)</label>
            <input type="text" name="telephone" id="telephone" value="{{ old('telephone') }}">

            <label for="role">Rôle</label>
            <select name="role" id="role" required>
                <option value="">-- Sélectionner --</option>
                <option value="superviseur">Superviseur</option>
                <option value="technicien">Technicien</option>
                <option value="administrateur">Administrateur</option>
            </select>

            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>

            <label for="password_confirmation">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>

            <button type="submit" class="btn-submit">Créer le compte</button>
        </form>

        <a href="{{ route('admin.index') }}" class="footer-link">← Retour à la liste</a>
    </div>
</body>
</html>
