@extends('layouts.admin')

@section('title', 'Criar Novo Orçamento')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200/60 p-8 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Criar Novo Orçamento</h1>
                        <p class="text-slate-500 mt-1">Preencha os dados para gerar um orçamento detalhado</p>
                    </div>
                </div>
                <a href="{{ route('admin.orcamentos.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-slate-600 to-slate-700 text-white font-medium rounded-xl shadow-lg hover:from-slate-700 hover:to-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar
                </a>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200/60 overflow-hidden">

            <form action="{{ route('admin.orcamentos.store') }}" method="POST" id="orcamentoForm">
                @csrf
                <div class="p-8">
                    @if ($errors->any())
                        <div class="bg-gradient-to-r from-red-50 to-rose-50 border border-red-200/60 rounded-2xl p-6 mb-8 shadow-lg">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-red-800 font-semibold">Atenção! Corrija os seguintes erros:</h4>
                            </div>
                            <ul class="space-y-2">
                                @foreach ($errors->all() as $error)
                                    <li class="flex items-center text-red-700">
                                        <svg class="w-3 h-3 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Seção: Informações Básicas -->
                    <div class="mb-12">
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-slate-800">Informações Básicas</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Data de Solicitação -->
                            <div class="group">
                                <label for="data_solicitacao" class="block text-sm font-semibold text-slate-700 mb-3">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Data de Solicitação
                                        <span class="text-red-500 ml-1">*</span>
                                    </span>
                                </label>
                                <input type="date" 
                                       class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 transition-all duration-200 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300 @error('data_solicitacao') border-red-300 bg-red-50 text-red-900 focus:border-red-500 focus:ring-red-500/10 @enderror" 
                                       id="data_solicitacao" 
                                       name="data_solicitacao" 
                                       value="{{ old('data_solicitacao', date('Y-m-d')) }}" 
                                       required>
                                @error('data_solicitacao')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Centro de Custo -->
                            <div class="group">
                                <label for="centro_custo_search" class="block text-sm font-semibold text-slate-700 mb-3">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        Centro de Custo
                                        <span class="text-red-500 ml-1">*</span>
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="text" 
                                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 transition-all duration-200 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300 @error('centro_custo_id') border-red-300 bg-red-50 text-red-900 focus:border-red-500 focus:ring-red-500/10 @enderror" 
                                           id="centro_custo_search" 
                                           placeholder="Digite o código ou nome do centro de custo..." 
                                           autocomplete="off">
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    
                                    <!-- Dropdown de sugestões -->
                                    <div id="centro_custo_dropdown" class="absolute z-50 w-full mt-2 bg-white border-2 border-slate-200 rounded-xl shadow-xl hidden max-h-60 overflow-y-auto">
                                        <div id="centro_custo_results"></div>
                                        <div id="centro_custo_no_results" class="px-4 py-3 text-sm text-slate-500 hidden">
                                            Nenhum centro de custo encontrado
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Campo hidden para armazenar o ID selecionado -->
                                <input type="hidden" id="centro_custo_id" name="centro_custo_id" value="{{ old('centro_custo_id') }}" required>
                                
                                <!-- Exibir centro de custo selecionado -->
                                <div id="centro_custo_selecionado" class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg hidden">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="text-sm font-medium text-blue-800">Centro de Custo Selecionado:</span>
                                            <div class="text-blue-700" id="nome_centro_custo_selecionado"></div>
                                        </div>
                                        <button type="button" onclick="limparCentroCusto()" class="text-blue-600 hover:text-blue-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                
                                @error('centro_custo_id')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- ID de Protocolo -->
                            <div class="group">
                                <label for="id_protocolo" class="block text-sm font-semibold text-slate-700 mb-3">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        ID de Protocolo
                                    </span>
                                </label>
                                <input type="text" 
                                       class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 transition-all duration-200 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300 @error('id_protocolo') border-red-300 bg-red-50 text-red-900 focus:border-red-500 focus:ring-red-500/10 @enderror" 
                                       id="id_protocolo" 
                                       name="id_protocolo" 
                                       value="{{ old('id_protocolo') }}" 
                                       placeholder="Ex: PROT-2025-001">
                                @error('id_protocolo')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Número do Orçamento -->
                            <div class="group">
                                <label for="numero_orcamento" class="block text-sm font-semibold text-slate-700 mb-3">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                        </svg>
                                        Número do Orçamento
                                    </span>
                                </label>
                                <input type="text" 
                                       class="w-full px-4 py-3.5 bg-slate-100 border border-slate-200 rounded-xl text-slate-600 cursor-not-allowed" 
                                       id="numero_orcamento" 
                                       name="numero_orcamento" 
                                       value="{{ old('numero_orcamento', 'Gerado automaticamente') }}" 
                                       readonly>
                                <p class="mt-2 text-sm text-slate-500 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    O número será gerado automaticamente ao salvar
                                </p>
                            </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
                            <!-- Tipo de Orçamento -->
                            <div class="group">
                                <label for="tipo_orcamento" class="block text-sm font-semibold text-slate-700 mb-3">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                        Tipo de Orçamento
                                        <span class="text-red-500 ml-1">*</span>
                                    </span>
                                </label>
                                <select class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 transition-all duration-200 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300 @error('tipo_orcamento') border-red-300 bg-red-50 text-red-900 focus:border-red-500 focus:ring-red-500/10 @enderror" 
                                        id="tipo_orcamento" 
                                        name="tipo_orcamento" 
                                        required>
                                    <option value="" class="text-slate-400">Selecione o tipo</option>
                                    <option value="prestador" {{ old('tipo_orcamento') == 'prestador' ? 'selected' : '' }}>Prestador</option>
                                    <option value="aumento_km" {{ old('tipo_orcamento') == 'aumento_km' ? 'selected' : '' }}>Aumento KM</option>
                                    <option value="proprio_nova_rota" {{ old('tipo_orcamento') == 'proprio_nova_rota' ? 'selected' : '' }}>Próprio Nova Rota</option>
                                </select>
                                @error('tipo_orcamento')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Nome da Rota -->
                            <div class="group">
                                <label for="nome_rota" class="block text-sm font-semibold text-slate-700 mb-3">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                        </svg>
                                        Nome da Rota
                                        <span class="text-red-500 ml-1">*</span>
                                    </span>
                                </label>
                                <input type="text" 
                                       class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 transition-all duration-200 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300 @error('nome_rota') border-red-300 bg-red-50 text-red-900 focus:border-red-500 focus:ring-red-500/10 @enderror" 
                                       id="nome_rota" 
                                       name="nome_rota" 
                                       value="{{ old('nome_rota') }}" 
                                       placeholder="Ex: São Paulo - Rio de Janeiro" 
                                       required>
                                @error('nome_rota')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- ID LOGCARE -->
                            <div class="group">
                                <label for="id_logcare" class="block text-sm font-semibold text-slate-700 mb-3">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                        </svg>
                                        ID LOGCARE
                                    </span>
                                </label>
                                <input type="text" 
                                       class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 transition-all duration-200 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300 @error('id_logcare') border-red-300 bg-red-50 text-red-900 focus:border-red-500 focus:ring-red-500/10 @enderror" 
                                       id="id_logcare" 
                                       name="id_logcare" 
                                       value="{{ old('id_logcare') }}" 
                                       placeholder="Ex: LOG123456">
                                @error('id_logcare')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
                            <!-- Cliente OMIE -->
                            <div class="md:col-span-2 lg:col-span-3">
                                <label for="cliente_omie_search" class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Cliente (OMIE) <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" 
                                           class="block w-full pl-12 pr-12 py-4 text-base border-2 border-gray-200 rounded-xl bg-white shadow-sm transition-all duration-200 hover:border-gray-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none @error('cliente_omie_id') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-100 @enderror" 
                                           id="cliente_omie_search" 
                                           placeholder="Digite para buscar cliente..." 
                                           autocomplete="off">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    
                                    <!-- Client Dropdown Results -->
                                    <div id="client_dropdown" class="absolute z-50 w-full mt-2 bg-white border-2 border-gray-200 rounded-xl shadow-xl hidden max-h-60 overflow-y-auto">
                                        <!-- Loading State -->
                                        <div id="client_loading" class="px-6 py-4 text-center text-gray-500 hidden">
                                            <div class="inline-flex items-center">
                                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span class="text-sm font-medium">Buscando clientes...</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Results List -->
                                        <div id="client_results" class="divide-y divide-gray-200">
                                            <!-- Results will be dynamically inserted here -->
                                        </div>
                                        
                                        <!-- No Results State -->
                                        <div id="client_no_results" class="px-6 py-4 text-center text-gray-500 hidden">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0120 12a8 8 0 11-16 0 8 8 0 016.291-7.836z"></path>
                                                </svg>
                                                <p class="text-sm font-medium">Nenhum cliente encontrado</p>
                                                <p class="text-xs text-gray-400 mt-1">Tente buscar por outro termo</p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <input type="hidden" 
                                       id="cliente_omie_id" 
                                       name="cliente_omie_id" 
                                       value="{{ old('cliente_omie_id') }}">
                                <input type="hidden" 
                                       id="cliente_nome" 
                                       name="cliente_nome" 
                                       value="{{ old('cliente_nome') }}">
                                <p class="mt-3 text-sm text-gray-500 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Digite para buscar clientes na API OMIE
                                </p>
                                <div id="cliente_selecionado" class="mt-3 hidden">
                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-4 shadow-sm">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-sm text-green-800">
                                                <span class="font-semibold">Cliente selecionado:</span> 
                                                <span id="nome_cliente_selecionado" class="font-medium"></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @error('cliente_omie_id')
                                    <div class="mt-3 flex items-center text-sm text-red-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
                            <!-- Horário -->
                            <div>
                                <label for="horario" class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Horário <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <input type="time" 
                                           class="block w-full pl-12 pr-4 py-4 text-base border-2 border-gray-200 rounded-xl bg-gray-50 shadow-sm transition-all duration-200 hover:border-gray-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none @error('horario') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-100 @enderror" 
                                           id="horario" 
                                           name="horario" 
                                           value="{{ old('horario', '00:00') }}" 
                                           required>
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('horario')
                                    <div class="mt-3 flex items-center text-sm text-red-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Frequência de Atendimento -->
                            <div>
                                <label class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Frequência de Atendimento <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="flex flex-wrap gap-2">
                                    <label class="flex items-center px-3 py-2 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 group">
                                        <input type="checkbox" name="frequencia_atendimento[]" value="segunda" class="mr-2 w-4 h-4 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" {{ in_array('segunda', old('frequencia_atendimento', [])) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Seg</span>
                                    </label>
                                    <label class="flex items-center px-3 py-2 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 group">
                                        <input type="checkbox" name="frequencia_atendimento[]" value="terca" class="mr-2 w-4 h-4 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" {{ in_array('terca', old('frequencia_atendimento', [])) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Ter</span>
                                    </label>
                                    <label class="flex items-center px-3 py-2 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 group">
                                        <input type="checkbox" name="frequencia_atendimento[]" value="quarta" class="mr-2 w-4 h-4 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" {{ in_array('quarta', old('frequencia_atendimento', [])) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Qua</span>
                                    </label>
                                    <label class="flex items-center px-3 py-2 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 group">
                                        <input type="checkbox" name="frequencia_atendimento[]" value="quinta" class="mr-2 w-4 h-4 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" {{ in_array('quinta', old('frequencia_atendimento', [])) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Qui</span>
                                    </label>
                                    <label class="flex items-center px-3 py-2 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 group">
                                        <input type="checkbox" name="frequencia_atendimento[]" value="sexta" class="mr-2 w-4 h-4 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" {{ in_array('sexta', old('frequencia_atendimento', [])) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Sex</span>
                                    </label>
                                    <label class="flex items-center px-3 py-2 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 group">
                                        <input type="checkbox" name="frequencia_atendimento[]" value="sabado" class="mr-2 w-4 h-4 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" {{ in_array('sabado', old('frequencia_atendimento', [])) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Sáb</span>
                                    </label>
                                    <label class="flex items-center px-3 py-2 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 group">
                                        <input type="checkbox" name="frequencia_atendimento[]" value="domingo" class="mr-2 w-4 h-4 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" {{ in_array('domingo', old('frequencia_atendimento', [])) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Dom</span>
                                    </label>
                                </div>
                                @error('frequencia_atendimento')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">

                            <!-- Status -->
                            <div>
                                <label for="status" class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Status <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <select class="block w-full px-4 py-3 pr-10 border-2 border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 text-base bg-white hover:border-gray-300 appearance-none @error('status') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-200 @enderror" 
                                            id="status" 
                                            name="status" 
                                            required>
                                        <option value="">Selecione o status</option>
                                        <option value="em_andamento" {{ old('status', 'em_andamento') == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                                        <option value="enviado" {{ old('status') == 'enviado' ? 'selected' : '' }}>Enviado</option>
                                        <option value="aprovado" {{ old('status') == 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                                        <option value="rejeitado" {{ old('status') == 'rejeitado' ? 'selected' : '' }}>Rejeitado</option>
                                        <option value="cancelado" {{ old('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('status')
                                    <div class="flex items-center mt-2 text-sm text-red-600">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Seção do Aumento KM -->
                        <div id="aumento_km_section" class="bg-white shadow-lg rounded-2xl mt-8 border border-gray-100" style="display: none;">
                            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50 rounded-t-2xl">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-semibold text-gray-900">Dados do Aumento KM</h3>
                                        <p class="text-sm text-gray-600">Informações para cálculo do aumento de quilometragem</p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-8 py-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    <!-- KM por Dia -->
                                    <div>
                                        <label class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                            </svg>
                                            KM por Dia <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                                </svg>
                                            </div>
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0"
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 hover:border-gray-400 @error('km_dia') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-200 @enderror" 
                                                   id="km_dia" 
                                                   name="km_dia" 
                                                   value="{{ old('km_dia') }}"
                                                   placeholder="0,00">
                                        </div>
                                        @error('km_dia')
                                            <div class="flex items-center mt-2 text-sm text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Quantidade de Dias de Aumento -->
                                    <div>
                                        <label class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Quantidade de Dias de Aumento
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                                </svg>
                                            </div>
                                            <input type="number" 
                                                   min="1"
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 hover:border-gray-400" 
                                                   id="qtd_dias_aumento" 
                                                   name="qtd_dias_aumento" 
                                                   value="{{ old('qtd_dias_aumento') }}"
                                                   placeholder="1">
                                        </div>
                                        @error('qtd_dias_aumento')
                                            <div class="flex items-center mt-2 text-sm text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Combustível KM/Litro -->
                                    <div>
                                        <label class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                            </svg>
                                            Combustível KM/Litro <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                                </svg>
                                            </div>
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0"
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 hover:border-gray-400 @error('combustivel_km_litro') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-200 @enderror" 
                                                   id="combustivel_km_litro" 
                                                   name="combustivel_km_litro" 
                                                   value="{{ old('combustivel_km_litro') }}"
                                                   placeholder="0,00">
                                        </div>
                                        @error('combustivel_km_litro')
                                            <div class="flex items-center mt-2 text-sm text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Valor Combustível -->
                                    <div>
                                        <label class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                            Valor Combustível <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">R$</span>
                                            </div>
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0"
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 hover:border-gray-400 @error('valor_combustivel') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-200 @enderror" 
                                                   id="valor_combustivel" 
                                                   name="valor_combustivel" 
                                                   value="{{ old('valor_combustivel') }}"
                                                   placeholder="0,00">
                                        </div>
                                        @error('valor_combustivel')
                                            <div class="flex items-center mt-2 text-sm text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Hora Extra -->
                                    <div>
                                        <label class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Hora Extra
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">R$</span>
                                            </div>
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0"
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 hover:border-gray-400 @error('hora_extra') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-200 @enderror" 
                                                   id="hora_extra" 
                                                   name="hora_extra" 
                                                   value="{{ old('hora_extra') }}"
                                                   placeholder="0,00">
                                        </div>
                                        @error('hora_extra')
                                            <div class="flex items-center mt-2 text-sm text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Pedágio -->
                                    <div>
                                        <label class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            Pedágio
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">R$</span>
                                            </div>
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0"
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 hover:border-gray-400 @error('pedagio') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-200 @enderror" 
                                                   id="pedagio" 
                                                   name="pedagio" 
                                                   value="{{ old('pedagio') }}"
                                                   placeholder="0,00">
                                        </div>
                                        @error('pedagio')
                                            <div class="flex items-center mt-2 text-sm text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Percentual de Lucro -->
                                    <div>
                                        <label class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                            </svg>
                                            Percentual de Lucro (%)
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">%</span>
                                            </div>
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0" 
                                                   max="100"
                                                   class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 hover:border-gray-400 @error('lucro_percentual_aumento') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-200 @enderror" 
                                                   id="lucro_percentual_aumento" 
                                                   name="lucro_percentual_aumento" 
                                                   value="{{ old('lucro_percentual_aumento') }}"
                                                   placeholder="0,00">
                                        </div>
                                        @error('lucro_percentual_aumento')
                                            <div class="flex items-center mt-2 text-sm text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Grupo de Imposto -->
                                    <div>
                                        <label class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                            Grupo de Imposto
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <select class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 hover:border-gray-400 bg-white @error('grupo_imposto_id_aumento') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-200 @enderror" 
                                                    id="grupo_imposto_id_aumento" 
                                                    name="grupo_imposto_id_aumento">
                                                <option value="">Selecione um grupo de impostos...</option>
                                                @foreach($gruposImpostos as $grupo)
                                                    <option value="{{ $grupo->id }}" data-percentual="{{ $grupo->percentual_total }}" {{ old('grupo_imposto_id_aumento') == $grupo->id ? 'selected' : '' }}>
                                                        {{ $grupo->nome }} ({{ number_format($grupo->percentual_total, 2, ',', '.') }}%)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('grupo_imposto_id_aumento')
                                            <div class="flex items-center mt-2 text-sm text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Percentual de Impostos -->
                                    <div>
                                        <label class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                            Percentual de Impostos (%)
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">%</span>
                                            </div>
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0" 
                                                   max="100"
                                                   class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 hover:border-gray-400 @error('impostos_percentual_aumento') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-200 @enderror" 
                                                   id="impostos_percentual_aumento" 
                                                   name="impostos_percentual_aumento" 
                                                   value="{{ old('impostos_percentual_aumento') }}"
                                                   placeholder="0,00">
                                        </div>
                                        @error('impostos_percentual_aumento')
                                            <div class="flex items-center mt-2 text-sm text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Valor Total Calculado -->
                                    <div class="md:col-span-2 lg:col-span-3">
                                        <label class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                            Valor Total Calculado
                                        </label>
                                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-4 border border-green-200">
                                            <div class="text-2xl font-bold text-green-600" id="valor_total_aumento_km">R$ 0,00</div>
                                            <div class="text-sm text-gray-600 mt-1">Cálculo baseado nos valores informados</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Seção do Próprio Nova Rota -->
                        <div id="proprio_nova_rota_section" class="bg-white shadow-lg rounded-2xl mt-8 border border-gray-100" style="display: none;">
                            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-t-2xl">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-semibold text-gray-900">Dados da Nova Rota</h3>
                                        <p class="text-sm text-gray-600">Informações para cálculo da nova rota própria</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Mensagem de Em Desenvolvimento -->
                            <div class="px-8 py-4 bg-yellow-50 border-b border-yellow-200">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-yellow-800">
                                            <strong>Em desenvolvimento</strong> - Esta funcionalidade está sendo implementada e pode não estar totalmente funcional.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="px-8 py-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Nova Origem -->
                                    <div>
                                        <label class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                            <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Nova Origem
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                            </div>
                                            <input type="text" 
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 hover:border-gray-400" 
                                                   id="nova_origem" 
                                                   name="nova_origem" 
                                                   value="{{ old('nova_origem') }}"
                                                   placeholder="Digite a nova origem">
                                        </div>
                                        @error('nova_origem')
                                            <div class="flex items-center mt-2 text-sm text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Novo Destino -->
                                    <div>
                                        <label class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                            <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Novo Destino
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                            </div>
                                            <input type="text" 
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 hover:border-gray-400" 
                                                   id="novo_destino" 
                                                   name="novo_destino" 
                                                   value="{{ old('novo_destino') }}"
                                                   placeholder="Digite o novo destino">
                                        </div>
                                        @error('novo_destino')
                                            <div class="flex items-center mt-2 text-sm text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Distância (KM) -->
                                    <div>
                                        <label class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                            <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                            </svg>
                                            Distância (KM)
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                                </svg>
                                            </div>
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0"
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 hover:border-gray-400" 
                                                   id="distancia_km_proprio" 
                                                   name="distancia_km_proprio" 
                                                   value="{{ old('distancia_km_proprio') }}"
                                                   placeholder="0,00">
                                        </div>
                                        @error('distancia_km_proprio')
                                            <div class="flex items-center mt-2 text-sm text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Valor por KM -->
                                    <div>
                                        <label class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                            <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                            Valor por KM
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                </svg>
                                            </div>
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0"
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 hover:border-gray-400" 
                                                   id="valor_km_proprio" 
                                                   name="valor_km_proprio" 
                                                   value="{{ old('valor_km_proprio') }}"
                                                   placeholder="0,00">
                                        </div>
                                        @error('valor_km_proprio')
                                            <div class="flex items-center mt-2 text-sm text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Valor Total Calculado -->
                                    <div class="md:col-span-2">
                                        <label class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                            <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                            Valor Total Calculado
                                        </label>
                                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-4 border border-indigo-200">
                                            <div class="text-2xl font-bold text-indigo-600" id="valor_total_proprio_nova_rota">R$ 0,00</div>
                                            <div class="text-sm text-gray-600 mt-1">Distância × Valor/KM</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Seção do Prestador -->
                        <div id="prestador_section" class="bg-white shadow-lg rounded-2xl mt-8 border border-gray-100" style="display: none;">
                            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-2xl">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-xl font-bold text-gray-900">Dados do Prestador</h3>
                                        <p class="mt-1 text-sm text-gray-600">Informações específicas para orçamentos de prestador</p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-8 py-6 space-y-6">


                                <!-- Fornecedor Selecionado -->
                                <div id="fornecedor_selecionado" class="mb-6" style="display: none;">
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-green-800">
                                                    Fornecedor selecionado: <span id="nome_fornecedor_selecionado"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Busca de Fornecedor OMIE -->
                                <div class="mb-6">
                                    <label class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                        <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        Buscar Fornecedor <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    
                                    <!-- Grid com dois campos de busca -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <!-- Busca por Código -->
                                        <div class="relative">
                                            <label for="fornecedor_codigo_search" class="block text-xs font-medium text-gray-600 mb-1">Buscar por Código</label>
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none" style="top: 20px;">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                                </svg>
                                            </div>
                                            <input type="text" 
                                                   class="block w-full pl-9 pr-9 py-2.5 border-2 border-gray-200 rounded-lg shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200 text-sm bg-white hover:border-gray-300" 
                                                   id="fornecedor_codigo_search" 
                                                   placeholder="Ex: 123456" 
                                                   autocomplete="off">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none" style="top: 20px;">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        
                                        <!-- Busca por Nome -->
                                        <div class="relative">
                                            <label for="fornecedor_nome_search" class="block text-xs font-medium text-gray-600 mb-1">Buscar por Nome</label>
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none" style="top: 20px;">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                            <input type="text" 
                                                   class="block w-full pl-9 pr-9 py-2.5 border-2 border-gray-200 rounded-lg shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200 text-sm bg-white hover:border-gray-300" 
                                                   id="fornecedor_nome_search" 
                                                   placeholder="Ex: Empresa ABC Ltda" 
                                                   autocomplete="off">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none" style="top: 20px;">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Campo oculto para manter compatibilidade -->
                                    <input type="hidden" id="fornecedor_omie_search" autocomplete="off">
                                    
                                    <div class="relative">
                                        
                                        <!-- Fornecedor Dropdown Results -->
                                        <div id="fornecedor_dropdown" class="absolute z-50 w-full mt-2 bg-white border-2 border-gray-200 rounded-xl shadow-xl hidden max-h-60 overflow-y-auto">
                                            <!-- Loading State -->
                                            <div id="fornecedor_loading" class="px-6 py-4 text-center text-gray-500 hidden">
                                                <div class="inline-flex items-center">
                                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    <span class="text-sm font-medium">Buscando fornecedores...</span>
                                                </div>
                                            </div>
                                            
                                            <!-- Results List -->
                                            <div id="fornecedor_results" class="divide-y divide-gray-200">
                                                <!-- Results will be dynamically inserted here -->
                                            </div>
                                            
                                            <!-- No Results State -->
                                            <div id="fornecedor_no_results" class="px-6 py-4 text-center text-gray-500 hidden">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    <span class="text-sm font-medium">Nenhum fornecedor encontrado</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Campos ocultos para o fornecedor -->
                                <input type="hidden" id="fornecedor_omie_id" name="fornecedor_omie_id" value="{{ old('fornecedor_omie_id') }}">
                                <input type="hidden" id="fornecedor_nome" name="fornecedor_nome" value="{{ old('fornecedor_nome') }}">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                    <!-- Valor de Referência -->
                                    <div>
                                        <label for="valor_referencia" class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                            Valor de Referência (R$) <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                </svg>
                                            </div>
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0"
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400" 
                                                   id="valor_referencia" 
                                                   name="valor_referencia" 
                                                   value="{{ old('valor_referencia') }}"
                                                   placeholder="0,00">
                                        </div>
                                        @error('valor_referencia')
                                            <div class="flex items-center mt-2 text-sm text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <!-- Quantidade de Dias -->
                                    <div>
                                        <label for="qtd_dias" class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Quantidade de Dias <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                                </svg>
                                            </div>
                                            <input type="number" 
                                                   min="1"
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400" 
                                                   id="qtd_dias" 
                                                   name="qtd_dias" 
                                                   value="{{ old('qtd_dias') }}"
                                                   placeholder="1">
                                        </div>
                                        @error('qtd_dias')
                                            <div class="flex items-center mt-2 text-sm text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Percentual de Lucro -->
                                    <div>
                                        <label for="percentual_lucro" class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                            </svg>
                                            Percentual de Lucro (%) <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0"
                                                   max="100"
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400" 
                                                   id="percentual_lucro"
                                           name="percentual_lucro"
                                           value="{{ old('percentual_lucro') }}"
                                                   placeholder="0,00">
                                        </div>
                                        @error('percentual_lucro')
                                            <div class="flex items-center mt-2 text-sm text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Seletor de Grupo de Impostos -->
                                    <div>
                                        <label for="grupo_imposto_id" class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                            <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-4H3m16 8H1m18 4H7"></path>
                                            </svg>
                                            Grupo de Impostos
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-4H3m16 8H1m18 4H7"></path>
                                                </svg>
                                            </div>
                                            <select class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400 bg-white" 
                                                    id="grupo_imposto_id" 
                                                    name="grupo_imposto_id">
                                                <option value="">Selecione um grupo de impostos...</option>
                                                @foreach($gruposImpostos as $grupo)
                                                    <option value="{{ $grupo->id }}" data-percentual="{{ $grupo->percentual_total }}" {{ old('grupo_imposto_id') == $grupo->id ? 'selected' : '' }}>
                                                        {{ $grupo->nome }} ({{ number_format($grupo->percentual_total, 2, ',', '.') }}%)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('grupo_imposto_id')
                                            <div class="flex items-center mt-2 text-sm text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Percentual de Impostos -->
                                    <div>
                                        <label for="percentual_impostos" class="flex items-center text-sm font-semibold text-gray-800 mb-3">
                                            <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                            Percentual de Impostos (%) <span class="text-red-500 ml-1">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0"
                                                   max="100"
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400" 
                                                   id="percentual_impostos"
                                           name="percentual_impostos"
                                           value="{{ old('percentual_impostos') }}"
                                                   placeholder="0,00">
                                        </div>
                                        @error('percentual_impostos')
                                            <div class="flex items-center mt-2 text-sm text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Resumo dos Cálculos -->
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                                    <div class="flex items-center mb-4">
                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        <h4 class="text-lg font-semibold text-gray-900">Resumo dos Cálculos</h4>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-100">
                                            <div class="flex items-center mb-2">
                                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2"></path>
                                                </svg>
                                                <span class="text-sm text-gray-600 font-medium">Custo Fornecedor</span>
                                            </div>
                                            <div class="text-xl font-bold text-gray-900" id="custo_fornecedor_display">R$ 0,00</div>
                                        </div>
                                        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-100">
                                            <div class="flex items-center mb-2">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                                </svg>
                                                <span class="text-sm text-gray-600 font-medium">Valor Lucro</span>
                                            </div>
                                            <div class="text-xl font-bold text-green-600" id="valor_lucro_display">R$ 0,00</div>
                                        </div>
                                        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-100">
                                            <div class="flex items-center mb-2">
                                                <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-sm text-gray-600 font-medium">Valor Impostos</span>
                                            </div>
                                            <div class="text-xl font-bold text-red-600" id="valor_impostos_display">R$ 0,00</div>
                                        </div>
                                        <div class="bg-white rounded-lg p-4 shadow-sm border border-indigo-200 ring-2 ring-indigo-100">
                                            <div class="flex items-center mb-2">
                                                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                </svg>
                                                <span class="text-sm text-gray-600 font-medium">Valor Total</span>
                                            </div>
                                            <div class="text-2xl font-bold text-indigo-600" id="valor_total_display">R$ 0,00</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
                            <!-- Observações -->
                            <div class="md:col-span-2 lg:col-span-3">
                                <label for="observacoes" class="flex items-center text-sm font-medium text-gray-700 mb-3">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Observações
                                </label>
                                <div class="relative">
                                    <textarea class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 resize-none text-base @error('observacoes') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500 @enderror" 
                                              id="observacoes" 
                                              name="observacoes" 
                                              rows="4" 
                                              placeholder="Digite observações adicionais sobre o orçamento...">{{ old('observacoes') }}</textarea>
                                    <div class="absolute left-3 top-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('observacoes')
                                    <div class="mt-2 flex items-center text-sm text-red-600">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Campos hidden para valores calculados -->
                        <input type="hidden" id="valor_lucro_hidden" name="valor_lucro" value="0">
                        <input type="hidden" id="valor_impostos_hidden" name="valor_impostos" value="0">
                        
                        <!-- Campos hidden para valores calculados do Aumento KM -->
                        <input type="hidden" id="valor_lucro_aumento_hidden" name="valor_lucro_aumento" value="0">
                        <input type="hidden" id="valor_impostos_aumento_hidden" name="valor_impostos_aumento" value="0">
                    </div>

                    <div class="px-6 py-6 bg-gradient-to-r from-gray-50 to-gray-100 border-t border-gray-200 rounded-b-lg">
                        <div class="flex justify-end space-x-4">
                            <button type="button" 
                                    class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200" 
                                    onclick="window.history.back()">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Cancelar
                            </button>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform hover:scale-105 transition-all duration-200">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                Salvar Orçamento
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@push('scripts')
<script>
// Funções globais para busca de clientes
let timeoutId;
let selectedIndex = -1;
let currentResults = [];

// Constantes globais para elementos DOM
const clienteSelecionado = document.getElementById('cliente_selecionado');
const clienteOmieId = document.getElementById('cliente_omie_id');
const clienteNome = document.getElementById('cliente_nome');
const nomeClienteSelecionado = document.getElementById('nome_cliente_selecionado');
const clienteDropdown = document.getElementById('client_dropdown');
const clienteLoading = document.getElementById('client_loading');
const clienteResults = document.getElementById('client_results');
const clienteNoResults = document.getElementById('client_no_results');

function showDropdown() {
    if (clienteDropdown) {
        clienteDropdown.classList.remove('hidden');
    }
}

function hideDropdown() {
    if (clienteDropdown) clienteDropdown.classList.add('hidden');
    selectedIndex = -1;
    currentResults = [];
}

function showLoading() {
    if (clienteLoading) clienteLoading.classList.remove('hidden');
    if (clienteResults) clienteResults.innerHTML = '';
    if (clienteNoResults) clienteNoResults.classList.add('hidden');
    showDropdown();
}

function hideLoading() {
    if (clienteLoading) clienteLoading.classList.add('hidden');
}

function buscarClientesOmie(termo) {
    showLoading();
    
    // Criar AbortController para timeout
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 35000); // 35 segundos
    
    fetch(`/api/omie/clientes/search?search=${encodeURIComponent(termo)}`, {
        signal: controller.signal,
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            clearTimeout(timeoutId);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            hideLoading();
            
            if (data.success && data.data && data.data.length > 0) {
                displayResults(data.data);
            } else {
                if (clienteNoResults) clienteNoResults.classList.remove('hidden');
                if (clienteResults) clienteResults.innerHTML = '';
                showDropdown();
            }
        })
        .catch(error => {
            clearTimeout(timeoutId);
            console.error('Erro ao buscar clientes OMIE:', error);
            hideLoading();
            
            let errorMessage = 'Erro ao buscar clientes. Tente novamente.';
            
            if (error.name === 'AbortError') {
                errorMessage = 'Busca cancelada por timeout. Tente novamente.';
            } else if (error.message.includes('HTTP')) {
                errorMessage = `Erro no servidor: ${error.message}`;
            } else if (error.message.includes('Failed to fetch')) {
                errorMessage = 'Erro de conexão. Verifique sua internet.';
            }
            
            if (clienteResults) {
                clienteResults.innerHTML = `
                    <div class="px-4 py-3 text-red-600 text-sm">
                        ${errorMessage}
                    </div>
                `;
            }
            showDropdown();
        });
}

function displayResults(clientes) {
    hideLoading();
    currentResults = clientes;
    selectedIndex = -1;
    
    const clienteResults = document.getElementById('client_results');
    const clienteNoResults = document.getElementById('client_no_results');
    
    if (clientes.length === 0) {
        if (clienteResults) clienteResults.innerHTML = '';
        if (clienteNoResults) clienteNoResults.classList.remove('hidden');
        showDropdown();
        return;
    }
    
    if (clienteNoResults) clienteNoResults.classList.add('hidden');
    
    const html = clientes.map((cliente, index) => `
        <div class="cliente-item px-4 py-3 cursor-pointer hover:bg-gray-50 border-b border-gray-100 last:border-b-0" 
             data-index="${index}">
            <div class="font-medium text-gray-900">${cliente.razao_social || cliente.nome_fantasia || cliente.nome}</div>
            <div class="text-sm text-gray-500">
                ${cliente.documento || cliente.cnpj_cpf ? 'CNPJ/CPF: ' + (cliente.documento || cliente.cnpj_cpf) : ''}
                ${cliente.omie_id || cliente.id || cliente.codigo_cliente_omie ? ' • Código: ' + (cliente.omie_id || cliente.id || cliente.codigo_cliente_omie) : ''}
            </div>
        </div>
    `).join('');
    
    if (clienteResults) {
        clienteResults.innerHTML = html;
        
        // Adicionar event listeners para clique
        clienteResults.querySelectorAll('.cliente-item').forEach((item, index) => {
            item.addEventListener('click', () => {
                selecionarCliente(clientes[index]);
            });
            
            item.addEventListener('mouseenter', () => {
                selectedIndex = index;
                updateSelection();
            });
        });
    }
    
    showDropdown();
}

function selecionarCliente(cliente) {
    const clienteSearch = document.getElementById('cliente_omie_search');
    
    if (clienteOmieId) clienteOmieId.value = cliente.omie_id || cliente.id || cliente.codigo_cliente_omie;
    if (clienteNome) clienteNome.value = cliente.razao_social || cliente.nome_fantasia || cliente.nome;
    if (clienteSearch) clienteSearch.value = cliente.razao_social || cliente.nome_fantasia || cliente.nome;
    if (nomeClienteSelecionado) nomeClienteSelecionado.textContent = cliente.razao_social || cliente.nome_fantasia || cliente.nome;
    if (clienteSelecionado) clienteSelecionado.style.display = 'block';
    hideDropdown();
    
    console.log('Cliente selecionado:', {
        id: cliente.omie_id || cliente.id || cliente.codigo_cliente_omie,
        nome: cliente.razao_social || cliente.nome_fantasia || cliente.nome
    });
}

function updateSelection() {
    if (!clienteResults) return;
    const items = clienteResults.querySelectorAll('.cliente-item');
    items.forEach((item, index) => {
        if (index === selectedIndex) {
            item.classList.add('bg-indigo-50', 'text-indigo-900');
        } else {
            item.classList.remove('bg-indigo-50', 'text-indigo-900');
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Controle de exibição das seções por tipo de orçamento
    const tipoOrcamentoSelect = document.getElementById('tipo_orcamento');
    const prestadorSection = document.getElementById('prestador_section');
    const aumentoKmSection = document.getElementById('aumento_km_section');
    const proprioNovaRotaSection = document.getElementById('proprio_nova_rota_section');
    
    // Função para mostrar/ocultar seções baseado no tipo
    function toggleSections() {
        if (tipoOrcamentoSelect) {
            // Ocultar todas as seções
            if (prestadorSection) prestadorSection.style.display = 'none';
            if (aumentoKmSection) aumentoKmSection.style.display = 'none';
            if (proprioNovaRotaSection) proprioNovaRotaSection.style.display = 'none';
            
            // Desabilitar campos de todas as seções
            const prestadorInputs = prestadorSection ? prestadorSection.querySelectorAll('input, select, textarea') : [];
            const aumentoKmInputs = aumentoKmSection ? aumentoKmSection.querySelectorAll('input, select, textarea') : [];
            const proprioNovaRotaInputs = proprioNovaRotaSection ? proprioNovaRotaSection.querySelectorAll('input, select, textarea') : [];
            
            [...prestadorInputs, ...aumentoKmInputs, ...proprioNovaRotaInputs].forEach(input => {
                input.disabled = true;
                // Limpar campos de percentual quando não são da seção ativa
                if (input.name === 'percentual_lucro' || input.name === 'percentual_impostos') {
                    input.value = '';
                }
            });
            
            // Exibir seção correspondente ao tipo selecionado e habilitar seus campos
            const tipoSelecionado = tipoOrcamentoSelect.value;
            if (tipoSelecionado === 'prestador' && prestadorSection) {
                prestadorSection.style.display = 'block';
                prestadorInputs.forEach(input => input.disabled = false);
            } else if (tipoSelecionado === 'aumento_km' && aumentoKmSection) {
                aumentoKmSection.style.display = 'block';
                aumentoKmInputs.forEach(input => input.disabled = false);
            } else if (tipoSelecionado === 'proprio_nova_rota' && proprioNovaRotaSection) {
                proprioNovaRotaSection.style.display = 'block';
                proprioNovaRotaInputs.forEach(input => input.disabled = false);
            }
        }
    }
    
    // Event listener para mudança de tipo de orçamento
    if (tipoOrcamentoSelect) {
        tipoOrcamentoSelect.addEventListener('change', toggleSections);
    }
    
    // Verificar estado inicial
    toggleSections();
    
    // Cálculos automáticos do prestador
    const valorReferenciaInput = document.getElementById('valor_referencia');
    const qtdDiasInput = document.getElementById('qtd_dias');
    const percentualLucroInput = document.getElementById('percentual_lucro');
                const percentualImpostosInput = document.getElementById('percentual_impostos');
    
    const custoFornecedorDisplay = document.getElementById('custo_fornecedor_display');
    const valorLucroDisplay = document.getElementById('valor_lucro_display');
    const valorImpostosDisplay = document.getElementById('valor_impostos_display');
    const valorTotalDisplay = document.getElementById('valor_total_display');
    
    function formatarMoeda(valor) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(valor || 0);
    }
    
    function calcularValoresPrestador() {
        const valorReferencia = parseFloat(valorReferenciaInput.value) || 0;
        const qtdDias = parseInt(qtdDiasInput.value) || 0;
        const percentualLucro = parseFloat(percentualLucroInput.value) || 0;
        const percentualImpostos = parseFloat(percentualImpostosInput.value) || 0;
        
        // Custo Fornecedor = Valor Referência × Quantidade de Dias
        const custoFornecedor = valorReferencia * qtdDias;
        
        // Valor Lucro = Custo Fornecedor × (Percentual Lucro / 100)
        const valorLucro = custoFornecedor * (percentualLucro / 100);
        
        // Subtotal = Custo Fornecedor + Valor Lucro
        const subtotal = custoFornecedor + valorLucro;
        
        // Valor Impostos = Subtotal × (Percentual Impostos / 100)
        const valorImpostos = subtotal * (percentualImpostos / 100);
        
        // Valor Total = Subtotal + Valor Impostos
        const valorTotal = subtotal + valorImpostos;
        
        // Atualizar displays
        custoFornecedorDisplay.textContent = formatarMoeda(custoFornecedor);
        valorLucroDisplay.textContent = formatarMoeda(valorLucro);
        valorImpostosDisplay.textContent = formatarMoeda(valorImpostos);
        valorTotalDisplay.textContent = formatarMoeda(valorTotal);
        
        // Atualizar campos hidden para envio ao backend
        const valorLucroHidden = document.getElementById('valor_lucro_hidden');
        const valorImpostosHidden = document.getElementById('valor_impostos_hidden');
        if (valorLucroHidden) valorLucroHidden.value = valorLucro.toFixed(2);
        if (valorImpostosHidden) valorImpostosHidden.value = valorImpostos.toFixed(2);
        
        // Atualizar campo valor_total do orçamento principal se for prestador
        const valorTotalPrincipal = document.getElementById('valor_total');
        if (tipoOrcamentoSelect && tipoOrcamentoSelect.value === 'prestador' && valorTotalPrincipal) {
            valorTotalPrincipal.value = valorTotal.toFixed(2);
        }
    }
    
    // Função para formatar percentual com 2 casas decimais
    function formatarPercentual(input) {
        let valor = input.value;
        
        // Remove caracteres não numéricos exceto ponto e vírgula
        valor = valor.replace(/[^0-9.,]/g, '');
        
        // Substitui vírgula por ponto
        valor = valor.replace(',', '.');
        
        // Verifica se há mais de um ponto
        const pontos = valor.split('.');
        if (pontos.length > 2) {
            valor = pontos[0] + '.' + pontos.slice(1).join('');
        }
        
        // Limita a 2 casas decimais
        if (pontos.length === 2 && pontos[1].length > 2) {
            valor = pontos[0] + '.' + pontos[1].substring(0, 2);
        }
        
        // Limita o valor máximo a 100
        const numeroValor = parseFloat(valor);
        if (numeroValor > 100) {
            valor = '100.00';
        }
        
        input.value = valor;
    }
    
    // Event listeners para cálculos automáticos
    if (valorReferenciaInput) valorReferenciaInput.addEventListener('input', calcularValoresPrestador);
    if (qtdDiasInput) qtdDiasInput.addEventListener('input', calcularValoresPrestador);
    if (percentualLucroInput) {
        percentualLucroInput.addEventListener('input', function() {
            formatarPercentual(this);
            calcularValoresPrestador();
        });
    }
    if (percentualImpostosInput) {
        percentualImpostosInput.addEventListener('input', function() {
            // Permitir edição livre do valor sem formatação/arredondamento
            calcularValoresPrestador();
        });
    }
    
    // Calcular valores iniciais se houver dados
    calcularValoresPrestador();
    
    // Integração com Grupos de Impostos
    const grupoImpostoSelect = document.getElementById('grupo_imposto_id');
    
    if (grupoImpostoSelect) {
        grupoImpostoSelect.addEventListener('change', function() {
            const grupoId = this.value;
            
            if (grupoId) {
                // Fazer requisição AJAX para buscar o percentual do grupo
                fetch('/admin/orcamentos/buscar-percentual-grupo-imposto', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        grupo_imposto_id: grupoId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.percentual !== undefined && percentualImpostosInput) {
                        percentualImpostosInput.value = parseFloat(data.percentual).toFixed(2);
                        // Não formatar para manter precisão original
                        calcularValoresPrestador();
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar percentual do grupo de impostos:', error);
                    // Fallback: usar o atributo data-percentual se disponível
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption && selectedOption.dataset.percentual && percentualImpostosInput) {
                        percentualImpostosInput.value = parseFloat(selectedOption.dataset.percentual).toFixed(2);
                        // Não formatar para manter precisão original
                        calcularValoresPrestador();
                    }
                });
            } else {
                // Limpar o campo de percentual se nenhum grupo for selecionado
                if (percentualImpostosInput) {
                    percentualImpostosInput.value = '';
                    calcularValoresPrestador();
                }
            }
        });
    }
    
    // Cálculos automáticos do Aumento KM
    const kmDiaInput = document.getElementById('km_dia');
    const qtdDiasAumentoInput = document.getElementById('qtd_dias_aumento');
    const combustivelKmLitroInput = document.getElementById('combustivel_km_litro');
    const valorCombustivelInput = document.getElementById('valor_combustivel');
    const horaExtraInput = document.getElementById('hora_extra');
    const pedagioInput = document.getElementById('pedagio');
    const lucroPercentualAumentoInput = document.getElementById('lucro_percentual_aumento');
    const grupoImpostoIdAumentoSelect = document.getElementById('grupo_imposto_id_aumento');
    const impostosPercentualAumentoInput = document.getElementById('impostos_percentual_aumento');
    const valorTotalAumentoKmDisplay = document.getElementById('valor_total_aumento_km');
    
    // Função para atualizar percentual de impostos baseado no grupo selecionado (Aumento KM)
    if (grupoImpostoIdAumentoSelect) {
        grupoImpostoIdAumentoSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption && selectedOption.dataset.percentual && impostosPercentualAumentoInput) {
                const percentual = parseFloat(selectedOption.dataset.percentual) || 0;
                impostosPercentualAumentoInput.value = percentual.toFixed(2);
                formatarPercentual(impostosPercentualAumentoInput);
                calcularValoresAumentoKm();
            } else if (impostosPercentualAumentoInput) {
                impostosPercentualAumentoInput.value = '';
                calcularValoresAumentoKm();
            }
        });
    }
    
    function calcularValoresAumentoKm() {
        const kmDia = parseFloat(kmDiaInput?.value) || 0;
        const qtdDiasAumento = parseInt(qtdDiasAumentoInput?.value) || 0;
        const combustivelKmLitro = parseFloat(combustivelKmLitroInput?.value) || 0;
        const valorCombustivel = parseFloat(valorCombustivelInput?.value) || 0;
        const horaExtra = parseFloat(horaExtraInput?.value) || 0;
        const pedagio = parseFloat(pedagioInput?.value) || 0;
        const lucroPercentual = parseFloat(lucroPercentualAumentoInput?.value) || 0;
        const impostosPercentual = parseFloat(impostosPercentualAumentoInput?.value) || 0;
        
        // Cálculo básico: KM total do mês
        const kmTotalMes = kmDia * qtdDiasAumento;
        
        // Cálculo do combustível total
        const totalCombustivel = combustivelKmLitro > 0 ? kmTotalMes / combustivelKmLitro : 0;
        
        // Custo total combustível + hora extra + pedágio
        const custoTotalCombustivelHe = (totalCombustivel * valorCombustivel) + horaExtra + pedagio;
        
        // Calcular lucro
        const valorLucro = custoTotalCombustivelHe * (lucroPercentual / 100);
        
        // Subtotal com lucro
        const subtotalComLucro = custoTotalCombustivelHe + valorLucro;
        
        // Calcular impostos
        const valorImpostos = subtotalComLucro * (impostosPercentual / 100);
        
        // Valor total final
        const valorTotal = subtotalComLucro + valorImpostos;
        
        // Atualizar display
        if (valorTotalAumentoKmDisplay) {
            valorTotalAumentoKmDisplay.textContent = formatarMoeda(valorTotal);
        }
        
        // Atualizar campos hidden com valores calculados
        const valorLucroAumentoHidden = document.getElementById('valor_lucro_aumento_hidden');
        const valorImpostosAumentoHidden = document.getElementById('valor_impostos_aumento_hidden');
        if (valorLucroAumentoHidden) {
            valorLucroAumentoHidden.value = valorLucro.toFixed(2);
        }
        if (valorImpostosAumentoHidden) {
            valorImpostosAumentoHidden.value = valorImpostos.toFixed(2);
        }
        
        // Atualizar campo valor_total do orçamento principal se for aumento_km
        const valorTotalPrincipal = document.getElementById('valor_total');
        if (tipoOrcamentoSelect && tipoOrcamentoSelect.value === 'aumento_km' && valorTotalPrincipal) {
            valorTotalPrincipal.value = valorTotal.toFixed(2);
        }
    }
    
    // Event listeners para cálculos automáticos do Aumento KM
    if (kmDiaInput) kmDiaInput.addEventListener('input', calcularValoresAumentoKm);
    if (qtdDiasAumentoInput) qtdDiasAumentoInput.addEventListener('input', calcularValoresAumentoKm);
    if (combustivelKmLitroInput) combustivelKmLitroInput.addEventListener('input', calcularValoresAumentoKm);
    if (valorCombustivelInput) valorCombustivelInput.addEventListener('input', calcularValoresAumentoKm);
    if (horaExtraInput) horaExtraInput.addEventListener('input', calcularValoresAumentoKm);
    if (pedagioInput) pedagioInput.addEventListener('input', calcularValoresAumentoKm);
    if (lucroPercentualAumentoInput) {
        lucroPercentualAumentoInput.addEventListener('input', function() {
            formatarPercentual(this);
            calcularValoresAumentoKm();
        });
    }
    if (impostosPercentualAumentoInput) {
        impostosPercentualAumentoInput.addEventListener('input', function() {
            formatarPercentual(this);
            calcularValoresAumentoKm();
        });
    }
    
    // Calcular valores iniciais do Aumento KM
    calcularValoresAumentoKm();
    
    // Cálculos automáticos do Próprio Nova Rota
    const distanciaKmInput = document.getElementById('distancia_km_proprio');
    const valorKmProprioInput = document.getElementById('valor_km_proprio');
    const valorTotalProprioNovaRotaDisplay = document.getElementById('valor_total_proprio_nova_rota');
    
    function calcularValoresProprioNovaRota() {
        const distanciaKm = parseFloat(distanciaKmInput.value) || 0;
        const valorKmProprio = parseFloat(valorKmProprioInput.value) || 0;
        
        // Valor Total = Distância KM × Valor por KM
        const valorTotal = distanciaKm * valorKmProprio;
        
        // Atualizar display
        if (valorTotalProprioNovaRotaDisplay) {
            valorTotalProprioNovaRotaDisplay.textContent = formatarMoeda(valorTotal);
        }
        
        // Atualizar campo valor_total do orçamento principal se for proprio_nova_rota
        const valorTotalPrincipal = document.getElementById('valor_total');
        if (tipoOrcamentoSelect && tipoOrcamentoSelect.value === 'proprio_nova_rota' && valorTotalPrincipal) {
            valorTotalPrincipal.value = valorTotal.toFixed(2);
        }
    }
    
    // Event listeners para cálculos automáticos do Próprio Nova Rota
    if (distanciaKmInput) distanciaKmInput.addEventListener('input', calcularValoresProprioNovaRota);
    if (valorKmProprioInput) valorKmProprioInput.addEventListener('input', calcularValoresProprioNovaRota);
    
    // Calcular valores iniciais do Próprio Nova Rota
    calcularValoresProprioNovaRota();
    
    // Busca de clientes OMIE
    const clienteSearch = document.getElementById('cliente_omie_search');
    const orcamentoForm = document.getElementById('orcamentoForm');
    
    if (clienteSearch) {
        clienteSearch.addEventListener('input', function() {
            clearTimeout(timeoutId);
            const termo = this.value.trim();
            
            // Se o campo estiver vazio, limpar seleção
            if (termo === '') {
                if (clienteSelecionado) clienteSelecionado.style.display = 'none';
                if (clienteOmieId) clienteOmieId.value = '';
                if (clienteNome) clienteNome.value = '';
                hideDropdown();
                return;
            }
            
            // Buscar após 500ms de inatividade (permite busca por ID ou nome)
            if (termo.length >= 2) {
                timeoutId = setTimeout(function() {
                    buscarClientesOmie(termo);
                }, 500);
            } else {
                hideDropdown();
            }
        });
        
        // Navegação por teclado
        clienteSearch.addEventListener('keydown', function(e) {
            if (clienteDropdown && !clienteDropdown.classList.contains('hidden')) {
                switch(e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        selectedIndex = Math.min(selectedIndex + 1, currentResults.length - 1);
                        updateSelection();
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        selectedIndex = Math.max(selectedIndex - 1, -1);
                        updateSelection();
                        break;
                    case 'Enter':
                        e.preventDefault();
                        if (selectedIndex >= 0 && currentResults[selectedIndex]) {
                            selecionarCliente(currentResults[selectedIndex]);
                        }
                        break;
                    case 'Escape':
                        hideDropdown();
                        break;
                }
            }
        });
        
        // Foco no campo de busca quando a página carregar
        clienteSearch.focus();
    }
    
    // Fechar dropdown ao clicar fora
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#cliente_omie_search') && !e.target.closest('#client_dropdown')) {
            hideDropdown();
        }
    });
    
    // Funções removidas - agora estão no escopo global
    
    // Validação do formulário
    if (orcamentoForm) {
        orcamentoForm.addEventListener('submit', function(e) {
            const clienteId = clienteOmieId ? clienteOmieId.value : '';
            if (!clienteId) {
                e.preventDefault();
                alert('Por favor, selecione um cliente válido.');
                if (clienteSearch) clienteSearch.focus();
                return false;
            }
        });
    }
    
    // Função para calcular automaticamente a quantidade de dias baseado na frequência de atendimento
    function calcularQuantidadeDias() {
        const checkboxes = document.querySelectorAll('input[name="frequencia_atendimento[]"]:checked');
        const qtdDiasInput = document.getElementById('qtd_dias');
        const qtdDiasAumentoInput = document.getElementById('qtd_dias_aumento');
        const tipoOrcamento = document.getElementById('tipo_orcamento');
        
        // Valor calculado
        const diasSelecionados = checkboxes.length;
        
        // Atualiza campo do Prestador
        if (qtdDiasInput) {
            qtdDiasInput.value = diasSelecionados;
            if (tipoOrcamento && tipoOrcamento.value === 'prestador') {
                calcularValoresPrestador();
            }
        }
        // Atualiza campo do Aumento KM
        if (qtdDiasAumentoInput) {
            qtdDiasAumentoInput.value = diasSelecionados;
            if (tipoOrcamento && tipoOrcamento.value === 'aumento_km') {
                calcularValoresAumentoKm();
            }
        }
    }
    
    // Adicionar event listeners aos checkboxes de frequência de atendimento
    document.querySelectorAll('input[name="frequencia_atendimento[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', calcularQuantidadeDias);
    });
    
    // Calcular quantidade de dias inicial se houver checkboxes marcados
    calcularQuantidadeDias();
    
    // Busca de fornecedores OMIE - Campos independentes para código e nome
    let fornecedorCodigoTimeoutId;
    let fornecedorNomeTimeoutId;
    
    // Elementos dos campos de busca
    const fornecedorCodigoSearch = document.getElementById('fornecedor_codigo_search');
    const fornecedorNomeSearch = document.getElementById('fornecedor_nome_search');
    const fornecedorSearch = document.getElementById('fornecedor_omie_search'); // Campo oculto para compatibilidade
    
    // Elementos de resultado e seleção
    const fornecedorSelecionado = document.getElementById('fornecedor_selecionado');
    const fornecedorOmieId = document.getElementById('fornecedor_omie_id');
    const fornecedorNome = document.getElementById('fornecedor_nome');
    const nomeFornecedorSelecionado = document.getElementById('nome_fornecedor_selecionado');
    const fornecedorDropdown = document.getElementById('fornecedor_dropdown');
    const fornecedorLoading = document.getElementById('fornecedor_loading');
    const fornecedorResults = document.getElementById('fornecedor_results');
    const fornecedorNoResults = document.getElementById('fornecedor_no_results');
    
    let fornecedorSelectedIndex = -1;
    let fornecedorCurrentResults = [];
    let activeSearchField = null; // Controla qual campo está ativo
    
    function showFornecedorLoading() {
        if (fornecedorLoading) fornecedorLoading.classList.remove('hidden');
        if (fornecedorResults) fornecedorResults.innerHTML = '';
        if (fornecedorNoResults) fornecedorNoResults.classList.add('hidden');
    }
    
    function hideFornecedorLoading() {
        if (fornecedorLoading) fornecedorLoading.classList.add('hidden');
    }
    
    function updateFornecedorSelection() {
        if (!fornecedorResults) return;
        
        const items = fornecedorResults.querySelectorAll('.fornecedor-item');
        items.forEach((item, index) => {
            if (index === fornecedorSelectedIndex) {
                item.classList.add('bg-indigo-50');
            } else {
                item.classList.remove('bg-indigo-50');
            }
        });
    }
    
    function displayFornecedorResults(fornecedores) {
        fornecedorCurrentResults = fornecedores;
        fornecedorSelectedIndex = -1;
        
        if (fornecedores.length === 0) {
            if (fornecedorResults) fornecedorResults.innerHTML = '';
            if (fornecedorNoResults) fornecedorNoResults.classList.remove('hidden');
            showFornecedorDropdown();
            return;
        }
        
        if (fornecedorNoResults) fornecedorNoResults.classList.add('hidden');
        
        const html = fornecedores.map((fornecedor, index) => `
            <div class="fornecedor-item px-4 py-3 cursor-pointer hover:bg-gray-50 border-b border-gray-100 last:border-b-0" 
                 data-index="${index}">
                <div class="font-medium text-gray-900">${fornecedor.razao_social || fornecedor.nome_fantasia || fornecedor.nome}</div>
                <div class="text-sm text-gray-500">
                    ${fornecedor.documento || fornecedor.cnpj_cpf ? 'CNPJ/CPF: ' + (fornecedor.documento || fornecedor.cnpj_cpf) : ''}
                    ${fornecedor.omie_id || fornecedor.id || fornecedor.codigo_fornecedor_omie ? ' • Código: ' + (fornecedor.omie_id || fornecedor.id || fornecedor.codigo_fornecedor_omie) : ''}
                </div>
            </div>
        `).join('');
        
        if (fornecedorResults) {
            fornecedorResults.innerHTML = html;
            
            // Adicionar event listeners para clique
            fornecedorResults.querySelectorAll('.fornecedor-item').forEach((item, index) => {
                item.addEventListener('click', () => {
                    selecionarFornecedor(fornecedores[index]);
                });
                
                item.addEventListener('mouseenter', () => {
                    fornecedorSelectedIndex = index;
                    updateFornecedorSelection();
                });
            });
        }
        
        showFornecedorDropdown();
    }
    
    // Event listeners para busca por código
    if (fornecedorCodigoSearch) {
        fornecedorCodigoSearch.addEventListener('input', function() {
            clearTimeout(fornecedorCodigoTimeoutId);
            const codigo = this.value.trim();
            activeSearchField = 'codigo';
            
            // Limpar o outro campo quando este for usado
            if (fornecedorNomeSearch && codigo.length > 0) {
                fornecedorNomeSearch.value = '';
            }
            
            if (codigo.length >= 1) {
                fornecedorCodigoTimeoutId = setTimeout(() => {
                    buscarFornecedoresPorCodigo(codigo);
                }, 300);
            } else {
                hideFornecedorDropdown();
            }
        });
        
        // Navegação por teclado para campo código
        fornecedorCodigoSearch.addEventListener('keydown', handleFornecedorKeydown);
    }
    
    // Event listeners para busca por nome
    if (fornecedorNomeSearch) {
        fornecedorNomeSearch.addEventListener('input', function() {
            clearTimeout(fornecedorNomeTimeoutId);
            const nome = this.value.trim();
            activeSearchField = 'nome';
            
            // Limpar o outro campo quando este for usado
            if (fornecedorCodigoSearch && nome.length > 0) {
                fornecedorCodigoSearch.value = '';
            }
            
            if (nome.length >= 2) {
                fornecedorNomeTimeoutId = setTimeout(() => {
                    buscarFornecedoresPorNome(nome);
                }, 500);
            } else {
                hideFornecedorDropdown();
            }
        });
        
        // Navegação por teclado para campo nome
        fornecedorNomeSearch.addEventListener('keydown', handleFornecedorKeydown);
    }
    
    // Função unificada para navegação por teclado
    function handleFornecedorKeydown(e) {
        if (fornecedorDropdown && !fornecedorDropdown.classList.contains('hidden')) {
            switch(e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    fornecedorSelectedIndex = Math.min(fornecedorSelectedIndex + 1, fornecedorCurrentResults.length - 1);
                    updateFornecedorSelection();
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    fornecedorSelectedIndex = Math.max(fornecedorSelectedIndex - 1, -1);
                    updateFornecedorSelection();
                    break;
                case 'Enter':
                    e.preventDefault();
                    if (fornecedorSelectedIndex >= 0 && fornecedorCurrentResults[fornecedorSelectedIndex]) {
                        selecionarFornecedor(fornecedorCurrentResults[fornecedorSelectedIndex]);
                    }
                    break;
                case 'Escape':
                    hideFornecedorDropdown();
                    break;
            }
        }
    }
    
    // Fechar dropdown ao clicar fora
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#fornecedor_codigo_search') && 
            !e.target.closest('#fornecedor_nome_search') && 
            !e.target.closest('#fornecedor_dropdown')) {
            hideFornecedorDropdown();
        }
    });
    
    function showFornecedorDropdown() {
        if (fornecedorDropdown) fornecedorDropdown.classList.remove('hidden');
    }
    
    function hideFornecedorDropdown() {
        if (fornecedorDropdown) fornecedorDropdown.classList.add('hidden');
        fornecedorSelectedIndex = -1;
    }
    
    // Função para buscar fornecedores por código
    function buscarFornecedoresPorCodigo(codigo) {
        showFornecedorLoading();
        
        // Criar AbortController para timeout
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 35000); // 35 segundos
        
        // Usar a API específica de fornecedores com parâmetro de código
        fetch(`/api/omie/fornecedores/search?search=${encodeURIComponent(codigo)}&type=codigo`, {
            signal: controller.signal,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
            .then(response => {
                clearTimeout(timeoutId);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                hideFornecedorLoading();
                
                if (data.success && data.data && data.data.length > 0) {
                    displayFornecedorResults(data.data);
                } else {
                    // Nenhum resultado encontrado
                    if (fornecedorNoResults) fornecedorNoResults.classList.remove('hidden');
                    if (fornecedorResults) fornecedorResults.innerHTML = '';
                    showFornecedorDropdown();
                }
            })
            .catch(error => {
                handleFornecedorSearchError(error, timeoutId);
            });
    }
    
    // Função para buscar fornecedores por nome
    function buscarFornecedoresPorNome(nome) {
        showFornecedorLoading();
        
        // Criar AbortController para timeout
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 35000); // 35 segundos
        
        // Usar a API específica de fornecedores com parâmetro de nome
        fetch(`/api/omie/fornecedores/search?search=${encodeURIComponent(nome)}&type=nome`, {
            signal: controller.signal,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
            .then(response => {
                clearTimeout(timeoutId);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                hideFornecedorLoading();
                
                if (data.success && data.data && data.data.length > 0) {
                    displayFornecedorResults(data.data);
                } else {
                    // Nenhum resultado encontrado
                    if (fornecedorNoResults) fornecedorNoResults.classList.remove('hidden');
                    if (fornecedorResults) fornecedorResults.innerHTML = '';
                    showFornecedorDropdown();
                }
            })
            .catch(error => {
                handleFornecedorSearchError(error, timeoutId);
            });
    }
    
    // Função unificada para tratamento de erros
    function handleFornecedorSearchError(error, timeoutId) {
        clearTimeout(timeoutId);
        console.error('Erro ao buscar fornecedores OMIE:', error);
        hideFornecedorLoading();
        
        let errorMessage = 'Erro ao buscar fornecedores. Tente novamente.';
        
        if (error.name === 'AbortError') {
            errorMessage = 'Busca cancelada por timeout. Tente novamente.';
        } else if (error.message.includes('HTTP')) {
            errorMessage = `Erro no servidor: ${error.message}`;
        } else if (error.message.includes('Failed to fetch')) {
            errorMessage = 'Erro de conexão. Verifique sua internet.';
        }
        
        // Mostrar mensagem de erro
        if (fornecedorResults) {
            fornecedorResults.innerHTML = `
                <div class="px-4 py-3 text-red-600 text-sm">
                    ${errorMessage}
                </div>
            `;
        }
        showFornecedorDropdown();
    }
    
    function selecionarFornecedor(fornecedor) {
        const fornecedorId = fornecedor.omie_id || fornecedor.id || fornecedor.codigo_fornecedor_omie;
        const fornecedorNomeCompleto = fornecedor.razao_social || fornecedor.nome_fantasia || fornecedor.nome;
        
        // Preencher campos ocultos
        if (fornecedorOmieId) fornecedorOmieId.value = fornecedorId;
        if (fornecedorNome) fornecedorNome.value = fornecedorNomeCompleto;
        if (fornecedorSearch) fornecedorSearch.value = fornecedorNomeCompleto; // Campo oculto para compatibilidade
        
        // Preencher ambos os campos de busca
        if (fornecedorCodigoSearch) {
            fornecedorCodigoSearch.value = fornecedorId;
        }
        if (fornecedorNomeSearch) {
            fornecedorNomeSearch.value = fornecedorNomeCompleto;
        }
        
        // Atualizar elementos de exibição
        if (nomeFornecedorSelecionado) nomeFornecedorSelecionado.textContent = fornecedorNomeCompleto;
        if (fornecedorSelecionado) fornecedorSelecionado.style.display = 'block';
        
        hideFornecedorDropdown();
        
        console.log('Fornecedor selecionado:', {
            id: fornecedorId,
            nome: fornecedorNomeCompleto,
            campo_ativo: activeSearchField
        });
    }
    
    // ===== FUNCIONALIDADE CENTRO DE CUSTO =====
    const centroCustoSearch = document.getElementById('centro_custo_search');
    const centroCustoId = document.getElementById('centro_custo_id');
    const centroCustoDropdown = document.getElementById('centro_custo_dropdown');
    const centroCustoResults = document.getElementById('centro_custo_results');
    const centroCustoNoResults = document.getElementById('centro_custo_no_results');
    const centroCustoSelecionado = document.getElementById('centro_custo_selecionado');
    const nomeCentroCustoSelecionado = document.getElementById('nome_centro_custo_selecionado');
    
    let centroCustoTimeoutId;
    let centroCustoCurrentResults = [];
    let centroCustoSelectedIndex = -1;
    
    // Dados dos centros de custo (passados do backend)
    const centrosCustoData = @json($centrosCusto);
    
    function buscarCentrosCusto(termo) {
        if (!termo || termo.length < 1) {
            hideCentroCustoDropdown();
            return;
        }
        
        const termoLower = termo.toLowerCase();
        const resultados = centrosCustoData.filter(centro => {
            const codigo = centro.codigo ? centro.codigo.toLowerCase() : '';
            const nome = centro.name ? centro.name.toLowerCase() : '';
            
            return codigo.includes(termoLower) || nome.includes(termoLower);
        });
        
        displayCentroCustoResults(resultados);
    }
    
    function displayCentroCustoResults(centros) {
        centroCustoCurrentResults = centros;
        centroCustoSelectedIndex = -1;
        
        if (centros.length === 0) {
            if (centroCustoResults) centroCustoResults.innerHTML = '';
            if (centroCustoNoResults) centroCustoNoResults.classList.remove('hidden');
            showCentroCustoDropdown();
            return;
        }
        
        if (centroCustoNoResults) centroCustoNoResults.classList.add('hidden');
        
        const html = centros.map((centro, index) => `
            <div class="centro-custo-item px-4 py-3 cursor-pointer hover:bg-gray-50 border-b border-gray-100 last:border-b-0" 
                 data-index="${index}">
                <div class="font-medium text-gray-900">${centro.name}</div>
                <div class="text-sm text-gray-500">
                    Código: ${centro.codigo || 'N/A'}
                </div>
            </div>
        `).join('');
        
        if (centroCustoResults) {
            centroCustoResults.innerHTML = html;
            
            // Adicionar event listeners para clique
            centroCustoResults.querySelectorAll('.centro-custo-item').forEach((item, index) => {
                item.addEventListener('click', () => {
                    selecionarCentroCusto(centros[index]);
                });
                
                item.addEventListener('mouseenter', () => {
                    centroCustoSelectedIndex = index;
                    updateCentroCustoSelection();
                });
            });
        }
        
        showCentroCustoDropdown();
    }
    
    function updateCentroCustoSelection() {
        if (!centroCustoResults) return;
        
        const items = centroCustoResults.querySelectorAll('.centro-custo-item');
        items.forEach((item, index) => {
            if (index === centroCustoSelectedIndex) {
                item.classList.add('bg-blue-50');
            } else {
                item.classList.remove('bg-blue-50');
            }
        });
    }
    
    function selecionarCentroCusto(centro) {
        if (centroCustoId) centroCustoId.value = centro.id;
        if (centroCustoSearch) centroCustoSearch.value = `${centro.codigo} - ${centro.name}`;
        if (nomeCentroCustoSelecionado) nomeCentroCustoSelecionado.innerHTML = `<strong>${centro.codigo}</strong> - ${centro.name}`;
        if (centroCustoSelecionado) centroCustoSelecionado.classList.remove('hidden');
        hideCentroCustoDropdown();
        
        console.log('Centro de custo selecionado:', {
            id: centro.id,
            codigo: centro.codigo,
            nome: centro.name
        });
    }
    
    function limparCentroCusto() {
        if (centroCustoId) centroCustoId.value = '';
        if (centroCustoSearch) centroCustoSearch.value = '';
        if (centroCustoSelecionado) centroCustoSelecionado.classList.add('hidden');
        if (centroCustoSearch) centroCustoSearch.focus();
    }
    
    function showCentroCustoDropdown() {
        if (centroCustoDropdown) centroCustoDropdown.classList.remove('hidden');
    }
    
    function hideCentroCustoDropdown() {
        if (centroCustoDropdown) centroCustoDropdown.classList.add('hidden');
        centroCustoSelectedIndex = -1;
    }
    
    // Event listeners para centro de custo
    if (centroCustoSearch) {
        centroCustoSearch.addEventListener('input', function() {
            clearTimeout(centroCustoTimeoutId);
            const termo = this.value.trim();
            
            centroCustoTimeoutId = setTimeout(() => {
                buscarCentrosCusto(termo);
            }, 300);
        });
        
        // Navegação por teclado
        centroCustoSearch.addEventListener('keydown', function(e) {
            if (centroCustoDropdown && !centroCustoDropdown.classList.contains('hidden')) {
                switch(e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        centroCustoSelectedIndex = Math.min(centroCustoSelectedIndex + 1, centroCustoCurrentResults.length - 1);
                        updateCentroCustoSelection();
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        centroCustoSelectedIndex = Math.max(centroCustoSelectedIndex - 1, -1);
                        updateCentroCustoSelection();
                        break;
                    case 'Enter':
                        e.preventDefault();
                        if (centroCustoSelectedIndex >= 0 && centroCustoCurrentResults[centroCustoSelectedIndex]) {
                            selecionarCentroCusto(centroCustoCurrentResults[centroCustoSelectedIndex]);
                        }
                        break;
                    case 'Escape':
                        hideCentroCustoDropdown();
                        break;
                }
            }
        });
    }
    
    // Fechar dropdown ao clicar fora
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#centro_custo_search') && !e.target.closest('#centro_custo_dropdown')) {
            hideCentroCustoDropdown();
        }
    });
    
    // Carregar centro de custo selecionado se houver valor antigo
    if (centroCustoId && centroCustoId.value) {
        const centroSelecionado = centrosCustoData.find(centro => centro.id == centroCustoId.value);
        if (centroSelecionado) {
            selecionarCentroCusto(centroSelecionado);
        }
    }
    
    // Validação do formulário
    if (orcamentoForm) {
        orcamentoForm.addEventListener('submit', function(e) {
            const clienteId = clienteOmieId ? clienteOmieId.value : '';
            const fornecedorId = fornecedorOmieId ? fornecedorOmieId.value : '';
            const tipoOrcamento = tipoOrcamentoSelect ? tipoOrcamentoSelect.value : '';
            const centroCustoIdValue = centroCustoId ? centroCustoId.value : '';
            
            if (!clienteId) {
                e.preventDefault();
                alert('Por favor, selecione um cliente válido.');
                if (clienteSearch) clienteSearch.focus();
                return false;
            }
            
            if (!centroCustoIdValue) {
                e.preventDefault();
                alert('Por favor, selecione um centro de custo válido.');
                if (centroCustoSearch) centroCustoSearch.focus();
                return false;
            }
            
            // Validar fornecedor apenas para tipo 'prestador'
            if (tipoOrcamento === 'prestador' && !fornecedorId) {
                e.preventDefault();
                alert('Por favor, selecione um fornecedor válido.');
                if (fornecedorSearch) fornecedorSearch.focus();
                return false;
            }
        });
    }
});

</script>
@endpush

@endsection