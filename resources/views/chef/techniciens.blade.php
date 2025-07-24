<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Techniciens</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo1.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-white min-h-screen">
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Liste des Techniciens (<?php echo count($techniciens); ?>)</h1>

        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-left text-sm uppercase">
                    <tr>
                        <th class="p-4">Nom</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Service</th>
                        <th class="p-4">Créé le</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300 dark:divide-gray-600">
                    @forelse($techniciens as $tech)
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <td class="p-4">{{ $tech->user->nom ?? '—' }} {{ $tech->user->prenom ?? '' }}</td>
                            <td class="p-4">{{ $tech->user->email ?? '—' }}</td>
                            <td class="p-4">{{ $tech->service->titre ?? '—' }}</td>
                            <td class="p-4">{{ $tech->user?->created_at?->format('d/m/Y') ?? 'Date inconnue' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-4 text-center text-gray-500 dark:text-gray-400">Aucun technicien trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
