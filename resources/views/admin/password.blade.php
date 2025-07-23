@extends('admin.layout')
@section('title', 'Changer le mot de passe')
@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Changer le mot de passe</h2>
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
            @foreach($errors->all() as $error)
                @if(str_contains($error, 'current password'))
                    <div>Le mot de passe actuel est incorrect.</div>
                @elseif(str_contains($error, 'password confirmation'))
                    <div>La confirmation du nouveau mot de passe ne correspond pas.</div>
                @elseif(str_contains($error, 'at least 8 characters'))
                    <div>Le nouveau mot de passe doit contenir au moins 8 caractères.</div>
                @elseif(str_contains($error, 'required'))
                    <div>Veuillez remplir tous les champs obligatoires.</div>
                @else
                    <div>{{ $error }}</div>
                @endif
            @endforeach
        </div>
    @endif
    <form action="{{ route('admin.password') }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <label class="block mb-1 font-medium">Mot de passe actuel</label>
            <input type="password" name="current_password" class="border rounded px-3 py-2 w-full" required>
        </div>
        <div>
            <label class="block mb-1 font-medium">Nouveau mot de passe</label>
            <input type="password" name="password" class="border rounded px-3 py-2 w-full" required>
        </div>
        <div>
            <label class="block mb-1 font-medium">Confirmer le nouveau mot de passe</label>
            <input type="password" name="password_confirmation" class="border rounded px-3 py-2 w-full" required>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-700 text-white rounded hover:bg-blue-800">Enregistrer</button>
            <a href="{{ route('admin.profil') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Annuler</a>
        </div>
    </form>
</div>
@endsection
