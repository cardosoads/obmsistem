<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário está autenticado
        if (!Auth::check()) {
            // Se for uma requisição AJAX, retorna JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Não autenticado.',
                    'redirect' => '/'
                ], 401);
            }
            
            // Redireciona para a página de login com mensagem
            return redirect('/')
                ->with('error', 'Você precisa estar logado para acessar esta área.');
        }

        // Futuramente aqui pode ser adicionada verificação de nível/papel do usuário
        // Por exemplo: if (!Auth::user()->isAdmin()) { ... }
        
        return $next($request);
    }
}