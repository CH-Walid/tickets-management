@extends('admin.layout')
@section('title', 'Utilisateurs')
@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-8 animate-fade-in-up">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
        <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Utilisateurs</h2>
        <div class="flex gap-2 w-full md:w-auto items-center">
            <a href="{{ route('admin.utilisateurs.export') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition flex items-center gap-2"><i data-lucide="download" class="w-4 h-4"></i>Exporter</a>
            <input id="search-user" type="text" placeholder="Rechercher..." class="flex-1 md:w-64 px-4 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
            <a href="{{ route('admin.utilisateurs.create') }}" class="px-4 py-2 bg-blue-700 text-white rounded-lg shadow hover:bg-blue-800 transition flex items-center gap-2"><i data-lucide="plus" class="w-4 h-4"></i>Ajouter</a>
            <button id="toggle-cards" class="ml-2 p-2 rounded-full border border-gray-200 bg-white shadow-sm hover:bg-blue-50 transition flex items-center" title="Vue cartes"><i data-lucide="layout-grid" class="w-5 h-5"></i></button>
            <button id="toggle-list" class="p-2 rounded-full border border-gray-200 bg-white shadow-sm hover:bg-blue-50 transition flex items-center" title="Vue liste"><i data-lucide="list" class="w-5 h-5"></i></button>
        </div>
    </div>
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    <div id="view-cards" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($utilisateurs as $user)
            <div class="bg-white rounded-2xl shadow group hover:shadow-lg transition-all duration-300 p-6 flex flex-col items-center relative border border-gray-100 user-card" data-nom="{{ strtolower($user->nom ?? '') }}" data-prenom="{{ strtolower($user->prenom ?? '') }}" data-email="{{ strtolower($user->email ?? '') }}" data-service="{{ strtolower($user->userSimple->service->titre ?? '') }}">
                <x-user-avatar :user="$user" size="16" font="xl" class="mb-4 shadow-lg" />
                <div class="text-center w-full">
                    <div class="font-bold text-lg text-gray-900 mb-1">{{ $user->nom ?? '' }} {{ $user->prenom ?? '' }}</div>
                    <div class="text-sm text-gray-500 mb-1 truncate max-w-[180px] mx-auto" title="{{ $user->email ?? '' }}">{{ $user->email ?? '' }}</div>
                    <div class="mb-2"><span class="inline-block bg-blue-50 text-blue-700 text-xs px-2 py-1 rounded-full font-medium">{{ $user->userSimple->service->titre ?? '' }}</span></div>
                </div>
                <div class="flex gap-2 mt-4 w-full justify-center">
                    <a href="{{ route('admin.utilisateurs.show', $user->id) }}" class="p-2 rounded-full hover:bg-indigo-50 text-indigo-600 transition" title="Voir"><i data-lucide="eye" class="w-5 h-5"></i></a>
                    <a href="{{ route('admin.utilisateurs.edit', $user->id) }}" class="p-2 rounded-full hover:bg-blue-50 text-blue-700 transition" title="Modifier"><i data-lucide="pencil" class="w-5 h-5"></i></a>
                    <form action="{{ route('admin.utilisateurs.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 rounded-full hover:bg-red-50 text-red-600 transition" title="Supprimer"><i data-lucide="trash-2" class="w-5 h-5"></i></button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-gray-400">Aucun utilisateur trouvé.</div>
        @endforelse
    </div>
    <div id="view-list" class="hidden overflow-x-auto rounded-xl border border-gray-100">
        <table class="min-w-full table-auto bg-white rounded-xl">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nom</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Prénom</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Service</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($utilisateurs as $user)
                <tr class="border-t hover:bg-gray-50 transition user-row" data-nom="{{ strtolower($user->nom ?? '') }}" data-prenom="{{ strtolower($user->prenom ?? '') }}" data-email="{{ strtolower($user->email ?? '') }}" data-service="{{ strtolower($user->userSimple->service->titre ?? '') }}">
                    <td class="px-4 py-2 font-semibold text-gray-800">{{ $user->nom ?? '' }}</td>
                    <td class="px-4 py-2 text-gray-700">{{ $user->prenom ?? '' }}</td>
                    <td class="px-4 py-2 text-gray-600 truncate max-w-[180px]" title="{{ $user->email ?? '' }}">{{ $user->email ?? '' }}</td>
                    <td class="px-4 py-2">
                        <span class="inline-block bg-blue-50 text-blue-700 text-xs px-2 py-1 rounded-full font-medium">{{ $user->userSimple->service->titre ?? '' }}</span>
                    </td>
                    <td class="px-4 py-2 flex gap-2 justify-center">
                        <a href="{{ route('admin.utilisateurs.show', $user->id) }}" class="p-2 rounded hover:bg-indigo-50 text-indigo-600 transition" title="Voir"><i data-lucide="eye" class="w-5 h-5"></i></a>
                        <a href="{{ route('admin.utilisateurs.edit', $user->id) }}" class="p-2 rounded hover:bg-blue-50 text-blue-700 transition" title="Modifier"><i data-lucide="pencil" class="w-5 h-5"></i></a>
                        <form action="{{ route('admin.utilisateurs.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded hover:bg-red-50 text-red-600 transition" title="Supprimer"><i data-lucide="trash-2" class="w-5 h-5"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-6 text-gray-400">Aucun utilisateur trouvé.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<style>
@keyframes fade-in-up { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: none; } }
.animate-fade-in-up { animation: fade-in-up 0.7s cubic-bezier(.4,2,.6,1) both; }
.user-card .truncate, .user-row .truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnCards = document.getElementById('toggle-cards');
    const btnList = document.getElementById('toggle-list');
    const viewCards = document.getElementById('view-cards');
    const viewList = document.getElementById('view-list');
    let mode = localStorage.getItem('userViewMode') || 'cards';
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
        localStorage.setItem('userViewMode', mode);
        updateView();
    });
    btnList.addEventListener('click', function() {
        mode = 'list';
        localStorage.setItem('userViewMode', mode);
        updateView();
    });
    updateView();

    // Recherche intelligente
    const searchInput = document.getElementById('search-user');
    searchInput.addEventListener('input', function() {
        const q = searchInput.value.trim().toLowerCase();
        // Cartes
        document.querySelectorAll('.user-card').forEach(card => {
            const nom = card.dataset.nom;
            const prenom = card.dataset.prenom;
            const email = card.dataset.email;
            const service = card.dataset.service;
            if (
                nom.includes(q) ||
                prenom.includes(q) ||
                email.includes(q) ||
                service.includes(q)
            ) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
        // Liste
        document.querySelectorAll('.user-row').forEach(row => {
            const nom = row.dataset.nom;
            const prenom = row.dataset.prenom;
            const email = row.dataset.email;
            const service = row.dataset.service;
            if (
                nom.includes(q) ||
                prenom.includes(q) ||
                email.includes(q) ||
                service.includes(q)
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