@extends('layouts.admin')

@section('title', 'Impostos')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Impostos</h1>
        <a href="{{ route('admin.impostos.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-plus mr-2"></i>Novo Imposto
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <form method="GET" action="{{ route('admin.impostos.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Buscar por nome ou descrição..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <select name="status" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos os Status</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Ativo</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
            
            <div>
                <select name="tipo" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos os Tipos</option>
                    <option value="percentual" {{ request('tipo') == 'percentual' ? 'selected' : '' }}>Percentual</option>
                    <option value="fixo" {{ request('tipo') == 'fixo' ? 'selected' : '' }}>Valor Fixo</option>
                </select>
            </div>
            
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200">
                <i class="fas fa-search mr-2"></i>Filtrar
            </button>
            
            <a href="{{ route('admin.impostos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-200">
                <i class="fas fa-times mr-2"></i>Limpar
            </a>
        </form>
    </div>

    <!-- Tabela -->
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentual</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($impostos as $imposto)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $imposto->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ number_format($imposto->percentual, 2, ',', '.') }}%
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $imposto->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $imposto->active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.impostos.show', $imposto->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 transition duration-200" 
                                   title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.impostos.edit', $imposto->id) }}" 
                                   class="text-yellow-600 hover:text-yellow-900 transition duration-200" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="toggleStatus({{ $imposto->id }}, {{ $imposto->active ? 'false' : 'true' }})" 
                                        class="{{ $imposto->active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }} transition duration-200" 
                                        title="{{ $imposto->active ? 'Desativar' : 'Ativar' }}">
                                    <i class="fas {{ $imposto->active ? 'fa-times-circle' : 'fa-check-circle' }}"></i>
                                </button>
                                <button onclick="deleteImposto({{ $imposto->id }}, '{{ $imposto->name }}')" 
                                        class="text-red-600 hover:text-red-900 transition duration-200" 
                                        title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center py-8">
                                <i class="fas fa-percentage text-4xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium">Nenhum imposto encontrado</p>
                                <p class="text-sm">Comece criando seu primeiro imposto</p>
                                <a href="{{ route('admin.impostos.create') }}" 
                                   class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                                    <i class="fas fa-plus mr-2"></i>Criar Imposto
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    @if($impostos->hasPages())
        <div class="mt-6">
            {{ $impostos->links() }}
        </div>
    @endif
</div>

<!-- Scripts -->
<script>
function toggleStatus(id, newStatus) {
    if (confirm('Tem certeza que deseja alterar o status deste imposto?')) {
        fetch(`/admin/impostos/${id}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ ativo: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao alterar status: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao alterar status');
        });
    }
}

function deleteImposto(id, nome) {
    if (confirm(`Tem certeza que deseja excluir o imposto "${nome}"?\n\nEsta ação não pode ser desfeita.`)) {
        fetch(`/admin/impostos/${id}`, {
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
                alert('Erro ao excluir imposto: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao excluir imposto');
        });
    }
}
</script>
@endsection