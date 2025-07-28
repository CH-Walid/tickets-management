@extends('admin.layout')

@section('title', 'Ajouter une catégorie')

@section('content')
<div class="max-w-2xl mx-auto mt-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.parametrage') }}" class="text-gray-500 hover:text-blue-600 flex items-center mr-4">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Retour
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Ajouter une catégorie</h1>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf
        
        <div>
            <label class="block text-gray-700 font-medium mb-1">Titre de la catégorie</label>
            <input type="text" name="titre" value="{{ old('titre') }}" 
                   class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200 @error('titre') border-red-500 @else border-gray-300 @enderror" 
                   required 
                   placeholder="Entrez le nom de la catégorie">
            @error('titre')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.parametrage') }}" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition">
                Annuler
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-700 text-white rounded hover:bg-blue-800 transition">
                Ajouter la catégorie
            </button>
        </div>
    </form>
</div>
@endsection
