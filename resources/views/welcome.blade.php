<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OBM Sistema - Login</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo/Brand Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-800 rounded-2xl mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-semibold text-slate-800 mb-2">OBM Sistema</h1>
            <p class="text-slate-600">Acesse sua conta para continuar</p>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-8">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-green-800 text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-blue-800 text-sm font-medium">{{ session('info') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-red-800 text-sm font-medium mb-2">Erro no login:</p>
                            <ul class="text-red-700 text-sm space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form id="loginForm" class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                        E-mail
                    </label>
                    <div class="relative">
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required 
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 border @error('email') border-red-300 @else border-slate-300 @enderror rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent transition-all duration-200 bg-slate-50 focus:bg-white"
                            placeholder="seu@email.com"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                    </div>
                    <div id="email-error" class="mt-1 text-sm text-red-600 hidden"></div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                        Senha
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            class="w-full px-4 py-3 border @error('password') border-red-300 @else border-slate-300 @enderror rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent transition-all duration-200 bg-slate-50 focus:bg-white"
                            placeholder="••••••••"
                        >
                        <button 
                            type="button" 
                            id="togglePassword" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        >
                            <svg id="eyeIcon" class="h-5 w-5 text-slate-400 hover:text-slate-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="password-error" class="mt-1 text-sm text-red-600 hidden"></div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            id="remember" 
                            name="remember" 
                            type="checkbox" 
                            class="h-4 w-4 text-slate-600 focus:ring-slate-500 border-slate-300 rounded"
                        >
                        <label for="remember" class="ml-2 block text-sm text-slate-600">
                            Lembrar de mim
                        </label>
                    </div>
                    <a href="#" class="text-sm text-slate-600 hover:text-slate-800 transition-colors duration-200">
                        Esqueceu a senha?
                    </a>
                </div>

                <!-- Login Button -->
                <button 
                    type="submit" 
                    id="loginButton"
                    class="w-full bg-slate-800 hover:bg-slate-900 disabled:bg-slate-400 disabled:cursor-not-allowed text-white font-medium py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 flex items-center justify-center"
                >
                    <span id="buttonText">Entrar</span>
                    <svg id="loadingIcon" class="animate-spin -mr-1 ml-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-sm text-slate-500">
                Não tem uma conta? 
                <a href="#" class="text-slate-700 hover:text-slate-900 font-medium transition-colors duration-200">
                    Cadastre-se
                </a>
            </p>
        </div>
    </div>

    <!-- Background Pattern -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-40 -right-32 w-80 h-80 bg-slate-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse"></div>
        <div class="absolute -bottom-40 -left-32 w-80 h-80 bg-slate-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <!-- JavaScript para validação e interatividade -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const loginButton = document.getElementById('loginButton');
            const buttonText = document.getElementById('buttonText');
            const loadingIcon = document.getElementById('loadingIcon');
            const togglePassword = document.getElementById('togglePassword');
            const eyeIcon = document.getElementById('eyeIcon');
            
            // Toggle de visualização da senha
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Alterar ícone do olho
                if (type === 'text') {
                    eyeIcon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                    `;
                } else {
                    eyeIcon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    `;
                }
            });
            
            // Validação em tempo real do email
            emailInput.addEventListener('input', function() {
                const email = this.value;
                const emailError = document.getElementById('email-error');
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (email && !emailRegex.test(email)) {
                    emailError.textContent = 'Por favor, insira um e-mail válido.';
                    emailError.classList.remove('hidden');
                    this.classList.add('border-red-300');
                    this.classList.remove('border-slate-300');
                } else {
                    emailError.classList.add('hidden');
                    this.classList.remove('border-red-300');
                    this.classList.add('border-slate-300');
                }
                
                validateForm();
            });
            
            // Validação em tempo real da senha
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                const passwordError = document.getElementById('password-error');
                
                if (password && password.length < 6) {
                    passwordError.textContent = 'A senha deve ter pelo menos 6 caracteres.';
                    passwordError.classList.remove('hidden');
                    this.classList.add('border-red-300');
                    this.classList.remove('border-slate-300');
                } else {
                    passwordError.classList.add('hidden');
                    this.classList.remove('border-red-300');
                    this.classList.add('border-slate-300');
                }
                
                validateForm();
            });
            
            // Função para validar o formulário completo
            function validateForm() {
                const email = emailInput.value;
                const password = passwordInput.value;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                const isValid = email && 
                               emailRegex.test(email) && 
                               password && 
                               password.length >= 6;
                
                loginButton.disabled = !isValid;
            }
            
            // Submissão do formulário com loading
            loginForm.addEventListener('submit', function(e) {
                // Mostrar loading
                loginButton.disabled = true;
                buttonText.textContent = 'Entrando...';
                loadingIcon.classList.remove('hidden');
                
                // Validação final antes do envio
                const email = emailInput.value;
                const password = passwordInput.value;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (!email || !emailRegex.test(email)) {
                    e.preventDefault();
                    showError('email-error', 'Por favor, insira um e-mail válido.');
                    resetButton();
                    return;
                }
                
                if (!password || password.length < 6) {
                    e.preventDefault();
                    showError('password-error', 'A senha deve ter pelo menos 6 caracteres.');
                    resetButton();
                    return;
                }
            });
            
            // Função para mostrar erro
            function showError(elementId, message) {
                const errorElement = document.getElementById(elementId);
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
            }
            
            // Função para resetar o botão
            function resetButton() {
                loginButton.disabled = false;
                buttonText.textContent = 'Entrar';
                loadingIcon.classList.add('hidden');
            }
            
            // Validação inicial
            validateForm();
            
            // Auto-hide flash messages após 5 segundos
            const flashMessages = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-blue-50"], [class*="bg-red-50"]');
            flashMessages.forEach(function(message) {
                setTimeout(function() {
                    message.style.transition = 'opacity 0.5s ease-out';
                    message.style.opacity = '0';
                    setTimeout(function() {
                        message.remove();
                    }, 500);
                }, 5000);
            });
        });
    </script>
</body>
</html>
