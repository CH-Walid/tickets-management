@extends('layouts.app')

@section('title', 'Dashboard - Système de Gestion des Incidents')

@section('content')

<div class="mt-24 max-w-3xl mx-auto p-6 bg-white shadow rounded">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Modifier le Ticket</h1>

    {{-- ✅ Message de succès ou erreur --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded border border-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded border border-red-300">
            {{ session('error') }}
        </div>
    @endif

    {{-- ✅ Formulaire de mise à jour du statut --}}
    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Titre --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Titre</label>
            <p class="mt-1 text-gray-800 font-semibold">{{ $ticket->titre }}</p>
        </div>

        {{-- Catégorie --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Catégorie</label>
            <p class="mt-1 text-gray-800">{{ $ticket->categorie->titre }}</p>
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <p class="mt-1 text-gray-700">{{ $ticket->description }}</p>
        </div>

        {{-- Statut --}}
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
            <select name="status" id="status"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="nouveau" {{ $ticket->status == 'nouveau' ? 'selected' : '' }}>Nouveau</option>
                <option value="en_cours" {{ $ticket->status == 'en_cours' ? 'selected' : '' }}>En cours</option>
                <option value="résolu" {{ $ticket->status == 'résolu' ? 'selected' : '' }}>Résolu</option>
                <option value="cloturé" {{ $ticket->status == 'cloturé' ? 'selected' : '' }}>Clôturé</option>
            </select>
        </div>

        {{-- Boutons --}}
        <div class="flex justify-end gap-4">
            <a href="{{ route('tickets.show', $ticket->id) }}"
               class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Annuler</a>
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Enregistrer</button>
        </div>
    </form>

    {{-- 🔁 Formulaire de modification du commentaire --}}
    @php
        $commentaire = $ticket->commentaires->where('technicien_id', auth()->id())->first();
    @endphp

    @if ($commentaire)
        <div class="mt-12 border-t pt-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Modifier votre commentaire</h2>

            {{-- Formulaire de modification --}}
            <form action="{{ route('commentaires.update', $commentaire->id) }}" method="POST">
                @csrf
                @method('PUT')

                <textarea name="content" rows="3" required
                          class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring focus:ring-indigo-300">{{ old('content', $commentaire->contenu) }}</textarea>

                <div class="mt-4 flex justify-end gap-4">
                    <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Mettre à jour</button>
                </div>
            </form>

            {{-- Formulaire de suppression --}}
            <form action="{{ route('commentaires.destroy', $commentaire->id) }}" method="POST"
                  class="mt-2 flex justify-end"
                  onsubmit="return confirm('Voulez-vous vraiment supprimer ce commentaire ?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Supprimer</button>
            </form>
        </div>
    @endif
</div>

@extends('layouts.app')