<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Exibe o formulário de login
     */
    public function showLoginForm(): View
    {
        return view('welcome');
    }

    /**
     * Processa o login do usuário
     */
    public function login(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'Por favor, insira um email válido.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Redirecionar baseado no role do usuário
            $dashboardRoute = $user->getDashboardRoute();
            
            // Por enquanto, apenas admin tem dashboard implementado
            if ($user->isAdmin()) {
                return redirect()->intended(route($dashboardRoute));
            } else {
                // Para outros roles, redirecionar para admin por enquanto
                // TODO: Implementar dashboards específicos para manager e user
                return redirect()->intended(route('admin.dashboard'))
                    ->with('info', 'Dashboard específico para seu nível será implementado em breve.');
            }
        }

        return redirect()->back()
            ->withErrors(['email' => 'As credenciais fornecidas não conferem com nossos registros.'])
            ->withInput($request->except('password'));
    }

    /**
     * Realiza o logout do usuário
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Logout realizado com sucesso.');
    }

    /**
     * Verifica se o usuário está autenticado (para AJAX)
     */
    public function checkAuth()
    {
        return response()->json([
            'authenticated' => Auth::check(),
            'user' => Auth::user()
        ]);
    }
}