@extends('admin.layout')
@section('title', 'Éditer ticket')
@section('content')
<div class="max-w-2xl mx-auto p-8 bg-white/80 backdrop-blur rounded-3xl shadow-2xl space-y-8 animate-fade-in-up">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="text-gray-400 hover:text-blue-600 flex items-center gap-1 text-sm"><i data-lucide="arrow-left" class="w-4 h-4"></i>Retour</a>
        <h1 class="text-2xl font-bold text-blue-700 ml-4">Éditer le ticket #{{ $ticket->id }}</h1>
    </div>
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.tickets.update', $ticket->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Titre</label>
                <input type="text" name="titre" value="{{ old('titre', $ticket->titre) }}" class="border border-gray-200 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Statut</label>
                <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="nouveau" @if(old('status', $ticket->status)==='nouveau') selected @endif>Nouveau</option>
                    <option value="en_cours" @if(old('status', $ticket->status)==='en_cours') selected @endif>En cours</option>
                    <option value="résolu" @if(old('status', $ticket->status)==='résolu') selected @endif>Résolu</option>
                    <option value="cloturé" @if(old('status', $ticket->status)==='cloturé') selected @endif>Cloturé</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block mb-1 text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="4" class="border border-gray-200 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>{{ old('description', $ticket->description) }}</textarea>
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Utilisateur</label>
                <select name="user_simple_id" class="border border-gray-200 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    @foreach($utilisateurs as $user)
                        <option value="{{ $user->id }}" @if(old('user_simple_id', $ticket->user_simple_id)==$user->id) selected @endif>{{ $user->prenom }} {{ $user->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Technicien assigné</label>
                <select name="technicien_id" class="border border-gray-200 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Aucun</option>
                    @foreach($techniciens as $tech)
                        <option value="{{ $tech->id }}" @if(old('technicien_id', $ticket->technicien_id)==$tech->id) selected @endif>{{ $tech->prenom }} {{ $tech->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Catégorie</label>
                <select name="categorie_id" class="border border-gray-200 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Aucune</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @if(old('categorie_id', $ticket->categorie_id)==$cat->id) selected @endif>{{ $cat->titre }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex gap-2 mt-6 justify-end">
            <button type="submit" class="px-6 py-2 bg-blue-700 text-white rounded-lg shadow hover:bg-blue-800 transition font-semibold flex items-center gap-2"><i data-lucide="save" class="w-5 h-5"></i>Enregistrer</button>
            <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg shadow hover:bg-gray-300 transition font-semibold flex items-center gap-2"><i data-lucide="x" class="w-5 h-5"></i>Annuler</a>
        </div>
    </form>
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