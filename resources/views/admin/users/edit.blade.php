@extends('layouts.admin')

@section('title', 'Editar Usuário')
@section('page-title', 'Usuários')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold text-gray-900">Editar Usuário: {{ $user->name }}</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.users.show', $user) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Visualizar
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Informações do Usuário</h2>
        </div>
        
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nome -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome Completo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                           placeholder="Digite o nome completo">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                           placeholder="Digite o email">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Função -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        Função <span class="text-red-500">*</span>
                    </label>
                    <select id="role" name="role" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role') border-red-500 @enderror"
                            {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        <option value="">Selecione uma função</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="manager" {{ old('role', $user->role) === 'manager' ? 'selected' : '' }}>Gerente</option>
                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Usuário</option>
                    </select>
                    @if($user->id === auth()->id())
                        <input type="hidden" name="role" value="{{ $user->role }}">
                        <p class="mt-1 text-xs text-gray-500">Você não pode alterar sua própria função</p>
                    @endif
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="active" class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>
                    <div class="flex items-center">
                        <input type="checkbox" id="active" name="active" value="1" 
                               {{ old('active', $user->email_verified_at ? '1' : '0') ? 'checked' : '' }}
                               {{ $user->id === auth()->id() ? 'disabled' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="active" class="ml-2 block text-sm text-gray-900">
                            Usuário ativo
                        </label>
                    </div>
                    @if($user->id === auth()->id())
                        <input type="hidden" name="active" value="1">
                        <p class="mt-1 text-xs text-gray-500">Você não pode desativar sua própria conta</p>
                    @else
                        <p class="mt-1 text-xs text-gray-500">Usuários inativos não conseguem fazer login no sistema</p>
                    @endif
                </div>
            </div>

            <!-- Alterar Senha -->
            <div class="mt-8 border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Alterar Senha (Opcional)</h3>
                <p class="text-sm text-gray-600 mb-4">Deixe em branco para manter a senha atual</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nova Senha -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Nova Senha
                        </label>
                        <input type="password" id="password" name="password"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                               placeholder="Digite a nova senha (mín. 8 caracteres)">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmar Nova Senha -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar Nova Senha
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Confirme a nova senha">
                    </div>
                </div>
            </div>

            <!-- Informações do Sistema -->
            <div class="mt-8 border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Sistema</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-600">
                    <div>
                        <strong>Criado em:</strong> {{ $user->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div>
                        <strong>Última atualização:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}
                    </div>
                    @if($user->email_verified_at)
                    <div>
                        <strong>Email verificado em:</strong> {{ $user->email_verified_at->format('d/m/Y H:i') }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Descrição das Funções -->
            <div class="mt-6 p-4 bg-gray-50 rounded-md">
                <h3 class="text-sm font-medium text-gray-900 mb-2">Descrição das Funções:</h3>
                <ul class="text-xs text-gray-600 space-y-1">
                    <li><strong>Administrador:</strong> Acesso completo ao sistema, pode gerenciar usuários, configurações e todos os módulos</li>
                    <li><strong>Gerente:</strong> Acesso aos módulos operacionais, pode visualizar relatórios e gerenciar orçamentos</li>
                    <li><strong>Usuário:</strong> Acesso básico aos módulos de consulta e criação de orçamentos</li>
                </ul>
            </div>

            <!-- Botões -->
            <div class="mt-6 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.users.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Validação de senha em tempo real
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    
    function validatePasswords() {
        if (password.value && passwordConfirmation.value) {
            if (password.value !== passwordConfirmation.value) {
                passwordConfirmation.setCustomValidity('As senhas não coincidem');
            } else {
                passwordConfirmation.setCustomValidity('');
            }
        }
    }
    
    password.addEventListener('input', validatePasswords);
    passwordConfirmation.addEventListener('input', validatePasswords);
});
</script>
@endsection