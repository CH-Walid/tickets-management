@extends('layouts.app')

@section('title', 'Modifier Technicien')

@section('content')
<div class="p-6 dark:bg-gray-900 min-h-screen">
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Modifier Technicien</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('chef.techniciens.update', $technicien->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="nom" class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Nom</label>
                <input type="text" name="nom" id="nom" value="{{ old('nom', $technicien->user->nom) }}" required
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            <div>
                <label for="prenom" class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Prénom</label>
                <input type="text" name="prenom" id="prenom" value="{{ old('prenom', $technicien->user->prenom) }}" required
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            <div>
                <label for="email" class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $technicien->user->email) }}" required
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            <div>
                <label for="service_id" class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Service</label>
                <select name="service_id" id="service_id" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">-- Aucun --</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}" {{ old('service_id', $technicien->service_id) == $service->id ? 'selected' : '' }}>
                            {{ $service->titre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('chef.techniciens.index') }}" class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 rounded-md hover:bg-blue-700 text-white">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
