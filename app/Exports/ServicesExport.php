<?php

namespace App\Exports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ServicesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Service::select('titre')->orderBy('titre')->get();
    }
    public function headings(): array
    {
        return ['Titre'];
    }
} 