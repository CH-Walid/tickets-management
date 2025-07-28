<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Technicien;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::orderByDesc('created_at')->limit(50)->get();
        return view('admin.tickets', compact('tickets'));
    }

    public function dashboard()
    {
        $totalTickets = \App\Models\Ticket::count();
        $openTickets = \App\Models\Ticket::whereIn('status', ['nouveau'])->count();
        $inProgressTickets = \App\Models\Ticket::where('status', 'en_cours')->count();
        $closedTickets = \App\Models\Ticket::whereIn('status', ['cloturé', 'résolu'])->count();
        $latestTickets = \App\Models\Ticket::orderByDesc('created_at')->limit(5)->get();
        $totalUsers = \App\Models\User::where('role', 'user_simple')->count();
        $totalTechniciens = Technicien::count();
        $techniciensList = Technicien::with('user')->get();

 
        // Récupérer tous les statuts distincts de la base
        $allStatuses = \App\Models\Ticket::select('status')->distinct()->pluck('status')->toArray();
        // Générer exactement 12 mois consécutifs à partir de juillet 2025
        $months = collect();
        $monthLabels = collect();
        $startDate = \Carbon\Carbon::create(2025, 7, 1); // Juillet 2025
        for ($i = 0; $i < 12; $i++) {
            $months->push($startDate->copy()->addMonths($i)->format('Y-m'));
            $monthLabels->push($startDate->copy()->addMonths($i)->format('M Y'));
        }
        // Générer les données du graphe pour chaque statut
        $ticketsChartData = [];
        foreach ($allStatuses as $status) {
            $ticketsChartData[$status] = [];
            foreach ($months as $month) {
                $year = substr($month, 0, 4);
                $monthNum = substr($month, 5, 2);
                $ticketsChartData[$status][] = \App\Models\Ticket::where('status', $status)
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $monthNum)
                    ->count();
            }
        }
        return view('admin.dashboard', compact(
            'totalTickets',
            'openTickets',
            'inProgressTickets',
            'closedTickets',
            'latestTickets',
            'totalUsers',
            'totalTechniciens',
            'techniciensList',
            'monthLabels',
            'ticketsChartData',
            'allStatuses',
        ));
    }

    
    public function profil()
    {
        return view('admin.profil');
    }

   
    public function editProfil()
    {
        return view('admin.profil-edit');
    }

    public function updateProfil(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'nom' => 'nullable|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:30',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
     
        if ($request->hasFile('photo')) {
            if ($user->img && Storage::disk('public')->exists($user->img)) {
                Storage::disk('public')->delete($user->img);
            }
            $imgPath = $request->file('photo')->store('profile-images', 'public');
            $user->img = $imgPath;
        }
        if ($request->has('delete_photo') && $request->input('delete_photo')) {
            if ($user->img && Storage::disk('public')->exists($user->img)) {
                Storage::disk('public')->delete($user->img);
            }
            $user->img = null;
        }
        $user->nom = $validated['nom'];
        $user->prenom = $validated['prenom'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->save();
        return redirect()->route('admin.profil')->with('success', 'Profil mis à jour avec succès.');
    }

    public function parametres()
    {
        return view('admin.parametres');
    }

    public function updateParametres(Request $request)
    {

        return redirect()->route('admin.admin.parametres')->with('success', 'Paramètres enregistrés avec succès.');
    }

    public function password(Request $request)
    {
        if ($request->isMethod('post')) {
            $user = auth()->user();
            $request->validate([
                'current_password' => 'required',
                'password' => 'required|string|min:8|confirmed',
            ]);
            if (!\Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
            }
            $user->password = \Hash::make($request->password);
            $user->password_changed_at = now();
            $user->save();
            return redirect()->route('admin.password')->with('success', 'Mot de passe mis à jour avec succès.');
        }
        return view('admin.password');
    }

    public function search(Request $request)
    {
        $q = $request->input('q');
        if (!$q || strlen($q) < 1) {
            return response()->json(['tickets' => [], 'users' => []]);
        }

        $tickets = \App\Models\Ticket::where('titre', 'like', "%$q%")
            ->orWhere('description', 'like', "%$q%")
            ->orWhere('id', $q)
            ->limit(5)
            ->get(['id', 'titre', 'status']);

        $users = \App\Models\User::where('nom', 'like', "%$q%")
            ->orWhere('prenom', 'like', "%$q%")
            ->orWhere('email', 'like', "%$q%")
            ->limit(5)
            ->get(['id', 'nom', 'prenom', 'email', 'role']);

        return response()->json([
            'tickets' => $tickets,
            'users' => $users
        ]);
    }
}
