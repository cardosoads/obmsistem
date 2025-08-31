@extends('layouts.admin')

@section('title', 'Novo Centro de Custo')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Novo Centro de Custo</h1>
        <a href="{{ route('admin.centros-custo.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    <form action="{{ route('admin.centros-custo.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Dados Básicos -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Dados do Centro de Custo</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="codigo" class="block text-sm font-medium text-gray-700 mb-2">CC (Código) *</label>
                    <input type="text" 
                           id="codigo" 
                           name="codigo" 
                           value="{{ old('codigo') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('codigo') border-red-500 @enderror" 
                           required>
                    <p class="text-xs text-gray-500 mt-1">Código único para identificação (ex: CC001, ADM, VEN)</p>
                    @error('codigo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome *</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
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
                          rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Descrição detalhada do centro de custo e suas responsabilidades</p>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Dados do Cliente OMIE -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Cliente (OMIE)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="cliente_omie_search" class="block text-sm font-medium text-gray-700 mb-2">Buscar Cliente</label>
                    <div class="relative">
                        <input type="text" 
                               id="cliente_omie_search" 
                               placeholder="Digite o nome do cliente para buscar..."
                               value="{{ old('cliente_nome') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               autocomplete="off">
                        <input type="hidden" 
                               id="cliente_omie_id" 
                               name="cliente_omie_id" 
                               value="{{ old('cliente_omie_id') }}">
                        <input type="hidden" 
                               id="cliente_nome" 
                               name="cliente_nome" 
                               value="{{ old('cliente_nome') }}">
                        
                        <!-- Dropdown de resultados -->
                        <div id="cliente_dropdown" 
                             class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto hidden">
                            <div id="cliente_loading" class="p-3 text-center text-gray-500 hidden">
                                <i class="fas fa-spinner fa-spin"></i> Buscando clientes...
                            </div>
                            <div id="cliente_results"></div>
                            <div id="cliente_no_results" class="p-3 text-center text-gray-500 hidden">
                                Nenhum cliente encontrado
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Cliente selecionado será buscado da API OMIE</p>
                    @error('cliente_omie_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="cliente_omie_id_display" class="block text-sm font-medium text-gray-700 mb-2">ID Cliente OMIE</label>
                    <input type="text" 
                           id="cliente_omie_id_display" 
                           value="{{ old('cliente_omie_id') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           readonly>
                    <p class="text-xs text-gray-500 mt-1">ID do cliente selecionado</p>
                </div>
            </div>
        </div>

        <!-- Base e Dados Relacionados -->
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
                            <option value="{{ $base->id }}" {{ old('base_id') == $base->id ? 'selected' : '' }}>
                                {{ $base->name }} - {{ $base->city }}/{{ $base->uf }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Selecione a base cadastrada</p>
                    @error('base_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="regional" class="block text-sm font-medium text-gray-700 mb-2">Regional</label>
                    <input type="text" 
                           id="regional" 
                           name="regional" 
                           value="{{ old('regional') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" 
                           readonly>
                    <p class="text-xs text-gray-500 mt-1">Preenchido automaticamente pela base selecionada</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div>
                    <label for="sigla" class="block text-sm font-medium text-gray-700 mb-2">Sigla</label>
                    <input type="text" 
                           id="sigla" 
                           name="sigla" 
                           value="{{ old('sigla') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" 
                           readonly>
                    <p class="text-xs text-gray-500 mt-1">Preenchido automaticamente pela base</p>
                </div>
                
                <div>
                    <label for="uf" class="block text-sm font-medium text-gray-700 mb-2">UF</label>
                    <input type="text" 
                           id="uf" 
                           name="uf" 
                           value="{{ old('uf') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" 
                           readonly>
                    <p class="text-xs text-gray-500 mt-1">Preenchido automaticamente pela base</p>
                </div>
                
                <div>
                    <label for="supervisor" class="block text-sm font-medium text-gray-700 mb-2">Supervisor</label>
                    <input type="text" 
                           id="supervisor" 
                           name="supervisor" 
                           value="{{ old('supervisor') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" 
                           readonly>
                    <p class="text-xs text-gray-500 mt-1">Preenchido automaticamente pela base</p>
                </div>
            </div>
        </div>

        <!-- Marca e Mercado -->
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
                            <option value="{{ $marca->id }}" {{ old('marca_id') == $marca->id ? 'selected' : '' }}>
                                {{ $marca->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Selecione a marca cadastrada</p>
                    @error('marca_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="mercado" class="block text-sm font-medium text-gray-700 mb-2">Mercado</label>
                    <input type="text" 
                           id="mercado" 
                           name="mercado" 
                           value="{{ old('mercado') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('mercado') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Mercado do centro de custo</p>
                    @error('mercado')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Status</h3>
            <div class="flex items-center">
                <input type="checkbox" 
                       id="active" 
                       name="active" 
                       value="1" 
                       {{ old('active', true) ? 'checked' : '' }}
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="active" class="ml-2 block text-sm text-gray-700">
                    Centro de custo ativo
                </label>
            </div>
            <p class="text-xs text-gray-500 mt-1">Centros de custo inativos não aparecerão nas listagens do sistema</p>
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.centros-custo.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                <i class="fas fa-save mr-2"></i>Salvar Centro de Custo
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
    const clienteLoading = document.getElementById('cliente_loading');
    
    let clienteTimeout;

    // Busca de clientes OMIE
    const clienteSearchInput = document.getElementById('cliente_omie_search');
    const clienteOmieIdDisplay = document.getElementById('cliente_omie_id_display');
    const clienteDropdown = document.getElementById('cliente_dropdown');
    const clienteResults = document.getElementById('cliente_results');
    const clienteNoResults = document.getElementById('cliente_no_results');
    
    let clienteSearchTimeout;
    
    if (clienteSearchInput) {
        clienteSearchInput.addEventListener('input', function() {
            const searchTerm = this.value.trim();
            
            clearTimeout(clienteSearchTimeout);
            
            if (searchTerm.length >= 2) {
                clienteSearchTimeout = setTimeout(() => {
                    searchClientes(searchTerm);
                }, 300);
            } else {
                hideClienteDropdown();
            }
        });
        
        // Esconder dropdown quando clicar fora
        document.addEventListener('click', function(e) {
            if (!clienteSearchInput.contains(e.target) && !clienteDropdown.contains(e.target)) {
                hideClienteDropdown();
            }
        });
    }
    
    function searchClientes(searchTerm) {
        showClienteLoading();
        
        fetch(`/api/omie/clientes/search?search=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(data => {
                hideClienteLoading();
                
                if (data.success && data.data && data.data.length > 0) {
                    showClienteResults(data.data);
                } else {
                    showClienteNoResults();
                }
            })
            .catch(error => {
                console.error('Erro ao buscar clientes:', error);
                hideClienteLoading();
                showClienteNoResults();
            });
    }
    
    function showClienteResults(clientes) {
        clienteResults.innerHTML = '';
        
        clientes.forEach(cliente => {
            const item = document.createElement('div');
            item.className = 'p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-200 last:border-b-0';
            item.innerHTML = `
                <div class="font-medium text-gray-900">${cliente.nome}</div>
                <div class="text-sm text-gray-500">ID: ${cliente.id}</div>
            `;
            
            item.addEventListener('click', function() {
                selectCliente(cliente);
            });
            
            clienteResults.appendChild(item);
        });
        
        clienteDropdown.classList.remove('hidden');
        clienteNoResults.classList.add('hidden');
    }
    
    function selectCliente(cliente) {
        clienteSearchInput.value = cliente.nome;
        clienteOmieIdInput.value = cliente.id;
        clienteNomeInput.value = cliente.nome;
        clienteOmieIdDisplay.value = cliente.id;
        
        hideClienteDropdown();
    }
    
    function showClienteLoading() {
        clienteLoading.classList.remove('hidden');
        clienteResults.innerHTML = '';
        clienteNoResults.classList.add('hidden');
        clienteDropdown.classList.remove('hidden');
    }
    
    function hideClienteLoading() {
        clienteLoading.classList.add('hidden');
    }
    
    function showClienteNoResults() {
        clienteNoResults.classList.remove('hidden');
        clienteResults.innerHTML = '';
        clienteDropdown.classList.remove('hidden');
    }
    
    function hideClienteDropdown() {
        clienteDropdown.classList.add('hidden');
        clienteLoading.classList.add('hidden');
        clienteNoResults.classList.add('hidden');
    }

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
    

});
</script>
@endpush

@endsection