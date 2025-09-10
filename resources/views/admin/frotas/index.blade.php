@extends('layouts.admin')

@section('title', 'Frotas')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Frotas</h1>
        <a href="{{ route('admin.frotas.create') }}" 
           class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-plus mr-2"></i>Nova Frota
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <form method="GET" action="{{ route('admin.frotas.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Buscar por tipo de veículo..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
            
            <div>
                <select name="tipo_veiculo_id" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Todos os Tipos</option>
                    @foreach($tiposVeiculos as $tipo)
                        <option value="{{ $tipo->id }}" {{ request('tipo_veiculo_id') == $tipo->id ? 'selected' : '' }}>
                            {{ $tipo->codigo }} - {{ $tipo->descricao }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <select name="status" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Todos os Status</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Ativo</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
            
            <button type="submit" 
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition duration-200">
                <i class="fas fa-search mr-2"></i>Filtrar
            </button>
            
            @if(request()->hasAny(['search', 'tipo_veiculo_id', 'status']))
                <a href="{{ route('admin.frotas.index') }}" 
                   class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-md transition duration-200">
                    <i class="fas fa-times mr-2"></i>Limpar
                </a>
            @endif
        </form>
    </div>

    <!-- Resumo -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-car text-blue-500 text-2xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-blue-600">Total de Frotas</p>
                    <p class="text-2xl font-semibold text-blue-900">{{ $totalFrotas }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-green-50 p-4 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-600">Frotas Ativas</p>
                    <p class="text-2xl font-semibold text-green-900">{{ $frotasAtivas }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-yellow-50 p-4 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-dollar-sign text-yellow-500 text-2xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-yellow-600">Custo Total Médio</p>
                    <p class="text-2xl font-semibold text-yellow-900">R$ {{ number_format($custoMedio, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-purple-50 p-4 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-chart-line text-purple-500 text-2xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-purple-600">Valor FIPE Total</p>
                    <p class="text-2xl font-semibold text-purple-900">R$ {{ number_format($valorFipeTotal, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tipo de Veículo
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        FIPE
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        % FIPE
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aluguel
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Custo Total
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($frotas as $frota)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $frota->id }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <div class="font-medium">{{ $frota->tipoVeiculo->codigo ?? '-' }}</div>
                                <div class="text-gray-500">{{ $frota->tipoVeiculo->descricao ?? '-' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-mono">R$ {{ number_format($frota->fipe, 2, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ number_format($frota->percentual_fipe, 1, ',', '.') }}%</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-mono">R$ {{ number_format($frota->aluguel_carro, 2, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">R$ {{ number_format($frota->custo_total, 2, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $frota->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $frota->active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('admin.frotas.show', $frota->id) }}" 
                                   class="text-blue-600 hover:text-blue-900" 
                                   title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('admin.frotas.edit', $frota->id) }}" 
                                   class="text-yellow-600 hover:text-yellow-900" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <button onclick="recalcularCusto({{ $frota->id }})" 
                                        class="text-purple-600 hover:text-purple-900" 
                                        title="Recalcular Custo">
                                    <i class="fas fa-calculator"></i>
                                </button>
                                
                                <button onclick="toggleStatus({{ $frota->id }}, {{ $frota->active ? 'false' : 'true' }})" 
                                        class="{{ $frota->active ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900' }}" 
                                        title="{{ $frota->active ? 'Desativar' : 'Ativar' }}">
                                    <i class="fas {{ $frota->active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                </button>
                                
                                <button onclick="deleteFrota({{ $frota->id }})" 
                                        class="text-red-600 hover:text-red-900" 
                                        title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center py-8">
                                <i class="fas fa-car text-4xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium text-gray-400">Nenhuma frota encontrada</p>
                                <p class="text-sm text-gray-400 mt-1">Comece criando uma nova frota</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    @if($frotas->hasPages())
        <div class="mt-6">
            {{ $frotas->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<script>
function toggleStatus(id, status) {
    if (confirm('Tem certeza que deseja alterar o status desta frota?')) {
        fetch(`/admin/frotas/${id}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ active: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                location.reload();
            } else {
                alert('Erro ao alterar status: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao alterar status da frota.');
        });
    }
}

function recalcularCusto(id) {
    if (confirm('Tem certeza que deseja recalcular o custo desta frota?')) {
        fetch(`/admin/frotas/${id}/recalcular-custo`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                location.reload();
            } else {
                alert('Erro ao recalcular custo: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao recalcular custo da frota.');
        });
    }
}

function deleteFrota(id) {
    if (confirm('Tem certeza que deseja excluir esta frota? Esta ação não pode ser desfeita.')) {
        fetch(`/admin/frotas/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                location.reload();
            } else {
                alert('Erro ao excluir frota: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao excluir frota.');
        });
    }
}
</script>
@endsection