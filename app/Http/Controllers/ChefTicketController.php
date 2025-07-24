<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Notification;
use App\Models\Categorie;
use App\Models\Technicien;
use App\Models\Service;   
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\User;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Hash;



class ChefTicketController extends Controller
{
    /**
     * Affiche la liste paginée des tickets (dashboard chef)
     */
    public function dashboard()
    {
         $oneWeekAgo = Carbon::now()->subDays(7);

    $tickets = Ticket::with(['categorie', 'userSimple.user', 'technicien'])
        ->where('created_at', '>=', $oneWeekAgo)
        ->latest()
        ->paginate(4);

    $techniciens = Technicien::with('user')->get();

    return view('chef.dashboard', compact('tickets', 'techniciens'));
    
    }

    /**
     * Affiche le formulaire d'édition d'un ticket
     */
        public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        $categories = Categorie::where('is_official', true)->get();
        $autre = Categorie::where('titre', 'Autre')->first();
        $isCustomCategorie = $ticket->categorie && !$ticket->categorie->is_official;

        return view('chef.ticket-edit', compact('ticket', 'categories', 'autre', 'isCustomCategorie'));
    }

    /**
     * Met à jour un ticket après validation (chef)
     */
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

        // Validation complète adaptée au chef (inclut le status)
        $validatedData = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'categorie_id' => 'required|integer|exists:categories,id',
            'priorite' => 'required|string|in:basse,normale,urgente',
            'status' => 'required|string|in:nouveau,en_cours,résolu,cloturé',
            'piece_jointe' => 'nullable|file|mimes:png,jpg,jpeg,pdf,doc,docx|max:2048',
            'remove_piece_jointe' => 'nullable|boolean',
        ]);

        // Suppression pièce jointe si demandé
        if ($request->input('remove_piece_jointe') == '1' && $ticket->piece_jointe) {
            Storage::disk('public')->delete($ticket->piece_jointe);
            $ticket->piece_jointe = null;
        }

        // Mise à jour pièce jointe si nouveau fichier uploadé
        if ($request->hasFile('piece_jointe')) {
            if ($ticket->piece_jointe) {
                Storage::disk('public')->delete($ticket->piece_jointe);
            }
            $ticket->piece_jointe = $request->file('piece_jointe')->store('incidents/attachments', 'public');
        }

        // Mise à jour des données du ticket
        $ticket->update([
            'titre' => $validatedData['titre'],
            'description' => $validatedData['description'],
            'priorite' => $validatedData['priorite'],
            'status' => $validatedData['status'],
            'categorie_id' => $validatedData['categorie_id'],
            'piece_jointe' => $ticket->piece_jointe,
        ]);

        $ticket->save();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Ticket mis à jour avec succès.']);
        }

        return redirect()->route('chef.dashboard')->with('success', 'Ticket mis à jour avec succès.');
    }

    /**
     * Supprime un ticket
     */
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);

        $ticket->delete();

        return redirect()->route('chef.dashboard')->with('success', 'Ticket supprimé.');
    }

     public function assignTechnicien(Request $request, $id)
    {
        $request->validate([
            'technicien_id' => 'required|exists:users,id',
        ]);

        $ticket = Ticket::find($id);

        if (!$ticket) {
            return redirect()->back()->with('error', 'Ticket non trouvé.');
        }

        $ticket->technicien_id = $request->input('technicien_id');
        if ($ticket->status === 'nouveau') {
        $ticket->status = 'en_cours';
    }
        $ticket->save();

        return redirect()->back()->with('success', 'Technicien assigné avec succès.');
    }

public function allTickets(Request $request)
{
    $search = $request->input('search');

    $query = Ticket::with(['categorie', 'userSimple.user']);

    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('titre', 'like', "%$search%")
              ->orWhereHas('categorie', function($q2) use ($search) {
                  $q2->where('titre', 'like', "%$search%");
              })
              ->orWhere('priorite', 'like', "%$search%")
              ->orWhere('status', 'like', "%$search%")
              ->orWhereHas('userSimple.user', function($q3) use ($search) {
                  $q3->where('nom', 'like', "%$search%")
                     ->orWhere('prenom', 'like', "%$search%");
              });
        });
    }

    $tickets = $query->latest()->paginate(4)->withQueryString();
     $techniciens = \App\Models\Technicien::with('user')->get();


   return view('chef.ticket_all', compact('tickets', 'techniciens'));
   

}
public function export()
{
   // $tickets = Ticket::with(['technicien.user', 'categorie'])->get();
    $tickets = Ticket::with(['technicien', 'categorie'])->get();

    $pdf = Pdf::loadView('chef.tickets_export_pdf', compact('tickets'));
    return $pdf->download('tickets.pdf');
}

