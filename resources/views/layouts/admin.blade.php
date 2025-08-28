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
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 shadow-2xl transform transition-all duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col backdrop-blur-sm" 
             :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
            
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 px-4 bg-gradient-to-r from-blue-600 to-purple-600 flex-shrink-0 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20 animate-pulse"></div>
                <h1 class="text-xl font-bold text-white relative z-10 tracking-wide">Admin Panel</h1>
                <div class="absolute -top-2 -right-2 w-8 h-8 bg-white/10 rounded-full animate-bounce delay-1000"></div>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-6 scrollbar-thin scrollbar-thumb-slate-600 scrollbar-track-transparent" x-data="{ 
                cadastrosOpen: {{ request()->routeIs('admin.bases*', 'admin.marcas*') ? 'true' : 'false' }},
                omieOpen: {{ request()->routeIs('admin.omie*') ? 'true' : 'false' }},
                financeirosOpen: {{ request()->routeIs('admin.centros-custo*', 'admin.impostos*', 'admin.grupos-impostos*') ? 'true' : 'false' }}
            }">
                <div class="px-4 space-y-3">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="group flex items-center px-4 py-3 text-slate-300 rounded-xl hover:bg-gradient-to-r hover:from-blue-600/20 hover:to-purple-600/20 hover:text-white transition-all duration-300 transform hover:scale-105 hover:shadow-lg {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg shadow-blue-500/25' : '' }}">
                        <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                        <span class="font-medium">Dashboard</span>
                        <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <i class="fas fa-chevron-right w-3 h-3"></i>
                        </div>
                    </a>
                    

                    
                    <!-- Cadastros -->
                    <div class="space-y-2">
                        <button @click="cadastrosOpen = !cadastrosOpen" 
                                class="group w-full flex items-center justify-between px-4 py-3 text-slate-300 rounded-xl hover:bg-gradient-to-r hover:from-emerald-600/20 hover:to-teal-600/20 hover:text-white transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('admin.bases*', 'admin.marcas*') ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-lg shadow-emerald-500/25' : '' }}">
                            <div class="flex items-center">
                                <i class="fas fa-address-book w-5 h-5 mr-3"></i>
                                <span class="font-medium">Cadastros</span>
                            </div>
                            <i class="fas fa-chevron-down w-4 h-4 transition-all duration-300 group-hover:text-emerald-300" :class="{ 'rotate-180': cadastrosOpen }"></i>
                        </button>
                        
                        <div x-show="cadastrosOpen" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="ml-6 space-y-2 border-l-2 border-slate-700 pl-4">

                            <a href="{{ route('admin.bases.index') }}" 
                               class="group flex items-center px-3 py-2 text-sm text-slate-400 rounded-lg hover:bg-slate-700/50 hover:text-emerald-300 transition-all duration-200 transform hover:translate-x-1 {{ request()->routeIs('admin.bases*') ? 'bg-slate-700 text-emerald-300 border-l-2 border-emerald-500' : '' }}">
                                <i class="fas fa-map-marker-alt w-4 h-4 mr-3 group-hover:text-emerald-400"></i>
                                <span>Bases</span>
                            </a>
                            <a href="{{ route('admin.marcas.index') }}" 
                               class="group flex items-center px-3 py-2 text-sm text-slate-400 rounded-lg hover:bg-slate-700/50 hover:text-emerald-300 transition-all duration-200 transform hover:translate-x-1 {{ request()->routeIs('admin.marcas*') ? 'bg-slate-700 text-emerald-300 border-l-2 border-emerald-500' : '' }}">
                                <i class="fas fa-tags w-4 h-4 mr-3 group-hover:text-emerald-400"></i>
                                <span>Marcas</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Omie -->
                    <div class="space-y-2">
                        <button @click="omieOpen = !omieOpen" 
                                class="group w-full flex items-center justify-between px-4 py-3 text-slate-300 rounded-xl hover:bg-gradient-to-r hover:from-cyan-600/20 hover:to-blue-600/20 hover:text-white transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('admin.omie*') ? 'bg-gradient-to-r from-cyan-600 to-blue-600 text-white shadow-lg shadow-cyan-500/25' : '' }}">
                            <div class="flex items-center">
                                <i class="fas fa-cloud w-5 h-5 mr-3"></i>
                                <span class="font-medium">Omie</span>
                            </div>
                            <i class="fas fa-chevron-down w-4 h-4 transition-all duration-300 group-hover:text-cyan-300" :class="{ 'rotate-180': omieOpen }"></i>
                        </button>
                        
                        <div x-show="omieOpen" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="ml-6 space-y-2 border-l-2 border-slate-700 pl-4">
                            <a href="{{ route('admin.omie.clientes') }}" 
                               class="group flex items-center px-3 py-2 text-sm text-slate-400 rounded-lg hover:bg-slate-700/50 hover:text-cyan-300 transition-all duration-200 transform hover:translate-x-1 {{ request()->routeIs('admin.omie.clientes', 'admin.omie.fornecedores') ? 'bg-slate-700 text-cyan-300 border-l-2 border-cyan-500' : '' }}">
                                <i class="fas fa-users w-4 h-4 mr-3 group-hover:text-cyan-400"></i>
                                <span>Clientes/Fornecedores</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Financeiro -->
                    <div class="space-y-2">
                        <button @click="financeirosOpen = !financeirosOpen" 
                                class="group w-full flex items-center justify-between px-4 py-3 text-slate-300 rounded-xl hover:bg-gradient-to-r hover:from-amber-600/20 hover:to-orange-600/20 hover:text-white transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('admin.centros-custo*', 'admin.impostos*', 'admin.grupos-impostos*') ? 'bg-gradient-to-r from-amber-600 to-orange-600 text-white shadow-lg shadow-amber-500/25' : '' }}">
                            <div class="flex items-center">
                                <i class="fas fa-calculator w-5 h-5 mr-3"></i>
                                <span class="font-medium">Financeiro</span>
                            </div>
                            <i class="fas fa-chevron-down w-4 h-4 transition-all duration-300 group-hover:text-amber-300" :class="{ 'rotate-180': financeirosOpen }"></i>
                        </button>
                        
                        <div x-show="financeirosOpen" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="ml-6 space-y-2 border-l-2 border-slate-700 pl-4">
                            <a href="{{ route('admin.centros-custo.index') }}" 
                               class="group flex items-center px-3 py-2 text-sm text-slate-400 rounded-lg hover:bg-slate-700/50 hover:text-amber-300 transition-all duration-200 transform hover:translate-x-1 {{ request()->routeIs('admin.centros-custo*') ? 'bg-slate-700 text-amber-300 border-l-2 border-amber-500' : '' }}">
                                <i class="fas fa-building w-4 h-4 mr-3 group-hover:text-amber-400"></i>
                                <span>Centros de Custo</span>
                            </a>
                            <a href="{{ route('admin.impostos.index') }}" 
                               class="group flex items-center px-3 py-2 text-sm text-slate-400 rounded-lg hover:bg-slate-700/50 hover:text-amber-300 transition-all duration-200 transform hover:translate-x-1 {{ request()->routeIs('admin.impostos.index') ? 'bg-slate-700 text-amber-300 border-l-2 border-amber-500' : '' }}">
                                <i class="fas fa-percentage w-4 h-4 mr-3 group-hover:text-amber-400"></i>
                                <span>Impostos</span>
                            </a>
                            <a href="{{ route('admin.grupos-impostos.index') }}" 
                               class="group flex items-center px-3 py-2 text-sm text-slate-400 rounded-lg hover:bg-slate-700/50 hover:text-amber-300 transition-all duration-200 transform hover:translate-x-1 {{ request()->routeIs('admin.grupos-impostos*') ? 'bg-slate-700 text-amber-300 border-l-2 border-amber-500' : '' }}">
                                <i class="fas fa-layer-group w-4 h-4 mr-3 group-hover:text-amber-400"></i>
                                <span>Grupos de Impostos</span>
                            </a>

                        </div>
                    </div>
                    
                    <!-- Orçamentos -->
                    <a href="{{ route('admin.orcamentos.index') }}" 
                       class="group flex items-center px-4 py-3 text-slate-300 rounded-xl hover:bg-gradient-to-r hover:from-indigo-600/20 hover:to-purple-600/20 hover:text-white transition-all duration-300 transform hover:scale-105 hover:shadow-lg {{ request()->routeIs('admin.orcamentos*') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/25' : '' }}">
                        <i class="fas fa-file-invoice w-5 h-5 mr-3"></i>
                        <span class="font-medium">Orçamentos</span>
                        <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <i class="fas fa-chevron-right w-3 h-3"></i>
                        </div>
                    </a>
                    
                    <!-- Divisor -->
                    <div class="relative my-6">
                        <div class="border-t border-slate-700"></div>
                        <div class="absolute inset-0 flex justify-center">
                            <div class="bg-gradient-to-r from-slate-900 via-slate-600 to-slate-900 px-3">
                                <i class="fas fa-grip-lines-vertical text-slate-600 text-xs"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Usuários -->
                    <a href="{{ route('admin.users.index') }}" 
                       class="group flex items-center px-4 py-3 text-slate-300 rounded-xl hover:bg-gradient-to-r hover:from-rose-600/20 hover:to-pink-600/20 hover:text-white transition-all duration-300 transform hover:scale-105 hover:shadow-lg {{ request()->routeIs('admin.users*') ? 'bg-gradient-to-r from-rose-600 to-pink-600 text-white shadow-lg shadow-rose-500/25' : '' }}">
                        <i class="fas fa-users w-5 h-5 mr-3"></i>
                        <span class="font-medium">Usuários</span>
                        <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <i class="fas fa-chevron-right w-3 h-3"></i>
                        </div>
                    </a>
                    
                    <!-- Relatórios -->
                    <a href="{{ route('admin.reports') }}" 
                       class="group flex items-center px-4 py-3 text-slate-300 rounded-xl hover:bg-gradient-to-r hover:from-green-600/20 hover:to-emerald-600/20 hover:text-white transition-all duration-300 transform hover:scale-105 hover:shadow-lg {{ request()->routeIs('admin.reports*') ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg shadow-green-500/25' : '' }}">
                        <i class="fas fa-chart-bar w-5 h-5 mr-3"></i>
                        <span class="font-medium">Relatórios</span>
                        <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <i class="fas fa-chevron-right w-3 h-3"></i>
                        </div>
                    </a>
                    
                    <!-- Configurações -->
                    <a href="{{ route('admin.settings') }}" 
                       class="group flex items-center px-4 py-3 text-slate-300 rounded-xl hover:bg-gradient-to-r hover:from-slate-600/20 hover:to-gray-600/20 hover:text-white transition-all duration-300 transform hover:scale-105 hover:shadow-lg {{ request()->routeIs('admin.settings*') ? 'bg-gradient-to-r from-slate-600 to-gray-600 text-white shadow-lg shadow-slate-500/25' : '' }}">
                        <i class="fas fa-cog w-5 h-5 mr-3"></i>
                        <span class="font-medium">Configurações</span>
                        <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <i class="fas fa-chevron-right w-3 h-3"></i>
                        </div>
                    </a>
                    

                </div>
            </nav>
            
            <!-- User Info -->
            <div class="flex-shrink-0 p-4 border-t border-slate-700 bg-gradient-to-r from-slate-800 to-slate-900">
                <div class="flex items-center group hover:bg-slate-700/30 rounded-xl p-2 transition-all duration-300">
                    <div class="flex-shrink-0 relative">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg ring-2 ring-slate-600 group-hover:ring-blue-400 transition-all duration-300">
                            <span class="text-sm font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-slate-800"></div>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate group-hover:text-blue-300 transition-colors duration-300">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-400 truncate group-hover:text-slate-300 transition-colors duration-300">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('auth.logout') }}" class="ml-2">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-red-400 hover:bg-red-500/10 p-2 rounded-lg transition-all duration-200 transform hover:scale-110" title="Sair">
                            <i class="fas fa-sign-out-alt w-4 h-4"></i>
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