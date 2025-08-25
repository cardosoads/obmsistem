@extends('layouts.admin')

@section('title', 'Fornecedores Omie')

@section('content')
<div class="mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between">
            <h3 class="text-lg font-medium text-gray-900 mb-4 md:mb-0">Fornecedores Omie</h3>
            <div class="flex flex-col md:flex-row gap-2">
                <input type="text" id="searchInput" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 md:w-80" placeholder="Buscar fornecedores...">
                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" onclick="searchFornecedores()">Buscar</button>
            </div>
        </div>
        <div class="p-6">
            <div id="loading" class="text-center hidden">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-2 text-gray-600">Carregando...</span>
            </div>

            <!-- Tabela de fornecedores -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Omie</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Razão Social</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome Fantasia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CNPJ/CPF</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="fornecedoresTableBody" class="bg-white divide-y divide-gray-200">
                        @forelse($fornecedores as $fornecedor)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $fornecedor['codigo_fornecedor_omie'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $fornecedor['razao_social'] ?? $fornecedor['nome_fantasia'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $fornecedor['nome_fantasia'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $fornecedor['cnpj_cpf'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ ($fornecedor['inativo'] ?? 'N') === 'N' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ($fornecedor['inativo'] ?? 'N') === 'N' ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" 
                                            onclick="showFornecedorDetails({{ $fornecedor['codigo_fornecedor_omie'] ?? 0 }})">
                                        <i class="fas fa-eye mr-1"></i> Detalhes
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Nenhum fornecedor encontrado</td>
                            </tr>
                        @endforelse
                    </tbody>
                        </table>
                    </div>
                    
                    @if(isset($pagination) && $pagination['total_pages'] > 1)
                    <div class="flex flex-col sm:flex-row justify-between items-center mt-6 pt-4 border-t border-gray-200">
                        <div class="text-sm text-gray-700 mb-4 sm:mb-0">
                            Página {{ $pagination['current_page'] }} de {{ $pagination['total_pages'] }}
                            ({{ $pagination['total_records'] }} registros)
                        </div>
                        <nav class="flex space-x-2">
                            @if($pagination['current_page'] > 1)
                                <a href="{{ route('admin.omie.fornecedores', ['page' => $pagination['current_page'] - 1]) }}" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700">
                                    Anterior
                                </a>
                            @endif
                            
                            @for($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++)
                                <a href="{{ route('admin.omie.fornecedores', ['page' => $i]) }}" class="px-3 py-2 text-sm font-medium {{ $i == $pagination['current_page'] ? 'text-blue-600 bg-blue-50 border-blue-500' : 'text-gray-500 bg-white border-gray-300' }} border rounded-md hover:bg-gray-50 hover:text-gray-700">
                                    {{ $i }}
                                </a>
                            @endfor
                            
                            @if($pagination['current_page'] < $pagination['total_pages'])
                                <a href="{{ route('admin.omie.fornecedores', ['page' => $pagination['current_page'] + 1]) }}" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700">
                                    Próxima
                                </a>
                            @endif
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de detalhes do fornecedor -->
<div id="fornecedorDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Detalhes do Fornecedor</h3>
            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeModal()">
                <span class="sr-only">Fechar</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="mt-4">
            <div id="fornecedorDetailsLoading" class="text-center hidden">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-2 text-gray-600">Carregando...</span>
            </div>
            <div id="fornecedorDetailsContent"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function searchFornecedores() {
    const searchTerm = document.getElementById('searchInput').value;
    const loading = document.getElementById('loading');
    
    loading.classList.remove('hidden');
    
    fetch(`{{ url('/admin/omie/fornecedores') }}?busca=${encodeURIComponent(searchTerm)}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Atualizar apenas o conteúdo da tabela
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newTableBody = doc.getElementById('fornecedoresTableBody');
        
        if (newTableBody) {
            document.getElementById('fornecedoresTableBody').innerHTML = newTableBody.innerHTML;
        }
        
        loading.classList.add('hidden');
    })
    .catch(error => {
        console.error('Erro:', error);
        loading.classList.add('hidden');
    });
}

// Permitir busca ao pressionar Enter
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        searchFornecedores();
    }
});

function showFornecedorDetails(omieId) {
    if (!omieId) {
        alert('ID do fornecedor não encontrado');
        return;
    }
    
    document.getElementById('fornecedorDetailsLoading').classList.remove('hidden');
    document.getElementById('fornecedorDetailsContent').innerHTML = '';
    document.getElementById('fornecedorDetailsModal').classList.remove('hidden');
    
    // Fetch fornecedor details
    fetch(`{{ url('/admin/omie/fornecedores') }}/${omieId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('fornecedorDetailsLoading').classList.add('hidden');
            
            if (data.success) {
                displayFornecedorDetails(data.fornecedor);
            } else {
                document.getElementById('fornecedorDetailsContent').innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        Erro ao carregar detalhes: ${data.message}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            document.getElementById('fornecedorDetailsLoading').classList.add('hidden');
            document.getElementById('fornecedorDetailsContent').innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    Erro ao carregar detalhes do fornecedor
                </div>
            `;
        });
}

function closeModal() {
    document.getElementById('fornecedorDetailsModal').classList.add('hidden');
}

function displayFornecedorDetails(fornecedor) {
    const content = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h6 class="text-lg font-semibold text-gray-900 mb-3">Informações Básicas</h6>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="space-y-2">
                        <div class="flex justify-between"><span class="font-medium text-gray-600">ID Omie:</span><span class="text-gray-900">${fornecedor.codigo_fornecedor_omie || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Razão Social:</span><span class="text-gray-900">${fornecedor.razao_social || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Nome Fantasia:</span><span class="text-gray-900">${fornecedor.nome_fantasia || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">CNPJ/CPF:</span><span class="text-gray-900">${fornecedor.cnpj_cpf || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Status:</span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${(fornecedor.inativo || 'N') === 'N' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                ${(fornecedor.inativo || 'N') === 'N' ? 'Ativo' : 'Inativo'}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <h6 class="text-lg font-semibold text-gray-900 mb-3">Contato</h6>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="space-y-2">
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Email:</span><span class="text-gray-900">${fornecedor.email || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Telefone 1:</span><span class="text-gray-900">${(fornecedor.telefone1_ddd || '') + ' ' + (fornecedor.telefone1_numero || 'N/A')}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Telefone 2:</span><span class="text-gray-900">${(fornecedor.telefone2_ddd || '') + ' ' + (fornecedor.telefone2_numero || 'N/A')}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Fax:</span><span class="text-gray-900">${(fornecedor.fax_ddd || '') + ' ' + (fornecedor.fax_numero || 'N/A')}</span></div>
                    </div>
                </div>
            </div>
        </div>
        
        ${fornecedor.endereco ? `
        <div class="mt-6">
            <h6 class="text-lg font-semibold text-gray-900 mb-3">Endereço</h6>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Logradouro:</span><span class="text-gray-900">${fornecedor.endereco.logradouro || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Número:</span><span class="text-gray-900">${fornecedor.endereco.numero || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Complemento:</span><span class="text-gray-900">${fornecedor.endereco.complemento || 'N/A'}</span></div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Bairro:</span><span class="text-gray-900">${fornecedor.endereco.bairro || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Cidade:</span><span class="text-gray-900">${fornecedor.endereco.cidade || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">Estado:</span><span class="text-gray-900">${fornecedor.endereco.estado || 'N/A'}</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-600">CEP:</span><span class="text-gray-900">${fornecedor.endereco.cep || 'N/A'}</span></div>
                    </div>
                </div>
            </div>
        </div>
        ` : ''}
        
        ${fornecedor.dadosBancarios && fornecedor.dadosBancarios.length > 0 ? `
        <div class="mt-6">
            <h6 class="text-lg font-semibold text-gray-900 mb-3">Dados Bancários</h6>
            <div class="space-y-3">
                ${fornecedor.dadosBancarios.map(banco => `
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <div class="flex justify-between"><span class="font-medium text-gray-600">Banco:</span><span class="text-gray-900">${banco.codigo_banco || 'N/A'} - ${banco.nome_banco || 'N/A'}</span></div>
                                <div class="flex justify-between"><span class="font-medium text-gray-600">Agência:</span><span class="text-gray-900">${banco.agencia || 'N/A'}</span></div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between"><span class="font-medium text-gray-600">Conta:</span><span class="text-gray-900">${banco.conta_corrente || 'N/A'}</span></div>
                                <div class="flex justify-between"><span class="font-medium text-gray-600">Tipo:</span><span class="text-gray-900">${banco.tipo_conta || 'N/A'}</span></div>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        </div>
        ` : ''}
    `;
    
    document.getElementById('fornecedorDetailsContent').innerHTML = content;
}
</script>
@endpush