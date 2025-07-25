<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Exports\ServicesExport;
use Maatwebsite\Excel\Facades\Excel;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('titre')->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
        ]);
        Service::create($validated);
        return redirect()->route('admin.services.index')->with('success', 'Service ajouté avec succès.');
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
        ]);
        $service->update($validated);
        return redirect()->route('admin.services.index')->with('success', 'Service modifié avec succès.');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service supprimé.');
    }

    public function export()
    {
        return Excel::download(new ServicesExport, 'services.xlsx');
    }
} 