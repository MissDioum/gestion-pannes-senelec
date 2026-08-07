<?php
namespace App\Http\Controllers;

use App\Models\Signalement;
use App\Models\User;
use App\Models\Intervention;
use App\Models\Notification;
use Illuminate\Http\Request;

class SuperviseurController extends Controller
{
    public function index()
    {
        $signalements = Signalement::where('statut', 'en_attente')
            ->with('typePanne', 'user')
            ->latest()
            ->get();

        $aCloturer = Signalement::where('statut', 'termine')
            ->with('typePanne', 'user')
            ->latest()
            ->get();

        $techniciens = User::where('role', 'technicien')->get();

        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->take(10)
            ->get();

        $nonLues = Notification::where('user_id', auth()->id())->where('lu', false)->count();

        return view('superviseur.index', compact('signalements', 'aCloturer', 'techniciens', 'notifications', 'nonLues'));
    }

    public function statistiques()
    {
        $total     = Signalement::count();
        $enAttente = Signalement::where('statut', 'en_attente')->count();
        $affecte   = Signalement::where('statut', 'affecte')->count();
        $enCours   = Signalement::where('statut', 'en_cours')->count();
        $termine   = Signalement::where('statut', 'termine')->count();
        $cloture   = Signalement::where('statut', 'cloture')->count();

        $parType = Signalement::selectRaw('type_panne_id, count(*) as total')
            ->groupBy('type_panne_id')
            ->with('typePanne')
            ->get();

        $techniciens = User::where('role', 'technicien')
            ->withCount(['interventionsTech as missions_count'])
            ->get();

        return view('superviseur.statistiques', compact(
            'total', 'enAttente', 'affecte', 'enCours', 'termine', 'cloture', 'parType', 'techniciens'
        ));
    }

    public function marquerLues()
    {
        Notification::where('user_id', auth()->id())->update(['lu' => true]);
        return back();
    }

    public function affecter(Request $request, string $id)
    {
        $request->validate([
            'technicien_id' => 'required|exists:users,id',
        ]);

        $signalement = Signalement::where('statut', 'en_attente')->findOrFail($id);

        Intervention::create([
            'signalement_id'   => $signalement->id,
            'technicien_id'    => $request->technicien_id,
            'date_affectation' => now(),
        ]);

        $signalement->update(['statut' => 'affecte']);

        Notification::create([
            'user_id'        => $request->technicien_id,
            'type'           => 'affectation',
            'message'        => 'Vous avez ete affecte a une nouvelle intervention : ' . $signalement->typePanne->libelle,
            'signalement_id' => $signalement->id,
        ]);

        Notification::create([
            'user_id'        => $signalement->user_id,
            'type'           => 'statut_change',
            'message'        => 'Votre signalement a ete pris en charge et un technicien a ete affecte.',
            'signalement_id' => $signalement->id,
        ]);

        return redirect()->route('superviseur.index')
            ->with('success', 'Signalement affecte avec succes.');
    }

    public function cloturer(string $id)
    {
        $signalement = Signalement::where('statut', 'termine')->findOrFail($id);
        $signalement->update(['statut' => 'cloture']);

        Notification::create([
            'user_id'        => $signalement->user_id,
            'type'           => 'cloture',
            'message'        => 'Votre signalement a ete cloture. L\'intervention est terminee.',
            'signalement_id' => $signalement->id,
        ]);

        return redirect()->route('superviseur.index')
            ->with('success', 'Signalement cloture avec succes.');
    }
}
