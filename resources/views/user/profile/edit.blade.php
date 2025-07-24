<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo1.png') }}">
    <title>Modifier profile - Système de Gestion des Incidents</title>

    <script>
        // Appliquer la classe "dark" si darkMode est activé
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto p-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Modifier les informations personnelles</h1>
            <p class="text-gray-600 dark:text-gray-300">Mettez à jour vos informations pour maintenir votre profil à jour.</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 dark:bg-red-200 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-200 border border-green-200 text-green-700 px-4 py-3 rounded-md mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Informations personnelles</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('user.profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Photo de profil</label>
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-200">
                                <img src="{{ $user->profile_image_url }}" alt="Photo actuelle" id="profile-image" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <input type="file" id="profile-image-upload" accept="image/*" onchange="uploadImage(this)"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF jusqu'à 2MB</p>
                            </div>
                            @if($user->img)
                                <button type="button" onclick="confirmDeleteImage()" class="text-red-600 hover:text-red-800 text-sm">
                                    Supprimer
                                </button>
                            @endif
                        </div>
                    </div>

                    @php
                        $fields = [
                            ['id' => 'prenom', 'label' => 'Prénom', 'required' => true],
                            ['id' => 'nom', 'label' => 'Nom', 'required' => true],
                            ['id' => 'email', 'label' => 'Adresse e-mail', 'required' => true],
                            ['id' => 'phone', 'label' => 'Téléphone', 'required' => false],
                        ];
                    @endphp

                    @foreach($fields as $field)
                        <div class="space-y-2">
                            <label for="{{ $field['id'] }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                {{ $field['label'] }} @if($field['required'])<span class="text-red-500">*</span>@endif
                            </label>
                            <input type="{{ $field['id'] === 'email' ? 'email' : ($field['id'] === 'phone' ? 'tel' : 'text') }}"
                                   id="{{ $field['id'] }}"
                                   name="{{ $field['id'] }}"
                                   value="{{ old($field['id'], $user->{$field['id']}) }}"
                                   @if($field['required']) required @endif
                                   class="w-full h-12 px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                        </div>
                    @endforeach

                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Nouveau mot de passe
                        </label>
                        <input type="password" id="password" name="password"
                               class="w-full h-12 px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Confirmer le mot de passe
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="w-full h-12 px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                    </div>

                    <div class="flex justify-end gap-4 pt-6 border-t dark:border-gray-700">
                        <a href="{{ route('user.profile.show') }}"
                           class="px-8 py-3 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                            Annuler
                        </a>
                        <button type="submit"
                                class="px-8 py-3 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form id="delete-image-form" action="{{ route('user.profile.delete-image') }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

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
                .then(response => response.ok ? response.json() : Promise.reject(response))
                .then(data => {
                    if (data.success) {
                        document.getElementById('profile-image').src = data.image_url;
                        alert(data.message);
                    } else {
                        alert('Erreur : ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Une erreur est survenue lors du téléchargement de l\'image.');
                });
            }
        }

        function confirmDeleteImage() {
            if (confirm('Êtes-vous sûr de vouloir supprimer votre photo de profil ?')) {
                document.getElementById('delete-image-form').submit();
            }
        }
    </script>
</body>
</html>
