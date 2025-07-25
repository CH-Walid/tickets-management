<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TicketsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Ticket::with(['userSimple.user', 'technicien.user', 'service', 'categorie'])
            ->get()
            ->map(function($t) {
                return [
                    'ID' => $t->id,
                    'Titre' => $t->titre,
                    'Statut' => $t->status,
                    'Utilisateur' => $t->userSimple->user->nom ?? '',
                    'Technicien' => $t->technicien->user->nom ?? '',
                    'Service' => $t->service->titre ?? '',
                    'Catégorie' => $t->categorie->titre ?? '',
                    'Créé le' => $t->created_at ? $t->created_at->format('d/m/Y') : '',
                ];
            });
    }
    public function headings(): array
    {
        return ['ID', 'Titre', 'Statut', 'Utilisateur', 'Technicien', 'Service', 'Catégorie', 'Créé le'];
    }
} 