@extends('layouts.admin')

@section('title', 'Editar Funcionário')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Editar Funcionário</h1>
        <a href="{{ route('admin.recursos-humanos.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    <form action="{{ route('admin.recursos-humanos.update', $recursoHumano->id) }}" method="POST" class="space-y-6" x-data="rhForm()">
        @csrf
        @method('PUT')
        
        <!-- Dados Básicos -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Dados Básicos</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="tipo_contratacao" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Contratação *</label>
                    <select id="tipo_contratacao" 
                            name="tipo_contratacao" 
                            x-model="form.tipo_contratacao"
                            @change="calcularCustos()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('tipo_contratacao') border-red-500 @enderror" 
                            required>
                        <option value="">Selecione o tipo</option>
                        <option value="CLT" {{ old('tipo_contratacao', $recursoHumano->tipo_contratacao) == 'CLT' ? 'selected' : '' }}>CLT</option>
                        <option value="PJ" {{ old('tipo_contratacao', $recursoHumano->tipo_contratacao) == 'PJ' ? 'selected' : '' }}>PJ</option>
                        <option value="Terceirizado" {{ old('tipo_contratacao', $recursoHumano->tipo_contratacao) == 'Terceirizado' ? 'selected' : '' }}>Terceirizado</option>
                        <option value="Temporário" {{ old('tipo_contratacao', $recursoHumano->tipo_contratacao) == 'Temporário' ? 'selected' : '' }}>Temporário</option>
                        <option value="Estagiário" {{ old('tipo_contratacao', $recursoHumano->tipo_contratacao) == 'Estagiário' ? 'selected' : '' }}>Estagiário</option>
                    </select>
                    @error('tipo_contratacao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="cargo" class="block text-sm font-medium text-gray-700 mb-2">Cargo *</label>
                    <input type="text" 
                           id="cargo" 
                           name="cargo" 
                           value="{{ old('cargo', $recursoHumano->cargo) }}"
                           x-model="form.cargo"
                           placeholder="Ex: Motorista, Supervisor, etc."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('cargo') border-red-500 @enderror" 
                           required>
                    @error('cargo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="base_id" class="block text-sm font-medium text-gray-700 mb-2">Base</label>
                    <select id="base_id" 
                            name="base_id" 
                            x-model="form.base_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('base_id') border-red-500 @enderror">
                        <option value="">Selecione uma base</option>
                        @foreach($bases as $base)
                            <option value="{{ $base->id }}" {{ old('base_id', $recursoHumano->base_id) == $base->id ? 'selected' : '' }}>
                                {{ $base->name }} - {{ $base->uf }}
                            </option>
                        @endforeach
                    </select>
                    @error('base_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Valores Salariais -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Valores Salariais</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="salario_base" class="block text-sm font-medium text-gray-700 mb-2">Salário Base /mês *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="text" 
                               id="salario_base" 
                               name="salario_base" 
                               value="{{ old('salario_base', $recursoHumano->salario_base ? number_format($recursoHumano->salario_base, 2, ',', '.') : '0,00') }}"
                               x-model="form.salario_base"
                               @input="calcularCustos()"
                               placeholder="0,00"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 money-mask @error('salario_base') border-red-500 @enderror" 
                               required>
                    </div>
                    @error('salario_base')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="insalubridade" class="block text-sm font-medium text-gray-700 mb-2">Insalubridade /mês</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="text" 
                               id="insalubridade" 
                               name="insalubridade" 
                               value="{{ old('insalubridade', $recursoHumano->insalubridade ? number_format($recursoHumano->insalubridade, 2, ',', '.') : '0,00') }}"
                               x-model="form.insalubridade"
                               @input="calcularCustos()"
                               placeholder="0,00"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 money-mask @error('insalubridade') border-red-500 @enderror">
                    </div>
                    @error('insalubridade')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="periculosidade" class="block text-sm font-medium text-gray-700 mb-2">Periculosidade /mês</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="text" 
                               id="periculosidade" 
                               name="periculosidade" 
                               value="{{ old('periculosidade', $recursoHumano->periculosidade ? number_format($recursoHumano->periculosidade, 2, ',', '.') : '0,00') }}"
                               x-model="form.periculosidade"
                               @input="calcularCustos()"
                               placeholder="0,00"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 money-mask @error('periculosidade') border-red-500 @enderror">
                    </div>
                    @error('periculosidade')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="horas_extras" class="block text-sm font-medium text-gray-700 mb-2">Horas Extras /mês</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="text" 
                               id="horas_extras" 
                               name="horas_extras" 
                               value="{{ old('horas_extras', $recursoHumano->horas_extras ? number_format($recursoHumano->horas_extras, 2, ',', '.') : '0,00') }}"
                               x-model="form.horas_extras"
                               @input="calcularCustos()"
                               placeholder="0,00"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 money-mask @error('horas_extras') border-red-500 @enderror">
                    </div>
                    @error('horas_extras')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="adicional_noturno" class="block text-sm font-medium text-gray-700 mb-2">Adicional Noturno /mês</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="text" 
                               id="adicional_noturno" 
                               name="adicional_noturno" 
                               value="{{ old('adicional_noturno', $recursoHumano->adicional_noturno ? number_format($recursoHumano->adicional_noturno, 2, ',', '.') : '0,00') }}"
                               x-model="form.adicional_noturno"
                               @input="calcularCustos()"
                               placeholder="0,00"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 money-mask @error('adicional_noturno') border-red-500 @enderror">
                    </div>
                    @error('adicional_noturno')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="extras" class="block text-sm font-medium text-gray-700 mb-2">Extras /mês</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="text" 
                               id="extras" 
                               name="extras" 
                               value="{{ old('extras', $recursoHumano->extras ? number_format($recursoHumano->extras, 2, ',', '.') : '0,00') }}"
                               x-model="form.extras"
                               @input="calcularCustos()"
                               placeholder="0,00"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 money-mask @error('extras') border-red-500 @enderror">
                    </div>
                    @error('extras')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="vale_transporte" class="block text-sm font-medium text-gray-700 mb-2">Vale Transporte /mês</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="text" 
                               id="vale_transporte" 
                               name="vale_transporte" 
                               value="{{ old('vale_transporte', $recursoHumano->vale_transporte ? number_format($recursoHumano->vale_transporte, 2, ',', '.') : '0,00') }}"
                               x-model="form.vale_transporte"
                               @input="calcularCustos()"
                               placeholder="0,00"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 money-mask @error('vale_transporte') border-red-500 @enderror">
                    </div>
                    @error('vale_transporte')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="beneficios" class="block text-sm font-medium text-gray-700 mb-2">Benefícios /mês</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="text" 
                               id="beneficios" 
                               name="beneficios" 
                               value="{{ old('beneficios', $recursoHumano->beneficios ? number_format($recursoHumano->beneficios, 2, ',', '.') : '0,00') }}"
                               x-model="form.beneficios"
                               @input="calcularCustos()"
                               placeholder="0,00"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 money-mask @error('beneficios') border-red-500 @enderror">
                    </div>
                    @error('beneficios')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Encargos e Totais -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Encargos e Totais</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="percentual_encargos" class="block text-sm font-medium text-gray-700 mb-2">Percentual Encargos (%)</label>
                    <input type="number" 
                           id="percentual_encargos" 
                           name="percentual_encargos" 
                           value="{{ old('percentual_encargos', $recursoHumano->percentual_encargos) }}"
                           step="0.01"
                           min="0"
                           max="100"
                           x-model="form.percentual_encargos"
                           @input="calcularCustos()"
                           placeholder="68.50"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('percentual_encargos') border-red-500 @enderror">
                    @error('percentual_encargos')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="encargos_sociais" class="block text-sm font-medium text-gray-700 mb-2">Encargos Sociais /mês</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="text" 
                               id="encargos_sociais" 
                               name="encargos_sociais" 
                               value="{{ old('encargos_sociais', $recursoHumano->encargos_sociais ? number_format($recursoHumano->encargos_sociais, 2, ',', '.') : '0,00') }}"
                               x-model="calculated.encargos_sociais"
                               placeholder="0,00"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none" 
                               readonly>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Calculado automaticamente</p>
                </div>
                
                <div>
                    <label for="custo_total_mao_obra" class="block text-sm font-medium text-gray-700 mb-2">Custo Total Mão de Obra /mês</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                        <input type="text" 
                               id="custo_total_mao_obra" 
                               name="custo_total_mao_obra" 
                               value="{{ old('custo_total_mao_obra', $recursoHumano->custo_total_mao_obra ? number_format($recursoHumano->custo_total_mao_obra, 2, ',', '.') : '0,00') }}"
                               x-model="calculated.custo_total"
                               placeholder="0,00"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none font-semibold" 
                               readonly>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Calculado automaticamente</p>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Status</h3>
            <div class="flex items-center">
                <input type="checkbox" 
                       id="active" 
                       name="active" 
                       value="1" 
                       {{ old('active', $recursoHumano->active) ? 'checked' : '' }}
                       x-model="form.active"
                       class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                <label for="active" class="ml-2 block text-sm text-gray-700">
                    Funcionário ativo
                </label>
            </div>
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.recursos-humanos.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg transition duration-200">
                <i class="fas fa-save mr-2"></i>Atualizar Funcionário
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/money-mask.js') }}"></script>
<script>
// Função Alpine.js para inicializar os dados do formulário
function rhForm() {
    return {
        form: {
            tipo_contratacao: '{{ old('tipo_contratacao', $recursoHumano->tipo_contratacao) }}',
            cargo: '{{ old('cargo', $recursoHumano->cargo) }}',
            base_id: '{{ old('base_id', $recursoHumano->base_id) }}',
            salario_base: '{{ old('salario_base', $recursoHumano->salario_base ? number_format($recursoHumano->salario_base, 2, ',', '.') : '0,00') }}',
            insalubridade: '{{ old('insalubridade', $recursoHumano->insalubridade ? number_format($recursoHumano->insalubridade, 2, ',', '.') : '0,00') }}',
            periculosidade: '{{ old('periculosidade', $recursoHumano->periculosidade ? number_format($recursoHumano->periculosidade, 2, ',', '.') : '0,00') }}',
            horas_extras: '{{ old('horas_extras', $recursoHumano->horas_extras ? number_format($recursoHumano->horas_extras, 2, ',', '.') : '0,00') }}',
            adicional_noturno: '{{ old('adicional_noturno', $recursoHumano->adicional_noturno ? number_format($recursoHumano->adicional_noturno, 2, ',', '.') : '0,00') }}',
            extras: '{{ old('extras', $recursoHumano->extras ? number_format($recursoHumano->extras, 2, ',', '.') : '0,00') }}',
            vale_transporte: '{{ old('vale_transporte', $recursoHumano->vale_transporte ? number_format($recursoHumano->vale_transporte, 2, ',', '.') : '0,00') }}',
            beneficios: '{{ old('beneficios', $recursoHumano->beneficios ? number_format($recursoHumano->beneficios, 2, ',', '.') : '0,00') }}',
            percentual_encargos: '{{ old('percentual_encargos', $recursoHumano->percentual_encargos) }}',
            active: {{ old('active', $recursoHumano->active) ? 'true' : 'false' }}
        },
        calculated: {
            encargos_sociais: '{{ old('encargos_sociais', $recursoHumano->encargos_sociais ? number_format($recursoHumano->encargos_sociais, 2, ',', '.') : '0,00') }}',
            custo_total: '{{ old('custo_total_mao_obra', $recursoHumano->custo_total_mao_obra ? number_format($recursoHumano->custo_total_mao_obra, 2, ',', '.') : '0,00') }}'
        }
    }
}

function calcularCustos() {
    // Obter valores dos campos usando a função de máscara monetária
    const salarioBase = window.getFieldNumericValue('salario_base') || 0;
    const insalubridade = window.getFieldNumericValue('insalubridade') || 0;
    const periculosidade = window.getFieldNumericValue('periculosidade') || 0;
    const horasExtras = window.getFieldNumericValue('horas_extras') || 0;
    const adicionalNoturno = window.getFieldNumericValue('adicional_noturno') || 0;
    const extras = window.getFieldNumericValue('extras') || 0;
    const valeTransporte = window.getFieldNumericValue('vale_transporte') || 0;
    const beneficios = window.getFieldNumericValue('beneficios') || 0;
    const percentualEncargos = parseFloat(document.getElementById('percentual_encargos').value) || 0;

    // Calcular salário bruto
    const salarioBruto = salarioBase + insalubridade + periculosidade + horasExtras + adicionalNoturno + extras + valeTransporte;
    
    // Calcular encargos sociais
    const encargosSociais = salarioBruto * (percentualEncargos / 100);
    
    // Calcular custo total
    const custoTotal = salarioBruto + encargosSociais + beneficios;
    
    // Atualizar campos calculados com formatação
    const encargosSociaisField = document.getElementById('encargos_sociais');
    const custoTotalField = document.getElementById('custo_total_mao_obra');
    
    encargosSociaisField.value = encargosSociais.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    custoTotalField.value = custoTotal.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    // Atualizar valores numéricos para envio do formulário
    encargosSociaisField.setAttribute('data-numeric-value', encargosSociais);
    custoTotalField.setAttribute('data-numeric-value', custoTotal);
}

// Adicionar event listeners para recalcular quando os valores mudarem
document.addEventListener('DOMContentLoaded', function() {
    const camposCalculo = [
        'salario_base', 'insalubridade', 'periculosidade', 'horas_extras',
        'adicional_noturno', 'extras', 'vale_transporte', 'beneficios', 'percentual_encargos'
    ];
    
    camposCalculo.forEach(campo => {
        const elemento = document.getElementById(campo);
        if (elemento) {
            elemento.addEventListener('input', calcularCustos);
            elemento.addEventListener('change', calcularCustos);
        }
    });
    
    // Calcular valores iniciais
    setTimeout(calcularCustos, 100);
});
</script>
@endpush