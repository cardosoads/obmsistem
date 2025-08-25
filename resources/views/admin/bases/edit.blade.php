@extends('layouts.admin')

@section('title', 'Editar Base')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Editar Base</h1>
        <a href="{{ route('admin.bases.show', $base->id) }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    <form action="{{ route('admin.bases.update', $base->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Dados Básicos -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Dados Básicos</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">BASE (Cidade) *</label>
                    <select id="city" 
                            name="city" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('city') border-red-500 @enderror" 
                            required>
                        <option value="">Selecione uma cidade</option>
                    </select>
                    @error('city')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="uf" class="block text-sm font-medium text-gray-700 mb-2">UF</label>
                    <input type="text" 
                           id="uf" 
                           name="uf" 
                           value="{{ old('uf', $base->uf) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none" 
                           readonly>
                    <p class="text-xs text-gray-500 mt-1">Preenchido automaticamente</p>
                    @error('uf')
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
                           value="{{ old('regional', $base->regional) }}"
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
                           value="{{ old('sigla', $base->sigla) }}"
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
                           value="{{ old('supervisor', $base->supervisor) }}"
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
                       {{ old('active', $base->active) ? 'checked' : '' }}
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="active" class="ml-2 block text-sm text-gray-700">
                    Base ativa
                </label>
            </div>
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.bases.show', $base->id) }}" 
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    let cidadesBrasil = [];
    
    // Carregar dados das cidades
    fetch('/cidades-brasileiras.json')
        .then(response => response.json())
        .then(data => {
            cidadesBrasil = data;
            populateCitySelect();
        })
        .catch(error => {
            console.error('Erro ao carregar cidades:', error);
        });
    
    // Popular o select de cidades
    function populateCitySelect() {
        const citySelect = document.getElementById('city');
        const allCities = [];
        
        // Coletar todas as cidades de todos os estados
        Object.keys(cidadesBrasil).forEach(estado => {
            cidadesBrasil[estado].cidades.forEach(cidade => {
                allCities.push({
                    nome: cidade,
                    uf: estado,
                    regiao: cidadesBrasil[estado].regiao
                });
            });
        });
        
        // Ordenar cidades alfabeticamente
        allCities.sort((a, b) => a.nome.localeCompare(b.nome));
        
        // Adicionar opções ao select
        allCities.forEach(cidade => {
            const option = document.createElement('option');
            option.value = cidade.nome;
            option.textContent = `${cidade.nome} - ${cidade.uf}`;
            option.dataset.uf = cidade.uf;
            option.dataset.regiao = cidade.regiao;
            
            // Selecionar a cidade atual se estiver editando
            if (cidade.nome === '{{ old("city", $base->city ?? "") }}') {
                option.selected = true;
            }
            
            citySelect.appendChild(option);
        });
    }
    
    // Atualizar UF e Regional quando cidade for selecionada
    document.getElementById('city').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            document.getElementById('uf').value = selectedOption.dataset.uf || '';
            document.getElementById('regional').value = selectedOption.dataset.regiao || '';
        } else {
            document.getElementById('uf').value = '';
            document.getElementById('regional').value = '';
        }
    });
    
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
    if (cep) {
        cep.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
            
            // Buscar endereço quando CEP estiver completo
            if (value.length === 9) {
                buscarCep(value.replace('-', ''));
            }
        });
    }
    
    function buscarCep(cep) {
        if (cep.length === 8) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        const logradouro = document.getElementById('logradouro');
                        const bairro = document.getElementById('bairro');
                        const cidade = document.getElementById('cidade');
                        const estado = document.getElementById('estado');
                        
                        if (logradouro) logradouro.value = data.logradouro || '';
                        if (bairro) bairro.value = data.bairro || '';
                        if (cidade) cidade.value = data.localidade || '';
                        if (estado) estado.value = data.uf || '';
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar CEP:', error);
                });
        }
    }
    
    // Converter estado para maiúsculo
    const estado = document.getElementById('estado');
    if (estado) {
        estado.addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });
    }
});
</script>
@endsection