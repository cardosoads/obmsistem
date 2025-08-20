@extends('layouts.admin')

@section('title', 'Orçamentos')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Orçamentos</h1>
        <a href="{{ route('admin.orcamentos.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-plus mr-2"></i>Novo Orçamento
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <form method="GET" action="{{ route('admin.orcamentos.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Buscar por número, cliente ou observações..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <select name="status" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos os Status</option>
                    <option value="rascunho" {{ request('status') == 'rascunho' ? 'selected' : '' }}>Rascunho</option>
                    <option value="aguardando" {{ request('status') == 'aguardando' ? 'selected' : '' }}>Aguardando</option>
                    <option value="aprovado" {{ request('status') == 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                    <option value="rejeitado" {{ request('status') == 'rejeitado' ? 'selected' : '' }}>Rejeitado</option>
                    <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
            
            <div>
                <select name="tipo" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos os Tipos</option>
                    <option value="prestador" {{ request('tipo') == 'prestador' ? 'selected' : '' }}>Prestador</option>
                    <option value="proprio_nova_rota" {{ request('tipo') == 'proprio_nova_rota' ? 'selected' : '' }}>Próprio Nova Rota</option>
                    <option value="aumento_km" {{ request('tipo') == 'aumento_km' ? 'selected' : '' }}>Aumento KM</option>
                </select>
            </div>
            
            <div>
                <input type="text" 
                       name="cliente_nome" 
                       placeholder="Buscar por cliente..."
                       value="{{ request('cliente_nome') }}"
                       class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200">
                <i class="fas fa-search mr-2"></i>Filtrar
            </button>
            
            <a href="{{ route('admin.orcamentos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-200">
                <i class="fas fa-times mr-2"></i>Limpar
            </a>
        </form>
    </div>

    <!-- Estatísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600">Total</p>
                    <p class="text-2xl font-bold text-blue-700">{{ $estatisticas['total'] ?? 0 }}</p>
                </div>
                <i class="fas fa-file-invoice text-blue-500 text-2xl"></i>
            </div>
        </div>
        
        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-yellow-600">Pendentes</p>
                    <p class="text-2xl font-bold text-yellow-700">{{ $estatisticas['pendentes'] ?? 0 }}</p>
                </div>
                <i class="fas fa-clock text-yellow-500 text-2xl"></i>
            </div>
        </div>
        
        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-600">Aprovados</p>
                    <p class="text-2xl font-bold text-green-700">{{ $estatisticas['aprovados'] ?? 0 }}</p>
                </div>
                <i class="fas fa-check-circle text-green-500 text-2xl"></i>
            </div>
        </div>
        
        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-600">Valor Total</p>
                    <p class="text-lg font-bold text-purple-700">R$ {{ number_format($estatisticas['valor_total'] ?? 0, 2, ',', '.') }}</p>
                </div>
                <i class="fas fa-dollar-sign text-purple-500 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Tabela -->
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DT Solicitação</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orçamento</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CC (+)</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Evento</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detalhes (+)</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Total (+)</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DT Aprovação</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DT Início</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DT Exclusão</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DT Envio</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orcamentos as $orcamento)
                    <tr class="hover:bg-gray-50">
                        <!-- DT Solicitação -->
                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $orcamento->created_at->format('d/m/Y H:i') }}
                        </td>
                        
                        <!-- Orçamento -->
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                #{{ str_pad($orcamento->id, 6, '0', STR_PAD_LEFT) }}
                            </div>
                            <div class="text-xs text-gray-500">{{ $orcamento->cliente_nome ?? 'N/A' }}</div>
                        </td>
                        
                        <!-- CC (+) -->
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900" title="{{ $orcamento->centroCusto->descricao ?? 'N/A' }}">
                                {{ $orcamento->centroCusto->codigo ?? 'N/A' }}
                            </div>
                        </td>
                        
                        <!-- Evento -->
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900" title="{{ $orcamento->evento ?? '' }}">
                                {{ Str::limit($orcamento->evento ?? '-', 20) }}
                            </div>
                        </td>
                        
                        <!-- Detalhes (+) -->
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900" title="{{ $orcamento->detalhes ?? '' }}">
                                {{ Str::limit($orcamento->detalhes ?? '-', 25) }}
                            </div>
                        </td>
                        
                        <!-- Valor Total (+) -->
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900" title="Valor com impostos: R$ {{ number_format($orcamento->valor_final ?? 0, 2, ',', '.') }}">
                                R$ {{ number_format($orcamento->valor_total ?? 0, 2, ',', '.') }}
                            </div>
                        </td>
                        
                        <!-- Status -->
                        <td class="px-3 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @switch($orcamento->status)
                                    @case('rascunho')
                                        bg-gray-100 text-gray-800
                                        @break
                                    @case('aguardando')
                                        bg-yellow-100 text-yellow-800
                                        @break
                                    @case('aprovado')
                                        bg-green-100 text-green-800
                                        @break
                                    @case('rejeitado')
                                        bg-red-100 text-red-800
                                        @break
                                    @case('cancelado')
                                        bg-red-100 text-red-800
                                        @break
                                    @default
                                        bg-gray-100 text-gray-800
                                @endswitch">
                                @switch($orcamento->status)
                                    @case('aguardando')
                                        AGUARDANDO
                                        @break
                                    @case('aprovado')
                                        APROVADO
                                        @break
                                    @case('rejeitado')
                                        REPROVADO
                                        @break
                                    @default
                                        {{ strtoupper($orcamento->status ?? 'rascunho') }}
                                @endswitch
                            </span>
                        </td>
                        
                        <!-- DT Aprovação -->
                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $orcamento->data_aprovacao ? $orcamento->data_aprovacao->format('d/m/Y H:i') : '-' }}
                        </td>
                        
                        <!-- DT Início -->
                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $orcamento->data_inicio ? $orcamento->data_inicio->format('d/m/Y H:i') : '-' }}
                        </td>
                        
                        <!-- DT Exclusão -->
                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $orcamento->data_exclusao ? $orcamento->data_exclusao->format('d/m/Y H:i') : '-' }}
                        </td>
                        
                        <!-- DT Envio -->
                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $orcamento->data_envio ? $orcamento->data_envio->format('d/m/Y H:i') : '-' }}
                        </td>
                        
                        <!-- Ações -->
                        <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-1">
                                <a href="{{ route('admin.orcamentos.show', $orcamento->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 transition duration-200" 
                                   title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(in_array($orcamento->status, ['rascunho', 'aguardando']))
                                    <a href="{{ route('admin.orcamentos.edit', $orcamento->id) }}" 
                                       class="text-yellow-600 hover:text-yellow-900 transition duration-200" 
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                                <button onclick="duplicateOrcamento({{ $orcamento->id }})" 
                                        class="text-green-600 hover:text-green-900 transition duration-200" 
                                        title="Duplicar">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <button onclick="deleteOrcamento({{ $orcamento->id }}, '#{{ str_pad($orcamento->id, 6, '0', STR_PAD_LEFT) }}')" 
                                        class="text-red-600 hover:text-red-900 transition duration-200" 
                                        title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center py-8">
                                <i class="fas fa-file-invoice text-4xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium">Nenhum orçamento encontrado</p>
                                <p class="text-sm">Comece criando seu primeiro orçamento</p>
                                <a href="{{ route('admin.orcamentos.create') }}" 
                                   class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                                    <i class="fas fa-plus mr-2"></i>Criar Orçamento
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    @if($orcamentos->hasPages())
        <div class="mt-6">
            {{ $orcamentos->links() }}
        </div>
    @endif
</div>

<!-- Scripts -->
<script>
function duplicateOrcamento(id) {
    if (confirm('Tem certeza que deseja duplicar este orçamento?')) {
        fetch(`/admin/orcamentos/${id}/duplicate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = `/admin/orcamentos/${data.new_id}/edit`;
            } else {
                alert('Erro ao duplicar orçamento: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao duplicar orçamento');
        });
    }
}

function deleteOrcamento(id, numero) {
    if (confirm(`Tem certeza que deseja excluir o orçamento ${numero}?\n\nEsta ação não pode ser desfeita.`)) {
        fetch(`/admin/orcamentos/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao excluir orçamento: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao excluir orçamento');
        });
    }
}
</script>
@endsection