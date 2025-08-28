@extends('layouts.admin')

@section('title', 'Editar Centro de Custo')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Editar Centro de Custo</h1>
        <a href="{{ route('admin.centros-custo.show', $centroCusto) }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    <form action="{{ route('admin.centros-custo.update', $centroCusto) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Dados Básicos -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Dados do Centro de Custo</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="codigo" class="block text-sm font-medium text-gray-700 mb-2">Código *</label>
                    <input type="text" 
                           id="codigo" 
                           name="codigo" 
                           value="{{ old('codigo', $centroCusto->codigo) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none" 
                           readonly>
                    <p class="text-xs text-gray-500 mt-1">Código único para identificação (somente leitura)</p>
                    @error('codigo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome *</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $centroCusto->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" 
                           required>
                    <p class="text-xs text-gray-500 mt-1">Nome do centro de custo (ex: Administrativo, Vendas)</p>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                <textarea id="description" 
                          name="description" 
                          rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $centroCusto->description) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Descrição detalhada do centro de custo e suas responsabilidades</p>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Dados do Cliente OMIE -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Cliente OMIE</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="cliente_omie_id" class="block text-sm font-medium text-gray-700 mb-2">ID Cliente OMIE</label>
                    <div class="relative">
                        <input type="text" 
                               id="cliente_omie_id" 
                               name="cliente_omie_id" 
                               value="{{ old('cliente_omie_id', $centroCusto->cliente_omie_id) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cliente_omie_id') border-red-500 @enderror"
                               placeholder="Digite o ID do cliente OMIE">
                        <div id="cliente-loading" class="absolute right-3 top-3 hidden">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">ID do cliente na API OMIE - o nome será preenchido automaticamente</p>
                    @error('cliente_omie_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="cliente_nome" class="block text-sm font-medium text-gray-700 mb-2">Nome do Cliente</label>
                    <div class="relative">
                        <input type="text" 
                               id="cliente_nome" 
                               name="cliente_nome" 
                               value="{{ old('cliente_nome', $centroCusto->cliente_nome) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cliente_nome') border-red-500 @enderror"
                               placeholder="Digite o nome do cliente para buscar...">
                        <div id="cliente_nome_suggestions" class="absolute z-50 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto" style="display: none;"></div>
                        <div id="cliente-nome-loading" class="absolute right-3 top-3 hidden">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Digite o nome do cliente para buscar ou será preenchido automaticamente pelo ID OMIE</p>
                    @error('cliente_nome')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Dados da Base -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Base e Localização</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="base_id" class="block text-sm font-medium text-gray-700 mb-2">Base *</label>
                    <select id="base_id" 
                            name="base_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('base_id') border-red-500 @enderror" 
                            required>
                        <option value="">Selecione uma base</option>
                        @foreach($bases as $base)
                            <option value="{{ $base->id }}" {{ old('base_id', $centroCusto->base_id) == $base->id ? 'selected' : '' }}>
                                {{ $base->name }} - {{ $base->city }}/{{ $base->uf }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Selecione a base onde o centro de custo está localizado</p>
                    @error('base_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="regional" class="block text-sm font-medium text-gray-700 mb-2">Regional</label>
                    <input type="text" 
                           id="regional" 
                           name="regional" 
                           value="{{ old('regional', $centroCusto->regional) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none" 
                           readonly>
                    <p class="text-xs text-gray-500 mt-1">Preenchido automaticamente com base na base selecionada</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div>
                    <label for="sigla" class="block text-sm font-medium text-gray-700 mb-2">Sigla</label>
                    <input type="text" 
                           id="sigla" 
                           name="sigla" 
                           value="{{ old('sigla', $centroCusto->sigla) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none" 
                           readonly>
                    <p class="text-xs text-gray-500 mt-1">Sigla da base</p>
                </div>
                
                <div>
                    <label for="uf" class="block text-sm font-medium text-gray-700 mb-2">UF</label>
                    <input type="text" 
                           id="uf" 
                           name="uf" 
                           value="{{ old('uf', $centroCusto->uf) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none" 
                           readonly>
                    <p class="text-xs text-gray-500 mt-1">Estado da base</p>
                </div>
                
                <div>
                    <label for="supervisor" class="block text-sm font-medium text-gray-700 mb-2">Supervisor</label>
                    <input type="text" 
                           id="supervisor" 
                           name="supervisor" 
                           value="{{ old('supervisor', $centroCusto->supervisor) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none" 
                           readonly>
                    <p class="text-xs text-gray-500 mt-1">Supervisor da base</p>
                </div>
            </div>
        </div>

        <!-- Dados da Marca -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Marca e Mercado</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="marca_id" class="block text-sm font-medium text-gray-700 mb-2">Marca *</label>
                    <select id="marca_id" 
                            name="marca_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('marca_id') border-red-500 @enderror" 
                            required>
                        <option value="">Selecione uma marca</option>
                        @foreach($marcas as $marca)
                            <option value="{{ $marca->id }}" {{ old('marca_id', $centroCusto->marca_id) == $marca->id ? 'selected' : '' }}>
                                {{ $marca->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Selecione a marca associada ao centro de custo</p>
                    @error('marca_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="mercado" class="block text-sm font-medium text-gray-700 mb-2">Mercado</label>
                    <input type="text" 
                           id="mercado" 
                           name="mercado" 
                           value="{{ old('mercado', $centroCusto->mercado) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('mercado') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Mercado de atuação do centro de custo</p>
                    @error('mercado')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Dados da API Omie -->
        @if($centroCusto->omie_codigo || $centroCusto->omie_estrutura || $centroCusto->sincronizado_em)
        <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
            <h3 class="text-lg font-semibold text-blue-700 mb-4">
                <i class="fas fa-sync-alt mr-2"></i>Dados da API Omie
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Código Omie</label>
                    <input type="text" 
                           value="{{ $centroCusto->omie_codigo ?? 'Não sincronizado' }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none" 
                           readonly>
                    <p class="text-xs text-gray-500 mt-1">Código do departamento na API Omie</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estrutura Omie</label>
                    <input type="text" 
                           value="{{ $centroCusto->omie_estrutura ?? 'Não sincronizado' }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none" 
                           readonly>
                    <p class="text-xs text-gray-500 mt-1">Estrutura hierárquica na API Omie</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Omie</label>
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
                    <p class="text-xs text-gray-500 mt-1">Status do departamento na API Omie</p>
                </div>
            </div>
            
            @if($centroCusto->sincronizado_em)
            <div class="mt-4 pt-4 border-t border-blue-200">
                <p class="text-sm text-blue-600">
                    <i class="fas fa-clock mr-1"></i>
                    Última sincronização: {{ $centroCusto->sincronizado_em->format('d/m/Y H:i:s') }}
                </p>
            </div>
            @endif
        </div>
        @endif

        <!-- Status -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Status</h3>
            <div class="flex items-center">
                <input type="checkbox" 
                       id="active" 
                       name="active" 
                       value="1" 
                       {{ old('active', $centroCusto->active) ? 'checked' : '' }}
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="active" class="ml-2 block text-sm text-gray-700">
                    Centro de custo ativo
                </label>
            </div>
            <p class="text-xs text-gray-500 mt-1">Centros de custo inativos não aparecerão nas listagens do sistema</p>
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.centros-custo.show', $centroCusto) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                <i class="fas fa-save mr-2"></i>Salvar Alterações
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const baseSelect = document.getElementById('base_id');
    const regionalInput = document.getElementById('regional');
    const siglaInput = document.getElementById('sigla');
    const ufInput = document.getElementById('uf');
    const supervisorInput = document.getElementById('supervisor');
    
    const clienteOmieIdInput = document.getElementById('cliente_omie_id');
    const clienteNomeInput = document.getElementById('cliente_nome');
    const clienteLoading = document.getElementById('cliente-loading');
    
    let clienteTimeout;

    // Preenchimento automático da base
    baseSelect.addEventListener('change', function() {
        const baseId = this.value;
        
        if (baseId) {
            // Fazer requisição AJAX para buscar dados da base
            fetch(`{{ url('admin/centros-custo/base') }}/${baseId}/data`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        regionalInput.value = data.data.regional || '';
                        siglaInput.value = data.data.sigla || '';
                        ufInput.value = data.data.uf || '';
                        supervisorInput.value = data.data.supervisor || '';
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar dados da base:', error);
                    // Limpar campos em caso de erro
                    regionalInput.value = '';
                    siglaInput.value = '';
                    ufInput.value = '';
                    supervisorInput.value = '';
                });
        } else {
            // Limpar campos se nenhuma base for selecionada
            regionalInput.value = '';
            siglaInput.value = '';
            ufInput.value = '';
            supervisorInput.value = '';
        }
    });
    
    // Buscar dados do cliente OMIE por ID e Nome (funcionalidade bidirecional)
    let clienteOmieTimeout;
    let clienteNomeTimeout;
    let isUpdatingFromApi = false;

    // Busca por ID Cliente OMIE
    clienteOmieIdInput.addEventListener('input', function() {
        if (isUpdatingFromApi) return;
        
        clearTimeout(clienteOmieTimeout);
        let omieId = this.value.trim();
        
        if (omieId && omieId.length > 0 && /^\d+$/.test(omieId)) {
            clienteOmieTimeout = setTimeout(() => {
                buscarClienteOmiePorId(omieId);
            }, 500);
        } else if (!omieId) {
            // Limpar campo nome se ID for removido
            if (clienteNomeInput && !isUpdatingFromApi) {
                clienteNomeInput.value = '';
            }
        }
    });

    // Busca por Nome do Cliente
    let suggestionsList = document.getElementById('cliente_nome_suggestions');
    let loadingIndicator = document.getElementById('cliente-nome-loading');
    
    clienteNomeInput.addEventListener('input', function() {
        if (isUpdatingFromApi) return;
        
        clearTimeout(clienteNomeTimeout);
        let nomeCliente = this.value.trim();
        
        if (nomeCliente && nomeCliente.length >= 3) {
            clienteNomeTimeout = setTimeout(() => {
                buscarClienteOmiePorNome(nomeCliente);
            }, 500);
        } else {
            suggestionsList.style.display = 'none';
            if (!nomeCliente && !isUpdatingFromApi) {
                clienteOmieIdInput.value = '';
            }
        }
    });
    
    // Fechar sugestões ao clicar fora
    document.addEventListener('click', function(e) {
        if (!clienteNomeInput.contains(e.target) && !suggestionsList.contains(e.target)) {
            suggestionsList.style.display = 'none';
        }
    });
    
    function buscarClienteOmiePorId(omieId) {
        // Mostrar loading
        clienteLoading.classList.remove('hidden');
        
        // Fazer requisição para buscar dados do cliente
        fetch(`{{ url('/api/omie/clientes') }}/${omieId}`)
            .then(response => response.json())
            .then(data => {
                clienteLoading.classList.add('hidden');
                
                if (data.success && data.data) {
                    isUpdatingFromApi = true;
                    clienteNomeInput.value = data.data.nome || data.data.razao_social || '';
                    isUpdatingFromApi = false;
                    
                    // Feedback visual de sucesso
                    clienteOmieIdInput.classList.remove('border-red-500');
                    clienteOmieIdInput.classList.add('border-green-500');
                    
                    setTimeout(() => {
                        clienteOmieIdInput.classList.remove('border-green-500');
                    }, 2000);
                } else {
                    // Cliente não encontrado
                    isUpdatingFromApi = true;
                    clienteNomeInput.value = '';
                    isUpdatingFromApi = false;
                    
                    // Feedback visual de erro
                    clienteOmieIdInput.classList.remove('border-green-500');
                    clienteOmieIdInput.classList.add('border-red-500');
                    
                    setTimeout(() => {
                        clienteOmieIdInput.classList.remove('border-red-500');
                    }, 2000);
                }
            })
            .catch(error => {
                console.error('Erro ao buscar cliente OMIE:', error);
                clienteLoading.classList.add('hidden');
                isUpdatingFromApi = true;
                clienteNomeInput.value = '';
                isUpdatingFromApi = false;
                
                // Feedback visual de erro
                clienteOmieIdInput.classList.remove('border-green-500');
                clienteOmieIdInput.classList.add('border-red-500');
                
                setTimeout(() => {
                    clienteOmieIdInput.classList.remove('border-red-500');
                }, 2000);
            });
    }
    
    function buscarClienteOmiePorNome(nomeCliente) {
        // Mostrar loading
        loadingIndicator.classList.remove('hidden');
        
        // Fazer requisição para buscar clientes por nome
         fetch(`{{ url('/api/omie/clientes/search') }}?search=${encodeURIComponent(nomeCliente)}`)
            .then(response => response.json())
            .then(data => {
                loadingIndicator.classList.add('hidden');
                
                if (data.success && data.data && data.data.length > 0) {
                    mostrarSugestoesClientes(data.data);
                } else {
                    suggestionsList.innerHTML = '<div class="p-3 text-gray-500 text-sm">Nenhum cliente encontrado</div>';
                    suggestionsList.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Erro ao buscar clientes OMIE:', error);
                loadingIndicator.classList.add('hidden');
                suggestionsList.innerHTML = '<div class="p-3 text-red-500 text-sm">Erro ao buscar clientes</div>';
                suggestionsList.style.display = 'block';
            });
    }
    
    function mostrarSugestoesClientes(clientes) {
        let html = '';
        
        clientes.forEach(cliente => {
             html += `
                 <div class="p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-200 cliente-suggestion" 
                      data-id="${cliente.omie_id || cliente.id}" 
                      data-nome="${cliente.nome || cliente.razao_social}">
                     <div class="font-medium text-gray-900">${cliente.nome || cliente.razao_social}</div>
                     <div class="text-sm text-gray-500">ID: ${cliente.omie_id || cliente.id}</div>
                     ${cliente.documento ? `<div class="text-xs text-gray-400">${cliente.documento}</div>` : ''}
                 </div>
             `;
         });
        
        suggestionsList.innerHTML = html;
        suggestionsList.style.display = 'block';
        
        // Adicionar event listeners para as sugestões
        document.querySelectorAll('.cliente-suggestion').forEach(suggestion => {
            suggestion.addEventListener('click', function() {
                const clienteId = this.getAttribute('data-id');
                const clienteNome = this.getAttribute('data-nome');
                
                isUpdatingFromApi = true;
                clienteOmieIdInput.value = clienteId;
                clienteNomeInput.value = clienteNome;
                isUpdatingFromApi = false;
                
                suggestionsList.style.display = 'none';
                
                // Feedback visual de sucesso
                clienteNomeInput.classList.remove('border-red-500');
                clienteNomeInput.classList.add('border-green-500');
                
                setTimeout(() => {
                    clienteNomeInput.classList.remove('border-green-500');
                }, 2000);
            });
        });
    }
});
</script>
@endpush

@endsection