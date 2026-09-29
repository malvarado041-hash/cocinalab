<aside id="sidebar" class="w-64 bg-white shadow-lg h-screen fixed inset-y-0 left-0 z-10 sidebar-transition">
    <div class="p-4 flex items-center justify-between">
        <button id="sidebar-toggle" type="button" class="flex items-center gap-3 min-w-0 text-left focus:outline-none" aria-label="Colapsar sidebar">
            <div class="w-10 h-10 bg-primary-500 rounded-xl flex items-center justify-center flex-shrink-0 transition hover:scale-105">
                <i class="fas fa-utensils text-white text-xl"></i>
            </div>
            <div class="sidebar-text truncate">
                <h1 class="text-xl font-bold text-gray-800 truncate">CocinaLab</h1>
                <p class="text-xs text-gray-500 truncate">Admin Panel</p>
            </div>
        </button>
    </div>

    <nav class="p-4 flex-1 overflow-y-auto" id="sidebar-nav">
        <div class="sidebar-section px-3 py-2">
            <h3 class="sidebar-text text-xs font-semibold text-gray-400 uppercase tracking-wider">Navegación</h3>
            <ul class="space-y-1 mt-2" role="navigation" aria-label="Navegación principal">
                <li>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="sidebar-link flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-primary-50 text-primary-600' : '' }}"
                       data-tooltip="Dashboard">
                        <i class="fas fa-tachometer-alt sidebar-icon w-6 text-center text-lg"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}" 
                       class="sidebar-link flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.users*') ? 'bg-primary-50 text-primary-600' : '' }}"
                       data-tooltip="Usuarios">
                        <i class="fas fa-users sidebar-icon w-6 text-center text-lg"></i>
                        <span class="sidebar-text">Usuarios</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar-section px-3 py-2">
            <h3 class="sidebar-text text-xs font-semibold text-gray-400 uppercase tracking-wider">Sistema</h3>
            <ul class="space-y-1 mt-2" role="navigation" aria-label="Sistema">
                <li>
                    <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition-all duration-200" data-tooltip="Configuración">
                        <i class="fas fa-cog sidebar-icon w-6 text-center text-lg"></i>
                        <span class="sidebar-text">Configuración</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition-all duration-200" data-tooltip="Reportes">
                        <i class="fas fa-chart-bar sidebar-icon w-6 text-center text-lg"></i>
                        <span class="sidebar-text">Reportes</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="p-4 border-t sidebar-footer">
        <div class="flex items-center gap-3 px-3 py-2">
            <div class="w-9 h-9 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-user-shield text-gray-600"></i>
            </div>
            <div class="sidebar-user-info flex-1 min-w-0">
                <p class="sidebar-text text-sm font-medium text-gray-800 truncate">{{ Auth::user()->name }}</p>
                <p class="sidebar-text text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
            </div>
        </div>
        <div class="mt-3 space-y-2">
            <a href="{{ route('home') }}" class="sidebar-link flex items-center gap-3 px-4 py-2 text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition" data-tooltip="Ver sitio">
                <i class="fas fa-external-link-alt w-5 text-center"></i>
                <span class="sidebar-text">Ver sitio</span>
            </a>
            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="sidebar-link flex items-center gap-3 px-4 py-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition w-full" data-tooltip="Cerrar sesión">
                    <i class="fas fa-sign-out-alt w-5 text-center"></i>
                    <span class="sidebar-text">Cerrar sesión</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Tooltip element for collapsed sidebar -->
<div id="sidebar-tooltip" class="fixed z-50 hidden px-3 py-2 bg-gray-900 text-white text-sm rounded-lg shadow-lg pointer-events-none"></div>

