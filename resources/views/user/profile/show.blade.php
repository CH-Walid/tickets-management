@extends('layouts.app')

@section('title', 'Mon Profil - Système de Gestion des Incidents')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 dark:bg-gray-900">
    <div class="space-y-6">
        
        <!-- Messages -->
        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-200 border border-green-200 dark:border-green-300 text-green-700 dark:text-green-900 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-200 border border-red-200 dark:border-red-300 text-red-700 dark:text-red-900 px-4 py-3 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        <!-- Profil -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border dark:border-gray-700 shadow-sm">
            <div class="px-6 py-4 border-b dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Profil</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center space-x-6">
                    <div class="relative flex-shrink-0">
                        <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700">
                            <img src="{{ $user->profile_image_url }}"
                                 id="profile-image"
                                 alt="Photo de profil"
                                 class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="mb-4 md:mb-0">
                                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $user->full_name }}</h2>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">
                                    {{ optional(optional($user->userSimple)->service)->titre ?? 'Aucun service assigné' }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('user.profile.edit') }}" 
                                   class="flex items-center space-x-2 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                    <i class="fas fa-edit w-4 h-4"></i>
                                    <span>Modifier</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations personnelles -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border dark:border-gray-700 shadow-sm">
            <div class="px-6 py-4 border-b dark:border-gray-700 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Informations Personnelles</h2>
                <a href="{{ route('user.profile.edit') }}" 
                   class="flex items-center space-x-2 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <i class="fas fa-edit w-4 h-4"></i>
                    <span>Modifier</span>
                </a>
            </div>
            <div class="p-6">
                <div class="space-y-6 text-gray-900 dark:text-gray-100">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Prénom</label>
                        <p class="font-medium">{{ $user->prenom }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nom</label>
                        <p class="font-medium">{{ $user->nom }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Adresse Email</label>
                        <p class="font-medium">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Téléphone</label>
                        <p class="font-medium">{{ $user->phone ?: 'Non renseigné' }}</p>
                    </div>
                    @if($user->service)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Service</label>
                        <p class="font-medium">{{ $user->service->titre }}</p>
                    </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Membre depuis</label>
                        <p class="font-medium">{{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function uploadImage(input) {
    if (input.files && input.files[0]) {
        const formData = new FormData();
        formData.append('image', input.files[0]);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("user.profile.upload.image") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text) });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                document.getElementById('profile-image').src = data.image_url;
                alert(data.message);
            } else {
                alert('Erreur: ' + data.message + (data.error ? ' (' + data.error + ')' : ''));
            }
        })
        .catch(error => {
            try {
                const errorData = JSON.parse(error.message);
                if (errorData.message) {
                    alert('Erreur du serveur: ' + errorData.message);
                } else {
                    alert('Erreur inattendue lors du téléchargement de l\'image.');
                }
            } catch (e) {
                alert('Erreur réseau ou de traitement: ' + error.message);
            }
        });
    }
}
</script>
@endsection
