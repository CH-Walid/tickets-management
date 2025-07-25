<!DOCTYPE html>
<html lang="fr" >
<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/png" href="{{ asset('images/logo1.png') }}">
    <title>Détail du ticket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    if (localStorage.getItem('darkMode') === 'true') {
        document.documentElement.classList.add('dark');
    }
    </script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
</head>
<body class="bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100 min-h-screen p-6">

<div class="max-w-4xl mx-auto">
    <h2 class="text-3xl font-semibold mb-6 border-b border-gray-300 dark:border-gray-700 pb-2">
        Détail du ticket
    </h2>

    <div class="bg-white rounded-lg shadow-md p-6 space-y-4 dark:bg-gray-800">
        <h3 class="text-2xl font-bold dark:text-gray-100">{{ $ticket->titre }}</h3>

        <div>
            <h4 class="font-semibold mb-1 dark:text-gray-200">Description :</h4>
            <p class="whitespace-pre-line text-gray-700 dark:text-gray-300">{{ $ticket->description }}</p>
        </div>

        <p><span class="font-semibold dark:text-gray-200">Catégorie :</span> 
            <span class="text-gray-800 dark:text-gray-300">{{ $ticket->categorie->nom ?? 'Non spécifiée' }}</span>
        </p>

        <p>
            <span class="font-semibold dark:text-gray-200">Priorité :</span>
            @php
                $prioriteColors = [
                    'faible' => 'bg-green-500 text-white',
                    'moyenne' => 'bg-yellow-400 text-gray-900',
                    'élevée' => 'bg-red-600 text-white',
                ];
                $prioriteClass = $prioriteColors[$ticket->priorite] ?? 'bg-gray-400 text-gray-900';
            @endphp
            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $prioriteClass }}">
                {{ ucfirst($ticket->priorite) }}
            </span>
        </p>

        <p>
            <span class="font-semibold dark:text-gray-200">Statut :</span>
            @php
                $statusColors = [
                    'nouveau' => 'bg-blue-600 text-white',
                    'en_cours' => 'bg-blue-400 text-gray-900',
                    'resolu' => 'bg-green-600 text-white',
                    'cloture' => 'bg-gray-600 text-gray-300',
                ];
                $statusClass = $statusColors[$ticket->status] ?? 'bg-gray-400 text-gray-900';
            @endphp
            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $statusClass }}">
                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
            </span>
        </p>

        <p>
            <span class="font-semibold dark:text-gray-200">Technicien assigné :</span>
            @if($ticket->technicien && $ticket->technicien->user)
                <span class="text-gray-800 dark:text-gray-300">
                    {{ $ticket->technicien->user->nom }} {{ $ticket->technicien->user->prenom }}
                </span>
            @else
                <span class="italic text-gray-500 dark:text-gray-400">Non assigné</span>
            @endif
        </p>

        <p>
            <span class="font-semibold dark:text-gray-200">Date de création :</span>
            <span class="text-gray-800 dark:text-gray-300">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
        </p>
        <p>
            <span class="font-semibold dark:text-gray-200">Dernière mise à jour :</span>
            <span class="text-gray-800 dark:text-gray-300">{{ $ticket->updated_at->diffForHumans() }}</span>
        </p>

        @if($ticket->piece_jointe)
            <p>
                <span class="font-semibold dark:text-gray-200">Pièce jointe :</span>
                <a href="{{ asset('storage/' . $ticket->piece_jointe) }}" target="_blank" 
                   class="text-blue-600 hover:underline dark:text-blue-400">
                   Voir le fichier
                </a>
            </p>
        @endif
    </div>

    <div class="mt-6">
        <a href="{{ route('tech.dashboard') }}" 
           class="inline-block bg-gray-200 text-gray-900 font-semibold px-5 py-2 rounded hover:bg-gray-300
                  dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600 transition">
           Retour au dashboard
        </a>
    </div>
</div>

</body>
</html>
