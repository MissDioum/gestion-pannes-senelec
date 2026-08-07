<?php
namespace App\Http\Controllers;

use App\Models\Signalement;
use App\Models\TypePanne;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanneController extends Controller
{
    public function index()
    {
        $signalements = Signalement::where('user_id', Auth::id())
            ->with('typePanne')
            ->latest()
            ->get();

        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->take(10)
            ->get();

        $nonLues = Notification::where('user_id', Auth::id())->where('lu', false)->count();

        return view('pannes.index', compact('signalements', 'notifications', 'nonLues'));
    }

    public function historique()
    {
        $signalements = Signalement::where('user_id', Auth::id())
            ->where('statut', 'cloture')
            ->with('typePanne')
            ->latest()
            ->get();

        return view('pannes.historique', compact('signalements'));
    }

    public function marquerLues()
    {
        Notification::where('user_id', Auth::id())->update(['lu' => true]);
        return back();
    }

    public function create()
    {
        $typesPannes = TypePanne::all();
        return view('pannes.create', compact('typesPannes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_panne_id' => 'required|exists:types_pannes,id',
            'description'   => 'required|string|max:1000',
            'latitude'      => 'nullable|numeric',
            'longitude'     => 'nullable|numeric',
            'adresse'       => 'nullable|string|max:255',
            'photo'         => 'nullable|image|max:5120',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['statut'] = 'en_attente';

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('signalements', 'public');
        }

        Signalement::create($validated);

        return redirect()->route('pannes.index')
            ->with('success', 'Votre signalement a été envoyé avec succès.');
    }

    public function show(string $id)
    {
        $signalement = Signalement::where('user_id', Auth::id())
            ->with('typePanne', 'zone')
            ->findOrFail($id);

        return view('pannes.show', compact('signalement'));
    }

    public function statut(string $id)
    {
        $signalement = Signalement::where('user_id', Auth::id())->findOrFail($id);

        return response()->json([
            'statut' => $signalement->statut,
        ]);
    }
}
