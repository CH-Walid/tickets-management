<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Technicien;
use App\Models\Service;
use App\Models\Categorie;
use Carbon\Carbon;

class StatistiquesController extends Controller
{
    public function index(Request $request)
    {
        // Stats globales
        $totalTickets = Ticket::count();
        $openTickets = Ticket::where('status', 'nouveau')->count();
        $inProgressTickets = Ticket::where('status', 'en_cours')->count();
        $closedTickets = Ticket::whereIn('status', ['cloturé', 'résolu'])->count();
        $totalUsers = User::where('role', 'user_simple')->count();
        $totalTechniciens = Technicien::count();
        $totalServices = Service::count();
        $totalCategories = Categorie::count();

        // Tickets par mois (12 derniers mois)
        $months = collect(range(0, 11))->map(function($i) {
            return Carbon::now()->subMonths(11 - $i)->format('Y-m');
        });
        $ticketsPerMonth = $months->map(function($month) {
            return Ticket::whereYear('created_at', substr($month, 0, 4))
                ->whereMonth('created_at', substr($month, 5, 2))
                ->count();
        });
        $monthLabels = $months->map(function($m) {
            return Carbon::createFromFormat('Y-m', $m)->format('M y');
        });

        // Tickets par statut
        $ticketsByStatus = Ticket::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')->pluck('total', 'status');

        // Tickets par service
        $ticketsByService = Service::withCount(['tickets as total' => function($q){ $q->select(\DB::raw('count(*)')); }])->get();

        // Tickets par catégorie
        $ticketsByCategorie = Categorie::withCount(['tickets as total' => function($q){ $q->select(\DB::raw('count(*)')); }])->get();

        // Tickets par technicien
        $ticketsByTechnicien = Technicien::withCount(['tickets as total' => function($q){ $q->select(\DB::raw('count(*)')); }])->with('user')->get();

        // Utilisateurs par service
        $usersByService = Service::withCount(['userSimples as total' => function($q){ $q->select(\DB::raw('count(*)')); }])->get();

        // Top techniciens (par tickets traités)
        $topTechniciens = $ticketsByTechnicien->sortByDesc('total')->take(5);

        // Top services (par volume de tickets)
        $topServices = $ticketsByService->sortByDesc('total')->take(5);

        // Délai moyen de résolution (tickets fermés)
        $avgResolutionTime = Ticket::whereIn('status', ['cloturé', 'résolu'])
            ->whereNotNull('created_at')
            ->whereNotNull('updated_at')
            ->get()
            ->map(function($t) {
                return $t->updated_at->diffInHours($t->created_at);
            })->avg();

        // Taux de résolution
        $resolutionRate = $totalTickets > 0 ? round($closedTickets / $totalTickets * 100, 1) : 0;

        return view('admin.statistiques.index', compact(
            'totalTickets', 'openTickets', 'inProgressTickets', 'closedTickets',
            'totalUsers', 'totalTechniciens', 'totalServices', 'totalCategories',
            'ticketsPerMonth', 'monthLabels', 'ticketsByStatus',
            'ticketsByService', 'ticketsByCategorie', 'ticketsByTechnicien',
            'usersByService', 'topTechniciens', 'topServices',
            'avgResolutionTime', 'resolutionRate'
        ));
    }
} 