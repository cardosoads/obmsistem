@extends('layouts.admin')

@section('title', 'Editar Orçamento')

@section('content')
<div class="py-12">
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Editar Orçamento #{{ $orcamento->numero_orcamento }}</h2>
                        <p class="text-sm text-gray-600 mt-1">
                            Criado em {{ $orcamento->created_at->format('d/m/Y H:i') }} por {{ $orcamento->user->name }}
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.orcamentos.show', $orcamento) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Visualizar
                        </a>
                        <a href="{{ route('admin.orcamentos.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Voltar
                        </a>
                    </div>
                </div>

                <!-- Formulário -->
                <form method="POST" action="{{ route('admin.orcamentos.update', $orcamento) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Dados Básicos -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Dados Básicos</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Data da Solicitação -->
                            <div>
                                <label for="data_solicitacao" class="block text-sm font-medium text-gray-700 mb-2">
                                    Data da Solicitação *
                                </label>
                                <input type="date" 
                                       id="data_solicitacao" 
                                       name="data_solicitacao" 
                                       value="{{ old('data_solicitacao', $orcamento->data_solicitacao ? $orcamento->data_solicitacao->format('Y-m-d') : '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('data_solicitacao') border-red-500 @enderror" 
                                       required>
                                @error('data_solicitacao')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Centro de Custo -->
                            <div>
                                <label for="centro_custo_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Centro de Custo *
                                </label>
                                <select id="centro_custo_id" 
                                        name="centro_custo_id" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('centro_custo_id') border-red-500 @enderror" 
                                        required>
                                    <option value="">Selecione um centro de custo</option>
                                    @foreach($centrosCusto as $centroCusto)
                                        <option value="{{ $centroCusto->id }}" 
                                                {{ old('centro_custo_id', $orcamento->centro_custo_id) == $centroCusto->id ? 'selected' : '' }}>
                                            {{ $centroCusto->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('centro_custo_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- ID de Protocolo -->
                            <div>
                                <label for="id_protocolo" class="block text-sm font-medium text-gray-700 mb-2">
                                    ID de Protocolo
                                </label>
                                <input type="text" 
                                       id="id_protocolo" 
                                       name="id_protocolo" 
                                       value="{{ old('id_protocolo', $orcamento->id_protocolo) }}"
                                       placeholder="Digite o ID de protocolo"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('id_protocolo') border-red-500 @enderror">
                                @error('id_protocolo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                    Status *
                                </label>
                                <select id="status" 
                                        name="status" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror" 
                                        required>
                                    <option value="em_andamento" {{ old('status', $orcamento->status) == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                                    <option value="enviado" {{ old('status', $orcamento->status) == 'enviado' ? 'selected' : '' }}>Enviado</option>
                                    <option value="aprovado" {{ old('status', $orcamento->status) == 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                                    <option value="rejeitado" {{ old('status', $orcamento->status) == 'rejeitado' ? 'selected' : '' }}>Rejeitado</option>
                                    <option value="cancelado" {{ old('status', $orcamento->status) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Informações da Rota -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações da Rota</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nome da Rota -->
                            <div>
                                <label for="nome_rota" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nome da Rota *
                                </label>
                                <input type="text" 
                                       id="nome_rota" 
                                       name="nome_rota" 
                                       value="{{ old('nome_rota', $orcamento->nome_rota) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('nome_rota') border-red-500 @enderror" 
                                       required>
                                @error('nome_rota')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- ID Logcare -->
                            <div>
                                <label for="id_logcare" class="block text-sm font-medium text-gray-700 mb-2">
                                    ID Logcare
                                </label>
                                <input type="text" 
                                       id="id_logcare" 
                                       name="id_logcare" 
                                       value="{{ old('id_logcare', $orcamento->id_logcare) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('id_logcare') border-red-500 @enderror">
                                @error('id_logcare')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Horário -->
                            <div>
                                <label for="horario" class="block text-sm font-medium text-gray-700 mb-2">
                                    Horário <span class="text-red-500">*</span>
                                </label>
                                <input type="time" 
                                       class="mt-1 block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base @error('horario') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500 @enderror" 
                                       id="horario" 
                                       name="horario" 
                                       value="{{ old('horario', '00:00') }}" 
                                       step="1"
                                       readonly
                                       required>
                                @error('horario')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Frequência de Atendimento -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Frequência de Atendimento <span class="text-red-500">*</span>
                                </label>
                                @php
                                    $diasSelecionados = old('frequencia_atendimento', $orcamento->frequencia_atendimento ?? []);
                                @endphp
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="checkbox" name="frequencia_atendimento[]" value="segunda" class="mr-2 text-indigo-600 focus:ring-indigo-500" {{ in_array('segunda', $diasSelecionados) ? 'checked' : '' }}>
                                        <span class="text-sm">Segunda</span>
                                    </label>
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="checkbox" name="frequencia_atendimento[]" value="terca" class="mr-2 text-indigo-600 focus:ring-indigo-500" {{ in_array('terca', $diasSelecionados) ? 'checked' : '' }}>
                                        <span class="text-sm">Terça</span>
                                    </label>
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="checkbox" name="frequencia_atendimento[]" value="quarta" class="mr-2 text-indigo-600 focus:ring-indigo-500" {{ in_array('quarta', $diasSelecionados) ? 'checked' : '' }}>
                                        <span class="text-sm">Quarta</span>
                                    </label>
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="checkbox" name="frequencia_atendimento[]" value="quinta" class="mr-2 text-indigo-600 focus:ring-indigo-500" {{ in_array('quinta', $diasSelecionados) ? 'checked' : '' }}>
                                        <span class="text-sm">Quinta</span>
                                    </label>
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="checkbox" name="frequencia_atendimento[]" value="sexta" class="mr-2 text-indigo-600 focus:ring-indigo-500" {{ in_array('sexta', $diasSelecionados) ? 'checked' : '' }}>
                                        <span class="text-sm">Sexta</span>
                                    </label>
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="checkbox" name="frequencia_atendimento[]" value="sabado" class="mr-2 text-indigo-600 focus:ring-indigo-500" {{ in_array('sabado', $diasSelecionados) ? 'checked' : '' }}>
                                        <span class="text-sm">Sábado</span>
                                    </label>
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="checkbox" name="frequencia_atendimento[]" value="domingo" class="mr-2 text-indigo-600 focus:ring-indigo-500" {{ in_array('domingo', $diasSelecionados) ? 'checked' : '' }}>
                                        <span class="text-sm">Domingo</span>
                                    </label>
                                </div>
                                @error('frequencia_atendimento')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Informações do Cliente -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Cliente</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Nome do Cliente -->
                            <div>
                                <label for="cliente_nome" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nome do Cliente
                                </label>
                                <input type="text" 
                                       id="cliente_nome" 
                                       name="cliente_nome" 
                                       value="{{ old('cliente_nome', $orcamento->cliente_nome) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('cliente_nome') border-red-500 @enderror">
                                @error('cliente_nome')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- ID Cliente Omie -->
                            <div>
                                <label for="cliente_omie_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    ID Cliente Omie
                                </label>
                                <input type="text" 
                                       id="cliente_omie_id" 
                                       name="cliente_omie_id" 
                                       value="{{ old('cliente_omie_id', $orcamento->cliente_omie_id) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('cliente_omie_id') border-red-500 @enderror">
                                @error('cliente_omie_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tipo de Orçamento -->
                            <div>
                                <label for="tipo_orcamento" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo de Orçamento *
                                </label>
                                <select id="tipo_orcamento" 
                                        name="tipo_orcamento" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('tipo_orcamento') border-red-500 @enderror" 
                                        required>
                                    <option value="">Selecione o tipo</option>
                                    <option value="prestador" {{ old('tipo_orcamento', $orcamento->tipo_orcamento) == 'prestador' ? 'selected' : '' }}>Prestador</option>
                                    <option value="aumento_km" {{ old('tipo_orcamento', $orcamento->tipo_orcamento) == 'aumento_km' ? 'selected' : '' }}>Aumento KM</option>
                                    <option value="proprio_nova_rota" {{ old('tipo_orcamento', $orcamento->tipo_orcamento) == 'proprio_nova_rota' ? 'selected' : '' }}>Próprio Nova Rota</option>
                                </select>
                                @error('tipo_orcamento')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Data de Validade -->

                    <!-- Dados do Prestador -->
                    <div id="dados_prestador" class="bg-gray-50 rounded-lg p-6" style="display: {{ old('tipo_orcamento', $orcamento->tipo_orcamento) == 'prestador' ? 'block' : 'none' }};">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Dados do Prestador</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Busca de Fornecedor OMIE -->
                            <div class="md:col-span-2">
                                <label for="fornecedor_omie_search" class="block text-sm font-medium text-gray-700 mb-2">
                                    Buscar Fornecedor OMIE *
                                </label>
                                <div class="relative">
                                    <input type="text" 
                                           id="fornecedor_omie_search" 
                                           placeholder="Digite o nome ou razão social do fornecedor..."
                                           value="{{ old('fornecedor_nome', $orcamento->orcamentoPrestador->fornecedor_nome ?? '') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('fornecedor_omie_id') border-red-500 @enderror">
                                    
                                    <!-- Dropdown de resultados -->
                                    <div id="fornecedor_dropdown" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg hidden">
                                        <!-- Loading -->
                                        <div id="fornecedor_loading" class="px-4 py-3 text-gray-500 text-sm hidden">
                                            <div class="flex items-center">
                                                <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Buscando fornecedores...
                                            </div>
                                        </div>
                                        
                                        <!-- Resultados -->
                                        <div id="fornecedor_results"></div>
                                        
                                        <!-- Nenhum resultado -->
                                        <div id="fornecedor_no_results" class="px-4 py-3 text-gray-500 text-sm hidden">
                                            Nenhum fornecedor encontrado
                                        </div>
                                    </div>
                                </div>
                                
                                @error('fornecedor_omie_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Fornecedor Selecionado -->
                            <div id="fornecedor_selecionado" class="md:col-span-2" style="display: {{ old('fornecedor_omie_id', $orcamento->orcamentoPrestador->fornecedor_omie_id ?? '') ? 'block' : 'none' }};">
                                <div class="bg-green-50 border border-green-200 rounded-md p-3">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-green-800">Fornecedor selecionado:</span>
                                        <span id="nome_fornecedor_selecionado" class="ml-2 text-sm text-green-700">{{ old('fornecedor_nome', $orcamento->orcamentoPrestador->fornecedor_nome ?? '') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Campos ocultos para envio -->
                            <input type="hidden" id="fornecedor_omie_id" name="fornecedor_omie_id" value="{{ old('fornecedor_omie_id', $orcamento->orcamentoPrestador->fornecedor_omie_id ?? '') }}">
                            <input type="hidden" id="fornecedor_nome" name="fornecedor_nome" value="{{ old('fornecedor_nome', $orcamento->orcamentoPrestador->fornecedor_nome ?? '') }}">

                            <!-- Valor de Referência -->
                            <div>
                                <label for="valor_referencia" class="block text-sm font-medium text-gray-700 mb-2">
                                    Valor de Referência (R$) *
                                </label>
                                <input type="number" 
                                       id="valor_referencia" 
                                       name="valor_referencia" 
                                       value="{{ old('valor_referencia', $orcamento->orcamentoPrestador->valor_referencia ?? '') }}"
                                       step="0.01" 
                                       min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('valor_referencia') border-red-500 @enderror">
                                @error('valor_referencia')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quantidade de Dias -->
                            <div>
                                <label for="qtd_dias" class="block text-sm font-medium text-gray-700 mb-2">
                                    Quantidade de Dias *
                                </label>
                                <input type="number" 
                                       id="qtd_dias" 
                                       name="qtd_dias" 
                                       value="{{ old('qtd_dias', $orcamento->orcamentoPrestador->qtd_dias ?? '') }}"
                                       min="1"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('qtd_dias') border-red-500 @enderror">
                                @error('qtd_dias')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Percentual de Lucro -->
            <div>
                <label for="percentual_lucro" class="block text-sm font-medium text-gray-700 mb-2">
                    Percentual de Lucro (%) *
                </label>
                <input type="number" 
                       id="percentual_lucro"
name="percentual_lucro"
value="{{ old('percentual_lucro', $orcamento->orcamentoPrestador->lucro_percentual ?? '') }}"
                       step="0.01" 
                       min="0" 
                       max="100"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('percentual_lucro') border-red-500 @enderror">
                @error('percentual_lucro')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grupo de Impostos -->
            <div>
                <label for="grupo_imposto_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Grupo de Impostos
                </label>
                <select id="grupo_imposto_id" 
                        name="grupo_imposto_id" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('grupo_imposto_id') border-red-500 @enderror">
                    <option value="">Selecione um grupo de impostos</option>
                    @foreach($gruposImpostos as $grupo)
                        <option value="{{ $grupo->id }}" 
                                data-percentual="{{ $grupo->percentual_total }}"
                                {{ old('grupo_imposto_id', $orcamento->orcamentoPrestador->grupo_imposto_id ?? '') == $grupo->id ? 'selected' : '' }}>
                            {{ $grupo->nome }} ({{ number_format($grupo->percentual_total, 2, ',', '.') }}%)
                        </option>
                    @endforeach
                </select>
                @error('grupo_imposto_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

                            <!-- Percentual de Impostos -->
                            <div>
                                <label for="percentual_impostos" class="block text-sm font-medium text-gray-700 mb-2">
                                    Percentual de Impostos (%) *
                                </label>
                                <input type="number" 
                                       id="percentual_impostos"
name="percentual_impostos"
value="{{ old('percentual_impostos', $orcamento->orcamentoPrestador->impostos_percentual ?? '') }}"
                                       step="0.01" 
                                       min="0" 
                                       max="100"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('percentual_impostos') border-red-500 @enderror">
                                @error('percentual_impostos')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Resumo dos Cálculos -->
                        <div class="mt-6 bg-white rounded-lg p-4 border border-gray-200">
                            <h4 class="text-md font-semibold text-gray-800 mb-3">Resumo dos Cálculos</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <span class="text-blue-600 font-medium">Custo Fornecedor:</span>
                                    <div id="display_custo_fornecedor" class="text-lg font-bold text-blue-800">
                                        R$ {{ number_format($orcamento->orcamentoPrestador->custo_fornecedor ?? 0, 2, ',', '.') }}
                                    </div>
                                </div>
                                <div class="bg-green-50 p-3 rounded-lg">
                                    <span class="text-green-600 font-medium">Valor Lucro:</span>
                                    <div id="display_valor_lucro" class="text-lg font-bold text-green-800">
                                        R$ {{ number_format($orcamento->orcamentoPrestador->valor_lucro ?? 0, 2, ',', '.') }}
                                    </div>
                                </div>
                                <div class="bg-yellow-50 p-3 rounded-lg">
                                    <span class="text-yellow-600 font-medium">Valor Impostos:</span>
                                    <div id="display_valor_impostos" class="text-lg font-bold text-yellow-800">
                                        R$ {{ number_format($orcamento->orcamentoPrestador->valor_impostos ?? 0, 2, ',', '.') }}
                                    </div>
                                </div>
                                <div class="bg-purple-50 p-3 rounded-lg">
                                    <span class="text-purple-600 font-medium">Valor Total:</span>
                                    <div id="display_valor_total" class="text-lg font-bold text-purple-800">
                                        R$ {{ number_format($orcamento->orcamentoPrestador->valor_total ?? 0, 2, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Observações -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Observações</h3>
                        <div>
                            <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-2">
                                Observações
                            </label>
                            <textarea id="observacoes" 
                                      name="observacoes" 
                                      rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('observacoes') border-red-500 @enderror" 
                                      placeholder="Digite observações adicionais sobre o orçamento...">{{ old('observacoes', $orcamento->observacoes) }}</textarea>
                            @error('observacoes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Campos Hidden para Valores Calculados -->
                    <input type="hidden" id="valor_lucro_hidden" name="valor_lucro" value="{{ old('valor_lucro', $orcamento->orcamentoPrestador->valor_lucro ?? 0) }}">
                    <input type="hidden" id="valor_impostos_hidden" name="valor_impostos" value="{{ old('valor_impostos', $orcamento->orcamentoPrestador->valor_impostos ?? 0) }}">

                    <!-- Botões de Ação -->
                    <div class="flex justify-end space-x-3 pt-6">
                        <a href="{{ route('admin.orcamentos.show', $orcamento) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Atualizar Orçamento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gerenciar seleção de dias da semana para frequência de atendimento
    function updateFrequenciaAtendimento() {
        const checkboxes = document.querySelectorAll('input[name="frequencia_atendimento[]"]');
        const hiddenInput = document.getElementById('frequencia_atendimento_hidden');
        
        if (!hiddenInput) return;
        
        const diasSelecionados = [];
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                diasSelecionados.push(checkbox.value);
            }
        });
        
        hiddenInput.value = diasSelecionados.join(',');
    }

    // Adicionar event listeners aos checkboxes de frequência
    const frequenciaCheckboxes = document.querySelectorAll('input[name="frequencia_atendimento[]"]');
    frequenciaCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateFrequenciaAtendimento);
    });
    
    // Carregar valores existentes na edição
    const frequenciaAtendimentoValue = '{{ old("frequencia_atendimento", is_array($orcamento->frequencia_atendimento) ? implode(",", $orcamento->frequencia_atendimento) : ($orcamento->frequencia_atendimento ?? "")) }}';
    if (frequenciaAtendimentoValue) {
        const diasSelecionados = frequenciaAtendimentoValue.split(',');
        frequenciaCheckboxes.forEach(checkbox => {
            if (diasSelecionados.includes(checkbox.value)) {
                checkbox.checked = true;
            }
        });
    }
    
    // Atualizar na inicialização
    updateFrequenciaAtendimento();
    
    // Controle de exibição da seção Dados do Prestador
    const tipoOrcamentoSelect = document.getElementById('tipo_orcamento');
    const dadosPrestadorDiv = document.getElementById('dados_prestador');
    
    function toggleDadosPrestador() {
        if (tipoOrcamentoSelect.value === 'prestador') {
            dadosPrestadorDiv.style.display = 'block';
        } else {
            dadosPrestadorDiv.style.display = 'none';
        }
    }
    
    tipoOrcamentoSelect.addEventListener('change', toggleDadosPrestador);
    
    // Cálculos automáticos para prestador
    const valorReferenciaInput = document.getElementById('valor_referencia');
    const qtdDiasInput = document.getElementById('qtd_dias');
    const percentualLucroInput = document.getElementById('percentual_lucro');
        const percentualImpostosInput = document.getElementById('percentual_impostos');
    
    function calcularValoresPrestador() {
        const valorReferencia = parseFloat(valorReferenciaInput.value) || 0;
        const qtdDias = parseInt(qtdDiasInput.value) || 0;
        const percentualLucro = parseFloat(percentualLucroInput.value) || 0;
        const percentualImpostos = parseFloat(percentualImpostosInput.value) || 0;
        
        // Custo Fornecedor = Valor Referência × Quantidade de Dias
        const custoFornecedor = valorReferencia * qtdDias;
        
        // Valor Lucro = Custo Fornecedor × (Percentual Lucro / 100)
        const valorLucro = custoFornecedor * (percentualLucro / 100);
        
        // Valor Impostos = Custo Fornecedor × (Percentual Impostos / 100)
        const valorImpostos = custoFornecedor * (percentualImpostos / 100);
        
        // Valor Total = Custo Fornecedor + Valor Lucro + Valor Impostos
        const valorTotal = custoFornecedor + valorLucro + valorImpostos;
        
        // Atualizar displays
        document.getElementById('display_custo_fornecedor').textContent = 
            'R$ ' + custoFornecedor.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('display_valor_lucro').textContent = 
            'R$ ' + valorLucro.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('display_valor_impostos').textContent = 
            'R$ ' + valorImpostos.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('display_valor_total').textContent = 
            'R$ ' + valorTotal.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        
        // Atualizar campos hidden para envio ao backend
        const valorLucroHidden = document.getElementById('valor_lucro_hidden');
        const valorImpostosHidden = document.getElementById('valor_impostos_hidden');
        
        if (valorLucroHidden) {
            valorLucroHidden.value = valorLucro.toFixed(2);
        }
        
        if (valorImpostosHidden) {
            valorImpostosHidden.value = valorImpostos.toFixed(2);
        }
    }
    
    // Função para formatar percentuais com 2 casas decimais e máximo 100
    function formatarPercentual(input) {
        let valor = parseFloat(input.value);
        if (isNaN(valor)) {
            valor = 0;
        }
        if (valor > 100) {
            valor = 100;
        }
        input.value = valor.toFixed(2);
    }
    
    // Adicionar event listeners para cálculos automáticos
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
            formatarPercentual(this);
            calcularValoresPrestador();
        });
    }
    
    // Integração com Grupos de Impostos
    const grupoImpostoSelect = document.getElementById('grupo_imposto_id');
    
    if (grupoImpostoSelect) {
        grupoImpostoSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (selectedOption && selectedOption.value) {
                // Buscar percentual via AJAX para obter dados mais detalhados
                fetch('/admin/orcamentos/buscar-percentual-grupo-imposto', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        grupo_imposto_id: selectedOption.value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && percentualImpostosInput) {
                        percentualImpostosInput.value = data.percentual_total;
                        formatarPercentual(percentualImpostosInput);
                        calcularValoresPrestador();
                        
                        console.log('Grupo de impostos selecionado:', {
                            nome: data.nome_grupo,
                            percentual: data.percentual_total,
                            impostos: data.impostos
                        });
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar percentual do grupo de impostos:', error);
                    
                    // Fallback: usar o percentual do data-attribute
                    const percentual = selectedOption.getAttribute('data-percentual');
                    if (percentual && percentualImpostosInput) {
                        percentualImpostosInput.value = percentual;
                        formatarPercentual(percentualImpostosInput);
                        calcularValoresPrestador();
                    }
                });
            } else {
                // Limpar campo se nenhum grupo selecionado
                if (percentualImpostosInput) {
                    percentualImpostosInput.value = '';
                    calcularValoresPrestador();
                }
            }
        });
    }
    
    // Função para calcular automaticamente a quantidade de dias baseado na frequência de atendimento
    function calcularQuantidadeDias() {
        const checkboxes = document.querySelectorAll('input[name=\"frequencia_atendimento[]\"]:checked');
        const qtdDiasInput = document.getElementById('qtd_dias');
        const qtdDiasAumentoInput = document.getElementById('qtd_dias_aumento');
        const tipoOrcamento = document.getElementById('tipo_orcamento');

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
    
    // Calcular valores iniciais se já existirem dados
    calcularValoresPrestador();
    
    // Busca de fornecedores OMIE - Funcionalidade igual à do cliente
    let fornecedorTimeoutId;
    
    const fornecedorSearch = document.getElementById('fornecedor_omie_search');
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
    
    if (fornecedorSearch) {
        fornecedorSearch.addEventListener('input', function() {
            clearTimeout(fornecedorTimeoutId);
            const termo = this.value.trim();
            
            if (termo.length >= 2) {
                fornecedorTimeoutId = setTimeout(() => {
                    buscarFornecedoresOmie(termo);
                }, 500);
            } else {
                hideFornecedorDropdown();
            }
        });
        
        // Navegação por teclado
        fornecedorSearch.addEventListener('keydown', function(e) {
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
        });
    }
    
    // Fechar dropdown ao clicar fora
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#fornecedor_omie_search') && !e.target.closest('#fornecedor_dropdown')) {
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
    
    function buscarFornecedoresOmie(termo) {
        showFornecedorLoading();
        
        // Usar a API específica de fornecedores
        fetch(`/api/omie/fornecedores/search?search=${encodeURIComponent(termo)}`)
            .then(response => response.json())
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
                console.error('Erro ao buscar fornecedores OMIE:', error);
                hideFornecedorLoading();
                
                // Mostrar mensagem de erro
                if (fornecedorResults) {
                    fornecedorResults.innerHTML = `
                        <div class="px-4 py-3 text-red-600 text-sm">
                            Erro ao buscar fornecedores. Tente novamente.
                        </div>
                    `;
                }
                showFornecedorDropdown();
            });
    }
    
    function selecionarFornecedor(fornecedor) {
        if (fornecedorOmieId) fornecedorOmieId.value = fornecedor.omie_id || fornecedor.id || fornecedor.codigo_fornecedor_omie;
        if (fornecedorNome) fornecedorNome.value = fornecedor.razao_social || fornecedor.nome_fantasia || fornecedor.nome;
        if (fornecedorSearch) fornecedorSearch.value = fornecedor.razao_social || fornecedor.nome_fantasia || fornecedor.nome;
        if (nomeFornecedorSelecionado) nomeFornecedorSelecionado.textContent = fornecedor.razao_social || fornecedor.nome_fantasia || fornecedor.nome;
        if (fornecedorSelecionado) fornecedorSelecionado.style.display = 'block';
        hideFornecedorDropdown();
        
        console.log('Fornecedor selecionado:', {
            id: fornecedor.omie_id || fornecedor.id || fornecedor.codigo_fornecedor_omie,
            nome: fornecedor.razao_social || fornecedor.nome_fantasia || fornecedor.nome
        });
    }
});
</script>
@endpush

@endsection