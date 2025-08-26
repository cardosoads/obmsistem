@extends('layouts.admin')

@section('title', 'Detalhes do Grupo de Impostos')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $grupoImposto->nome }}</h1>
            <div class="flex items-center mt-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $grupoImposto->status_class == 'badge-success' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    <i class="fas {{ $grupoImposto->ativo ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                    {{ $grupoImposto->status }}
                </span>
                <span class="ml-3 text-sm text-gray-500">
                    Criado em {{ $grupoImposto->created_at->format('d/m/Y H:i') }}
                </span>
            </div>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.grupos-impostos.edit', $grupoImposto) }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('admin.grupos-impostos.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações Básicas -->
        <div class="lg:col-span-2">
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informações Básicas</h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nome</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $grupoImposto->nome }}</p>
                    </div>
                    
                    @if($grupoImposto->descricao)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Descrição</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $grupoImposto->descricao }}</p>
                    </div>
                    @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <p class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $grupoImposto->status_class == 'badge-success' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                <i class="fas {{ $grupoImposto->ativo ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                {{ $grupoImposto->status }}
                            </span>
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Criado em</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $grupoImposto->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Atualizado em</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $grupoImposto->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Impostos do Grupo -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Impostos do Grupo</h3>
                
                @if($grupoImposto->impostos->count() > 0)
                    <div class="space-y-3">
                        @foreach($grupoImposto->impostos as $imposto)
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $imposto->nome }}</h4>
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $imposto->ativo ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $imposto->status }}
                                            </span>
                                        </div>
                                        @if($imposto->descricao)
                                            <p class="mt-1 text-xs text-gray-500">{{ $imposto->descricao }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-blue-600">{{ $imposto->percentual_formatado }}</div>
                                        <div class="text-xs text-gray-500">sobre valor base</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-3xl mb-3"></i>
                        <p class="text-lg font-medium">Nenhum imposto associado</p>
                        <p class="text-sm">Este grupo ainda não possui impostos associados</p>
                        <a href="{{ route('admin.grupos-impostos.edit', $grupoImposto) }}" 
                           class="mt-3 inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                            <i class="fas fa-plus mr-2"></i>Adicionar Impostos
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Resumo e Estatísticas -->
        <div class="space-y-6">
            <!-- Resumo Financeiro -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-800 mb-4">Resumo Financeiro</h3>
                
                <div class="space-y-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $grupoImposto->quantidade_impostos }}</div>
                        <div class="text-sm text-blue-800">Impostos Ativos</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $grupoImposto->percentual_total_formatado }}</div>
                        <div class="text-sm text-green-800">Percentual Total</div>
                    </div>
                    
                    @if($grupoImposto->impostos->count() > 0)
                    <div class="border-t border-blue-200 pt-4">
                        <h4 class="text-sm font-medium text-blue-800 mb-2">Exemplos de Cálculo:</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-blue-700">R$ 1.000,00:</span>
                                <span class="font-medium text-blue-900">R$ {{ number_format($grupoImposto->calcularValorTotal(1000), 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-700">R$ 5.000,00:</span>
                                <span class="font-medium text-blue-900">R$ {{ number_format($grupoImposto->calcularValorTotal(5000), 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-700">R$ 10.000,00:</span>
                                <span class="font-medium text-blue-900">R$ {{ number_format($grupoImposto->calcularValorTotal(10000), 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Ações Rápidas -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ações Rápidas</h3>
                
                <div class="space-y-2">
                    <a href="{{ route('admin.grupos-impostos.edit', $grupoImposto) }}" 
                       class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i>Editar Grupo
                    </a>
                    
                    @if($grupoImposto->ativo)
                        <form action="{{ route('admin.grupos-impostos.toggle-status', $grupoImposto) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                                <i class="fas fa-pause mr-2"></i>Desativar
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.grupos-impostos.toggle-status', $grupoImposto) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                                <i class="fas fa-play mr-2"></i>Ativar
                            </button>
                        </form>
                    @endif
                    
                    <button onclick="confirmarExclusao()" 
                            class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-trash mr-2"></i>Excluir Grupo
                    </button>
                </div>
            </div>
            
            <!-- Histórico Recente -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informações do Sistema</h3>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID do Grupo:</span>
                        <span class="font-medium text-gray-900">#{{ $grupoImposto->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Criado em:</span>
                        <span class="font-medium text-gray-900">{{ $grupoImposto->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Última atualização:</span>
                        <span class="font-medium text-gray-900">{{ $grupoImposto->updated_at->diffForHumans() }}</span>
                    </div>
                    @if($grupoImposto->impostos->count() > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Impostos associados:</span>
                        <span class="font-medium text-gray-900">{{ $grupoImposto->impostos->count() }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Calculadora Avançada -->
    @if($grupoImposto->impostos->count() > 0)
    <div class="mt-8 bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-lg">
        <h3 class="text-xl font-semibold text-green-800 mb-6">Calculadora de Impostos</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="valor-calculo" class="block text-sm font-medium text-green-700 mb-2">Valor Base para Cálculo</label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-gray-500">R$</span>
                    <input type="number" 
                           id="valor-calculo" 
                           step="0.01" 
                           min="0"
                           value="1000.00"
                           class="w-full pl-10 pr-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="0,00">
                </div>
                <button onclick="calcularImpostos()" 
                        class="mt-3 w-full bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-md transition duration-200 font-medium">
                    <i class="fas fa-calculator mr-2"></i>Calcular Impostos
                </button>
            </div>
            
            <div id="resultado-calculo" class="bg-white p-4 rounded-lg border border-green-200" style="display: none;">
                <h4 class="font-medium text-green-800 mb-3">Resultado do Cálculo</h4>
                <div id="breakdown-calculo"></div>
            </div>
        </div>
    </div>
    @endif
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
                        Esta ação não pode ser desfeita e removerá todas as associações com impostos.
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
// Dados dos impostos para cálculos
const grupoImpostos = @json($grupoImposto->detalhes_impostos);

// Função para calcular impostos
function calcularImpostos() {
    const valorBase = parseFloat(document.getElementById('valor-calculo').value) || 0;
    const resultadoDiv = document.getElementById('resultado-calculo');
    const breakdownDiv = document.getElementById('breakdown-calculo');
    
    if (valorBase <= 0) {
        alert('Digite um valor válido para o cálculo.');
        return;
    }
    
    let totalImpostos = 0;
    let breakdownHtml = '<div class="space-y-2">';
    
    grupoImpostos.forEach(imposto => {
        const valorImposto = valorBase * (parseFloat(imposto.percentual) / 100);
        totalImpostos += valorImposto;
        
        breakdownHtml += `<div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0">
            <div>
                <div class="text-sm font-medium text-gray-900">${imposto.nome}</div>
                <div class="text-xs text-gray-500">${imposto.percentual_formatado}</div>
            </div>
            <div class="text-sm font-medium text-gray-900">R$ ${valorImposto.toFixed(2).replace('.', ',')}</div>
        </div>`;
    });
    
    const valorFinal = valorBase + totalImpostos;
    const percentualTotal = (totalImpostos / valorBase) * 100;
    
    breakdownHtml += `</div>
    <div class="mt-4 pt-4 border-t border-gray-300 space-y-2">
        <div class="flex justify-between items-center text-sm">
            <span class="text-gray-700">Valor Base:</span>
            <span class="font-medium text-gray-900">R$ ${valorBase.toFixed(2).replace('.', ',')}</span>
        </div>
        <div class="flex justify-between items-center text-sm">
            <span class="text-gray-700">Total de Impostos (${percentualTotal.toFixed(2).replace('.', ',')}%):</span>
            <span class="font-medium text-red-600">R$ ${totalImpostos.toFixed(2).replace('.', ',')}</span>
        </div>
        <div class="flex justify-between items-center text-lg font-bold border-t border-gray-300 pt-2">
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
    // Event listener para o valor de cálculo
    document.getElementById('valor-calculo').addEventListener('input', function() {
        document.getElementById('resultado-calculo').style.display = 'none';
    });
    
    // Event listener para Enter no campo de valor
    document.getElementById('valor-calculo').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            calcularImpostos();
        }
    });
    
    // Event listener para fechar modal com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            fecharModalExclusao();
        }
    });
    
    // Calcular automaticamente com valor inicial
    if (grupoImpostos.length > 0) {
        calcularImpostos();
    }
});
</script>
@endpush