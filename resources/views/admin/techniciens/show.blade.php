@extends('admin.layout')
@section('title', 'Détail technicien')
@section('content')
<div class="max-w-2xl mx-auto p-8 bg-white/80 backdrop-blur rounded-3xl shadow-2xl space-y-8 animate-fade-in-up relative">
    <!-- Bouton retour -->
    <a href="{{ route('admin.techniciens.techniciens.index') }}" class="absolute left-6 top-6 text-gray-400 hover:text-blue-600 transition flex items-center gap-1 text-sm z-10"><i data-lucide="arrow-left" class="w-4 h-4"></i>Retour</a>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-center gap-6">
        <div class="relative group">
            @php $photo = $technicien->user->photo ?? $technicien->photo ?? null; @endphp
            @if($photo)
                <img id="profile-photo" src="{{ asset('storage/' . $photo) }}" alt="Avatar" class="w-28 h-28 rounded-full object-cover border-4 border-blue-200 shadow cursor-pointer transition-transform group-hover:scale-105">
            @else
                <x-user-avatar :user="$technicien->user" size="28" font="2xl" class="w-28 h-28 rounded-full object-cover border-4 border-blue-200 shadow" id="profile-photo" />
            @endif
            <!-- Je retire le bouton et l'icône de loupe -->
        </div>
        <div class="flex-1 text-center sm:text-left">
            <div class="font-bold text-2xl text-gray-900 mb-1">{{ $technicien->user->nom ?? '-' }} {{ $technicien->user->prenom ?? '-' }}</div>
            <div class="text-gray-500 text-sm mb-1 truncate" title="{{ $technicien->user->email ?? '-' }}">{{ $technicien->user->email ?? '-' }}</div>
            <div class="text-xs text-gray-400 mb-1">Téléphone : <span class="font-semibold text-gray-600">{{ $technicien->user->phone ?? '-' }}</span></div>
            <div class="mt-2"><span class="inline-block bg-blue-50 text-blue-700 text-xs px-3 py-1 rounded-full font-medium">{{ $technicien->service->titre ?? '-' }}</span></div>
        </div>
    </div>
    <!-- Stats synthétiques -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white/70 backdrop-blur rounded-xl shadow-lg p-6 flex flex-col items-center">
            <span class="text-xs text-gray-400 uppercase mb-1">Tickets traités</span>
            <span class="text-2xl font-bold text-blue-700">{{ $technicien->tickets->count() }}</span>
        </div>
        <div class="bg-white/70 backdrop-blur rounded-xl shadow-lg p-6 flex flex-col items-center">
            @php
                $total = $technicien->tickets->count();
                $resolved = $technicien->tickets->whereIn('status', ['résolu','cloturé'])->count();
                $successRate = $total > 0 ? round($resolved / $total * 100, 1) : 0;
            @endphp
            <span class="text-xs text-gray-400 uppercase mb-1">Taux de réussite</span>
            <span class="text-2xl font-bold text-green-600">{{ $successRate }}%</span>
        </div>
        <div class="bg-white/70 backdrop-blur rounded-xl shadow-lg p-6 flex flex-col items-center">
            @php
                $note = ($technicien->commentaires && $technicien->commentaires->count() > 0)
                    ? round($technicien->commentaires->avg('note'), 2)
                    : '-';
            @endphp
            <span class="text-xs text-gray-400 uppercase mb-1">Note</span>
            <span class="text-2xl font-bold text-yellow-500">{{ $note }}</span>
        </div>
    </div>
    <!-- Graphique performance -->
    <div class="bg-white/70 backdrop-blur rounded-xl shadow-lg p-6">
        <div class="text-xs text-gray-400 uppercase mb-2">Performance sur 12 mois</div>
        <canvas id="performanceChart" height="120"></canvas>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Génère 12 mois glissants dynamiquement
    function getLast12MonthsLabels() {
        const labels = [];
        const now = new Date();
        for (let i = 11; i >= 0; i--) {
            const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
            labels.push(d.toLocaleString('fr-FR', { month: 'short', year: '2-digit' }));
        }
        return labels;
    }
    const jsLabels = getLast12MonthsLabels();
    const phpData = @json(
        collect(range(0,11))->map(function($i) use($technicien) {
            $month = now()->subMonths(11-$i)->format('Y-m');
            return $technicien->tickets->filter(function($t) use($month) {
                return optional($t->created_at)->format('Y-m') === $month;
            })->count();
        })
    );
    const ctx = document.getElementById('performanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: jsLabels,
            datasets: [{
                label: 'Tickets traités',
                data: phpData,
                backgroundColor: 'rgba(59,130,246,0.7)',
                borderRadius: 8,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
</script>
@endpush
<style>
@keyframes fade-in-up { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: none; } }
.animate-fade-in-up { animation: fade-in-up 0.7s cubic-bezier(.4,2,.6,1) both; }
</style>
@endsection
