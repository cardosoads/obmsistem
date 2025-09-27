@extends('layouts.admin')

@section('title', 'Configurações')
@section('page-title', 'Configurações')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'profile' }">
    <!-- Header -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-xl font-semibold text-gray-900">Configurações do Sistema</h1>
            <p class="mt-1 text-sm text-gray-600">Gerencie as configurações gerais do sistema e sua conta.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Settings Navigation -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Categorias</h2>
                </div>
                <nav class="p-2">
                    <button @click="activeTab = 'profile'" 
                            :class="activeTab === 'profile' ? 'border-2' : 'text-gray-600 hover:text-gray-900'" :style="activeTab === 'profile' ? 'background-color: rgba(30, 57, 81, 0.1); color: #1E3951; border-color: #1E3951;' : ''"
                            class="w-full text-left px-4 py-2 text-sm font-medium rounded-md border transition-colors duration-200 mb-2">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Perfil
                    </button>
                    <button @click="activeTab = 'security'" 
                            :class="activeTab === 'security' ? 'border-2' : 'text-gray-600 hover:text-gray-900'" :style="activeTab === 'security' ? 'background-color: rgba(30, 57, 81, 0.1); color: #1E3951; border-color: #1E3951;' : ''"
                            class="w-full text-left px-4 py-2 text-sm font-medium rounded-md border transition-colors duration-200 mb-2">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Segurança
                    </button>
                    <button @click="activeTab = 'system'" 
                            :class="activeTab === 'system' ? 'border-2' : 'text-gray-600 hover:text-gray-900'" :style="activeTab === 'system' ? 'background-color: rgba(30, 57, 81, 0.1); color: #1E3951; border-color: #1E3951;' : ''"
                            class="w-full text-left px-4 py-2 text-sm font-medium rounded-md border transition-colors duration-200 mb-2">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Sistema
                    </button>
                    <button @click="activeTab = 'omie'" 
                            :class="activeTab === 'omie' ? 'border-2' : 'text-gray-600 hover:text-gray-900'" :style="activeTab === 'omie' ? 'background-color: rgba(30, 57, 81, 0.1); color: #1E3951; border-color: #1E3951;' : ''"
                            class="w-full text-left px-4 py-2 text-sm font-medium rounded-md border transition-colors duration-200 mb-2">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                        </svg>
                        API Omie
                    </button>
                    <button @click="activeTab = 'notifications'" 
                            :class="activeTab === 'notifications' ? 'border-2' : 'text-gray-600 hover:text-gray-900'" :style="activeTab === 'notifications' ? 'background-color: rgba(30, 57, 81, 0.1); color: #1E3951; border-color: #1E3951;' : ''"
                            class="w-full text-left px-4 py-2 text-sm font-medium rounded-md border transition-colors duration-200">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h8v-2H4v2zM4 11h10V9H4v2z"></path>
                        </svg>
                        Notificações
                    </button>
                </nav>
            </div>
        </div>

        <!-- Settings Content -->
        <div class="lg:col-span-2">
            <!-- Profile Settings -->
            <div x-show="activeTab === 'profile'" class="bg-white shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informações do Perfil</h3>
                </div>
                <form class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome Completo</label>
                            <input type="text" id="name" value="{{ auth()->user()->name ?? 'Administrador' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" value="{{ auth()->user()->email ?? 'admin@sistema.com' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Biografia</label>
                        <textarea id="bio" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Conte um pouco sobre você..."></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200" style="background: #1E3951;" onmouseover="this.style.background='#2A4A63'" onmouseout="this.style.background='#1E3951'">
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>

            <!-- Security Settings -->
            <div x-show="activeTab === 'security'" class="bg-white shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Configurações de Segurança</h3>
                </div>
                <div class="p-6 space-y-6">
                    <form class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Senha Atual</label>
                            <input type="password" id="current_password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Nova Senha</label>
                            <input type="password" id="new_password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Confirmar Nova Senha</label>
                            <input type="password" id="confirm_password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200" style="background: #F8AB14;" onmouseover="this.style.background='#E09712'" onmouseout="this.style.background='#F8AB14'">
                                Alterar Senha
                            </button>
                        </div>
                    </form>
                    
                    <hr class="my-6">
                    
                    <div>
                        <h4 class="text-md font-medium text-gray-900 mb-4">Autenticação de Dois Fatores</h4>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">2FA não está ativado</p>
                                <p class="text-sm text-gray-500">Adicione uma camada extra de segurança à sua conta</p>
                            </div>
                            <button class="text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200" style="background: #1E3951;" onmouseover="this.style.background='#2A4A63'" onmouseout="this.style.background='#1E3951'">
                                Ativar 2FA
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Settings -->
            <div x-show="activeTab === 'system'" class="bg-white shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Configurações do Sistema</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">Fuso Horário</label>
                            <select id="timezone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="America/Sao_Paulo">São Paulo (GMT-3)</option>
                                <option value="America/New_York">Nova York (GMT-5)</option>
                                <option value="Europe/London">Londres (GMT+0)</option>
                            </select>
                        </div>
                        <div>
                            <label for="language" class="block text-sm font-medium text-gray-700 mb-2">Idioma</label>
                            <select id="language" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="pt-BR">Português (Brasil)</option>
                                <option value="en-US">English (US)</option>
                                <option value="es-ES">Español</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-md font-medium text-gray-900 mb-4">Configurações de Manutenção</h4>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Modo de Manutenção</p>
                                    <p class="text-sm text-gray-500">Ativar para realizar manutenções no sistema</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all" style="peer-focus:ring-color: rgba(30, 57, 81, 0.3); peer-checked:background: #1E3951;"></div>
                                </label>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Logs de Debug</p>
                                    <p class="text-sm text-gray-500">Ativar logs detalhados para debug</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all" style="peer-focus:ring-color: rgba(30, 57, 81, 0.3); peer-checked:background: #1E3951;"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button class="text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200" style="background: #1E3951;" onmouseover="this.style.background='#2A4A63'" onmouseout="this.style.background='#1E3951'">
                            Salvar Configurações
                        </button>
                    </div>
                </div>
            </div>

            <!-- Omie API Settings -->
            <div x-show="activeTab === 'omie'" class="bg-white shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Configurações da API Omie</h3>
                    <p class="mt-1 text-sm text-gray-600">Configure as credenciais para integração com a API da Omie</p>
                </div>
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 p-4 rounded-md" style="background: rgba(30, 57, 81, 0.1); border: 1px solid rgba(30, 57, 81, 0.2);">
                            <div class="flex">
                                <svg class="w-5 h-5" style="color: #1E3951;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="ml-3 text-sm" style="color: #1E3951;">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 p-4 rounded-md" style="background: rgba(248, 171, 20, 0.1); border: 1px solid rgba(248, 171, 20, 0.2);">
                            <div class="flex">
                                <svg class="w-5 h-5" style="color: #F8AB14;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="ml-3">
                                    @foreach($errors->all() as $error)
                                        <p class="text-sm" style="color: #F8AB14;">{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('admin.settings.omie.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="omie_app_key" class="block text-sm font-medium text-gray-700 mb-2">
                                    App Key da Omie
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="omie_app_key" 
                                       name="omie_app_key" 
                                       value="{{ old('omie_app_key', $omieSettings['omie_app_key'] ?? '') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('omie_app_key') border-red-300 @enderror"
                                       placeholder="Digite o App Key fornecido pela Omie"
                                       required>
                                @error('omie_app_key')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="omie_app_secret" class="block text-sm font-medium text-gray-700 mb-2">
                                    App Secret da Omie
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       id="omie_app_secret" 
                                       name="omie_app_secret" 
                                       value="{{ old('omie_app_secret', $omieSettings['omie_app_secret'] ?? '') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('omie_app_secret') border-red-300 @enderror"
                                       placeholder="Digite o App Secret fornecido pela Omie"
                                       required>
                                @error('omie_app_secret')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-blue-800">Como obter as credenciais da API Omie:</h4>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ol class="list-decimal list-inside space-y-1">
                                            <li>Acesse o painel da Omie em <a href="https://app.omie.com.br" target="_blank" class="underline">app.omie.com.br</a></li>
                                            <li>Vá em "Configurações" → "Integrações" → "API"</li>
                                            <li>Gere ou copie suas credenciais App Key e App Secret</li>
                                            <li>Cole as credenciais nos campos acima</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" 
                                    onclick="testOmieConnection()" 
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                                Testar Conexão
                            </button>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                                Salvar Configurações
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Notifications Settings -->
            <div x-show="activeTab === 'notifications'" class="bg-white shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Preferências de Notificação</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Notificações por Email</p>
                                <p class="text-sm text-gray-500">Receber notificações importantes por email</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Notificações de Sistema</p>
                                <p class="text-sm text-gray-500">Alertas sobre status do sistema e erros</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Relatórios Semanais</p>
                                <p class="text-sm text-gray-500">Resumo semanal de atividades do sistema</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                            Salvar Preferências
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function testOmieConnection() {
    // Desabilita o botão durante o teste
    const button = event.target;
    const originalText = button.textContent;
    button.disabled = true;
    button.textContent = 'Testando...';
    
    // Faz a requisição para testar a conexão
    fetch('{{ route("admin.settings.omie.test") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mostra mensagem de sucesso
            showNotification('Conexão testada com sucesso!', 'success');
        } else {
            // Mostra mensagem de erro
            showNotification(data.message || 'Erro ao testar conexão', 'error');
        }
    })
    .catch(error => {
        showNotification('Erro ao testar conexão: ' + error.message, 'error');
    })
    .finally(() => {
        // Reabilita o botão
        button.disabled = false;
        button.textContent = originalText;
    });
}

function showNotification(message, type) {
    // Cria uma notificação temporária
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg z-50 ${
        type === 'success' ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-red-50 border border-red-200 text-red-700'
    }`;
    notification.innerHTML = `
        <div class="flex">
            <svg class="w-5 h-5 ${type === 'success' ? 'text-green-400' : 'text-red-400'}" fill="currentColor" viewBox="0 0 20 20">
                ${type === 'success' 
                    ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>'
                    : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>'
                }
            </svg>
            <p class="ml-3 text-sm">${message}</p>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Remove a notificação após 5 segundos
    setTimeout(() => {
        notification.remove();
    }, 5000);
}
</script>
@endsection