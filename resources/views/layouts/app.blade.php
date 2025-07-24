<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo1.png') }}">
    <title>@yield('title', 'Système de Gestion des Incidents')</title>
        <script>
    if (localStorage.getItem('darkMode') === 'true') {
        document.documentElement.classList.add('dark');
    }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <!-- Sidebar -->
    @include('layouts.partials.sidebar')

    <!-- Main Content Area -->
    <div class="ml-64">
        <!-- Header -->
        @include('layouts.partials.header')

        <!-- Page Content -->
        <main class="pt-20 p-2">
            @yield('content')
        </main>
    </div>

    @include('layouts.partials.scripts')
    @stack('scripts')
</body>
</html>
