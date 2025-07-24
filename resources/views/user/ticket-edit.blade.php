<!DOCTYPE html>
<html lang="fr" class="">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/png" href="{{ asset('images/logo1.png') }}" />
    <title>Modifier le Ticket - Système de Gestion des Incidents</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script>
    if (localStorage.getItem('darkMode') === 'true') {
        document.documentElement.classList.add('dark');
    }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <style>
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%,
            100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        /* Style pour le message d'erreur */
        .error-message {
            background-color: #fee2e2; /* red-100 */
            color: #dc2626; /* red-600 */
            border: 1px solid #ef4444; /* red-500 */
            padding: 1rem;
            border-radius: 0.5rem;
            margin-top: 1rem;
            text-align: center;
        }
    </style>
    <script>
      // Appliquer automatiquement la classe dark si stockée dans localStorage
      if (localStorage.getItem('theme') === 'dark') {
          document.documentElement.classList.add('dark');
      }
    </script>
</head>
<body
    class="min-h-screen bg-blue-50 dark:bg-gray-900 p-6 font-sans text-gray-800 dark:text-gray-200 antialiased"
>
    <!-- Page principale -->
    <div id="mainPage" class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-10">
            <div
                class="flex items-center justify-between mb-8 text-gray-600 dark:text-gray-300"
            >
                <a
                    href="{{ route('user.dashboard') }}"
                    id="backBtn"
                    class="flex items-center gap-2 px-4 py-2 hover:text-gray-800 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md transition-colors"
                >
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Retour au Home
                </a>
                <div class="flex items-center gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                        <span id="currentDate"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                        <span id="currentTime"></span>
                    </div>
                </div>
            </div>
            <!-- Titre principal -->
            <div class="text-center mb-8">
                <h1
                    class="text-5xl font-extrabold text-gray-900 dark:text-white mb-3"
                >
                    Modifier le Ticket
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    Système de Gestion des Incidents
                </p>
            </div>
        </div>
        <!-- Formulaire -->
        <div
            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg rounded-xl"
        >
            <div class="p-8">
                <form
                    id="incidentForm"
                    method="POST"
                    action="{{ route('incident.update', $ticket->id) }}"
                    enctype="multipart/form-data"
                    class="space-y-6"
                >
                    <input
                        type="hidden"
                        name="remove_piece_jointe"
                        id="remove_piece_jointe"
                        value="0"
                    />
                    @csrf
                    @method('PUT')

                    <!-- Message d'erreur global -->
                    <div
                        id="errorMessage"
                        class="error-message hidden bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 border border-red-500 dark:border-red-600"
                    ></div>
                    <!-- Titre -->
                    <div class="space-y-2">
                        <label
                            for="titre"
                            class="block text-gray-700 dark:text-gray-300 font-medium"
                            >Titre de l'incident *</label
                        >
                        <input
                            type="text"
                            id="titre"
                            name="titre"
                            placeholder="Décrivez brièvement le problème"
                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-gray-100 placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-blue-400 h-12 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500 transition-colors"
                            value="{{ old('titre', $ticket->titre) }}"
                            required
                        />
                    </div>
                    <!-- Description -->
                    <div class="space-y-2">
                        <label
                            for="description"
                            class="block text-gray-700 dark:text-gray-300 font-medium"
                            >Description détaillée *</label
                        >
                        <textarea
                            id="description"
                            name="description"
                            placeholder="Expliquez le problème en détail..."
                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-gray-100 placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-blue-400 min-h-[140px] resize-y px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500 transition-colors"
                            required
                        >{{ old('description', $ticket->description) }}</textarea>
                    </div>
                    <!-- Catégorie -->
                    <div class="space-y-2">
                        <label
                            for="categorie"
                            class="block text-gray-700 dark:text-gray-300 font-medium"
                            >Catégorie</label
                        >
                        <div class="relative">
                            <select
                                name="categorie_id"
                                id="categorie"
                                class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-black dark:text-gray-100 h-12 px-4 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500 focus:border-blue-400 transition-colors pr-10"
                            >
                                @foreach ($categories as $categorie)
                                <option
                                    value="{{ $categorie->id }}"
                                    @if (!$isCustomCategorie && $ticket->categorie_id == $categorie->id)
                                    selected
                                    @endif
                                >
                                    {{ $categorie->titre }}
                                </option>
                                @endforeach

                                <option
                                    value="{{ $autre->id ?? 7 }}"
                                    @if ($isCustomCategorie)
                                    selected
                                    @elseif ($ticket->categorie_id == ($autre->id ?? 7))
                                    selected
                                    @endif
                                >
                                    Autre
                                </option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300"
                            >
                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </div>
                    <!-- Champ pour nouvelle catégorie, affiché uniquement si "Autre" est sélectionné -->
                    <div
                        id="nouvelleCategorieContainer"
                        class="space-y-2 {{ $isCustomCategorie ? '' : 'hidden' }}"
                    >
                        <label
                            for="nouvelle_categorie"
                            class="block text-gray-700 dark:text-gray-300 font-medium"
                            >Nouvelle catégorie</label
                        >
                        <input
                            type="text"
                            name="nouvelle_categorie"
                            id="nouvelle_categorie"
                            placeholder="Entrez une nouvelle catégorie"
                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-gray-100 placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-blue-400 h-12 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500 transition-colors"
                            value="{{ old('nouvelle_categorie', $isCustomCategorie ? $ticket->categorie->titre : '') }}"
                        />
                    </div>
                    <!-- Priorité -->
                    <div class="space-y-2">
                        <label
                            for="priorite"
                            class="block text-gray-700 dark:text-gray-300 font-medium"
                            >Priorité</label
                        >
                        <div class="relative">
                            <select
                                name="priorite"
                                id="priorite"
                                class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-black dark:text-gray-100 h-12 px-4 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500 focus:border-blue-400 transition-colors pr-10"
                            >
                                <option value="">-- Sélectionner la priorité --</option>
                                <option
                                    value="basse"
                                    {{ $ticket->priorite == 'basse' ? 'selected' : '' }}
                                >
                                    Basse
                                </option>
                                <option
                                    value="normale"
                                    {{ $ticket->priorite == 'normale' ? 'selected' : '' }}
                                >
                                    Normale
                                </option>
                                <option
                                    value="urgente"
                                    {{ $ticket->priorite == 'urgente' ? 'selected' : '' }}
                                >
                                    Urgente
                                </option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300"
                            >
                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </div>
                    <!-- Pièce jointe -->
                    <div class="space-y-2">
                        <label
                            class="block text-gray-700 dark:text-gray-300 font-medium"
                            >Pièce jointe</label
                        >
                        <div
                            id="fileUploadArea"
                            class="border-2 border-dashed border-blue-200 dark:border-blue-700 rounded-lg p-6 text-center hover:border-blue-400 dark:hover:border-blue-500 transition-colors cursor-pointer {{ $ticket->piece_jointe ? 'hidden' : '' }}"
                        >
                            <i
                                data-lucide="upload"
                                class="w-8 h-8 text-blue-500 dark:text-blue-400 mx-auto mb-3"
                            ></i>
                            <label
                                for="fileInput"
                                class="cursor-pointer text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white font-medium"
                                >Cliquer pour ajouter un fichier</label
                            >
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Formats acceptés : PNG, JPG, JPEG, PDF, DOC, DOCX
                            </p>
                            <input
                                type="file"
                                id="fileInput"
                                name="piece_jointe"
                                class="hidden"
                                accept=".png,.jpg,.jpeg,.pdf,.doc,.docx"
                            />
                        </div>
                        <div
                            id="fileDisplay"
                            class="flex items-center justify-between p-3 bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg {{ $ticket->piece_jointe ? '' : 'hidden' }}"
                        >
                            <div class="flex items-center gap-2">
                                <i
                                    data-lucide="file-text"
                                    class="w-5 h-5 text-gray-700 dark:text-gray-300"
                                ></i>
                                <span
                                    id="fileName"
                                    class="text-gray-800 dark:text-gray-100 text-sm font-medium"
                                >
                                    {{ $ticket->piece_jointe ? basename($ticket->piece_jointe) : '' }}
                                </span>
                            </div>
                            <button
                                type="button"
                                id="removeFile"
                                class="text-red-500 hover:text-red-700 hover:bg-red-100 dark:hover:bg-red-800 p-1 rounded-md transition-colors"
                            >
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                        @if($ticket->piece_jointe)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Fichier actuel :
                            <a
                                href="{{ asset('storage/' . $ticket->piece_jointe) }}"
                                target="_blank"
                                class="underline text-blue-600 dark:text-blue-400"
                                >{{ basename($ticket->piece_jointe) }}</a
                            >
                        </p>
                        @endif
                    </div>
                    <!-- Boutons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <button
                            type="submit"
                            id="submitBtn"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white h-12 rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                        >
                            <span id="submitText">Enregistrer les modifications</span>
                        </button>
                        <button
                            type="button"
                            id="cancelBtn"
                            class="px-6 text-gray-600 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 h-12 rounded-lg font-medium transition-colors"
                        >
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Page de succès -->
    <div
        id="successPage"
        class="hidden min-h-screen flex items-center justify-center bg-white dark:bg-gray-800"
    >
        <div
            class="w-full max-w-md text-center border border-gray-200 dark:border-gray-700 shadow-lg rounded-xl p-8 bg-white dark:bg-gray-800"
        >
            <div class="pt-4 pb-4 px-6">
                <div
                    class="w-24 h-24 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse"
                >
                    <i data-lucide="check-circle-2" class="w-12 h-12 text-white"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    Ticket Modifié !
                </h3>
                <p class="text-gray-700 dark:text-gray-300 mb-8">
                    Vos modifications ont été enregistrées avec succès.
                </p>
                <button
                    type="button"
                    id="newIncidentBtn"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition-colors"
                >
                    Retour au Dashboard
                </button>
            </div>
        </div>
    </div>
    <script>
        // Initialisation des icônes Lucide
        lucide.createIcons();

        // Variables globales
        let selectedFile = null;
        let isSubmitting = false;

        // Éléments DOM
        const mainPage = document.getElementById('mainPage');
        const successPage = document.getElementById('successPage');
        const form = document.getElementById('incidentForm');
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        const fileInput = document.getElementById('fileInput');
        const fileUploadArea = document.getElementById('fileUploadArea');
        const fileDisplay = document.getElementById('fileDisplay');
        const fileName = document.getElementById('fileName');
        const removeFileBtn = document.getElementById('removeFile');
        const newIncidentBtn = document.getElementById('newIncidentBtn'); // Used for "Retour au Dashboard" on success page
        const cancelBtn = document.getElementById('cancelBtn');
        const titreInput = document.getElementById('titre');
        const descriptionInput = document.getElementById('description');
        const errorMessageDiv = document.getElementById('errorMessage');
        const categorieSelect = document.getElementById('categorie');
        const nouvelleCategorieContainer = document.getElementById('nouvelleCategorieContainer');

        // Initialisation du fichier joint existant
        const initialFileName = fileName.textContent.trim();
        if (initialFileName) {
            fileUploadArea.classList.add('hidden');
            fileDisplay.classList.remove('hidden');
            // No need to set selectedFile here, as it's for new uploads.
            // The form will handle existing file via its name attribute.
        } else {
            fileUploadArea.classList.remove('hidden');
            fileDisplay.classList.add('hidden');
        }


        // Mise à jour de l'heure
        function updateTime() {
            const now = new Date();
            document.getElementById('currentDate').textContent = now.toLocaleDateString('fr-FR');
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('fr-FR');
        }

        // Gestion des fichiers
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                selectedFile = file;
                fileName.textContent = file.name;
                fileUploadArea.classList.add('hidden');
                fileDisplay.classList.remove('hidden');
                lucide.createIcons();
            }
        });

            removeFileBtn.addEventListener('click', function() {
        selectedFile = null;
        fileInput.value = ''; // Reset input file
        fileName.textContent = '';
        fileUploadArea.classList.remove('hidden');
        fileDisplay.classList.add('hidden');
        // Indiquer au serveur que la pièce jointe doit être supprimée
        document.getElementById('remove_piece_jointe').value = '1';
        lucide.createIcons();
    });

        // Validation du formulaire côté client
        function validateForm() {
            const titre = titreInput.value.trim();
            const description = descriptionInput.value.trim();
            return titre && description;
        }

        function updateSubmitButton() {
            const isValid = validateForm();
            submitBtn.disabled = !isValid || isSubmitting;
            if (isSubmitting) {
                submitText.innerHTML = `<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></div> Traitement en cours...`;
            } else {
                submitText.textContent = 'Enregistrer les modifications';
            }
        }

        // Fonction pour afficher un message d'erreur
        function showErrorMessage(message) {
            errorMessageDiv.innerHTML = message; // Use innerHTML for potential <br> tags
            errorMessageDiv.classList.remove('hidden');
        }

        // Fonction pour masquer le message d'erreur
        function hideErrorMessage() {
            errorMessageDiv.classList.add('hidden');
            errorMessageDiv.innerHTML = '';
        }

        // Écouteurs pour la validation
        titreInput.addEventListener('input', updateSubmitButton);
        descriptionInput.addEventListener('input', updateSubmitButton);
        titreInput.addEventListener('input', hideErrorMessage);
        descriptionInput.addEventListener('input', hideErrorMessage);

        // Soumission du formulaire via AJAX
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            hideErrorMessage();

            if (!validateForm() || isSubmitting) {
                if (!validateForm()) {
                    showErrorMessage("Veuillez remplir tous les champs obligatoires (Titre et Description).");
                }
                return;
            }

            isSubmitting = true;
            updateSubmitButton();

            const formData = new FormData(form);

            // If a new file is selected, it's already in formData.
            // If no new file is selected but there was an existing one,
            // we need to explicitly tell the server not to clear it if the input is empty.
            // This is typically handled server-side by checking if 'piece_jointe' is present.
            // If the user removed the existing file, 'selectedFile' would be null,
            // and 'fileInput.value' would be empty, effectively removing it on submit.

            try {
                const response = await fetch(form.action, {
                    method: 'POST', // Laravel handles PUT via _method field
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '',
                    },
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    console.error('Erreur de soumission:', errorData);
                    let errorMessage = 'Une erreur est survenue lors de la soumission.';
                    if (response.status === 422 && errorData.errors) {
                        errorMessage = 'Veuillez corriger les erreurs suivantes :<br>';
                        for (const field in errorData.errors) {
                            errorMessage += `- ${errorData.errors[field].join(', ')}<br>`;
                        }
                    } else if (errorData.message) {
                        errorMessage = errorData.message;
                    }
                    showErrorMessage(errorMessage);
                    isSubmitting = false;
                    updateSubmitButton();
                    return;
                }

                const data = await response.json();
                console.log('Succès:', data);

                // Afficher la page de succès
                mainPage.classList.add('hidden');
                successPage.classList.remove('hidden');
                lucide.createIcons();

                // Réinitialiser après 3 secondes et revenir au formulaire principal
                setTimeout(() => {
                    // For edit ticket, we might want to redirect to dashboard or show a success message
                    // and stay on the page, or redirect to the ticket details.
                    // For consistency with "create ticket", we'll go back to dashboard.
                    window.location.href = "{{ route('user.dashboard') }}";
                }, 3000);

            } catch (error) {
                console.error('Erreur réseau ou inattendue:', error);
                showErrorMessage('Une erreur inattendue est survenue. Veuillez réessayer.');
                isSubmitting = false;
                updateSubmitButton();
            }
        });

        // Réinitialisation du formulaire (pour le bouton Annuler ou après succès si on reste sur la page)
        function resetForm() {
            form.reset();
            selectedFile = null;
            isSubmitting = false;
            fileInput.value = '';
            fileName.textContent = ''; // Clear displayed file name
            fileUploadArea.classList.remove('hidden');
            fileDisplay.classList.add('hidden');
            hideErrorMessage();
            updateSubmitButton();
            lucide.createIcons();
            // Reset "Nouvelle catégorie" visibility
            nouvelleCategorieContainer.classList.add('hidden');
        }

        // Bouton "Retour au Dashboard" sur la page de succès
        newIncidentBtn.addEventListener('click', function() {
            window.location.href = "{{ route('user.dashboard') }}";
        });

        // Bouton "Annuler"
        cancelBtn.addEventListener('click', function() {
            if (confirm('Êtes-vous sûr de vouloir annuler ? Toutes les modifications non enregistrées seront perdues.')) {
                window.location.href = "{{ route('user.dashboard') }}"; // Redirect to dashboard on cancel
            }
        });

        // Initialisation
        updateTime();
        setInterval(updateTime, 1000);
        updateSubmitButton(); // Appel initial pour définir l'état du bouton
        lucide.createIcons();

        // Gestion du champ "Nouvelle catégorie" si "Autre" est sélectionné
                const idCategorieAutre = "{{ $autre->id ?? 7 }}";

        categorieSelect.addEventListener('change', function () {
            if (this.value === idCategorieAutre) {
                nouvelleCategorieContainer.classList.remove('hidden');
            } else {
                nouvelleCategorieContainer.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
