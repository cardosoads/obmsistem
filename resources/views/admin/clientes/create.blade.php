@extends('layouts.admin')

@section('title', 'Novo Cliente')
@section('page-title', 'Novo Cliente')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Cadastrar Novo Cliente</h3>
                <a href="{{ route('admin.clientes.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.clientes.store') }}" class="p-6">
            @csrf
            
            <!-- Dados Básicos -->
            <div class="mb-8">
                <h4 class="text-md font-medium text-gray-900 mb-4">Dados Básicos</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome Completo *</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-300 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-mail *</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-300 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Documento *</label>
                        <select id="tipo_documento" 
                                name="tipo_documento" 
                                required
                                onchange="toggleDocumentMask()"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('tipo_documento') border-red-300 @enderror">
                            <option value="">Selecione...</option>
                            <option value="cpf" {{ old('tipo_documento') === 'cpf' ? 'selected' : '' }}>CPF</option>
                            <option value="cnpj" {{ old('tipo_documento') === 'cnpj' ? 'selected' : '' }}>CNPJ</option>
                        </select>
                        @error('tipo_documento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="documento" class="block text-sm font-medium text-gray-700 mb-2">Documento *</label>
                        <input type="text" 
                               id="documento" 
                               name="documento" 
                               value="{{ old('documento') }}"
                               required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('documento') border-red-300 @enderror">
                        @error('documento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Contato -->
            <div class="mb-8">
                <h4 class="text-md font-medium text-gray-900 mb-4">Contato</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="telefone" class="block text-sm font-medium text-gray-700 mb-2">Telefone</label>
                        <input type="text" 
                               id="telefone" 
                               name="telefone" 
                               value="{{ old('telefone') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('telefone') border-red-300 @enderror">
                        @error('telefone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="celular" class="block text-sm font-medium text-gray-700 mb-2">Celular</label>
                        <input type="text" 
                               id="celular" 
                               name="celular" 
                               value="{{ old('celular') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('celular') border-red-300 @enderror">
                        @error('celular')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Endereço -->
            <div class="mb-8">
                <h4 class="text-md font-medium text-gray-900 mb-4">Endereço</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                    <div>
                        <label for="cep" class="block text-sm font-medium text-gray-700 mb-2">CEP</label>
                        <input type="text" 
                               id="cep" 
                               name="cep" 
                               value="{{ old('cep') }}"
                               onblur="buscarCep()"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('cep') border-red-300 @enderror">
                        @error('cep')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="endereco" class="block text-sm font-medium text-gray-700 mb-2">Endereço</label>
                        <input type="text" 
                               id="endereco" 
                               name="endereco" 
                               value="{{ old('endereco') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('endereco') border-red-300 @enderror">
                        @error('endereco')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-4">
                    <div>
                        <label for="numero" class="block text-sm font-medium text-gray-700 mb-2">Número</label>
                        <input type="text" 
                               id="numero" 
                               name="numero" 
                               value="{{ old('numero') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('numero') border-red-300 @enderror">
                        @error('numero')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="complemento" class="block text-sm font-medium text-gray-700 mb-2">Complemento</label>
                        <input type="text" 
                               id="complemento" 
                               name="complemento" 
                               value="{{ old('complemento') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('complemento') border-red-300 @enderror">
                        @error('complemento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="bairro" class="block text-sm font-medium text-gray-700 mb-2">Bairro</label>
                        <input type="text" 
                               id="bairro" 
                               name="bairro" 
                               value="{{ old('bairro') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('bairro') border-red-300 @enderror">
                        @error('bairro')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="cidade" class="block text-sm font-medium text-gray-700 mb-2">Cidade</label>
                        <input type="text" 
                               id="cidade" 
                               name="cidade" 
                               value="{{ old('cidade') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('cidade') border-red-300 @enderror">
                        @error('cidade')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <select id="estado" 
                                name="estado" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('estado') border-red-300 @enderror">
                            <option value="">Selecione...</option>
                            <option value="AC" {{ old('estado') === 'AC' ? 'selected' : '' }}>Acre</option>
                            <option value="AL" {{ old('estado') === 'AL' ? 'selected' : '' }}>Alagoas</option>
                            <option value="AP" {{ old('estado') === 'AP' ? 'selected' : '' }}>Amapá</option>
                            <option value="AM" {{ old('estado') === 'AM' ? 'selected' : '' }}>Amazonas</option>
                            <option value="BA" {{ old('estado') === 'BA' ? 'selected' : '' }}>Bahia</option>
                            <option value="CE" {{ old('estado') === 'CE' ? 'selected' : '' }}>Ceará</option>
                            <option value="DF" {{ old('estado') === 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                            <option value="ES" {{ old('estado') === 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                            <option value="GO" {{ old('estado') === 'GO' ? 'selected' : '' }}>Goiás</option>
                            <option value="MA" {{ old('estado') === 'MA' ? 'selected' : '' }}>Maranhão</option>
                            <option value="MT" {{ old('estado') === 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                            <option value="MS" {{ old('estado') === 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                            <option value="MG" {{ old('estado') === 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                            <option value="PA" {{ old('estado') === 'PA' ? 'selected' : '' }}>Pará</option>
                            <option value="PB" {{ old('estado') === 'PB' ? 'selected' : '' }}>Paraíba</option>
                            <option value="PR" {{ old('estado') === 'PR' ? 'selected' : '' }}>Paraná</option>
                            <option value="PE" {{ old('estado') === 'PE' ? 'selected' : '' }}>Pernambuco</option>
                            <option value="PI" {{ old('estado') === 'PI' ? 'selected' : '' }}>Piauí</option>
                            <option value="RJ" {{ old('estado') === 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                            <option value="RN" {{ old('estado') === 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                            <option value="RS" {{ old('estado') === 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                            <option value="RO" {{ old('estado') === 'RO' ? 'selected' : '' }}>Rondônia</option>
                            <option value="RR" {{ old('estado') === 'RR' ? 'selected' : '' }}>Roraima</option>
                            <option value="SC" {{ old('estado') === 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                            <option value="SP" {{ old('estado') === 'SP' ? 'selected' : '' }}>São Paulo</option>
                            <option value="SE" {{ old('estado') === 'SE' ? 'selected' : '' }}>Sergipe</option>
                            <option value="TO" {{ old('estado') === 'TO' ? 'selected' : '' }}>Tocantins</option>
                        </select>
                        @error('estado')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Observações -->
            <div class="mb-8">
                <h4 class="text-md font-medium text-gray-900 mb-4">Observações</h4>
                
                <div>
                    <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-2">Observações</label>
                    <textarea id="observacoes" 
                              name="observacoes" 
                              rows="4"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('observacoes') border-red-300 @enderror">{{ old('observacoes') }}</textarea>
                    @error('observacoes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Status -->
            <div class="mb-8">
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="ativo" 
                           name="ativo" 
                           value="1"
                           {{ old('ativo', true) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <label for="ativo" class="ml-2 block text-sm text-gray-900">Cliente ativo</label>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.clientes.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-200 disabled:opacity-25 transition ease-in-out duration-150">
                    Cancelar
                </a>
                
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <i class="fas fa-save mr-2"></i>
                    Salvar Cliente
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
// Máscaras
$(document).ready(function() {
    $('#telefone').mask('(00) 0000-0000');
    $('#celular').mask('(00) 00000-0000');
    $('#cep').mask('00000-000');
    
    toggleDocumentMask();
});

function toggleDocumentMask() {
    const tipoDocumento = document.getElementById('tipo_documento').value;
    const documentoInput = $('#documento');
    
    documentoInput.unmask();
    
    if (tipoDocumento === 'cpf') {
        documentoInput.mask('000.000.000-00');
    } else if (tipoDocumento === 'cnpj') {
        documentoInput.mask('00.000.000/0000-00');
    }
}

function buscarCep() {
    const cep = document.getElementById('cep').value.replace(/\D/g, '');
    
    if (cep.length === 8) {
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    document.getElementById('endereco').value = data.logradouro;
                    document.getElementById('bairro').value = data.bairro;
                    document.getElementById('cidade').value = data.localidade;
                    document.getElementById('estado').value = data.uf;
                }
            })
            .catch(error => {
                console.error('Erro ao buscar CEP:', error);
            });
    }
}
</script>
@endsection