<?php
namespace App\Http\Controllers;

use App\Models\Intervention;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TechnicienController extends Controller
{
    public function index()
    {
        $interventions = Intervention::where('technicien_id', Auth::id())
            ->with('signalement.typePanne', 'signalement.user')
            ->latest('date_affectation')
            ->get();

        $enAttente = $interventions->filter(fn($i) => $i->signalement->statut === 'affecte')->count();
        $enCours   = $interventions->filter(fn($i) => $i->signalement->statut === 'en_cours')->count();
        $terminees = $interventions->filter(fn($i) => $i->signalement->statut === 'termine')->count();
        $clotures  = $interventions->filter(fn($i) => $i->signalement->statut === 'cloture')->count();

        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->take(10)
            ->get();

        $nonLues = Notification::where('user_id', Auth::id())->where('lu', false)->count();

        return view('technicien.index', compact('interventions', 'enAttente', 'enCours', 'terminees', 'clotures', 'notifications', 'nonLues'));
    }

    public function marquerLues()
    {
        Notification::where('user_id', Auth::id())->update(['lu' => true]);
        return back();
    }

    public function mettreAJour(string $id)
    {
        $intervention = Intervention::where('technicien_id', Auth::id())->findOrFail($id);
        $intervention->signalement->update(['statut' => 'en_cours']);

        Notification::create([
            'user_id'        => $intervention->signalement->user_id,
            'type'           => 'statut_change',
            'message'        => 'Votre signalement est maintenant en cours de traitement par un technicien.',
            'signalement_id' => $intervention->signalement_id,
        ]);

        return redirect()->route('technicien.index')
            ->with('success', 'Mission mise a jour : intervention en cours.');
    }

    public function refuser(string $id)
    {
        $intervention = Intervention::where('technicien_id', Auth::id())->findOrFail($id);
        $signalement  = $intervention->signalement;

        $signalement->update(['statut' => 'en_attente']);
        $intervention->delete();

        $superviseur = \App\Models\User::where('role', 'superviseur')->first();
        if ($superviseur) {
            Notification::create([
                'user_id'        => $superviseur->id,
                'type'           => 'refus',
                'message'        => 'Un technicien a refuse l\'intervention : ' . $signalement->typePanne->libelle . '. Veuillez reaffecter.',
                'signalement_id' => $signalement->id,
            ]);
        }

        return redirect()->route('technicien.index')
            ->with('success', 'Mission refusee. Le superviseur en sera informe.');
    }

    public function marquerTermine(string $id)
    {
        $intervention = Intervention::where('technicien_id', Auth::id())->findOrFail($id);
        $intervention->signalement->update(['statut' => 'termine']);

        $superviseur = \App\Models\User::where('role', 'superviseur')->first();
        if ($superviseur) {
            Notification::create([
                'user_id'        => $superviseur->id,
                'type'           => 'statut_change',
                'message'        => 'L\'intervention pour "' . $intervention->signalement->typePanne->libelle . '" est terminee. En attente de cloture.',
                'signalement_id' => $intervention->signalement_id,
            ]);
        }

        return redirect()->route('technicien.index')
            ->with('success', 'Intervention marquee comme terminee. En attente de cloture par le superviseur.');
    }
}
