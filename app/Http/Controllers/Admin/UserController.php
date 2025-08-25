<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'inactive') {
                $query->whereNull('email_verified_at');
            }
        }
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        $users = $query->latest()->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = ['admin' => 'Administrador', 'manager' => 'Gerente', 'user' => 'Usuário'];
        
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,manager,user',
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'Por favor, insira um email válido.',
            'email.unique' => 'Este email já está sendo usado por outro usuário.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'role.required' => 'O campo função é obrigatório.',
            'role.in' => 'Função inválida selecionada.',
        ]);
        
        try {
            DB::beginTransaction();
            
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'email_verified_at' => now(), // Usuário criado pelo admin já é verificado
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.users.index')
                ->with('success', 'Usuário criado com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->withErrors(['error' => 'Erro ao criar usuário: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Removido: $user->load('orcamentos'); - orçamentos foram removidos do sistema
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = ['admin' => 'Administrador', 'manager' => 'Gerente', 'user' => 'Usuário'];
        
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,manager,user',
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'Por favor, insira um email válido.',
            'email.unique' => 'Este email já está sendo usado por outro usuário.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'role.required' => 'O campo função é obrigatório.',
            'role.in' => 'Função inválida selecionada.',
        ]);
        
        try {
            DB::beginTransaction();
            
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
            ];
            
            // Só atualizar senha se foi fornecida
            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }
            
            $user->update($updateData);
            
            DB::commit();
            
            return redirect()->route('admin.users.index')
                ->with('success', 'Usuário atualizado com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->withErrors(['error' => 'Erro ao atualizar usuário: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            // Removido: verificação de orçamentos - orçamentos foram removidos do sistema
            
            // Não permitir excluir o próprio usuário
            if ($user->id === auth()->id()) {
                return back()->withErrors([
                    'error' => 'Você não pode excluir sua própria conta.'
                ]);
            }
            
            $user->delete();
            
            return redirect()->route('admin.users.index')
                ->with('success', 'Usuário excluído com sucesso!');
                
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Erro ao excluir usuário: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Toggle user status (activate/deactivate)
     */
    public function toggleStatus(User $user)
    {
        try {
            // Não permitir desativar o próprio usuário
            if ($user->id === auth()->id()) {
                return back()->withErrors([
                    'error' => 'Você não pode alterar o status da sua própria conta.'
                ]);
            }
            
            $user->email_verified_at = $user->email_verified_at ? null : now();
            $user->save();
            
            $status = $user->email_verified_at ? 'ativado' : 'desativado';
            
            return back()->with('success', "Usuário {$status} com sucesso!");
            
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Erro ao alterar status do usuário: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Reset user password
     */
    public function resetPassword(User $user)
    {
        try {
            $newPassword = 'user123'; // Senha padrão
            
            $user->update([
                'password' => Hash::make($newPassword)
            ]);
            
            return back()->with('success', "Senha do usuário resetada para: {$newPassword}");
            
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Erro ao resetar senha: ' . $e->getMessage()
            ]);
        }
    }
}