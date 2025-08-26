@extends('layouts.admin')

@section('title', 'Editar Imposto')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Editar Imposto</h1>
        <a href="{{ route('admin.impostos.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    <form action="{{ route('admin.impostos.update', $imposto->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Dados do Imposto -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Dados do Imposto</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">Nome *</label>
                    <input type="text" 
                           id="nome" 
                           name="nome" 
                           value="{{ old('nome', $imposto->nome) }}"
                           placeholder="Ex: ICMS, IPI, PIS, COFINS..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nome') border-red-500 @enderror" 
                           required>
                    @error('nome')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="percentual" class="block text-sm font-medium text-gray-700 mb-2">Percentual (%) *</label>
                    <input type="number" 
                           id="percentual" 
                           name="percentual" 
                           value="{{ old('percentual', $imposto->percentual) }}"
                           placeholder="Ex: 18.00"
                           step="0.01"
                           min="0"
                           max="100"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('percentual') border-red-500 @enderror" 
                           required>
                    @error('percentual')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-4">
                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                <textarea id="descricao" 
                          name="descricao" 
                          rows="3"
                          placeholder="Descrição opcional do imposto..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('descricao') border-red-500 @enderror">{{ old('descricao', $imposto->descricao) }}</textarea>
                @error('descricao')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mt-4">
                <label for="grupos_impostos" class="block text-sm font-medium text-gray-700 mb-2">Grupos de Impostos</label>
                <select id="grupos_impostos" 
                        name="grupos_impostos[]" 
                        multiple
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('grupos_impostos') border-red-500 @enderror">
                    @foreach($gruposImpostos as $grupo)
                        <option value="{{ $grupo->id }}" 
                                {{ in_array($grupo->id, old('grupos_impostos', $imposto->gruposImpostos->pluck('id')->toArray())) ? 'selected' : '' }}>
                            {{ $grupo->nome }} ({{ $grupo->percentual_total_formatado }})
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-sm text-gray-500">
                    Selecione os grupos aos quais este imposto pertence (opcional)
                </p>
                @error('grupos_impostos')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mt-4">
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="ativo" 
                           name="ativo" 
                           value="1"
                           {{ old('ativo', $imposto->ativo) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="ativo" class="ml-2 block text-sm text-gray-700">
                        Imposto ativo
                    </label>
                </div>
                <p class="mt-1 text-sm text-gray-500">
                    Impostos inativos não aparecerão nas listagens principais
                </p>
                @error('ativo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Informações Adicionais -->
        <div class="bg-blue-50 p-4 rounded-lg">
            <h4 class="text-sm font-medium text-blue-800 mb-2">Informações</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-700">
                <div>
                    <strong>Criado em:</strong> {{ $imposto->created_at->format('d/m/Y H:i') }}
                </div>
                <div>
                    <strong>Última atualização:</strong> {{ $imposto->updated_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.impostos.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                <i class="fas fa-save mr-2"></i>Atualizar Imposto
            </button>
        </div>
    </form>
</div>

<script>
// Formatação do campo percentual
document.getElementById('percentual').addEventListener('input', function(e) {
    let value = e.target.value;
    if (value && !isNaN(value)) {
        // Limita a 2 casas decimais
        if (value.includes('.')) {
            let parts = value.split('.');
            if (parts[1] && parts[1].length > 2) {
                e.target.value = parseFloat(value).toFixed(2);
            }
        }
    }
});

// Preview do cálculo
document.getElementById('percentual').addEventListener('input', function(e) {
    const percentual = parseFloat(e.target.value) || 0;
    const valorBase = 1000; // Valor exemplo para demonstração
    const valorImposto = (valorBase * percentual / 100).toFixed(2);
    
    // Remove preview anterior se existir
    const existingPreview = document.getElementById('preview-calculo');
    if (existingPreview) {
        existingPreview.remove();
    }
    
    if (percentual > 0) {
        const preview = document.createElement('div');
        preview.id = 'preview-calculo';
        preview.className = 'mt-2 p-3 bg-blue-50 border border-blue-200 rounded-md';
        preview.innerHTML = `
            <p class="text-sm text-blue-700">
                <i class="fas fa-calculator mr-1"></i>
                <strong>Exemplo:</strong> Sobre R$ ${valorBase.toLocaleString('pt-BR', {minimumFractionDigits: 2})} = 
                <strong>R$ ${parseFloat(valorImposto).toLocaleString('pt-BR', {minimumFractionDigits: 2})}</strong>
            </p>
        `;
        e.target.parentNode.appendChild(preview);
    }
});

// Inicializar Select2 para grupos de impostos
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined' && $.fn.select2) {
        $('#grupos_impostos').select2({
            placeholder: 'Selecione os grupos...',
            allowClear: true,
            width: '100%'
        });
    }
});
</script>
@endsection