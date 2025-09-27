@extends('layouts.admin')

@section('title', 'Editar Cliente')

@section('content')
<!-- Breadcrumbs -->
<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                <i class="fas fa-home mr-2"></i>
                Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <a href="{{ route('admin.clientes.index') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">Clientes</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <span class="text-sm font-medium text-gray-500">Editar Cliente</span>
            </div>
        </li>
    </ol>
</nav>

<!-- Header com gradiente -->
<div class="rounded-xl shadow-lg mb-6" style="background: linear-gradient(135deg, #1E3951 0%, #2A4A66 100%);">
    <div class="p-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(248, 171, 20, 0.2);">
                    <i class="fas fa-user-edit text-2xl" style="color: #F8AB14;"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Editar Cliente</h1>
                    <p class="text-blue-100">Atualize as informações do cliente {{ $cliente->name }}</p>
                </div>
            </div>
            <a href="{{ route('admin.clientes.index') }}" 
               class="inline-flex items-center px-6 py-3 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" 
               style="background: #F8AB14; hover:background: #E09A12;">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>
</div>

<!-- Card principal -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <form action="{{ route('admin.clientes.update', $cliente->id) }}" method="POST" class="p-8">
        @csrf
        @method('PUT')
        
        <!-- Dados Básicos -->
        <div class="mb-8">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-4" style="background: rgba(30, 57, 81, 0.1);">
                    <i class="fas fa-user text-lg" style="color: #1E3951;"></i>
                </div>
                <h3 class="text-lg font-semibold" style="color: #1E3951;">Dados Básicos</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nome" class="block text-sm font-semibold mb-3" style="color: #1E3951;">
                        <i class="fas fa-user mr-2" style="color: #F8AB14;"></i>Nome Completo *
                    </label>
                    <input type="text" 
                           id="nome" 
                           name="nome" 
                           value="{{ old('nome', $cliente->nome) }}"
                           required
                           class="w-full px-4 py-3 border rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:border-transparent @error('nome') border-red-300 @enderror" 
                           style="border-color: rgba(30, 57, 81, 0.2); focus:ring-color: #1E3951;"
                           placeholder="Digite o nome completo">
                    @error('nome')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-semibold mb-3" style="color: #1E3951;">
                        <i class="fas fa-envelope mr-2" style="color: #F8AB14;"></i>E-mail
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $cliente->email) }}"
                           class="w-full px-4 py-3 border rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:border-transparent @error('email') border-red-300 @enderror" 
                           style="border-color: rgba(30, 57, 81, 0.2); focus:ring-color: #1E3951;"
                           placeholder="Digite o e-mail">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                
                <div>
                    <label for="tipo_documento" class="block text-sm font-semibold mb-3" style="color: #1E3951;">
                        <i class="fas fa-id-card mr-2" style="color: #F8AB14;"></i>Tipo de Documento *
                    </label>
                    <select id="tipo_documento" 
                            name="tipo_documento" 
                            required
                            class="w-full px-4 py-3 border rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:border-transparent @error('tipo_documento') border-red-300 @enderror" 
                            style="border-color: rgba(30, 57, 81, 0.2); focus:ring-color: #1E3951;">
                        <option value="">Selecione o tipo...</option>
                        <option value="cpf" {{ old('tipo_documento', $cliente->tipo_documento) == 'cpf' ? 'selected' : '' }}>CPF</option>
                        <option value="cnpj" {{ old('tipo_documento', $cliente->tipo_documento) == 'cnpj' ? 'selected' : '' }}>CNPJ</option>
                    </select>
                    @error('tipo_documento')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                
                <div>
                    <label for="documento" class="block text-sm font-semibold mb-3" style="color: #1E3951;">
                        <i class="fas fa-file-alt mr-2" style="color: #F8AB14;"></i>Documento *
                    </label>
                    <input type="text" 
                           id="documento" 
                           name="documento" 
                           value="{{ old('documento', $cliente->documento) }}"
                           required
                           class="w-full px-4 py-3 border rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:border-transparent @error('documento') border-red-300 @enderror" 
                           style="border-color: rgba(30, 57, 81, 0.2); focus:ring-color: #1E3951;"
                           placeholder="Digite o documento">
                    @error('documento')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Contato -->
        <div class="mb-8">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-4" style="background: rgba(30, 57, 81, 0.1);">
                    <i class="fas fa-phone text-lg" style="color: #1E3951;"></i>
                </div>
                <h3 class="text-lg font-semibold" style="color: #1E3951;">Contato</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="telefone" class="block text-sm font-semibold mb-3" style="color: #1E3951;">
                        <i class="fas fa-phone mr-2" style="color: #F8AB14;"></i>Telefone
                    </label>
                    <input type="text" 
                           id="telefone" 
                           name="telefone" 
                           value="{{ old('telefone', $cliente->telefone) }}"
                           class="w-full px-4 py-3 border rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:border-transparent @error('telefone') border-red-300 @enderror" 
                           style="border-color: rgba(30, 57, 81, 0.2); focus:ring-color: #1E3951;"
                           placeholder="(11) 1234-5678">
                    @error('telefone')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                
                <div>
                    <label for="celular" class="block text-sm font-semibold mb-3" style="color: #1E3951;">
                        <i class="fas fa-mobile-alt mr-2" style="color: #F8AB14;"></i>Celular
                    </label>
                    <input type="text" 
                           id="celular" 
                           name="celular" 
                           value="{{ old('celular', $cliente->celular) }}"
                           class="w-full px-4 py-3 border rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:border-transparent @error('celular') border-red-300 @enderror" 
                           style="border-color: rgba(30, 57, 81, 0.2); focus:ring-color: #1E3951;"
                           placeholder="(11) 91234-5678">
                    @error('celular')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Endereço -->
        <div class="mb-8">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-4" style="background: rgba(30, 57, 81, 0.1);">
                    <i class="fas fa-map-marker-alt text-lg" style="color: #1E3951;"></i>
                </div>
                <h3 class="text-lg font-semibold" style="color: #1E3951;">Endereço</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="cep" class="block text-sm font-semibold mb-3" style="color: #1E3951;">
                        <i class="fas fa-mail-bulk mr-2" style="color: #F8AB14;"></i>CEP
                    </label>
                    <input type="text" 
                           id="cep" 
                           name="cep" 
                           value="{{ old('cep', $cliente->cep) }}"
                           class="w-full px-4 py-3 border rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:border-transparent @error('cep') border-red-300 @enderror" 
                           style="border-color: rgba(30, 57, 81, 0.2); focus:ring-color: #1E3951;"
                           placeholder="12345-678">
                    @error('cep')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="logradouro" class="block text-sm font-semibold mb-3" style="color: #1E3951;">
                        <i class="fas fa-road mr-2" style="color: #F8AB14;"></i>Logradouro
                    </label>
                    <input type="text" 
                           id="logradouro" 
                           name="logradouro" 
                           value="{{ old('logradouro', $cliente->logradouro) }}"
                           class="w-full px-4 py-3 border rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:border-transparent @error('logradouro') border-red-300 @enderror" 
                           style="border-color: rgba(30, 57, 81, 0.2); focus:ring-color: #1E3951;"
                           placeholder="Digite o logradouro">
                    @error('logradouro')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div>
                    <label for="numero" class="block text-sm font-semibold mb-3" style="color: #1E3951;">
                        <i class="fas fa-hashtag mr-2" style="color: #F8AB14;"></i>Número
                    </label>
                    <input type="text" 
                           id="numero" 
                           name="numero" 
                           value="{{ old('numero', $cliente->numero) }}"
                           class="w-full px-4 py-3 border rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:border-transparent @error('numero') border-red-300 @enderror" 
                           style="border-color: rgba(30, 57, 81, 0.2); focus:ring-color: #1E3951;"
                           placeholder="123">
                    @error('numero')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                
                <div>
                    <label for="complemento" class="block text-sm font-semibold mb-3" style="color: #1E3951;">
                        <i class="fas fa-plus mr-2" style="color: #F8AB14;"></i>Complemento
                    </label>
                    <input type="text" 
                           id="complemento" 
                           name="complemento" 
                           value="{{ old('complemento', $cliente->complemento) }}"
                           class="w-full px-4 py-3 border rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:border-transparent @error('complemento') border-red-300 @enderror" 
                           style="border-color: rgba(30, 57, 81, 0.2); focus:ring-color: #1E3951;"
                           placeholder="Apto, Sala, etc.">
                    @error('complemento')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="bairro" class="block text-sm font-semibold mb-3" style="color: #1E3951;">
                        <i class="fas fa-building mr-2" style="color: #F8AB14;"></i>Bairro
                    </label>
                    <input type="text" 
                           id="bairro" 
                           name="bairro" 
                           value="{{ old('bairro', $cliente->bairro) }}"
                           class="w-full px-4 py-3 border rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:border-transparent @error('bairro') border-red-300 @enderror" 
                           style="border-color: rgba(30, 57, 81, 0.2); focus:ring-color: #1E3951;"
                           placeholder="Digite o bairro">
                    @error('bairro')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="cidade" class="block text-sm font-semibold mb-3" style="color: #1E3951;">
                        <i class="fas fa-city mr-2" style="color: #F8AB14;"></i>Cidade
                    </label>
                    <input type="text" 
                           id="cidade" 
                           name="cidade" 
                           value="{{ old('cidade', $cliente->cidade) }}"
                           class="w-full px-4 py-3 border rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:border-transparent @error('cidade') border-red-300 @enderror" 
                           style="border-color: rgba(30, 57, 81, 0.2); focus:ring-color: #1E3951;"
                           placeholder="Digite a cidade">
                    @error('cidade')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                
                <div>
                    <label for="estado" class="block text-sm font-semibold mb-3" style="color: #1E3951;">
                        <i class="fas fa-flag mr-2" style="color: #F8AB14;"></i>Estado
                    </label>
                    <select id="estado" 
                            name="estado" 
                            class="w-full px-4 py-3 border rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:border-transparent @error('estado') border-red-300 @enderror" 
                            style="border-color: rgba(30, 57, 81, 0.2); focus:ring-color: #1E3951;">
                        <option value="">Selecione o estado...</option>
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
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Observações -->
        <div class="mb-8">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-4" style="background: rgba(30, 57, 81, 0.1);">
                    <i class="fas fa-sticky-note text-lg" style="color: #1E3951;"></i>
                </div>
                <h3 class="text-lg font-semibold" style="color: #1E3951;">Observações</h3>
            </div>
            
            <div>
                <label for="observacoes" class="block text-sm font-semibold mb-3" style="color: #1E3951;">
                    <i class="fas fa-comment mr-2" style="color: #F8AB14;"></i>Observações
                </label>
                <textarea id="observacoes" 
                          name="observacoes" 
                          rows="4"
                          class="w-full px-4 py-3 border rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:border-transparent @error('observacoes') border-red-300 @enderror" 
                          style="border-color: rgba(30, 57, 81, 0.2); focus:ring-color: #1E3951;"
                          placeholder="Digite observações sobre o cliente...">{{ old('observacoes', $cliente->observacoes) }}</textarea>
                @error('observacoes')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>
        </div>
        
        <!-- Botões de ação -->
        <div class="flex justify-end space-x-4 pt-6 border-t" style="border-color: rgba(30, 57, 81, 0.1);">
            <a href="{{ route('admin.clientes.index') }}" 
               class="inline-flex items-center px-6 py-3 rounded-xl font-semibold transition-all duration-300 hover:shadow-lg" 
               style="background: rgba(30, 57, 81, 0.1); color: #1E3951; hover:background: rgba(30, 57, 81, 0.2);">
                <i class="fas fa-times mr-2"></i>Cancelar
            </a>
            
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" 
                    style="background: linear-gradient(135deg, #F8AB14 0%, #E09A12 100%);">
                <i class="fas fa-save mr-2"></i>Atualizar Cliente
            </button>
        </div>
    </form>
</div>
@endsection