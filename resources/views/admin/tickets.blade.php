@extends('admin.layout')
@section('title', 'Tickets')
@section('content')
<div class="p-8">
    <h2 class="text-2xl font-bold mb-6">Tickets</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full border glass animated-card fade-in">
            <thead class="bg-purple-100">
                <tr>
                    <th class="px-4 py-3 text-left">ID</th>
                    <th class="px-4 py-3 text-left">Titre</th>
                    <th class="px-4 py-3 text-left">Statut</th>
                    <th class="px-4 py-3 text-left">Priorité</th>
                    <th class="px-4 py-3 text-left">Créé le</th>
                    <th class="px-4 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $ticket)
                <tr class="hover:bg-purple-50 transition">
                    <td class="px-4 py-2">{{ $ticket->id }}</td>
                    <td class="px-4 py-2">{{ $ticket->title }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold 
                        @if($ticket->status=='open') bg-green-100 text-green-600
                        @elseif($ticket->status=='in_progress') bg-yellow-100 text-yellow-700
                        @elseif($ticket->status=='closed') bg-gray-200 text-gray-700
                        @endif">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ ucfirst($ticket->priority) }}</td>
                    <td class="px-4 py-2">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-2 flex gap-2">
                        <a href="#" class="ripple px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Voir</a>
                        <a href="#" class="ripple px-3 py-1 bg-purple-500 text-white rounded hover:bg-purple-600 transition">Assigner</a>
                        <a href="#" class="ripple px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">Fermer</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
