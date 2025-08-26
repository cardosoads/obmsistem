@extends('layouts.admin')

@section('title', 'Nova Base')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Nova Base</h1>
        <a href="{{ route('admin.bases.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    <form action="{{ route('admin.bases.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Dados Básicos -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Dados Básicos</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="uf" class="block text-sm font-medium text-gray-700 mb-2">UF *</label>
                    <select id="uf" 
                            name="uf" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('uf') border-red-500 @enderror" 
                            required>
                        <option value="">Selecione um estado</option>
                    </select>
                    @error('uf')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">BASE (Cidade) *</label>
                    <select id="name" 
                            name="name" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" 
                            required 
                            disabled>
                        <option value="">Primeiro selecione um estado</option>
                    </select>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="regional" class="block text-sm font-medium text-gray-700 mb-2">REGIONAL</label>
                    <input type="text" 
                           id="regional" 
                           name="regional" 
                           value="{{ old('regional') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none" 
                           readonly>
                    <p class="text-xs text-gray-500 mt-1">Preenchido automaticamente</p>
                    @error('regional')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="sigla" class="block text-sm font-medium text-gray-700 mb-2">SIGLA</label>
                    <input type="text" 
                           id="sigla" 
                           name="sigla" 
                           value="{{ old('sigla') }}"
                           maxlength="3"
                           placeholder="ABC"
                           style="text-transform: uppercase;"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sigla') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">3 caracteres maiúsculos</p>
                    @error('sigla')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-4">
                <div>
                    <label for="supervisor" class="block text-sm font-medium text-gray-700 mb-2">SUPERVISOR</label>
                    <input type="text" 
                           id="supervisor" 
                           name="supervisor" 
                           value="{{ old('supervisor') }}"
                           placeholder="Nome do supervisor"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('supervisor') border-red-500 @enderror">
                    @error('supervisor')
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
                    Base ativa
                </label>
            </div>
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.bases.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                <i class="fas fa-save mr-2"></i>Salvar Base
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let estados = [];
    let municipios = [];
    
    // Carregar estados do IBGE
    carregarEstados();
    
    async function carregarEstados() {
        try {
            const response = await fetch('/admin/localidades/estados');
            const data = await response.json();
            
            if (data.success) {
                estados = data.data;
                popularSelectEstados();
            } else {
                console.error('Erro ao carregar estados:', data.message);
                mostrarErro('Erro ao carregar estados. Tente recarregar a página.');
            }
        } catch (error) {
            console.error('Erro ao carregar estados:', error);
            mostrarErro('Erro de conexão ao carregar estados.');
        }
    }
    
    function popularSelectEstados() {
        const ufSelect = document.getElementById('uf');
        
        // Limpar opções existentes (exceto a primeira)
        ufSelect.innerHTML = '<option value="">Selecione um estado</option>';
        
        // Adicionar estados
        estados.forEach(estado => {
            const option = document.createElement('option');
            option.value = estado.sigla;
            option.textContent = `${estado.nome} (${estado.sigla})`;
            option.dataset.regiao = estado.regiao.nome;
            ufSelect.appendChild(option);
        });
    }
    
    // Carregar municípios quando UF for selecionada
    document.getElementById('uf').addEventListener('change', async function() {
        const uf = this.value;
        const citySelect = document.getElementById('name');
        const regionalInput = document.getElementById('regional');
        
        if (uf) {
            // Atualizar regional
            const selectedOption = this.options[this.selectedIndex];
            regionalInput.value = selectedOption.dataset.regiao || '';
            
            // Carregar municípios
            citySelect.disabled = true;
            citySelect.innerHTML = '<option value="">Carregando cidades...</option>';
            
            try {
                const response = await fetch(`/admin/localidades/municipios/${uf}`);
                const data = await response.json();
                
                if (data.success) {
                    municipios = data.data;
                    popularSelectCidades();
                } else {
                    console.error('Erro ao carregar municípios:', data.message);
                    mostrarErro('Erro ao carregar cidades. Tente novamente.');
                    citySelect.innerHTML = '<option value="">Erro ao carregar cidades</option>';
                }
            } catch (error) {
                console.error('Erro ao carregar municípios:', error);
                mostrarErro('Erro de conexão ao carregar cidades.');
                citySelect.innerHTML = '<option value="">Erro ao carregar cidades</option>';
            }
        } else {
            // Limpar campos dependentes
            citySelect.disabled = true;
            citySelect.innerHTML = '<option value="">Primeiro selecione um estado</option>';
            regionalInput.value = '';
        }
    });
    
    function popularSelectCidades() {
        const citySelect = document.getElementById('name');
        
        // Limpar e habilitar select
        citySelect.innerHTML = '<option value="">Selecione uma cidade</option>';
        citySelect.disabled = false;
        
        // Adicionar municípios
        municipios.forEach(municipio => {
            const option = document.createElement('option');
            option.value = municipio.nome;
            option.textContent = municipio.nome;
            citySelect.appendChild(option);
        });
    }
    
    function mostrarErro(mensagem) {
        // Criar ou atualizar div de erro
        let errorDiv = document.getElementById('localidades-error');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.id = 'localidades-error';
            errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
            document.querySelector('form').insertBefore(errorDiv, document.querySelector('form').firstChild);
        }
        errorDiv.textContent = mensagem;
        
        // Remover após 5 segundos
        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.parentNode.removeChild(errorDiv);
            }
        }, 5000);
    }
    
    // Converter sigla para maiúsculo e remover acentos
    document.getElementById('sigla').addEventListener('input', function(e) {
        let value = e.target.value;
        // Remover acentos e caracteres especiais
        value = value.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        // Manter apenas letras, números e espaços
        value = value.replace(/[^A-Za-z0-9\s]/g, '');
        // Converter para maiúsculo
        value = value.toUpperCase();
        // Limitar a 3 caracteres
        value = value.substring(0, 3);
        e.target.value = value;
    });
    
    // Máscara para CEP e busca automática
    const cep = document.getElementById('cep');
    cep.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
        e.target.value = value;
        
        // Buscar endereço quando CEP estiver completo
        if (value.length === 9) {
            buscarCep(value.replace('-', ''));
        }
    });
    
    function buscarCep(cep) {
        if (cep.length === 8) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        document.getElementById('logradouro').value = data.logradouro || '';
                        document.getElementById('bairro').value = data.bairro || '';
                        document.getElementById('cidade').value = data.localidade || '';
                        document.getElementById('estado').value = data.uf || '';
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar CEP:', error);
                });
        }
    }
    
    // Converter estado para maiúsculo
    const estado = document.getElementById('estado');
    estado.addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase();
    });
});
</script>
@endsection