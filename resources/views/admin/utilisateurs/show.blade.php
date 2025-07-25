@extends('admin.layout')
@section('title', 'Détail utilisateur')
@section('content')
<div class="max-w-2xl mx-auto p-8 bg-white rounded-3xl shadow-xl space-y-8 animate-fade-in-up relative">
    <a href="{{ route('admin.utilisateurs.index') }}" class="absolute left-6 top-6 text-gray-400 hover:text-blue-600 transition flex items-center gap-1 text-sm z-10"><i data-lucide="arrow-left" class="w-4 h-4"></i>Retour</a>
    <div class="flex flex-col sm:flex-row items-center gap-6 mb-8">
        <x-user-avatar :user="$utilisateur" size="28" font="2xl" class="w-28 h-28 rounded-full object-cover border-4 border-blue-200 shadow" />
        <div class="flex-1 text-center sm:text-left">
            <div class="font-bold text-2xl text-gray-900 mb-1">{{ $utilisateur->nom ?? '-' }} {{ $utilisateur->prenom ?? '-' }}</div>
            <div class="text-gray-500 text-sm mb-1 truncate" title="{{ $utilisateur->email ?? '-' }}">{{ $utilisateur->email ?? '-' }}</div>
            <div class="text-xs text-gray-400 mb-1">Téléphone : <span class="font-semibold text-gray-600">{{ $utilisateur->phone ?? '-' }}</span></div>
            <div class="mt-2"><span class="inline-block bg-blue-50 text-blue-700 text-xs px-3 py-1 rounded-full font-medium">{{ $utilisateur->userSimple->service->titre ?? '-' }}</span></div>
            <div class="flex flex-wrap gap-4 mt-4 justify-center sm:justify-start">
                <div class="bg-gray-100 rounded-lg px-4 py-2 text-xs text-gray-500">Créé le : <span class="font-semibold text-gray-700">{{ $utilisateur->created_at ? $utilisateur->created_at->format('d/m/Y') : '-' }}</span></div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        @php
            $tickets = $utilisateur->userSimple->tickets ?? collect();
            $total = $tickets->count();
            $open = $tickets->whereIn('status', ['ouvert','en_cours','new','nouveau'])->count();
            $closed = $tickets->whereIn('status', ['ferme','cloturé','résolu'])->count();
        @endphp
        <div class="bg-gray-50 rounded-xl shadow p-6 flex flex-col items-center">
            <span class="text-xs text-gray-400 uppercase mb-1">Tickets</span>
            <span class="text-2xl font-bold text-blue-700">{{ $total }}</span>
        </div>
        <div class="bg-gray-50 rounded-xl shadow p-6 flex flex-col items-center">
            <span class="text-xs text-gray-400 uppercase mb-1">Ouverts</span>
            <span class="text-2xl font-bold text-green-600">{{ $open }}</span>
        </div>
        <div class="bg-gray-50 rounded-xl shadow p-6 flex flex-col items-center">
            <span class="text-xs text-gray-400 uppercase mb-1">Fermés</span>
            <span class="text-2xl font-bold text-gray-500">{{ $closed }}</span>
        </div>
    </div>
    <div class="bg-gray-50 rounded-xl shadow p-6">
        <div class="font-semibold text-blue-700 mb-4">Tickets créés par cet utilisateur</div>
        @if($tickets->count())
            <table class="min-w-full table-auto">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Titre</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Statut</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($tickets as $ticket)
                    <tr class="border-t hover:bg-gray-50 transition">
                        <td class="px-4 py-2">{{ $ticket->titre }}</td>
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
                        <td class="px-4 py-2">{{ $ticket->created_at ? $ticket->created_at->format('d/m/Y') : '-' }}</td>
                        <td class="px-4 py-2 text-center">
                            <a href="{{ route('admin.tickets') }}#ticket-{{ $ticket->id }}" class="text-indigo-600 hover:text-indigo-900" title="Voir le ticket"><i data-lucide="eye" class="w-5 h-5"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="text-gray-400 text-center py-6">Aucun ticket créé par cet utilisateur.</div>
        @endif
    </div>
</div>
@endsection 