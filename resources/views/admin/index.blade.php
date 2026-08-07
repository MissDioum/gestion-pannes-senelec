<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #F5F5F3; color: #2B2B2B; }
        .header { background: #1976D2; color: #fff; padding: 18px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
        .header h1 { font-size: 18px; font-weight: 600; }
        .header-actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
        .btn-new { background: #fff; color: #1976D2; text-decoration: none; font-size: 13px; font-weight: 600; padding: 8px 14px; border-radius: 20px; }
        .btn-link { background: rgba(255,255,255,0.2); color: #fff; text-decoration: none; font-size: 13px; font-weight: 600; padding: 8px 14px; border-radius: 20px; }
        .btn-link:hover { background: rgba(255,255,255,0.28); }
        .container { max-width: 800px; margin: 0 auto; padding: 20px 16px 40px; }
        .alert-success { background: #E6F4EA; color: #1E7E34; border: 1px solid #B7E1C1; padding: 10px 14px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
        th, td { padding: 12px 16px; text-align: left; font-size: 14px; }
        th { background: #F5F5F3; color: #666; font-size: 12px; text-transform: uppercase; }
        tr:not(:last-child) td { border-bottom: 1px solid #EEE; }
        .role-badge { font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; }
        .role-abonne { background: #F0F0F0; color: #666; }
        .role-superviseur { background: #E3F2FD; color: #1976D2; }
        .role-technicien { background: #FFF3E0; color: #E85D0C; }
        .role-administrateur { background: #F3E5F5; color: #7B1FA2; }
        .btn-delete { color: #C0392B; text-decoration: none; font-size: 13px; font-weight: 600; }
        .btn-delete:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Gestion des utilisateurs</h1>
        <div class="header-actions">
            <a href="{{ route('admin.dashboard') }}" class="btn-new">Vue globale</a>
            <a href="{{ route('admin.export') }}" class="btn-new">Exporter Excel</a>
            <a href="{{ route('admin.create') }}" class="btn-new">+ Creer un compte</a>
            <a href="{{ route('profile.edit') }}" class="btn-link">Profil</a>
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-link" style="border:none;">Deconnexion</button>
            </form>
        </div>
    </div>

    <div class="container">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <table>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Role</th>
                <th></th>
            </tr>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="role-badge role-{{ $user->role }}">{{ $user->role }}</span></td>
                    <td>
                        @if ($user->id !== auth()->id())
                            <form action="{{ route('admin.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Supprimer ce compte ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" style="background:none;border:none;cursor:pointer;">Supprimer</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</body>
</html>
