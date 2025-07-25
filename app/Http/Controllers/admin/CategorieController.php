<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categorie;
use App\Exports\CategoriesExport;
use Maatwebsite\Excel\Facades\Excel;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = Categorie::orderBy('titre')->get();
        return view('admin.parametrage.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.parametrage.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
        ]);
        Categorie::create($validated);
        return redirect()->route('admin.parametrage')->with('success', 'Catégorie ajoutée avec succès.');
    }

    public function edit($id)
    {
        $categorie = Categorie::findOrFail($id);
        return view('admin.parametrage.categories.edit', compact('categorie'));
    }

    public function update(Request $request, $id)
    {
        $categorie = Categorie::findOrFail($id);
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
        ]);
        $categorie->update($validated);
        return redirect()->route('admin.parametrage')->with('success', 'Catégorie modifiée avec succès.');
    }

    public function destroy($id)
    {
        $categorie = Categorie::findOrFail($id);
        $categorie->delete();
        return redirect()->route('admin.parametrage')->with('success', 'Catégorie supprimée.');
    }

    public function export()
    {
        return Excel::download(new CategoriesExport, 'categories.xlsx');
    }
} 