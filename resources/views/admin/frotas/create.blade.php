@extends('layouts.admin')

@section('title', 'Nova Frota')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Nova Frota</h1>
        <a href="{{ route('admin.frotas.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    <form action="{{ route('admin.frotas.store') }}" method="POST" class="space-y-6" x-data="frotaForm()">
        @csrf
        
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
                            <option value="{{ $tipo->id }}" {{ old('tipo_veiculo_id', request('tipo_veiculo_id')) == $tipo->id ? 'selected' : '' }}>
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
                        <option value="1" {{ old('active', '1') == '1' ? 'selected' : '' }}>Ativo</option>
                        <option value="0" {{ old('active') == '0' ? 'selected' : '' }}>Inativo</option>
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="fipe" class="block text-sm font-medium text-gray-700 mb-2">Valor FIPE *</label>
                    <input type="number" 
                           id="fipe" 
                           name="fipe" 
                           value="{{ old('fipe') }}"
                           step="0.01"
                           min="0"
                           x-model="form.fipe"
                           @input="calcularCustos()"
                           placeholder="0,00"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('fipe') border-red-500 @enderror" 
                           required>
                    @error('fipe')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="percentual_fipe" class="block text-sm font-medium text-gray-700 mb-2">Percentual FIPE (%)</label>
                    <input type="number" 
                           id="percentual_fipe" 
                           name="percentual_fipe" 
                           value="{{ old('percentual_fipe', 80) }}"
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
            </div>
        </div>

        <!-- Custos Operacionais -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Custos Operacionais</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="aluguel_carro" class="block text-sm font-medium text-gray-700 mb-2">Aluguel do Carro *</label>
                    <input type="number" 
                           id="aluguel_carro" 
                           name="aluguel_carro" 
                           value="{{ old('aluguel_carro') }}"
                           step="0.01"
                           min="0"
                           x-model="form.aluguel_carro"
                           @input="calcularCustos()"
                           placeholder="0,00"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('aluguel_carro') border-red-500 @enderror" 
                           required>
                    @error('aluguel_carro')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="rastreador" class="block text-sm font-medium text-gray-700 mb-2">Rastreador</label>
                    <input type="number" 
                           id="rastreador" 
                           name="rastreador" 
                           value="{{ old('rastreador', 0) }}"
                           step="0.01"
                           min="0"
                           x-model="form.rastreador"
                           @input="calcularCustos()"
                           placeholder="0,00"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('rastreador') border-red-500 @enderror">
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
                    <label for="provisoes_avarias" class="block text-sm font-medium text-gray-700 mb-2">Provisões para Avarias</label>
                    <input type="number" 
                           id="provisoes_avarias" 
                           name="provisoes_avarias" 
                           value="{{ old('provisoes_avarias', 0) }}"
                           step="0.01"
                           min="0"
                           x-model="form.provisoes_avarias"
                           @input="calcularCustos()"
                           placeholder="0,00"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('provisoes_avarias') border-red-500 @enderror">
                    @error('provisoes_avarias')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="provisao_desmobilizacao" class="block text-sm font-medium text-gray-700 mb-2">Provisão para Desmobilização</label>
                    <input type="number" 
                           id="provisao_desmobilizacao" 
                           name="provisao_desmobilizacao" 
                           value="{{ old('provisao_desmobilizacao', 0) }}"
                           step="0.01"
                           min="0"
                           x-model="form.provisao_desmobilizacao"
                           @input="calcularCustos()"
                           placeholder="0,00"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('provisao_desmobilizacao') border-red-500 @enderror">
                    @error('provisao_desmobilizacao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="provisao_diaria_rac" class="block text-sm font-medium text-gray-700 mb-2">Provisão Diária RAC</label>
                    <input type="number" 
                           id="provisao_diaria_rac" 
                           name="provisao_diaria_rac" 
                           value="{{ old('provisao_diaria_rac', 0) }}"
                           step="0.01"
                           min="0"
                           x-model="form.provisao_diaria_rac"
                           @input="calcularCustos()"
                           placeholder="0,00"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('provisao_diaria_rac') border-red-500 @enderror">
                    @error('provisao_diaria_rac')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Resumo de Custos -->
        <div class="bg-blue-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-blue-700 mb-4">Resumo de Custos</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <p class="text-sm text-blue-600">Valor FIPE Calculado</p>
                    <p class="text-2xl font-bold text-blue-800" x-text="formatCurrency(form.fipe * form.percentual_fipe / 100)">R$ 0,00</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-blue-600">Total Provisões</p>
                    <p class="text-2xl font-bold text-blue-800" x-text="formatCurrency(parseFloat(form.provisoes_avarias || 0) + parseFloat(form.provisao_desmobilizacao || 0) + parseFloat(form.provisao_diaria_rac || 0))">R$ 0,00</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-blue-600">Custo Total Estimado</p>
                    <p class="text-3xl font-bold text-blue-900" x-text="formatCurrency(calcularCustoTotal())">R$ 0,00</p>
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
                <i class="fas fa-save mr-2"></i>Salvar
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
                    <li><strong>Custo Total:</strong> Será calculado automaticamente com base nos valores informados</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function frotaForm() {
    return {
        form: {
            tipo_veiculo_id: '{{ old("tipo_veiculo_id", request("tipo_veiculo_id")) }}',
            fipe: {{ old('fipe', 0) }},
            percentual_fipe: {{ old('percentual_fipe', 80) }},
            aluguel_carro: {{ old('aluguel_carro', 0) }},
            rastreador: {{ old('rastreador', 0) }},
            provisoes_avarias: {{ old('provisoes_avarias', 0) }},
            provisao_desmobilizacao: {{ old('provisao_desmobilizacao', 0) }},
            provisao_diaria_rac: {{ old('provisao_diaria_rac', 0) }}
        },
        
        calcularCustos() {
            // Função para recalcular custos em tempo real
        },
        
        calcularCustoTotal() {
            const fipeCalculado = (parseFloat(this.form.fipe || 0) * parseFloat(this.form.percentual_fipe || 0)) / 100;
            const aluguel = parseFloat(this.form.aluguel_carro || 0);
            const rastreador = parseFloat(this.form.rastreador || 0);
            const provisoes = parseFloat(this.form.provisoes_avarias || 0) + 
                            parseFloat(this.form.provisao_desmobilizacao || 0) + 
                            parseFloat(this.form.provisao_diaria_rac || 0);
            
            return fipeCalculado + aluguel + rastreador + provisoes;
        },
        
        formatCurrency(value) {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(value || 0);
        }
    }
}
</script>
@endsection