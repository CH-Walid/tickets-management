@extends('layouts.app')

@section('title', 'Les Tickets')

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
                Tous Les Tickets
            </h1>

            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Voici tous les tickets créés.</p>

            <!-- Form de recherche -->
            <form method="GET" action="{{ route('chef.tickets.all') }}" class="flex items-center gap-4 w-full">
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
               <a href="{{ route('chef.tickets.export') }}"
                class="dark:bg-green-600 bg-green-500 hover:bg-green-700 dark:hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center gap-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m0 0l-3-3m3 3l3-3m-9-2h12" />
                    </svg>Exporter
                </a>
            </form>
        </div>

        <!-- Tableau des tickets -->
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300 dark:border-gray-600 border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700">
                        <th class="py-4 px-6 text-left text-gray-700 dark:text-gray-200 font-semibold border-r border-gray-300 dark:border-gray-600">TITRE</th>
                        <th class="py-4 px-6 text-left text-gray-700 dark:text-gray-200 font-semibold border-r border-gray-300 dark:border-gray-600">CATÉGORIE</th>
                        <th class="py-4 px-6 text-left text-gray-700 dark:text-gray-200 font-semibold border-r border-gray-300 dark:border-gray-600">ASSIGNÉ À</th>
                        <th class="py-4 px-6 text-center text-gray-700 dark:text-gray-200 font-semibold border-r border-gray-300 dark:border-gray-600">PRIORITÉ</th>
                        <th class="py-4 px-6 text-left text-gray-700 dark:text-gray-200 font-semibold border-r border-gray-300 dark:border-gray-600">STATUT</th>
                        <th class="py-4 px-6 text-left text-gray-700 dark:text-gray-200 font-semibold border-r border-gray-300 dark:border-gray-600">CRÉÉ LE</th>
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
                        <tr class="border-b border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <td class="py-4 px-6 border-r border-gray-300 dark:border-gray-600 font-medium text-gray-900 dark:text-white">{{ $ticket->titre }}</td>
                            <td class="py-4 px-6 border-r border-gray-300 dark:border-gray-600 text-sm text-gray-600 dark:text-gray-300">{{ $ticket->categorie->titre ?? 'Non défini' }}</td>

                            <td class="py-4 px-6 border-r border-gray-300 dark:border-gray-600 text-sm text-gray-600 dark:text-gray-300">
                               @if($ticket->technicien && $ticket->technicien->user)
                                    {{ $ticket->technicien->user->nom }} {{ $ticket->technicien->user->prenom }}
                                @else
                                    <span class="italic text-gray-400 dark:text-gray-500">Non assigné</span>
                                @endif

                            </td>

                            <td class="py-4 px-6 border-r border-gray-300 dark:border-gray-600 text-center">
                                <span class="px-3 py-1 text-xs rounded-full border font-medium {{ $prioriteClass }}">
                                    {{ ucfirst($ticket->priorite) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 border-r border-gray-300 dark:border-gray-600">
                                <span class="px-3 py-1 text-xs rounded-full border font-medium {{ $statusClass }}">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 border-r border-gray-300 dark:border-gray-600 text-sm text-gray-500 dark:text-gray-300">
                                {{ $ticket->created_at->diffForHumans() }}
                            </td>
                            <td class="py-4 px-6 flex items-center gap-2">

                                 <!-- Modifier icône -->
                                <a href="{{ route('chef.tickets.edit', $ticket->id) }}"
                                  class="text-blue-500 hover:text-blue-700 mr-3"
                                   title="Modifier">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5h2M15.5 3.5l5 5m-1.086-6.414a2 2 0 112.828 2.828L16 13H13v-3l6.414-6.414zM4 20h4l10-10-4-4L4 16v4z" />
                                    </svg>
                                </a>

                                <!-- Supprimer icône avec modal -->
<button onclick="openDialog({{ $ticket->id }})" class="text-red-500 hover:text-red-700" title="Supprimer">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-6 0h6" />
    </svg>
</button>

<!-- Modal -->
<div id="modal-{{ $ticket->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="fixed inset-0 bg-gray-500/75 dark:bg-black/60 transition-opacity" aria-hidden="true"></div>

    <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md z-10">
        <div class="p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-300/20">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75M3 17.126c-.866 1.5.217 3.374 1.948 3.374h14.104c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L3 17.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div class="ml-4 text-left">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Supprimer le ticket</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-300">Voulez-vous vraiment supprimer ce ticket ? Cette action est irréversible.</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 flex justify-end">
            <form method="POST" action="{{ route('chef.tickets.destroy', $ticket->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-500 text-sm">Supprimer</button>
            </form>
            <button onclick="closeDialog({{ $ticket->id }})" class="ml-2 bg-white dark:bg-gray-600 dark:text-gray-200 px-4 py-2 rounded-md text-gray-700 border dark:border-gray-500 hover:bg-gray-100 dark:hover:bg-gray-500 text-sm">Annuler</button>
        </div>
    </div>
</div>

<!-- JavaScript to control dialog -->
<script>
    function openDialog(id) {
        document.getElementById('modal-' + id).classList.remove('hidden');
    }
    function closeDialog(id) {
        document.getElementById('modal-' + id).classList.add('hidden');
    }
</script>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                Aucun ticket trouvé.
                            </td>
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