@extends('layouts.admin')

@section('title', 'Dashboard Administrativo')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6 bg-gradient-to-r from-blue-500 to-blue-600 text-white">
            <h1 class="text-2xl font-bold">Bem-vindo, {{ $user->name }}!</h1>
            <p class="mt-2 text-blue-100">Aqui está um resumo das atividades do sistema.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-users text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total de Usuários</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_users']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Usuários Ativos</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['active_users']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Logins -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Logins Hoje</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['recent_logins']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-check text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Status do Sistema</dt>
                            <dd class="text-lg font-medium text-green-600">{{ $stats['system_status'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activity -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Atividade Recente</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">Sistema iniciado com sucesso</p>
                            <p class="text-xs text-gray-500">{{ now()->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-2 h-2 bg-blue-400 rounded-full"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">Usuário {{ $user->name }} fez login</p>
                            <p class="text-xs text-gray-500">{{ now()->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-2 h-2 bg-yellow-400 rounded-full"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">Dashboard carregado</p>
                            <p class="text-xs text-gray-500">{{ now()->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Ações Rápidas</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('admin.orcamentos.create') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                        <i class="fas fa-file-invoice-dollar text-blue-500 text-3xl mb-2"></i>
                        <span class="text-sm font-medium text-gray-900">Novo Orçamento</span>
                    </a>
                    
                    <!-- Clientes/Fornecedores unificado - API OMIE -->
                    <a href="{{ route('admin.omie.pessoas') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                        <i class="fas fa-users text-blue-500 text-3xl mb-2"></i>
                        <span class="text-sm font-medium text-gray-900 text-center">Clientes/<br>Fornecedores</span>
                    </a>
                    
                    <a href="{{ route('admin.bases.index') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                        <i class="fas fa-map-marker-alt text-red-500 text-3xl mb-2"></i>
                        <span class="text-sm font-medium text-gray-900">Bases</span>
                    </a>
                    
                    <a href="{{ route('admin.marcas.index') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                        <i class="fas fa-tags text-purple-500 text-3xl mb-2"></i>
                        <span class="text-sm font-medium text-gray-900">Marcas</span>
                    </a>
                    
                    <a href="{{ route('admin.centros-custo.index') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                        <i class="fas fa-building text-indigo-500 text-3xl mb-2"></i>
                        <span class="text-sm font-medium text-gray-900">Centros de Custo</span>
                    </a>
                    
                    <a href="{{ route('admin.impostos.index') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                        <i class="fas fa-percentage text-yellow-500 text-3xl mb-2"></i>
                        <span class="text-sm font-medium text-gray-900">Impostos</span>
                    </a>
                    
                    <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                        <i class="fas fa-users text-purple-500 text-3xl mb-2"></i>
                        <span class="text-sm font-medium text-gray-900">Usuários</span>
                    </a>
                    
                    <a href="{{ route('admin.reports') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                        <i class="fas fa-chart-bar text-teal-500 text-3xl mb-2"></i>
                        <span class="text-sm font-medium text-gray-900">Relatórios</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection