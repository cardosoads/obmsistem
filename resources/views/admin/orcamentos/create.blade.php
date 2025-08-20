@extends('layouts.admin')

@section('title', 'Novo Orçamento')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Novo Orçamento</h1>
        <a href="{{ route('admin.orcamentos.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.orcamentos.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Informações Básicas -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Informações Básicas</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label for="data_solicitacao" class="block text-sm font-medium text-gray-700 mb-2">Data de Solicitação *</label>
                    <input type="date" 
                           id="data_solicitacao" 
                           name="data_solicitacao" 
                           value="{{ old('data_solicitacao', date('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <div>
                    <label for="centro_custo_id" class="block text-sm font-medium text-gray-700 mb-2">Centro de Custo *</label>
                    <select id="centro_custo_id" 
                            name="centro_custo_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <option value="">Selecione um centro de custo</option>
                        @foreach($centrosCusto as $centroCusto)
                            <option value="{{ $centroCusto->id }}" {{ old('centro_custo_id') == $centroCusto->id ? 'selected' : '' }}>
                                {{ $centroCusto->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="numero_orcamento" class="block text-sm font-medium text-gray-700 mb-2">Número do Orçamento</label>
                    <input type="text" 
                           id="numero_orcamento" 
                           name="numero_orcamento" 
                           value="{{ old('numero_orcamento', 'AUTO-' . date('YmdHis')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100"
                           readonly>
                </div>

                <div>
                    <label for="evento" class="block text-sm font-medium text-gray-700 mb-2">Evento *</label>
                    <select id="evento" 
                            name="evento" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <option value="">Selecione o evento</option>
                        <option value="AUMENTO DE KM" {{ old('evento') == 'AUMENTO DE KM' ? 'selected' : '' }}>AUMENTO DE KM</option>
                        <option value="BASE" {{ old('evento') == 'BASE' ? 'selected' : '' }}>BASE</option>
                        <option value="INCLUSÃO" {{ old('evento') == 'INCLUSÃO' ? 'selected' : '' }}>INCLUSÃO</option>
                    </select>
                </div>

                <div>
                    <label for="nome_rota" class="block text-sm font-medium text-gray-700 mb-2">Nome da Rota *</label>
                    <input type="text" 
                           id="nome_rota" 
                           name="nome_rota" 
                           value="{{ old('nome_rota') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <div>
                    <label for="id_logcare" class="block text-sm font-medium text-gray-700 mb-2">ID LOGCARE *</label>
                    <input type="text" 
                           id="id_logcare" 
                           name="id_logcare" 
                           value="{{ old('id_logcare') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <div>
                    <label for="cliente_omie" class="block text-sm font-medium text-gray-700 mb-2">Cliente (OMIE) *</label>
                    <div class="relative">
                        <input type="text" 
                               id="cliente_omie_search" 
                               name="cliente_omie_search"
                               placeholder="Digite para buscar cliente..."
                               value="{{ old('cliente_nome') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               autocomplete="off"
                               required>
                        <input type="hidden" 
                               id="cliente_omie_id" 
                               name="cliente_omie_id" 
                               value="{{ old('cliente_omie_id') }}">
                        <input type="hidden" 
                               id="cliente_nome" 
                               name="cliente_nome" 
                               value="{{ old('cliente_nome') }}">
                        
                        <!-- Dropdown de resultados -->
                        <div id="cliente_dropdown" 
                             class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto hidden">
                            <div id="cliente_loading" class="p-3 text-center text-gray-500 hidden">
                                <i class="fas fa-spinner fa-spin"></i> Buscando clientes...
                            </div>
                            <div id="cliente_results"></div>
                            <div id="cliente_no_results" class="p-3 text-center text-gray-500 hidden">
                                Nenhum cliente encontrado
                            </div>
                        </div>
                    </div>
                    <small class="text-gray-500">Cliente selecionado será buscado da API OMIE</small>
                </div>

                <div>
                    <label for="horario" class="block text-sm font-medium text-gray-700 mb-2">Horário *</label>
                    <input type="time" 
                           id="horario" 
                           name="horario" 
                           value="{{ old('horario') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <div>
                    <label for="frequencia_atendimento" class="block text-sm font-medium text-gray-700 mb-2">Frequência de Atendimento *</label>
                    <input type="text" 
                           id="frequencia_atendimento" 
                           name="frequencia_atendimento" 
                           value="{{ old('frequencia_atendimento') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: Diário, Semanal, etc."
                           required>
                </div>
            </div>
        </div>

        <!-- Tipo de Orçamento -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Tipo de Orçamento</h3>
            <div class="mb-4">
                <label for="tipo_orcamento" class="block text-sm font-medium text-gray-700 mb-2">Selecione o Tipo *</label>
                <select id="tipo_orcamento" 
                        name="tipo_orcamento" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                    <option value="">Selecione o tipo de orçamento</option>
                    <option value="prestador" {{ old('tipo_orcamento') == 'prestador' ? 'selected' : '' }}>TIPO 1 - SOLICITAÇÃO DE NOVO ORÇAMENTO (PRESTADOR)</option>
                    <option value="aumento_km" {{ old('tipo_orcamento') == 'aumento_km' ? 'selected' : '' }}>TIPO 2 - SOLICITAÇÃO DE NOVO ORÇAMENTO (AUMENTO DE KM)</option>
                    <option value="proprio_nova_rota" {{ old('tipo_orcamento') == 'proprio_nova_rota' ? 'selected' : '' }}>TIPO 3 - SOLICITAÇÃO DE NOVO ORÇAMENTO (PRÓPRIO/NOVA ROTA)</option>
                </select>
            </div>
        </div>

        <!-- Campos Específicos por Tipo de Orçamento -->
        
        <!-- TIPO 1 - PRESTADOR -->
        <div id="campos_prestador" class="bg-blue-50 p-6 rounded-lg" style="display: none;">
            <h3 class="text-lg font-semibold text-blue-700 mb-4">TIPO 1 - SOLICITAÇÃO DE NOVO ORÇAMENTO (PRESTADOR)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label for="fornecedor_omie_id" class="block text-sm font-medium text-gray-700 mb-2">ID Fornecedor Omie *</label>
                    <input type="text" 
                           id="fornecedor_omie_id" 
                           name="fornecedor_omie_id" 
                           value="{{ old('fornecedor_omie_id') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Digite o ID do fornecedor...">
                </div>

                <div>
                    <label for="fornecedor_nome" class="block text-sm font-medium text-gray-700 mb-2">Nome do Fornecedor</label>
                    <div class="relative">
                        <input type="text" 
                               id="fornecedor_nome" 
                               name="fornecedor_nome" 
                               value="{{ old('fornecedor_nome') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100"
                               placeholder="Nome será preenchido automaticamente"
                               readonly>
                        <div id="fornecedor_loading" class="absolute right-3 top-2 text-gray-500 hidden">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </div>
                    <div id="fornecedor_error" class="text-red-500 text-sm mt-1 hidden">Fornecedor não encontrado</div>
                </div>

                <div>
                    <label for="valor_referencia" class="block text-sm font-medium text-gray-700 mb-2">Valor Referência</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">R$</span>
                        <input type="number" 
                               id="valor_referencia" 
                               name="valor_referencia" 
                               value="{{ old('valor_referencia') }}"
                               step="0.01"
                               min="0"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0,00">
                    </div>
                </div>

                <div>
                    <label for="qtd_dias" class="block text-sm font-medium text-gray-700 mb-2">Qtd Dias</label>
                    <input type="number" 
                           id="qtd_dias" 
                           name="qtd_dias" 
                           value="{{ old('qtd_dias') }}"
                           min="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="30">
                </div>

                <div>
                    <label for="custo_fornecedor" class="block text-sm font-medium text-gray-700 mb-2">Custo Fornecedor</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">R$</span>
                        <input type="number" 
                               id="custo_fornecedor" 
                               name="custo_fornecedor" 
                               value="{{ old('custo_fornecedor') }}"
                               step="0.01"
                               min="0"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0,00">
                    </div>
                </div>

                <div>
                    <label for="lucro_percentual" class="block text-sm font-medium text-gray-700 mb-2">Lucro (%)</label>
                    <div class="relative">
                        <input type="number" 
                               id="lucro_percentual" 
                               name="lucro_percentual" 
                               value="{{ old('lucro_percentual') }}"
                               step="0.01"
                               min="0"
                               max="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0,00">
                        <span class="absolute right-3 top-2 text-gray-500">%</span>
                    </div>
                </div>

                <div>
                    <label for="valor_lucro_prestador" class="block text-sm font-medium text-gray-700 mb-2">Valor do Lucro</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">R$</span>
                        <input type="number" 
                               id="valor_lucro_prestador" 
                               name="valor_lucro" 
                               value="{{ old('valor_lucro') }}"
                               step="0.01"
                               min="0"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100"
                               placeholder="0,00"
                               readonly>
                    </div>
                </div>

                <div>
                    <label for="impostos_percentual_prestador" class="block text-sm font-medium text-gray-700 mb-2">Impostos (%)</label>
                    <div class="relative">
                        <input type="number" 
                               id="impostos_percentual_prestador" 
                               name="impostos_percentual" 
                               value="{{ old('impostos_percentual') }}"
                               step="0.01"
                               min="0"
                               max="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0,00">
                        <span class="absolute right-3 top-2 text-gray-500">%</span>
                    </div>
                </div>

                <div>
                    <label for="valor_impostos_prestador" class="block text-sm font-medium text-gray-700 mb-2">Valor dos Impostos</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">R$</span>
                        <input type="number" 
                               id="valor_impostos_prestador" 
                               name="valor_impostos" 
                               value="{{ old('valor_impostos') }}"
                               step="0.01"
                               min="0"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100"
                               placeholder="0,00"
                               readonly>
                    </div>
                </div>

                <div>
                    <label for="valor_total_prestador" class="block text-sm font-medium text-gray-700 mb-2">Valor Total</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">R$</span>
                        <input type="number" 
                               id="valor_total_prestador" 
                               name="valor_total" 
                               value="{{ old('valor_total') }}"
                               step="0.01"
                               min="0"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100"
                               placeholder="0,00"
                               readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- TIPO 2 - AUMENTO DE KM -->
        <div id="campos_aumento_km" class="bg-green-50 p-6 rounded-lg" style="display: none;">
            <h3 class="text-lg font-semibold text-green-700 mb-4">TIPO 2 - SOLICITAÇÃO DE NOVO ORÇAMENTO (AUMENTO DE KM)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label for="km_dia" class="block text-sm font-medium text-gray-700 mb-2">KM Dia</label>
                    <input type="number" 
                           id="km_dia" 
                           name="km_dia" 
                           value="{{ old('km_dia') }}"
                           step="0.01"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="0,00">
                </div>

                <div>
                    <label for="qtd_dias_km" class="block text-sm font-medium text-gray-700 mb-2">Qtd Dias</label>
                    <input type="number" 
                           id="qtd_dias_km" 
                           name="qtd_dias" 
                           value="{{ old('qtd_dias') }}"
                           min="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="30">
                </div>

                <div>
                    <label for="km_total_mes" class="block text-sm font-medium text-gray-700 mb-2">KM Total (Mês)</label>
                    <input type="number" 
                           id="km_total_mes" 
                           name="km_total_mes" 
                           value="{{ old('km_total_mes') }}"
                           step="0.01"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100"
                           placeholder="0,00"
                           readonly>
                </div>

                <div>
                    <label for="combustivel_km_litro" class="block text-sm font-medium text-gray-700 mb-2">Combustível (KM/Litro)</label>
                    <input type="number" 
                           id="combustivel_km_litro" 
                           name="combustivel_km_litro" 
                           value="{{ old('combustivel_km_litro') }}"
                           step="0.01"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="0,00">
                </div>

                <div>
                    <label for="total_combustivel" class="block text-sm font-medium text-gray-700 mb-2">Total de Combustível</label>
                    <input type="number" 
                           id="total_combustivel" 
                           name="total_combustivel" 
                           value="{{ old('total_combustivel') }}"
                           step="0.01"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100"
                           placeholder="0,00"
                           readonly>
                </div>

                <div>
                    <label for="valor_combustivel" class="block text-sm font-medium text-gray-700 mb-2">Valor do Combustível</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">R$</span>
                        <input type="number" 
                               id="valor_combustivel" 
                               name="valor_combustivel" 
                               value="{{ old('valor_combustivel') }}"
                               step="0.01"
                               min="0"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0,00">
                    </div>
                </div>

                <div>
                    <label for="hora_extra" class="block text-sm font-medium text-gray-700 mb-2">Hora Extra</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">R$</span>
                        <input type="number" 
                               id="hora_extra" 
                               name="hora_extra" 
                               value="{{ old('hora_extra') }}"
                               step="0.01"
                               min="0"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0,00">
                    </div>
                </div>

                <div>
                    <label for="custo_total_combustivel_he" class="block text-sm font-medium text-gray-700 mb-2">Custo Total Combustível + HE</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">R$</span>
                        <input type="number" 
                               id="custo_total_combustivel_he" 
                               name="custo_total_combustivel_he" 
                               value="{{ old('custo_total_combustivel_he') }}"
                               step="0.01"
                               min="0"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100"
                               placeholder="0,00"
                               readonly>
                    </div>
                </div>

                <div>
                    <label for="lucro_percentual_km" class="block text-sm font-medium text-gray-700 mb-2">Lucro (%)</label>
                    <div class="relative">
                        <input type="number" 
                               id="lucro_percentual_km" 
                               name="lucro_percentual" 
                               value="{{ old('lucro_percentual') }}"
                               step="0.01"
                               min="0"
                               max="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0,00">
                        <span class="absolute right-3 top-2 text-gray-500">%</span>
                    </div>
                </div>

                <div>
                    <label for="valor_lucro_km" class="block text-sm font-medium text-gray-700 mb-2">Valor do Lucro</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">R$</span>
                        <input type="number" 
                               id="valor_lucro_km" 
                               name="valor_lucro" 
                               value="{{ old('valor_lucro') }}"
                               step="0.01"
                               min="0"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100"
                               placeholder="0,00"
                               readonly>
                    </div>
                </div>

                <div>
                    <label for="impostos_percentual_km" class="block text-sm font-medium text-gray-700 mb-2">Impostos (%)</label>
                    <div class="relative">
                        <input type="number" 
                               id="impostos_percentual_km" 
                               name="impostos_percentual" 
                               value="{{ old('impostos_percentual') }}"
                               step="0.01"
                               min="0"
                               max="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0,00">
                        <span class="absolute right-3 top-2 text-gray-500">%</span>
                    </div>
                </div>

                <div>
                    <label for="valor_impostos_km" class="block text-sm font-medium text-gray-700 mb-2">Valor dos Impostos</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">R$</span>
                        <input type="number" 
                               id="valor_impostos_km" 
                               name="valor_impostos" 
                               value="{{ old('valor_impostos') }}"
                               step="0.01"
                               min="0"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100"
                               placeholder="0,00"
                               readonly>
                    </div>
                </div>

                <div>
                    <label for="valor_total_km" class="block text-sm font-medium text-gray-700 mb-2">Valor Total</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">R$</span>
                        <input type="number" 
                               id="valor_total_km" 
                               name="valor_total" 
                               value="{{ old('valor_total') }}"
                               step="0.01"
                               min="0"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100"
                               placeholder="0,00"
                               readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- TIPO 3 - PRÓPRIO/NOVA ROTA -->
        <div id="campos_proprio_nova_rota" class="bg-yellow-50 p-6 rounded-lg" style="display: none;">
            <h3 class="text-lg font-semibold text-yellow-700 mb-4">TIPO 3 - SOLICITAÇÃO DE NOVO ORÇAMENTO (PRÓPRIO/NOVA ROTA)</h3>
            <div class="text-center py-8">
                <p class="text-gray-600 text-lg">Campos ainda não definidos para este tipo de orçamento.</p>
                <p class="text-gray-500 text-sm mt-2">Esta seção será implementada quando os campos forem especificados.</p>
            </div>
        </div>











        <!-- Botões de Ação -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.orcamentos.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition duration-200">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                <i class="fas fa-save mr-2"></i>Salvar Orçamento
            </button>
        </div>
    </form>
</div>

<script>
// Controle de exibição dos campos por tipo de orçamento
function toggleTipoOrcamentoFields() {
    const tipoOrcamento = document.getElementById('tipo_orcamento').value;
    
    // Ocultar todas as seções
    document.getElementById('campos_prestador').style.display = 'none';
    document.getElementById('campos_aumento_km').style.display = 'none';
    document.getElementById('campos_proprio_nova_rota').style.display = 'none';
    
    // Exibir a seção correspondente
    if (tipoOrcamento === 'prestador') {
        document.getElementById('campos_prestador').style.display = 'block';
    } else if (tipoOrcamento === 'aumento_km') {
        document.getElementById('campos_aumento_km').style.display = 'block';
    } else if (tipoOrcamento === 'proprio_nova_rota') {
        document.getElementById('campos_proprio_nova_rota').style.display = 'block';
    }
}

// Cálculos para PRESTADOR
function calculatePrestadorValues() {
    const custoFornecedor = parseFloat(document.getElementById('custo_fornecedor').value) || 0;
    const lucroPercentual = parseFloat(document.getElementById('lucro_percentual').value) || 0;
    const impostosPercentual = parseFloat(document.getElementById('impostos_percentual_prestador').value) || 0;
    
    // Calcular valor do lucro
    const valorLucro = custoFornecedor * (lucroPercentual / 100);
    document.getElementById('valor_lucro_prestador').value = valorLucro.toFixed(2);
    
    // Calcular subtotal (custo + lucro)
    const subtotal = custoFornecedor + valorLucro;
    
    // Calcular valor dos impostos
    const valorImpostos = subtotal * (impostosPercentual / 100);
    document.getElementById('valor_impostos_prestador').value = valorImpostos.toFixed(2);
    
    // Calcular valor total
    const valorTotal = subtotal + valorImpostos;
    document.getElementById('valor_total_prestador').value = valorTotal.toFixed(2);
}

// Cálculos para AUMENTO DE KM
function calculateAumentoKmValues() {
    const kmDia = parseFloat(document.getElementById('km_dia').value) || 0;
    const qtdDias = parseFloat(document.getElementById('qtd_dias_km').value) || 0;
    const combustivelKmLitro = parseFloat(document.getElementById('combustivel_km_litro').value) || 0;
    const valorCombustivel = parseFloat(document.getElementById('valor_combustivel').value) || 0;
    const horaExtra = parseFloat(document.getElementById('hora_extra').value) || 0;
    const lucroPercentual = parseFloat(document.getElementById('lucro_percentual_km').value) || 0;
    const impostosPercentual = parseFloat(document.getElementById('impostos_percentual_km').value) || 0;
    
    // Calcular KM total do mês
    const kmTotalMes = kmDia * qtdDias;
    document.getElementById('km_total_mes').value = kmTotalMes.toFixed(2);
    
    // Calcular total de combustível em litros
    const totalCombustivel = combustivelKmLitro > 0 ? kmTotalMes / combustivelKmLitro : 0;
    document.getElementById('total_combustivel').value = totalCombustivel.toFixed(2);
    
    // Calcular custo total combustível + HE
    const custoTotalCombustivelHe = (totalCombustivel * valorCombustivel) + horaExtra;
    document.getElementById('custo_total_combustivel_he').value = custoTotalCombustivelHe.toFixed(2);
    
    // Calcular valor do lucro
    const valorLucro = custoTotalCombustivelHe * (lucroPercentual / 100);
    document.getElementById('valor_lucro_km').value = valorLucro.toFixed(2);
    
    // Calcular subtotal (custo + lucro)
    const subtotal = custoTotalCombustivelHe + valorLucro;
    
    // Calcular valor dos impostos
    const valorImpostos = subtotal * (impostosPercentual / 100);
    document.getElementById('valor_impostos_km').value = valorImpostos.toFixed(2);
    
    // Calcular valor total
    const valorTotal = subtotal + valorImpostos;
    document.getElementById('valor_total_km').value = valorTotal.toFixed(2);
}

// Funcionalidade de busca de clientes OMIE
let clienteSearchTimeout;
const clienteSearchInput = document.getElementById('cliente_omie_search');
const clienteDropdown = document.getElementById('cliente_dropdown');
const clienteLoading = document.getElementById('cliente_loading');
const clienteResults = document.getElementById('cliente_results');
const clienteNoResults = document.getElementById('cliente_no_results');
const clienteOmieId = document.getElementById('cliente_omie_id');
const clienteNome = document.getElementById('cliente_nome');

if (clienteSearchInput) {
    clienteSearchInput.addEventListener('input', function() {
        const searchTerm = this.value.trim();
        
        // Limpar timeout anterior
        clearTimeout(clienteSearchTimeout);
        
        if (searchTerm.length < 2) {
            hideClienteDropdown();
            return;
        }
        
        // Aguardar 300ms antes de fazer a busca
        clienteSearchTimeout = setTimeout(() => {
            searchClientes(searchTerm);
        }, 300);
    });
    
    // Esconder dropdown quando clicar fora
    document.addEventListener('click', function(e) {
        if (!clienteSearchInput.contains(e.target) && !clienteDropdown.contains(e.target)) {
            hideClienteDropdown();
        }
    });
}

function searchClientes(searchTerm) {
    showClienteLoading();
    
    fetch(`/api/omie/clientes/search?search=${encodeURIComponent(searchTerm)}`)
        .then(response => response.json())
        .then(data => {
            hideClienteLoading();
            
            if (data.success && data.data.length > 0) {
                displayClienteResults(data.data);
            } else {
                showClienteNoResults();
            }
        })
        .catch(error => {
            console.error('Erro ao buscar clientes:', error);
            hideClienteLoading();
            showClienteNoResults();
        });
}

function displayClienteResults(clientes) {
    clienteResults.innerHTML = '';
    
    clientes.forEach(cliente => {
        const item = document.createElement('div');
        item.className = 'p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-200';
        item.innerHTML = `
            <div class="font-medium text-gray-900">${cliente.nome}</div>
            <div class="text-sm text-gray-500">${cliente.documento || 'Sem documento'}</div>
            <div class="text-xs text-gray-400">${cliente.cidade || ''} ${cliente.estado || ''}</div>
        `;
        
        item.addEventListener('click', function() {
            selectCliente(cliente);
        });
        
        clienteResults.appendChild(item);
    });
    
    showClienteDropdown();
    clienteNoResults.classList.add('hidden');
}

function selectCliente(cliente) {
    clienteSearchInput.value = cliente.nome;
    clienteOmieId.value = cliente.omie_id;
    clienteNome.value = cliente.nome;
    hideClienteDropdown();
}

function showClienteLoading() {
    clienteLoading.classList.remove('hidden');
    clienteResults.innerHTML = '';
    clienteNoResults.classList.add('hidden');
    showClienteDropdown();
}

function hideClienteLoading() {
    clienteLoading.classList.add('hidden');
}

function showClienteNoResults() {
    clienteNoResults.classList.remove('hidden');
    clienteResults.innerHTML = '';
    showClienteDropdown();
}

function showClienteDropdown() {
    clienteDropdown.classList.remove('hidden');
}

function hideClienteDropdown() {
    clienteDropdown.classList.add('hidden');
    clienteLoading.classList.add('hidden');
    clienteNoResults.classList.add('hidden');
}

// Busca de fornecedor por ID
const fornecedorIdInput = document.getElementById('fornecedor_omie_id');
const fornecedorNomeInput = document.getElementById('fornecedor_nome');
const fornecedorLoading = document.getElementById('fornecedor_loading');
const fornecedorError = document.getElementById('fornecedor_error');

if (fornecedorIdInput) {
    let fornecedorTimeout;
    
    fornecedorIdInput.addEventListener('input', function() {
        const fornecedorId = this.value.trim();
        
        clearTimeout(fornecedorTimeout);
        
        // Limpar nome e erro
        fornecedorNomeInput.value = '';
        fornecedorError.classList.add('hidden');
        
        if (fornecedorId.length === 0) {
            return;
        }
        
        // Aguardar 500ms antes de fazer a busca
        fornecedorTimeout = setTimeout(() => {
            buscarFornecedorPorId(fornecedorId);
        }, 500);
    });
}

function buscarFornecedorPorId(fornecedorId) {
    fornecedorLoading.classList.remove('hidden');
    fornecedorError.classList.add('hidden');
    
    fetch(`/api/omie/clientes/${fornecedorId}`)
        .then(response => response.json())
        .then(data => {
            fornecedorLoading.classList.add('hidden');
            
            if (data.success && data.data) {
                fornecedorNomeInput.value = data.data.nome;
            } else {
                fornecedorError.classList.remove('hidden');
                fornecedorNomeInput.value = '';
            }
        })
        .catch(error => {
            fornecedorLoading.classList.add('hidden');
            fornecedorError.classList.remove('hidden');
            fornecedorNomeInput.value = '';
            console.error('Erro ao buscar fornecedor:', error);
        });
}

// Calcular valor final automaticamente
document.addEventListener('DOMContentLoaded', function() {
    // Controle de tipo de orçamento
    document.getElementById('tipo_orcamento').addEventListener('change', toggleTipoOrcamentoFields);
    
    // Event listeners para cálculos do PRESTADOR
    ['custo_fornecedor', 'lucro_percentual', 'impostos_percentual_prestador'].forEach(function(id) {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', calculatePrestadorValues);
        }
    });
    
    // Event listeners para cálculos do AUMENTO DE KM
    ['km_dia', 'qtd_dias_km', 'combustivel_km_litro', 'valor_combustivel', 'hora_extra', 'lucro_percentual_km', 'impostos_percentual_km'].forEach(function(id) {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', calculateAumentoKmValues);
        }
    });
});
</script>
@endsection