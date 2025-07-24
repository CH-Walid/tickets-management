<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Connexion</title>
  @vite('resources/css/app.css')
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

      {{-- Message de succès après inscription --}}
      @if(session('success'))
        <div class="text-sm text-green-600 bg-green-100 px-4 py-2 rounded">
          {{ session('success') }}
        </div>
      @endif

      {{-- Affichage des erreurs --}}
      @if ($errors->any())
        <div class="text-sm text-red-600 bg-red-100 px-4 py-2 rounded">
          <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
        @csrf

        <div class="-space-y-px rounded-md shadow-sm">
          <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Adresse e-mail</label>
            <input id="email" name="email" type="email" autocomplete="email" required
              class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
              placeholder="ex: nom.prenom@mmsp.gov.ma" value="{{ old('email') }}">
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
            <div class="relative">
              <input id="password" name="password" type="password" autocomplete="current-password" required
                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm pr-10"
                placeholder="••••••••">
              <button type="button" id="togglePassword" tabindex="-1" class="absolute inset-y-0 right-0 flex items-center px-3 focus:outline-none" aria-label="Afficher le mot de passe">
                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
            </div>
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
            <a href="{{ route('register') }}" class="inline-block text-sm font-medium text-indigo-600 hover:text-indigo-500">Créer un compte</a>
          </p>
        </div>
      </form>
    </div>
  </div>
</body>
<script>
  const passwordInput = document.getElementById('password');
  const togglePassword = document.getElementById('togglePassword');
  const eyeIcon = document.getElementById('eyeIcon');
  let isVisible = false;
  togglePassword.addEventListener('click', function () {
    isVisible = !isVisible;
    passwordInput.type = isVisible ? 'text' : 'password';
    eyeIcon.innerHTML = isVisible
      ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.042-3.368m3.087-2.933A9.953 9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.293 5.411M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />`
      : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />\n<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
  });
</script>
</html>
