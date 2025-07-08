<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Connexion</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full">
  <div class="flex min-h-full items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8 bg-white p-8 rounded-xl shadow-md">
      <div class="text-center">
        <img class="mx-auto h-16 w-auto" src="{{ asset('images/logo.svg') }}" alt="Logo" />
        <h2 class="mt-6 text-2xl font-bold tracking-tight text-gray-900">
          Bienvenue sur la plateforme de support
        </h2>
        <p class="mt-2 text-sm text-gray-600">Connectez-vous pour continuer</p>
      </div>

      <form class="mt-8 space-y-6" action="#" method="POST">
        <div class="-space-y-px rounded-md shadow-sm">
          <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Adresse e-mail</label>
            <input id="email" name="email" type="email" autocomplete="email" required
              class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
              placeholder="ex: nom.prenom@mmsp.gov.ma">
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required
              class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
              placeholder="••••••••">
          </div>
        </div>

        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input id="remember_me" name="remember" type="checkbox"
              class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            <label for="remember_me" class="ml-2 block text-sm text-gray-900">
              Se souvenir de moi
            </label>
          </div>

          <div class="text-sm">
            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Mot de passe oublié ?</a>
          </div>
        </div>

        <div>
          <button type="submit"
            class="group relative flex w-full justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            Se connecter
          </button>
        </div>

        <div class="text-center mt-4">
          <p class="text-sm text-gray-600">Pas encore de compte ?
          <a href="{{ route('register') }}" class="mt-2 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-500">Créer un compte</a></p>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
