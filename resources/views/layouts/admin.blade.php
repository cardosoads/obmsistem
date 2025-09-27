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
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Custom Scrollbar Styles */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #1E3951;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #F8AB14;
            transform: scale(1.1);
        }
        
        /* Firefox scrollbar */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #1E3951 rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="h-screen flex overflow-hidden" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl border-r border-gray-100 transform transition-all duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col" 
             :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
            
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 px-6 bg-white border-b border-gray-100 flex-shrink-0">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color: #1E3951;">
                        <i class="fas fa-chart-line text-white text-sm"></i>
                    </div>
                    <h1 class="text-xl font-bold tracking-tight" style="color: #1E3951;">OBM Sistema</h1>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4 custom-scrollbar" x-data="{ 
                cadastrosOpen: {{ request()->routeIs('admin.bases*', 'admin.marcas*') ? 'true' : 'false' }},
                omieOpen: {{ request()->routeIs('admin.omie*') ? 'true' : 'false' }},
                financeirosOpen: {{ request()->routeIs('admin.centros-custo*', 'admin.impostos*', 'admin.grupos-impostos*') ? 'true' : 'false' }},
                frotasOpen: {{ request()->routeIs('admin.tipos-veiculos*', 'admin.frotas*', 'admin.combustiveis*') ? 'true' : 'false' }}
            }">
                <div class="px-3 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="group flex items-center px-4 py-2.5 mx-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'text-white shadow-sm' : 'text-gray-700 hover:text-white' }}" 
                       style="{{ request()->routeIs('admin.dashboard') ? 'background-color: #1E3951;' : '' }}" 
                       onmouseover="if (!this.classList.contains('text-white')) { this.style.backgroundColor='#F8AB14'; this.style.color='white'; }" 
                       onmouseout="if (!{{ request()->routeIs('admin.dashboard') ? 'true' : 'false' }}) { this.style.backgroundColor=''; this.style.color=''; }">
                        <i class="fas fa-tachometer-alt w-5 h-5 mr-3" style="color: {{ request()->routeIs('admin.dashboard') ? 'white' : '#1E3951' }};"></i>
                        <span class="font-medium text-sm">Dashboard</span>
                    </a>
                    

                    
                    <!-- Cadastros -->
                    <div class="space-y-1">
                        <button @click="cadastrosOpen = !cadastrosOpen" 
                                class="group w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.bases*', 'admin.marcas*') ? 'text-white shadow-sm' : 'text-gray-700 hover:text-white' }}" 
                                style="{{ request()->routeIs('admin.bases*', 'admin.marcas*') ? 'background-color: #1E3951;' : '' }}" 
                                onmouseover="if (!this.classList.contains('text-white')) { this.style.backgroundColor='#F8AB14'; this.style.color='white'; }" 
                                onmouseout="if (!{{ request()->routeIs('admin.bases*', 'admin.marcas*') ? 'true' : 'false' }}) { this.style.backgroundColor=''; this.style.color=''; }">
                            <div class="flex items-center">
                                <i class="fas fa-address-book w-5 h-5 mr-3" style="color: {{ request()->routeIs('admin.bases*', 'admin.marcas*') ? 'white' : '#1E3951' }};"></i>
                                <span class="font-medium text-sm">Cadastros</span>
                            </div>
                            <i class="fas fa-chevron-down w-4 h-4 transition-all duration-200" style="color: {{ request()->routeIs('admin.bases*', 'admin.marcas*') ? 'white' : '#1E3951' }};" :class="{ 'rotate-180': cadastrosOpen }"></i>
                        </button>
                        
                        <div x-show="cadastrosOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-1"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-1"
                             class="ml-8 mr-2 space-y-1">

                            <a href="{{ route('admin.bases.index') }}" 
                               class="group flex items-center px-3 py-2 text-sm rounded-md transition-all duration-200 {{ request()->routeIs('admin.bases*') ? 'text-white shadow-sm' : 'text-gray-600 hover:text-white' }}" 
                               style="{{ request()->routeIs('admin.bases*') ? 'background-color: #F8AB14;' : '' }}" 
                               onmouseover="if (!this.classList.contains('text-white')) { this.style.backgroundColor='#F8AB14'; this.style.color='white'; }" 
                               onmouseout="if (!{{ request()->routeIs('admin.bases*') ? 'true' : 'false' }}) { this.style.backgroundColor=''; this.style.color=''; }">
                                <i class="fas fa-map-marker-alt w-4 h-4 mr-3" style="color: {{ request()->routeIs('admin.bases*') ? 'white' : '#1E3951' }};"></i>
                                <span>Bases</span>
                            </a>
                            <a href="{{ route('admin.marcas.index') }}" 
                               class="group flex items-center px-3 py-2 text-sm rounded-md transition-all duration-200 {{ request()->routeIs('admin.marcas*') ? 'text-white shadow-sm' : 'text-gray-600 hover:text-white' }}" 
                               style="{{ request()->routeIs('admin.marcas*') ? 'background-color: #F8AB14;' : '' }}" 
                               onmouseover="if (!this.classList.contains('text-white')) { this.style.backgroundColor='#F8AB14'; this.style.color='white'; }" 
                               onmouseout="if (!{{ request()->routeIs('admin.marcas*') ? 'true' : 'false' }}) { this.style.backgroundColor=''; this.style.color=''; }">
                                <i class="fas fa-tags w-4 h-4 mr-3" style="color: {{ request()->routeIs('admin.marcas*') ? 'white' : '#1E3951' }};"></i>
                                <span>Marcas</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Omie -->
                    <div class="space-y-1">
                        <button @click="omieOpen = !omieOpen" 
                                class="group w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.omie*') ? 'text-white shadow-sm' : 'text-gray-700 hover:text-white' }}" 
                                style="{{ request()->routeIs('admin.omie*') ? 'background-color: #1E3951;' : '' }}" 
                                onmouseover="if (!this.classList.contains('text-white')) { this.style.backgroundColor='#F8AB14'; this.style.color='white'; }" 
                                onmouseout="if (!{{ request()->routeIs('admin.omie*') ? 'true' : 'false' }}) { this.style.backgroundColor=''; this.style.color=''; }">
                            <div class="flex items-center">
                                <i class="fas fa-cloud w-5 h-5 mr-3" style="color: {{ request()->routeIs('admin.omie*') ? 'white' : '#1E3951' }};"></i>
                                <span class="font-medium text-sm">Omie</span>
                            </div>
                            <i class="fas fa-chevron-down w-4 h-4 transition-all duration-200" style="color: {{ request()->routeIs('admin.omie*') ? 'white' : '#1E3951' }};" :class="{ 'rotate-180': omieOpen }"></i>
                        </button>
                        
                        <div x-show="omieOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-1"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-1"
                             class="ml-8 mr-2 space-y-1">
                            <a href="{{ route('admin.omie.clientes') }}" 
                               class="group flex items-center px-3 py-2 text-sm rounded-md transition-all duration-200 {{ request()->routeIs('admin.omie.clientes', 'admin.omie.fornecedores') ? 'text-white shadow-sm' : 'text-gray-600 hover:text-white' }}" 
                               style="{{ request()->routeIs('admin.omie.clientes', 'admin.omie.fornecedores') ? 'background-color: #F8AB14;' : '' }}" 
                               onmouseover="if (!this.classList.contains('text-white')) { this.style.backgroundColor='#F8AB14'; this.style.color='white'; }" 
                               onmouseout="if (!{{ request()->routeIs('admin.omie.clientes', 'admin.omie.fornecedores') ? 'true' : 'false' }}) { this.style.backgroundColor=''; this.style.color=''; }">
                                <i class="fas fa-users w-4 h-4 mr-3" style="color: {{ request()->routeIs('admin.omie.clientes', 'admin.omie.fornecedores') ? 'white' : '#1E3951' }};"></i>
                                <span>Clientes/Fornecedores</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Financeiro -->
                    <div class="space-y-1">
                        <button @click="financeirosOpen = !financeirosOpen" 
                                class="group w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.centros-custo*', 'admin.impostos*', 'admin.grupos-impostos*') ? 'text-white shadow-sm' : 'text-gray-700 hover:text-white' }}" 
                                style="{{ request()->routeIs('admin.centros-custo*', 'admin.impostos*', 'admin.grupos-impostos*') ? 'background-color: #1E3951;' : '' }}" 
                                onmouseover="if (!this.classList.contains('text-white')) { this.style.backgroundColor='#F8AB14'; this.style.color='white'; }" 
                                onmouseout="if (!{{ request()->routeIs('admin.centros-custo*', 'admin.impostos*', 'admin.grupos-impostos*') ? 'true' : 'false' }}) { this.style.backgroundColor=''; this.style.color=''; }">
                            <div class="flex items-center">
                                <i class="fas fa-calculator w-5 h-5 mr-3" style="color: {{ request()->routeIs('admin.centros-custo*', 'admin.impostos*', 'admin.grupos-impostos*') ? 'white' : '#1E3951' }};"></i>
                                <span class="font-medium text-sm">Financeiro</span>
                            </div>
                            <i class="fas fa-chevron-down w-4 h-4 transition-all duration-200" style="color: {{ request()->routeIs('admin.centros-custo*', 'admin.impostos*', 'admin.grupos-impostos*') ? 'white' : '#1E3951' }};" :class="{ 'rotate-180': financeirosOpen }"></i>
                        </button>
                        
                        <div x-show="financeirosOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-1"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-1"
                             class="ml-8 mr-2 space-y-1">
                            <a href="{{ route('admin.centros-custo.index') }}" 
                               class="group flex items-center px-3 py-2 text-sm rounded-md transition-all duration-200 {{ request()->routeIs('admin.centros-custo*') ? 'text-white shadow-sm' : 'text-gray-600 hover:text-white' }}" 
                               style="{{ request()->routeIs('admin.centros-custo*') ? 'background-color: #F8AB14;' : '' }}" 
                               onmouseover="if (!this.classList.contains('text-white')) { this.style.backgroundColor='#F8AB14'; this.style.color='white'; }" 
                               onmouseout="if (!{{ request()->routeIs('admin.centros-custo*') ? 'true' : 'false' }}) { this.style.backgroundColor=''; this.style.color=''; }">
                                <i class="fas fa-building w-4 h-4 mr-3" style="color: {{ request()->routeIs('admin.centros-custo*') ? 'white' : '#1E3951' }};"></i>
                                <span>Centros de Custo</span>
                            </a>
                            <a href="{{ route('admin.impostos.index') }}" 
                               class="group flex items-center px-3 py-2 text-sm rounded-md transition-all duration-200 {{ request()->routeIs('admin.impostos.index') ? 'text-white shadow-sm' : 'text-gray-600 hover:text-white' }}" 
                               style="{{ request()->routeIs('admin.impostos.index') ? 'background-color: #F8AB14;' : '' }}" 
                               onmouseover="if (!this.classList.contains('text-white')) { this.style.backgroundColor='#F8AB14'; this.style.color='white'; }" 
                               onmouseout="if (!{{ request()->routeIs('admin.impostos.index') ? 'true' : 'false' }}) { this.style.backgroundColor=''; this.style.color=''; }">
                                <i class="fas fa-percentage w-4 h-4 mr-3" style="color: {{ request()->routeIs('admin.impostos.index') ? 'white' : '#1E3951' }};"></i>
                                <span>Impostos</span>
                            </a>
                            <a href="{{ route('admin.grupos-impostos.index') }}" 
                               class="group flex items-center px-3 py-2 text-sm rounded-md transition-all duration-200 {{ request()->routeIs('admin.grupos-impostos*') ? 'text-white shadow-sm' : 'text-gray-600 hover:text-white' }}" 
                               style="{{ request()->routeIs('admin.grupos-impostos*') ? 'background-color: #F8AB14;' : '' }}" 
                               onmouseover="if (!this.classList.contains('text-white')) { this.style.backgroundColor='#F8AB14'; this.style.color='white'; }" 
                               onmouseout="if (!{{ request()->routeIs('admin.grupos-impostos*') ? 'true' : 'false' }}) { this.style.backgroundColor=''; this.style.color=''; }">
                                <i class="fas fa-layer-group w-4 h-4 mr-3" style="color: {{ request()->routeIs('admin.grupos-impostos*') ? 'white' : '#1E3951' }};"></i>
                                <span>Grupos de Impostos</span>
                            </a>

                        </div>
                    </div>
                    
                    <!-- Orçamentos -->
                    <a href="{{ route('admin.orcamentos.index') }}" 
                       class="group flex items-center px-4 py-2.5 mx-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.orcamentos*') ? 'text-white shadow-sm' : 'text-gray-700 hover:text-white' }}" 
                       style="{{ request()->routeIs('admin.orcamentos*') ? 'background-color: #1E3951;' : '' }}" 
                       onmouseover="if (!this.classList.contains('text-white')) { this.style.backgroundColor='#F8AB14'; this.style.color='white'; }" 
                       onmouseout="if (!{{ request()->routeIs('admin.orcamentos*') ? 'true' : 'false' }}) { this.style.backgroundColor=''; this.style.color=''; }">
                        <i class="fas fa-file-invoice w-5 h-5 mr-3" style="color: {{ request()->routeIs('admin.orcamentos*') ? 'white' : '#1E3951' }};"></i>
                        <span class="font-medium text-sm">Orçamentos</span>
                    </a>
                    
                    <!-- Recursos Humanos -->
                    <a href="{{ route('admin.recursos-humanos.index') }}" 
                       class="group flex items-center px-4 py-2.5 mx-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.recursos-humanos*') ? 'text-white shadow-sm' : 'text-gray-700 hover:text-white' }}" 
                       style="{{ request()->routeIs('admin.recursos-humanos*') ? 'background-color: #1E3951;' : '' }}" 
                       onmouseover="if (!this.classList.contains('text-white')) { this.style.backgroundColor='#F8AB14'; this.style.color='white'; }" 
                       onmouseout="if (!{{ request()->routeIs('admin.recursos-humanos*') ? 'true' : 'false' }}) { this.style.backgroundColor=''; this.style.color=''; }">
                        <i class="fas fa-user-tie w-5 h-5 mr-3" style="color: {{ request()->routeIs('admin.recursos-humanos*') ? 'white' : '#1E3951' }};"></i>
                        <span class="font-medium text-sm">Recursos Humanos</span>
                    </a>
                    
                    <!-- Frotas e Veículos -->
                    <div class="space-y-1">
                        <button @click="frotasOpen = !frotasOpen" 
                                class="group w-full flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.tipos-veiculos*', 'admin.frotas*', 'admin.combustiveis*') ? 'text-white shadow-sm' : 'text-gray-700 hover:text-white' }}" 
                                style="{{ request()->routeIs('admin.tipos-veiculos*', 'admin.frotas*', 'admin.combustiveis*') ? 'background-color: #1E3951;' : '' }}" 
                                onmouseover="if (!this.classList.contains('text-white')) { this.style.backgroundColor='#F8AB14'; this.style.color='white'; }" 
                                onmouseout="if (!{{ request()->routeIs('admin.tipos-veiculos*', 'admin.frotas*', 'admin.combustiveis*') ? 'true' : 'false' }}) { this.style.backgroundColor=''; this.style.color=''; }">
                            <div class="flex items-center">
                                <i class="fas fa-truck w-5 h-5 mr-3" style="color: {{ request()->routeIs('admin.tipos-veiculos*', 'admin.frotas*', 'admin.combustiveis*') ? 'white' : '#1E3951' }};"></i>
                                <span class="font-medium text-sm">Frotas e Veículos</span>
                            </div>
                            <i class="fas fa-chevron-down w-4 h-4 transition-all duration-200" style="color: {{ request()->routeIs('admin.tipos-veiculos*', 'admin.frotas*', 'admin.combustiveis*') ? 'white' : '#1E3951' }};" :class="{ 'rotate-180': frotasOpen }"></i>
                        </button>
                        
                        <div x-show="frotasOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-1"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-1"
                             class="ml-8 mr-2 space-y-1">

                            <a href="{{ route('admin.tipos-veiculos.index') }}" 
                               class="group flex items-center px-3 py-2 text-sm rounded-md transition-all duration-200 {{ request()->routeIs('admin.tipos-veiculos*') ? 'text-white shadow-sm' : 'text-gray-600 hover:text-white' }}" 
                               style="{{ request()->routeIs('admin.tipos-veiculos*') ? 'background-color: #F8AB14;' : '' }}" 
                               onmouseover="if (!this.classList.contains('text-white')) { this.style.backgroundColor='#F8AB14'; this.style.color='white'; }" 
                               onmouseout="if (!{{ request()->routeIs('admin.tipos-veiculos*') ? 'true' : 'false' }}) { this.style.backgroundColor=''; this.style.color=''; }">
                                <i class="fas fa-car w-4 h-4 mr-3" style="color: {{ request()->routeIs('admin.tipos-veiculos*') ? 'white' : '#1E3951' }};"></i>
                                <span>Tipos de Veículos</span>
                            </a>
                            <a href="{{ route('admin.frotas.index') }}" 
                               class="group flex items-center px-3 py-2 text-sm rounded-md transition-all duration-200 {{ request()->routeIs('admin.frotas*') ? 'text-white shadow-sm' : 'text-gray-600 hover:text-white' }}" 
                               style="{{ request()->routeIs('admin.frotas*') ? 'background-color: #F8AB14;' : '' }}" 
                               onmouseover="if (!this.classList.contains('text-white')) { this.style.backgroundColor='#F8AB14'; this.style.color='white'; }" 
                               onmouseout="if (!{{ request()->routeIs('admin.frotas*') ? 'true' : 'false' }}) { this.style.backgroundColor=''; this.style.color=''; }">
                                <i class="fas fa-truck-moving w-4 h-4 mr-3" style="color: {{ request()->routeIs('admin.frotas*') ? 'white' : '#1E3951' }};"></i>
                                <span>Frotas</span>
                            </a>
                            <a href="{{ route('admin.combustiveis.index') }}" 
                               class="group flex items-center px-3 py-2 text-sm rounded-md transition-all duration-200 {{ request()->routeIs('admin.combustiveis*') ? 'text-white shadow-sm' : 'text-gray-600 hover:text-white' }}" 
                               style="{{ request()->routeIs('admin.combustiveis*') ? 'background-color: #F8AB14;' : '' }}" 
                               onmouseover="if (!this.classList.contains('text-white')) { this.style.backgroundColor='#F8AB14'; this.style.color='white'; }" 
                               onmouseout="if (!{{ request()->routeIs('admin.combustiveis*') ? 'true' : 'false' }}) { this.style.backgroundColor=''; this.style.color=''; }">
                                <i class="fas fa-gas-pump w-4 h-4 mr-3" style="color: {{ request()->routeIs('admin.combustiveis*') ? 'white' : '#1E3951' }};"></i>
                                <span>Combustíveis</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Divisor -->
                    <div class="relative my-4 mx-4">
                        <div class="border-t border-gray-200"></div>
                    </div>
                    
                    <!-- Usuários -->
                    <a href="{{ route('admin.users.index') }}" 
                       class="group flex items-center px-4 py-2.5 mx-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users*') ? 'text-white shadow-sm' : 'text-gray-700 hover:text-white' }}" 
                       style="{{ request()->routeIs('admin.users*') ? 'background-color: #1E3951;' : '' }}" 
                       onmouseover="if (!this.classList.contains('text-white')) { this.style.backgroundColor='#F8AB14'; this.style.color='white'; }" 
                       onmouseout="if (!{{ request()->routeIs('admin.users*') ? 'true' : 'false' }}) { this.style.backgroundColor=''; this.style.color=''; }">
                        <i class="fas fa-users w-5 h-5 mr-3" style="color: {{ request()->routeIs('admin.users*') ? 'white' : '#1E3951' }};"></i>
                        <span class="font-medium text-sm">Usuários</span>
                    </a>
                    
                    <!-- Relatórios -->
                    <a href="{{ route('admin.reports') }}" 
                       class="group flex items-center px-4 py-2.5 mx-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reports*') ? 'text-white shadow-sm' : 'text-gray-700 hover:text-white' }}" 
                       style="{{ request()->routeIs('admin.reports*') ? 'background-color: #1E3951;' : '' }}" 
                       onmouseover="if (!this.classList.contains('text-white')) { this.style.backgroundColor='#F8AB14'; this.style.color='white'; }" 
                       onmouseout="if (!{{ request()->routeIs('admin.reports*') ? 'true' : 'false' }}) { this.style.backgroundColor=''; this.style.color=''; }">
                        <i class="fas fa-chart-bar w-5 h-5 mr-3" style="color: {{ request()->routeIs('admin.reports*') ? 'white' : '#1E3951' }};"></i>
                        <span class="font-medium text-sm">Relatórios</span>
                    </a>
                    
                    <!-- Configurações -->
                    <a href="{{ route('admin.settings') }}" 
                       class="group flex items-center px-4 py-2.5 mx-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.settings*') ? 'text-white shadow-sm' : 'text-gray-700 hover:text-white' }}" 
                       style="{{ request()->routeIs('admin.settings*') ? 'background-color: #1E3951;' : '' }}" 
                       onmouseover="if (!this.classList.contains('text-white')) { this.style.backgroundColor='#F8AB14'; this.style.color='white'; }" 
                       onmouseout="if (!{{ request()->routeIs('admin.settings*') ? 'true' : 'false' }}) { this.style.backgroundColor=''; this.style.color=''; }">
                        <i class="fas fa-cog w-5 h-5 mr-3" style="color: {{ request()->routeIs('admin.settings*') ? 'white' : '#1E3951' }};"></i>
                        <span class="font-medium text-sm">Configurações</span>
                    </a>
                    

                </div>
            </nav>
            
            <!-- User Info -->
            <div class="flex-shrink-0 p-4 border-t border-gray-200 bg-white">
                <div class="flex items-center group hover:bg-gray-50 rounded-lg p-3 transition-all duration-200">
                    <div class="flex-shrink-0 relative">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shadow-sm ring-2 ring-gray-200 group-hover:ring-gray-300 transition-all duration-200" style="background-color: #F8AB14;">
                            <span class="text-sm font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div class="absolute -top-1 -right-1 w-3 h-3 rounded-full border-2 border-white" style="background: #1E3951;"></div>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium truncate" style="color: #1E3951;">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('auth.logout') }}" class="ml-2">
                        @csrf
                        <button type="submit" class="text-gray-400 p-2 rounded-lg transition-all duration-200" style="hover:color: #1E3951; hover:background: rgba(30, 57, 81, 0.1);" title="Sair" onmouseover="this.style.color='#1E3951'; this.style.background='rgba(30, 57, 81, 0.1)'" onmouseout="this.style.color=''; this.style.background=''">
                            <i class="fas fa-sign-out-alt w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Mobile sidebar overlay -->
        <div class="fixed inset-0 z-40 bg-neutral-600 bg-opacity-75 lg:hidden" 
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
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-neutral-200 h-16 flex items-center justify-between px-6 flex-shrink-0">
                <div class="flex items-center space-x-4">
                    <!-- Mobile menu toggle -->
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="lg:hidden p-2 rounded-lg text-neutral-600 hover:text-primary-900 hover:bg-neutral-100 transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-bars w-5 h-5"></i>
                    </button>
                    
                    <!-- Back button -->
                    <button onclick="history.back()" 
                            class="p-2 rounded-lg text-neutral-600 hover:text-primary-900 hover:bg-neutral-100 transition-all duration-200 transform hover:scale-105" 
                            title="Voltar">
                        <i class="fas fa-arrow-left w-5 h-5"></i>
                    </button>
                    
                    <!-- Page title -->
                    <h1 class="text-xl font-semibold text-primary-900">@yield('page-title', 'Dashboard')</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- User info -->
                    <div class="flex items-center space-x-3">
                        <div class="hidden sm:block text-right">
                            <p class="text-sm font-medium" style="color: #1E3951;">{{ auth()->user()->name }}</p>
                            <p class="text-xs" style="color: #6B7280;">{{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                        <div class="relative">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center shadow-md ring-2 ring-white" style="background: linear-gradient(135deg, #F8AB14 0%, #E09712 100%);">
                                <span class="text-xs font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <div class="absolute -top-1 -right-1 w-3 h-3 rounded-full border-2 border-white" style="background: #1E3951;"></div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page content -->
            <main class="flex-1 overflow-y-auto bg-neutral-50">
                <div class="p-6">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="mb-4 bg-success-100 border border-success-400 text-success-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="mb-4 bg-error-100 border border-error-400 text-error-700 px-4 py-3 rounded relative" role="alert">
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