@extends('layouts.admin')

@section('title', 'Nova Base')

@section('content')
<!-- Header com gradiente -->
<div class="rounded-xl shadow-lg mb-6" style="background: linear-gradient(135deg, #1E3951 0%, #2A4A66 100%);">
    <div class="p-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-xl flex items-center justify-center" style="background: rgba(248, 171, 20, 0.2);">
                    <i class="fas fa-plus text-3xl" style="color: #F8AB14;"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Nova Base</h1>
                    <p class="text-blue-100">Cadastre uma nova base operacional no sistema</p>
                </div>
            </div>
            <a href="{{ route('admin.bases.index') }}" 
               class="flex items-center px-6 py-3 rounded-xl text-white transition-all duration-200 hover:scale-105" 
               style="background: rgba(248, 171, 20, 0.2); border: 1px solid rgba(248, 171, 20, 0.3);">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>
</div>

<!-- Breadcrumbs -->
<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium hover:text-blue-600" style="color: #1E3951;">
                <i class="fas fa-home mr-2"></i>Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <a href="{{ route('admin.bases.index') }}" class="text-sm font-medium hover:text-blue-600" style="color: #1E3951;">Bases</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <span class="text-sm font-medium text-gray-500">Nova Base</span>
            </div>
        </li>
    </ol>
</nav>

<!-- Formulário -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">

    <form action="{{ route('admin.bases.store') }}" method="POST" class="p-8">
        @csrf
        
        <!-- Dados Básicos -->
        <div class="mb-8">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-4" style="background: rgba(30, 57, 81, 0.1);">
                    <i class="fas fa-info-circle text-lg" style="color: #1E3951;"></i>
                </div>
                <h3 class="text-xl font-bold" style="color: #1E3951;">Dados Básicos</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="uf" class="block text-sm font-semibold mb-3" style="color: #1E3951;">UF *</label>
                    <select id="uf" 
                            name="uf" 
                            class="w-full px-4 py-3 border-2 rounded-xl transition-all duration-200 focus:outline-none @error('uf') border-red-400 @enderror" 
                            style="border-color: rgba(30, 57, 81, 0.2); focus:border-color: #F8AB14;" 
                            required>
                        <option value="">Selecione um estado</option>
                    </select>
                    @error('uf')
                        <p class="text-red-400 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                
                <div>
                    <label for="name" class="block text-sm font-semibold mb-3" style="color: #1E3951;">BASE (Cidade) *</label>
                    <select id="name" 
                            name="name" 
                            class="w-full px-4 py-3 border-2 rounded-xl transition-all duration-200 focus:outline-none @error('name') border-red-400 @enderror" 
                            style="border-color: rgba(30, 57, 81, 0.2); focus:border-color: #F8AB14;" 
                            required 
                            disabled>
                        <option value="">Primeiro selecione um estado</option>
                    </select>
                    @error('name')
                        <p class="text-red-400 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label for="regional" class="block text-sm font-semibold mb-3" style="color: #1E3951;">REGIONAL</label>
                    <input type="text" 
                           id="regional" 
                           name="regional" 
                           value="{{ old('regional') }}"
                           class="w-full px-4 py-3 border-2 rounded-xl transition-all duration-200 focus:outline-none" 
                           style="border-color: rgba(30, 57, 81, 0.2); background-color: #f8f9fa;" 
                           readonly>
                    <p class="text-xs text-gray-500 mt-2 flex items-center">
                        <i class="fas fa-info-circle mr-1"></i>Preenchido automaticamente
                    </p>
                    @error('regional')
                        <p class="text-red-400 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                
                <div>
                    <label for="sigla" class="block text-sm font-semibold mb-3" style="color: #1E3951;">SIGLA</label>
                    <input type="text" 
                           id="sigla" 
                           name="sigla" 
                           value="{{ old('sigla') }}"
                           maxlength="3"
                           placeholder="ABC"
                           style="text-transform: uppercase; border-color: rgba(30, 57, 81, 0.2);"
                           class="w-full px-4 py-3 border-2 rounded-xl transition-all duration-200 focus:outline-none @error('sigla') border-red-400 @enderror">
                    <p class="text-xs text-gray-500 mt-2 flex items-center">
                        <i class="fas fa-info-circle mr-1"></i>3 caracteres maiúsculos
                    </p>
                    @error('sigla')
                        <p class="text-red-400 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6">
                <div>
                    <label for="supervisor" class="block text-sm font-semibold mb-3" style="color: #1E3951;">SUPERVISOR</label>
                    <input type="text" 
                           id="supervisor" 
                           name="supervisor" 
                           value="{{ old('supervisor') }}"
                           placeholder="Nome do supervisor"
                           style="border-color: rgba(30, 57, 81, 0.2);"
                           class="w-full px-4 py-3 border-2 rounded-xl transition-all duration-200 focus:outline-none @error('supervisor') border-red-400 @enderror">
                    @error('supervisor')
                        <p class="text-red-400 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>



        <!-- Status -->
        <div class="mb-8">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-4" style="background: rgba(30, 57, 81, 0.1);">
                    <i class="fas fa-toggle-on text-lg" style="color: #1E3951;"></i>
                </div>
                <h3 class="text-xl font-bold" style="color: #1E3951;">Status</h3>
            </div>
            <div class="flex items-center p-4 rounded-xl" style="background: rgba(30, 57, 81, 0.05);">
                <input type="checkbox" 
                       id="active" 
                       name="active" 
                       value="1" 
                       {{ old('active', true) ? 'checked' : '' }}
                       class="h-5 w-5 rounded transition-all duration-200" 
                       style="accent-color: #F8AB14;">
                <label for="active" class="ml-3 block text-sm font-medium" style="color: #1E3951;">
                    <i class="fas fa-check-circle mr-2" style="color: #F8AB14;"></i>Base ativa
                </label>
            </div>
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-4 pt-6 border-t" style="border-color: rgba(30, 57, 81, 0.1);">
            <a href="{{ route('admin.bases.index') }}" 
               class="flex items-center px-6 py-3 rounded-xl text-white transition-all duration-200 hover:scale-105" 
               style="background: rgba(108, 117, 125, 0.8);">
                <i class="fas fa-times mr-2"></i>Cancelar
            </a>
            <button type="submit" 
                    class="flex items-center px-8 py-3 rounded-xl text-white font-semibold transition-all duration-200 hover:scale-105 shadow-lg" 
                    style="background: linear-gradient(135deg, #F8AB14 0%, #E09612 100%);">
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