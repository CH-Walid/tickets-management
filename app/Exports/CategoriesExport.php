<?php

namespace App\Exports;

use App\Models\Categorie;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoriesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Categorie::select('titre')->orderBy('titre')->get();
    }
    public function headings(): array
    {
        return ['Titre'];
    }
} 