<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Categorie;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class IncidentController extends Controller
{
   public function dashboard(Request $request)
{
    $userId = auth()->id();
    $threeDaysAgo = Carbon::now()->subDays(3);
    $search = $request->input('search');

    $query = Ticket::with('categorie')
        ->where('user_simple_id', $userId)
        ->where('created_at', '>=', $threeDaysAgo)
        ->orderByDesc('created_at'); 

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('titre', 'like', "%{$search}%")
              ->orWhereHas('categorie', function ($q2) use ($search) {
                  $q2->where('titre', 'like', "%{$search}%");
              })
              ->orWhere('priorite', 'like', "%{$search}%")
              ->orWhere('status', 'like', "%{$search}%");
        });
    }

    $tickets = $query->paginate(5)->withQueryString();

    return view('user.dashboard', compact('tickets'));
}

    public function create()
    {
        $categories = Categorie::where('is_official', true)->get();
        $autre = Categorie::where('titre', 'Autre')->first();  // Récupérer la catégorie "Autre"
        return view('user.ticket', compact('categories', 'autre'));
    }

    public function store(Request $request)
{
    // Récupérer la catégorie 'Autre' en base (au lieu de hardcoder 7)
    $autreCategorie = Categorie::where('titre', 'Autre')->first();

    if ($autreCategorie && $request->input('categorie_id') == $autreCategorie->id) {
        $request->validate([
            'nouvelle_categorie' => 'required|string|max:255'
        ]);

        $titre = trim($request->input('nouvelle_categorie'));

        // Vérifier si la catégorie existe déjà
        $existante = Categorie::where('titre', $titre)->first();

        if ($existante) {
            $request->merge(['categorie_id' => $existante->id]);
        } else {
            $nouvelleCategorie = Categorie::create([
                'titre' => $titre,
                'is_official' => false,
            ]);
            $request->merge(['categorie_id' => $nouvelleCategorie->id]);
        }
    }

       
        // Validation
        $validatedData = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'categorie_id' => 'required|integer|exists:categories,id',
            'priorite' => 'required|string|in:basse,normale,urgente',
            'piece_jointe' => 'nullable|file|mimes:png,jpg,jpeg,pdf,doc,docx|max:2048',
        ]);

        // Gestion pièce jointe
        $attachmentPath = null;
        if ($request->hasFile('piece_jointe')) {
            $attachmentPath = $request->file('piece_jointe')->store('incidents/attachments', 'public');
        }

        // Création du ticket
        $ticket = Ticket::create([
            'titre' => $validatedData['titre'],
            'description' => $validatedData['description'],
            'piece_jointe' => $attachmentPath,
            'status' => 'nouveau',
            'priorite' => $validatedData['priorite'],
            'categorie_id' => $validatedData['categorie_id'] ?? null,
            'user_simple_id' => auth()->id(),
            'technicien_id' => null,
            'in_progress_at' => null,
            'resolved_at' => null,
            'closed_at' => null,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Incident déclaré avec succès !']);
        }

        return redirect()->route('user.ticket')->with('success', 'Incident déclaré avec succès !');
    }

    public function edit($id)
    {
         $ticket = Ticket::findOrFail($id);
        $categories = Categorie::where('is_official', true)->get();
        $autre = Categorie::where('titre', 'Autre')->first();
        $isCustomCategorie = $ticket->categorie && !$ticket->categorie->is_official;

    return view('user.ticket-edit', compact('ticket', 'categories', 'autre', 'isCustomCategorie'));
    }

    public function update(Request $request, $id)
{
    $ticket = Ticket::findOrFail($id);

    $autreCategorie = Categorie::where('titre', 'Autre')->first();

    if ($autreCategorie && $request->input('categorie_id') == $autreCategorie->id) {
        $request->validate([
            'nouvelle_categorie' => 'required|string|max:255',
        ]);

                $titre = trim($request->input('nouvelle_categorie'));

        $existante = Categorie::where('titre', $titre)->first();

        if ($existante) {
            $request->merge(['categorie_id' => $existante->id]);
        } else {
            $nouvelleCategorie = Categorie::create([
                'titre' => $titre,
                'is_official' => false,
            ]);
            $request->merge(['categorie_id' => $nouvelleCategorie->id]);
        }


        
    }

        // Validation
        $validatedData = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'categorie_id' => 'required|integer|exists:categories,id',
            'priorite' => 'required|string|in:basse,normale,urgente',
            'piece_jointe' => 'nullable|file|mimes:png,jpg,jpeg,pdf,doc,docx|max:2048',
            'remove_piece_jointe' => 'nullable|boolean',
        ]);

        // Supprimer l’ancienne pièce jointe si demandé
    if ($request->input('remove_piece_jointe') == '1' && $ticket->piece_jointe) {
        Storage::disk('public')->delete($ticket->piece_jointe);
        $ticket->piece_jointe = null;
    }

    // Si nouveau fichier uploadé, supprimer l'ancien puis stocker le nouveau
    if ($request->hasFile('piece_jointe')) {
        if ($ticket->piece_jointe) {
            Storage::disk('public')->delete($ticket->piece_jointe);
        }
        $ticket->piece_jointe = $request->file('piece_jointe')->store('incidents/attachments', 'public');
    }

        // Mise à jour ticket
        $ticket->update([
            'titre' => $validatedData['titre'],
            'description' => $validatedData['description'],
            'priorite' => $validatedData['priorite'],
            'categorie_id' => $validatedData['categorie_id'] ?? null,
        ]);
        
          $ticket->save();
        // ✅ Si AJAX, retourne une réponse JSON
    if ($request->ajax()) {
        return response()->json(['success' => true, 'message' => 'Ticket mis à jour avec succès.']);
    }

        return redirect()->route('user.dashboard')->with('success', 'Ticket mis à jour avec succès');
    }
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($ticket->user_simple_id !== auth()->id()) {
            abort(403, "Accès refusé");
        }

        $ticket->delete(); // Soft delete

        return redirect()->route('user.dashboard')->with('success', 'Ticket supprimé avec succès.');
    }

    public function allTickets(Request $request)
{
    $userId = auth()->id();
    $search = $request->input('search');

    $query = Ticket::with('categorie', 'userSimple')
        ->where('user_simple_id', $userId) // 👈 uniquement MES tickets
        ->orderByDesc('created_at');

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('titre', 'like', "%{$search}%")
              ->orWhereHas('categorie', fn($q2) => $q2->where('titre', 'like', "%{$search}%"))
              ->orWhere('priorite', 'like', "%{$search}%")
              ->orWhere('status', 'like', "%{$search}%");
        });
    }

   
    $tickets = $query->paginate(5);

    return view('user.tickets-all', compact('tickets'));
}
}