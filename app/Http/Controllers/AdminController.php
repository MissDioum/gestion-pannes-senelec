<?php
namespace App\Http\Controllers;

use App\Models\Signalement;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalSignalements = Signalement::count();
        $enAttente = Signalement::where('statut', 'en_attente')->count();
        $affecte   = Signalement::where('statut', 'affecte')->count();
        $enCours   = Signalement::where('statut', 'en_cours')->count();
        $cloture   = Signalement::where('statut', 'cloture')->count();

        $totalAbonnes      = User::where('role', 'abonne')->count();
        $totalTechniciens  = User::where('role', 'technicien')->count();
        $totalSuperviseurs = User::where('role', 'superviseur')->count();

        $dernierSignalements = Signalement::with('typePanne', 'user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSignalements', 'enAttente', 'affecte', 'enCours', 'cloture',
            'totalAbonnes', 'totalTechniciens', 'totalSuperviseurs',
            'dernierSignalements'
        ));
    }

    public function index()
    {
        $users = User::latest()->get();

        return view('admin.index', compact('users'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
            'role'      => ['required', 'in:superviseur,technicien,administrateur'],
            'telephone' => ['nullable', 'string', 'max:20'],
        ]);

        User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role'      => $validated['role'],
            'telephone' => $validated['telephone'] ?? null,
        ]);

        return redirect()->route('admin.index')
            ->with('success', 'Compte créé avec succès.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.index')
            ->with('success', 'Compte supprimé avec succès.');
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'role'      => ['required', 'in:abonne,superviseur,technicien,administrateur'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'password'  => ['nullable', 'confirmed', 'min:8'],
        ]);

        $user->name      = $validated['name'];
        $user->email     = $validated['email'];
        $user->role      = $validated['role'];
        $user->telephone = $validated['telephone'] ?? $user->telephone;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.index')
            ->with('success', 'Compte mis à jour avec succès.');
    }

    public function exportSignalements()
    {
        $signalements = Signalement::with('typePanne', 'user', 'zone')->get();

        $nomFichier = 'signalements_' . now()->format('Y-m-d_H-i') . '.csv';

        $callback = function () use ($signalements) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['ID', 'Type de panne', 'Description', 'Statut', 'Abonné', 'Adresse', 'Zone', 'Date du signalement']);

            foreach ($signalements as $s) {
                fputcsv($file, [
                    $s->id,
                    $s->typePanne->libelle,
                    $s->description,
                    str_replace('_', ' ', $s->statut),
                    $s->user->name,
                    $s->adresse ?? '—',
                    $s->zone?->libelle ?? '—',
                    $s->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->streamDownload($callback, $nomFichier, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $nomFichier . '"',
        ]);
    }
}
