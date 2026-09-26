<header class="bg-white shadow-sm sticky top-0 z-20">
    <div class="flex items-center justify-between px-6 py-4">
        <div class="flex items-center gap-4">
            <h2 class="text-xl font-semibold text-gray-800">@yield('header-title', 'Panel de Administración')</h2>
            @yield('header-actions')
        </div>
        
        <div class="flex items-center gap-4">
            <div class="hidden md:block relative">
                <button class="flex items-center gap-2 p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition">
                    <i class="fas fa-bell text-lg"></i>
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                </button>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="hidden md:block text-right">
                    <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                </div>
                <div class="w-9 h-9 bg-primary-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-shield text-primary-600"></i>
                </div>
            </div>
        </div>
    </div>
</header>