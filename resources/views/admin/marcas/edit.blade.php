@extends('layouts.admin')

@section('title', 'Editar Marca')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Editar Marca</h1>
        <a href="{{ route('admin.marcas.show', $marca->id) }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    <form action="{{ route('admin.marcas.update', $marca->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Dados Básicos -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Dados da Marca</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">MARCA *</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $marca->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" 
                           placeholder=""
                           style="text-transform: uppercase;"
                           required>
                    <p class="text-xs text-gray-500 mt-1">Apenas letras maiúsculas, números e espaços. Sem acentuação.</p>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="mercado" class="block text-sm font-medium text-gray-700 mb-2">MERCADO *</label>
                    <input type="text" 
                           id="mercado" 
                           name="mercado" 
                           value="{{ old('mercado', $marca->mercado) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('mercado') border-red-500 @enderror" 
                           placeholder=""
                           style="text-transform: uppercase;"
                           required>
                    <p class="text-xs text-gray-500 mt-1">Apenas letras maiúsculas, números e espaços. Sem acentuação.</p>
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
                       id="ativo" 
                       name="ativo" 
                       value="1" 
                       {{ old('ativo', $marca->ativo) ? 'checked' : '' }}
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="ativo" class="ml-2 block text-sm text-gray-700">
                    Marca ativa
                </label>
            </div>
            <p class="text-xs text-gray-500 mt-1">Marcas inativas não aparecerão nas listagens do sistema</p>
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.marcas.show', $marca->id) }}" 
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
@endsection