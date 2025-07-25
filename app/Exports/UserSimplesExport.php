<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserSimplesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::where('role', 'user_simple')
            ->with(['userSimple.service'])
            ->get()
            ->map(function($u) {
                return [
                    'ID' => $u->id,
                    'Nom' => $u->nom,
                    'Prénom' => $u->prenom,
                    'Email' => $u->email,
                    'Téléphone' => $u->phone,
                    'Service' => $u->userSimple->service->titre ?? '',
                ];
            });
    }
    public function headings(): array
    {
        return ['ID', 'Nom', 'Prénom', 'Email', 'Téléphone', 'Service'];
    }
} 