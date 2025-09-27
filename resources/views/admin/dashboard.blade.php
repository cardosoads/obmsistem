@extends('layouts.admin')

@section('title', 'Dashboard Administrativo')
@section('page-title', 'Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="relative overflow-hidden" style="background: linear-gradient(135deg, #1E3951 0%, #2A4A63 100%);">
        <div class="absolute inset-0 bg-black opacity-5"></div>
        <div class="absolute top-0 right-0 -mt-8 -mr-8 w-40 h-40 rounded-full" style="background: #F8AB14; opacity: 0.1;"></div>
        <div class="absolute bottom-0 left-0 -mb-6 -ml-6 w-32 h-32 rounded-full" style="background: #F8AB14; opacity: 0.08;"></div>
        <div class="relative px-6 py-12">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-white mb-3">Olá, {{ $user->name }}! 👋</h1>
                        <p class="text-blue-100 text-xl mb-2">Bem-vindo ao seu painel de controle</p>
                        <p class="text-blue-200 text-sm">{{ now()->format('l, d \d\e F \d\e Y') }} • {{ now()->format('H:i') }}</p>
                    </div>
                    <div class="hidden lg:block">
                        <div class="w-24 h-24 rounded-2xl flex items-center justify-center" style="background: rgba(248, 171, 20, 0.2);">
                            <i class="fas fa-tachometer-alt text-white text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 -mt-8 relative z-10">

        <!-- Cards de Métricas Principais -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total de Orçamentos -->
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 mb-2">Total de Orçamentos</p>
                            <p class="text-3xl font-bold" style="color: #1E3951;">{{ number_format($stats['total_orcamentos'] ?? 0) }}</p>
                            <div class="flex items-center mt-2">
                                <span class="text-xs px-2 py-1 rounded-full" style="background: rgba(248, 171, 20, 0.1); color: #F8AB14;">
                                    <i class="fas fa-arrow-up mr-1"></i>+15% este mês
                                </span>
                            </div>
                        </div>
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #1E3951, #2A4A63);">
                            <i class="fas fa-file-invoice-dollar text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Valor Total dos Orçamentos -->
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 mb-2">Valor Total</p>
                            <p class="text-3xl font-bold" style="color: #1E3951;">R$ {{ number_format($stats['valor_total_orcamentos'] ?? 0, 0, ',', '.') }}</p>
                            <div class="flex items-center mt-2">
                                <span class="text-xs px-2 py-1 rounded-full" style="background: rgba(248, 171, 20, 0.1); color: #F8AB14;">
                                    <i class="fas fa-chart-line mr-1"></i>Crescimento
                                </span>
                            </div>
                        </div>
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #F8AB14, #E09712);">
                            <i class="fas fa-dollar-sign text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usuários Ativos -->
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 mb-2">Usuários Ativos</p>
                            <p class="text-3xl font-bold" style="color: #1E3951;">{{ number_format($stats['active_users'] ?? 0) }}</p>
                            <div class="flex items-center mt-2">
                                <span class="text-xs px-2 py-1 rounded-full" style="background: rgba(30, 57, 81, 0.1); color: #1E3951;">
                                    <i class="fas fa-users mr-1"></i>Online agora
                                </span>
                            </div>
                        </div>
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #1E3951, #2A4A63);">
                            <i class="fas fa-user-check text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status do Sistema -->
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 mb-2">Sistema</p>
                            <p class="text-2xl font-bold" style="color: #1E3951;">Operacional</p>
                            <div class="flex items-center mt-2">
                                <span class="text-xs px-2 py-1 rounded-full" style="background: rgba(30, 57, 81, 0.1); color: #1E3951;">
                                    <i class="fas fa-circle mr-1 animate-pulse" style="color: #1E3951;"></i>99.9% uptime
                                </span>
                            </div>
                        </div>
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #1E3951, #2A4A63);">
                            <i class="fas fa-server text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações Rápidas - Largura Total -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 mb-8">
            <div class="px-8 py-6 border-b border-gray-100">
                <h3 class="text-xl font-bold flex items-center" style="color: #1E3951;">
                    <i class="fas fa-bolt mr-3" style="color: #F8AB14;"></i>
                    Ações Rápidas
                </h3>
                <p class="text-gray-600 text-sm mt-1">Acesso direto às principais funcionalidades do sistema</p>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <!-- Novo Orçamento -->
                    <a href="{{ route('admin.orcamentos.create') }}" class="group flex flex-col items-center p-6 rounded-xl transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" style="background: linear-gradient(135deg, rgba(30, 57, 81, 0.05), rgba(30, 57, 81, 0.1)); border: 1px solid rgba(30, 57, 81, 0.1);">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3" style="background: #1E3951;">
                            <i class="fas fa-plus text-white text-lg"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-900 text-center">Novo Orçamento</p>
                        <p class="text-xs text-gray-600 text-center mt-1">Criar orçamento</p>
                    </a>

                    <!-- Gerenciar Frotas -->
                    <a href="{{ route('admin.frotas.index') }}" class="group flex flex-col items-center p-6 rounded-xl transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" style="background: linear-gradient(135deg, rgba(30, 57, 81, 0.05), rgba(30, 57, 81, 0.1)); border: 1px solid rgba(30, 57, 81, 0.1);">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3" style="background: #1E3951;">
                            <i class="fas fa-car text-white text-lg"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-900 text-center">Frotas</p>
                        <p class="text-xs text-gray-600 text-center mt-1">Gerenciar veículos</p>
                    </a>

                    <!-- Recursos Humanos -->
                    <a href="{{ route('admin.recursos-humanos.index') }}" class="group flex flex-col items-center p-6 rounded-xl transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" style="background: linear-gradient(135deg, rgba(30, 57, 81, 0.05), rgba(30, 57, 81, 0.1)); border: 1px solid rgba(30, 57, 81, 0.1);">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3" style="background: #1E3951;">
                            <i class="fas fa-users text-white text-lg"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-900 text-center">RH</p>
                        <p class="text-xs text-gray-600 text-center mt-1">Funcionários</p>
                    </a>

                    <!-- Bases -->
                    <a href="{{ route('admin.bases.index') }}" class="group flex flex-col items-center p-6 rounded-xl transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" style="background: linear-gradient(135deg, rgba(30, 57, 81, 0.05), rgba(30, 57, 81, 0.1)); border: 1px solid rgba(30, 57, 81, 0.1);">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3" style="background: #1E3951;">
                            <i class="fas fa-map-marker-alt text-white text-lg"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-900 text-center">Bases</p>
                        <p class="text-xs text-gray-600 text-center mt-1">Localizações</p>
                    </a>

                    <!-- Relatórios -->
                    <a href="{{ route('admin.reports') }}" class="group flex flex-col items-center p-6 rounded-xl transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" style="background: linear-gradient(135deg, rgba(30, 57, 81, 0.05), rgba(30, 57, 81, 0.1)); border: 1px solid rgba(30, 57, 81, 0.1);">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3" style="background: #1E3951;">
                            <i class="fas fa-chart-bar text-white text-lg"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-900 text-center">Relatórios</p>
                        <p class="text-xs text-gray-600 text-center mt-1">Análises</p>
                    </a>

                    <!-- Configurações -->
                    <a href="{{ route('admin.settings') }}" class="group flex flex-col items-center p-6 rounded-xl transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" style="background: linear-gradient(135deg, rgba(30, 57, 81, 0.05), rgba(30, 57, 81, 0.1)); border: 1px solid rgba(30, 57, 81, 0.1);">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3" style="background: #1E3951;">
                            <i class="fas fa-cog text-white text-lg"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-900 text-center">Configurações</p>
                        <p class="text-xs text-gray-600 text-center mt-1">Sistema</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- Seção de Módulos do Sistema -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 mb-8">
            <div class="px-8 py-6 border-b border-gray-100">
                <h3 class="text-xl font-bold flex items-center" style="color: #1E3951;">
                    <i class="fas fa-th-large mr-3" style="color: #F8AB14;"></i>
                    Módulos do Sistema
                </h3>
                <p class="text-gray-600 text-sm mt-1">Visão geral dos principais módulos e suas estatísticas</p>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Recursos Humanos -->
                    <div class="group relative p-6 rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border" style="background: linear-gradient(135deg, rgba(30, 57, 81, 0.05), rgba(30, 57, 81, 0.1)); border-color: rgba(30, 57, 81, 0.2);">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: #1E3951;">
                                <i class="fas fa-users text-white text-lg"></i>
                            </div>
                            <a href="{{ route('admin.recursos-humanos.index') }}" class="transition-colors duration-200" style="color: #1E3951;" onmouseover="this.style.color='#2A4A63'" onmouseout="this.style.color='#1E3951'">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-3">Recursos Humanos</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total:</span>
                                <span class="font-semibold" style="color: #1E3951;">{{ number_format($modulesStats['total_funcionarios'] ?? 0) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Ativos:</span>
                                <span class="font-semibold" style="color: #1E3951;">{{ number_format($modulesStats['funcionarios_ativos'] ?? 0) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Custo Total:</span>
                                <span class="font-semibold" style="color: #F8AB14;">R$ {{ number_format($modulesStats['custo_total_funcionarios'] ?? 0, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Frotas -->
                    <div class="group relative p-6 rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border" style="background: linear-gradient(135deg, rgba(30, 57, 81, 0.05), rgba(30, 57, 81, 0.1)); border-color: rgba(30, 57, 81, 0.2);">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: #1E3951;">
                                <i class="fas fa-car text-white text-lg"></i>
                            </div>
                            <a href="{{ route('admin.frotas.index') }}" class="transition-colors duration-200" style="color: #1E3951;" onmouseover="this.style.color='#2A4A63'" onmouseout="this.style.color='#1E3951'">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-3">Frotas</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total:</span>
                                <span class="font-semibold" style="color: #1E3951;">{{ number_format($modulesStats['total_veiculos'] ?? 0) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Ativos:</span>
                                <span class="font-semibold" style="color: #1E3951;">{{ number_format($modulesStats['veiculos_ativos'] ?? 0) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Valor Médio:</span>
                                <span class="font-semibold" style="color: #F8AB14;">R$ {{ number_format($modulesStats['valor_medio_veiculo'] ?? 0, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bases -->
                    <div class="group relative p-6 rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border" style="background: linear-gradient(135deg, rgba(248, 171, 20, 0.05), rgba(248, 171, 20, 0.1)); border-color: rgba(248, 171, 20, 0.2);">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: #F8AB14;">
                                <i class="fas fa-map-marker-alt text-white text-lg"></i>
                            </div>
                            <a href="{{ route('admin.bases.index') }}" class="transition-colors duration-200" style="color: #F8AB14;" onmouseover="this.style.color='#E09712'" onmouseout="this.style.color='#F8AB14'">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-3">Bases</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total:</span>
                                <span class="font-semibold" style="color: #1E3951;">{{ number_format($modulesStats['total_bases'] ?? 0) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Ativas:</span>
                                <span class="font-semibold" style="color: #1E3951;">{{ number_format($modulesStats['bases_ativas'] ?? 0) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Regiões:</span>
                                <span class="font-semibold" style="color: #F8AB14;">{{ number_format($modulesStats['total_regioes'] ?? 0) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Orçamentos -->
                    <div class="group relative p-6 rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border" style="background: linear-gradient(135deg, rgba(248, 171, 20, 0.05), rgba(248, 171, 20, 0.1)); border-color: rgba(248, 171, 20, 0.2);">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: #F8AB14;">
                                <i class="fas fa-file-invoice-dollar text-white text-lg"></i>
                            </div>
                            <a href="{{ route('admin.orcamentos.index') }}" class="transition-colors duration-200" style="color: #F8AB14;" onmouseover="this.style.color='#E09712'" onmouseout="this.style.color='#F8AB14'">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-3">Orçamentos</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total:</span>
                                <span class="font-semibold" style="color: #1E3951;">{{ number_format($modulesStats['total_orcamentos'] ?? 0) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Aprovados:</span>
                                <span class="font-semibold" style="color: #1E3951;">{{ number_format($modulesStats['orcamentos_aprovados'] ?? 0) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Valor Médio:</span>
                                <span class="font-semibold" style="color: #F8AB14;">R$ {{ number_format($modulesStats['valor_medio_orcamento'] ?? 0, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Seção Principal: Atividades Recentes -->
        <div class="grid grid-cols-1 gap-8">
            <!-- Atividades Recentes -->
            <div class="w-full">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                    <div class="px-8 py-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold flex items-center" style="color: #1E3951;">
                                <i class="fas fa-clock mr-3" style="color: #F8AB14;"></i>
                                Atividades Recentes
                            </h3>
                            <span class="text-xs px-3 py-1 rounded-full" style="background: rgba(248, 171, 20, 0.1); color: #F8AB14;">Tempo real</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="space-y-6">
                            <div class="flex items-start space-x-4 p-4 rounded-xl hover:bg-gray-50 transition-all duration-200">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-4 h-4 rounded-full animate-pulse shadow-lg" style="background: #1E3951;"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">Sistema iniciado com sucesso</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ now()->format('d/m/Y H:i') }}</p>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                                        <div class="h-2 rounded-full transition-all duration-500" style="width: 100%; background: #1E3951;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4 p-4 rounded-xl hover:bg-gray-50 transition-all duration-200">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-4 h-4 rounded-full shadow-lg" style="background: #1E3951;"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">Usuário {{ $user->name }} fez login</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ now()->format('d/m/Y H:i') }}</p>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                                        <div class="h-2 rounded-full transition-all duration-500" style="width: 85%; background: #1E3951;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4 p-4 rounded-xl hover:bg-gray-50 transition-all duration-200">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-4 h-4 rounded-full shadow-lg" style="background: #F8AB14;"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">Dashboard carregado</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ now()->format('d/m/Y H:i') }}</p>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                                        <div class="h-2 rounded-full transition-all duration-500" style="width: 70%; background: #F8AB14;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 text-center">
                            <a href="#" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:shadow-md" style="background: rgba(30, 57, 81, 0.1); color: #1E3951;">
                                Ver todas as atividades <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection