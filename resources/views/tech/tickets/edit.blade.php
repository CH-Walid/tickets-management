<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le Ticket</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

    <div class="max-w-3xl mx-auto p-6 mt-10 bg-white shadow rounded">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Modifier le Ticket</h1>

        <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Titre (affichage seulement) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Titre</label>
                <p class="mt-1 text-gray-800 font-semibold">{{ $ticket->titre }}</p>
            </div>

            {{-- Catégorie (affichage seulement) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Catégorie</label>
                <p class="mt-1 text-gray-800">{{ $ticket->categorie->titre }}</p>
            </div>

            {{-- Description (affichage seulement) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <p class="mt-1 text-gray-700">{{ $ticket->description }}</p>
            </div>

            {{-- Statut (modifiable) --}}
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                <select name="status" id="status"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="nouveau" {{ $ticket->status == 'nouveau' ? 'selected' : '' }}>Nouveau</option>
                    <option value="en_cours" {{ $ticket->status == 'en_cours' ? 'selected' : '' }}>En cours</option>
                    <option value="résolu" {{ $ticket->status == 'résolu' ? 'selected' : '' }}>Résolu</option>
                    <option value="cloturé" {{ $ticket->status == 'cloturé' ? 'selected' : '' }}>Clôturé</option>
                </select>
            </div>

            {{-- Boutons --}}
            <div class="flex justify-end gap-4">
                <a href="{{ route('tickets.show', $ticket->id) }}"
                   class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Annuler</a>
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Enregistrer</button>
            </div>
        </form>
    </div>

</body>
</html>
