@extends('layouts.app')

@section('title', 'Chef - Liste des Techniciens')

@section('content')
<div class="p-6 dark:bg-gray-900 min-h-screen">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md ring-1 ring-gray-200 dark:ring-gray-700">

        <!-- En-tête + Boutons -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-indigo-50 dark:bg-indigo-900 rounded-t-lg">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <h1 class="text-2xl font-bold text-indigo-800 dark:text-indigo-200">
                    Liste des Techniciens ({{ count($techniciens) }})
                </h1>
                <div class="flex gap-2">
                    <a href="{{ route('chef.techniciens.create') }}"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow">
                        Ajouter Tech
                    </a>
                    <form method="GET" action="{{ route('chef.techniciens.export.pdf') }}">
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow">
                            Exporter PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Barre de recherche sous le titre -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600 bg-indigo-50 dark:bg-gray-800">
            <form method="GET" action="{{ route('chef.techniciens.index') }}" class="max-w-md">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Rechercher par nom, email ou service..." 
                    class="w-full rounded-md border border-gray-300 dark:border-gray-600 px-3 py-2 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
            </form>
        </div>

        <!-- Tableau -->
        <div class="overflow-x-visible">
           <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-indigo-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 uppercase tracking-wide">
                    <tr>
                        <th class="px-6 py-3 text-left">Nom</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Service</th>
                        <th class="px-6 py-3 text-left">Créé le</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300 dark:divide-gray-600">
                    @forelse($techniciens as $tech)
                        <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-700 transition">
                            <td class="px-6 py-4 text-gray-800 dark:text-gray-100">
                                {{ $tech->user->nom ?? '—' }} {{ $tech->user->prenom ?? '' }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-200">
                                {{ $tech->user->email ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-200">
                                {{ $tech->service->titre ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ $tech->user?->created_at?->format('d/m/Y') ?? 'Date inconnue' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="relative inline-block text-left" x-data="{ open: false }">
                                    <button @click="open = !open"
                                        class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-indigo-100 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                        aria-haspopup="true" aria-expanded="open">
                                        <svg class="w-5 h-5 text-gray-700 dark:text-gray-200" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm0 5a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm0 5a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"/>
                                        </svg>
                                    </button>

                                    <div
                                        x-show="open"
                                        @click.away="open = false"
                                        x-transition
                                        class="absolute z-50 mt-2 right-0 w-40 bg-white dark:bg-gray-900 rounded-md shadow-lg ring-1 ring-black ring-opacity-5"
                                        style="transform: translateY(10px);"
                                    >
                                        <div class="py-1">
                                            <a href="{{ route('chef.techniciens.edit', $tech->id) }}"
                                               class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-indigo-700">
                                                Modifier
                                            </a>
                                            <form method="POST" action="{{ route('chef.techniciens.destroy', $tech->id) }}"
                                                  onsubmit="return confirm('Voulez-vous vraiment supprimer ce technicien ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100 dark:hover:bg-red-700">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                Aucun technicien trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
           <!-- Pagination -->
<div class="px-6 py-4">
    {{ $techniciens->links('pagination::simple-tailwind') }}
</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="//unpkg.com/alpinejs" defer></script>
@endpush
