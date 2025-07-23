<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title', 'Dashboard')</title>
    @vite('resources/css/app.css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-gray-50" data-user-id="{{ Auth::id() }}">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="flex flex-col min-h-screen w-72 bg-gray-100 border-r border-blue-100 shadow-2xl backdrop-blur-xl rounded-r-3xl p-6 transition-all duration-400 ease-in-out z-30 relative group/sidebar">
    <!-- Toggle button intégré -->
    <button id="sidebar-toggle" class="mb-6 ml-1 bg-white/60 backdrop-blur-md shadow-inner rounded-full p-2 hover:bg-white/80 transition-all duration-300 flex items-center justify-center focus:outline-none">
        <i data-lucide="menu" class="w-5 h-5 text-blue-600"></i>
    </button>

    <!-- Logo SVG login -->
    <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center mb-10">
        <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="h-14 w-auto drop-shadow-xl transition-transform duration-300 hover:scale-105">
    </a>
    <nav class="flex-1 flex flex-col gap-4 mt-2 overflow-y-auto custom-scroll pr-2">
        <div class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2 ml-2 sidebar-label">Navigation</div>
        <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-4 px-5 py-3 rounded-xl font-semibold text-gray-700 transition-all duration-300 relative sidebar-link sidebar-tooltip" data-tooltip="Tableau de bord">
            <span class="sidebar-anim-bar"></span>
            <i data-lucide="layout-dashboard" class="w-6 h-6 text-gray-400 group-hover:text-indigo-500 transition-colors"></i>
            <span class="z-10 sidebar-label">Dashboard</span>
        </a>
        <a href="{{ route('admin.tickets.index') }}" class="group flex items-center gap-4 px-5 py-3 rounded-xl font-semibold text-gray-700 transition-all duration-300 relative sidebar-link sidebar-tooltip" data-tooltip="Tickets">
            <span class="sidebar-anim-bar"></span>
            <i data-lucide="ticket" class="w-6 h-6 text-gray-400 group-hover:text-blue-500 transition-colors"></i>
            <span class="z-10 sidebar-label">Tickets</span>
        </a>
        <a href="{{ route('admin.utilisateurs.index') }}" class="group flex items-center gap-4 px-5 py-3 rounded-xl font-semibold text-gray-700 transition-all duration-300 relative sidebar-link sidebar-tooltip" data-tooltip="Utilisateurs">
            <span class="sidebar-anim-bar"></span>
            <i data-lucide="users" class="w-6 h-6 text-gray-400 group-hover:text-fuchsia-500 transition-colors"></i>
            <span class="z-10 sidebar-label">Utilisateurs</span>
        </a>
        <a href="{{ route('admin.techniciens.techniciens.index') }}" class="group flex items-center gap-4 px-5 py-3 rounded-xl font-semibold text-gray-700 transition-all duration-300 relative sidebar-link sidebar-tooltip" data-tooltip="Techniciens">
            <span class="sidebar-anim-bar"></span>
            <i data-lucide="settings" class="w-6 h-6 text-gray-400 group-hover:text-cyan-500 transition-colors"></i>
            <span class="z-10 sidebar-label">Techniciens</span>
        </a>
        <a href="{{ route('admin.services.index') }}" class="group flex items-center gap-3 px-5 py-3 rounded-xl font-semibold text-gray-700 transition-all duration-300 relative sidebar-link sidebar-tooltip whitespace-nowrap" data-tooltip="Services & Catégories">
            <span class="sidebar-anim-bar"></span>
            <i data-lucide="layers" class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors"></i>
            <span class="z-10 sidebar-label whitespace-nowrap">Services & Catégories</span>
        </a>
        <a href="{{ url('admin/statistiques') }}" class="group flex items-center gap-4 px-5 py-3 rounded-xl font-semibold text-gray-700 transition-all duration-300 relative sidebar-link sidebar-tooltip" data-tooltip="Statistiques">
            <span class="sidebar-anim-bar"></span>
            <i data-lucide="bar-chart-3" class="w-6 h-6 text-gray-400 group-hover:text-yellow-500 transition-colors"></i>
            <span class="z-10 sidebar-label">Statistiques</span>
        </a>
    </nav>
    <!-- Section utilisateur en bas -->
    <div class="mt-8 pt-4 border-t border-blue-100 flex items-center gap-3 relative" id="sidebar-user-menu">
        @if(Auth::user() && Auth::user()->photo)
            <x-user-avatar :user="Auth::user()" size="10" font="lg" />
        @else
            <x-user-avatar :user="Auth::user()" size="10" font="lg" />
        @endif
        <div class="flex-1 sidebar-profile-details transition-all duration-300">
            <div class="font-semibold text-gray-700 text-sm">{{ Auth::user()->nom ?? 'Admin' }}</div>
            <div class="text-xs text-gray-400 truncate">{{ Auth::user()->email ?? '' }}</div>
        </div>
        <button class="ml-2 p-2 rounded-full hover:bg-gray-200 transition" id="sidebar-user-dropdown-btn" type="button">
            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-500"></i>
        </button>
        <div class="hidden absolute left-0 bottom-14 w-56 bg-white border border-gray-200 rounded-lg shadow-lg z-50 text-gray-800" id="sidebar-user-dropdown-menu">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 hover:bg-gray-100">
                    <i data-lucide="log-out" class="w-5 h-5 text-red-500"></i> Se déconnecter
                </button>
            </form>
        </div>
    </div>
</aside>
<script>
// Animation "active" sur la sidebar
function setActiveSidebarLink() {
    const links = document.querySelectorAll('.sidebar-link');
    links.forEach(link => link.classList.remove('active'));
    // Détecter la page courante
    let found = false;
    links.forEach(link => {
        if(!found && link.href && window.location.href.includes(link.href)) {
            link.classList.add('active');
            found = true;
        }
    });
    // Fallback : activer le premier si rien trouvé
    if(!document.querySelector('.sidebar-link.active') && links.length) {
        links[0].classList.add('active');
    }
}
setActiveSidebarLink();
window.addEventListener('DOMContentLoaded', setActiveSidebarLink);

// Sidebar toggle persistant avec localStorage
const sidebar = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebar-toggle');
let sidebarCollapsed = false;

// Fonction pour appliquer l'état du sidebar
function applySidebarState() {
    if (sidebarCollapsed) {
        sidebar.classList.remove('w-72', 'p-6');
        sidebar.classList.add('w-20', 'p-2');
        document.querySelectorAll('.sidebar-label').forEach(el => {
            el.classList.add('hidden');
        });
        document.querySelectorAll('.sidebar-profile-details').forEach(el => {
            el.classList.add('hidden');
        });
        document.querySelectorAll('.sidebar-tooltip').forEach(el => {
            el.setAttribute('title', el.dataset.tooltip);
        });
    } else {
        sidebar.classList.add('w-72', 'p-6');
        sidebar.classList.remove('w-20', 'p-2');
        document.querySelectorAll('.sidebar-label').forEach(el => {
            el.classList.remove('hidden');
        });
        document.querySelectorAll('.sidebar-profile-details').forEach(el => {
            el.classList.remove('hidden');
        });
        document.querySelectorAll('.sidebar-tooltip').forEach(el => {
            el.removeAttribute('title');
        });
    }
}

// Initialisation : lire l'état depuis localStorage (fermé par défaut)
sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
applySidebarState();

sidebarToggle.addEventListener('click', function() {
    sidebarCollapsed = !sidebarCollapsed;
    localStorage.setItem('sidebarCollapsed', sidebarCollapsed);
    applySidebarState();
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('sidebar-user-dropdown-btn');
    const menu = document.getElementById('sidebar-user-dropdown-menu');
    const logoutBtn = menu.querySelector('form button[type="submit"]');
    let open = false;
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        open = !open;
        menu.classList.toggle('hidden', !open);
    });
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.stopPropagation(); // Empêche la fermeture du menu avant le submit
        });
    }
    document.addEventListener('click', function(e) {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            open = false;
            menu.classList.add('hidden');
        }
    });
});
</script>
<style>
.custom-scroll::-webkit-scrollbar {
    width: 8px;
    background: transparent;
}
.custom-scroll::-webkit-scrollbar-thumb {
    background: #e0e7ef;
    border-radius: 6px;
}
/* Modern sidebar animation */
.sidebar-link {
    position: relative;
    overflow: hidden;
    transition: background 0.3s, color 0.3s;
}
.sidebar-link.active, .sidebar-link:focus {
    background: linear-gradient(90deg, #e5e7eb 0%, #d1d5db 100%);
    color: #3730a3;
    box-shadow: 0 2px 12px 0 rgba(99,102,241,0.08);
}
.sidebar-link .sidebar-anim-bar {
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 4px;
    background: linear-gradient(180deg, #6366f1 0%, #818cf8 100%);
    border-radius: 4px;
    opacity: 0;
    transform: scaleY(0.5);
    transition: opacity 0.3s, transform 0.3s;
}
.sidebar-link.active .sidebar-anim-bar {
    opacity: 1;
    transform: scaleY(1);
}
.sidebar-link i[data-lucide] {
    transition: transform 0.3s cubic-bezier(.4,2,.6,1), color 0.3s;
}
.sidebar-link.active i[data-lucide] {
    color: #6366f1 !important;
    transform: scale(1.15) rotate(-6deg);
    filter: drop-shadow(0 2px 6px #6366f133);
}
/* Harmonisation des couleurs boutons et hover */
.sidebar-link:hover:not(.active) {
    background: #f3f4f6;
    color: #3730a3;
}
.sidebar-link .sidebar-label {
    color: #374151;
}
</style>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                            <input type="text" placeholder="Rechercher..." data-admin-search class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-80 focus:outline-none focus:ring-2 focus:ring-blue-700">
                        </div>
                        <div class="flex items-center gap-3">
                            <!-- Notifications -->
                            <div class="relative group" id="notif-dropdown">
                                <button class="relative p-2 hover:bg-gray-100 rounded-lg" id="notif-btn">
                                    <i data-lucide="bell" class="w-5 h-5"></i>
                                    <span id="notif-badge" class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full text-[10px] text-white flex items-center justify-center" style="display: none;"></span>
                                </button>
                                <div class="hidden group-focus-within:block group-hover:block absolute right-0 mt-2 w-96 bg-white border border-gray-200 rounded-lg shadow-lg z-50 text-gray-800 max-h-[48rem] overflow-y-auto" id="notif-menu">
                                    <div class="p-4 font-semibold border-b">Notifications</div>
                                    <ul id="notif-list" class="divide-y"></ul>
                                    <div class="p-4 text-xs text-gray-400 text-center" id="notif-empty">Aucune notification</div>
                                </div>
                            </div>
                            <!-- Chat direct -->
                            <div class="relative group" id="chat-dropdown">
                                <button class="relative p-2 hover:bg-gray-100 rounded-lg" id="chat-btn">
                                    <i data-lucide="message-square" class="w-5 h-5"></i>
                                    <span id="chat-badge" class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full text-[10px] text-white flex items-center justify-center" style="display: none;"></span>
                                </button>
                                <div class="hidden group-focus-within:block group-hover:block absolute right-0 mt-2 w-96 bg-white border border-gray-200 rounded-lg shadow-lg z-50 text-gray-800 flex flex-col max-h-[48rem]" id="chat-menu">
                                    <div class="p-4 font-semibold border-b">Chat avec le chef technicien</div>
                                    <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50"></div>
                                    <form id="chat-form" class="flex border-t p-3 gap-2">
                                        <input type="text" id="chat-input" class="flex-1 p-3 border-0 focus:ring-0 rounded-lg bg-gray-100" placeholder="Écrire un message..." autocomplete="off">
                                        <button type="submit" class="px-5 py-2 text-blue-600 font-bold rounded-lg bg-blue-50 hover:bg-blue-100">Envoyer</button>
                                    </form>
                                </div>
                            </div>
                            <!-- Profil -->
                            <div class="relative group" id="admin-dropdown">
                                <button class="flex items-center gap-2 focus:outline-none" id="admin-dropdown-btn">
                                    <x-user-avatar :user="Auth::user()" size="10" font="lg" class="border-2 border-blue-200 shadow" />
<span class="font-medium text-sm">Admin</span>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-500"></i>
                                </button>
                                <div class="hidden group-focus-within:block group-hover:block absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50 text-gray-800" id="admin-dropdown-menu">
                                    <a href="{{ route('admin.profil') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100">
                                        <i data-lucide="user" class="w-4 h-4 text-gray-500"></i>
                                        Profil
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Content -->
            <main class="flex-1 p-6 overflow-auto">
                @yield('content')
            </main>
        </div>
    </div>
    @vite('resources/js/admin/admin.js')
<script src="/js/admin-search.js"></script>
<script src="/js/admin-dropdown.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>
    @stack('scripts')
</body>
</html>
