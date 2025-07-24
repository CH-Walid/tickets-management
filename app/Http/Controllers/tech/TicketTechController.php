<?php

namespace App\Http\Controllers\tech;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Commentaire;
use App\Http\Controllers\Controller;

class TicketTechController extends Controller
{
    // 🟢 Liste des tickets assignés
    public function index()
    {
        $tickets = Ticket::with('categorie')->where('technicien_id', auth()->id())->get();
        return view('tech.tickets.index', compact('tickets'));
    }

    // 🔍 Détails d’un ticket
    public function show($id)
    {
        $ticket = Ticket::with('categorie') // 👈 eager load the 'categorie' relationship
        ->where('technicien_id', auth()->id())
        ->findOrFail($id);
        return view('tech.tickets.show', compact('ticket'));
    }

    // ✏️ Modifier (statut uniquement)
    public function edit($id)
    {
        $ticket = Ticket::with('categorie') // 👈 eager load the 'categorie' relationship
        ->where('technicien_id', auth()->id())
        ->findOrFail($id);

        return view('tech.tickets.edit', compact('ticket'));
    }

    // 💾 Mise à jour du statut
    public function update(Request $request, $id)
    {
        $ticket = Ticket::where('technicien_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:nouveau,en_cours,cloturé,résolu'
        ]);

        $ticket->status = $validated['status'];
        $ticket->save();

        return redirect()->route('tickets.show', $ticket->id)
                         ->with('success', 'Le ticket a été mis à jour avec succès.');
    }

    // 🗨️ Ajouter un commentaire
    public function commenter(Request $request, $id)
    {
        if (!$request->isMethod('post')) {
            abort(405, 'Méthode non autorisée');
        }

        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $ticket = Ticket::where('technicien_id', auth()->id())->findOrFail($id);

        Commentaire::create([
            'content' => $request->input('content'),
            'ticket_id' => $ticket->id,
            'technicien_id' => auth()->id(),
        ]);

        return redirect()->route('tickets.show', $ticket->id)
                         ->with('success', 'Commentaire ajouté avec succès.');
    }
}
