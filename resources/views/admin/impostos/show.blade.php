@extends('layouts.admin')

@section('title', 'Detalhes do Imposto')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detalhes do Imposto</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.impostos.edit', $imposto->id) }}" 
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <button onclick="toggleStatus({{ $imposto->id }}, {{ $imposto->ativo ? 'false' : 'true' }})" 
                    class="{{ $imposto->ativo ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas {{ $imposto->ativo ? 'fa-times-circle' : 'fa-check-circle' }} mr-2"></i>
                {{ $imposto->ativo ? 'Desativar' : 'Ativar' }}
            </button>
            <a href="{{ route('admin.impostos.index') }}" 
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
                        <label class="block text-sm font-medium text-gray-500">Nome</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $imposto->nome }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium mt-1
                            {{ $imposto->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas {{ $imposto->ativo ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                            {{ $imposto->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tipo</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium mt-1
                            {{ $imposto->tipo === 'percentual' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                            <i class="fas {{ $imposto->tipo === 'percentual' ? 'fa-percentage' : 'fa-dollar-sign' }} mr-1"></i>
                            {{ $imposto->tipo === 'percentual' ? 'Percentual' : 'Valor Fixo' }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Valor</label>
                        <p class="text-lg font-semibold text-gray-900">
                            @if($imposto->tipo === 'percentual')
                                {{ number_format($imposto->valor, 2, ',', '.') }}%
                            @else
                                R$ {{ number_format($imposto->valor, 2, ',', '.') }}
                            @endif
                        </p>
                    </div>
                </div>
                
                @if($imposto->descricao)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-500">Descrição</label>
                        <p class="text-gray-900 mt-1">{{ $imposto->descricao }}</p>
                    </div>
                @endif
            </div>
            
            <!-- Datas -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Informações do Sistema</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Criado em</label>
                        <p class="text-gray-900">{{ $imposto->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Última atualização</label>
                        <p class="text-gray-900">{{ $imposto->updated_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Estatísticas -->
        <div>
            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Estatísticas</h3>
                <div class="space-y-4">
                    <div class="bg-white p-4 rounded-lg border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Orçamentos</p>
                                <p class="text-2xl font-bold text-blue-600">{{ $imposto->orcamentos_count ?? 0 }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <i class="fas fa-file-invoice text-blue-600"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-4 rounded-lg border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Orçamentos Ativos</p>
                                <p class="text-2xl font-bold text-green-600">{{ $imposto->orcamentos_ativos_count ?? 0 }}</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                        </div>
                    </div>
                    
                    @if($imposto->valor_total_aplicado)
                        <div class="bg-white p-4 rounded-lg border">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Valor Total Aplicado</p>
                                    <p class="text-lg font-bold text-purple-600">R$ {{ number_format($imposto->valor_total_aplicado, 2, ',', '.') }}</p>
                                </div>
                                <div class="p-3 bg-purple-100 rounded-full">
                                    <i class="fas fa-calculator text-purple-600"></i>
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
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Orçamentos que Utilizam este Imposto</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Número</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Valor Base</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Valor Imposto</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($orcamentos as $orcamento)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm font-medium text-gray-900">
                                        #{{ str_pad($orcamento->id, 6, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-900">
                                        {{ $orcamento->cliente_nome ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-900">
                                        R$ {{ number_format($orcamento->valor_base ?? 0, 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-900">
                                        @php
                                            $valorImposto = 0;
                                            if($imposto->tipo === 'percentual') {
                                                $valorImposto = ($orcamento->valor_base ?? 0) * ($imposto->valor / 100);
                                            } else {
                                                $valorImposto = $imposto->valor;
                                            }
                                        @endphp
                                        R$ {{ number_format($valorImposto, 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $orcamento->status === 'ativo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($orcamento->status ?? 'pendente') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-500">
                                        {{ $orcamento->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('admin.orcamentos.show', $orcamento->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 text-sm" 
                                           title="Ver detalhes">
                                            <i class="fas fa-eye"></i>
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
function toggleStatus(id, newStatus) {
    if (confirm('Tem certeza que deseja alterar o status deste imposto?')) {
        fetch(`/admin/impostos/${id}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ ativo: newStatus })
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
            alert('Erro ao alterar status');
        });
    }
}
</script>
@endsection