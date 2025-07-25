<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserSimple;
use App\Models\Service;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserSimplesExport;

class UserSimpleController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user_simple')->with(['userSimple.service']);
        if ($q = $request->input('q')) {
            $query->where(function($sub) use ($q) {
                $sub->where('nom', 'like', "%$q%")
                    ->orWhere('prenom', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhereHas('userSimple.service', function($s) use ($q) {
                        $s->where('titre', 'like', "%$q%") ;
                    });
            });
        }
        $utilisateurs = $query->get();
        return view('admin.utilisateurs.index', compact('utilisateurs'));
    }

    public function show($id)
    {
        $utilisateur = User::where('role', 'user_simple')->with(['userSimple.service'])->findOrFail($id);
        return view('admin.utilisateurs.show', compact('utilisateur'));
    }

    public function create()
    {
        $services = Service::all();
        return view('admin.utilisateurs.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:30',
            'service_id' => 'required|exists:services,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $user = new User();
        $user->nom = $validated['nom'];
        $user->prenom = $validated['prenom'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->role = 'user_simple';
        $user->password = \Hash::make($validated['password']);
        if ($request->hasFile('photo')) {
            $user->photo = $request->file('photo')->store('avatars', 'public');
        }
        $user->save();
        $user->userSimple()->create(['service_id' => $validated['service_id']]);
        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur ajouté avec succès.');
    }

    public function edit($id)
    {
        $utilisateur = User::where('role', 'user_simple')->with(['userSimple.service'])->findOrFail($id);
        $services = Service::all();
        return view('admin.utilisateurs.edit', compact('utilisateur', 'services'));
    }

    public function update(Request $request, $id)
    {
        $user = User::where('role', 'user_simple')->findOrFail($id);
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:30',
            'password' => 'nullable|string|min:8|confirmed',
            'service_id' => 'required|exists:services,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $user->nom = $validated['nom'];
        $user->prenom = $validated['prenom'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        if ($validated['password'] ?? false) {
            $user->password = \Hash::make($validated['password']);
        }
        if ($request->has('delete_photo') && $request->input('delete_photo')) {
            if ($user->photo && $user->photo !== 'avatars/default.png') {
                \Storage::disk('public')->delete($user->photo);
            }
            $user->photo = null;
        } elseif ($request->hasFile('photo')) {
            if ($user->photo && $user->photo !== 'avatars/default.png') {
                \Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('avatars', 'public');
        }
        $user->save();
        $user->userSimple->service_id = $validated['service_id'];
        $user->userSimple->save();
        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur modifié avec succès.');
    }

    public function destroy($id)
    {
        $user = User::where('role', 'user_simple')->findOrFail($id);
        $user->delete();
        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur supprimé.');
    }

    public function export()
    {
        return Excel::download(new UserSimplesExport, 'utilisateurs.xlsx');
    }
} 