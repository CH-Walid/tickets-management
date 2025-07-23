@extends('admin.layout')
@section('title', 'Statistiques')
@section('content')
<div class="max-w-7xl mx-auto p-8 space-y-10 animate-fade-in-up">
    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow group hover:shadow-lg transition-all duration-300 relative overflow-hidden animate-fade-in">
            <div class="absolute top-0 right-0 w-16 h-16 bg-blue-500 rounded-bl-3xl opacity-10"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tickets totaux</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalTickets }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow">
                    <i data-lucide="file-text" class="w-6 h-6 text-white"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow group hover:shadow-lg transition-all duration-300 relative overflow-hidden animate-fade-in" style="animation-delay:0.05s;">
            <div class="absolute top-0 right-0 w-16 h-16 bg-green-500 rounded-bl-3xl opacity-10"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tickets ouverts</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $openTickets }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow">
                    <i data-lucide="circle" class="w-6 h-6 text-white"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow group hover:shadow-lg transition-all duration-300 relative overflow-hidden animate-fade-in" style="animation-delay:0.1s;">
            <div class="absolute top-0 right-0 w-16 h-16 bg-yellow-500 rounded-bl-3xl opacity-10"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">En cours</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $inProgressTickets }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center shadow">
                    <i data-lucide="loader" class="w-6 h-6 text-white"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow group hover:shadow-lg transition-all duration-300 relative overflow-hidden animate-fade-in" style="animation-delay:0.15s;">
            <div class="absolute top-0 right-0 w-16 h-16 bg-lime-500 rounded-bl-3xl opacity-10"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tickets fermés</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $closedTickets }}</p>
                </div>
                <div class="w-12 h-12 bg-lime-500 rounded-xl flex items-center justify-center shadow">
                    <i data-lucide="check-circle" class="w-6 h-6 text-white"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-2xl shadow p-6 animate-fade-in-up">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold">Évolution des tickets (12 mois)</h3>
                <span class="text-xs text-gray-400">(créés par mois)</span>
            </div>
            <canvas id="ticketsChart" height="120"></canvas>
        </div>
        <div class="bg-white rounded-2xl shadow p-6 animate-fade-in-up">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold">Tickets par statut</h3>
            </div>
            <canvas id="statusChart" height="120"></canvas>
        </div>
    </div>
  <!-- Top techniciens/services -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="bg-white rounded-2xl shadow p-6 animate-fade-in-up">
        <h3 class="text-lg font-semibold mb-4">Top techniciens (tickets traités)</h3>
        <ul class="divide-y divide-gray-100">
            @foreach($topTechniciens as $tech)
                <li class="py-2 flex items-center gap-3">
                    @php
                        $user = $tech->user;
                        $bgColors = ['bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-pink-500', 'bg-purple-500'];
                        $colorClass = $bgColors[$loop->index % count($bgColors)];
                    @endphp

                    @if($user->photo)
                        <img src="{{ asset('storage/' . $user->photo) }}"
                             alt="Avatar"
                             class="w-10 h-10 object-cover rounded-full shadow" />
                    @else
                        <span class="{{ $colorClass }} text-white w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold shadow">
                            {{ strtoupper(substr($user->prenom,0,1) . substr($user->nom,0,1)) }}
                        </span>
                    @endif

                    <span class="font-semibold text-gray-800">{{ $user->prenom }} {{ $user->nom }}</span>
                    <span class="ml-auto text-blue-700 font-bold">{{ $tech->total }}</span>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 animate-fade-in-up">
        <h3 class="text-lg font-semibold mb-4">Top services (tickets)</h3>
        <ul class="divide-y divide-gray-100">
            @foreach($topServices as $service)
                <li class="py-2 flex items-center gap-3">
                    <span class="font-semibold text-gray-800">{{ $service->titre }}</span>
                    <span class="ml-auto text-green-700 font-bold">{{ $service->total }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique évolution des tickets
    const ctxTickets = document.getElementById('ticketsChart').getContext('2d');
    new Chart(ctxTickets, {
        type: 'line',
        data: {
            labels: @json($monthLabels),
            datasets: [{
                label: 'Tickets créés',
                data: @json($ticketsPerMonth),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.08)',
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#6366f1',
                fill: true,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
    // Graphique tickets par statut
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($ticketsByStatus->toArray())),
            datasets: [{
                data: @json(array_values($ticketsByStatus->toArray())),
                backgroundColor: ['#22c55e','#eab308','#64748b','#6366f1','#f43f5e'],
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom' } }
        }
    });
    if(window.lucide) lucide.createIcons();
</script>
@endpush
<style>
@keyframes fade-in-up { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: none; } }
.animate-fade-in-up { animation: fade-in-up 0.7s cubic-bezier(.4,2,.6,1) both; }
@keyframes fade-in { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: none; } }
.animate-fade-in { animation: fade-in 0.7s cubic-bezier(.4,2,.6,1) both; }
</style>
@endsection 