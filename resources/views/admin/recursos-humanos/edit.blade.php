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
                
                <div>
                    <label for="base_salarial" class="block text-sm font-medium text-gray-700 mb-2">Base Salarial</label>
                    <input type="text" 
                           id="base_salarial" 
                           name="base_salarial" 
                           value="{{ old('base_salarial', $recursoHumano->base_salarial) }}"
                           placeholder="Descrição da base salarial"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('base_salarial') border-red-500 @enderror">
                    @error('base_salarial')
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
                    <label for="salario_base" class="block text-sm font-medium text-gray-700 mb-2">Salário Base *</label>
                    <input type="number" 
                           id="salario_base" 
                           name="salario_base" 
                           value="{{ old('salario_base', $recursoHumano->salario_base) }}"
                           step="0.01"
                           min="0"
                           x-model="form.salario_base"
                           @input="calcularCustos()"
                           placeholder="0,00"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('salario_base') border-red-500 @enderror" 
                           required>
                    @error('salario_base')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="insalubridade" class="block text-sm font-medium text-gray-700 mb-2">Insalubridade</label>
                    <input type="number" 
                           id="insalubridade" 
                           name="insalubridade" 
                           value="{{ old('insalubridade', $recursoHumano->insalubridade) }}"
                           step="0.01"
                           min="0"
                           x-model="form.insalubridade"
                           @input="calcularCustos()"
                           placeholder="0,00"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('insalubridade') border-red-500 @enderror">
                    @error('insalubridade')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="periculosidade" class="block text-sm font-medium text-gray-700 mb-2">Periculosidade</label>
                    <input type="number" 
                           id="periculosidade" 
                           name="periculosidade" 
                           value="{{ old('periculosidade', $recursoHumano->periculosidade) }}"
                           step="0.01"
                           min="0"
                           x-model="form.periculosidade"
                           @input="calcularCustos()"
                           placeholder="0,00"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('periculosidade') border-red-500 @enderror">
                    @error('periculosidade')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="horas_extras" class="block text-sm font-medium text-gray-700 mb-2">Horas Extras</label>
                    <input type="number" 
                           id="horas_extras" 
                           name="horas_extras" 
                           value="{{ old('horas_extras', $recursoHumano->horas_extras) }}"
                           step="0.01"
                           min="0"
                           x-model="form.horas_extras"
                           @input="calcularCustos()"
                           placeholder="0,00"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('horas_extras') border-red-500 @enderror">
                    @error('horas_extras')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="adicional_noturno" class="block text-sm font-medium text-gray-700 mb-2">Adicional Noturno</label>
                    <input type="number" 
                           id="adicional_noturno" 
                           name="adicional_noturno" 
                           value="{{ old('adicional_noturno', $recursoHumano->adicional_noturno) }}"
                           step="0.01"
                           min="0"
                           x-model="form.adicional_noturno"
                           @input="calcularCustos()"
                           placeholder="0,00"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('adicional_noturno') border-red-500 @enderror">
                    @error('adicional_noturno')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="extras" class="block text-sm font-medium text-gray-700 mb-2">Extras</label>
                    <input type="number" 
                           id="extras" 
                           name="extras" 
                           value="{{ old('extras', $recursoHumano->extras) }}"
                           step="0.01"
                           min="0"
                           x-model="form.extras"
                           @input="calcularCustos()"
                           placeholder="0,00"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('extras') border-red-500 @enderror">
                    @error('extras')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="vale_transporte" class="block text-sm font-medium text-gray-700 mb-2">Vale Transporte</label>
                    <input type="number" 
                           id="vale_transporte" 
                           name="vale_transporte" 
                           value="{{ old('vale_transporte', $recursoHumano->vale_transporte) }}"
                           step="0.01"
                           min="0"
                           x-model="form.vale_transporte"
                           @input="calcularCustos()"
                           placeholder="0,00"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('vale_transporte') border-red-500 @enderror">
                    @error('vale_transporte')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="beneficios" class="block text-sm font-medium text-gray-700 mb-2">Benefícios</label>
                    <input type="number" 
                           id="beneficios" 
                           name="beneficios" 
                           value="{{ old('beneficios', $recursoHumano->beneficios) }}"
                           step="0.01"
                           min="0"
                           x-model="form.beneficios"
                           @input="calcularCustos()"
                           placeholder="0,00"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('beneficios') border-red-500 @enderror">
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
                    <label for="encargos_sociais" class="block text-sm font-medium text-gray-700 mb-2">Encargos Sociais</label>
                    <input type="number" 
                           id="encargos_sociais" 
                           name="encargos_sociais" 
                           value="{{ old('encargos_sociais', $recursoHumano->encargos_sociais) }}"
                           x-model="calculated.encargos_sociais"
                           step="0.01"
                           min="0"
                           placeholder="0,00"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none" 
                           readonly>
                    <p class="text-xs text-gray-500 mt-1">Calculado automaticamente</p>
                </div>
                
                <div>
                    <label for="custo_total_mao_obra" class="block text-sm font-medium text-gray-700 mb-2">Custo Total Mão de Obra</label>
                    <input type="number" 
                           id="custo_total_mao_obra" 
                           name="custo_total_mao_obra" 
                           value="{{ old('custo_total_mao_obra', $recursoHumano->custo_total_mao_obra) }}"
                           x-model="calculated.custo_total"
                           step="0.01"
                           min="0"
                           placeholder="0,00"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none font-semibold" 
                           readonly>
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

