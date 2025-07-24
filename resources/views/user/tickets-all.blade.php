@extends('layouts.app')

@section('title', 'Mes Tickets')

@section('content')
<div class="p-6 dark:bg-gray-900 min-h-screen">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-gray-700 dark:text-white" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 12V6.75A2.25 2.25 0 014.5 4.5h4.379a2.25 2.25 0 011.59.659l1.122 1.122a2.25 2.25 0 001.59.659H19.5a2.25 2.25 0 012.25 2.25V12m-19.5 0v5.25A2.25 2.25 0 004.5 19.5h15a2.25 2.25 0 002.25-2.25V12m-19.5 0h19.5" />
                </svg>
                Mes Tickets
            </h1>

            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Voici tous les tickets que vous avez créés.</p>

            <!-- Form de recherche -->
            <form method="GET" action="{{ route('tickets.all') }}" class="flex items-center gap-4 w-full">
                <div class="relative flex-grow max-w-md">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Rechercher par titre, catégorie, priorité, statut..."
                        class="pl-10 w-full bg-gray-50 dark:bg-gray-700 dark:text-white border border-gray-200 dark:border-gray-600 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-300 h-4 w-4"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </div>

                <button type="submit"
                    class="dark:bg-red-500 bg-blue-500 hover:bg-blue-600 dark:hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center gap-2 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <polygon points="22,3 2,3 10,12.46 10,19 14,21 14,12.46"></polygon>
                    </svg>
                    Filtrer
                </button>
            </form>
        </div>

        <!-- Tableau des tickets -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700">
                        <th class="py-4 px-6 text-left text-gray-700 dark:text-gray-200 font-semibold">TITRE</th>
                        <th class="py-4 px-6 text-left text-gray-700 dark:text-gray-200 font-semibold">CATÉGORIE</th>
                        <th class="py-4 px-6 text-center text-gray-700 dark:text-gray-200 font-semibold">PRIORITÉ</th>
                        <th class="py-4 px-6 text-left text-gray-700 dark:text-gray-200 font-semibold">STATUT</th>
                        <th class="py-4 px-6 text-left text-gray-700 dark:text-gray-200 font-semibold">CRÉÉ LE</th>
                        <th class="py-4 px-6 text-left text-gray-700 dark:text-gray-200 font-semibold">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        @php
                            $prioriteColors = [
                                'basse' => 'bg-green-100 text-green-800 border-green-200 dark:bg-green-200 dark:text-green-900',
                                'normale' => 'bg-yellow-100 text-yellow-800 border-yellow-200 dark:bg-yellow-200 dark:text-yellow-900',
                                'urgente' => 'bg-red-100 text-red-800 border-red-200 dark:bg-red-300 dark:text-red-900',
                            ];
                            $statusColors = [
                                'nouveau' => 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-300 dark:text-blue-900',
                                'en cours' => 'bg-yellow-100 text-yellow-800 border-yellow-200 dark:bg-yellow-300 dark:text-yellow-900',
                                'résolu' => 'bg-green-100 text-green-800 border-green-200 dark:bg-green-300 dark:text-green-900',
                                'fermé' => 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-400 dark:text-gray-900',
                            ];
                            $prioriteClass = $prioriteColors[strtolower($ticket->priorite)] ?? 'bg-gray-100 dark:bg-gray-600';
                            $statusClass = $statusColors[strtolower($ticket->status)] ?? 'bg-gray-100 dark:bg-gray-600';
                        @endphp
                        <tr class="border-b border-gray-100 dark:border-gray-700">
                            <td class="py-4 px-6 font-medium text-gray-900 dark:text-white">{{ $ticket->titre }}</td>
                            <td class="py-4 px-6 text-sm text-gray-600 dark:text-gray-300">{{ $ticket->categorie->titre ?? 'Non défini' }}</td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-3 py-1 text-xs rounded-full border font-medium {{ $prioriteClass }}">
                                    {{ ucfirst($ticket->priorite) }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 text-xs rounded-full border font-medium {{ $statusClass }}">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-500 dark:text-gray-300">
                                {{ $ticket->created_at->diffCourtPourHumains() }}
                            </td>
                            <td class="py-4 px-6 flex items-center gap-2">
                                <!-- Modifier -->
                                @php
                            $nonModifiableStatuses = ['en_cours', 'résolu', 'fermé']; // adapte les valeurs selon tes statuts exacts (sans underscore si tu utilises des espaces)
                             @endphp  
                              @if (!in_array(strtolower($ticket->status), $nonModifiableStatuses))  
                                <a href="{{ route('tickets.edit', $ticket->id) }}"
                                   class="h-8 w-8 p-0 hover:bg-gray-100 dark:hover:bg-gray-700 rounded flex items-center justify-center"
                                   title="Modifier">
                                    <svg class="h-4 w-4 text-gray-500 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>
                                @endif

                                <!-- Supprimer -->
                                <form id="delete-form-{{ $ticket->id }}" method="POST" action="{{ route('tickets.destroy', $ticket->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="h-8 w-8 p-0 hover:bg-red-100 dark:hover:bg-red-700 rounded flex items-center justify-center text-gray-500 dark:text-gray-300"
                                        onclick="openDeleteModal({{ $ticket->id }})"
                                        title="Supprimer">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                            <path d="M10 11v6"></path>
                                            <path d="M14 11v6"></path>
                                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                                        </svg>
                                    </button>
                                </form>

                                <!-- Modale -->
                                <div id="delete-modal-{{ $ticket->id }}"
                                    class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-500 bg-opacity-75"
                                    aria-modal="true" role="dialog">
                                    <div class="bg-white dark:bg-gray-800 text-gray-800 dark:text-white rounded-lg shadow-lg max-w-md w-full p-6 text-center">
                                        <h3 class="text-lg font-semibold mb-4">Supprimer ce ticket ?</h3>
                                        <p class="mb-6 text-sm text-gray-600 dark:text-gray-400">
                                            Êtes-vous sûr de vouloir supprimer ce ticket ? Cette action est irréversible.
                                        </p>
                                        <div class="flex justify-center gap-4">
                                            <button type="button"
                                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded"
                                                onclick="confirmDelete({{ $ticket->id }})">
                                                Supprimer
                                            </button>
                                            <button type="button"
                                                class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 px-4 py-2 rounded"
                                                onclick="closeDeleteModal({{ $ticket->id }})">
                                                Annuler
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    function openDeleteModal(id) {
                                        document.getElementById('delete-modal-' + id).classList.remove('hidden');
                                        document.getElementById('delete-modal-' + id).classList.add('flex');
                                    }

                                    function closeDeleteModal(id) {
                                        document.getElementById('delete-modal-' + id).classList.remove('flex');
                                        document.getElementById('delete-modal-' + id).classList.add('hidden');
                                    }

                                    function confirmDelete(id) {
                                        document.getElementById('delete-form-' + id).submit();
                                    }
                                </script>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-500 dark:text-gray-400">Aucun ticket trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="px-6 py-4">
                {{ $tickets->links('pagination::simple-tailwind') }}
            </div>
        </div>
    </div>
</div>
@endsection
