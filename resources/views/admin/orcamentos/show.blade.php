@extends('layouts.admin')

@section('title', 'Visualizar Orçamento')

@section('content')
<div class="py-12">
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Orçamento #{{ $orcamento->numero_orcamento }}</h2>
                        <p class="text-sm text-gray-600 mt-1">
                            Criado em {{ $orcamento->created_at->format('d/m/Y H:i') }} por {{ $orcamento->user->name }}
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.orcamentos.pdf', $orcamento) }}" 
                           class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                           target="_blank">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Exportar PDF
                        </a>
                        <a href="{{ route('admin.orcamentos.edit', $orcamento) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar
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

                <!-- Status Badge -->
                <div class="mb-6">
                    @switch($orcamento->status)
                        @case('em_andamento')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 01-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4z" clip-rule="evenodd"></path>
                                </svg>
                                Em Andamento
                            </span>
                            @break
                        @case('enviado')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                Enviado
                            </span>
                            @break
                        @case('aprovado')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Aprovado
                            </span>
                            @break
                        @case('rejeitado')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Rejeitado
                            </span>
                            @break
                        @case('cancelado')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Cancelado
                            </span>
                            @break
                    @endswitch
                </div>

                <!-- Informações Principais -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Dados Básicos -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Dados Básicos</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Data da Solicitação</label>
                                <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($orcamento->data_solicitacao)->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Data do Orçamento</label>
                                <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($orcamento->data_orcamento)->format('d/m/Y') }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Centro de Custo</label>
                                <p class="text-sm text-gray-900">{{ $orcamento->centroCusto->name ?? 'N/A' }}</p>
                            </div>
                            @if($orcamento->id_protocolo)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">ID de Protocolo</label>
                                <p class="text-sm text-gray-900">{{ $orcamento->id_protocolo }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Informações da Rota -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações da Rota</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nome da Rota</label>
                                <p class="text-sm text-gray-900">{{ $orcamento->nome_rota }}</p>
                            </div>
                            @if($orcamento->id_logcare)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">ID Logcare</label>
                                <p class="text-sm text-gray-900">{{ $orcamento->id_logcare }}</p>
                            </div>
                            @endif
                            @if($orcamento->horario)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Horário</label>
                                <p class="text-sm text-gray-900">{{ $orcamento->horario }}</p>
                            </div>
                            @endif
                            @if($orcamento->frequencia_atendimento && count($orcamento->frequencia_atendimento) > 0)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Frequência de Atendimento</label>
                                <p class="text-sm text-gray-900">
                                    @php
                                        $diasFormatados = collect($orcamento->frequencia_atendimento)->map(function($dia) {
                                            return ucfirst($dia);
                                        })->join(', ');
                                    @endphp
                                    {{ $diasFormatados }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Informações do Cliente -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Cliente</h3>
                        <div class="space-y-3">
                            @if($orcamento->cliente_nome)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nome do Cliente</label>
                                <p class="text-sm text-gray-900">{{ $orcamento->cliente_nome }}</p>
                            </div>
                            @endif
                            @if($orcamento->cliente_omie_id)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">ID Cliente Omie</label>
                                <p class="text-sm text-gray-900">{{ $orcamento->cliente_omie_id }}</p>
                            </div>
                            @endif
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tipo de Orçamento</label>
                                <p class="text-sm text-gray-900">
                                    @switch($orcamento->tipo_orcamento)
                                        @case('prestador')
                                            Prestador
                                            @break
                                        @case('aumento_km')
                                            Aumento KM
                                            @break
                                        @case('proprio_nova_rota')
                                            Próprio Nova Rota
                                            @break
                                        @default
                                            {{ $orcamento->tipo_orcamento }}
                                    @endswitch
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dados do Prestador -->
                @if($orcamento->tipo_orcamento === 'prestador' && $orcamento->orcamentoPrestador)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Detalhamento Financeiro - Prestador</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-handshake mr-2"></i>
                            Serviço Terceirizado
                        </span>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Informações do Fornecedor -->
                        <div class="bg-blue-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-building mr-2"></i>
                                Dados do Fornecedor
                            </h4>
                            <div class="space-y-4">
                                @if($orcamento->orcamentoPrestador->fornecedor_omie_id)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700 font-medium">ID Fornecedor Omie:</span>
                                    <span class="text-gray-900 font-semibold">{{ $orcamento->orcamentoPrestador->fornecedor_omie_id }}</span>
                                </div>
                                @endif
                                
                                @if($orcamento->orcamentoPrestador->valor_referencia)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700 font-medium">Valor de Referência (por dia):</span>
                                    <span class="text-blue-600 font-bold text-lg">R$ {{ number_format($orcamento->orcamentoPrestador->valor_referencia, 2, ',', '.') }}</span>
                                </div>
                                @endif
                                
                                @if($orcamento->orcamentoPrestador->qtd_dias)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700 font-medium">Quantidade de Dias:</span>
                                    <span class="text-gray-900 font-semibold">{{ $orcamento->orcamentoPrestador->qtd_dias }} dias</span>
                                </div>
                                @endif
                                
                                @if($orcamento->orcamentoPrestador->custo_fornecedor)
                                <div class="bg-white rounded-lg p-4 mt-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-blue-600">R$ {{ number_format($orcamento->orcamentoPrestador->custo_fornecedor, 2, ',', '.') }}</div>
                                        <div class="text-sm text-gray-600 mt-1">Custo Total do Fornecedor</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Configurações de Margem -->
                        <div class="bg-yellow-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-percentage mr-2"></i>
                                Configurações de Margem
                            </h4>
                            <div class="space-y-4">
                                @if($orcamento->orcamentoPrestador->lucro_percentual)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700 font-medium">Percentual de Lucro:</span>
                                    <span class="text-yellow-600 font-bold text-lg">{{ number_format($orcamento->orcamentoPrestador->lucro_percentual, 2, ',', '.') }}%</span>
                                </div>
                                @endif
                                
                                @if($orcamento->orcamentoPrestador->impostos_percentual)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700 font-medium">Percentual de Impostos:</span>
                                    <span class="text-red-600 font-bold text-lg">{{ number_format($orcamento->orcamentoPrestador->impostos_percentual, 2, ',', '.') }}%</span>
                                </div>
                                @endif
                                
                                @if($orcamento->orcamentoPrestador->valor_lucro)
                                <div class="bg-white rounded-lg p-4 mt-4">
                                    <div class="text-center">
                                        <div class="text-xl font-bold text-yellow-600">R$ {{ number_format($orcamento->orcamentoPrestador->valor_lucro, 2, ',', '.') }}</div>
                                        <div class="text-sm text-gray-600 mt-1">Valor do Lucro</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Resumo Financeiro -->
                    <div class="bg-gray-50 rounded-lg p-6 mt-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-calculator mr-2"></i>
                            Resumo Financeiro
                        </h4>
                        <div class="space-y-4">
                            <!-- Custo Base -->
                            @if($orcamento->orcamentoPrestador->custo_fornecedor)
                            <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                <span class="text-gray-700 font-medium">Custo do Fornecedor</span>
                                <span class="text-lg font-semibold text-gray-900">R$ {{ number_format($orcamento->orcamentoPrestador->custo_fornecedor, 2, ',', '.') }}</span>
                            </div>
                            @endif
                            
                            <!-- Lucro -->
                            @if($orcamento->orcamentoPrestador->valor_lucro)
                            <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                <span class="text-gray-700 font-medium">Margem de Lucro</span>
                                <span class="text-lg font-semibold text-yellow-600">+ R$ {{ number_format($orcamento->orcamentoPrestador->valor_lucro, 2, ',', '.') }}</span>
                            </div>
                            @endif
                            
                            <!-- Impostos -->
                            @if($orcamento->orcamentoPrestador->valor_impostos)
                            <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                <span class="text-gray-700 font-medium">Impostos e Taxas</span>
                                <span class="text-lg font-semibold text-red-600">+ R$ {{ number_format($orcamento->orcamentoPrestador->valor_impostos, 2, ',', '.') }}</span>
                            </div>
                            @endif
                            
                            <!-- Total -->
                            @if($orcamento->orcamentoPrestador->valor_total)
                            <div class="flex justify-between items-center py-4 bg-green-100 rounded-lg px-4 mt-4">
                                <span class="text-lg font-bold text-gray-900">Valor Total do Orçamento</span>
                                <span class="text-2xl font-bold text-green-600">R$ {{ number_format($orcamento->orcamentoPrestador->valor_total, 2, ',', '.') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Observações -->
                    <div class="bg-blue-50 rounded-lg p-4 mt-6">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                            <div>
                                <h5 class="font-semibold text-blue-900 mb-2">Informações Importantes</h5>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• Este orçamento utiliza serviços de prestador terceirizado</li>
                                    <li>• Os valores incluem margem de lucro e impostos aplicáveis</li>
                                    <li>• O custo base é calculado multiplicando o valor de referência pela quantidade de dias</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Dados do Aumento de KM -->
                @if($orcamento->tipo_orcamento === 'aumento_km' && $orcamento->orcamentoAumentoKm)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Detalhamento Financeiro - Aumento de KM</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-route mr-2"></i>
                            Aumento KM
                        </span>
                    </div>

                    <div class="space-y-8">
                        <!-- Dados Operacionais -->
                        <div class="bg-blue-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                                <i class="fas fa-tachometer-alt mr-2"></i>
                                Dados Operacionais
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <div class="text-center">
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <div class="text-2xl font-bold text-blue-600">{{ number_format($orcamento->orcamentoAumentoKm->km_dia, 0, ',', '.') }}</div>
                                        <div class="text-sm text-gray-600 mt-1">KM por Dia</div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <div class="text-2xl font-bold text-blue-600">{{ $orcamento->orcamentoAumentoKm->qtd_dias }}</div>
                                        <div class="text-sm text-gray-600 mt-1">Quantidade de Dias</div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <div class="text-2xl font-bold text-blue-600">{{ number_format($orcamento->orcamentoAumentoKm->km_total_mes, 0, ',', '.') }}</div>
                                        <div class="text-sm text-gray-600 mt-1">KM Total/Mês</div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <div class="text-2xl font-bold text-blue-600">{{ number_format($orcamento->orcamentoAumentoKm->combustivel_km_litro, 1, ',', '.') }}</div>
                                        <div class="text-sm text-gray-600 mt-1">KM por Litro</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cálculos de Combustível -->
                        <div class="bg-green-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-green-900 mb-4 flex items-center">
                                <i class="fas fa-gas-pump mr-2"></i>
                                Cálculos de Combustível
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="text-center">
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <div class="text-xl font-bold text-green-600">{{ number_format($orcamento->orcamentoAumentoKm->total_combustivel, 2, ',', '.') }}L</div>
                                        <div class="text-sm text-gray-600 mt-1">Total de Combustível</div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <div class="text-xl font-bold text-green-600">R$ {{ number_format($orcamento->orcamentoAumentoKm->valor_combustivel, 2, ',', '.') }}</div>
                                        <div class="text-sm text-gray-600 mt-1">Valor por Litro</div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <div class="text-xl font-bold text-green-600">R$ {{ number_format($orcamento->orcamentoAumentoKm->hora_extra, 2, ',', '.') }}</div>
                                        <div class="text-sm text-gray-600 mt-1">Hora Extra</div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <div class="text-xl font-bold text-green-600">R$ {{ number_format($orcamento->orcamentoAumentoKm->pedagio ?? 0, 2, ',', '.') }}</div>
                                        <div class="text-sm text-gray-600 mt-1">Pedágio</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Resumo Financeiro -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-calculator mr-2"></i>
                                Resumo Financeiro
                            </h4>
                            <div class="space-y-4">
                                <!-- Custo Base -->
                                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                    <span class="text-gray-700 font-medium">Custo Total (Combustível + Hora Extra + Pedágio)</span>
                                    <span class="text-lg font-semibold text-gray-900">R$ {{ number_format($orcamento->orcamentoAumentoKm->custo_total_combustivel_he, 2, ',', '.') }}</span>
                                </div>
                                
                                <!-- Percentuais -->
                                @if($orcamento->orcamentoAumentoKm->lucro_percentual)
                                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                    <span class="text-gray-700 font-medium">Percentual de Lucro</span>
                                    <span class="text-yellow-600 font-bold text-lg">{{ number_format($orcamento->orcamentoAumentoKm->lucro_percentual, 2, ',', '.') }}%</span>
                                </div>
                                @endif
                                
                                @if($orcamento->orcamentoAumentoKm->impostos_percentual)
                                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                    <span class="text-gray-700 font-medium">Percentual de Impostos</span>
                                    <span class="text-red-600 font-bold text-lg">{{ number_format($orcamento->orcamentoAumentoKm->impostos_percentual, 2, ',', '.') }}%</span>
                                </div>
                                @endif

                                <!-- Valores Calculados -->
                                @if($orcamento->orcamentoAumentoKm->valor_lucro)
                                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                    <span class="text-gray-700 font-medium">Valor do Lucro</span>
                                    <span class="text-lg font-semibold text-yellow-600">+ R$ {{ number_format($orcamento->orcamentoAumentoKm->valor_lucro, 2, ',', '.') }}</span>
                                </div>
                                @endif
                                
                                @if($orcamento->orcamentoAumentoKm->valor_impostos)
                                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                    <span class="text-gray-700 font-medium">Valor dos Impostos</span>
                                    <span class="text-lg font-semibold text-red-600">+ R$ {{ number_format($orcamento->orcamentoAumentoKm->valor_impostos, 2, ',', '.') }}</span>
                                </div>
                                @endif
                                
                                <!-- Total -->
                                <div class="flex justify-between items-center py-4 bg-green-100 rounded-lg px-4 mt-4">
                                    <span class="text-xl font-bold text-green-800">Valor Total do Orçamento</span>
                                    <span class="text-2xl font-bold text-green-800">R$ {{ number_format($orcamento->orcamentoAumentoKm->valor_total, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Observações do Aumento KM -->
                        @if($orcamento->orcamentoAumentoKm->observacoes)
                        <div class="bg-yellow-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-yellow-900 mb-3 flex items-center">
                                <i class="fas fa-sticky-note mr-2"></i>
                                Observações Específicas
                            </h4>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <p class="text-gray-700 whitespace-pre-wrap">{{ $orcamento->orcamentoAumentoKm->observacoes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Observações -->
                @if($orcamento->observacoes)
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Observações</h3>
                    <div class="prose max-w-none">
                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $orcamento->observacoes }}</p>
                    </div>
                </div>
                @endif

                <!-- Histórico de Alterações -->
                <div class="mt-8 bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Sistema</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Criado em</label>
                            <p class="text-sm text-gray-900">{{ $orcamento->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Última atualização</label>
                            <p class="text-sm text-gray-900">{{ $orcamento->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection