@extends('layouts.admin')

@section('title', 'Orçamentos')
@section('page-title', 'Orçamentos')

@section('content')
<div class="mx-auto">
    <div class="bg-white shadow-sm rounded-lg">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Gerenciar Orçamentos</h3>
                <a href="{{ route('admin.orcamentos.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-plus mr-2"></i> Novo Orçamento
                </a>
            </div>
        </div>

        <!-- Filtros -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <form method="GET" action="{{ route('admin.orcamentos.index') }}" class="mb-6" id="filters-form">
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Número, cliente, rota...">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="status" name="status">
                            <option value="">Todos</option>
                            <option value="rascunho" {{ request('status') == 'rascunho' ? 'selected' : '' }}>Rascunho</option>
                            <option value="enviado" {{ request('status') == 'enviado' ? 'selected' : '' }}>Enviado</option>
                            <option value="aprovado" {{ request('status') == 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                            <option value="rejeitado" {{ request('status') == 'rejeitado' ? 'selected' : '' }}>Rejeitado</option>
                            <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>
                    <div>
                        <label for="tipo_orcamento" class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="tipo_orcamento" name="tipo_orcamento">
                            <option value="">Todos</option>
                            <option value="prestador" {{ request('tipo_orcamento') == 'prestador' ? 'selected' : '' }}>Prestador</option>
                            <option value="aumento_km" {{ request('tipo_orcamento') == 'aumento_km' ? 'selected' : '' }}>Aumento KM</option>
                            <option value="proprio_nova_rota" {{ request('tipo_orcamento') == 'proprio_nova_rota' ? 'selected' : '' }}>Próprio Nova Rota</option>
                        </select>
                    </div>
                    <div>
                        <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-1">Data Início</label>
                        <input type="date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                               id="data_inicio" 
                               name="data_inicio" 
                               value="{{ request('data_inicio') }}">
                    </div>
                    <div>
                        <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-1">Data Fim</label>
                        <input type="date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                               id="data_fim" 
                               name="data_fim" 
                               value="{{ request('data_fim') }}">
                    </div>
                    <div class="flex flex-col justify-end">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.orcamentos.index') }}" class="inline-flex items-center px-3 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-times mr-2"></i> Limpar Filtros
                    </a>
                </div>
                    </form>
                </div>

                <!-- Mensagens de Sessão -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 mx-6 mt-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none'">
                            <svg class="fill-current h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <title>Fechar</title>
                                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                            </svg>
                        </span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 mx-6 mt-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none'">
                            <svg class="fill-current h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <title>Fechar</title>
                                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                            </svg>
                        </span>
                    </div>
                @endif

                <div class="overflow-hidden">

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Solicitação</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rota</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responsável</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($orcamentos as $orcamento)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <strong>{{ $orcamento->numero_orcamento }}</strong>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $orcamento->data_solicitacao->format('d/m/Y') }}
                                            <br><small class="text-gray-400">{{ $orcamento->data_solicitacao->diffForHumans() }}</small>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <strong>{{ $orcamento->cliente_nome }}</strong>
                                            @if($orcamento->cliente_omie_id)
                                                <br><small class="text-gray-400">ID: {{ $orcamento->cliente_omie_id }}</small>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $orcamento->nome_rota }}
                                            @if($orcamento->id_logcare)
                                                <br><small class="text-gray-400">LOGCARE: {{ $orcamento->id_logcare }}</small>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @switch($orcamento->tipo_orcamento)
                                                @case('prestador')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Prestador</span>
                                                    @break
                                                @case('aumento_km')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Aumento KM</span>
                                                    @break
                                                @case('proprio_nova_rota')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Próprio Nova Rota</span>
                                                    @break
                                                @default
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ $orcamento->tipo_orcamento }}</span>
                                            @endswitch
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap" style="min-width: 140px;">
                                            <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 status-select bg-white" 
                                                    data-orcamento-id="{{ $orcamento->id }}" 
                                                    data-current-status="{{ $orcamento->status }}">
                                                <option value="rascunho" {{ $orcamento->status == 'rascunho' ? 'selected' : '' }}>Rascunho</option>
                                                <option value="enviado" {{ $orcamento->status == 'enviado' ? 'selected' : '' }}>Enviado</option>
                                                <option value="aprovado" {{ $orcamento->status == 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                                                <option value="rejeitado" {{ $orcamento->status == 'rejeitado' ? 'selected' : '' }}>Rejeitado</option>
                                                <option value="cancelado" {{ $orcamento->status == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($orcamento->valor_final)
                                                <strong>R$ {{ number_format($orcamento->valor_final, 2, ',', '.') }}</strong>
                                            @elseif($orcamento->valor_total)
                                                R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}
                                            @else
                                                <span class="text-gray-400">Não informado</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $orcamento->user->name ?? 'N/A' }}
                                            @if($orcamento->data_orcamento)
                                                <br><small class="text-gray-400">{{ $orcamento->data_orcamento->format('d/m/Y') }}</small>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.orcamentos.show', $orcamento) }}" 
                                                   class="inline-flex items-center px-2 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150" 
                                                   title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.orcamentos.edit', $orcamento) }}" 
                                                   class="inline-flex items-center px-2 py-1 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150" 
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="inline-flex items-center px-2 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" 
                                                        title="Excluir"
                                                        onclick="confirmDelete({{ $orcamento->id }}, '{{ $orcamento->numero_orcamento }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-4 text-center">
                                            <div class="text-gray-500">
                                                <i class="fas fa-inbox text-3xl mb-3"></i>
                                                <h5 class="text-lg font-medium mb-2">Nenhum orçamento encontrado</h5>
                                                <p class="mb-4">Não há orçamentos cadastrados com os filtros selecionados.</p>
                                                <a href="{{ route('admin.orcamentos.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    <i class="fas fa-plus mr-2"></i> Criar Primeiro Orçamento
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($orcamentos->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <small class="text-gray-500">
                                    Mostrando {{ $orcamentos->firstItem() }} a {{ $orcamentos->lastItem() }} 
                                    de {{ $orcamentos->total() }} resultados
                                </small>
                            </div>
                            <div>
                                {{ $orcamentos->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Confirmar Exclusão</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeDeleteModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-500">
                    Tem certeza que deseja excluir o orçamento <strong id="numeroOrcamentoExclusao" class="text-gray-900"></strong>?
                </p>
                <p class="text-sm text-red-600 mt-2">
                    <small>Esta ação não pode ser desfeita.</small>
                </p>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300" 
                        onclick="closeDeleteModal()">
                    Cancelar
                </button>
                <form id="formExclusao" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Excluir
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-submit do formulário de filtros com delay
let timeoutId;
document.getElementById('search')?.addEventListener('input', function() {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(function() {
        document.getElementById('filters-form')?.submit();
    }, 500);
});

// Submit imediato para outros filtros
const filterElements = ['status', 'tipo_orcamento', 'data_inicio', 'data_fim'];
filterElements.forEach(id => {
    const element = document.getElementById(id);
    if (element) {
        element.addEventListener('change', function() {
            document.getElementById('filters-form')?.submit();
        });
    }
});

// Funções do modal de exclusão
function confirmDelete(id, numero) {
    document.getElementById('numeroOrcamentoExclusao').textContent = numero;
    document.getElementById('formExclusao').action = `/admin/orcamentos/${id}`;
    document.getElementById('delete-modal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
}

// Fechar modal ao clicar fora dele
document.getElementById('delete-modal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Fechar modal com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endpush
@endsection