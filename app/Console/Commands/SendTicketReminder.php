<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use App\Models\Technicien;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Carbon\Carbon;

class SendTicketReminder extends Command
{
    protected $signature = 'tickets:send-reminder';
    protected $description = 'Envoi rappel email aux techniciens pour tickets non traités';

    public function handle()
    {
        $now = Carbon::now();

        // Trouver tickets non traités depuis plus de 24h ou 48h
        $tickets = Ticket::whereIn('status', ['nouveau', 'en_cours'])
            ->whereNotNull('technicien_id')
            ->where(function ($query) use ($now) {
                $query->where('updated_at', '<=', $now->subHours(24))
                      ->orWhere('updated_at', '<=', $now->subHours(48));
            })
            ->get()
            ->groupBy('technicien_id');

        foreach ($tickets as $technicienId => $ticketsGroup) {
            $technicien = Technicien::find($technicienId);
            if (!$technicien) continue;

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.example.com';
                $mail->SMTPAuth = true;
               $mail->Username = 'douniamoujdidi541@gmail.com';
               $mail->Password = 'lhfjvtbkxkuyllbz';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('douniamoujdidi541@gmail.com', 'Gestion Tickets');
                 $mail->addAddress($technicien->user->email, $technicien->user->prenom . ' ' . $technicien->user->nom);

                $mail->isHTML(true);
                $mail->Subject = 'Rappel : Tickets non traités';

                $mail->Body = "Bonjour {$technicien->user->prenom} {$technicien->user->nom},<br><br>"
                      . "Vous avez les tickets suivants non traités depuis plus de 24h :<br><ul>";

                foreach ($ticketsGroup as $ticket) {
                    $body .= "<li><a href='" . route('tickets.show', $ticket->id) . "'>{$ticket->titre}</a> - mis à jour le {$ticket->updated_at->format('d/m/Y H:i')}</li>";
                }

                $body .= "</ul><br>Merci de les traiter rapidement.<br><br>Cordialement,<br>L'équipe";

                $mail->Body = $body;

                $mail->send();
            } catch (Exception $e) {
                \Log::error('Erreur envoi mail rappel: '.$mail->ErrorInfo);
            }
        }

        $this->info('Rappels envoyés avec succès');
    }
}