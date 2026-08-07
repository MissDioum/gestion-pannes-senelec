<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'utilisateur</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #F5F5F3; color: #2B2B2B; }
        .header { background: #1976D2; color: #fff; padding: 18px 20px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 18px; font-weight: 600; }
        .btn-back { background: rgba(255,255,255,0.2); color: #fff; text-decoration: none; font-size: 13px; padding: 8px 14px; border-radius: 20px; }
        .container { max-width: 480px; margin: 30px auto; padding: 0 16px 40px; }
        .card { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
        .card h2 { font-size: 16px; font-weight: 600; margin-bottom: 20px; color: #333; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 6px; }
        input, select { width: 100%; padding: 10px 12px; border: 1px solid #DDD; border-radius: 8px; font-size: 14px; background: #FAFAFA; }
        input:focus, select:focus { outline: none; border-color: #1976D2; background: #fff; }
        .hint { font-size: 12px; color: #999; margin-top: 4px; }
        .error { font-size: 12px; color: #C0392B; margin-top: 4px; }
        .btn-submit { width: 100%; background: #1976D2; color: #fff; border: none; padding: 12px; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; margin-top: 8px; }
        .btn-submit:hover { background: #1565C0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Modifier l'utilisateur</h1>
        <a href="{{ route('admin.index') }}" class="btn-back">← Retour</a>
    </div>

    <div class="container">
        <div class="card">
            <h2>{{ $user->name }}</h2>
            <form action="{{ route('admin.update', $user->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label>Nom complet</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label>Rôle</label>
                    <select name="role" required>
                        @foreach (['abonne', 'superviseur', 'technicien', 'administrateur'] as $role)
                            <option value="{{ $role }}" {{ old('role', $user->role) === $role ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label>Téléphone</label>
                    <input type="text" name="telephone" value="{{ old('telephone', $user->telephone) }}">
                </div>

                <div class="form-group">
                    <label>Nouveau mot de passe</label>
                    <input type="password" name="password" placeholder="Laisser vide pour ne pas changer">
                    <p class="hint">Minimum 8 caractères</p>
                    @error('password') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label>Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation">
                </div>

                <button type="submit" class="btn-submit">Enregistrer les modifications</button>
            </form>
        </div>
    </div>
</body>
</html>
