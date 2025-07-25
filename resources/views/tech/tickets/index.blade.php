@extends('layouts.app')

@section('title', 'Dashboard - Système de Gestion des Incidents')

@section('content')

    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Tickets assignés</h1>

        <div class="bg-white shadow-md rounded overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Titre</th>
                        <th class="px-6 py-3">Catégorie</th>
                        <th class="px-6 py-3">Statut</th>
                        <th class="px-6 py-3">Créé le</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($tickets as $ticket)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">{{ $ticket->id }}</td>
                            <td class="px-6 py-4">{{ $ticket->titre }}</td>
                            <td class="px-6 py-4">{{ $ticket->categorie->titre }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'nouveau' => ['bg' => 'bg-green-500', 'text' => 'text-green-600'],
                                        'en_cours' => ['bg' => 'bg-orange-500', 'text' => 'text-orange-600'],
                                        'résolu' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-600'],
                                        'cloturé' => ['bg' => 'bg-gray-500', 'text' => 'text-gray-600'],
                                    ];
                                    $statusLabel = ucfirst($ticket->status);
                                    $color = $statusColors[$ticket->status] ?? ['bg' => 'bg-gray-300', 'text' => 'text-gray-500'];
                                @endphp

                                <div class="flex items-center space-x-2 text-sm font-semibold">
                                    <span class="inline-block w-3 h-3 rounded-full {{ $color['bg'] }}"></span>
                                    <span class="{{ $color['text'] }}">{{ $statusLabel }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                {{ $ticket->created_at ? $ticket->created_at->format('d/m/Y') : 'Non défini' }}
                            </td>

                            <td class="px-6 py-4 flex gap-2">
                                <a href="{{ route('tech.tickets.show', $ticket->id) }}" class="text-blue-600 hover:underline">Voir</a>
                                <a href="{{ route('tickets.edit', $ticket->id) }}" class="text-indigo-600 hover:underline">Modifier</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center px-6 py-4 text-gray-500">Aucun ticket trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