public function create()
{ 
    $services = Service::all(); 
    return view('chef.techniciens.create', compact('services'));
}

public function storeTechnicien(Request $request)
{
    $request->validate([
        'nom' => 'required',
        'prenom' => 'required',
        'email' => 'required|email|unique:users,email',
        'service_id' => 'required|exists:services,id',
    ]);

    $token = Str::random(60);

    $user = User::create([
        'nom' => $request->nom,
        'prenom' => $request->prenom,
        'email' => $request->email,
        'role' => 'technicien',
        'password' => Hash::make(Str::random(10)), // mot de passe temporaire obligatoire
        'password_token' => $token,
    ]);
    $user->technicien()->create([
        'service_id' => $request->service_id,
    ]);

   $this->sendPasswordEmail($request->email, $token, $request->prenom, $request->nom);


    return back()->with('success', 'Technicien ajouté. Email envoyé à : ' . $request->email);
}

private function sendPasswordEmail($email, $token, $prenom, $nom)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'douniamoujdidi541@gmail.com';
        $mail->Password = 'xwcyhjqzvmxhwmgf';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $nomComplet = $prenom . ' ' . $nom;
        $mail->setFrom('douniamoujdidi541@gmail.com', 'Ministère de la Transition Numérique et de la Réforme de l’Administration');
        $mail->addAddress($email);

        $link = route('technicien.password.form', ['token' => urlencode($token)]);


        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Définition de votre mot de passe - Système de gestion des incidents';
        $mail->Body = "
    <p>Bonjour <strong>$nomComplet</strong>,</p>
    <p>Vous avez été ajouté en tant que technicien au sein du <strong>Système de gestion des incidents</strong> du Ministère de la Transition Numérique et de la Réforme de l’Administration.</p>
    <p>Pour activer votre compte et définir votre mot de passe, veuillez cliquer sur le lien sécurisé ci-dessous :</p>
    <p><a href=\"$link\">Définir mon mot de passe</a></p>
    <p>Si vous ne parvenez pas à cliquer sur le lien, copiez et collez l’adresse suivante dans votre navigateur :</p>
    <p><small>$link</small></p>
    <p>Nous vous recommandons de ne pas partager ce lien avec quiconque afin de préserver la sécurité de votre compte.</p>
    <br>
    <p>Cordialement,</p>
    <p><strong>Service Support</strong><br>
    Ministère de la Transition Numérique et de la Réforme de l’Administration</p>
";

        $mail->send();
    } catch (Exception $e) {
        \Log::error("Erreur email : " . $mail->ErrorInfo);
    }
}
public function assignTicket(Request $request, $id)
{
    $ticket = Ticket::findOrFail($id);
    $technicien = Technicien::findOrFail($request->technicien_id);

    $ticket->technicien_id = $technicien->id;
    $ticket->status = 'nouveau';

    $ticket->save();

    

    $this->sendAssignEmail($technicien, $ticket);

    return back()->with('success', 'Ticket assigné et email envoyé');
}

private function sendAssignEmail($technicien, $ticket)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'douniamoujdidi541@gmail.com';
        $mail->Password = 'lhfjvtbkxkuyllbz';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('douniamoujdidi541@gmail.com', 'Gestion Tickets');
         $mail->addAddress($technicien->user->email, $technicien->user->prenom . ' ' . $technicien->user->nom);


        $mail->isHTML(true);
        $mail->Subject = 'Nouveau ticket assigné';
       $mail->Body = "Bonjour {$technicien->user->prenom} {$technicien->user->nom},<br><br>"
                    . "Un nouveau ticket vous a été assigné : <strong>{$ticket->titre}</strong>.<br>"
                    . "Merci de le traiter rapidement.<br><br>"
                    . "<a href='" . route('tickets.show', $ticket->id) . "'>Voir le ticket</a><br><br>"
                    . "Cordialement,<br>L'équipe";

        $mail->send();
    } catch (Exception $e) {
        \Log::error('Erreur envoi mail assignation: '.$mail->ErrorInfo);
    }
}
public function show($id)
    {
        // Trouver le ticket ou 404 si non trouvé
        $ticket = Ticket::findOrFail($id);

        // Retourner la vue avec le ticket
        return view('user.ticket-show', compact('ticket'));
    }

}


