@extends('layouts.admin')

@section('title', 'Editar Grupo de Impostos')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Editar Grupo de Impostos</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.grupos-impostos.show', $grupoImposto) }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-eye mr-2"></i>Visualizar
            </a>
            <a href="{{ route('admin.grupos-impostos.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <form action="{{ route('admin.grupos-impostos.update', $grupoImposto) }}" method="POST" id="grupoForm">
        @csrf
        @method('PUT')
        
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
                               value="{{ old('nome', $grupoImposto->nome) }}"
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
                                  placeholder="Descrição opcional do grupo...">{{ old('descricao', $grupoImposto->descricao) }}</textarea>
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
                               {{ old('ativo', $grupoImposto->ativo) ? 'checked' : '' }}
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
                                               {{ in_array($imposto->id, old('impostos', $grupoImposto->impostos->pluck('id')->toArray())) ? 'checked' : '' }}
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
                            <span class="text-gray-600">Impostos selecionados: <span id="impostos-count">{{ $grupoImposto->impostos->count() }}</span></span>
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
        
        <!-- Resumo Atual do Grupo -->
        @if($grupoImposto->impostos->count() > 0)
        <div class="mt-6 bg-green-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-green-800 mb-4">Resumo Atual do Grupo</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $grupoImposto->quantidade_impostos }}</div>
                    <div class="text-sm text-green-800">Impostos Ativos</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $grupoImposto->percentual_total_formatado }}</div>
                    <div class="text-sm text-blue-800">Percentual Total</div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-semibold text-purple-600">R$ {{ number_format($grupoImposto->calcularValorTotal(1000), 2, ',', '.') }}</div>
                    <div class="text-sm text-purple-800">Valor para R$ 1.000,00</div>
                </div>
            </div>
            
            <div class="mt-4">
                <h4 class="font-medium text-green-800 mb-2">Impostos Atuais:</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach($grupoImposto->impostos as $imposto)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $imposto->nome }} ({{ $imposto->percentual_formatado }})
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        
        <!-- Resumo do Grupo (Preview das alterações) -->
        <div class="mt-6 bg-blue-50 p-4 rounded-lg" id="resumo-grupo" style="display: none;">
            <h3 class="text-lg font-semibold text-blue-800 mb-4">Preview das Alterações</h3>
            
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
                <h4 class="font-medium text-blue-800 mb-2">Impostos Selecionados:</h4>
                <div id="impostos-selecionados" class="flex flex-wrap gap-2"></div>
            </div>
        </div>
        
        <!-- Calculadora de Teste -->
        <div class="mt-6 bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Calculadora de Teste</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="valor-teste" class="block text-sm font-medium text-gray-700 mb-1">Valor Base para Teste</label>
                    <input type="number" 
                           id="valor-teste" 
                           step="0.01" 
                           min="0"
                           value="1000.00"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Digite um valor...">
                </div>
                <div class="flex items-end">
                    <button type="button" 
                            onclick="calcularTeste()" 
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-200">
                        <i class="fas fa-calculator mr-2"></i>Calcular
                    </button>
                </div>
            </div>
            
            <div id="resultado-teste" class="mt-4 p-4 bg-white rounded-md border" style="display: none;">
                <h4 class="font-medium text-gray-800 mb-2">Resultado do Cálculo:</h4>
                <div id="breakdown-teste"></div>
            </div>
        </div>
        
        <!-- Botões de Ação -->
        <div class="mt-8 flex justify-between">
            <div class="flex space-x-2">
                <button type="button" 
                        onclick="confirmarExclusao()" 
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-trash mr-2"></i>Excluir Grupo
                </button>
            </div>
            
            <div class="flex space-x-2">
                <a href="{{ route('admin.grupos-impostos.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-save mr-2"></i>Salvar Alterações
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div id="modal-exclusao" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-gray-900">Confirmar Exclusão</h3>
                    </div>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-500">
                        Tem certeza que deseja excluir o grupo "{{ $grupoImposto->nome }}"? 
                        Esta ação não pode ser desfeita.
                    </p>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" 
                            onclick="fecharModalExclusao()" 
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md transition duration-200">
                        Cancelar
                    </button>
                    <form action="{{ route('admin.grupos-impostos.destroy', $grupoImposto) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md transition duration-200">
                            Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Variáveis globais
let impostosData = @json($impostos->keyBy('id'));

