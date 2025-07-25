<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Service;
use App\Models\Categorie;
use App\Exports\TicketsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Technicien;

class TicketAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['userSimple.user', 'technicien.user', 'service', 'categorie']);
        if ($q = $request->input('q')) {
            $query->where('titre', 'like', "%$q%")
                ->orWhere('description', 'like', "%$q%")
                ->orWhere('id', $q);
        }
     
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($service = $request->input('service_id')) {
            $query->whereHas('userSimple', function($q) use ($service) {
                $q->where('service_id', $service);
            });
        }
        if ($tech = $request->input('technicien_id')) {
            $query->where('technicien_id', $tech);
        }
        if ($user = $request->input('user_simple_id')) {
            $query->where('user_simple_id', $user);
        }
        $tickets = $query->orderByDesc('created_at')->get();
        $services = Service::all();
        $techniciens = Technicien::with('user')->get();
        $utilisateurs = User::where('role', 'user_simple')->get();
        $categories = Categorie::all();
        return view('admin.tickets.index', compact('tickets', 'services', 'techniciens', 'utilisateurs', 'categories'));
    }

    public function show($id)
    {
        $ticket = Ticket::with(['userSimple.user', 'technicien.user', 'service', 'categorie', 'commentaires'])->findOrFail($id);
        return view('admin.tickets.show', compact('ticket'));
    }

    public function create()
    {
        $services = Service::all();
        $techniciens = User::where('role', 'technicien')->get();
        $utilisateurs = User::where('role', 'user_simple')->get();
        $categories = Categorie::all();
        return view('admin.tickets.create', compact('services', 'techniciens', 'utilisateurs', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string',
            'technicien_id' => 'nullable|exists:users,id',
            'user_simple_id' => 'required|exists:users,id',
            'categorie_id' => 'nullable|exists:categories,id',
        ]);
        $ticket = Ticket::create($validated);
        return redirect()->route('admin.tickets.index')->with('success', 'Ticket créé avec succès.');
    }

    public function edit($id)
    {
        $ticket = Ticket::with(['userSimple.user', 'technicien.user', 'service', 'categorie'])->findOrFail($id);
        $services = Service::all();
        $techniciens = User::where('role', 'technicien')->get();
        $utilisateurs = User::where('role', 'user_simple')->get();
        $categories = Categorie::all();
        return view('admin.tickets.edit', compact('ticket', 'services', 'techniciens', 'utilisateurs', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string',
            'technicien_id' => 'nullable|exists:users,id',
            'user_simple_id' => 'required|exists:users,id',
            'categorie_id' => 'nullable|exists:categories,id',
        ]);
        $ticket->update($validated);
        return redirect()->route('admin.tickets.index')->with('success', 'Ticket modifié avec succès.');
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        return redirect()->route('admin.tickets.index')->with('success', 'Ticket supprimé.');
    }

    public function export()
    {
        return Excel::download(new TicketsExport, 'tickets.xlsx');
    }
} 