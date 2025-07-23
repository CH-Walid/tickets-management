<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use App\Exports\TechniciensExport;
use Maatwebsite\Excel\Facades\Excel;

class TechnicienController extends Controller
{
    public function index()
    {
        // On récupère tous les techniciens avec leur user et service
        $techniciens = \App\Models\Technicien::with(['user', 'service'])->get();
        return view('admin.techniciens.index', compact('techniciens'));
    }

    public function create()
    {
        $services = \App\Models\Service::all();
        return view('admin.techniciens.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:20',
            'prenom' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:8|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'service_id' => 'required|exists:services,id',
        ]);
        $user = new User();
        $user->nom = $validated['nom'];
        $user->prenom = $validated['prenom'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->role = 'technicien';
        $user->password = \Hash::make($validated['password']);
        if ($request->hasFile('photo')) {
            if ($user->photo && \Storage::disk('public')->exists($user->photo)) {
                \Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('avatars', 'public');
        } elseif ($request->has('delete_photo') && $request->input('delete_photo')) {
            if ($user->photo && \Storage::disk('public')->exists($user->photo)) {
                \Storage::disk('public')->delete($user->photo);
            }
            $user->photo = null;
        }
        $user->save();

        return redirect()->route('admin.techniciens.techniciens.index')->with('success', 'Technicien ajouté avec succès.');
    }

    public function show($id)
    {
        $technicien = \App\Models\Technicien::with(['user', 'service'])->findOrFail($id);
        return view('admin.techniciens.show', compact('technicien'));
    }

    public function edit($id)
    {
        $technicien = \App\Models\Technicien::with(['user', 'service'])->findOrFail($id);
        $services = \App\Models\Service::all();
        return view('admin.techniciens.edit', compact('technicien', 'services'));
    }

    public function update(Request $request, $id)
    {
     
        $user = User::where('role', 'technicien')->findOrFail($id);
        $technicien = $user->technicien;
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:30',
            'password' => 'nullable|string|min:8|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'service_id' => 'required|exists:services,id',
        ]);
        $user->nom = $validated['nom'];
        $user->prenom = $validated['prenom'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        if ($validated['password'] ?? false) {
            $user->password = \Hash::make($validated['password']);
        }
        // Gestion de la photo
        if ($request->hasFile('photo')) {
            if ($user->photo && \Storage::disk('public')->exists($user->photo)) {
                \Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('avatars', 'public');
        }
        if ($request->has('delete_photo') && $request->input('delete_photo')) {
            if ($user->photo && \Storage::disk('public')->exists($user->photo)) {
                \Storage::disk('public')->delete($user->photo);
            }
            $user->photo = null;
        }
        $user->save();
    
        if ($technicien) {
            $technicien->service_id = $validated['service_id'];
            $technicien->save();
        } else {
            $user->technicien()->create(['id' => $user->id, 'service_id' => $validated['service_id']]);
        }
        // Ajout d'un cache buster pour la photo
        session(['photo_buster' => time()]);
        return redirect()->route('admin.techniciens.techniciens.index')->with('success', 'Technicien modifié avec succès.');
    }

    public function destroy($id)
    {
        $technicien = User::where('role', 'technicien')->findOrFail($id);
        $technicien->delete();
        return redirect()->route('admin.techniciens.techniciens.index')->with('success', 'Technicien supprimé.');
    }

    public function export()
    {
        return Excel::download(new TechniciensExport, 'techniciens.xlsx');
    }
}
