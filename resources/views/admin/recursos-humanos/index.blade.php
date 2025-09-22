@extends('layouts.admin')

@section('title', 'Recursos Humanos')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Recursos Humanos</h1>
        <a href="{{ route('admin.recursos-humanos.create') }}" 
           class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-plus mr-2"></i>Novo Funcionário
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <form method="GET" action="{{ route('admin.recursos-humanos.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Buscar por cargo, tipo de contratação ou base..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
            
            <div>
                <select name="tipo_contratacao" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Todos os Tipos</option>
                    <option value="CLT" {{ request('tipo_contratacao') == 'CLT' ? 'selected' : '' }}>CLT</option>
                    <option value="PJ" {{ request('tipo_contratacao') == 'PJ' ? 'selected' : '' }}>PJ</option>
                    <option value="Terceirizado" {{ request('tipo_contratacao') == 'Terceirizado' ? 'selected' : '' }}>Terceirizado</option>
                    <option value="Temporário" {{ request('tipo_contratacao') == 'Temporário' ? 'selected' : '' }}>Temporário</option>
                    <option value="Estagiário" {{ request('tipo_contratacao') == 'Estagiário' ? 'selected' : '' }}>Estagiário</option>
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
            
            @if(request()->hasAny(['search', 'tipo_contratacao', 'status']))
                <a href="{{ route('admin.recursos-humanos.index') }}" 
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
                        Cargo
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tipo Contratação
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Base
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Salário Base
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Custo Total Mão de Obra /mês
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
                @forelse($recursosHumanos as $rh)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $rh->cargo }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @switch($rh->tipo_contratacao)
                                    @case('CLT')
                                        bg-blue-100 text-blue-800
                                        @break
                                    @case('PJ')
                                        bg-purple-100 text-purple-800
                                        @break
                                    @case('Terceirizado')
                                        bg-orange-100 text-orange-800
                                        @break
                                    @case('Temporário')
                                        bg-yellow-100 text-yellow-800
                                        @break
                                    @case('Estagiário')
                                        bg-green-100 text-green-800
                                        @break
                                    @default
                                        bg-gray-100 text-gray-800
                                @endswitch">
                                {{ $rh->tipo_contratacao }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $rh->base->name ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-mono">R$ {{ number_format($rh->salario_base, 2, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">R$ {{ number_format($rh->custo_total_mao_obra, 2, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $rh->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $rh->active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('admin.recursos-humanos.show', $rh->id) }}" 
                                   class="text-blue-600 hover:text-blue-900" 
                                   title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('admin.recursos-humanos.edit', $rh->id) }}" 
                                   class="text-yellow-600 hover:text-yellow-900" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <button onclick="toggleStatus({{ $rh->id }}, {{ $rh->active ? 'false' : 'true' }})" 
                                        class="{{ $rh->active ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900' }}" 
                                        title="{{ $rh->active ? 'Desativar' : 'Ativar' }}">
                                    <i class="fas {{ $rh->active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                </button>
                                
                                <button onclick="deleteRH({{ $rh->id }})" 
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
                                <i class="fas fa-user-tie text-4xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium text-gray-400">Nenhum funcionário encontrado</p>
                                <p class="text-sm text-gray-400 mt-1">Comece criando um novo cadastro de RH</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    @if($recursosHumanos->hasPages())
        <div class="mt-6">
            {{ $recursosHumanos->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<script>
function toggleStatus(id, status) {
    if (confirm('Tem certeza que deseja alterar o status deste funcionário?')) {
        fetch(`/admin/recursos-humanos/${id}/toggle-status`, {
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
            alert('Erro ao alterar status do funcionário.');
        });
    }
}

function deleteRH(id) {
    if (confirm('Tem certeza que deseja excluir este funcionário? Esta ação não pode ser desfeita.')) {
        fetch(`/admin/recursos-humanos/${id}`, {
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
                alert('Erro ao excluir funcionário: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao excluir funcionário.');
        });
    }
}
</script>
@endsection