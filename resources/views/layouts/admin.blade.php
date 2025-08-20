<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Administrativo')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="h-screen flex overflow-hidden" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col" 
             :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
            
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 px-4 bg-gray-900 flex-shrink-0">
                <h1 class="text-xl font-bold text-white">Admin Panel</h1>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4" x-data="{ 
                orcamentosOpen: {{ request()->routeIs('admin.orcamentos*') ? 'true' : 'false' }},
                cadastrosOpen: {{ request()->routeIs('admin.bases*', 'admin.marcas*') ? 'true' : 'false' }},
                omieOpen: {{ request()->routeIs('admin.omie*') ? 'true' : 'false' }},
                financeirosOpen: {{ request()->routeIs('admin.centros-custo*', 'admin.impostos*') ? 'true' : 'false' }}
            }">
                <div class="px-4 space-y-2">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}">
                        <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                        Dashboard
                    </a>
                    
                    <!-- Orçamentos -->
                    <div class="space-y-1">
                        <button @click="orcamentosOpen = !orcamentosOpen" 
                                class="w-full flex items-center justify-between px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('admin.orcamentos*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <div class="flex items-center">
                                <i class="fas fa-file-invoice-dollar w-5 h-5 mr-3"></i>
                                Orçamentos
                            </div>
                            <i class="fas fa-chevron-down w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': orcamentosOpen }"></i>
                        </button>
                        
                        <div x-show="orcamentosOpen" x-transition class="ml-6 space-y-1">
                            <a href="{{ route('admin.orcamentos.index') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('admin.orcamentos.index') ? 'bg-blue-50 text-blue-700' : '' }}">
                                <i class="fas fa-list w-4 h-4 mr-3"></i>
                                Listar Orçamentos
                            </a>
                            <a href="{{ route('admin.orcamentos.create') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('admin.orcamentos.create') ? 'bg-blue-50 text-blue-700' : '' }}">
                                <i class="fas fa-plus w-4 h-4 mr-3"></i>
                                Novo Orçamento
                            </a>
                        </div>
                    </div>
                    
                    <!-- Cadastros -->
                    <div class="space-y-1">
                        <button @click="cadastrosOpen = !cadastrosOpen" 
                                class="w-full flex items-center justify-between px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('admin.bases*', 'admin.marcas*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <div class="flex items-center">
                                <i class="fas fa-address-book w-5 h-5 mr-3"></i>
                                Cadastros
                            </div>
                            <i class="fas fa-chevron-down w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': cadastrosOpen }"></i>
                        </button>
                        
                        <div x-show="cadastrosOpen" x-transition class="ml-6 space-y-1">

                            <a href="{{ route('admin.bases.index') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('admin.bases*') ? 'bg-blue-50 text-blue-700' : '' }}">
                                <i class="fas fa-map-marker-alt w-4 h-4 mr-3"></i>
                                Bases
                            </a>
                            <a href="{{ route('admin.marcas.index') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('admin.marcas*') ? 'bg-blue-50 text-blue-700' : '' }}">
                                <i class="fas fa-tags w-4 h-4 mr-3"></i>
                                Marcas
                            </a>
                        </div>
                    </div>
                    
                    <!-- Omie -->
                    <div class="space-y-1">
                        <button @click="omieOpen = !omieOpen" 
                                class="w-full flex items-center justify-between px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('admin.omie*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <div class="flex items-center">
                                <i class="fas fa-cloud w-5 h-5 mr-3"></i>
                                Omie
                            </div>
                            <i class="fas fa-chevron-down w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': omieOpen }"></i>
                        </button>
                        
                        <div x-show="omieOpen" x-transition class="ml-6 space-y-1">
                            <a href="{{ route('admin.omie.clientes') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('admin.omie.clientes', 'admin.omie.fornecedores') ? 'bg-blue-50 text-blue-700' : '' }}">
                                <i class="fas fa-users w-4 h-4 mr-3"></i>
                                Clientes/Fornecedores
                            </a>
                        </div>
                    </div>
                    
                    <!-- Financeiro -->
                    <div class="space-y-1">
                        <button @click="financeirosOpen = !financeirosOpen" 
                                class="w-full flex items-center justify-between px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('admin.centros-custo*', 'admin.impostos*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <div class="flex items-center">
                                <i class="fas fa-calculator w-5 h-5 mr-3"></i>
                                Financeiro
                            </div>
                            <i class="fas fa-chevron-down w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': financeirosOpen }"></i>
                        </button>
                        
                        <div x-show="financeirosOpen" x-transition class="ml-6 space-y-1">
                            <a href="{{ route('admin.centros-custo.index') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('admin.centros-custo*') ? 'bg-blue-50 text-blue-700' : '' }}">
                                <i class="fas fa-building w-4 h-4 mr-3"></i>
                                Centros de Custo
                            </a>
                            <a href="{{ route('admin.impostos.index') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('admin.impostos.index') ? 'bg-blue-50 text-blue-700' : '' }}">
                                <i class="fas fa-percentage w-4 h-4 mr-3"></i>
                                Impostos
                            </a>

                        </div>
                    </div>
                    
                    <!-- Divisor -->
                    <div class="border-t border-gray-200 my-4"></div>
                    
                    <!-- Usuários -->
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('admin.users*') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}">
                        <i class="fas fa-users w-5 h-5 mr-3"></i>
                        Usuários
                    </a>
                    
                    <!-- Relatórios -->
                    <a href="{{ route('admin.reports') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('admin.reports*') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}">
                        <i class="fas fa-chart-bar w-5 h-5 mr-3"></i>
                        Relatórios
                    </a>
                    
                    <!-- Configurações -->
                    <a href="{{ route('admin.settings') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('admin.settings*') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}">
                        <i class="fas fa-cog w-5 h-5 mr-3"></i>
                        Configurações
                    </a>
                </div>
            </nav>
            
            <!-- User Info -->
            <div class="flex-shrink-0 p-4 border-t border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-gray-700">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('auth.logout') }}" class="ml-2">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-gray-600 transition-colors duration-200" title="Sair">
                            <i class="fas fa-sign-out-alt w-5 h-5"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Mobile sidebar overlay -->
        <div class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden" 
             x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             style="display: none;"></div>
        
        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-4 py-4">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700">
                            <i class="fas fa-bars w-6 h-6"></i>
                        </button>
                        <h2 class="ml-4 text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700">
                            <i class="fas fa-bell w-6 h-6"></i>
                        </button>
                    </div>
                </div>
            </header>
            
            <!-- Page content -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="p-6">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>