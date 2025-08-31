@extends('layouts.admin')

@section('title', 'Teste - Select de Clientes')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            <i class="fas fa-flask text-blue-600 mr-2"></i>
            Página de Teste - Select de Clientes
        </h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Select com busca dinâmica -->
            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-gray-700">
                    <i class="fas fa-search text-green-600 mr-2"></i>
                    Select com Busca Dinâmica
                </h2>
                
                <div class="relative">
                    <label for="cliente-select" class="block text-sm font-medium text-gray-700 mb-2">
                        Buscar Cliente:
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="cliente-search" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            placeholder="Digite o nome do cliente..."
                            autocomplete="off"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                    
                    <!-- Dropdown de resultados -->
                    <div id="cliente-dropdown" class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg shadow-lg mt-1 hidden max-h-60 overflow-y-auto">
                        <!-- Resultados serão inseridos aqui via JavaScript -->
                    </div>
                </div>
                
                <!-- Cliente selecionado -->
                <div id="cliente-selecionado" class="hidden p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h3 class="font-semibold text-blue-800 mb-2">Cliente Selecionado:</h3>
                    <div id="cliente-info" class="text-sm text-blue-700">
                        <!-- Informações do cliente serão inseridas aqui -->
                    </div>
                </div>
            </div>
            
            <!-- Select tradicional -->
            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-gray-700">
                    <i class="fas fa-list text-purple-600 mr-2"></i>
                    Select Tradicional
                </h2>
                
                <div>
                    <label for="cliente-tradicional" class="block text-sm font-medium text-gray-700 mb-2">
                        Selecionar Cliente:
                    </label>
                    <select id="cliente-tradicional" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">Carregando clientes...</option>
                    </select>
                </div>
                
                <button 
                    id="carregar-clientes" 
                    class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors duration-200"
                >
                    <i class="fas fa-sync-alt mr-2"></i>
                    Carregar Todos os Clientes
                </button>
            </div>
        </div>
        
        <!-- Log de ações -->
        <div class="mt-8">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">
                <i class="fas fa-terminal text-orange-600 mr-2"></i>
                Log de Ações
            </h2>
            <div id="log-container" class="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-sm h-40 overflow-y-auto">
                <div class="text-gray-500">Aguardando ações...</div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clienteSearch = document.getElementById('cliente-search');
    const clienteDropdown = document.getElementById('cliente-dropdown');
    const clienteSelecionado = document.getElementById('cliente-selecionado');
    const clienteInfo = document.getElementById('cliente-info');
    const clienteTradicional = document.getElementById('cliente-tradicional');
    const carregarClientesBtn = document.getElementById('carregar-clientes');
    const logContainer = document.getElementById('log-container');
    
    let searchTimeout;
    let clienteSelecionadoData = null;
    
    // Função para adicionar log
    function addLog(message, type = 'info') {
        const timestamp = new Date().toLocaleTimeString();
        const colors = {
            info: 'text-blue-400',
            success: 'text-green-400',
            error: 'text-red-400',
            warning: 'text-yellow-400'
        };
        
        const logEntry = document.createElement('div');
        logEntry.className = colors[type] || 'text-gray-400';
        logEntry.innerHTML = `[${timestamp}] ${message}`;
        
        logContainer.appendChild(logEntry);
        logContainer.scrollTop = logContainer.scrollHeight;
    }
    
    // Busca dinâmica de clientes
    clienteSearch.addEventListener('input', function() {
        const searchTerm = this.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (searchTerm.length === 0) {
            clienteDropdown.classList.add('hidden');
            clienteSelecionado.classList.add('hidden');
            clienteSelecionadoData = null;
            return;
        }
        
        if (searchTerm.length < 2) {
            clienteDropdown.innerHTML = '<div class="p-3 text-gray-500 text-center">Digite pelo menos 2 caracteres...</div>';
            clienteDropdown.classList.remove('hidden');
            addLog('Aguardando mais caracteres...', 'warning');
            return;
        }
        
        // Mostrar indicador de carregamento
        clienteDropdown.innerHTML = '<div class="p-3 text-center"><i class="fas fa-spinner fa-spin text-blue-500 mr-2"></i>Buscando...</div>';
        clienteDropdown.classList.remove('hidden');
        
        searchTimeout = setTimeout(() => {
            buscarClientes(searchTerm);
        }, 300);
    });
    
    // Função para buscar clientes
    async function buscarClientes(searchTerm) {
        try {
            addLog(`Buscando clientes com termo: "${searchTerm}"`, 'info');
            
            const response = await fetch(`/api/omie/clientes/search?search=${encodeURIComponent(searchTerm)}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            
            if (data.success && data.data && data.data.length > 0) {
                addLog(`Encontrados ${data.data.length} clientes`, 'success');
                mostrarResultados(data.data);
            } else {
                addLog('Nenhum cliente encontrado', 'warning');
                mostrarResultados([]);
            }
            
        } catch (error) {
            addLog(`Erro na busca: ${error.message}`, 'error');
            console.error('Erro ao buscar clientes:', error);
        }
    }
    
    // Função para mostrar resultados
    function mostrarResultados(clientes) {
        clienteDropdown.innerHTML = '';
        
        if (clientes.length === 0) {
            clienteDropdown.innerHTML = '<div class="p-3 text-gray-500 text-center"><i class="fas fa-search text-gray-400 mr-2"></i>Nenhum cliente encontrado</div>';
            clienteDropdown.classList.remove('hidden');
            return;
        }
        
        clientes.forEach((cliente, index) => {
            const item = document.createElement('div');
            item.className = 'p-3 hover:bg-blue-50 cursor-pointer border-b border-gray-200 last:border-b-0 transition-colors duration-150';
            item.setAttribute('data-index', index);
            item.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">${cliente.nome_fantasia || cliente.razao_social}</div>
                        <div class="text-sm text-gray-600">
                            ${cliente.cnpj_cpf ? `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800 mr-2">${cliente.cnpj_cpf}</span>` : ''}
                            ${cliente.cidade ? `<span class="text-gray-500">${cliente.cidade}${cliente.estado ? ', ' + cliente.estado : ''}</span>` : ''}
                        </div>
                    </div>
                    <div class="text-gray-400">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </div>
                </div>
            `;
            
            item.addEventListener('click', () => {
                selecionarCliente(cliente);
            });
            
            item.addEventListener('mouseenter', () => {
                // Remove highlight de outros itens
                document.querySelectorAll('#cliente-dropdown > div').forEach(el => {
                    el.classList.remove('bg-blue-50');
                });
                item.classList.add('bg-blue-50');
            });
            
            clienteDropdown.appendChild(item);
        });
        
        clienteDropdown.classList.remove('hidden');
    }
    
    // Função para selecionar cliente
    function selecionarCliente(cliente) {
        clienteSelecionadoData = cliente;
        clienteSearch.value = cliente.nome_fantasia || cliente.razao_social;
        clienteDropdown.classList.add('hidden');
        
        // Mostrar informações do cliente
        clienteInfo.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <div><strong>ID:</strong> ${cliente.codigo_cliente_omie}</div>
                <div><strong>Razão Social:</strong> ${cliente.razao_social}</div>
                <div><strong>Nome Fantasia:</strong> ${cliente.nome_fantasia || 'N/A'}</div>
                <div><strong>CNPJ/CPF:</strong> ${cliente.cnpj_cpf || 'N/A'}</div>
                <div><strong>Cidade:</strong> ${cliente.cidade || 'N/A'}</div>
                <div><strong>UF:</strong> ${cliente.estado || 'N/A'}</div>
            </div>
        `;
        
        clienteSelecionado.classList.remove('hidden');
        addLog(`Cliente selecionado: ${cliente.nome_fantasia || cliente.razao_social}`, 'success');
    }
    
    // Carregar todos os clientes no select tradicional
    carregarClientesBtn.addEventListener('click', async function() {
        try {
            addLog('Carregando todos os clientes...', 'info');
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Carregando...';
            
            const response = await fetch('/api/omie/clientes/search?search=', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            
            if (data.success && data.data) {
                clienteTradicional.innerHTML = '<option value="">Selecione um cliente...</option>';
                
                data.data.forEach(cliente => {
                    const option = document.createElement('option');
                    option.value = cliente.codigo_cliente_omie;
                    option.textContent = `${cliente.nome_fantasia || cliente.razao_social} (${cliente.cnpj_cpf || 'S/N'})`;
                    clienteTradicional.appendChild(option);
                });
                
                addLog(`${data.data.length} clientes carregados no select`, 'success');
            }
            
        } catch (error) {
            addLog(`Erro ao carregar clientes: ${error.message}`, 'error');
            console.error('Erro ao carregar clientes:', error);
        } finally {
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Carregar Todos os Clientes';
        }
    });
    
    // Navegação por teclado no dropdown
    let selectedIndex = -1;
    
    clienteSearch.addEventListener('keydown', function(event) {
        const items = clienteDropdown.querySelectorAll('[data-index]');
        
        if (items.length === 0) return;
        
        switch(event.key) {
            case 'ArrowDown':
                event.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                highlightItem(items, selectedIndex);
                break;
                
            case 'ArrowUp':
                event.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                highlightItem(items, selectedIndex);
                break;
                
            case 'Enter':
                event.preventDefault();
                if (selectedIndex >= 0 && items[selectedIndex]) {
                    items[selectedIndex].click();
                }
                break;
                
            case 'Escape':
                clienteDropdown.classList.add('hidden');
                selectedIndex = -1;
                break;
        }
    });
    
    function highlightItem(items, index) {
        // Remove highlight de todos os itens
        items.forEach(item => {
            item.classList.remove('bg-blue-100', 'border-blue-200');
        });
        
        // Adiciona highlight ao item selecionado
        if (index >= 0 && items[index]) {
            items[index].classList.add('bg-blue-100', 'border-blue-200');
            items[index].scrollIntoView({ block: 'nearest' });
        }
    }
    
    // Reset do índice selecionado quando novos resultados aparecem
    const originalMostrarResultados = mostrarResultados;
    mostrarResultados = function(clientes) {
        selectedIndex = -1;
        originalMostrarResultados(clientes);
    };
    
    // Fechar dropdown ao clicar fora
    document.addEventListener('click', function(event) {
        if (!clienteSearch.contains(event.target) && !clienteDropdown.contains(event.target)) {
            clienteDropdown.classList.add('hidden');
            selectedIndex = -1;
        }
    });
    
    // Focar no campo de busca ao clicar no dropdown
    clienteDropdown.addEventListener('click', function(event) {
        event.stopPropagation();
    });
    
    // Log inicial
    addLog('Página de teste carregada', 'success');
});
</script>
@endsection