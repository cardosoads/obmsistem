@extends('layouts.admin')

@section('title', 'Clientes Omie')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Clientes Omie</h3>
            <div class="flex gap-2">
                <input type="text" id="searchInput" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Buscar clientes..." style="width: 300px;">
                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500" onclick="searchClientes()">Buscar</button>
            </div>
        </div>
        <div class="p-6">
            <div id="loading" class="text-center" style="display: none;">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-2 text-gray-600">Carregando...</span>
            </div>
            
            <div class="overflow-x-auto overflow-y-auto max-h-96 border border-gray-200 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">ID Omie</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">Documento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">Telefone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="clientesTableBody" class="bg-white divide-y divide-gray-200">
                        @forelse($clientes as $cliente)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cliente['codigo_cliente_omie'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cliente['razao_social'] ?? $cliente['nome_fantasia'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cliente['cnpj_cpf'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cliente['email'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cliente['telefone1_ddd'] ?? '' }} {{ $cliente['telefone1_numero'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ ($cliente['inativo'] ?? 'N') === 'N' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ($cliente['inativo'] ?? 'N') === 'N' ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button type="button" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" 
                                        onclick="showClienteDetails({{ $cliente['codigo_cliente_omie'] ?? 0 }})">
                                    <i class="fas fa-eye mr-1"></i> Detalhes
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Nenhum cliente encontrado</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($pagination) && $pagination['total_pages'] > 1)
            <div class="flex justify-between items-center mt-6">
                <div class="text-sm text-gray-700">
                    Página {{ $pagination['current_page'] }} de {{ $pagination['total_pages'] }}
                    ({{ $pagination['total_records'] }} registros)
                </div>
                <nav class="flex space-x-2">
                    @if($pagination['current_page'] > 1)
                    <a href="{{ route('admin.omie.clientes', ['page' => $pagination['current_page'] - 1]) }}" 
                       class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Anterior
                    </a>
                    @endif
                    
                    @for($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++)
                    <a href="{{ route('admin.omie.clientes', ['page' => $i]) }}" 
                       class="px-3 py-2 text-sm font-medium {{ $i == $pagination['current_page'] ? 'text-white bg-blue-600 border-blue-600' : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-50' }} border rounded-md">
                        {{ $i }}
                    </a>
                    @endfor
                    
                    @if($pagination['current_page'] < $pagination['total_pages'])
                    <a href="{{ route('admin.omie.clientes', ['page' => $pagination['current_page'] + 1]) }}" 
                       class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Próxima
                    </a>
                    @endif
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de Detalhes -->
<div id="clienteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <h3 class="text-lg font-bold text-gray-900">Detalhes do Cliente</h3>
            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" onclick="closeModal()">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        <div id="clienteDetailsContent" class="mt-4">
            <div class="text-center">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-2 text-gray-600">Carregando...</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function searchClientes() {
    const searchTerm = document.getElementById('searchInput').value;
    const url = new URL(window.location.href);
    
    if (searchTerm) {
        url.searchParams.set('search', searchTerm);
    } else {
        url.searchParams.delete('search');
    }
    
    url.searchParams.delete('page'); // Reset pagination
    window.location.href = url.toString();
}

// Permitir busca ao pressionar Enter
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        searchClientes();
    }
});

function showClienteDetails(omieId) {
    if (!omieId) {
        alert('ID do cliente não encontrado');
        return;
    }
    
    document.getElementById('clienteModal').classList.remove('hidden');
    
    // Reset modal content
    document.getElementById('clienteDetailsContent').innerHTML = `
        <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="ml-2 text-gray-600">Carregando...</span>
        </div>
    `;
    
    // Fetch cliente details
    fetch(`{{ url('/admin/omie/clientes') }}/${omieId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayClienteDetails(data.data);
            } else {
                document.getElementById('clienteDetailsContent').innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    Erro ao carregar detalhes: ${data.message}
                </div>
            `;
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            document.getElementById('clienteDetailsContent').innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    Erro ao carregar detalhes do cliente
                </div>
            `;
        });
}

function closeModal() {
    document.getElementById('clienteModal').classList.add('hidden');
}

function displayClienteDetails(cliente) {
    const content = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h6 class="text-lg font-semibold text-gray-900 mb-3">Informações Básicas</h6>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="space-y-2">
                        <div class="flex justify-between"><span class="font-medium text-gray-600">ID Omie:</span><span class="text-gray-900">${cliente.codigo_cliente_omie || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Razão Social:</span><span class="text-gray-900">${cliente.razao_social || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Nome Fantasia:</span><span class="text-gray-900">${cliente.nome_fantasia || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">CNPJ/CPF:</span><span class="text-gray-900">${cliente.cnpj_cpf || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Status:</span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${(cliente.inativo || 'N') === 'N' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                ${(cliente.inativo || 'N') === 'N' ? 'Ativo' : 'Inativo'}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <h6 class="text-lg font-semibold text-gray-900 mb-3">Contato</h6>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="space-y-2">
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Email:</span><span class="text-gray-900">${cliente.email || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Telefone 1:</span><span class="text-gray-900">${(cliente.telefone1_ddd || '') + ' ' + (cliente.telefone1_numero || 'N/A')}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Telefone 2:</span><span class="text-gray-900">${(cliente.telefone2_ddd || '') + ' ' + (cliente.telefone2_numero || 'N/A')}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Fax:</span><span class="text-gray-900">${(cliente.fax_ddd || '') + ' ' + (cliente.fax_numero || 'N/A')}</span></div>
                    </div>
                </div>
            </div>
        </div>
        
        ${cliente.endereco ? `
        <div class="mt-6">
            <h6 class="text-lg font-semibold text-gray-900 mb-3">Endereço</h6>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Logradouro:</span><span class="text-gray-900">${cliente.endereco.logradouro || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Número:</span><span class="text-gray-900">${cliente.endereco.numero || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Complemento:</span><span class="text-gray-900">${cliente.endereco.complemento || 'N/A'}</span></div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Bairro:</span><span class="text-gray-900">${cliente.endereco.bairro || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Cidade:</span><span class="text-gray-900">${cliente.endereco.cidade || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Estado:</span><span class="text-gray-900">${cliente.endereco.estado || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">CEP:</span><span class="text-gray-900">${cliente.endereco.cep || 'N/A'}</span></div>
                    </div>
                </div>
            </div>
        </div>
        ` : ''}
    `;
    
    document.getElementById('clienteDetailsContent').innerHTML = content;
}
</script>
@endpush