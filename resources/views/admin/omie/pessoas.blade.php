@extends('layouts.admin')

@section('title', 'Pessoas Omie')

@section('content')
<div class="mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between">
            <h3 class="text-lg font-medium text-gray-900 mb-4 md:mb-0">Pessoas Omie (Clientes e Fornecedores)</h3>
            <div class="flex flex-col md:flex-row gap-2">
                <select id="tipoFilter" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="todos" {{ $tipo === 'todos' ? 'selected' : '' }}>Todos</option>
                    <option value="clientes" {{ $tipo === 'clientes' ? 'selected' : '' }}>Apenas Clientes</option>
                    <option value="fornecedores" {{ $tipo === 'fornecedores' ? 'selected' : '' }}>Apenas Fornecedores</option>
                </select>
                <input type="text" id="searchInput" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 md:w-80" placeholder="Buscar pessoas..." value="{{ $search }}">
                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" onclick="searchPessoas()">Buscar</button>
            </div>
        </div>
        <div class="p-6">
            @if(isset($error))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ $error }}
                </div>
            @endif
            
            <div id="loading" class="text-center hidden">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-2 text-gray-600">Carregando...</span>
            </div>

            <!-- Tabela de pessoas -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Omie</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="pessoasTableBody" class="bg-white divide-y divide-gray-200">
                        @forelse($pessoas as $pessoa)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $pessoa['tipo_pessoa'] === 'cliente' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $pessoa['tipo_pessoa'] === 'cliente' ? 'Cliente' : 'Fornecedor' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pessoa['id_omie'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="font-medium">{{ $pessoa['nome_principal'] }}</div>
                                    @if(isset($pessoa['nome_fantasia']) && $pessoa['nome_fantasia'] !== $pessoa['razao_social'])
                                        <div class="text-xs text-gray-500">{{ $pessoa['nome_fantasia'] }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pessoa['cnpj_cpf'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pessoa['email'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ ($pessoa['telefone1_ddd'] ?? '') . ' ' . ($pessoa['telefone1_numero'] ?? 'N/A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ ($pessoa['inativo'] ?? 'N') === 'N' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ($pessoa['inativo'] ?? 'N') === 'N' ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button type="button" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" 
                                            onclick="showPessoaDetails({{ $pessoa['id_omie'] ?? 0 }}, '{{ $pessoa['tipo_pessoa'] }}')">
                                        <i class="fas fa-eye mr-1"></i> Detalhes
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">Nenhuma pessoa encontrada</td>
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
                        <a href="{{ route('admin.omie.pessoas', ['page' => $pagination['current_page'] - 1, 'tipo' => $tipo, 'search' => $search]) }}" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700">
                            Anterior
                        </a>
                    @endif
                    
                    @for($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++)
                        <a href="{{ route('admin.omie.pessoas', ['page' => $i, 'tipo' => $tipo, 'search' => $search]) }}" class="px-3 py-2 text-sm font-medium {{ $i == $pagination['current_page'] ? 'text-blue-600 bg-blue-50 border-blue-500' : 'text-gray-500 bg-white border-gray-300' }} border rounded-md hover:bg-gray-50 hover:text-gray-700">
                            {{ $i }}
                        </a>
                    @endfor
                    
                    @if($pagination['current_page'] < $pagination['total_pages'])
                        <a href="{{ route('admin.omie.pessoas', ['page' => $pagination['current_page'] + 1, 'tipo' => $tipo, 'search' => $search]) }}" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700">
                            Próxima
                        </a>
                    @endif
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de detalhes da pessoa -->
<div id="pessoaDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Detalhes da Pessoa</h3>
            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeModal()">
                <span class="sr-only">Fechar</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="mt-4">
            <div id="pessoaDetailsLoading" class="text-center hidden">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-2 text-gray-600">Carregando...</span>
            </div>
            <div id="pessoaDetailsContent"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function searchPessoas() {
    const searchTerm = document.getElementById('searchInput').value;
    const tipo = document.getElementById('tipoFilter').value;
    const loading = document.getElementById('loading');
    
    loading.classList.remove('hidden');
    
    const url = new URL(window.location.href);
    url.searchParams.set('search', searchTerm);
    url.searchParams.set('tipo', tipo);
    url.searchParams.delete('page'); // Reset para primeira página
    
    window.location.href = url.toString();
}

// Permitir busca ao pressionar Enter
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        searchPessoas();
    }
});

// Buscar ao alterar o filtro de tipo
document.getElementById('tipoFilter').addEventListener('change', function() {
    searchPessoas();
});

function showPessoaDetails(omieId, tipo) {
    if (!omieId) {
        alert('ID da pessoa não encontrado');
        return;
    }
    
    document.getElementById('pessoaDetailsLoading').classList.remove('hidden');
    document.getElementById('pessoaDetailsContent').innerHTML = '';
    document.getElementById('pessoaDetailsModal').classList.remove('hidden');
    
    // Fetch pessoa details
    fetch(`{{ url('/admin/omie/pessoas') }}/${omieId}?tipo=${tipo}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('pessoaDetailsLoading').classList.add('hidden');
            
            if (data.success) {
                const pessoa = data.data;
                const tipoPessoa = pessoa.tipo_pessoa || tipo;
                
                document.getElementById('pessoaDetailsContent').innerHTML = `
                    <div class="space-y-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Informações Básicas</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <div class="flex justify-between"><span class="font-medium text-gray-600">Tipo:</span><span class="text-gray-900 capitalize">${tipoPessoa}</span></div>
                                    <div class="flex justify-between"><span class="font-medium text-gray-600">ID Omie:</span><span class="text-gray-900">${pessoa.codigo_cliente_omie || pessoa.codigo_fornecedor_omie || 'N/A'}</span></div>
                                    <div class="flex justify-between"><span class="font-medium text-gray-600">Razão Social:</span><span class="text-gray-900">${pessoa.razao_social || 'N/A'}</span></div>
                                    <div class="flex justify-between"><span class="font-medium text-gray-600">Nome Fantasia:</span><span class="text-gray-900">${pessoa.nome_fantasia || 'N/A'}</span></div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between"><span class="font-medium text-gray-600">CNPJ/CPF:</span><span class="text-gray-900">${pessoa.cnpj_cpf || 'N/A'}</span></div>
                                    <div class="flex justify-between"><span class="font-medium text-gray-600">Email:</span><span class="text-gray-900">${pessoa.email || 'N/A'}</span></div>
                                    <div class="flex justify-between"><span class="font-medium text-gray-600">Telefone:</span><span class="text-gray-900">${(pessoa.telefone1_ddd || '') + ' ' + (pessoa.telefone1_numero || 'N/A')}</span></div>
                                    <div class="flex justify-between"><span class="font-medium text-gray-600">Status:</span><span class="text-gray-900">${(pessoa.inativo || 'N') === 'N' ? 'Ativo' : 'Inativo'}</span></div>
                                </div>
                            </div>
                        </div>
                        
                        ${pessoa.endereco ? `
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Endereço</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <div class="flex justify-between"><span class="font-medium text-gray-600">Logradouro:</span><span class="text-gray-900">${pessoa.endereco || 'N/A'}</span></div>
                                    <div class="flex justify-between"><span class="font-medium text-gray-600">Número:</span><span class="text-gray-900">${pessoa.numero || 'N/A'}</span></div>
                                    <div class="flex justify-between"><span class="font-medium text-gray-600">Bairro:</span><span class="text-gray-900">${pessoa.bairro || 'N/A'}</span></div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between"><span class="font-medium text-gray-600">Cidade:</span><span class="text-gray-900">${pessoa.cidade || 'N/A'}</span></div>
                                    <div class="flex justify-between"><span class="font-medium text-gray-600">Estado:</span><span class="text-gray-900">${pessoa.estado || 'N/A'}</span></div>
                                    <div class="flex justify-between"><span class="font-medium text-gray-600">CEP:</span><span class="text-gray-900">${pessoa.cep || 'N/A'}</span></div>
                                </div>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                `;
            } else {
                document.getElementById('pessoaDetailsContent').innerHTML = `
                    <div class="text-center text-red-600">
                        <p>Erro ao carregar detalhes: ${data.message}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            document.getElementById('pessoaDetailsLoading').classList.add('hidden');
            document.getElementById('pessoaDetailsContent').innerHTML = `
                <div class="text-center text-red-600">
                    <p>Erro ao carregar detalhes da pessoa</p>
                </div>
            `;
            console.error('Erro:', error);
        });
}

function closeModal() {
    document.getElementById('pessoaDetailsModal').classList.add('hidden');
}

// Fechar modal ao clicar fora dele
document.getElementById('pessoaDetailsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endpush