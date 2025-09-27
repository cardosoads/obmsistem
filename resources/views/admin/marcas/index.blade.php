@extends('layouts.admin')

@section('title', 'Marcas')

@section('content')
<!-- Header com gradiente -->
<div class="rounded-xl shadow-lg mb-6" style="background: linear-gradient(135deg, #1E3951 0%, #2A4A66 100%);">
    <div class="p-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(248, 171, 20, 0.2);">
                    <i class="fas fa-tags text-2xl" style="color: #F8AB14;"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Marcas</h1>
                    <p class="text-blue-100">Gerencie as marcas e mercados do sistema</p>
                </div>
            </div>
            <a href="{{ route('admin.marcas.create') }}" 
               class="inline-flex items-center px-6 py-3 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" 
               style="background: #F8AB14; hover:background: #E09A12;">
                <i class="fas fa-plus mr-2"></i>Nova Marca
            </a>
        </div>
    </div>
</div>

<!-- Card principal -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">

    <!-- Filtros -->
    <div class="p-6 border-b" style="background: linear-gradient(135deg, rgba(30, 57, 81, 0.02), rgba(30, 57, 81, 0.05));">
        <form method="GET" action="{{ route('admin.marcas.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Buscar por marca ou mercado..."
                       class="w-full px-4 py-3 border rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:border-transparent" 
                       style="border-color: rgba(30, 57, 81, 0.2); focus:ring-color: #1E3951;">
            </div>
            
            <div>
                <select name="status" class="px-4 py-3 border rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:border-transparent" 
                        style="border-color: rgba(30, 57, 81, 0.2); focus:ring-color: #1E3951;">
                    <option value="">Todos os Status</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Ativo</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
            
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" 
                    style="background: #1E3951;">
                <i class="fas fa-search mr-2"></i>Filtrar
            </button>
            
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.marcas.index') }}" 
                   class="inline-flex items-center px-6 py-3 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg" 
                   style="background: #F8AB14;">
                    <i class="fas fa-times mr-2"></i>Limpar
                </a>
            @endif
        </form>
    </div>

    <!-- Tabela -->
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead style="background: linear-gradient(135deg, #1E3951 0%, #2A4A66 100%);">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                        Marca
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                        Mercado
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                        Criado em
                    </th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse($marcas as $marca)
                    <tr class="border-b transition-all duration-300 hover:shadow-md" style="border-color: rgba(30, 57, 81, 0.1); hover:background: rgba(30, 57, 81, 0.02);">
                        <td class="px-6 py-6 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-4 p-2" style="background: rgba(30, 57, 81, 0.1);">
                                    <i class="fas fa-tags text-lg" style="color: #1E3951;"></i>
                                </div>
                                <div class="text-sm font-semibold" style="color: #1E3951;">{{ $marca->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap">
                            <div class="text-sm text-gray-700 px-2 py-1">{{ $marca->mercado }}</div>
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-semibold
                                {{ $marca->active ? 'text-white' : 'text-white' }}" 
                                style="background: {{ $marca->active ? '#F8AB14' : '#1E3951' }};">
                                <i class="fas {{ $marca->active ? 'fa-check-circle' : 'fa-times-circle' }} mr-2"></i>
                                {{ $marca->active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap">
                            <div class="text-sm text-gray-700 px-2 py-1">{{ $marca->created_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap text-right">
                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('admin.marcas.show', $marca->id) }}" 
                                   class="inline-flex items-center justify-center w-10 h-10 rounded-lg transition-all duration-300 hover:shadow-md p-2" 
                                   style="background: rgba(30, 57, 81, 0.1); color: #1E3951; hover:background: #1E3951; hover:color: white;"
                                   title="Visualizar">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                
                                <a href="{{ route('admin.marcas.edit', $marca->id) }}" 
                                   class="inline-flex items-center justify-center w-10 h-10 rounded-lg transition-all duration-300 hover:shadow-md p-2" 
                                   style="background: rgba(248, 171, 20, 0.1); color: #F8AB14; hover:background: #F8AB14; hover:color: white;"
                                   title="Editar">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                
                                <button onclick="toggleStatus({{ $marca->id }}, {{ $marca->active ? 'false' : 'true' }})" 
                                        class="inline-flex items-center justify-center w-10 h-10 rounded-lg transition-all duration-300 hover:shadow-md p-2" 
                                        style="background: {{ $marca->active ? 'rgba(248, 171, 20, 0.1)' : 'rgba(30, 57, 81, 0.1)' }}; color: {{ $marca->active ? '#F8AB14' : '#1E3951' }};"
                                        title="{{ $marca->active ? 'Desativar' : 'Ativar' }}">
                                    <i class="fas {{ $marca->active ? 'fa-toggle-on' : 'fa-toggle-off' }} text-sm"></i>
                                </button>
                                
                                <button onclick="deleteMarca({{ $marca->id }})" 
                                        class="inline-flex items-center justify-center w-10 h-10 rounded-lg transition-all duration-300 hover:shadow-md p-2" 
                                        style="background: rgba(30, 57, 81, 0.1); color: #1E3951; hover:background: #1E3951; hover:color: white;"
                                        title="Excluir">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center py-8">
                                <div class="w-20 h-20 rounded-full flex items-center justify-center mb-6" style="background: rgba(30, 57, 81, 0.1);">
                                    <i class="fas fa-tags text-3xl" style="color: #1E3951;"></i>
                                </div>
                                <p class="text-lg font-semibold mb-2" style="color: #1E3951;">Nenhuma marca encontrada</p>
                                <p class="text-sm text-gray-500 mb-6">Comece criando uma nova marca para o sistema</p>
                                <a href="{{ route('admin.marcas.create') }}" 
                                   class="inline-flex items-center px-6 py-3 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" 
                                   style="background: #F8AB14;">
                                    <i class="fas fa-plus mr-2"></i>Criar Nova Marca
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    @if($marcas->hasPages())
        <div class="p-6 border-t" style="border-color: rgba(30, 57, 81, 0.1); background: linear-gradient(135deg, rgba(30, 57, 81, 0.02), rgba(30, 57, 81, 0.05));">
            <div class="flex justify-center">
                {{ $marcas->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>

<script>
function toggleStatus(id, status) {
    if (confirm('Tem certeza que deseja alterar o status desta marca?')) {
        fetch(`/admin/marcas/${id}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ active: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao alterar status da marca.');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao alterar status da marca.');
        });
    }
}

function deleteMarca(id) {
    if (confirm('Tem certeza que deseja excluir esta marca? Esta ação não pode ser desfeita.')) {
        fetch(`/admin/marcas/${id}`, {
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
                alert('Erro ao excluir marca.');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao excluir marca.');
        });
    }
}
</script>
@endsection