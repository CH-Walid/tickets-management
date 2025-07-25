<div id="sidebar" class="fixed left-0 top-0 h-full w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transition-all duration-300 ease-in-out z-50 overflow-y-auto">
    <div class="p-4">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 flex items-center justify-center flex-shrink-0">
                <img src="{{ asset('images/logo1.png') }}">
            </div>
            <div id="sidebarTitle" class="transition-opacity duration-300">
                <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Système de Gestion</div>
                <div class="text-sm font-medium text-gray-600 dark:text-gray-400">des Incidents</div>
            </div>
        </div>
    </div>

    <div class="px-4 py-2">
        <span id="menuLabel" class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider transition-opacity duration-300">MENU</span>
    </div>

    <nav class="mt-4">
         @if(auth()->user()->role === 'user_simple')

            <a href="{{route('user.dashboard')}}" class="px-4 py-2 mx-2 rounded-lg cursor-pointer flex items-center space-x-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-th-large w-5 h-5 flex-shrink-0"></i>
                <span class="sidebar-text transition-opacity duration-300">Tableau de bord</span>
            </a>

            <a href="{{route('tickets.all')}}" class="px-4 py-2 mx-2 rounded-lg cursor-pointer flex items-center space-x-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-ticket-alt w-5 h-5 flex-shrink-0"></i>
                <span class="sidebar-text transition-opacity duration-300">Tickets</span>
            </a>

            <a href="{{route('user.profile.show')}}" class="px-4 py-2 mx-2 rounded-lg cursor-pointer flex items-center space-x-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-user w-5 h-5 flex-shrink-0"></i>
                <span class="sidebar-text transition-opacity duration-300">Profile</span>
            </a>
            
        @elseif(auth()->user()->role==='chef_technicien')
            <a href="{{route('chef.dashboard')}}" class="px-4 py-2 mx-2 rounded-lg cursor-pointer flex items-center space-x-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-th-large w-5 h-5 flex-shrink-0"></i>
                <span class="sidebar-text transition-opacity duration-300">Tableau de bord</span>
            </a>

            <a href="{{route('chef.dashboard')}}" class="px-4 py-2 mx-2 rounded-lg cursor-pointer flex items-center space-x-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-user w-5 h-5 flex-shrink-0"></i>
                <span class="sidebar-text transition-opacity duration-300">Profile</span>
            </a>

            <a href="{{route('chef.tickets.all')}}" class="px-4 py-2 mx-2 rounded-lg cursor-pointer flex items-center space-x-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-ticket-alt w-5 h-5 flex-shrink-0"></i>
                <span class="sidebar-text transition-opacity duration-300">Liste Des Tickets</span>
            </a>
            <a href="{{ route('chef.techniciens.index') }}" class="px-4 py-2 mx-2 rounded-lg cursor-pointer flex items-center space-x-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-users-cog w-5 h-5 flex-shrink-0"></i>
                <span class="sidebar-text transition-opacity duration-300">Liste Des techniciens</span>
            </a>
        @elseif(auth()->user()->role==='technicien')


            <a href="{{route('tech.tickets.index')}}" class="px-4 py-2 mx-2 rounded-lg cursor-pointer flex items-center space-x-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-ticket-alt w-5 h-5 flex-shrink-0"></i>
                <span class="sidebar-text transition-opacity duration-300">Liste Des Tickets</span>
            </a>

            <a href="{{route('user.profile.show')}}" class="px-4 py-2 mx-2 rounded-lg cursor-pointer flex items-center space-x-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-user w-5 h-5 flex-shrink-0"></i>
                <span class="sidebar-text transition-opacity duration-300">Profile</span>
            </a>
        @endif
    </nav>

    <div class="mt-8 px-4 py-2">
        <span id="supportLabel" class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider transition-opacity duration-300">SUPPORT</span>
    </div>

    <a href="/chat" class="px-4 py-2 mx-2 rounded-lg cursor-pointer flex items-center space-x-3 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
        <i class="fas fa-comments w-5 h-5 flex-shrink-0"></i>
        <span class="sidebar-text transition-opacity duration-300">Chat</span>
    </a>
</div>
