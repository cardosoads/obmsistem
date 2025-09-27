@extends('layouts.admin')

@section('title', 'Clientes')

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
                <span class="text-sm font-medium text-gray-500">Clientes</span>
            </div>
        </li>
    </ol>
</nav>

<!-- Header com gradiente -->
<div class="rounded-xl shadow-lg mb-6" style="background: linear-gradient(135deg, #1E3951 0%, #2A4A66 100%);">
    <div class="p-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(248, 171, 20, 0.2);">
                    <i class="fas fa-users text-2xl" style="color: #F8AB14;"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Clientes</h1>
                    <p class="text-blue-100">Gerencie os clientes do sistema</p>
                </div>
            </div>
            <a href="{{ route('admin.clientes.create') }}" 
               class="inline-flex items-center px-6 py-3 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" 
               style="background: #F8AB14; hover:background: #E09A12;">
                <i class="fas fa-plus mr-2"></i>Novo Cliente
            </a>
        </div>
    </div>
</div>

<!-- Card principal -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">

    <!-- Filtros -->
    <div class="p-6 border-b" style="background: linear-gradient(135deg, rgba(30, 57, 81, 0.02), rgba(30, 57, 81, 0.05));">
        <form method="GET" action="{{ route('admin.clientes.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Buscar por nome, email ou documento..."
                       class="w-full px-4 py-3 border rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:border-transparent" 
                       style="border-color: rgba(30, 57, 81, 0.2); focus:ring-color: #1E3951;">
            </div>
            
            <div>
                <select name="status" class="px-4 py-3 border rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:border-transparent" 
                        style="border-color: rgba(30, 57, 81, 0.2); focus:ring-color: #1E3951;">
                    <option value="">Todos os Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativo</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
            
            <div>
                <select name="tipo_documento" class="px-4 py-3 border rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:border-transparent" 
                        style="border-color: rgba(30, 57, 81, 0.2); focus:ring-color: #1E3951;">
                    <option value="">Todos os Tipos</option>
                    <option value="cpf" {{ request('tipo_documento') === 'cpf' ? 'selected' : '' }}>CPF</option>
                    <option value="cnpj" {{ request('tipo_documento') === 'cnpj' ? 'selected' : '' }}>CNPJ</option>
                </select>
            </div>
            
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" 
                    style="background: #1E3951;">
                <i class="fas fa-search mr-2"></i>Filtrar
            </button>
            
            @if(request()->hasAny(['search', 'status', 'tipo_documento']))
                <a href="{{ route('admin.clientes.index') }}" 
                   class="inline-flex items-center px-6 py-3 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg" 
                   style="background: #F8AB14;">
                    <i class="fas fa-times mr-2"></i>Limpar
                </a>
            @endif
        </form>
    </div>

    <!-- Tabela -->
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead style="background: linear-gradient(135deg, #1E3951 0%, #2A4A66 100%);">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                        Cliente
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                        Documento
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                        Contato
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                        Criado em
                    </th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse($clientes as $cliente)
                    <tr class="border-b transition-all duration-300 hover:shadow-md" style="border-color: rgba(30, 57, 81, 0.1); hover:background: rgba(30, 57, 81, 0.02);">
                        <td class="px-6 py-6 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-4 p-2" style="background: rgba(30, 57, 81, 0.1);">
                                    <span class="text-sm font-bold" style="color: #1E3951;">{{ strtoupper(substr($cliente->name, 0, 2)) }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold" style="color: #1E3951;">{{ $cliente->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $cliente->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap">
                            <div class="text-sm font-mono font-medium px-2 py-1" style="color: #1E3951;">{{ $cliente->documento }}</div>
                            <div class="text-xs text-gray-500 px-2">{{ strtoupper($cliente->tipo_documento) }}</div>
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap">
                            <div class="text-sm text-gray-700 px-2 py-1">{{ $cliente->telefone ?? '-' }}</div>
                            @if($cliente->celular)
                                <div class="text-xs text-gray-500 px-2">{{ $cliente->celular }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-semibold
                                {{ $cliente->ativo ? 'text-white' : 'text-white' }}" 
                                style="background: {{ $cliente->ativo ? '#F8AB14' : '#1E3951' }};">
                                <i class="fas {{ $cliente->ativo ? 'fa-check-circle' : 'fa-times-circle' }} mr-2"></i>
                                {{ $cliente->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap">
                            <div class="text-sm text-gray-700 px-2 py-1">{{ $cliente->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500 px-2">{{ $cliente->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap text-right">
                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('admin.clientes.show', $cliente) }}" 
                                   class="inline-flex items-center justify-center w-10 h-10 rounded-lg transition-all duration-300 hover:shadow-md p-2" 
                                   style="background: rgba(30, 57, 81, 0.1); color: #1E3951; hover:background: #1E3951; hover:color: white;"
                                   title="Visualizar">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                
                                <a href="{{ route('admin.clientes.edit', $cliente) }}" 
                                   class="inline-flex items-center justify-center w-10 h-10 rounded-lg transition-all duration-300 hover:shadow-md p-2" 
                                   style="background: rgba(248, 171, 20, 0.1); color: #F8AB14; hover:background: #F8AB14; hover:color: white;"
                                   title="Editar">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                
                                <button onclick="toggleStatus({{ $cliente->id }})" 
                                        class="inline-flex items-center justify-center w-10 h-10 rounded-lg transition-all duration-300 hover:shadow-md p-2" 
                                        style="background: {{ $cliente->ativo ? 'rgba(248, 171, 20, 0.1)' : 'rgba(30, 57, 81, 0.1)' }}; color: {{ $cliente->ativo ? '#F8AB14' : '#1E3951' }};"
                                        title="{{ $cliente->ativo ? 'Desativar' : 'Ativar' }}">
                                    <i class="fas {{ $cliente->ativo ? 'fa-toggle-on' : 'fa-toggle-off' }} text-sm"></i>
                                </button>
                                
                                <button onclick="deleteCliente({{ $cliente->id }})" 
                                        class="inline-flex items-center justify-center w-10 h-10 rounded-lg transition-all duration-300 hover:shadow-md p-2" 
                                        style="background: rgba(30, 57, 81, 0.1); color: #1E3951; hover:background: #1E3951; hover:color: white;"
                                        title="Excluir">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center py-8">
                                <div class="w-20 h-20 rounded-full flex items-center justify-center mb-6" style="background: rgba(30, 57, 81, 0.1);">
                                    <i class="fas fa-users text-3xl" style="color: #1E3951;"></i>
                                </div>
                                <p class="text-lg font-semibold mb-2" style="color: #1E3951;">Nenhum cliente encontrado</p>
                                <p class="text-sm text-gray-500 mb-6">Comece criando um novo cliente para o sistema</p>
                                <a href="{{ route('admin.clientes.create') }}" 
                                   class="inline-flex items-center px-6 py-3 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" 
                                   style="background: #F8AB14;">
                                    <i class="fas fa-plus mr-2"></i>Criar Novo Cliente
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    @if($clientes->hasPages())
        <div class="p-6 border-t" style="border-color: rgba(30, 57, 81, 0.1); background: linear-gradient(135deg, rgba(30, 57, 81, 0.02), rgba(30, 57, 81, 0.05));">
            <div class="flex justify-center">
                {{ $clientes->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
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

function deleteCliente(clienteId) {
    if (confirm('Tem certeza que deseja excluir este cliente? Esta ação não pode ser desfeita.')) {
        fetch(`/admin/clientes/${clienteId}`, {
            method: 'DELETE',
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
            alert('Erro ao excluir cliente.');
        });
    }
}
</script>
@endsection