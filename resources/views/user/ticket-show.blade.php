@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
    <h1 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">
        Détail du ticket #{{ $ticket->id }}
    </h1>

    <div class="mb-4">
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300">Titre</h2>
        <p class="text-gray-900 dark:text-gray-100">{{ $ticket->titre }}</p>
    </div>

    <div class="mb-4">
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300">Description</h2>
        <p class="text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $ticket->description }}</p>
    </div>

    <div class="mb-4">
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300">Catégorie</h2>
        <p class="text-gray-900 dark:text-gray-100">
            {{ $ticket->categorie ? $ticket->categorie->titre : 'Non spécifiée' }}
        </p>
    </div>

    <div class="mb-4">
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300">Priorité</h2>
        <p class="text-gray-900 dark:text-gray-100">
            {{ ucfirst($ticket->priorite) ?? 'Non spécifiée' }}
        </p>
    </div>
</div>
@endsection
