@extends('admin.layout')
@section('title', 'Dashboard')
@section('content')
<div class="space-y-10">
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
    <!-- Section principale -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Colonne de gauche : résumé admin et derniers tickets -->
        <div class="col-span-1 lg:col-span-4 bg-white rounded-2xl shadow p-6 flex flex-col items-center animate-fade-in-up">
            <x-user-avatar :user="Auth::user()" size="20" font="2xl" class="mx-auto mb-4 border-2 border-blue-200 shadow" />
            <h3 class="font-semibold text-lg mb-1">Vue d'ensemble</h3>
            <div class="grid grid-cols-3 gap-4 my-4 w-full">
                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-500">{{ $totalTickets }}</div>
                    <div class="text-xs text-gray-600">Tickets</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-500">{{ $totalUsers }}</div>
                    <div class="text-xs text-gray-600">Utilisateurs</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-500">{{ $totalTechniciens }}</div>
                    <div class="text-xs text-gray-600">Techniciens</div>
                </div>
            </div>
            <div class="mt-6 w-full">
                <h4 class="font-semibold mb-2">Derniers tickets créés</h4>
                <ul class="space-y-2 text-sm">
                    @foreach($latestTickets as $ticket)
                        <li class="flex items-center gap-2 justify-between bg-gray-50 rounded px-2 py-1 hover:bg-gray-100 transition">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 {{ in_array($ticket->status, ['cloturé', 'résolu']) ? 'bg-gray-500' : ($ticket->status == 'nouveau' ? 'bg-green-500' : 'bg-yellow-500') }} rounded-full"></span>
                                <span class="font-medium">#{{ $ticket->id }}</span>
                                <span class="text-gray-700">{{ $ticket->titre }}</span>
                                <span class="text-xs text-gray-400">({{ ucfirst($ticket->status) }})</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">{{ $ticket->created_at ? $ticket->created_at->format('d/m/Y H:i') : '' }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="mt-8 w-full">
                <h4 class="font-semibold mb-2">Performance</h4>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Taux de résolution</span>
                        <span class="font-bold text-green-600">{{ $resolutionRate ?? '—' }}%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Délai moyen</span>
                        <span class="font-bold text-blue-600">{{ $avgResolutionTime ?? '—' }}</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Colonne de droite : graphique et stats -->
        <div class="col-span-1 lg:col-span-8 bg-white rounded-2xl shadow animate-fade-in-up">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold">Statistiques des tickets</h3>
                <span class="text-xs text-gray-400">(12 derniers mois)</span>
            </div>
            <div class="p-6">
                <canvas id="ticketsChart" height="120"></canvas>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('ticketsChart').getContext('2d');
    const labels = @json($monthLabels ?? []);
    const statuses = @json($allStatuses ?? []);
    const chartData = @json($ticketsChartData ?? []);
    // Palette de couleurs pour les statuts (ajoute-en si besoin)
    const statusColors = [
        '#10b981', // vert
        '#f59e0b', // jaune
        '#3b82f6', // bleu
        '#6366f1', // indigo
        '#ef4444', // rouge
        '#a21caf', // violet
        '#eab308', // or
        '#64748b', // gris
        '#22d3ee', // cyan
        '#f43f5e', // rose
    ];
    const datasets = statuses.map((status, idx) => ({
        label: status.charAt(0).toUpperCase() + status.slice(1),
        data: chartData[status] ?? [],
        backgroundColor: statusColors[idx % statusColors.length],
        stack: 'Stack 0',
    }));
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                x: {
                    display: true,
                    ticks: {
                        maxRotation: 45,
                        minRotation: 0,
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    // Ajout d'options pour lisibilité des courbes
    datasets.forEach(ds => {
        ds.fill = false;
        ds.tension = 0.3;
        ds.borderColor = ds.backgroundColor;
        ds.pointBackgroundColor = ds.backgroundColor;
        ds.borderWidth = 2;
    });
</script>
@endpush
<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: none; }
}
.animate-fade-in { animation: fade-in 0.7s cubic-bezier(.4,2,.6,1) both; }
@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: none; }
}
.animate-fade-in-up { animation: fade-in-up 0.9s cubic-bezier(.4,2,.6,1) both; }
</style>
@endsection
