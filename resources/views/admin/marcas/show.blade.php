@extends('layouts.admin')

@section('title', 'Detalhes da Marca')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detalhes da Marca</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.marcas.edit', $marca->id) }}" 
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <button onclick="toggleStatus({{ $marca->id }}, {{ $marca->active ? 'false' : 'true' }})" 
                    class="{{ $marca->active ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-{{ $marca->active ? 'times-circle' : 'check-circle' }} mr-2"></i>
                {{ $marca->active ? 'Desativar' : 'Ativar' }}
            </button>
            <a href="{{ route('admin.marcas.index') }}" 
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
                        <label class="block text-sm font-medium text-gray-500 mb-1">Marca</label>
                        <p class="text-gray-900 font-medium">{{ $marca->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Mercado</label>
                        <p class="text-gray-900 font-medium">{{ $marca->mercado }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $marca->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $marca->active ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Datas -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Informações do Sistema</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Criado em</label>
                        <p class="text-gray-900">{{ $marca->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Última atualização</label>
                        <p class="text-gray-900">{{ $marca->updated_at->format('d/m/Y H:i:s') }}</p>
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
    if (confirm('Tem certeza que deseja alterar o status desta marca?')) {
        fetch(`/admin/marcas/${id}/toggle-status`, {
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
</script>
@endsection