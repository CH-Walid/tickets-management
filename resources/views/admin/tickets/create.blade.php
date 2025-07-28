@extends('admin.layout')

@section('content')
<div class="max-w-2xl mx-auto mt-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.tickets.index') }}" class="text-gray-500 hover:text-blue-600 flex items-center mr-4">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
            Retour
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Créer un ticket</h1>
    </div>
    <form action="{{ route('admin.tickets.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf
        <div>
            <label class="block text-gray-700 font-medium mb-1">Titre</label>
            <input type="text" name="titre" value="{{ old('titre') }}" class="w-full border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200" required>
        </div>
        <div>
            <label class="block text-gray-700 font-medium mb-1">Description</label>
            <textarea name="description" rows="4" class="w-full border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200" required>{{ old('description') }}</textarea>
        </div>
        <div>
            <label class="block text-gray-700 font-medium mb-1">Statut</label>
            <select name="status" class="w-full border-gray-300 rounded px-3 py-2" required>
                <option value="nouveau" @if(old('status')==='nouveau') selected @endif>Nouveau</option>
                <option value="en_cours" @if(old('status')==='en_cours') selected @endif>En cours</option>
                <option value="résolu" @if(old('status')==='résolu') selected @endif>Résolu</option>
                <option value="cloturé" @if(old('status')==='cloturé') selected @endif>Cloturé</option>
            </select>
        </div>
        <div>
            <label class="block text-gray-700 font-medium mb-1">Utilisateur</label>
            <select name="user_simple_id" class="w-full border-gray-300 rounded px-3 py-2" required>
                @foreach($utilisateurs as $user)
                    <option value="{{ $user->id }}" @if(old('user_simple_id')==$user->id) selected @endif>{{ $user->prenom }} {{ $user->nom }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-gray-700 font-medium mb-1">Technicien assigné</label>
            <select name="technicien_id" class="w-full border-gray-300 rounded px-3 py-2">
                <option value="">Aucun</option>
                @foreach($techniciens as $tech)
                    <option value="{{ $tech->id }}" @if(old('technicien_id')==$tech->id) selected @endif>{{ $tech->prenom }} {{ $tech->nom }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-gray-700 font-medium mb-1">Catégorie</label>
            <select name="categorie_id" class="w-full border-gray-300 rounded px-3 py-2">
                <option value="">Aucune</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @if(old('categorie_id')==$cat->id) selected @endif>{{ $cat->titre }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex justify-end space-x-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Créer</button>
        </div>
    </form>
</div>
@endsection 