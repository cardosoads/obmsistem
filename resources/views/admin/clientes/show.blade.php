@extends('layouts.admin')

@section('title', 'Cliente: ' . $cliente->name)
@section('page-title', 'Detalhes do Cliente')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12">
                        <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-lg font-medium text-gray-700">{{ substr($cliente->name, 0, 2) }}</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $cliente->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $cliente->email }}</p>
                    </div>
                    <div class="ml-4">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $cliente->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $cliente->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.clientes.edit', $cliente) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-edit mr-2"></i>
                        Editar
                    </a>
                    
                    <button onclick="toggleStatus({{ $cliente->id }})" 
                            class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-toggle-{{ $cliente->ativo ? 'on' : 'off' }} mr-2"></i>
                        {{ $cliente->ativo ? 'Desativar' : 'Ativar' }}
                    </button>
                    
                    <a href="{{ route('admin.clientes.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações do Cliente -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h4 class="text-lg font-medium text-gray-900">Informações do Cliente</h4>
                </div>
                
                <div class="p-6">
                    <!-- Dados Básicos -->
                    <div class="mb-6">
                        <h5 class="text-md font-medium text-gray-900 mb-4">Dados Básicos</h5>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Nome Completo</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $cliente->name }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">E-mail</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $cliente->email }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Documento</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $cliente->documento }} ({{ strtoupper($cliente->tipo_documento) }})</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Status</label>
                                <p class="mt-1">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $cliente->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $cliente->ativo ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contato -->
                    <div class="mb-6">
                        <h5 class="text-md font-medium text-gray-900 mb-4">Contato</h5>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Telefone</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $cliente->telefone ?: 'Não informado' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Celular</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $cliente->celular ?: 'Não informado' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Endereço -->
                    @if($cliente->endereco || $cliente->cidade || $cliente->estado)
                    <div class="mb-6">
                        <h5 class="text-md font-medium text-gray-900 mb-4">Endereço</h5>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($cliente->cep)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">CEP</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $cliente->cep }}</p>
                            </div>
                            @endif
                            
                            @if($cliente->endereco)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Endereço</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $cliente->endereco }}
                                    @if($cliente->numero), {{ $cliente->numero }}@endif
                                    @if($cliente->complemento) - {{ $cliente->complemento }}@endif
                                </p>
                            </div>
                            @endif
                            
                            @if($cliente->bairro)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Bairro</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $cliente->bairro }}</p>
                            </div>
                            @endif
                            
                            @if($cliente->cidade)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Cidade/Estado</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $cliente->cidade }}@if($cliente->estado) - {{ $cliente->estado }}@endif</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    <!-- Observações -->
                    @if($cliente->observacoes)
                    <div class="mb-6">
                        <h5 class="text-md font-medium text-gray-900 mb-4">Observações</h5>
                        <p class="text-sm text-gray-900 whitespace-pre-line">{{ $cliente->observacoes }}</p>
                    </div>
                    @endif
                    
                    <!-- Datas -->
                    <div>
                        <h5 class="text-md font-medium text-gray-900 mb-4">Informações do Sistema</h5>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Criado em</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $cliente->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Última atualização</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $cliente->updated_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Orçamentos do Cliente -->
        <div>
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h4 class="text-lg font-medium text-gray-900">Orçamentos</h4>
                </div>
                
                <div class="p-6">
                    @if($cliente->orcamentos->count() > 0)
                        <div class="space-y-4">
                            @foreach($cliente->orcamentos->take(5) as $orcamento)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h6 class="text-sm font-medium text-gray-900">
                                            #{{ $orcamento->numero_orcamento }}
                                        </h6>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            @switch($orcamento->status)
                                                @case('rascunho') bg-gray-100 text-gray-800 @break
                                                @case('enviado') bg-blue-100 text-blue-800 @break
                                                @case('aprovado') bg-green-100 text-green-800 @break
                                                @case('rejeitado') bg-red-100 text-red-800 @break
                                                @case('cancelado') bg-yellow-100 text-yellow-800 @break
                                                @default bg-gray-100 text-gray-800
                                            @endswitch">
                                            {{ ucfirst($orcamento->status) }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 mb-2">{{ $orcamento->descricao }}</p>
                                    
                                    <div class="flex items-center justify-between text-xs text-gray-500">
                                        <span>{{ $orcamento->created_at->format('d/m/Y') }}</span>
                                        <span class="font-medium text-gray-900">
                                            R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}
                                        </span>
                                    </div>
                                    
                                    <div class="mt-2">
                                        <a href="{{ route('admin.orcamentos.show', $orcamento) }}" 
                                           class="text-blue-600 hover:text-blue-900 text-xs font-medium">
                                            Ver detalhes →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if($cliente->orcamentos->count() > 5)
                                <div class="text-center pt-4">
                                    <a href="{{ route('admin.orcamentos.index', ['cliente_id' => $cliente->id]) }}" 
                                       class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                        Ver todos os orçamentos ({{ $cliente->orcamentos->count() }})
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-file-invoice text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500 text-sm">Nenhum orçamento encontrado</p>
                            <a href="{{ route('admin.orcamentos.create', ['cliente_id' => $cliente->id]) }}" 
                               class="inline-flex items-center mt-4 px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <i class="fas fa-plus mr-2"></i>
                                Criar Orçamento
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Estatísticas -->
            <div class="bg-white rounded-lg shadow-sm mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h4 class="text-lg font-medium text-gray-900">Estatísticas</h4>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total de Orçamentos</span>
                            <span class="text-sm font-medium text-gray-900">{{ $cliente->orcamentos->count() }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Orçamentos Aprovados</span>
                            <span class="text-sm font-medium text-green-600">{{ $cliente->orcamentos->where('status', 'aprovado')->count() }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Valor Total Aprovado</span>
                            <span class="text-sm font-medium text-gray-900">
                                R$ {{ number_format($cliente->orcamentos->where('status', 'aprovado')->sum('valor_total'), 2, ',', '.') }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Último Orçamento</span>
                            <span class="text-sm font-medium text-gray-900">
                                @if($cliente->orcamentos->count() > 0)
                                    {{ $cliente->orcamentos->latest()->first()->created_at->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleStatus(clienteId) {
    if (confirm('Tem certeza que deseja alterar o status deste cliente?')) {
        fetch(`/admin/clientes/${clienteId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao alterar status do cliente.');
        });
    }
}
</script>
@endsection