<script>
function rhForm() {
    return {
        form: {
            tipo_contratacao: '{{ old('tipo_contratacao', $recursoHumano->tipo_contratacao) }}',
            salario_base: {{ old('salario_base', $recursoHumano->salario_base) }},
            insalubridade: {{ old('insalubridade', $recursoHumano->insalubridade) }},
            periculosidade: {{ old('periculosidade', $recursoHumano->periculosidade) }},
            horas_extras: {{ old('horas_extras', $recursoHumano->horas_extras) }},
            adicional_noturno: {{ old('adicional_noturno', $recursoHumano->adicional_noturno) }},
            extras: {{ old('extras', $recursoHumano->extras) }},
            vale_transporte: {{ old('vale_transporte', $recursoHumano->vale_transporte) }},
            beneficios: {{ old('beneficios', $recursoHumano->beneficios) }},
            percentual_encargos: {{ old('percentual_encargos', $recursoHumano->percentual_encargos) }}
        },
        calculated: {
            salario_bruto: 0,
            encargos_sociais: {{ old('encargos_sociais', $recursoHumano->encargos_sociais) }},
            custo_total: {{ old('custo_total_mao_obra', $recursoHumano->custo_total_mao_obra) }}
        },
        
        init() {
            this.calcularCustos();
        },
        
        calcularCustos() {
            // Calcular salário bruto
            this.calculated.salario_bruto = parseFloat(this.form.salario_base || 0) +
                                          parseFloat(this.form.insalubridade || 0) +
                                          parseFloat(this.form.periculosidade || 0) +
                                          parseFloat(this.form.horas_extras || 0) +
                                          parseFloat(this.form.adicional_noturno || 0) +
                                          parseFloat(this.form.extras || 0) +
                                          parseFloat(this.form.vale_transporte || 0);
            
            // Calcular encargos sociais
            this.calculated.encargos_sociais = this.calculated.salario_bruto * (parseFloat(this.form.percentual_encargos || 0) / 100);
            
            // Calcular custo total
            this.calculated.custo_total = this.calculated.salario_bruto + 
                                        this.calculated.encargos_sociais + 
                                        parseFloat(this.form.beneficios || 0);
            
            // Atualizar campos hidden
            document.getElementById('encargos_sociais').value = this.calculated.encargos_sociais.toFixed(2);
            document.getElementById('custo_total_mao_obra').value = this.calculated.custo_total.toFixed(2);
        }
    }
}
</script>
@endsection