@extends('admin.layout')
@section('title', 'Tickets')
@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-8 animate-fade-in-up">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
        <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Tickets</h2>
        <div class="flex gap-2 w-full md:w-auto items-center">
            <a href="{{ route('admin.tickets.export') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition flex items-center gap-2"><i data-lucide="download" class="w-4 h-4"></i>Exporter</a>
            <input id="search-ticket" type="text" placeholder="Rechercher..." class="flex-1 md:w-64 px-4 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
            <a href="{{ route('admin.tickets.create') }}" class="px-4 py-2 bg-blue-700 text-white rounded-lg shadow hover:bg-blue-800 transition flex items-center gap-2"><i data-lucide="plus" class="w-4 h-4"></i>Ajouter</a>
            <button id="toggle-cards" class="ml-2 p-2 rounded-full border border-gray-200 bg-white shadow-sm hover:bg-blue-50 transition flex items-center" title="Vue cartes"><i data-lucide="layout-grid" class="w-5 h-5"></i></button>
            <button id="toggle-list" class="p-2 rounded-full border border-gray-200 bg-white shadow-sm hover:bg-blue-50 transition flex items-center" title="Vue liste"><i data-lucide="list" class="w-5 h-5"></i></button>
        </div>
    </div>
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    <div class="flex flex-wrap gap-4 mb-4">
        <form method="GET" class="flex gap-2 flex-wrap items-center">
            <select name="status" class="border rounded px-2 py-1 text-sm">
                <option value="">Tous statuts</option>
                <option value="ouvert" @if(request('status') == 'ouvert') selected @endif>Ouvert</option>
                <option value="en_cours" @if(request('status') == 'en_cours') selected @endif>En cours</option>
                <option value="ferme" @if(request('status') == 'ferme') selected @endif>Fermé</option>
            </select>
            <select name="service_id" class="border rounded px-2 py-1 text-sm">
                <option value="">Tous services</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}" @if(request('service_id') == $service->id) selected @endif>{{ $service->titre }}</option>
                @endforeach
            </select>
            <select name="technicien_id" class="border rounded px-2 py-1 text-sm">
                <option value="">Tous techniciens</option>
                @foreach($techniciens as $tech)
                    <option value="{{ $tech->id }}" @if(request('technicien_id') == $tech->id) selected @endif>{{ $tech->user->nom ?? '' }} {{ $tech->user->prenom ?? '' }}</option>
                @endforeach
            </select>
            <select name="user_simple_id" class="border rounded px-2 py-1 text-sm">
                <option value="">Tous utilisateurs</option>
                @foreach($utilisateurs as $user)
                    <option value="{{ $user->id }}" @if(request('user_simple_id') == $user->id) selected @endif>{{ $user->nom }} {{ $user->prenom }}</option>
                @endforeach
            </select>
            <select name="categorie_id" class="border rounded px-2 py-1 text-sm">
                <option value="">Toutes catégories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @if(request('categorie_id') == $cat->id) selected @endif>{{ $cat->titre }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-3 py-1 bg-blue-100 text-blue-700 rounded">Filtrer</button>
        </form>
    </div>
    <div id="view-cards" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($tickets as $ticket)
            <div class="bg-white rounded-2xl shadow group hover:shadow-lg transition-all duration-300 p-6 flex flex-col items-start relative border border-gray-100 ticket-card" data-titre="{{ strtolower($ticket->titre ?? '') }}" data-status="{{ strtolower($ticket->status ?? '') }}" data-user="{{ strtolower($ticket->userSimple->user->nom ?? '') }}" data-tech="{{ strtolower($ticket->technicien->user->nom ?? '') }}">
                <div class="flex items-center gap-2 mb-2">
                    @php
                        $status = strtolower($ticket->status);
                        $badge = match(true) {
                            str_contains($status, 'ouvert') || str_contains($status, 'new') || str_contains($status, 'nouveau') => ['text' => 'text-green-600', 'icon' => 'circle'],
                            str_contains($status, 'en_cours') => ['text' => 'text-yellow-500', 'icon' => 'loader'],
                            str_contains($status, 'ferme') || str_contains($status, 'cloturé') || str_contains($status, 'résolu') => ['text' => 'text-gray-500', 'icon' => 'check-circle'],
                            default => ['text' => 'text-blue-600', 'icon' => 'help-circle'],
                        };
                    @endphp
                    <i data-lucide="{{ $badge['icon'] }}" class="w-5 h-5 {{ $badge['text'] }}"></i>
                    <span class="font-semibold text-gray-800">{{ $ticket->titre }}</span>
                </div>
                <div class="text-xs text-gray-500 mb-1">{{ $ticket->created_at ? $ticket->created_at->format('d/m/Y') : '-' }}</div>
                <div class="text-xs text-gray-400 mb-2">Utilisateur : <span class="font-semibold text-gray-600">{{ $ticket->userSimple->user->nom ?? '-' }} {{ $ticket->userSimple->user->prenom ?? '' }}</span></div>
                <div class="text-xs text-gray-400 mb-2">Technicien : <span class="font-semibold text-gray-600">{{ $ticket->technicien->user->nom ?? '-' }} {{ $ticket->technicien->user->prenom ?? '' }}</span></div>
                <div class="text-xs text-gray-400 mb-2">Catégorie : <span class="font-semibold text-gray-600">{{ $ticket->categorie->titre ?? '-' }}</span></div>
                <div class="flex gap-2 mt-4 w-full justify-end">
                    <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="p-2 rounded-full hover:bg-indigo-50 text-indigo-600 transition" title="Voir"><i data-lucide="eye" class="w-5 h-5"></i></a>
                    <a href="{{ route('admin.tickets.edit', $ticket->id) }}" class="p-2 rounded-full hover:bg-blue-50 text-blue-700 transition" title="Modifier"><i data-lucide="pencil" class="w-5 h-5"></i></a>
                    <form action="{{ route('admin.tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Supprimer ce ticket ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 rounded-full hover:bg-red-50 text-red-600 transition" title="Supprimer"><i data-lucide="trash-2" class="w-5 h-5"></i></button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-gray-400">Aucun ticket trouvé.</div>
        @endforelse
    </div>
    <div id="view-list" class="hidden overflow-x-auto rounded-xl border border-gray-100">
        <table class="min-w-full table-auto bg-white rounded-xl">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Titre</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Statut</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Utilisateur</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Technicien</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Catégorie</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($tickets as $ticket)
                <tr class="border-t hover:bg-gray-50 transition ticket-row" data-titre="{{ strtolower($ticket->titre ?? '') }}" data-status="{{ strtolower($ticket->status ?? '') }}" data-user="{{ strtolower($ticket->userSimple->user->nom ?? '') }}" data-tech="{{ strtolower($ticket->technicien->user->nom ?? '') }}">
                    <td class="px-4 py-2 font-semibold text-gray-800">{{ $ticket->titre }}</td>
                    <td class="px-4 py-2">
                        @php
                            $status = strtolower($ticket->status);
                            $badge = match(true) {
                                str_contains($status, 'ouvert') || str_contains($status, 'new') || str_contains($status, 'nouveau') => ['text' => 'text-green-600', 'icon' => 'circle'],
                                str_contains($status, 'en_cours') => ['text' => 'text-yellow-500', 'icon' => 'loader'],
                                str_contains($status, 'ferme') || str_contains($status, 'cloturé') || str_contains($status, 'résolu') => ['text' => 'text-gray-500', 'icon' => 'check-circle'],
                                default => ['text' => 'text-blue-600', 'icon' => 'help-circle'],
                            };
                        @endphp
                        <i data-lucide="{{ $badge['icon'] }}" class="w-5 h-5 {{ $badge['text'] }}"></i>
                    </td>
                    <td class="px-4 py-2 text-gray-700">{{ $ticket->userSimple->user->nom ?? '-' }} {{ $ticket->userSimple->user->prenom ?? '' }}</td>
                    <td class="px-4 py-2 text-gray-700">{{ $ticket->technicien->user->nom ?? '-' }} {{ $ticket->technicien->user->prenom ?? '' }}</td>
                    <td class="px-4 py-2 text-gray-600">{{ $ticket->categorie->titre ?? '-' }}</td>
                    <td class="px-4 py-2 text-gray-500">{{ $ticket->created_at ? $ticket->created_at->format('d/m/Y') : '-' }}</td>
                    <td class="px-4 py-2 flex gap-2 justify-center">
                        <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="p-2 rounded hover:bg-indigo-50 text-indigo-600 transition" title="Voir"><i data-lucide="eye" class="w-5 h-5"></i></a>
                        <a href="{{ route('admin.tickets.edit', $ticket->id) }}" class="p-2 rounded hover:bg-blue-50 text-blue-700 transition" title="Modifier"><i data-lucide="pencil" class="w-5 h-5"></i></a>
                        <form action="{{ route('admin.tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Supprimer ce ticket ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded hover:bg-red-50 text-red-600 transition" title="Supprimer"><i data-lucide="trash-2" class="w-5 h-5"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-6 text-gray-400">Aucun ticket trouvé.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<style>
@keyframes fade-in-up { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: none; } }
.animate-fade-in-up { animation: fade-in-up 0.7s cubic-bezier(.4,2,.6,1) both; }
.ticket-card .truncate, .ticket-row .truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnCards = document.getElementById('toggle-cards');
    const btnList = document.getElementById('toggle-list');
    const viewCards = document.getElementById('view-cards');
    const viewList = document.getElementById('view-list');
    let mode = localStorage.getItem('ticketViewMode') || 'cards';
    function updateView() {
        if (mode === 'cards') {
            viewCards.classList.remove('hidden');
            viewList.classList.add('hidden');
            btnCards.classList.add('active');
            btnList.classList.remove('active');
        } else {
            viewCards.classList.add('hidden');
            viewList.classList.remove('hidden');
            btnCards.classList.remove('active');
            btnList.classList.add('active');
        }
    }
    btnCards.addEventListener('click', function() {
        mode = 'cards';
        localStorage.setItem('ticketViewMode', mode);
        updateView();
    });
    btnList.addEventListener('click', function() {
        mode = 'list';
        localStorage.setItem('ticketViewMode', mode);
        updateView();
    });
    updateView();

    // Recherche intelligente
    const searchInput = document.getElementById('search-ticket');
    searchInput.addEventListener('input', function() {
        const q = searchInput.value.trim().toLowerCase();
        // Cartes
        document.querySelectorAll('.ticket-card').forEach(card => {
            const titre = card.dataset.titre;
            const status = card.dataset.status;
            const user = card.dataset.user;
            const tech = card.dataset.tech;
            if (
                titre.includes(q) ||
                status.includes(q) ||
                user.includes(q) ||
                tech.includes(q)
            ) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
        // Liste
        document.querySelectorAll('.ticket-row').forEach(row => {
            const titre = row.dataset.titre;
            const status = row.dataset.status;
            const user = row.dataset.user;
            const tech = row.dataset.tech;
            if (
                titre.includes(q) ||
                status.includes(q) ||
                user.includes(q) ||
                tech.includes(q)
            ) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>
@endsection 