@extends('layouts.admin')

@section('title', 'Editar Tipo de Veículo')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Editar Tipo de Veículo</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.tipos-veiculos.show', $tipoVeiculo->id) }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-eye mr-2"></i>Visualizar
            </a>
            <a href="{{ route('admin.tipos-veiculos.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <form action="{{ route('admin.tipos-veiculos.update', $tipoVeiculo->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Dados Básicos -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Dados Básicos</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="codigo" class="block text-sm font-medium text-gray-700 mb-2">Código *</label>
                    <input type="text" 
                           id="codigo" 
                           name="codigo" 
                           value="{{ old('codigo', $tipoVeiculo->codigo) }}"
                           placeholder="Ex: CAR001, SUV001, VAN001"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('codigo') border-red-500 @enderror" 
                           required>
                    @error('codigo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="tipo_combustivel" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Combustível *</label>
                    <select id="tipo_combustivel" 
                            name="tipo_combustivel" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('tipo_combustivel') border-red-500 @enderror" 
                            required>
                        <option value="">Selecione o tipo</option>
                        <option value="Gasolina" {{ old('tipo_combustivel', $tipoVeiculo->tipo_combustivel) == 'Gasolina' ? 'selected' : '' }}>Gasolina</option>
                        <option value="Etanol" {{ old('tipo_combustivel', $tipoVeiculo->tipo_combustivel) == 'Etanol' ? 'selected' : '' }}>Etanol</option>
                        <option value="Diesel" {{ old('tipo_combustivel', $tipoVeiculo->tipo_combustivel) == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="Flex" {{ old('tipo_combustivel', $tipoVeiculo->tipo_combustivel) == 'Flex' ? 'selected' : '' }}>Flex</option>
                    </select>
                    @error('tipo_combustivel')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="consumo_km_litro" class="block text-sm font-medium text-gray-700 mb-2">Consumo (km/litro) *</label>
                    <input type="number" 
                           id="consumo_km_litro" 
                           name="consumo_km_litro" 
                           value="{{ old('consumo_km_litro', $tipoVeiculo->consumo_km_litro) }}"
                           step="0.01"
                           min="0"
                           placeholder="Ex: 12.5"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('consumo_km_litro') border-red-500 @enderror" 
                           required>
                    @error('consumo_km_litro')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="active" 
                            name="active" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('active') border-red-500 @enderror">
                        <option value="1" {{ old('active', $tipoVeiculo->active) == '1' ? 'selected' : '' }}>Ativo</option>
                        <option value="0" {{ old('active', $tipoVeiculo->active) == '0' ? 'selected' : '' }}>Inativo</option>
                    </select>
                    @error('active')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-4">
                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">Descrição *</label>
                <textarea id="descricao" 
                          name="descricao" 
                          rows="3"
                          placeholder="Descrição detalhada do tipo de veículo"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('descricao') border-red-500 @enderror" 
                          required>{{ old('descricao', $tipoVeiculo->descricao) }}</textarea>
                @error('descricao')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Informações de Auditoria -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Informações de Auditoria</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                <div>
                    <strong>Criado em:</strong> {{ $tipoVeiculo->created_at->format('d/m/Y H:i:s') }}
                </div>
                <div>
                    <strong>Última atualização:</strong> {{ $tipoVeiculo->updated_at->format('d/m/Y H:i:s') }}
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.tipos-veiculos.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg transition duration-200">
                <i class="fas fa-save mr-2"></i>Atualizar
            </button>
        </div>
    </form>
</div>

<!-- Informações sobre Frotas Vinculadas -->
@if($tipoVeiculo->frotas->count() > 0)
<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-6">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="fas fa-exclamation-triangle text-yellow-400 text-lg"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-yellow-800">Atenção</h3>
            <div class="mt-2 text-sm text-yellow-700">
                <p>Este tipo de veículo possui <strong>{{ $tipoVeiculo->frotas->count() }} frota(s)</strong> vinculada(s). Alterações nos dados podem afetar os cálculos de custo das frotas.</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Informações Adicionais -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="fas fa-info-circle text-blue-400 text-lg"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">Informações Importantes</h3>
            <div class="mt-2 text-sm text-blue-700">
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Código:</strong> Deve ser único e identificar claramente o tipo de veículo</li>
                    <li><strong>Consumo:</strong> Valor médio em quilômetros por litro para cálculos de custo</li>
                    <li><strong>Tipo de Combustível:</strong> Define qual combustível o veículo utiliza</li>
                    <li><strong>Descrição:</strong> Informações detalhadas sobre o tipo de veículo</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection