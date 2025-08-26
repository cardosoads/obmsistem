@extends('layouts.admin')

@section('title', 'Novo Grupo de Impostos')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Novo Grupo de Impostos</h1>
        <a href="{{ route('admin.grupos-impostos.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    <form action="{{ route('admin.grupos-impostos.store') }}" method="POST" id="grupoForm">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Informações Básicas -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informações Básicas</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                        <input type="text" 
                               id="nome" 
                               name="nome" 
                               value="{{ old('nome') }}"
                               required
                               maxlength="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nome') border-red-500 @enderror"
                               placeholder="Ex: Impostos Federais">
                        @error('nome')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="descricao" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                        <textarea id="descricao" 
                                  name="descricao" 
                                  rows="3"
                                  maxlength="500"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('descricao') border-red-500 @enderror"
                                  placeholder="Descrição opcional do grupo...">{{ old('descricao') }}</textarea>
                        @error('descricao')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Máximo 500 caracteres</p>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="ativo" 
                               name="ativo" 
                               value="1"
                               {{ old('ativo', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="ativo" class="ml-2 block text-sm text-gray-700">
                            Grupo ativo
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Seleção de Impostos -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Impostos do Grupo</h3>
                
                <div class="space-y-3">
                    @if($impostos->count() > 0)
                        <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-md p-3 bg-white">
                            @foreach($impostos as $imposto)
                                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               id="imposto_{{ $imposto->id }}" 
                                               name="impostos[]" 
                                               value="{{ $imposto->id }}"
                                               {{ in_array($imposto->id, old('impostos', [])) ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded imposto-checkbox">
                                        <label for="imposto_{{ $imposto->id }}" class="ml-2 flex-1 cursor-pointer">
                                            <div class="text-sm font-medium text-gray-900">{{ $imposto->nome }}</div>
                                            @if($imposto->descricao)
                                                <div class="text-xs text-gray-500">{{ Str::limit($imposto->descricao, 50) }}</div>
                                            @endif
                                        </label>
                                    </div>
                                    <div class="text-sm font-semibold text-blue-600" data-percentual="{{ $imposto->percentual }}">
                                        {{ $imposto->percentual_formatado }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Impostos selecionados: <span id="impostos-count">0</span></span>
                            <div class="flex space-x-2">
                                <button type="button" onclick="selectAllImpostos()" class="text-blue-600 hover:text-blue-800">Selecionar todos</button>
                                <button type="button" onclick="deselectAllImpostos()" class="text-red-600 hover:text-red-800">Desmarcar todos</button>
                            </div>
                        </div>
                        
                        @error('impostos')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                            <p>Nenhum imposto disponível</p>
                            <p class="text-sm">Crie impostos primeiro para poder adicioná-los ao grupo</p>
                            <a href="{{ route('admin.impostos.create') }}" class="mt-2 inline-block text-blue-600 hover:text-blue-800">
                                Criar novo imposto
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Resumo do Grupo -->
        <div class="mt-6 bg-blue-50 p-4 rounded-lg" id="resumo-grupo" style="display: none;">
            <h3 class="text-lg font-semibold text-blue-800 mb-4">Resumo do Grupo</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600" id="total-impostos">0</div>
                    <div class="text-sm text-blue-800">Impostos Selecionados</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600" id="percentual-total">0,00%</div>
                    <div class="text-sm text-green-800">Percentual Total</div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-semibold text-purple-600" id="valor-exemplo">R$ 0,00</div>
                    <div class="text-sm text-purple-800">Valor para R$ 1.000,00</div>
                </div>
            </div>
            
            <div class="mt-4">
                <h4 class="font-medium text-blue-800 mb-2">Impostos Incluídos:</h4>
                <div id="impostos-selecionados" class="flex flex-wrap gap-2"></div>
            </div>
        </div>
        
        <!-- Calculadora de Teste -->
        <div class="mt-6 bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Calculadora de Teste</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label for="valor_teste" class="block text-sm font-medium text-gray-700 mb-1">Valor Base (R$)</label>
                    <input type="number" 
                           id="valor_teste" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="1000.00"
                           step="0.01"
                           min="0"
                           value="1000">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Percentual Total</label>
                    <div class="px-3 py-2 bg-white border border-gray-300 rounded-md font-semibold text-green-600" id="percentual-display">
                        0,00%
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valor dos Impostos</label>
                    <div class="px-3 py-2 bg-white border border-gray-300 rounded-md font-semibold text-blue-600" id="valor-impostos-display">
                        R$ 0,00
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total com Impostos</label>
                    <div class="px-3 py-2 bg-white border border-gray-300 rounded-md font-bold text-purple-600" id="valor-total-display">
                        R$ 0,00
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Botões de Ação -->
        <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.grupos-impostos.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                <i class="fas fa-times mr-2"></i>Cancelar
            </a>
            
            <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition duration-200">
                <i class="fas fa-save mr-2"></i>Salvar Grupo
            </button>
        </div>
    </form>
</div>

<script>
// Atualizar contadores e cálculos
function updateCalculations() {
    const checkboxes = document.querySelectorAll('.imposto-checkbox:checked');
    const count = checkboxes.length;
    const valorTeste = parseFloat(document.getElementById('valor_teste').value) || 0;
    
    let totalPercentual = 0;
    const impostosSelecionados = [];
    
    checkboxes.forEach(checkbox => {
        const percentualElement = checkbox.closest('.flex').querySelector('[data-percentual]');
        const percentual = parseFloat(percentualElement.dataset.percentual) || 0;
        const nome = checkbox.nextElementSibling.querySelector('.text-sm').textContent;
        
        totalPercentual += percentual;
        impostosSelecionados.push({
            nome: nome,
            percentual: percentual
        });
    });
    
    const valorImpostos = (valorTeste * totalPercentual) / 100;
    const valorTotal = valorTeste + valorImpostos;
    
    // Atualizar contadores
    document.getElementById('impostos-count').textContent = count;
    document.getElementById('total-impostos').textContent = count;
    document.getElementById('percentual-total').textContent = totalPercentual.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }) + '%';
    
    document.getElementById('valor-exemplo').textContent = 'R$ ' + ((1000 * totalPercentual) / 100).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    // Atualizar calculadora
    document.getElementById('percentual-display').textContent = totalPercentual.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }) + '%';
    
    document.getElementById('valor-impostos-display').textContent = 'R$ ' + valorImpostos.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    document.getElementById('valor-total-display').textContent = 'R$ ' + valorTotal.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    // Atualizar lista de impostos selecionados
    const container = document.getElementById('impostos-selecionados');
    container.innerHTML = '';
    
    impostosSelecionados.forEach(imposto => {
        const badge = document.createElement('span');
        badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
        badge.textContent = `${imposto.nome} (${imposto.percentual.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        })}%)`;
        container.appendChild(badge);
    });
    
    // Mostrar/ocultar resumo
    const resumo = document.getElementById('resumo-grupo');
    if (count > 0) {
        resumo.style.display = 'block';
    } else {
        resumo.style.display = 'none';
    }
}

// Selecionar todos os impostos
function selectAllImpostos() {
    document.querySelectorAll('.imposto-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
    updateCalculations();
}

// Desmarcar todos os impostos
function deselectAllImpostos() {
    document.querySelectorAll('.imposto-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateCalculations();
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Atualizar cálculos quando impostos são selecionados
    document.querySelectorAll('.imposto-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateCalculations);
    });
    
    // Atualizar cálculos quando valor de teste muda
    document.getElementById('valor_teste').addEventListener('input', updateCalculations);
    
    // Validação do formulário
    document.getElementById('grupoForm').addEventListener('submit', function(e) {
        const nome = document.getElementById('nome').value.trim();
        const impostosSelecionados = document.querySelectorAll('.imposto-checkbox:checked').length;
        
        if (!nome) {
            e.preventDefault();
            alert('Por favor, informe o nome do grupo.');
            document.getElementById('nome').focus();
            return;
        }
        
        if (impostosSelecionados === 0) {
            e.preventDefault();
            alert('Por favor, selecione pelo menos um imposto para o grupo.');
            return;
        }
    });
    
    // Cálculo inicial
    updateCalculations();
});
</script>
@endsection