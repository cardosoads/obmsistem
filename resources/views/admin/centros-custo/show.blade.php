@extends('layouts.admin')

@section('title', 'Detalhes do Centro de Custo')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detalhes do Centro de Custo</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.centros-custo.edit', $centroCusto->id) }}" 
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <button onclick="toggleStatus({{ $centroCusto->id }}, {{ $centroCusto->active ? 'false' : 'true' }})" 
                    class="{{ $centroCusto->active ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-{{ $centroCusto->active ? 'times-circle' : 'check-circle' }} mr-2"></i>
                {{ $centroCusto->active ? 'Desativar' : 'Ativar' }}
            </button>
            <a href="{{ route('admin.centros-custo.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações Básicas -->
        <div class="lg:col-span-2">
            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Informações Básicas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Código</label>
                        <p class="text-gray-900 font-medium">{{ $centroCusto->codigo }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Nome</label>
                        <p class="text-gray-900 font-medium">{{ $centroCusto->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Cliente OMIE ID</label>
                        <p class="text-gray-900">{{ $centroCusto->cliente_omie_id ?? 'Não informado' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Cliente Nome</label>
                        <p class="text-gray-900">{{ $centroCusto->cliente_nome ?? 'Não informado' }}</p>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $centroCusto->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $centroCusto->active ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>
                </div>
                
                @if($centroCusto->description)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Descrição</label>
                        <p class="text-gray-900">{{ $centroCusto->description }}</p>
                    </div>
                @endif
            </div>

            <!-- Informações da Base -->
            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Informações da Base</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Base</label>
                        <p class="text-gray-900 font-medium">{{ $centroCusto->base->name ?? 'Não informado' }}</p>
                        @if($centroCusto->base)
                            <p class="text-sm text-gray-500">{{ $centroCusto->base->city }}/{{ $centroCusto->base->uf }}</p>
                        @endif
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Regional</label>
                        <p class="text-gray-900">{{ $centroCusto->regional ?? 'Não informado' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Sigla</label>
                        <p class="text-gray-900">{{ $centroCusto->sigla ?? 'Não informado' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">UF</label>
                        <p class="text-gray-900">{{ $centroCusto->uf ?? 'Não informado' }}</p>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Supervisor</label>
                        <p class="text-gray-900">{{ $centroCusto->supervisor ?? 'Não informado' }}</p>
                    </div>
                </div>
            </div>

            <!-- Informações da Marca -->
            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Informações da Marca</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Marca</label>
                        <p class="text-gray-900 font-medium">{{ $centroCusto->marca->name ?? 'Não informado' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Mercado</label>
                        <p class="text-gray-900">{{ $centroCusto->mercado ?? 'Não informado' }}</p>
                    </div>
                </div>
            </div>

            <!-- Datas -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Informações do Sistema</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Criado em</label>
                        <p class="text-gray-900">{{ $centroCusto->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Última atualização</label>
                        <p class="text-gray-900">{{ $centroCusto->updated_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estatísticas -->
        <div>
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Estatísticas</h3>
                
                <div class="space-y-4">
                    <div class="bg-white p-4 rounded-lg border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total de Orçamentos</p>
                                <p class="text-2xl font-bold text-blue-600">{{ $centroCusto->orcamentos_count ?? 0 }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <i class="fas fa-file-invoice text-blue-600"></i>
                            </div>
                        </div>
                    </div>
                    
                    @if(isset($centroCusto->orcamentos_count) && $centroCusto->orcamentos_count > 0)
                        <div class="bg-white p-4 rounded-lg border">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Orçamentos Ativos</p>
                                    <p class="text-2xl font-bold text-green-600">{{ $centroCusto->orcamentos_ativos_count ?? 0 }}</p>
                                </div>
                                <div class="p-3 bg-green-100 rounded-full">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white p-4 rounded-lg border">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Valor Total</p>
                                    <p class="text-2xl font-bold text-purple-600">R$ {{ number_format($centroCusto->valor_total_orcamentos ?? 0, 2, ',', '.') }}</p>
                                </div>
                                <div class="p-3 bg-purple-100 rounded-full">
                                    <i class="fas fa-dollar-sign text-purple-600"></i>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Orçamentos Relacionados -->
    @if(isset($orcamentos) && $orcamentos->count() > 0)
        <div class="mt-6">
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Orçamentos Relacionados</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Número</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Valor</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($orcamentos as $orcamento)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm font-medium text-gray-900">
                                        #{{ str_pad($orcamento->id, 6, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-900">
                                        {{ $orcamento->cliente_nome ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-900">
                                        R$ {{ number_format($orcamento->valor_total ?? 0, 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $orcamento->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $orcamento->active ? 'Ativo' : 'Inativo' }}
                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-500">
                                        {{ $orcamento->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('admin.orcamentos.show', $orcamento->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 text-sm">
                                            Ver detalhes
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($orcamentos->hasPages())
                    <div class="mt-4">
                        {{ $orcamentos->links() }}
                    </div>
                @endif
            </div>
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
            body: JSON.stringify({ active: status })
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
</script>
@endsection