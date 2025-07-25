@extends('admin.layout')
@section('title', 'Ajouter un service')
@section('content')
<div class="max-w-xl mx-auto p-8 bg-white/80 backdrop-blur rounded-3xl shadow-2xl animate-fade-in-up">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.services.index') }}" class="text-gray-400 hover:text-blue-600 flex items-center gap-1 text-sm"><i data-lucide="arrow-left" class="w-4 h-4"></i>Retour</a>
        <h2 class="text-2xl font-bold text-blue-700 ml-4">Ajouter un service</h2>
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
    <form action="{{ route('admin.services.store') }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700">Titre</label>
            <input type="text" name="titre" value="{{ old('titre') }}" class="border border-gray-200 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
        </div>
        <div class="flex gap-2 mt-6 justify-end">
            <button type="submit" class="px-6 py-2 bg-blue-700 text-white rounded-lg shadow hover:bg-blue-800 transition font-semibold flex items-center gap-2"><i data-lucide="save" class="w-5 h-5"></i>Enregistrer</button>
            <a href="{{ route('admin.services.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg shadow hover:bg-gray-300 transition font-semibold flex items-center gap-2"><i data-lucide="x" class="w-5 h-5"></i>Annuler</a>
        </div>
    </form>
</div>
@push('scripts')
<script>document.addEventListener('DOMContentLoaded', function() { if (window.lucide) { lucide.createIcons(); } });</script>
@endpush
<style>@keyframes fade-in-up { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: none; } } .animate-fade-in-up { animation: fade-in-up 0.7s cubic-bezier(.4,2,.6,1) both; }</style>
@endsection 