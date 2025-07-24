
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo1.png') }}">
    <title>Créer un Ticket - Système de Gestion des Incidents</title>
    <script>
    if (localStorage.getItem('darkMode') === 'true') {
        document.documentElement.classList.add('dark');
    }
    </script>
    <!-- Tailwind & Lucide -->
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
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .5; }
        }
        .error-message {
            background-color: #fee2e2;
            color: #dc2626;
            border: 1px solid #ef4444;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-top: 1rem;
            text-align: center;
        }
        .dark .error-message {
            background-color: #7f1d1d;
            color: #fee2e2;
            border-color: #f87171;
        }
    </style>
</head>
<body class="min-h-screen bg-blue-50 dark:bg-gray-900 p-6 font-sans text-gray-800 dark:text-gray-100 antialiased">
    <div id="mainPage" class="max-w-4xl mx-auto">
        <div class="mb-10">
            <div class="flex items-center justify-between mb-8">
                <a href="{{route('user.dashboard')}}" id="backBtn" class="flex items-center gap-2 px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-800 rounded-md transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Retour au Home
                </a>
                <div class="flex items-center gap-6 text-sm text-gray-600 dark:text-gray-300">
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
            <div class="text-center mb-8">
                <h1 class="text-5xl font-extrabold text-gray-900 dark:text-white mb-3">Déclaration d'Incident</h1>
                <p class="text-lg text-gray-600 dark:text-gray-300">Système de Gestion des Incidents</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg rounded-xl">
            <div class="p-8">
                <form id="incidentForm" method="POST" action="{{ route('incident.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div id="errorMessage" class="error-message hidden"></div>

                    <div class="space-y-2">
                        <label for="titre" class="block text-gray-700 dark:text-gray-300 font-medium">Titre de l'incident *</label>
                        <input type="text" id="titre" name="titre" placeholder="Décrivez brièvement le problème"
                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-gray-100 placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-blue-400 h-12 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 transition-colors" required />
                    </div>

                    <div class="space-y-2">
                        <label for="description" class="block text-gray-700 dark:text-gray-300 font-medium">Description détaillée *</label>
                        <textarea id="description" name="description" placeholder="Expliquez le problème en détail..."
                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-gray-100 placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-blue-400 min-h-[140px] resize-y px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 transition-colors" required></textarea>
                    </div>

                    <div class="space-y-2">
                        <label for="categorie" class="block text-gray-700 dark:text-gray-300 font-medium">Catégorie</label>
                        <div class="relative">
                            <select name="categorie_id" id="categorie"
                                class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-black dark:text-white h-12 px-4 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-colors pr-10">
                                <option value="">-- Sélectionner une catégorie --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->titre }}</option>
                                @endforeach
                                @if($autre)
                                    <option value="{{ $autre->id }}">Autre</option>
                                @endif
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </div>

                    <div id="nouvelleCategorieContainer" class="space-y-2 hidden">
                        <label for="nouvelle_categorie" class="block text-gray-700 dark:text-gray-300 font-medium">Nouvelle catégorie</label>
                        <input type="text" name="nouvelle_categorie" id="nouvelle_categorie"
                            placeholder="Entrez une nouvelle catégorie"
                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-gray-100 placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-blue-400 h-12 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 transition-colors" />
                    </div>

                    <div class="space-y-2">
                        <label for="priorite" class="block text-gray-700 dark:text-gray-300 font-medium">Priorité</label>
                        <div class="relative">
                            <select name="priorite" id="priorite"
                                class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-black dark:text-white h-12 px-4 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-colors pr-10">
                                <option value="">-- Sélectionner la priorité --</option>
                                <option value="basse">Basse</option>
                                <option value="normale">Normale</option>
                                <option value="urgente">Urgente</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-gray-700 dark:text-gray-300 font-medium">Pièce jointe</label>
                        <div id="fileUploadArea" class="border-2 border-dashed border-blue-200 dark:border-blue-400 rounded-lg p-6 text-center hover:border-blue-400 transition-colors cursor-pointer">
                            <i data-lucide="upload" class="w-8 h-8 text-blue-500 mx-auto mb-3"></i>
                            <label for="fileInput" class="cursor-pointer text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white font-medium">
                                Cliquer pour ajouter un fichier
                            </label>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Formats acceptés : PNG, JPG, JPEG, PDF, DOC, DOCX</p>
                            <input type="file" id="fileInput" name="piece_jointe" class="hidden" accept=".png,.jpg,.jpeg,.pdf,.doc,.docx" />
                        </div>
                        <div id="fileDisplay" class="hidden flex items-center justify-between p-3 bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">
                            <div class="flex items-center gap-2">
                                <i data-lucide="file-text" class="w-5 h-5 text-gray-700 dark:text-gray-300"></i>
                                <span id="fileName" class="text-gray-800 dark:text-gray-100 text-sm font-medium"></span>
                            </div>
                            <button type="button" id="removeFile" class="text-red-500 hover:text-red-700 hover:bg-red-100 dark:hover:bg-red-800 p-1 rounded-md transition-colors">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <button type="submit" id="submitBtn"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white h-12 rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                            <span id="submitText">Soumettre la Demande</span>
                        </button>
                        <button type="button" id="cancelBtn"
                            class="px-6 text-gray-600 hover:text-gray-800 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-800 h-12 rounded-lg font-medium transition-colors">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="successPage" class="hidden min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md text-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg rounded-xl p-8">
            <div class="pt-4 pb-4 px-6">
                <div class="w-24 h-24 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse">
                    <i data-lucide="check-circle-2" class="w-12 h-12 text-white"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Incident Déclaré !</h3>
                <p class="text-gray-700 dark:text-gray-300 mb-8">Votre demande a été enregistrée avec succès.</p>
                <button type="button" id="newIncidentBtn"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition-colors">
                    Créer un autre incident
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
        const newIncidentBtn = document.getElementById('newIncidentBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const titreInput = document.getElementById('titre');
        const descriptionInput = document.getElementById('description');
        const errorMessageDiv = document.getElementById('errorMessage'); // Nouveau: div pour les messages d'erreur

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
                lucide.createIcons(); // Re-initialiser les icônes si nécessaire après changement de visibilité
            }
        });

        removeFileBtn.addEventListener('click', function() {
            selectedFile = null;
            fileInput.value = ''; // Réinitialise l'input de type fichier
            fileUploadArea.classList.remove('hidden');
            fileDisplay.classList.add('hidden');
            lucide.createIcons(); // Re-initialiser les icônes si nécessaire
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
                submitText.textContent = 'Soumettre la Demande';
            }
        }

        // Fonction pour afficher un message d'erreur
        function showErrorMessage(message) {
            errorMessageDiv.textContent = message;
            errorMessageDiv.classList.remove('hidden');
        }

        // Fonction pour masquer le message d'erreur
        function hideErrorMessage() {
            errorMessageDiv.classList.add('hidden');
            errorMessageDiv.textContent = '';
        }

        // Écouteurs pour la validation
        titreInput.addEventListener('input', updateSubmitButton);
        descriptionInput.addEventListener('input', updateSubmitButton);
        titreInput.addEventListener('input', hideErrorMessage); // Masquer l'erreur quand l'utilisateur tape
        descriptionInput.addEventListener('input', hideErrorMessage); // Masquer l'erreur quand l'utilisateur tape


        // Soumission du formulaire via AJAX
        form.addEventListener('submit', async function(e) {
            e.preventDefault(); // Empêche la soumission par défaut du formulaire
            hideErrorMessage(); // Masquer tout message d'erreur précédent

            if (!validateForm() || isSubmitting) {
                if (!validateForm()) {
                    showErrorMessage("Veuillez remplir tous les champs obligatoires (Titre et Description).");
                }
                return;
            }

            isSubmitting = true;
            updateSubmitButton(); // Met à jour le bouton avec l'état de soumission

            const formData = new FormData(form); // Crée un objet FormData à partir du formulaire

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest', // Indique que c'est une requête AJAX
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '', // Assurez-vous d'avoir une balise meta pour le token CSRF
                    },
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    console.error('Erreur de soumission:', errorData);

                    let errorMessage = 'Une erreur est survenue lors de la soumission.';
                    if (response.status === 422 && errorData.errors) {
                        // Si c'est une erreur de validation Laravel (statut 422)
                        errorMessage = 'Veuillez corriger les erreurs suivantes :<br>';
                        for (const field in errorData.errors) {
                            errorMessage += `- ${errorData.errors[field].join(', ')}<br>`;
                        }
                    } else if (errorData.message) {
                        errorMessage = errorData.message;
                    }
                    showErrorMessage(errorMessage); // Afficher le message d'erreur
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
                    resetForm();
                    successPage.classList.add('hidden');
                    mainPage.classList.remove('hidden');
                    lucide.createIcons();
                }, 3000);

            } catch (error) {
                console.error('Erreur réseau ou inattendue:', error);
                showErrorMessage('Une erreur inattendue est survenue. Veuillez réessayer.'); // Message pour les erreurs réseau
                isSubmitting = false;
                updateSubmitButton();
            }
        });

        // Réinitialisation du formulaire
        function resetForm() {
            form.reset();
            selectedFile = null;
            isSubmitting = false;
            fileInput.value = ''; // Assurez-vous que l'input de type fichier est bien vidé
            fileUploadArea.classList.remove('hidden');
            fileDisplay.classList.add('hidden');
            hideErrorMessage(); // Masquer le message d'erreur lors de la réinitialisation
            nouvelleCategorieContainer.classList.add('hidden');
            updateSubmitButton(); // Met à jour l'état du bouton après réinitialisation
            lucide.createIcons();
        }

        // Bouton "Créer un autre incident"
        newIncidentBtn.addEventListener('click', function() {
            resetForm();
            successPage.classList.add('hidden');
            mainPage.classList.remove('hidden');
            lucide.createIcons();
        });

        // Bouton "Annuler"
        cancelBtn.addEventListener('click', function() {
            if (confirm('Êtes-vous sûr de vouloir annuler ? Toutes les données saisies seront perdues.')) {
                resetForm();
                // window.location.href = "{{ route('user.dashboard') }}"; 
            }
        });

        // Initialisation
        updateTime();
        setInterval(updateTime, 1000);
        updateSubmitButton(); // Appel initial pour définir l'état du bouton
        lucide.createIcons();

        // Gestion du champ "Nouvelle catégorie" si "Autre" est sélectionné

    const categorieSelect = document.getElementById('categorie');
    const nouvelleCategorieContainer = document.getElementById('nouvelleCategorieContainer');
    const idAutre = "{{ $autre->id ?? '' }}";

    categorieSelect.addEventListener('change', function () {
        if (this.value === idAutre) {
            nouvelleCategorieContainer.classList.remove('hidden');
        } else {
            nouvelleCategorieContainer.classList.add('hidden');
        }
    });


    </script>
    
</body>
</html>
