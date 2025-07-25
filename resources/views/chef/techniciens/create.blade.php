@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-8 bg-white dark:bg-gray-800 p-8 rounded-xl shadow-md ring-1 ring-gray-200 dark:ring-gray-700">
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6 border-b border-gray-200 dark:border-gray-600 pb-3">
        Ajouter un nouveau technicien
    </h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded mb-6 text-sm">
            <ul class="list-disc pl-6 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('chef.techniciens.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div>
            <label for="nom" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Nom</label>
            <input 
                type="text" 
                name="nom" 
                id="nom"
                required 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            >
        </div>

        <div>
            <label for="prenom" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Prénom</label>
            <input 
                type="text" 
                name="prenom" 
                id="prenom"
                required 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            >
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Email</label>
            <input 
                type="email" 
                name="email" 
                id="email"
                required 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            >
        </div>

        <div>
            <label for="service_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Service</label>
            <select 
                name="service_id" 
                id="service_id"
                required 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            >
                <option value="" disabled selected>Choisir un service</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}">{{ $service->titre }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="img" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Photo (facultatif)</label>
            <input
                type="file"
                name="img"
                id="img"
                accept="image/*"
                class="block w-full text-sm text-gray-500
                       file:mr-4 file:py-2 file:px-4
                       file:rounded-md file:border-0
                       file:text-sm file:font-semibold
                       file:bg-indigo-50 file:text-indigo-700
                       hover:file:bg-indigo-100
                       dark:file:bg-gray-700 dark:file:text-gray-200 dark:hover:file:bg-gray-600
                       cursor-pointer
                       focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
            >
        </div>

        <div class="flex justify-end space-x-4 pt-4">
            <a href="{{ url()->previous() }}" 
               class="inline-block px-6 py-2 bg-gray-400 hover:bg-gray-500 text-white font-medium rounded-md transition duration-150">
               Annuler
            </a>

            <button 
                type="submit" 
                class="inline-block px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150"
            >
                Ajouter
            </button>
        </div>
    </form>
</div>
@endsection
