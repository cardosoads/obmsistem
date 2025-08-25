@extends('layouts.admin')

@section('title', 'Dashboard Administrativo')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Welcome Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 rounded-2xl shadow-xl">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white opacity-10 rounded-full"></div>
        <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-24 h-24 bg-white opacity-5 rounded-full"></div>
        <div class="relative p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Olá, {{ $user->name }}! 👋</h1>
                    <p class="text-blue-100 text-lg">Bem-vindo de volta ao seu painel administrativo</p>
                    <p class="text-blue-200 text-sm mt-1">{{ now()->format('l, d \d\e F \d\e Y') }}</p>
                </div>
                <div class="hidden md:block">
                    <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="group bg-white overflow-hidden shadow-lg rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Total de Usuários</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</p>
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>+12% este mês
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-users text-white text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="group bg-white overflow-hidden shadow-lg rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Usuários Ativos</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['active_users']) }}</p>
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>+8% esta semana
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-user-check text-white text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Logins -->
        <div class="group bg-white overflow-hidden shadow-lg rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Logins Hoje</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['recent_logins']) }}</p>
                        <p class="text-xs text-yellow-600 mt-1">
                            <i class="fas fa-clock mr-1"></i>Últimas 24h
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-sign-in-alt text-white text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="group bg-white overflow-hidden shadow-lg rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Status do Sistema</p>
                        <p class="text-xl font-bold text-green-600">{{ $stats['system_status'] }}</p>
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-circle text-green-400 mr-1 animate-pulse"></i>Online
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-teal-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-server text-white text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Activity -->
        <div class="lg:col-span-2">
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-history text-blue-500 mr-2"></i>
                            Atividade Recente
                        </h3>
                        <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded-full">Tempo real</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start space-x-4 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Sistema iniciado com sucesso</p>
                                <p class="text-xs text-gray-500 mt-1">{{ now()->format('d/m/Y H:i') }}</p>
                                <div class="w-full bg-gray-200 rounded-full h-1 mt-2">
                                    <div class="bg-green-400 h-1 rounded-full" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-3 h-3 bg-blue-400 rounded-full"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Usuário {{ $user->name }} fez login</p>
                                <p class="text-xs text-gray-500 mt-1">{{ now()->format('d/m/Y H:i') }}</p>
                                <div class="w-full bg-gray-200 rounded-full h-1 mt-2">
                                    <div class="bg-blue-400 h-1 rounded-full" style="width: 85%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Dashboard carregado</p>
                                <p class="text-xs text-gray-500 mt-1">{{ now()->format('d/m/Y H:i') }}</p>
                                <div class="w-full bg-gray-200 rounded-full h-1 mt-2">
                                    <div class="bg-yellow-400 h-1 rounded-full" style="width: 70%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 text-center">
                        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200">
                            Ver todas as atividades <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                        Ações Rápidas
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <!-- Orçamentos -->
                        <a href="{{ route('admin.orcamentos.index') }}" class="group flex items-center p-3 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg hover:from-blue-100 hover:to-blue-200 transition-all duration-200 transform hover:scale-105">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-600 transition-colors duration-200">
                                <i class="fas fa-file-invoice-dollar text-white"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Orçamentos</p>
                                <p class="text-xs text-gray-500">Gerenciar orçamentos</p>
                            </div>
                        </a>

                        <!-- Clientes/Fornecedores -->
                        <a href="{{ route('admin.omie.pessoas') }}" class="group flex items-center p-3 bg-gradient-to-r from-green-50 to-green-100 rounded-lg hover:from-green-100 hover:to-green-200 transition-all duration-200 transform hover:scale-105">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-600 transition-colors duration-200">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Clientes</p>
                                <p class="text-xs text-gray-500">API OMIE</p>
                            </div>
                        </a>

                        <!-- Bases -->
                        <a href="{{ route('admin.bases.index') }}" class="group flex items-center p-3 bg-gradient-to-r from-red-50 to-red-100 rounded-lg hover:from-red-100 hover:to-red-200 transition-all duration-200 transform hover:scale-105">
                            <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center mr-3 group-hover:bg-red-600 transition-colors duration-200">
                                <i class="fas fa-map-marker-alt text-white"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Bases</p>
                                <p class="text-xs text-gray-500">Localizações</p>
                            </div>
                        </a>

                        <!-- Marcas -->
                        <a href="{{ route('admin.marcas.index') }}" class="group flex items-center p-3 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg hover:from-purple-100 hover:to-purple-200 transition-all duration-200 transform hover:scale-105">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-600 transition-colors duration-200">
                                <i class="fas fa-tags text-white"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Marcas</p>
                                <p class="text-xs text-gray-500">Gerenciar marcas</p>
                            </div>
                        </a>

                        <!-- Centros de Custo -->
                        <a href="{{ route('admin.centros-custo.index') }}" class="group flex items-center p-3 bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-lg hover:from-indigo-100 hover:to-indigo-200 transition-all duration-200 transform hover:scale-105">
                            <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center mr-3 group-hover:bg-indigo-600 transition-colors duration-200">
                                <i class="fas fa-building text-white"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Centros de Custo</p>
                                <p class="text-xs text-gray-500">Controle financeiro</p>
                            </div>
                        </a>

                        <!-- Relatórios -->
                        <a href="{{ route('admin.reports') }}" class="group flex items-center p-3 bg-gradient-to-r from-teal-50 to-teal-100 rounded-lg hover:from-teal-100 hover:to-teal-200 transition-all duration-200 transform hover:scale-105">
                            <div class="w-10 h-10 bg-teal-500 rounded-lg flex items-center justify-center mr-3 group-hover:bg-teal-600 transition-colors duration-200">
                                <i class="fas fa-chart-bar text-white"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Relatórios</p>
                                <p class="text-xs text-gray-500">Análises e dados</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection