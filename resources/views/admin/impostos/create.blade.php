@extends('layouts.admin')

@section('title', 'Novo Imposto')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Novo Imposto</h1>
        <a href="{{ route('admin.impostos.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    <form action="{{ route('admin.impostos.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Dados Básicos -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Dados do Imposto</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">Nome *</label>
                    <input type="text" 
                           id="nome" 
                           name="nome" 
                           value="{{ old('nome') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nome') border-red-500 @enderror" 
                           required>
                    <p class="text-xs text-gray-500 mt-1">Nome do imposto (ex: ICMS, IPI, ISS)</p>
                    @error('nome')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">Tipo *</label>
                    <select id="tipo" 
                            name="tipo" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tipo') border-red-500 @enderror" 
                            required
                            onchange="updateValueLabel()">
                        <option value="">Selecione o tipo</option>
                        <option value="percentual" {{ old('tipo') == 'percentual' ? 'selected' : '' }}>Percentual (%)</option>
                        <option value="fixo" {{ old('tipo') == 'fixo' ? 'selected' : '' }}>Valor Fixo (R$)</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Tipo de cálculo do imposto</p>
                    @error('tipo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-4">
                <label for="valor" class="block text-sm font-medium text-gray-700 mb-2">
                    <span id="valor-label">Valor *</span>
                </label>
                <div class="relative">
                    <span id="valor-prefix" class="absolute left-3 top-2 text-gray-500 text-sm" style="display: none;">R$</span>
                    <input type="number" 
                           id="valor" 
                           name="valor" 
                           value="{{ old('valor') }}"
                           step="0.01"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('valor') border-red-500 @enderror" 
                           required>
                    <span id="valor-suffix" class="absolute right-3 top-2 text-gray-500 text-sm" style="display: none;">%</span>
                </div>
                <p id="valor-help" class="text-xs text-gray-500 mt-1">Valor do imposto</p>
                @error('valor')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mt-4">
                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                <textarea id="descricao" 
                          name="descricao" 
                          rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('descricao') border-red-500 @enderror">{{ old('descricao') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Descrição detalhada do imposto e sua aplicação</p>
                @error('descricao')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Status -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Status</h3>
            <div class="flex items-center">
                <input type="checkbox" 
                       id="ativo" 
                       name="ativo" 
                       value="1" 
                       {{ old('ativo', true) ? 'checked' : '' }}
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="ativo" class="ml-2 block text-sm text-gray-700">
                    Imposto ativo
                </label>
            </div>
            <p class="text-xs text-gray-500 mt-1">Impostos inativos não aparecerão nas listagens do sistema</p>
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.impostos.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                <i class="fas fa-save mr-2"></i>Salvar
            </button>
        </div>
    </form>
</div>

<script>
function updateValueLabel() {
    const tipo = document.getElementById('tipo').value;
    const valorLabel = document.getElementById('valor-label');
    const valorHelp = document.getElementById('valor-help');
    const valorPrefix = document.getElementById('valor-prefix');
    const valorSuffix = document.getElementById('valor-suffix');
    const valorInput = document.getElementById('valor');
    
    if (tipo === 'percentual') {
        valorLabel.textContent = 'Percentual *';
        valorHelp.textContent = 'Percentual do imposto (ex: 18.00 para 18%)';
        valorPrefix.style.display = 'none';
        valorSuffix.style.display = 'inline';
        valorInput.style.paddingLeft = '12px';
        valorInput.style.paddingRight = '24px';
        valorInput.placeholder = '0.00';
        valorInput.max = '100';
    } else if (tipo === 'fixo') {
        valorLabel.textContent = 'Valor Fixo *';
        valorHelp.textContent = 'Valor fixo do imposto em reais (ex: 150.00)';
        valorPrefix.style.display = 'inline';
        valorSuffix.style.display = 'none';
        valorInput.style.paddingLeft = '24px';
        valorInput.style.paddingRight = '12px';
        valorInput.placeholder = '0.00';
        valorInput.removeAttribute('max');
    } else {
        valorLabel.textContent = 'Valor *';
        valorHelp.textContent = 'Valor do imposto';
        valorPrefix.style.display = 'none';
        valorSuffix.style.display = 'none';
        valorInput.style.paddingLeft = '12px';
        valorInput.style.paddingRight = '12px';
        valorInput.placeholder = '';
        valorInput.removeAttribute('max');
    }
}

// Inicializar na carga da página
document.addEventListener('DOMContentLoaded', function() {
    updateValueLabel();
});
</script>
@endsection