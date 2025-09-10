@extends('layouts.admin')

@section('title', 'Detalhes do Funcionário')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detalhes do Funcionário</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.recursos-humanos.edit', $recursoHumano->id) }}" 
               class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('admin.recursos-humanos.index') }}" 
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
                <label class="block text-sm font-medium text-gray-500 mb-1">Cargo</label>
                <p class="text-lg font-semibold text-gray-900">{{ $recursoHumano->cargo }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Tipo de Contratação</label>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @switch($recursoHumano->tipo_contratacao)
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
                    {{ $recursoHumano->tipo_contratacao }}
                </span>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                    {{ $recursoHumano->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    <i class="fas {{ $recursoHumano->active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                    {{ $recursoHumano->active ? 'Ativo' : 'Inativo' }}
                </span>
            </div>
            
            @if($recursoHumano->base)
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Base</label>
                <p class="text-lg text-gray-900">{{ $recursoHumano->base->name }} - {{ $recursoHumano->base->uf }}</p>
            </div>
            @endif
            
            @if($recursoHumano->base_salarial)
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Base Salarial</label>
                <p class="text-lg text-gray-900">{{ $recursoHumano->base_salarial }}</p>
            </div>
            @endif
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Data de Cadastro</label>
                <p class="text-lg text-gray-900">{{ $recursoHumano->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Composição Salarial -->
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Composição Salarial</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-4 rounded-lg border">
                <label class="block text-sm font-medium text-gray-500 mb-1">Salário Base</label>
                <p class="text-xl font-bold text-blue-600">R$ {{ number_format($recursoHumano->salario_base, 2, ',', '.') }}</p>
            </div>
            
            @if($recursoHumano->insalubridade > 0)
            <div class="bg-white p-4 rounded-lg border">
                <label class="block text-sm font-medium text-gray-500 mb-1">Insalubridade</label>
                <p class="text-xl font-bold text-orange-600">R$ {{ number_format($recursoHumano->insalubridade, 2, ',', '.') }}</p>
            </div>
            @endif
            
            @if($recursoHumano->periculosidade > 0)
            <div class="bg-white p-4 rounded-lg border">
                <label class="block text-sm font-medium text-gray-500 mb-1">Periculosidade</label>
                <p class="text-xl font-bold text-red-600">R$ {{ number_format($recursoHumano->periculosidade, 2, ',', '.') }}</p>
            </div>
            @endif
            
            @if($recursoHumano->horas_extras > 0)
            <div class="bg-white p-4 rounded-lg border">
                <label class="block text-sm font-medium text-gray-500 mb-1">Horas Extras</label>
                <p class="text-xl font-bold text-purple-600">R$ {{ number_format($recursoHumano->horas_extras, 2, ',', '.') }}</p>
            </div>
            @endif
            
            @if($recursoHumano->adicional_noturno > 0)
            <div class="bg-white p-4 rounded-lg border">
                <label class="block text-sm font-medium text-gray-500 mb-1">Adicional Noturno</label>
                <p class="text-xl font-bold text-indigo-600">R$ {{ number_format($recursoHumano->adicional_noturno, 2, ',', '.') }}</p>
            </div>
            @endif
            
            @if($recursoHumano->extras > 0)
            <div class="bg-white p-4 rounded-lg border">
                <label class="block text-sm font-medium text-gray-500 mb-1">Extras</label>
                <p class="text-xl font-bold text-teal-600">R$ {{ number_format($recursoHumano->extras, 2, ',', '.') }}</p>
            </div>
            @endif
            
            @if($recursoHumano->vale_transporte > 0)
            <div class="bg-white p-4 rounded-lg border">
                <label class="block text-sm font-medium text-gray-500 mb-1">Vale Transporte</label>
                <p class="text-xl font-bold text-green-600">R$ {{ number_format($recursoHumano->vale_transporte, 2, ',', '.') }}</p>
            </div>
            @endif
            
            @if($recursoHumano->beneficios > 0)
            <div class="bg-white p-4 rounded-lg border">
                <label class="block text-sm font-medium text-gray-500 mb-1">Benefícios</label>
                <p class="text-xl font-bold text-cyan-600">R$ {{ number_format($recursoHumano->beneficios, 2, ',', '.') }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Resumo Financeiro -->
    <div class="bg-gradient-to-r from-teal-50 to-cyan-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Resumo Financeiro</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-lg border-l-4 border-blue-500 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Salário Bruto</p>
                        <p class="text-2xl font-bold text-blue-600">
                            R$ {{ number_format($recursoHumano->salario_bruto, 2, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-wallet text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg border-l-4 border-orange-500 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Encargos Sociais</p>
                        <p class="text-2xl font-bold text-orange-600">
                            R$ {{ number_format($recursoHumano->encargos_sociais, 2, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500">{{ number_format($recursoHumano->percentual_encargos, 2, ',', '.') }}%</p>
                    </div>
                    <div class="p-3 bg-orange-100 rounded-full">
                        <i class="fas fa-percentage text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg border-l-4 border-green-500 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Benefícios</p>
                        <p class="text-2xl font-bold text-green-600">
                            R$ {{ number_format($recursoHumano->beneficios, 2, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-gift text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg border-l-4 border-teal-500 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Custo Total</p>
                        <p class="text-2xl font-bold text-teal-600">
                            R$ {{ number_format($recursoHumano->custo_total_mao_obra, 2, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-3 bg-teal-100 rounded-full">
                        <i class="fas fa-calculator text-teal-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Breakdown de Custos -->
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Breakdown de Custos</h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                <span class="text-gray-600">Salário Base</span>
                <span class="font-semibold">R$ {{ number_format($recursoHumano->salario_base, 2, ',', '.') }}</span>
            </div>
            
            @if($recursoHumano->insalubridade > 0)
            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                <span class="text-gray-600">+ Insalubridade</span>
                <span class="font-semibold text-orange-600">R$ {{ number_format($recursoHumano->insalubridade, 2, ',', '.') }}</span>
            </div>
            @endif
            
            @if($recursoHumano->periculosidade > 0)
            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                <span class="text-gray-600">+ Periculosidade</span>
                <span class="font-semibold text-red-600">R$ {{ number_format($recursoHumano->periculosidade, 2, ',', '.') }}</span>
            </div>
            @endif
            
            @if($recursoHumano->horas_extras > 0)
            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                <span class="text-gray-600">+ Horas Extras</span>
                <span class="font-semibold text-purple-600">R$ {{ number_format($recursoHumano->horas_extras, 2, ',', '.') }}</span>
            </div>
            @endif
            
            @if($recursoHumano->adicional_noturno > 0)
            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                <span class="text-gray-600">+ Adicional Noturno</span>
                <span class="font-semibold text-indigo-600">R$ {{ number_format($recursoHumano->adicional_noturno, 2, ',', '.') }}</span>
            </div>
            @endif
            
            @if($recursoHumano->extras > 0)
            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                <span class="text-gray-600">+ Extras</span>
                <span class="font-semibold text-teal-600">R$ {{ number_format($recursoHumano->extras, 2, ',', '.') }}</span>
            </div>
            @endif
            
            @if($recursoHumano->vale_transporte > 0)
            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                <span class="text-gray-600">+ Vale Transporte</span>
                <span class="font-semibold text-green-600">R$ {{ number_format($recursoHumano->vale_transporte, 2, ',', '.') }}</span>
            </div>
            @endif
            
            <div class="flex justify-between items-center py-2 border-b-2 border-gray-300 bg-blue-50 px-3 rounded">
                <span class="font-semibold text-blue-700">= Salário Bruto</span>
                <span class="font-bold text-blue-700 text-lg">R$ {{ number_format($recursoHumano->salario_bruto, 2, ',', '.') }}</span>
            </div>
            
            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                <span class="text-gray-600">+ Encargos Sociais ({{ number_format($recursoHumano->percentual_encargos, 2, ',', '.') }}%)</span>
                <span class="font-semibold text-orange-600">R$ {{ number_format($recursoHumano->encargos_sociais, 2, ',', '.') }}</span>
            </div>
            
            @if($recursoHumano->beneficios > 0)
            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                <span class="text-gray-600">+ Benefícios</span>
                <span class="font-semibold text-green-600">R$ {{ number_format($recursoHumano->beneficios, 2, ',', '.') }}</span>
            </div>
            @endif
            
            <div class="flex justify-between items-center py-3 border-t-2 border-teal-300 bg-teal-50 px-3 rounded">
                <span class="font-bold text-teal-700 text-lg">= CUSTO TOTAL</span>
                <span class="font-bold text-teal-700 text-xl">R$ {{ number_format($recursoHumano->custo_total_mao_obra, 2, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Ações -->
    <div class="flex justify-end space-x-4">
        <button onclick="toggleStatus({{ $recursoHumano->id }}, {{ $recursoHumano->active ? 'false' : 'true' }})" 
                class="{{ $recursoHumano->active ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-6 py-2 rounded-lg transition duration-200">
            <i class="fas {{ $recursoHumano->active ? 'fa-toggle-off' : 'fa-toggle-on' }} mr-2"></i>
            {{ $recursoHumano->active ? 'Desativar' : 'Ativar' }}
        </button>
        
        <button onclick="deleteRH({{ $recursoHumano->id }})" 
                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition duration-200">
            <i class="fas fa-trash mr-2"></i>Excluir
        </button>
    </div>
</div>

<script>
function toggleStatus(id, status) {
    if (confirm('Tem certeza que deseja alterar o status deste funcionário?')) {
        fetch(`/admin/recursos-humanos/${id}/status`, {
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
            if (data.success) {
                window.location.href = '/admin/recursos-humanos';
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