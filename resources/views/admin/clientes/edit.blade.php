@extends('layouts.admin')

@section('title', 'Editar Cliente')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Editar Cliente</h1>
        <a href="{{ route('admin.clientes.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    <form action="{{ route('admin.clientes.update', $cliente->id) }}" method="POST" id="clienteForm">
        @csrf
        @method('PUT')
        
        <!-- Dados Básicos -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Dados Básicos</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">Nome *</label>
                    <input type="text" id="nome" name="nome" value="{{ old('nome', $cliente->nome) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nome') border-red-500 @enderror" 
                           required>
                    @error('nome')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-mail</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $cliente->email) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Documento *</label>
                    <select id="tipo_documento" name="tipo_documento" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tipo_documento') border-red-500 @enderror" 
                            required>
                        <option value="">Selecione o tipo</option>
                        <option value="cpf" {{ old('tipo_documento', $cliente->tipo_documento) == 'cpf' ? 'selected' : '' }}>CPF</option>
                        <option value="cnpj" {{ old('tipo_documento', $cliente->tipo_documento) == 'cnpj' ? 'selected' : '' }}>CNPJ</option>
                    </select>
                    @error('tipo_documento')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="documento" class="block text-sm font-medium text-gray-700 mb-2">Documento *</label>
                    <input type="text" id="documento" name="documento" value="{{ old('documento', $cliente->documento) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('documento') border-red-500 @enderror" 
                           required>
                    @error('documento')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Contato -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Contato</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="telefone" class="block text-sm font-medium text-gray-700 mb-2">Telefone</label>
                    <input type="text" id="telefone" name="telefone" value="{{ old('telefone', $cliente->telefone) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('telefone') border-red-500 @enderror" 
                           placeholder="(11) 1234-5678">
                    @error('telefone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="celular" class="block text-sm font-medium text-gray-700 mb-2">Celular</label>
                    <input type="text" id="celular" name="celular" value="{{ old('celular', $cliente->celular) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('celular') border-red-500 @enderror" 
                           placeholder="(11) 91234-5678">
                    @error('celular')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Endereço -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Endereço</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="cep" class="block text-sm font-medium text-gray-700 mb-2">CEP</label>
                    <input type="text" id="cep" name="cep" value="{{ old('cep', $cliente->cep) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cep') border-red-500 @enderror" 
                           placeholder="12345-678">
                    @error('cep')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="logradouro" class="block text-sm font-medium text-gray-700 mb-2">Logradouro</label>
                    <input type="text" id="logradouro" name="logradouro" value="{{ old('logradouro', $cliente->logradouro) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('logradouro') border-red-500 @enderror">
                    @error('logradouro')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label for="numero" class="block text-sm font-medium text-gray-700 mb-2">Número</label>
                    <input type="text" id="numero" name="numero" value="{{ old('numero', $cliente->numero) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('numero') border-red-500 @enderror">
                    @error('numero')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="complemento" class="block text-sm font-medium text-gray-700 mb-2">Complemento</label>
                    <input type="text" id="complemento" name="complemento" value="{{ old('complemento', $cliente->complemento) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('complemento') border-red-500 @enderror">
                    @error('complemento')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="bairro" class="block text-sm font-medium text-gray-700 mb-2">Bairro</label>
                    <input type="text" id="bairro" name="bairro" value="{{ old('bairro', $cliente->bairro) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('bairro') border-red-500 @enderror">
                    @error('bairro')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="cidade" class="block text-sm font-medium text-gray-700 mb-2">Cidade</label>
                    <input type="text" id="cidade" name="cidade" value="{{ old('cidade', $cliente->cidade) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cidade') border-red-500 @enderror">
                    @error('cidade')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select id="estado" name="estado" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('estado') border-red-500 @enderror">
                        <option value="">Selecione o estado</option>
                        <option value="AC" {{ old('estado', $cliente->estado) == 'AC' ? 'selected' : '' }}>Acre</option>
                        <option value="AL" {{ old('estado', $cliente->estado) == 'AL' ? 'selected' : '' }}>Alagoas</option>
                        <option value="AP" {{ old('estado', $cliente->estado) == 'AP' ? 'selected' : '' }}>Amapá</option>
                        <option value="AM" {{ old('estado', $cliente->estado) == 'AM' ? 'selected' : '' }}>Amazonas</option>
                        <option value="BA" {{ old('estado', $cliente->estado) == 'BA' ? 'selected' : '' }}>Bahia</option>
                        <option value="CE" {{ old('estado', $cliente->estado) == 'CE' ? 'selected' : '' }}>Ceará</option>
                        <option value="DF" {{ old('estado', $cliente->estado) == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                        <option value="ES" {{ old('estado', $cliente->estado) == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                        <option value="GO" {{ old('estado', $cliente->estado) == 'GO' ? 'selected' : '' }}>Goiás</option>
                        <option value="MA" {{ old('estado', $cliente->estado) == 'MA' ? 'selected' : '' }}>Maranhão</option>
                        <option value="MT" {{ old('estado', $cliente->estado) == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                        <option value="MS" {{ old('estado', $cliente->estado) == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                        <option value="MG" {{ old('estado', $cliente->estado) == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                        <option value="PA" {{ old('estado', $cliente->estado) == 'PA' ? 'selected' : '' }}>Pará</option>
                        <option value="PB" {{ old('estado', $cliente->estado) == 'PB' ? 'selected' : '' }}>Paraíba</option>
                        <option value="PR" {{ old('estado', $cliente->estado) == 'PR' ? 'selected' : '' }}>Paraná</option>
                        <option value="PE" {{ old('estado', $cliente->estado) == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                        <option value="PI" {{ old('estado', $cliente->estado) == 'PI' ? 'selected' : '' }}>Piauí</option>
                        <option value="RJ" {{ old('estado', $cliente->estado) == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                        <option value="RN" {{ old('estado', $cliente->estado) == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                        <option value="RS" {{ old('estado', $cliente->estado) == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                        <option value="RO" {{ old('estado', $cliente->estado) == 'RO' ? 'selected' : '' }}>Rondônia</option>
                        <option value="RR" {{ old('estado', $cliente->estado) == 'RR' ? 'selected' : '' }}>Roraima</option>
                        <option value="SC" {{ old('estado', $cliente->estado) == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                        <option value="SP" {{ old('estado', $cliente->estado) == 'SP' ? 'selected' : '' }}>São Paulo</option>
                        <option value="SE" {{ old('estado', $cliente->estado) == 'SE' ? 'selected' : '' }}>Sergipe</option>
                        <option value="TO" {{ old('estado', $cliente->estado) == 'TO' ? 'selected' : '' }}>Tocantins</option>
                    </select>
                    @error('estado')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Observações -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Observações</h3>
            <div>
                <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-2">Observações</label>
                <textarea id="observacoes" name="observacoes" rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('observacoes') border-red-500 @enderror" 
                          placeholder="Informações adicionais sobre o cliente...">{{ old('observacoes', $cliente->observacoes) }}</textarea>
                @error('observacoes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Status -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Status</h3>
            <div class="flex items-center">
                <input type="checkbox" id="ativo" name="ativo" value="1" 
                       {{ old('ativo', $cliente->ativo) ? 'checked' : '' }}
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="ativo" class="ml-2 block text-sm text-gray-700">
                    Cliente ativo
                </label>
            </div>
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.clientes.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                <i class="fas fa-save mr-2"></i>Atualizar Cliente
            </button>
        </div>
    </form>
</div>

<script>
// Máscaras para os campos
document.addEventListener('DOMContentLoaded', function() {
    // Máscara para telefone
    const telefoneInput = document.getElementById('telefone');
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 10) {
                value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
            } else {
                value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            }
            e.target.value = value;
        });
    }

    // Máscara para celular
    const celularInput = document.getElementById('celular');
    if (celularInput) {
        celularInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            e.target.value = value;
        });
    }

    // Máscara para CEP
    const cepInput = document.getElementById('cep');
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
            e.target.value = value;
        });

        // Buscar endereço pelo CEP
        cepInput.addEventListener('blur', function(e) {
            const cep = e.target.value.replace(/\D/g, '');
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
                    .catch(error => console.error('Erro ao buscar CEP:', error));
            }
        });
    }

    // Máscara para documento baseada no tipo
    const tipoDocumentoSelect = document.getElementById('tipo_documento');
    const documentoInput = document.getElementById('documento');
    
    function aplicarMascaraDocumento() {
        const tipo = tipoDocumentoSelect.value;
        
        documentoInput.removeEventListener('input', mascaraCPF);
        documentoInput.removeEventListener('input', mascaraCNPJ);
        
        if (tipo === 'cpf') {
            documentoInput.addEventListener('input', mascaraCPF);
            documentoInput.placeholder = '000.000.000-00';
        } else if (tipo === 'cnpj') {
            documentoInput.addEventListener('input', mascaraCNPJ);
            documentoInput.placeholder = '00.000.000/0000-00';
        } else {
            documentoInput.placeholder = '';
        }
    }
    
    function mascaraCPF(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
        e.target.value = value;
    }
    
    function mascaraCNPJ(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
        e.target.value = value;
    }
    
    tipoDocumentoSelect.addEventListener('change', aplicarMascaraDocumento);
    
    // Aplicar máscara inicial se já houver tipo selecionado
    if (tipoDocumentoSelect.value) {
        aplicarMascaraDocumento();
    }
});
</script>
@endsection