// Função para atualizar contadores e resumo
function atualizarResumo() {
    const checkboxes = document.querySelectorAll('.imposto-checkbox:checked');
    const count = checkboxes.length;
    const resumoDiv = document.getElementById('resumo-grupo');
    
    document.getElementById('impostos-count').textContent = count;
    
    if (count > 0) {
        let totalPercentual = 0;
        let impostosHtml = '';
        
        checkboxes.forEach(checkbox => {
            const impostoId = checkbox.value;
            const imposto = impostosData[impostoId];
            if (imposto) {
                totalPercentual += parseFloat(imposto.percentual);
                impostosHtml += `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    ${imposto.nome} (${imposto.percentual_formatado})
                </span>`;
            }
        });
        
        const valorExemplo = (1000 * totalPercentual / 100);
        
        document.getElementById('total-impostos').textContent = count;
        document.getElementById('percentual-total').textContent = totalPercentual.toFixed(2).replace('.', ',') + '%';
        document.getElementById('valor-exemplo').textContent = 'R$ ' + valorExemplo.toFixed(2).replace('.', ',');
        document.getElementById('impostos-selecionados').innerHTML = impostosHtml;
        
        resumoDiv.style.display = 'block';
    } else {
        resumoDiv.style.display = 'none';
    }
}

// Função para selecionar todos os impostos
function selectAllImpostos() {
    document.querySelectorAll('.imposto-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
    atualizarResumo();
}

// Função para desmarcar todos os impostos
function deselectAllImpostos() {
    document.querySelectorAll('.imposto-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    atualizarResumo();
}

// Função para calcular teste
function calcularTeste() {
    const valorBase = parseFloat(document.getElementById('valor-teste').value) || 0;
    const checkboxes = document.querySelectorAll('.imposto-checkbox:checked');
    const resultadoDiv = document.getElementById('resultado-teste');
    const breakdownDiv = document.getElementById('breakdown-teste');
    
    if (valorBase <= 0) {
        alert('Digite um valor válido para o teste.');
        return;
    }
    
    if (checkboxes.length === 0) {
        alert('Selecione pelo menos um imposto para o teste.');
        return;
    }
    
    let totalImpostos = 0;
    let breakdownHtml = '<div class="space-y-2">';
    
    checkboxes.forEach(checkbox => {
        const impostoId = checkbox.value;
        const imposto = impostosData[impostoId];
        if (imposto) {
            const valorImposto = valorBase * (parseFloat(imposto.percentual) / 100);
            totalImpostos += valorImposto;
            
            breakdownHtml += `<div class="flex justify-between items-center py-1 border-b border-gray-200">
                <span class="text-sm text-gray-700">${imposto.nome} (${imposto.percentual_formatado})</span>
                <span class="text-sm font-medium text-gray-900">R$ ${valorImposto.toFixed(2).replace('.', ',')}</span>
            </div>`;
        }
    });
    
    const valorFinal = valorBase + totalImpostos;
    
    breakdownHtml += `</div>
    <div class="mt-3 pt-3 border-t border-gray-300">
        <div class="flex justify-between items-center text-sm">
            <span class="text-gray-700">Valor Base:</span>
            <span class="font-medium">R$ ${valorBase.toFixed(2).replace('.', ',')}</span>
        </div>
        <div class="flex justify-between items-center text-sm">
            <span class="text-gray-700">Total de Impostos:</span>
            <span class="font-medium text-red-600">R$ ${totalImpostos.toFixed(2).replace('.', ',')}</span>
        </div>
        <div class="flex justify-between items-center text-lg font-bold border-t border-gray-300 pt-2 mt-2">
            <span class="text-gray-900">Valor Final:</span>
            <span class="text-green-600">R$ ${valorFinal.toFixed(2).replace('.', ',')}</span>
        </div>
    </div>`;
    
    breakdownDiv.innerHTML = breakdownHtml;
    resultadoDiv.style.display = 'block';
}

// Função para confirmar exclusão
function confirmarExclusao() {
    document.getElementById('modal-exclusao').classList.remove('hidden');
}

// Função para fechar modal de exclusão
function fecharModalExclusao() {
    document.getElementById('modal-exclusao').classList.add('hidden');
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Atualizar resumo inicial
    atualizarResumo();
    
    // Adicionar event listeners aos checkboxes
    document.querySelectorAll('.imposto-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', atualizarResumo);
    });
    
    // Event listener para o valor de teste
    document.getElementById('valor-teste').addEventListener('input', function() {
        document.getElementById('resultado-teste').style.display = 'none';
    });
    
    // Event listener para fechar modal com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            fecharModalExclusao();
        }
    });
});
</script>
@endpush