@extends('layouts.admin')

@section('title', 'Detalhes da Frota')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detalhes da Frota #{{ $frota->id }}</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.frotas.edit', $frota->id) }}" 
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('admin.frotas.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <!-- Informações Básicas -->
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Informações Básicas</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">ID da Frota</label>
                <p class="text-lg font-semibold text-gray-900">#{{ $frota->id }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Tipo de Veículo</label>
                <div>
                    <p class="text-lg font-semibold text-gray-900">{{ $frota->tipoVeiculo->codigo ?? '-' }}</p>
                    <p class="text-sm text-gray-600">{{ $frota->tipoVeiculo->descricao ?? '-' }}</p>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                    {{ $frota->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $frota->active ? 'Ativo' : 'Inativo' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Valores FIPE -->
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Valores FIPE</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Valor FIPE</label>
                <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($frota->fipe, 2, ',', '.') }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Percentual FIPE</label>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($frota->percentual_fipe, 1, ',', '.') }}%</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Valor FIPE Calculado</label>
                <p class="text-2xl font-bold text-green-600">R$ {{ number_format(($frota->fipe * $frota->percentual_fipe / 100), 2, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Custos Operacionais -->
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Custos Operacionais</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Aluguel do Carro</label>
                <p class="text-xl font-semibold text-gray-900">R$ {{ number_format($frota->aluguel_carro, 2, ',', '.') }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Rastreador</label>
                <p class="text-xl font-semibold text-gray-900">R$ {{ number_format($frota->rastreador, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Provisões -->
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Provisões</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Provisões para Avarias</label>
                <p class="text-xl font-semibold text-gray-900">R$ {{ number_format($frota->provisoes_avarias, 2, ',', '.') }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Provisão para Desmobilização</label>
                <p class="text-xl font-semibold text-gray-900">R$ {{ number_format($frota->provisao_desmobilizacao, 2, ',', '.') }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Provisão Diária RAC</label>
                <p class="text-xl font-semibold text-gray-900">R$ {{ number_format($frota->provisao_diaria_rac, 2, ',', '.') }}</p>
            </div>
        </div>
        
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="text-center">
                <label class="block text-sm font-medium text-gray-500 mb-1">Total de Provisões</label>
                <p class="text-2xl font-bold text-orange-600">
                    R$ {{ number_format(($frota->provisoes_avarias + $frota->provisao_desmobilizacao + $frota->provisao_diaria_rac), 2, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Resumo de Custos -->
    <div class="bg-blue-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-semibold text-blue-700 mb-4">Resumo de Custos</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
                <p class="text-sm text-blue-600 mb-2">Valor FIPE Calculado</p>
                <p class="text-xl font-bold text-blue-800">R$ {{ number_format(($frota->fipe * $frota->percentual_fipe / 100), 2, ',', '.') }}</p>
            </div>
            
            <div class="text-center">
                <p class="text-sm text-blue-600 mb-2">Custos Operacionais</p>
                <p class="text-xl font-bold text-blue-800">R$ {{ number_format(($frota->aluguel_carro + $frota->rastreador), 2, ',', '.') }}</p>
            </div>
            
            <div class="text-center">
                <p class="text-sm text-blue-600 mb-2">Total Provisões</p>
                <p class="text-xl font-bold text-blue-800">R$ {{ number_format(($frota->provisoes_avarias + $frota->provisao_desmobilizacao + $frota->provisao_diaria_rac), 2, ',', '.') }}</p>
            </div>
            
            <div class="text-center">
                <p class="text-sm text-blue-600 mb-2">Custo Total</p>
                <p class="text-3xl font-bold text-blue-900">R$ {{ number_format($frota->custo_total, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Informações do Tipo de Veículo -->
    @if($frota->tipoVeiculo)
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Informações do Tipo de Veículo</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Código</label>
                <p class="text-lg font-semibold text-gray-900">{{ $frota->tipoVeiculo->codigo }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Tipo de Combustível</label>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @switch($frota->tipoVeiculo->tipo_combustivel)
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
                    {{ $frota->tipoVeiculo->tipo_combustivel }}
                </span>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Consumo</label>
                <p class="text-lg font-semibold text-gray-900">{{ $frota->tipoVeiculo->consumo_formatado }}</p>
            </div>
        </div>
        
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-500 mb-1">Descrição</label>
            <p class="text-gray-900 bg-white p-3 rounded border">{{ $frota->tipoVeiculo->descricao }}</p>
        </div>
    </div>
    @endif

    <!-- Informações de Auditoria -->
    <div class="bg-gray-50 p-6 rounded-lg">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Informações de Auditoria</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-600">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Criado em</label>
                <p class="text-gray-900">{{ $frota->created_at->format('d/m/Y H:i:s') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Última atualização</label>
                <p class="text-gray-900">{{ $frota->updated_at->format('d/m/Y H:i:s') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Ações Rápidas -->
<div class="bg-white rounded-lg shadow-md p-6 mt-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">Ações Rápidas</h3>
    <div class="flex flex-wrap gap-4">
        <button onclick="toggleStatus({{ $frota->id }}, {{ $frota->active ? 'false' : 'true' }})" 
                class="{{ $frota->active ? 'bg-orange-500 hover:bg-orange-600' : 'bg-green-500 hover:bg-green-600' }} text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas {{ $frota->active ? 'fa-toggle-off' : 'fa-toggle-on' }} mr-2"></i>
            {{ $frota->active ? 'Desativar' : 'Ativar' }}
        </button>
        
        <button onclick="recalcularCusto({{ $frota->id }})" 
                class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-calculator mr-2"></i>Recalcular Custo
        </button>
        
        <a href="{{ route('admin.tipos-veiculos.show', $frota->tipoVeiculo->id ?? 0) }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200"
           {{ !$frota->tipoVeiculo ? 'style="pointer-events: none; opacity: 0.5;"' : '' }}>
            <i class="fas fa-car mr-2"></i>Ver Tipo de Veículo
        </a>
        
        <button onclick="deleteFrota({{ $frota->id }})" 
                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-trash mr-2"></i>Excluir
        </button>
    </div>
</div>

<!-- Gráfico de Composição de Custos -->
<div class="bg-white rounded-lg shadow-md p-6 mt-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">Composição de Custos</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <canvas id="custosChart" width="400" height="200"></canvas>
        </div>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-blue-50 rounded">
                <span class="text-sm font-medium text-blue-700">Valor FIPE ({{ number_format((($frota->fipe * $frota->percentual_fipe / 100) / $frota->custo_total) * 100, 1) }}%)</span>
                <span class="text-sm font-bold text-blue-800">R$ {{ number_format(($frota->fipe * $frota->percentual_fipe / 100), 2, ',', '.') }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-green-50 rounded">
                <span class="text-sm font-medium text-green-700">Aluguel ({{ number_format(($frota->aluguel_carro / $frota->custo_total) * 100, 1) }}%)</span>
                <span class="text-sm font-bold text-green-800">R$ {{ number_format($frota->aluguel_carro, 2, ',', '.') }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded">
                <span class="text-sm font-medium text-yellow-700">Rastreador ({{ number_format(($frota->rastreador / $frota->custo_total) * 100, 1) }}%)</span>
                <span class="text-sm font-bold text-yellow-800">R$ {{ number_format($frota->rastreador, 2, ',', '.') }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-orange-50 rounded">
                <span class="text-sm font-medium text-orange-700">Provisões ({{ number_format((($frota->provisoes_avarias + $frota->provisao_desmobilizacao + $frota->provisao_diaria_rac) / $frota->custo_total) * 100, 1) }}%)</span>
                <span class="text-sm font-bold text-orange-800">R$ {{ number_format(($frota->provisoes_avarias + $frota->provisao_desmobilizacao + $frota->provisao_diaria_rac), 2, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfico de composição de custos
const ctx = document.getElementById('custosChart').getContext('2d');
const custosChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Valor FIPE', 'Aluguel', 'Rastreador', 'Provisões'],
        datasets: [{
            data: [
                {{ ($frota->fipe * $frota->percentual_fipe / 100) }},
                {{ $frota->aluguel_carro }},
                {{ $frota->rastreador }},
                {{ ($frota->provisoes_avarias + $frota->provisao_desmobilizacao + $frota->provisao_diaria_rac) }}
            ],
            backgroundColor: [
                '#3B82F6',
                '#10B981',
                '#F59E0B',
                '#F97316'
            ],
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const value = context.parsed;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return context.label + ': R$ ' + value.toLocaleString('pt-BR', {minimumFractionDigits: 2}) + ' (' + percentage + '%)';
                    }
                }
            }
        }
    }
});

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
                window.location.href = '{{ route("admin.frotas.index") }}';
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