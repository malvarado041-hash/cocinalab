<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Panel Admin CocinaLab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fef7ee',
                            100: '#fdedd6',
                            200: '#fad9ad',
                            300: '#f6bd7a',
                            400: '#f19945',
                            500: '#ed7d1e',
                            600: '#de6319',
                            700: '#b84a14',
                            800: '#933c15',
                            900: '#773215',
                        }
                    }
                }
            }
        }
    </script>
    <script>
        (function() {
            try {
                if (localStorage.getItem('sidebarCollapsed') === 'true') {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            } catch (error) {
                // Ignore storage access errors.
            }
        })();
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .fade-in { animation: fadeIn 0.3s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex">
        @include('admin.partials.sidebar')

        <div id="main-content" class="flex-1 flex flex-col min-w-0 ml-64">
            @include('admin.partials.header')

            <main class="flex-1 p-6 lg:p-8">
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center gap-3 fade-in" role="alert">
                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg flex items-center gap-3 fade-in" role="alert">
                        <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>