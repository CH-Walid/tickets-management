@extends('layouts.app')

@section('title', 'Dashboard Technicien - Système de Gestion des Incidents')

@section('content')
<div class="p-6 dark:bg-gray-900 min-h-screen">
    <!-- Header avec informations du technicien -->
    <div class="mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                        👋 Bonjour, {{ auth()->user()->prenom }} {{ auth()->user()->nom }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-300">Voici un aperçu de vos tickets assignés</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ \Carbon\Carbon::now()->translatedFormat('l j F Y') }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ \Carbon\Carbon::now()->format('H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total des tickets -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total des tickets</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalTickets }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Nouveaux tickets -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Nouveaux tickets</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $newTickets }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Tickets en cours -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">En cours</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $inProgressTickets }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Tickets résolus -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Résolus</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $resolvedTickets }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Tickets urgents -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            Tickets urgents ({{ $urgentTickets->count() }})
                        </h2>
                        <a href="{{ route('tech.tickets.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                            Voir tous les tickets →
                        </a>
                    </div>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($urgentTickets as $ticket)
                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="font-semibold text-gray-900 dark:text-white">
                                            {{ $ticket->titre }}
                                        </h3>
                                        @php
                                            $statusColors = [
                                                'nouveau' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                                'en_cours' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                                'résolu' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                                'cloturé' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
                                            ];
                                            $statusClass = $statusColors[strtolower($ticket->status)] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 py-1 text-xs rounded-full {{ $statusClass }}">
                                            {{ ucfirst($ticket->status) }}
                                        </span>
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                            Urgent
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                        {{ strlen($ticket->description) > 100 ? substr($ticket->description, 0, 100) . '...' : $ticket->description }}
                                    </p>
                                    <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                        <span>📁 {{ $ticket->categorie->titre ?? 'Non classé' }}</span>
                                        <span>👤 {{ $ticket->userSimple->user->nom ?? 'Utilisateur inconnu' }} {{ $ticket->userSimple->user->prenom ?? '' }}</span>
                                        <span>📅 {{ $ticket->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="flex gap-2 ml-4">
                                    <a href="{{ route('tech.tickets.show', $ticket->id) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1 rounded transition-colors">
                                        Voir
                                    </a>
                                    @if($ticket->status !== 'résolu' && $ticket->status !== 'cloturé')
                                        <a href="{{ route('tech.tickets.edit', $ticket->id) }}" 
                                           class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1 rounded transition-colors">
                                            Traiter
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">Aucun ticket urgent ! 🎉</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Statistiques et activité récente -->
        <div class="space-y-6">
            <!-- Répartition par priorité -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Répartition par priorité</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Urgente</span>
                        </div>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $ticketsByPriority['urgente'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Normale</span>
                        </div>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $ticketsByPriority['normale'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Basse</span>
                        </div>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $ticketsByPriority['basse'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Tickets récents -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Activité récente</h3>
                <div class="space-y-3">
                    @forelse($recentTickets as $ticket)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            @php
                                $statusIcon = [
                                    'nouveau' => '🆕',
                                    'en_cours' => '⏳',
                                    'résolu' => '✅',
                                    'cloturé' => '🔒',
                                ];
                            @endphp
                            <div class="text-lg">{{ $statusIcon[$ticket->status] ?? '📄' }}</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ $ticket->titre }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $ticket->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <a href="{{ route('tech.tickets.show', $ticket->id) }}" 
                               class="text-blue-600 dark:text-blue-400 hover:underline text-xs">
                                Voir
                            </a>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">
                            Aucune activité récente
                        </p>
                    @endforelse
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Actions rapides</h3>
                <div class="space-y-3">
                    <a href="{{ route('tech.tickets.index') }}" 
                       class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="font-medium">Voir tous mes tickets</span>
                    </a>
                    
                    <a href="{{ route('user.profile.show') }}" 
                       class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="font-medium">Mon profil</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($totalTickets > 0)
        <!-- Performance du technicien -->
        <div class="mt-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">📈 Vos performances</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                            {{ round(($resolvedTickets / $totalTickets) * 100) }}%
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Taux de résolution</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                            {{ $inProgressTickets + $newTickets }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Tickets actifs</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                            {{ $urgentTickets->count() }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Tickets urgents</div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection