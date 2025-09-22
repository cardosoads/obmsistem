@extends('layouts.admin')

@section('title', 'Editar Frota')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Editar Frota #{{ $frota->id }}</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.frotas.show', $frota->id) }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-eye mr-2"></i>Visualizar
            </a>
            <a href="{{ route('admin.frotas.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <form action="{{ route('admin.frotas.update', $frota->id) }}" method="POST" class="space-y-6" x-data="frotaForm()">
        @csrf
        @method('PUT')
        
        <!-- Dados Básicos -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Dados Básicos</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="tipo_veiculo_id" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Veículo *</label>
                    <select id="tipo_veiculo_id" 
                            name="tipo_veiculo_id" 
                            x-model="form.tipo_veiculo_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('tipo_veiculo_id') border-red-500 @enderror" 
                            required>
                        <option value="">Selecione o tipo de veículo</option>
                        @foreach($tiposVeiculos as $tipo)
                            <option value="{{ $tipo->id }}" {{ old('tipo_veiculo_id', $frota->tipo_veiculo_id) == $tipo->id ? 'selected' : '' }}>
                                {{ $tipo->codigo }} - {{ $tipo->descricao }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo_veiculo_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="active" 
                            name="active" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('active') border-red-500 @enderror">
                        <option value="1" {{ old('active', $frota->active) == '1' ? 'selected' : '' }}>Ativo</option>
                        <option value="0" {{ old('active', $frota->active) == '0' ? 'selected' : '' }}>Inativo</option>
                    </select>
                    @error('active')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Valores FIPE -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Valores FIPE</h3>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label for="fipe" class="block text-sm font-medium text-gray-700 mb-2">Valor FIPE *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="text" 
                               id="fipe" 
                               name="fipe" 
                               value="{{ old('fipe') ? number_format(old('fipe'), 2, ',', '.') : number_format($frota->fipe, 2, ',', '.') }}"
                               x-model="form.fipe"
                               @input="updateFipeValue(); $nextTick(() => calcularCustos())"
                               @keyup="updateFipeValue(); $nextTick(() => calcularCustos())"
                               placeholder="0,00"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 money-mask @error('fipe') border-red-500 @enderror" 
                               required>
                    </div>
                    @error('fipe')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Custos Operacionais -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Custos Operacionais</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="percentual_fipe" class="block text-sm font-medium text-gray-700 mb-2">Percentual FIPE (%)</label>
                    <input type="number" 
                           id="percentual_fipe" 
                           name="percentual_fipe" 
                           value="{{ old('percentual_fipe', $frota->percentual_fipe) }}"
                           step="0.1"
                           min="0"
                           max="100"
                           x-model="form.percentual_fipe"
                           @input="calcularCustos()"
                           placeholder="80.0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('percentual_fipe') border-red-500 @enderror">
                    @error('percentual_fipe')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="aluguel_carro" class="block text-sm font-medium text-gray-700 mb-2">Aluguel do Carro</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="text" 
                               id="aluguel_carro" 
                               name="aluguel_carro" 
                               value="{{ old('aluguel_carro') ? number_format(old('aluguel_carro'), 2, ',', '.') : number_format($frota->aluguel_carro, 2, ',', '.') }}"
                               x-model="form.aluguel_carro"
                               placeholder="0,00"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 money-mask @error('aluguel_carro') border-red-500 @enderror" 
                               readonly>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Calculado automaticamente (Valor FIPE × Percentual FIPE)</p>
                    @error('aluguel_carro')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="rastreador" class="block text-sm font-medium text-gray-700 mb-2">Rastreador</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="text" 
                               id="rastreador" 
                               name="rastreador" 
                               value="{{ old('rastreador') ? number_format(old('rastreador'), 2, ',', '.') : number_format($frota->rastreador, 2, ',', '.') }}"
                               x-model="form.rastreador"
                               @input="calcularCustos()"
                               placeholder="0,00"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 money-mask @error('rastreador') border-red-500 @enderror">
                    </div>
                    @error('rastreador')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Provisões -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Provisões</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="percentual_provisoes_avarias" class="block text-sm font-medium text-gray-700 mb-2">Percentual Provisões Avarias (%)</label>
                    <input type="number" 
                           id="percentual_provisoes_avarias" 
                           name="percentual_provisoes_avarias" 
                           value="{{ old('percentual_provisoes_avarias', $frota->percentual_provisoes_avarias ?? 0) }}"
                           step="0.1"
                           min="0"
                           max="100"
                           x-model="form.percentual_provisoes_avarias"
                           @input="calcularCustos()"
                           placeholder="0.0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('percentual_provisoes_avarias') border-red-500 @enderror">
                    @error('percentual_provisoes_avarias')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="provisoes_avarias" class="block text-sm font-medium text-gray-700 mb-2">Provisões para Avarias</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="text" 
                               id="provisoes_avarias" 
                               name="provisoes_avarias" 
                               value="{{ old('provisoes_avarias') ? number_format(old('provisoes_avarias'), 2, ',', '.') : number_format($frota->provisoes_avarias, 2, ',', '.') }}"
                               x-model="form.provisoes_avarias"
                               placeholder="0,00"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 money-mask @error('provisoes_avarias') border-red-500 @enderror" 
                               readonly>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Calculado automaticamente (Aluguel × Percentual)</p>
                    @error('provisoes_avarias')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="percentual_provisao_desmobilizacao" class="block text-sm font-medium text-gray-700 mb-2">Percentual Provisão Desmobilização (%)</label>
                    <input type="number" 
                           id="percentual_provisao_desmobilizacao" 
                           name="percentual_provisao_desmobilizacao" 
                           value="{{ old('percentual_provisao_desmobilizacao', $frota->percentual_provisao_desmobilizacao ?? 0) }}"
                           step="0.1"
                           min="0"
                           max="100"
                           x-model="form.percentual_provisao_desmobilizacao"
                           @input="calcularCustos()"
                           placeholder="0.0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('percentual_provisao_desmobilizacao') border-red-500 @enderror">
                    @error('percentual_provisao_desmobilizacao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="provisao_desmobilizacao" class="block text-sm font-medium text-gray-700 mb-2">Provisão para Desmobilização</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="text" 
                               id="provisao_desmobilizacao" 
                               name="provisao_desmobilizacao" 
                               value="{{ old('provisao_desmobilizacao') ? number_format(old('provisao_desmobilizacao'), 2, ',', '.') : number_format($frota->provisao_desmobilizacao, 2, ',', '.') }}"
                               x-model="form.provisao_desmobilizacao"
                               placeholder="0,00"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 money-mask @error('provisao_desmobilizacao') border-red-500 @enderror" 
                               readonly>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Calculado automaticamente (Provisões Avarias × Percentual)</p>
                    @error('provisao_desmobilizacao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="percentual_provisao_rac" class="block text-sm font-medium text-gray-700 mb-2">Percentual Provisão RAC (%)</label>
                    <input type="number" 
                           id="percentual_provisao_rac" 
                           name="percentual_provisao_rac" 
                           value="{{ old('percentual_provisao_rac', $frota->percentual_provisao_rac ?? 0) }}"
                           step="0.1"
                           min="0"
                           max="100"
                           x-model="form.percentual_provisao_rac"
                           @input="calcularCustos()"
                           placeholder="0.0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('percentual_provisao_rac') border-red-500 @enderror">
                    @error('percentual_provisao_rac')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="provisao_diaria_rac" class="block text-sm font-medium text-gray-700 mb-2">Provisão Diária RAC</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="text" 
                               id="provisao_diaria_rac" 
                               name="provisao_diaria_rac" 
                               value="{{ old('provisao_diaria_rac') ? number_format(old('provisao_diaria_rac'), 2, ',', '.') : number_format($frota->provisao_diaria_rac, 2, ',', '.') }}"
                               x-model="form.provisao_diaria_rac"
                               placeholder="0,00"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 money-mask @error('provisao_diaria_rac') border-red-500 @enderror" 
                               readonly>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Calculado automaticamente (Aluguel × Percentual)</p>
                    @error('provisao_diaria_rac')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Resumo de Custos -->
        <div class="bg-blue-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-blue-700 mb-4">Resumo de Custos</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <p class="text-sm text-blue-600">Valor FIPE Digitado</p>
                    <p class="text-xl font-bold text-blue-800" x-text="formatFipeValue()">R$ {{ number_format($frota->fipe, 2, ',', '.') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-blue-600">Total Provisões</p>
                    <p class="text-xl font-bold text-blue-800" x-text="formatCurrency(parseFloat(form.provisoes_avarias || 0) + parseFloat(form.provisao_desmobilizacao || 0) + parseFloat(form.provisao_diaria_rac || 0))">R$ {{ number_format(($frota->provisoes_avarias + $frota->provisao_desmobilizacao + $frota->provisao_diaria_rac), 2, ',', '.') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-blue-600">Custo Total Atual</p>
                    <p class="text-xl font-bold text-gray-700">R$ {{ number_format($frota->custo_total, 2, ',', '.') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-blue-600">Custo Total Estimado</p>
                    <p class="text-2xl font-bold text-blue-900" x-text="formatCurrency(calcularCustoTotal())">R$ {{ number_format($frota->custo_total, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Informações de Auditoria -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Informações de Auditoria</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                <div>
                    <strong>Criado em:</strong> {{ $frota->created_at->format('d/m/Y H:i:s') }}
                </div>
                <div>
                    <strong>Última atualização:</strong> {{ $frota->updated_at->format('d/m/Y H:i:s') }}
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.frotas.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg transition duration-200">
                <i class="fas fa-save mr-2"></i>Atualizar
            </button>
        </div>
    </form>
</div>

<!-- Informações Adicionais -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="fas fa-info-circle text-blue-400 text-lg"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">Informações Importantes</h3>
            <div class="mt-2 text-sm text-blue-700">
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Valor FIPE:</strong> Valor de referência do veículo na tabela FIPE</li>
                    <li><strong>Percentual FIPE:</strong> Percentual aplicado sobre o valor FIPE para depreciação</li>
                    <li><strong>Aluguel:</strong> Valor mensal do aluguel do veículo</li>
                    <li><strong>Provisões:</strong> Valores reservados para manutenção e eventualidades</li>
                    <li><strong>Custo Total:</strong> Será recalculado automaticamente ao salvar</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/money-mask.js') }}"></script>
<script>
function frotaForm() {
    return {
        form: {
            tipo_veiculo_id: '{{ old("tipo_veiculo_id", $frota->tipo_veiculo_id) }}',
            fipe: '{{ old('fipe') ? number_format(old('fipe'), 2, ',', '.') : number_format($frota->fipe, 2, ',', '.') }}',
            percentual_fipe: {{ old('percentual_fipe', $frota->percentual_fipe) }},
            aluguel_carro: '{{ old('aluguel_carro') ? number_format(old('aluguel_carro'), 2, ',', '.') : number_format($frota->aluguel_carro, 2, ',', '.') }}',
            rastreador: '{{ old('rastreador') ? number_format(old('rastreador'), 2, ',', '.') : number_format($frota->rastreador, 2, ',', '.') }}',
            percentual_provisoes_avarias: {{ old('percentual_provisoes_avarias', $frota->percentual_provisoes_avarias ?? 0) }},
            provisoes_avarias: '{{ old('provisoes_avarias') ? number_format(old('provisoes_avarias'), 2, ',', '.') : number_format($frota->provisoes_avarias, 2, ',', '.') }}',
            percentual_provisao_desmobilizacao: {{ old('percentual_provisao_desmobilizacao', $frota->percentual_provisao_desmobilizacao ?? 0) }},
            provisao_desmobilizacao: '{{ old('provisao_desmobilizacao') ? number_format(old('provisao_desmobilizacao'), 2, ',', '.') : number_format($frota->provisao_desmobilizacao, 2, ',', '.') }}',
            percentual_provisao_rac: {{ old('percentual_provisao_rac', $frota->percentual_provisao_rac ?? 0) }},
            provisao_diaria_rac: '{{ old('provisao_diaria_rac') ? number_format(old('provisao_diaria_rac'), 2, ',', '.') : number_format($frota->provisao_diaria_rac, 2, ',', '.') }}'
        },
        
        // Valor numérico reativo do FIPE
        fipeNumericValue: {{ $frota->fipe }},
        
        // Função para formatar diretamente o valor FIPE digitado como moeda
        formatFipeValue() {
            // Pega o valor diretamente do campo DOM para evitar conflito com a máscara
            const fipeField = document.getElementById('fipe');
            if (fipeField && fipeField.value) {
                const fipeValue = fipeField.value.trim();
                if (fipeValue && fipeValue !== '0,00' && fipeValue !== '') {
                    // Se já tem R$ no início, retorna como está
                    if (fipeValue.startsWith('R$')) {
                        return fipeValue;
                    }
                    // Se não tem R$, adiciona sem alterar o resto
                    return 'R$ ' + fipeValue;
                }
            }
            return 'R$ 0,00';
        },
        
        // Função para atualizar o valor numérico do FIPE
        updateFipeValue() {
            const fipeValue = this.form.fipe || '0,00';
            // Remove R$ e espaços, depois remove pontos (separadores de milhares) e substitui vírgula por ponto
            const cleanValue = fipeValue
                .replace(/[R$\s]/g, '')
                .replace(/\./g, '') // Remove separadores de milhares
                .replace(',', '.'); // Substitui vírgula decimal por ponto
            
            this.fipeNumericValue = parseFloat(cleanValue) || 0;
        },
        
        // Função local para converter valor monetário em número
        getNumericValue(fieldId) {
            const field = document.getElementById(fieldId);
            if (!field || !field.value) return 0;
            
            // Remove formatação monetária e converte para número
            const cleanValue = field.value
                .replace(/[R$\s]/g, '')
                .replace(/\./g, '') // Remove separadores de milhares
                .replace(',', '.'); // Substitui vírgula decimal por ponto
            
            return parseFloat(cleanValue) || 0;
        },
        
        // Função local para formatar valor como moeda
        formatMoneyValue(value) {
            if (!value || isNaN(value)) return '0,00';
            
            return value.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        },
        
        calcularCustos() {
            // Obter valores usando funções locais
            const fipe = this.getNumericValue('fipe');
            const percentualFipe = parseFloat(this.form.percentual_fipe || 0);
            
            // 1. Calcular aluguel: FIPE * Percentual / 100
            const aluguelCalculado = (fipe * percentualFipe) / 100;
            
            // Formatar e atualizar o campo aluguel
            const aluguelFormatado = this.formatMoneyValue(aluguelCalculado);
            this.form.aluguel_carro = aluguelFormatado;
            
            // Atualizar o campo DOM diretamente
            const aluguelField = document.getElementById('aluguel_carro');
            if (aluguelField) {
                aluguelField.value = aluguelFormatado;
                // Disparar evento para sincronizar com Alpine.js
                aluguelField.dispatchEvent(new Event('input'));
            }
            
            // 2. Calcular provisões para avarias: Aluguel * Percentual / 100
            const percentualProvisoes = parseFloat(this.form.percentual_provisoes_avarias || 0);
            const provisoesCalculadas = (aluguelCalculado * percentualProvisoes) / 100;
            
            // Formatar e atualizar o campo provisões para avarias
            const provisoesFormatadas = this.formatMoneyValue(provisoesCalculadas);
            this.form.provisoes_avarias = provisoesFormatadas;
            
            // Atualizar o campo DOM diretamente
            const provisoesField = document.getElementById('provisoes_avarias');
            if (provisoesField) {
                provisoesField.value = provisoesFormatadas;
                // Disparar evento para sincronizar com Alpine.js
                provisoesField.dispatchEvent(new Event('input'));
            }
            
            // 3. Calcular provisão para desmobilização: Provisões Avarias * Percentual / 100
            const percentualDesmob = parseFloat(this.form.percentual_provisao_desmobilizacao || 0);
            const desmobCalculada = (provisoesCalculadas * percentualDesmob) / 100;
            
            // Formatar e atualizar o campo provisão desmobilização
            const desmobFormatada = this.formatMoneyValue(desmobCalculada);
            this.form.provisao_desmobilizacao = desmobFormatada;
            
            // Atualizar o campo DOM diretamente
            const desmobField = document.getElementById('provisao_desmobilizacao');
            if (desmobField) {
                desmobField.value = desmobFormatada;
                // Disparar evento para sincronizar com Alpine.js
                desmobField.dispatchEvent(new Event('input'));
            }
            
            // 4. Calcular provisão diária RAC: Aluguel * Percentual / 100
            const percentualRac = parseFloat(this.form.percentual_provisao_rac || 0);
            const racCalculada = (aluguelCalculado * percentualRac) / 100;
            
            // Formatar e atualizar o campo provisão RAC
            const racFormatada = this.formatMoneyValue(racCalculada);
            this.form.provisao_diaria_rac = racFormatada;
            
            // Atualizar o campo DOM diretamente
            const racField = document.getElementById('provisao_diaria_rac');
            if (racField) {
                racField.value = racFormatada;
                // Disparar evento para sincronizar com Alpine.js
                racField.dispatchEvent(new Event('input'));
            }
        },
        
        calcularCustoTotal() {
            const fipe = this.getNumericValue('fipe');
            const percentualFipe = parseFloat(this.form.percentual_fipe || 0);
            const fipeCalculado = (fipe * percentualFipe) / 100;
            
            const aluguel = this.getNumericValue('aluguel_carro');
            const rastreador = this.getNumericValue('rastreador');
            const provisoesAvarias = this.getNumericValue('provisoes_avarias');
            const provisaoDesmob = this.getNumericValue('provisao_desmobilizacao');
            const provisaoRac = this.getNumericValue('provisao_diaria_rac');
            
            return fipeCalculado + aluguel + rastreador + provisoesAvarias + provisaoDesmob + provisaoRac;
        },
        
        formatCurrency(value) {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(value || 0);
        },
        
        // Inicialização do componente
        init() {
            // Inicializar valor numérico do FIPE
            this.updateFipeValue();
            
            // Aguardar um momento para garantir que o DOM esteja pronto
            this.$nextTick(() => {
                // Calcular valores iniciais
                this.calcularCustos();
            });
            
            // Watcher para o campo FIPE
            this.$watch('form.fipe', () => {
                this.updateFipeValue();
                this.$nextTick(() => {
                    this.calcularCustos();
                });
            });
            
            // Watcher para percentual FIPE
            this.$watch('form.percentual_fipe', () => {
                this.$nextTick(() => {
                    this.calcularCustos();
                });
            });
        }
    }
}
</script>
@endsection