<style>
    .sidebar-transition { 
        transition: none; 
    }
    
    #sidebar {
        transition: none;
    }
    
    html.sidebar-collapsed #sidebar,
    #sidebar.w-20 {
        width: 5rem;
    }

    html.sidebar-collapsed #main-content,
    #main-content.ml-20 {
        margin-left: 5rem;
    }

    html.sidebar-collapsed #sidebar .sidebar-text,
    html.sidebar-collapsed #sidebar .sidebar-section h3,
    html.sidebar-collapsed #sidebar .sidebar-user-info,
    html.sidebar-collapsed #sidebar .sidebar-link span,
    #sidebar.w-20 .sidebar-text,
    #sidebar.w-20 .sidebar-section h3,
    #sidebar.w-20 .sidebar-user-info,
    #sidebar.w-20 .sidebar-link span,
    #sidebar.w-20 .sidebar-link::after,
    #sidebar.w-20 .sidebar-link {
        justify-content: center;
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }

    html.sidebar-collapsed #sidebar .sidebar-text,
    html.sidebar-collapsed #sidebar .sidebar-section h3,
    html.sidebar-collapsed #sidebar .sidebar-user-info,
    html.sidebar-collapsed #sidebar .sidebar-link span,
    #sidebar.w-20 .sidebar-text,
    #sidebar.w-20 .sidebar-section h3,
    #sidebar.w-20 .sidebar-user-info,
    #sidebar.w-20 .sidebar-link span {
        display: none !important;
    }
    
    #sidebar.w-20 .sidebar-icon {
        margin: 0 auto;
    }
    
    #sidebar.w-20 .sidebar-footer > div:first-child {
        justify-content: center;
    }
    
    #sidebar.w-20 .w-9 {
        margin: 0 auto;
    }
    
    .sidebar-link {
        position: relative;
        white-space: nowrap;
    }
    
    .sidebar-icon {
        flex-shrink: 0;
        width: 1.5rem;
    }
    
    .fade-in { 
        animation: fadeIn 0.3s ease-out; 
    }
    
    @keyframes fadeIn { 
        from { opacity: 0; transform: translateY(10px); } 
        to { opacity: 1; transform: translateY(0); } 
    }
    
    /* Tooltip for collapsed sidebar */
    #sidebar-tooltip {
        opacity: 0;
        transform: translateX(-10px);
        transition: opacity 0.15s ease, transform 0.15s ease;
    }
    
    #sidebar-tooltip.show {
        opacity: 1;
        transform: translateX(0);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const mainContent = document.getElementById('main-content');
    const tooltip = document.getElementById('sidebar-tooltip');
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    const isInitiallyCollapsed = document.documentElement.classList.contains('sidebar-collapsed');
    
    let isCollapsed = isInitiallyCollapsed;
    
    // Toggle sidebar
    if (sidebarToggle && sidebar && mainContent) {
        const applySidebarState = function(collapsed, savePreference) {
            isCollapsed = collapsed;

            sidebar.classList.toggle('w-20', collapsed);
            sidebar.classList.toggle('w-64', !collapsed);
            mainContent.classList.toggle('ml-20', collapsed);
            mainContent.classList.toggle('ml-64', !collapsed);
            document.documentElement.classList.toggle('sidebar-collapsed', collapsed);
            sidebarToggle.setAttribute('aria-label', collapsed ? 'Expandir sidebar' : 'Colapsar sidebar');

            if (savePreference) {
                localStorage.setItem('sidebarCollapsed', collapsed);
            }
        };

        const toggleSidebar = function() {
            applySidebarState(!isCollapsed, true);
        };

        sidebarToggle.addEventListener('click', toggleSidebar);

        applySidebarState(isInitiallyCollapsed, false);
    }
    
    // Tooltip on hover for collapsed sidebar
    sidebarLinks.forEach(function(link) {
        const tooltipText = link.getAttribute('data-tooltip');
        
        link.addEventListener('mouseenter', function(e) {
            if (isCollapsed && tooltipText && tooltip) {
                tooltip.textContent = tooltipText;
                tooltip.classList.add('show');
                
                const rect = link.getBoundingClientRect();
                tooltip.style.left = (rect.right + 8) + 'px';
                tooltip.style.top = (rect.top + rect.height / 2 - tooltip.offsetHeight / 2) + 'px';
            }
        });
        
        link.addEventListener('mouseleave', function() {
            if (tooltip) {
                tooltip.classList.remove('show');
            }
        });
        
        // Handle click on collapsed sidebar - temporarily expand
        link.addEventListener('click', function(e) {
            if (isCollapsed && link.href && !link.href.startsWith('#')) {
                // Allow normal navigation
            }
        });
    });
    
    // Auto-hide flash messages after 5 seconds
    setTimeout(function() {
        document.querySelectorAll('[role="alert"]').forEach(function(el) {
            el.style.opacity = '0';
            el.style.transform = 'translateY(-10px)';
            el.style.transition = 'all 0.3s ease';
            setTimeout(() => el.remove(), 300);
        });
    }, 5000);

    // Confirm delete
    document.querySelectorAll('form[onsubmit]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            if (!confirm('¿Estás seguro de eliminar este usuario?')) {
                e.preventDefault();
            }
        });
    });
});
</script>