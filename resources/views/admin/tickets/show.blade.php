@extends('admin.layout')
@section('title', 'Détail ticket')
@section('content')
<div class="max-w-2xl mx-auto p-8 bg-white/80 backdrop-blur rounded-3xl shadow-2xl space-y-8 animate-fade-in-up relative">
    <!-- Bouton retour -->
    <a href="{{ route('admin.tickets.index') }}" class="absolute left-6 top-6 text-gray-400 hover:text-blue-600 transition flex items-center gap-1 text-sm z-10"><i data-lucide="arrow-left" class="w-4 h-4"></i>Retour</a>
    <!-- Header centré -->
    <div class="flex flex-col items-center mb-6">
        @php
            $status = strtolower($ticket->status);
            $badge = match(true) {
                str_contains($status, 'ouvert') || str_contains($status, 'new') || str_contains($status, 'nouveau') => ['text' => 'text-green-600', 'icon' => 'circle', 'bg'=>'bg-green-100'],
                str_contains($status, 'en_cours') => ['text' => 'text-yellow-500', 'icon' => 'loader', 'bg'=>'bg-yellow-100'],
                str_contains($status, 'ferme') || str_contains($status, 'cloturé') || str_contains($status, 'résolu') => ['text' => 'text-gray-500', 'icon' => 'check-circle', 'bg'=>'bg-gray-100'],
                default => ['text' => 'text-blue-600', 'icon' => 'help-circle', 'bg'=>'bg-blue-100'],
            };
        @endphp
        <span class="inline-flex items-center gap-1 px-4 py-2 rounded-full {{ $badge['bg'] }} {{ $badge['text'] }} text-base font-bold shadow mb-2">
            <i data-lucide="{{ $badge['icon'] }}" class="w-5 h-5"></i>
            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
        </span>
        <span class="text-2xl font-bold text-gray-900">{{ $ticket->titre }}</span>
        <div class="text-xs text-gray-400 mt-1">Ticket #{{ $ticket->id }}</div>
    </div>
    <!-- Infos principales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
        <!-- Utilisateur -->
        <div class="flex flex-col items-center bg-blue-50/60 rounded-xl p-4 shadow">
            <div class="mb-2">
                @if($ticket->userSimple && $ticket->userSimple->user)
                    <x-user-avatar :user="$ticket->userSimple->user" size="16" font="xl" class="w-16 h-16 rounded-full object-cover border-2 border-blue-200 shadow" />
                @else
                    <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center text-2xl font-bold text-gray-400">?</div>
                @endif
            </div>
            <div class="font-semibold text-gray-800 text-base">{{ $ticket->userSimple->user->prenom ?? '-' }} {{ $ticket->userSimple->user->nom ?? '-' }}</div>
            <div class="text-xs text-gray-500 mb-1">Utilisateur</div>
            <div class="text-xs text-gray-500 truncate max-w-[120px]" title="{{ $ticket->userSimple->user->email ?? '-' }}">{{ $ticket->userSimple->user->email ?? '-' }}</div>
            <div class="mt-1"><span class="inline-block bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full font-medium">{{ $ticket->userSimple->service->titre ?? '-' }}</span></div>
        </div>
        <!-- Technicien -->
        <div class="flex flex-col items-center bg-green-50/60 rounded-xl p-4 shadow">
            <div class="mb-2">
                @if($ticket->technicien && $ticket->technicien->user)
                    <x-user-avatar :user="$ticket->technicien->user" size="16" font="xl" class="w-16 h-16 rounded-full object-cover border-2 border-green-200 shadow" />
                @else
                    <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center text-2xl font-bold text-gray-400">?</div>
                @endif
            </div>
            <div class="font-semibold text-gray-800 text-base">{{ $ticket->technicien->user->prenom ?? '-' }} {{ $ticket->technicien->user->nom ?? '-' }}</div>
            <div class="text-xs text-gray-500 mb-1">Technicien</div>
            <div class="text-xs text-gray-500 truncate max-w-[120px]" title="{{ $ticket->technicien->user->email ?? '-' }}">{{ $ticket->technicien->user->email ?? '-' }}</div>
            <div class="mt-1"><span class="inline-block bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-medium">{{ $ticket->technicien->service->titre ?? '-' }}</span></div>
        </div>
    </div>
    <!-- Infos secondaires -->
    <div class="flex flex-wrap gap-4 justify-center mb-6">
        <div class="bg-gray-100 rounded-lg px-4 py-2 text-xs text-gray-500">Catégorie : <span class="font-semibold text-gray-700">{{ $ticket->categorie?->titre ?? '-' }}</span></div>
        <div class="bg-gray-100 rounded-lg px-4 py-2 text-xs text-gray-500">Créé le : <span class="font-semibold text-gray-700">{{ $ticket->created_at ? $ticket->created_at->format('d/m/Y H:i') : '-' }}</span></div>
    </div>
    <!-- Description -->
    <div class="mb-6">
        <div class="text-gray-700 font-semibold mb-2 flex items-center gap-2"><i data-lucide="align-left" class="w-5 h-5"></i>Description</div>
        <div class="bg-white/70 backdrop-blur rounded-xl shadow p-6 whitespace-pre-line text-gray-800 text-base">{{ $ticket->description }}</div>
    </div>
    <!-- Commentaires -->
    @if($ticket->commentaires && count($ticket->commentaires))
    <div>
        <div class="text-gray-700 font-semibold mb-2 flex items-center gap-2"><i data-lucide="message-circle" class="w-5 h-5"></i>Commentaires</div>
        <ol class="relative border-l-2 border-blue-100 ml-4 space-y-6">
            @foreach($ticket->commentaires as $comment)
                <li class="ml-6">
                    <div class="absolute -left-3 top-1.5 w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center shadow"><i data-lucide="user" class="w-3 h-3 text-blue-600"></i></div>
                    <div class="bg-white rounded-lg shadow p-3">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-semibold text-gray-700 text-sm">{{ $comment->user?->prenom }} {{ $comment->user?->nom }}</span>
                            <span class="text-xs text-gray-400">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="text-gray-700 text-sm">{{ $comment->contenu }}</div>
                    </div>
                </li>
            @endforeach
        </ol>
    </div>
    @endif

    @php
        $isTech = auth()->check() && isset($ticket->technicien) && auth()->id() === optional($ticket->technicien->user)->id;
        $hasTechComment = $ticket->commentaires && $ticket->commentaires->where('user_id', auth()->id())->count() > 0;
    @endphp
    @if($isTech && !$hasTechComment)
    <div class="mt-8 bg-white/70 backdrop-blur rounded-xl shadow p-6">
        <div class="text-gray-700 font-semibold mb-2 flex items-center gap-2"><i data-lucide="plus" class="w-5 h-5"></i>Ajouter un commentaire</div>
        <form action="{{ route('admin.tickets.comment', $ticket->id) }}" method="POST" class="space-y-4">
            @csrf
            <textarea name="contenu" rows="3" class="w-full border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200" placeholder="Votre commentaire..." required></textarea>
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Envoyer</button>
            </div>
        </form>
    </div>
    @endif
</div>
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>
@endpush
<style>
@keyframes fade-in-up { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: none; } }
.animate-fade-in-up { animation: fade-in-up 0.7s cubic-bezier(.4,2,.6,1) both; }
</style>
@endsection 