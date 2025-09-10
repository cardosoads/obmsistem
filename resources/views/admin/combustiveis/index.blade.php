@extends('layouts.admin')

@section('title', 'Combustíveis')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Combustíveis</h1>
        <a href="{{ route('admin.combustiveis.create') }}" 
           class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-plus mr-2"></i>Novo Combustível
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <form method="GET" action="{{ route('admin.combustiveis.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Buscar por convênio ou base..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
            
            <div>
                <select name="base_id" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Todas as Bases</option>
                    @foreach($bases as $base)
                        <option value="{{ $base->id }}" {{ request('base_id') == $base->id ? 'selected' : '' }}>
                            {{ $base->name }} - {{ $base->uf }}
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
            
            @if(request()->hasAny(['search', 'base_id', 'status']))
                <a href="{{ route('admin.combustiveis.index') }}" 
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
                    <i class="fas fa-gas-pump text-blue-500 text-2xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-blue-600">Total de Convênios</p>
                    <p class="text-2xl font-semibold text-blue-900">{{ $totalCombustiveis }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-green-50 p-4 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-600">Convênios Ativos</p>
                    <p class="text-2xl font-semibold text-green-900">{{ $combustiveisAtivos }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-yellow-50 p-4 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-dollar-sign text-yellow-500 text-2xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-yellow-600">Preço Médio</p>
                    <p class="text-2xl font-semibold text-yellow-900">R$ {{ number_format($precoMedio, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-purple-50 p-4 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-map-marker-alt text-purple-500 text-2xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-purple-600">Bases Atendidas</p>
                    <p class="text-2xl font-semibold text-purple-900">{{ $basesAtendidas }}</p>
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
                        Base
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Convênio
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Preço por Litro
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Variação do Preço
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
                @forelse($combustiveis as $combustivel)
                    @php
                        $variacao = $precoMedio > 0 ? (($combustivel->preco_litro - $precoMedio) / $precoMedio) * 100 : 0;
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <div class="font-medium">{{ $combustivel->base->name ?? '-' }}</div>
                                <div class="text-gray-500">{{ $combustivel->base->uf ?? '-' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $combustivel->convenio }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">R$ {{ number_format($combustivel->preco_litro, 2, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($variacao > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-arrow-up mr-1"></i>+{{ number_format($variacao, 1, ',', '.') }}%
                                </span>
                            @elseif($variacao < 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-arrow-down mr-1"></i>{{ number_format($variacao, 1, ',', '.') }}%
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-minus mr-1"></i>0%
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $combustivel->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $combustivel->active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('admin.combustiveis.show', $combustivel->id) }}" 
                                   class="text-blue-600 hover:text-blue-900" 
                                   title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('admin.combustiveis.edit', $combustivel->id) }}" 
                                   class="text-yellow-600 hover:text-yellow-900" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <button onclick="toggleStatus({{ $combustivel->id }}, {{ $combustivel->active ? 'false' : 'true' }})" 
                                        class="{{ $combustivel->active ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900' }}" 
                                        title="{{ $combustivel->active ? 'Desativar' : 'Ativar' }}">
                                    <i class="fas {{ $combustivel->active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                </button>
                                
                                <button onclick="deleteCombustivel({{ $combustivel->id }})" 
                                        class="text-red-600 hover:text-red-900" 
                                        title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center py-8">
                                <i class="fas fa-gas-pump text-4xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium text-gray-400">Nenhum combustível encontrado</p>
                                <p class="text-sm text-gray-400 mt-1">Comece criando um novo convênio de combustível</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    @if($combustiveis->hasPages())
        <div class="mt-6">
            {{ $combustiveis->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Análise de Preços -->
@if($combustiveis->count() > 0)
<div class="bg-white rounded-lg shadow-md p-6 mt-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">Análise de Preços por Base</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($bases as $base)
            @php
                $combustiveisBase = $combustiveis->where('base_id', $base->id);
                $precoMenor = $combustiveisBase->min('preco_litro');
                $precoMaior = $combustiveisBase->max('preco_litro');
                $precoMedioBase = $combustiveisBase->avg('preco_litro');
            @endphp
            @if($combustiveisBase->count() > 0)
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-semibold text-gray-800 mb-2">{{ $base->name }} - {{ $base->uf }}</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Convênios:</span>
                        <span class="font-medium">{{ $combustiveisBase->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Menor preço:</span>
                        <span class="font-medium text-green-600">R$ {{ number_format($precoMenor, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Maior preço:</span>
                        <span class="font-medium text-red-600">R$ {{ number_format($precoMaior, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Preço médio:</span>
                        <span class="font-medium text-blue-600">R$ {{ number_format($precoMedioBase, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
</div>
@endif

<script>
function toggleStatus(id, status) {
    if (confirm('Tem certeza que deseja alterar o status deste combustível?')) {
        fetch(`/admin/combustiveis/${id}/toggle-status`, {
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
            alert('Erro ao alterar status do combustível.');
        });
    }
}

function deleteCombustivel(id) {
    if (confirm('Tem certeza que deseja excluir este combustível? Esta ação não pode ser desfeita.')) {
        fetch(`/admin/combustiveis/${id}`, {
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
                alert('Erro ao excluir combustível: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao excluir combustível.');
        });
    }
}
</script>
@endsection