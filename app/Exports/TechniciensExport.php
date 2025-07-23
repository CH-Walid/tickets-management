<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Technicien;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TechniciensExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // On récupère les techniciens avec leur user et leur service
        $techniciens = Technicien::with(['user', 'service'])->get();
        return $techniciens->map(function ($tech) {
            return [
                
                'Nom' => $tech->user->nom ?? '',
                'Prénom' => $tech->user->prenom ?? '',
                'Email' => $tech->user->email ?? '',
                
                'Service' => $tech->service->titre ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nom',
            'Prénom',
            'Email',
            'Service',
        ];
    }
}
