@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-8 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md ring-1 ring-gray-200 dark:ring-gray-700">
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-600 pb-2">
        Ajouter un nouveau technicien
    </h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-3 py-2 rounded mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-800 px-3 py-2 rounded mb-4 text-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('technicien.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm text-gray-700 dark:text-gray-200 mb-1">Nom</label>
            <input type="text" name="nom" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" required>
        </div>

        <div>
            <label class="block text-sm text-gray-700 dark:text-gray-200 mb-1">Prénom</label>
            <input type="text" name="prenom" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" required>
        </div>

        <div>
            <label class="block text-sm text-gray-700 dark:text-gray-200 mb-1">Email</label>
            <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" required>
        </div>

        <div>
            <label class="block text-sm text-gray-700 dark:text-gray-200 mb-1">Service</label>
            <select name="service_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" required>
                <option value="">Choisir un service </option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}">{{ $service->titre }}</option>
                @endforeach
            </select>
        </div>

        <div class="pt-2 text-right flex justify-end space-x-3">
    <a href="{{ url()->previous() }}" 
       class="bg-gray-400 hover:bg-gray-500 text-white font-medium px-5 py-2 rounded-md transition duration-150 inline-block">
       Annuler
    </a>

    <button type="submit" 
            class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2 rounded-md transition duration-150">
        Ajouter
    </button>
</div>
    </form>
</div>
@endsection