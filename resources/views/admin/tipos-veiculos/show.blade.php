@extends('layouts.admin')

@section('title', 'Detalhes do Tipo de Veículo')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detalhes do Tipo de Veículo</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.tipos-veiculos.edit', $tipoVeiculo->id) }}" 
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('admin.tipos-veiculos.index') }}" 
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
                <label class="block text-sm font-medium text-gray-500 mb-1">Código</label>
                <p class="text-lg font-semibold text-gray-900">{{ $tipoVeiculo->codigo }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Tipo de Combustível</label>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @switch($tipoVeiculo->tipo_combustivel)
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
                    {{ $tipoVeiculo->tipo_combustivel }}
                </span>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Consumo</label>
                <p class="text-lg font-semibold text-gray-900">{{ $tipoVeiculo->consumo_formatado }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                    {{ $tipoVeiculo->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $tipoVeiculo->status_formatado }}
                </span>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Frotas Vinculadas</label>
                <p class="text-lg font-semibold text-gray-900">{{ $tipoVeiculo->frotas->count() }}</p>
            </div>
        </div>
        
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-500 mb-1">Descrição</label>
            <p class="text-gray-900 bg-white p-3 rounded border">{{ $tipoVeiculo->descricao }}</p>
        </div>
    </div>

    <!-- Frotas Vinculadas -->
    @if($tipoVeiculo->frotas->count() > 0)
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Frotas Vinculadas ({{ $tipoVeiculo->frotas->count() }})</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            FIPE
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            % FIPE
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aluguel
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Custo Total
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
                    @foreach($tipoVeiculo->frotas as $frota)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                #{{ $frota->id }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                R$ {{ number_format($frota->fipe, 2, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($frota->percentual_fipe, 1, ',', '.') }}%
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                R$ {{ number_format($frota->aluguel_carro, 2, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900">
                                R$ {{ number_format($frota->custo_total, 2, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $frota->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $frota->active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.frotas.show', $frota->id) }}" 
                                   class="text-blue-600 hover:text-blue-900" 
                                   title="Ver Frota">
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
            <i class="fas fa-car text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-400">Nenhuma frota vinculada</h3>
            <p class="text-sm text-gray-400 mt-1">Este tipo de veículo ainda não possui frotas cadastradas</p>
            <a href="{{ route('admin.frotas.create') }}" 
               class="inline-flex items-center mt-4 bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-plus mr-2"></i>Cadastrar Frota
            </a>
        </div>
    </div>
    @endif

    <!-- Informações de Auditoria -->
    <div class="bg-gray-50 p-6 rounded-lg">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Informações de Auditoria</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-600">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Criado em</label>
                <p class="text-gray-900">{{ $tipoVeiculo->created_at->format('d/m/Y H:i:s') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Última atualização</label>
                <p class="text-gray-900">{{ $tipoVeiculo->updated_at->format('d/m/Y H:i:s') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Ações Rápidas -->
<div class="bg-white rounded-lg shadow-md p-6 mt-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">Ações Rápidas</h3>
    <div class="flex flex-wrap gap-4">
        <button onclick="toggleStatus({{ $tipoVeiculo->id }}, {{ $tipoVeiculo->active ? 'false' : 'true' }})" 
                class="{{ $tipoVeiculo->active ? 'bg-orange-500 hover:bg-orange-600' : 'bg-green-500 hover:bg-green-600' }} text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas {{ $tipoVeiculo->active ? 'fa-toggle-off' : 'fa-toggle-on' }} mr-2"></i>
            {{ $tipoVeiculo->active ? 'Desativar' : 'Ativar' }}
        </button>
        
        <a href="{{ route('admin.frotas.create') }}?tipo_veiculo_id={{ $tipoVeiculo->id }}" 
           class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-plus mr-2"></i>Nova Frota
        </a>
        
        <button onclick="deleteTipo({{ $tipoVeiculo->id }})" 
                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200"
                {{ $tipoVeiculo->frotas->count() > 0 ? 'disabled title="Não é possível excluir um tipo com frotas vinculadas"' : '' }}>
            <i class="fas fa-trash mr-2"></i>Excluir
        </button>
    </div>
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
                window.location.href = '{{ route("admin.tipos-veiculos.index") }}';
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