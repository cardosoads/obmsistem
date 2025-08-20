@extends('layouts.admin')

@section('title', 'Centros de Custo')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Centros de Custo</h1>
        <a href="{{ route('admin.centros-custo.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-plus mr-2"></i>Novo Centro de Custo
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <form method="GET" action="{{ route('admin.centros-custo.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Buscar por nome ou código..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="min-w-32">
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos os Status</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Ativo</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
            
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200">
                <i class="fas fa-search mr-2"></i>Filtrar
            </button>
            
            <a href="{{ route('admin.centros-custo.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-200">
                <i class="fas fa-times mr-2"></i>Limpar
            </a>
        </form>
    </div>

    <!-- Tabela -->
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Base</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marca</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($centrosCusto as $centroCusto)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $centroCusto->codigo }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $centroCusto->name }}</div>
                            @if($centroCusto->description)
                                <div class="text-xs text-gray-500">{{ Str::limit($centroCusto->description, 30) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($centroCusto->cliente_nome)
                                <div class="text-sm text-gray-900">{{ $centroCusto->cliente_nome }}</div>
                                @if($centroCusto->cliente_omie_id)
                                    <div class="text-xs text-gray-500">ID: {{ $centroCusto->cliente_omie_id }}</div>
                                @endif
                            @else
                                <span class="text-xs text-gray-400">Não informado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($centroCusto->base)
                                <div class="text-sm text-gray-900">{{ $centroCusto->base->name }}</div>
                                <div class="text-xs text-gray-500">{{ $centroCusto->base->city }}/{{ $centroCusto->base->uf }}</div>
                            @else
                                <span class="text-xs text-gray-400">Não informado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($centroCusto->marca)
                                <div class="text-sm text-gray-900">{{ $centroCusto->marca->name }}</div>
                                @if($centroCusto->mercado)
                                    <div class="text-xs text-gray-500">{{ $centroCusto->mercado }}</div>
                                @endif
                            @else
                                <span class="text-xs text-gray-400">Não informado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $centroCusto->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $centroCusto->active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.centros-custo.show', $centroCusto->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 transition duration-200" 
                                   title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.centros-custo.edit', $centroCusto->id) }}" 
                                   class="text-yellow-600 hover:text-yellow-900 transition duration-200" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="toggleStatus({{ $centroCusto->id }}, {{ $centroCusto->active ? 'false' : 'true' }})" 
                                        class="{{ $centroCusto->active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }} transition duration-200" 
                                        title="{{ $centroCusto->active ? 'Desativar' : 'Ativar' }}">
                                    <i class="fas fa-{{ $centroCusto->active ? 'times-circle' : 'check-circle' }}"></i>
                                </button>
                                <button onclick="deleteCentroCusto({{ $centroCusto->id }})" 
                                        class="text-red-600 hover:text-red-900 transition duration-200" 
                                        title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Nenhum centro de custo encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    @if($centrosCusto->hasPages())
        <div class="mt-6">
            {{ $centrosCusto->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<script>
function toggleStatus(id, status) {
    if (confirm('Tem certeza que deseja alterar o status deste centro de custo?')) {
        fetch(`/admin/centros-custo/${id}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ ativo: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao alterar status do centro de custo.');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao alterar status do centro de custo.');
        });
    }
}

function deleteCentroCusto(id) {
    if (confirm('Tem certeza que deseja excluir este centro de custo? Esta ação não pode ser desfeita.')) {
        fetch(`/admin/centros-custo/${id}`, {
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
                alert('Erro ao excluir centro de custo.');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao excluir centro de custo.');
        });
    }
}
</script>
@endsection