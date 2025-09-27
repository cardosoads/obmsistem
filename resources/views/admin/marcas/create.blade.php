@extends('layouts.admin')

@section('title', 'Nova Marca')

@section('content')
<!-- Header com gradiente -->
<div class="rounded-xl shadow-lg mb-6" style="background: linear-gradient(135deg, #1E3951 0%, #2A4A66 100%);">
    <div class="p-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(248, 171, 20, 0.2);">
                    <i class="fas fa-plus text-2xl" style="color: #F8AB14;"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Nova Marca</h1>
                    <p class="text-blue-100">Adicione uma nova marca ao sistema</p>
                </div>
            </div>
            <a href="{{ route('admin.marcas.index') }}" 
               class="inline-flex items-center px-6 py-3 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" 
               style="background: rgba(255, 255, 255, 0.2); hover:background: rgba(255, 255, 255, 0.3);">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>
</div>

<!-- Breadcrumbs -->
<nav class="mb-6">
    <ol class="flex items-center space-x-2 text-sm">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium hover:text-blue-600" style="color: #1E3951;">
                <i class="fas fa-home mr-2"></i>Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
                <a href="{{ route('admin.marcas.index') }}" class="text-sm font-medium hover:text-blue-600" style="color: #1E3951;">Marcas</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
                <span class="text-sm font-medium text-gray-500">Nova Marca</span>
            </div>
        </li>
    </ol>
</nav>

<!-- Card principal -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">

    <form action="{{ route('admin.marcas.store') }}" method="POST" class="p-6">
        @csrf
        
        <!-- Dados Básicos -->
        <div class="mb-8">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3" style="background: rgba(30, 57, 81, 0.1);">
                    <i class="fas fa-tags" style="color: #1E3951;"></i>
                </div>
                <h3 class="text-lg font-semibold" style="color: #1E3951;">Dados da Marca</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-semibold mb-3" style="color: #1E3951;">MARCA *</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="w-full px-4 py-3 border rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:border-transparent @error('name') border-red-500 @enderror" 
                           style="border-color: rgba(30, 57, 81, 0.2); focus:ring-color: #1E3951; text-transform: uppercase;"
                           placeholder="Digite o nome da marca"
                           required>
                    <p class="text-xs text-gray-500 mt-2">Apenas letras maiúsculas, números e espaços. Sem acentuação.</p>
                    @error('name')
                        <div class="flex items-center mt-2 text-red-600">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span class="text-sm">{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                
                <div>
                    <label for="mercado" class="block text-sm font-semibold mb-3" style="color: #1E3951;">MERCADO *</label>
                    <input type="text" 
                           id="mercado" 
                           name="mercado" 
                           value="{{ old('mercado') }}"
                           class="w-full px-4 py-3 border rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:border-transparent @error('mercado') border-red-500 @enderror" 
                           style="border-color: rgba(30, 57, 81, 0.2); focus:ring-color: #1E3951; text-transform: uppercase;"
                           placeholder="Digite o mercado"
                           required>
                    <p class="text-xs text-gray-500 mt-2">Apenas letras maiúsculas, números e espaços. Sem acentuação.</p>
                    @error('mercado')
                        <div class="flex items-center mt-2 text-red-600">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span class="text-sm">{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="mb-8">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3" style="background: rgba(248, 171, 20, 0.1);">
                    <i class="fas fa-toggle-on" style="color: #F8AB14;"></i>
                </div>
                <h3 class="text-lg font-semibold" style="color: #1E3951;">Status da Marca</h3>
            </div>
            
            <div class="flex items-center p-4 rounded-xl" style="background: rgba(30, 57, 81, 0.02); border: 1px solid rgba(30, 57, 81, 0.1);">
                <input type="checkbox" 
                       id="ativo" 
                       name="ativo" 
                       value="1" 
                       {{ old('ativo', true) ? 'checked' : '' }}
                       class="h-5 w-5 rounded transition-all duration-300" 
                       style="color: #F8AB14; focus:ring-color: #F8AB14;">
                <label for="ativo" class="ml-3 block text-sm font-medium" style="color: #1E3951;">
                    Marca ativa no sistema
                </label>
            </div>
            <p class="text-xs text-gray-500 mt-2">Marcas inativas não aparecerão nas listagens do sistema</p>
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-4 pt-6 border-t" style="border-color: rgba(30, 57, 81, 0.1);">
            <a href="{{ route('admin.marcas.index') }}" 
               class="inline-flex items-center px-6 py-3 rounded-xl font-semibold transition-all duration-300 hover:shadow-lg" 
               style="background: rgba(30, 57, 81, 0.1); color: #1E3951; hover:background: #1E3951; hover:color: white;">
                <i class="fas fa-times mr-2"></i>Cancelar
            </a>
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg transform hover:scale-105" 
                    style="background: linear-gradient(135deg, #F8AB14 0%, #E09A12 100%);">
                <i class="fas fa-save mr-2"></i>Salvar Marca
            </button>
        </div>
    </form>
</div>
@endsection