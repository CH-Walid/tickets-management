@extends('layouts.app')

@section('title', 'Dashboard - Système de Gestion des Incidents')

@section('content')

<div class="max-w-3xl mx-auto p-6 mt-10 bg-white shadow rounded">

    {{-- ✅ Message flash --}}
    @if(session('success'))
        <div class="mb-4 p-4 rounded bg-green-100 text-green-800 border border-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 rounded bg-red-100 text-red-800 border border-red-300">
            {{ session('error') }}
        </div>
    @endif

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Détails du Ticket</h1>

    {{-- Informations du ticket --}}
    <div class="space-y-4">
        <div>
            <h2 class="text-gray-600 text-sm">Titre</h2>
            <p class="text-lg font-semibold">{{ $ticket->titre }}</p>
        </div>

        <div>
            <h2 class="text-gray-600 text-sm">Catégorie</h2>
            <p>{{ $ticket->categorie->titre }}</p>
        </div>

        <div>
            <h2 class="text-gray-600 text-sm">Description</h2>
            <p class="text-gray-700">{{ $ticket->description }}</p>
        </div>

        <div>
            <h2 class="text-gray-600 text-sm mb-1">Statut</h2>
            @php
                $statusColors = [
                    'nouveau' => ['bg' => 'bg-green-500', 'text' => 'text-green-600', 'label' => 'Nouveau'],
                    'en_cours' => ['bg' => 'bg-orange-500', 'text' => 'text-orange-600', 'label' => 'En cours'],
                    'résolu' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-600', 'label' => 'Résolu'],
                    'cloturé' => ['bg' => 'bg-gray-500', 'text' => 'text-gray-600', 'label' => 'Clôturé'],
                ];
                $color = $statusColors[$ticket->status] ?? ['bg' => 'bg-gray-300', 'text' => 'text-gray-500', 'label' => ucfirst($ticket->status)];
            @endphp

            <div class="flex items-center space-x-2 text-sm font-semibold">
                <span class="inline-block w-3 h-3 rounded-full {{ $color['bg'] }}"></span>
                <span class="{{ $color['text'] }}">{{ $color['label'] }}</span>
            </div>
        </div>

        <div>
            <h2 class="text-gray-600 text-sm">Créé le</h2>
            <p>{{ optional($ticket->created_at)->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    {{-- Boutons d'action --}}
    <div class="mt-6 flex gap-4">
        <a href="{{ route('tickets.edit', $ticket->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            Modifier
        </a>
        <a href="{{ route('tickets.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
            Retour
        </a>
    </div>

    {{-- 🔽 Section Commentaires --}}
    <div class="mt-10 border-t pt-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Commentaires</h2>

        {{-- Liste des commentaires --}}
        <div class="space-y-3">
            @forelse ($ticket->commentaires as $commentaire)
                <div class="bg-gray-100 rounded p-4 shadow-sm">
                    <p class="text-sm text-gray-800">{{ $commentaire->content }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        Par {{ $commentaire->technicien->nom ?? 'Technicien inconnu' }}
                        — {{ $commentaire->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            @empty
                <p class="text-gray-500 text-sm">Aucun commentaire pour ce ticket.</p>
            @endforelse
        </div>

        {{-- Formulaire d'ajout de commentaire (une seule fois) --}}
        @if (! $ticket->commentaires->where('technicien_id', auth()->id())->count())
            <div class="mt-6">
                <form action="{{ route('tickets.commenter', $ticket->id) }}" method="POST">
                    @csrf
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Ajouter un commentaire</label>
                    <textarea name="content" id="content" rows="3"
                              class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring focus:ring-indigo-300"
                              required></textarea>
                    <button type="submit"
                            class="mt-2 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Commenter
                    </button>
                </form>
            </div>
        @else
            <p class="mt-4 text-sm text-gray-500 italic">Vous avez déjà commenté ce ticket.</p>
        @endif
    </div>
</div>

@endsection
