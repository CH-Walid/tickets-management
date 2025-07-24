@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;

    $user = Auth::user();
    $hasImage = $user->img && Storage::disk('public')->exists($user->img);
    $initials = strtoupper(mb_substr($user->prenom, 0, 1) . mb_substr($user->nom, 0, 1));
@endphp

<header class="fixed top-0 left-64 right-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 transition-colors duration-300 z-40">
    <div class="flex items-center justify-between">
        <!-- Left: Sidebar toggle & Search -->
        <div class="flex items-center space-x-4">
            <button id="sidebarToggle" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                <i class="fas fa-bars text-gray-600 dark:text-gray-300"></i>
            </button>

            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" placeholder="Search" class="pl-10 pr-12 py-2 w-80 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">
                <kbd class="absolute right-3 top-1/2 transform -translate-y-1/2 px-2 py-1 text-xs bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded">⌘K</kbd>
            </div>
        </div>

        <!-- Right: Dark mode toggle + User menu -->
        <div class="flex items-center space-x-4">
              <!-- Icône notifications -->
              <div class="relative">
    <button id="notifToggle" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg relative focus:outline-none">
    <i class="fas fa-bell text-gray-600 dark:text-gray-300 text-lg"></i>

    @if($countTicketsNonTraites > 0)
        <span
            class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full transform translate-x-1/2 -translate-y-1/2">
            {{ $countTicketsNonTraites }}
        </span>
    @endif
</button>

<div id="notifDropdown" class="hidden absolute right-0 mt-2 w-80 max-h-96 overflow-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50">
    @if($countTicketsNonTraites > 0)
        @foreach($ticketsNonTraites as $ticket)
    <a href="{{ route('tickets.show', $ticket->id) }}"
       data-ticket-id="{{ $ticket->id }}"
       class="notif-item block px-4 py-2 hover:bg-yellow-50 dark:hover:bg-yellow-900 text-sm border-b border-gray-200 dark:border-gray-700 cursor-pointer">
        <p class="notif-title font-semibold text-red-600 dark:text-red-400">🔔 Nouveau ticket assigné</p>
        <p class="text-sm text-gray-800 dark:text-gray-200">
            Le ticket <strong>"{{ $ticket->titre }}"</strong> vous a été assigné.
        </p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            Priorité : {{ ucfirst($ticket->priorite) }} – Assigné {{ $ticket->created_at->diffForHumans() }}
        </p>
    </a>
@endforeach

    @else
        <span class="block px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
            Aucune nouvelle notification
        </span>
    @endif
</div>
</div>


            <button id="darkModeToggle" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                <i id="darkModeIcon" class="fas fa-moon text-gray-600 dark:text-gray-300"></i>
            </button>

            <!-- User Dropdown -->
            <div class="relative">
                <button id="userMenuButton" class="flex items-center space-x-2 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                    <!-- Avatar -->
                    <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 flex items-center justify-center text-sm font-semibold text-gray-700 dark:text-white">
                        @if($hasImage)
                            <img id="userProfileImage" src="{{ $user->profile_image_url }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            {{ $initials }}
                        @endif
                    </div>
                    <!-- Nom -->
                    <span class="font-medium text-gray-900 dark:text-white">{{ $user->prenom }} {{ $user->nom }}</span>
                    <i class="fas fa-chevron-down text-gray-600 dark:text-gray-300"></i>
                </button>

                <!-- Dropdown menu -->
                <div id="userDropdown" class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50 hidden">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 flex items-center justify-center text-base font-semibold text-gray-700 dark:text-white">
                                @if($hasImage)
                                    <img src="{{ $user->profile_image_url }}" alt="Avatar" class="w-full h-full object-cover">
                                @else
                                    {{ $initials }}
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $user->prenom }} {{ $user->nom }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="py-1">
                        <a href="{{ route('user.profile.show') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-user mr-3"></i> Mon Profil
                        </a>
                        <a href="/settings" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-cog mr-3"></i> Paramètres
                        </a>
                        <a href="/help" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-question-circle mr-3"></i> Aide
                        </a>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700 py-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-sign-out-alt mr-3"></i> Se déconnecter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</header>
