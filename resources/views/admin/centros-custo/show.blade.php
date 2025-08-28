@extends('layouts.admin')

@section('title', 'Detalhes do Centro de Custo')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detalhes do Centro de Custo</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.centros-custo.edit', $centroCusto) }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
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
                        <p class="text-gray-900 font-medium bg-gray-100 px-3 py-2 rounded-md border">{{ $centroCusto->codigo }}</p>
                        <p class="text-xs text-gray-500 mt-1">Código único para identificação (somente leitura)</p>
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

            <!-- Dados da API Omie -->
            @if($centroCusto->omie_codigo || $centroCusto->omie_estrutura || $centroCusto->sincronizado_em)
            <div class="bg-blue-50 p-6 rounded-lg mb-6 border border-blue-200">
                <h3 class="text-lg font-semibold text-blue-700 mb-4">
                    <i class="fas fa-sync-alt mr-2"></i>Dados da API Omie
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Código Omie</label>
                        <p class="text-gray-900 font-medium">{{ $centroCusto->omie_codigo ?? 'Não sincronizado' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Estrutura Omie</label>
                        <p class="text-gray-900">{{ $centroCusto->omie_estrutura ?? 'Não sincronizado' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Status Omie</label>
                        <div class="flex items-center space-x-2">
                            @if($centroCusto->omie_inativo === null)
                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">
                                    Não sincronizado
                                </span>
                            @elseif($centroCusto->omie_inativo === 'S')
                                <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-600 rounded-full">
                                    <i class="fas fa-times-circle mr-1"></i>Inativo Omie
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-600 rounded-full">
                                    <i class="fas fa-check-circle mr-1"></i>Ativo Omie
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    @if($centroCusto->sincronizado_em)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Última Sincronização</label>
                        <p class="text-blue-600 font-medium">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $centroCusto->sincronizado_em->format('d/m/Y H:i:s') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

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
                    <!-- Removido: seção de orçamentos - orçamentos foram removidos do sistema -->

                </div>
            </div>
        </div>
    </div>

    <!-- Removido: seção de orçamentos relacionados - orçamentos foram removidos do sistema -->
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