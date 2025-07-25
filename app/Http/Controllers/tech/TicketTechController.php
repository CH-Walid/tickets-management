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
        $ticket = Ticket::with(['categorie', 'commentaires.technicien'])
            ->where('technicien_id', auth()->id())
            ->findOrFail($id);

        return view('tech.tickets.show', compact('ticket'));
    }

    // ✏️ Modifier (statut uniquement)
    public function edit($id)
    {
        $ticket = Ticket::with('categorie')
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

        return redirect()->route('tech.tickets.show', $ticket->id)
                         ->with('success', 'Le ticket a été mis à jour avec succès.');
    }

    // 🗨️ Ajouter un commentaire (une seule fois par ticket)
    public function commenter(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $ticket = Ticket::where('technicien_id', auth()->id())->findOrFail($id);

        $dejaCommentaire = $ticket->commentaires()
            ->where('technicien_id', auth()->id())
            ->exists();

        if ($dejaCommentaire) {
            return redirect()->route('tech.tickets.show', $ticket->id)
                             ->with('error', 'Vous avez déjà commenté ce ticket.');
        }

        Commentaire::create([
            'contenu' => $request->input('content'),
            'ticket_id' => $ticket->id,
            'technicien_id' => auth()->id(),
        ]);

        return redirect()->route('tech.tickets.show', $ticket->id)
                         ->with('success', 'Commentaire ajouté avec succès.');
    }

    // ✏️ Formulaire pour modifier un commentaire
    public function editCommentaire($id)
    {
        $commentaire = Commentaire::where('id', $id)
            ->where('technicien_id', auth()->id())
            ->firstOrFail();

        return view('tech.commentaires.edit', compact('commentaire'));
    }

    // 💾 Sauvegarder la modification du commentaire
    public function updateCommentaire(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $commentaire = Commentaire::where('id', $id)
            ->where('technicien_id', auth()->id())
            ->firstOrFail();

        $commentaire->contenu = $request->input('content');
        $commentaire->save();

        return redirect()->route('tech.tickets.show', $commentaire->ticket_id)
                         ->with('success', 'Commentaire modifié avec succès.');
    }

    // ❌ Supprimer un commentaire
    public function deleteCommentaire($id)
    {
        $commentaire = Commentaire::where('id', $id)
            ->where('technicien_id', auth()->id())
            ->firstOrFail();

        $ticketId = $commentaire->ticket_id;
        $commentaire->delete();

        return redirect()->route('tech.tickets.show', $ticketId)
                         ->with('success', 'Commentaire supprimé.');
    }
}
