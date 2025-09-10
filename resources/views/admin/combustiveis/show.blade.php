@extends('layouts.admin')

@section('title', 'Detalhes do Combustível')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detalhes do Combustível</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.combustiveis.edit', $combustivel->id) }}" 
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('admin.combustiveis.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <!-- Informações Básicas -->
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Informações Básicas</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">ID</label>
                <p class="text-lg font-semibold text-gray-900">#{{ $combustivel->id }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Base</label>
                <div>
                    <p class="text-lg font-semibold text-gray-900">{{ $combustivel->base->name ?? '-' }}</p>
                    <p class="text-sm text-gray-600">{{ $combustivel->base->uf ?? '-' }}</p>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Convênio</label>
                <p class="text-lg font-semibold text-gray-900">{{ $combustivel->convenio }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                    {{ $combustivel->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $combustivel->active ? 'Ativo' : 'Inativo' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Informações de Preço -->
    <div class="bg-blue-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-semibold text-blue-700 mb-4">Informações de Preço</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
            <div>
                <p class="text-sm text-blue-600 mb-2">Preço por Litro</p>
                <p class="text-3xl font-bold text-blue-900">R$ {{ number_format($combustivel->preco_litro, 3, ',', '.') }}</p>
            </div>
            
            <div>
                <p class="text-sm text-blue-600 mb-2">Preço Médio Geral</p>
                <p class="text-2xl font-bold text-blue-700">R$ {{ number_format($precoMedio, 3, ',', '.') }}</p>
            </div>
            
            <div>
                <p class="text-sm text-blue-600 mb-2">Menor Preço</p>
                <p class="text-2xl font-bold text-green-600">R$ {{ number_format($menorPreco, 3, ',', '.') }}</p>
            </div>
            
            <div>
                <p class="text-sm text-blue-600 mb-2">Maior Preço</p>
                <p class="text-2xl font-bold text-red-600">R$ {{ number_format($maiorPreco, 3, ',', '.') }}</p>
            </div>
        </div>
        
        @php
            $variacao = $precoMedio > 0 ? (($combustivel->preco_litro - $precoMedio) / $precoMedio) * 100 : 0;
        @endphp
        
        <div class="mt-6 text-center">
            <label class="block text-sm font-medium text-blue-600 mb-2">Variação em Relação à Média</label>
            @if($variacao > 0)
                <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-medium bg-red-100 text-red-800">
                    <i class="fas fa-arrow-up mr-2"></i>{{ number_format($variacao, 1, ',', '.') }}% acima da média
                </span>
            @elseif($variacao < 0)
                <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-medium bg-green-100 text-green-800">
                    <i class="fas fa-arrow-down mr-2"></i>{{ number_format(abs($variacao), 1, ',', '.') }}% abaixo da média
                </span>
            @else
                <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-medium bg-gray-100 text-gray-800">
                    <i class="fas fa-minus mr-2"></i>Preço na média
                </span>
            @endif
        </div>
    </div>

    <!-- Informações da Base -->
    @if($combustivel->base)
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Informações da Base</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Nome da Base</label>
                <p class="text-lg font-semibold text-gray-900">{{ $combustivel->base->name }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">UF</label>
                <p class="text-lg font-semibold text-gray-900">{{ $combustivel->base->uf }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Regional</label>
                <p class="text-lg font-semibold text-gray-900">{{ $combustivel->base->regional ?? '-' }}</p>
            </div>
        </div>
        
        <div class="mt-4">
            <a href="{{ route('admin.bases.show', $combustivel->base->id) }}" 
               class="inline-flex items-center text-blue-600 hover:text-blue-900">
                <i class="fas fa-external-link-alt mr-2"></i>Ver detalhes da base
            </a>
        </div>
    </div>
    @endif

    <!-- Outros Combustíveis na Mesma Base -->
    @if($outrosCombustiveis->count() > 0)
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Outros Combustíveis na Base {{ $combustivel->base->name ?? '' }} ({{ $outrosCombustiveis->count() }})</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Convênio
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Preço/Litro
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Diferença
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($outrosCombustiveis as $outro)
                        @php
                            $diferenca = $outro->preco_litro - $combustivel->preco_litro;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $outro->convenio }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 font-mono">
                                R$ {{ number_format($outro->preco_litro, 3, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($diferenca > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        +R$ {{ number_format($diferenca, 3, ',', '.') }}
                                    </span>
                                @elseif($diferenca < 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        R$ {{ number_format($diferenca, 3, ',', '.') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Igual
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $outro->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $outro->active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.combustiveis.show', $outro->id) }}" 
                                   class="text-blue-600 hover:text-blue-900" 
                                   title="Ver Combustível">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <div class="text-center py-8">
            <i class="fas fa-gas-pump text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-400">Único combustível na base</h3>
            <p class="text-sm text-gray-400 mt-1">Este é o único convênio de combustível cadastrado para esta base</p>
        </div>
    </div>
    @endif

    <!-- Informações de Auditoria -->
    <div class="bg-gray-50 p-6 rounded-lg">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Informações de Auditoria</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-600">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Criado em</label>
                <p class="text-gray-900">{{ $combustivel->created_at->format('d/m/Y H:i:s') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Última atualização</label>
                <p class="text-gray-900">{{ $combustivel->updated_at->format('d/m/Y H:i:s') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Ações Rápidas -->
<div class="bg-white rounded-lg shadow-md p-6 mt-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">Ações Rápidas</h3>
    <div class="flex flex-wrap gap-4">
        <button onclick="toggleStatus({{ $combustivel->id }}, {{ $combustivel->active ? 'false' : 'true' }})" 
                class="{{ $combustivel->active ? 'bg-orange-500 hover:bg-orange-600' : 'bg-green-500 hover:bg-green-600' }} text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas {{ $combustivel->active ? 'fa-toggle-off' : 'fa-toggle-on' }} mr-2"></i>
            {{ $combustivel->active ? 'Desativar' : 'Ativar' }}
        </button>
        
        <a href="{{ route('admin.bases.show', $combustivel->base->id ?? 0) }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200"
           {{ !$combustivel->base ? 'style="pointer-events: none; opacity: 0.5;"' : '' }}>
            <i class="fas fa-map-marker-alt mr-2"></i>Ver Base
        </a>
        
        <a href="{{ route('admin.combustiveis.create') }}?base_id={{ $combustivel->base_id }}" 
           class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-plus mr-2"></i>Novo Combustível na Base
        </a>
        
        <button onclick="deleteCombustivel({{ $combustivel->id }})" 
                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-trash mr-2"></i>Excluir
        </button>
    </div>
</div>

<!-- Gráfico de Comparação de Preços -->
<div class="bg-white rounded-lg shadow-md p-6 mt-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">Comparação de Preços</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <canvas id="precoChart" width="400" height="200"></canvas>
        </div>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-blue-50 rounded">
                <span class="text-sm font-medium text-blue-700">Preço Atual</span>
                <span class="text-lg font-bold text-blue-800">R$ {{ number_format($combustivel->preco_litro, 3, ',', '.') }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                <span class="text-sm font-medium text-gray-700">Preço Médio</span>
                <span class="text-lg font-bold text-gray-800">R$ {{ number_format($precoMedio, 3, ',', '.') }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-green-50 rounded">
                <span class="text-sm font-medium text-green-700">Menor Preço</span>
                <span class="text-lg font-bold text-green-800">R$ {{ number_format($menorPreco, 3, ',', '.') }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-red-50 rounded">
                <span class="text-sm font-medium text-red-700">Maior Preço</span>
                <span class="text-lg font-bold text-red-800">R$ {{ number_format($maiorPreco, 3, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfico de comparação de preços
const ctx = document.getElementById('precoChart').getContext('2d');
const precoChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Menor Preço', 'Preço Atual', 'Preço Médio', 'Maior Preço'],
        datasets: [{
            label: 'Preço por Litro (R$)',
            data: [
                {{ $menorPreco }},
                {{ $combustivel->preco_litro }},
                {{ $precoMedio }},
                {{ $maiorPreco }}
            ],
            backgroundColor: [
                '#10B981',
                '#3B82F6',
                '#6B7280',
                '#EF4444'
            ],
            borderColor: [
                '#059669',
                '#2563EB',
                '#4B5563',
                '#DC2626'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'R$ ' + context.parsed.y.toLocaleString('pt-BR', {minimumFractionDigits: 3});
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: false,
                ticks: {
                    callback: function(value) {
                        return 'R$ ' + value.toLocaleString('pt-BR', {minimumFractionDigits: 3});
                    }
                }
            }
        }
    }
});

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
                window.location.href = '{{ route("admin.combustiveis.index") }}';
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