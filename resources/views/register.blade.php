<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Inscription</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full">
  <div class="flex min-h-full items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-md space-y-6">

      <div class="text-center">
        <img class="mx-auto h-16 w-auto" src="{{ asset('images/logo.svg') }}" alt="Logo" />
        <h2 class="mt-6 text-2xl font-bold tracking-tight text-gray-900">Créer un compte</h2>
        <p class="mt-2 text-sm text-gray-600">Veuillez remplir les informations ci-dessous.</p>
      </div>

      {{-- Affichage des erreurs --}}
      @if ($errors->any())
        <div class="mb-4 text-red-600 bg-red-100 px-4 py-2 rounded">
          <ul class="text-sm list-disc pl-5">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('register') }}" method="POST" class="space-y-4">
        @csrf

        <div class="flex gap-4">
          <div class="w-1/2">
            <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
            <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" required
              class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm">
          </div>

          <div class="w-1/2">
            <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
            <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
              class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm">
          </div>

        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
          <input type="text" name="email" id="email" value="{{ old('email') }}" required
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm"
            placeholder="ex: nom.prenom@mmsp.gov.ma">
        </div>

        <div>
          <label for="service_id" class="block text-sm font-medium text-gray-700">Service</label>
          <select name="service_id" id="service_id" required
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm">
            <option value="">-- Choisir un service --</option>
            @foreach ($services as $service)
              <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                {{ $service->titre }}
              </option>
            @endforeach
          </select>
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
          <input type="password" name="password" id="password" required
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm"
            placeholder="••••••••">
        </div>

        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
          <input type="password" name="password_confirmation" id="password_confirmation" required
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm"
            placeholder="••••••••">
        </div>

        <div class="flex items-center">
          <input type="checkbox" name="agree" id="agree"
            class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
          <label for="agree" class="ml-2 block text-sm text-gray-600">
            J'accepte la <a href="#" class="text-indigo-600 hover:text-indigo-500 font-semibold">politique de confidentialité</a>.
          </label>
        </div>

        <div>
          <button type="submit"
            class="w-full flex justify-center rounded-md bg-indigo-600 py-2 px-4 text-sm font-medium text-white hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">
            S'inscrire
          </button>
        </div>

        <div class="text-center mt-4">
          <p class="text-sm text-gray-600">Déjà inscrit ?
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500 font-semibold">Se connecter</a>
          </p>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
