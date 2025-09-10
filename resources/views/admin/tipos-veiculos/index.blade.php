@extends('layouts.admin')

@section('title', 'Tipos de Veículos')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tipos de Veículos</h1>
        <a href="{{ route('admin.tipos-veiculos.create') }}" 
           class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-plus mr-2"></i>Novo Tipo de Veículo
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <form method="GET" action="{{ route('admin.tipos-veiculos.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Buscar por código ou descrição..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
            
            <div>
                <select name="tipo_combustivel" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Todos os Combustíveis</option>
                    <option value="Gasolina" {{ request('tipo_combustivel') == 'Gasolina' ? 'selected' : '' }}>Gasolina</option>
                    <option value="Etanol" {{ request('tipo_combustivel') == 'Etanol' ? 'selected' : '' }}>Etanol</option>
                    <option value="Diesel" {{ request('tipo_combustivel') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                    <option value="Flex" {{ request('tipo_combustivel') == 'Flex' ? 'selected' : '' }}>Flex</option>
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
            
            @if(request()->hasAny(['search', 'tipo_combustivel', 'status']))
                <a href="{{ route('admin.tipos-veiculos.index') }}" 
                   class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-md transition duration-200">
                    <i class="fas fa-times mr-2"></i>Limpar
                </a>
            @endif
        </form>
    </div>

    <!-- Tabela -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Código
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Descrição
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tipo Combustível
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Consumo (km/l)
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Frotas
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
                @forelse($tiposVeiculos as $tipo)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $tipo->codigo }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $tipo->descricao }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @switch($tipo->tipo_combustivel)
                                    @case('Gasolina')
                                        bg-blue-100 text-blue-800
                                        @break
                                    @case('Etanol')
                                        bg-green-100 text-green-800
                                        @break
                                    @case('Diesel')
                                        bg-yellow-100 text-yellow-800
                                        @break
                                    @case('Flex')
                                        bg-purple-100 text-purple-800
                                        @break
                                    @default
                                        bg-gray-100 text-gray-800
                                @endswitch">
                                {{ $tipo->tipo_combustivel }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-mono">{{ $tipo->consumo_formatado }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $tipo->frotas_count ?? 0 }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $tipo->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $tipo->active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('admin.tipos-veiculos.show', $tipo->id) }}" 
                                   class="text-blue-600 hover:text-blue-900" 
                                   title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('admin.tipos-veiculos.edit', $tipo->id) }}" 
                                   class="text-yellow-600 hover:text-yellow-900" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <button onclick="toggleStatus({{ $tipo->id }}, {{ $tipo->active ? 'false' : 'true' }})" 
                                        class="{{ $tipo->active ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900' }}" 
                                        title="{{ $tipo->active ? 'Desativar' : 'Ativar' }}">
                                    <i class="fas {{ $tipo->active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                </button>
                                
                                <button onclick="deleteTipo({{ $tipo->id }})" 
                                        class="text-red-600 hover:text-red-900" 
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
                                <i class="fas fa-car text-4xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium text-gray-400">Nenhum tipo de veículo encontrado</p>
                                <p class="text-sm text-gray-400 mt-1">Comece criando um novo tipo de veículo</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    @if($tiposVeiculos->hasPages())
        <div class="mt-6">
            {{ $tiposVeiculos->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<script>
function toggleStatus(id, status) {
    if (confirm('Tem certeza que deseja alterar o status deste tipo de veículo?')) {
        fetch(`/admin/tipos-veiculos/${id}/toggle-status`, {
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
            alert('Erro ao alterar status do tipo de veículo.');
        });
    }
}

function deleteTipo(id) {
    if (confirm('Tem certeza que deseja excluir este tipo de veículo? Esta ação não pode ser desfeita.')) {
        fetch(`/admin/tipos-veiculos/${id}`, {
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
                alert('Erro ao excluir tipo de veículo: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao excluir tipo de veículo.');
        });
    }
}
</script>
@endsection