@extends('layouts.admin')

@section('title', 'Detalhes do Cliente')

@section('content')
<!-- Breadcrumbs -->
<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                <i class="fas fa-home mr-2"></i>
                Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <a href="{{ route('admin.clientes.index') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">Clientes</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <span class="text-sm font-medium text-gray-500">{{ $cliente->name }}</span>
            </div>
        </li>
    </ol>
</nav>

<!-- Header com gradiente -->
<div class="rounded-xl shadow-lg mb-6" style="background: linear-gradient(135deg, #1E3951 0%, #2A4A66 100%);">
    <div class="p-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-xl flex items-center justify-center" style="background: rgba(248, 171, 20, 0.2);">
                    <span class="text-2xl font-bold" style="color: #F8AB14;">{{ strtoupper(substr($cliente->name, 0, 2)) }}</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ $cliente->name }}</h1>
                    <p class="text-blue-100">{{ $cliente->email }}</p>
                    <div class="flex items-center mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-white" 
                              style="background: {{ $cliente->ativo ? '#F8AB14' : '#1E3951' }};">
                            <i class="fas {{ $cliente->ativo ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                            {{ $cliente->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.clientes.edit', $cliente) }}" 
                   class="inline-flex items-center px-6 py-3 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" 
                   style="background: #F8AB14; hover:background: #E09A12;">
                    <i class="fas fa-edit mr-2"></i>Editar
                </a>
                <a href="{{ route('admin.clientes.index') }}" 
                   class="inline-flex items-center px-6 py-3 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg" 
                   style="background: rgba(255, 255, 255, 0.2); hover:background: rgba(255, 255, 255, 0.3);">
                    <i class="fas fa-arrow-left mr-2"></i>Voltar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Cards de informações -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Dados Básicos -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b" style="background: linear-gradient(135deg, rgba(30, 57, 81, 0.02), rgba(30, 57, 81, 0.05));">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-4" style="background: rgba(30, 57, 81, 0.1);">
                    <i class="fas fa-user text-lg" style="color: #1E3951;"></i>
                </div>
                <h3 class="text-lg font-semibold" style="color: #1E3951;">Dados Básicos</h3>
            </div>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex justify-between items-center py-3 border-b" style="border-color: rgba(30, 57, 81, 0.1);">
                <span class="text-sm font-semibold" style="color: #1E3951;">Nome Completo:</span>
                <span class="text-sm text-gray-700">{{ $cliente->name }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b" style="border-color: rgba(30, 57, 81, 0.1);">
                <span class="text-sm font-semibold" style="color: #1E3951;">E-mail:</span>
                <span class="text-sm text-gray-700">{{ $cliente->email ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b" style="border-color: rgba(30, 57, 81, 0.1);">
                <span class="text-sm font-semibold" style="color: #1E3951;">Tipo de Documento:</span>
                <span class="text-sm text-gray-700">{{ strtoupper($cliente->tipo_documento) }}</span>
            </div>
            <div class="flex justify-between items-center py-3">
                <span class="text-sm font-semibold" style="color: #1E3951;">Documento:</span>
                <span class="text-sm font-mono text-gray-700">{{ $cliente->documento }}</span>
            </div>
        </div>
    </div>

    <!-- Contato -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b" style="background: linear-gradient(135deg, rgba(30, 57, 81, 0.02), rgba(30, 57, 81, 0.05));">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-4" style="background: rgba(30, 57, 81, 0.1);">
                    <i class="fas fa-phone text-lg" style="color: #1E3951;"></i>
                </div>
                <h3 class="text-lg font-semibold" style="color: #1E3951;">Contato</h3>
            </div>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex justify-between items-center py-3 border-b" style="border-color: rgba(30, 57, 81, 0.1);">
                <span class="text-sm font-semibold" style="color: #1E3951;">Telefone:</span>
                <span class="text-sm text-gray-700">{{ $cliente->telefone ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center py-3">
                <span class="text-sm font-semibold" style="color: #1E3951;">Celular:</span>
                <span class="text-sm text-gray-700">{{ $cliente->celular ?? '-' }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Endereço -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="p-6 border-b" style="background: linear-gradient(135deg, rgba(30, 57, 81, 0.02), rgba(30, 57, 81, 0.05));">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-4" style="background: rgba(30, 57, 81, 0.1);">
                <i class="fas fa-map-marker-alt text-lg" style="color: #1E3951;"></i>
            </div>
            <h3 class="text-lg font-semibold" style="color: #1E3951;">Endereço</h3>
        </div>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="space-y-4">
                <div class="flex justify-between items-center py-3 border-b" style="border-color: rgba(30, 57, 81, 0.1);">
                    <span class="text-sm font-semibold" style="color: #1E3951;">CEP:</span>
                    <span class="text-sm text-gray-700">{{ $cliente->cep ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b" style="border-color: rgba(30, 57, 81, 0.1);">
                    <span class="text-sm font-semibold" style="color: #1E3951;">Logradouro:</span>
                    <span class="text-sm text-gray-700">{{ $cliente->logradouro ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-3">
                    <span class="text-sm font-semibold" style="color: #1E3951;">Número:</span>
                    <span class="text-sm text-gray-700">{{ $cliente->numero ?? '-' }}</span>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center py-3 border-b" style="border-color: rgba(30, 57, 81, 0.1);">
                    <span class="text-sm font-semibold" style="color: #1E3951;">Complemento:</span>
                    <span class="text-sm text-gray-700">{{ $cliente->complemento ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b" style="border-color: rgba(30, 57, 81, 0.1);">
                    <span class="text-sm font-semibold" style="color: #1E3951;">Bairro:</span>
                    <span class="text-sm text-gray-700">{{ $cliente->bairro ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-3">
                    <span class="text-sm font-semibold" style="color: #1E3951;">Cidade:</span>
                    <span class="text-sm text-gray-700">{{ $cliente->cidade ?? '-' }}</span>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center py-3 border-b" style="border-color: rgba(30, 57, 81, 0.1);">
                    <span class="text-sm font-semibold" style="color: #1E3951;">Estado:</span>
                    <span class="text-sm text-gray-700">{{ $cliente->estado ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-3">
                    <span class="text-sm font-semibold" style="color: #1E3951;">Status:</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-white" 
                          style="background: {{ $cliente->ativo ? '#F8AB14' : '#1E3951' }};">
                        <i class="fas {{ $cliente->ativo ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                        {{ $cliente->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Observações e Informações Adicionais -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Observações -->
    @if($cliente->observacoes)
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b" style="background: linear-gradient(135deg, rgba(30, 57, 81, 0.02), rgba(30, 57, 81, 0.05));">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-4" style="background: rgba(30, 57, 81, 0.1);">
                    <i class="fas fa-sticky-note text-lg" style="color: #1E3951;"></i>
                </div>
                <h3 class="text-lg font-semibold" style="color: #1E3951;">Observações</h3>
            </div>
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-700 leading-relaxed">{{ $cliente->observacoes }}</p>
        </div>
    </div>
    @endif

    <!-- Informações do Sistema -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b" style="background: linear-gradient(135deg, rgba(30, 57, 81, 0.02), rgba(30, 57, 81, 0.05));">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-4" style="background: rgba(30, 57, 81, 0.1);">
                    <i class="fas fa-info-circle text-lg" style="color: #1E3951;"></i>
                </div>
                <h3 class="text-lg font-semibold" style="color: #1E3951;">Informações do Sistema</h3>
            </div>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex justify-between items-center py-3 border-b" style="border-color: rgba(30, 57, 81, 0.1);">
                <span class="text-sm font-semibold" style="color: #1E3951;">Cadastrado em:</span>
                <span class="text-sm text-gray-700">{{ $cliente->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b" style="border-color: rgba(30, 57, 81, 0.1);">
                <span class="text-sm font-semibold" style="color: #1E3951;">Última atualização:</span>
                <span class="text-sm text-gray-700">{{ $cliente->updated_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between items-center py-3">
                <span class="text-sm font-semibold" style="color: #1E3951;">ID do Cliente:</span>
                <span class="text-sm font-mono text-gray-700">#{{ $cliente->id }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Ações rápidas -->
<div class="mt-8 flex justify-center space-x-4">
    <a href="{{ route('admin.clientes.edit', $cliente) }}" 
       class="inline-flex items-center px-8 py-4 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" 
       style="background: linear-gradient(135deg, #F8AB14 0%, #E09A12 100%);">
        <i class="fas fa-edit mr-3"></i>Editar Cliente
    </a>
    
    <button onclick="toggleStatus({{ $cliente->id }})" 
            class="inline-flex items-center px-8 py-4 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" 
            style="background: linear-gradient(135deg, #1E3951 0%, #2A4A66 100%);">
        <i class="fas {{ $cliente->ativo ? 'fa-toggle-off' : 'fa-toggle-on' }} mr-3"></i>
        {{ $cliente->ativo ? 'Desativar' : 'Ativar' }} Cliente
    </button>
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