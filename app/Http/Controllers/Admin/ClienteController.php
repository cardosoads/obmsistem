<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Cliente::query();
        
        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('document', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('active', false);
            }
        }
        
        if ($request->filled('document_type')) {
            if ($request->document_type === 'cpf') {
                $query->whereRaw('LENGTH(REPLACE(REPLACE(REPLACE(document, ".", ""), "-", ""), "/", "")) = 11');
            } elseif ($request->document_type === 'cnpj') {
                $query->whereRaw('LENGTH(REPLACE(REPLACE(REPLACE(document, ".", ""), "-", ""), "/", "")) = 14');
            }
        }
        
        $clientes = $query->latest()->paginate(15);
        
        return view('admin.clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'document' => 'required|string|max:20|unique:clientes,document',
            'email' => 'required|email|max:255|unique:clientes,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:10',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'observations' => 'nullable|string|max:1000',
            'active' => 'boolean'
        ]);
        
        // Validar formato do documento
        $documentClean = preg_replace('/[^0-9]/', '', $validated['document']);
        
        if (strlen($documentClean) === 11) {
            // CPF
            if (!$this->validarCPF($documentClean)) {
                return back()->withInput()->withErrors(['document' => 'CPF inválido.']);
            }
        } elseif (strlen($documentClean) === 14) {
            // CNPJ
            if (!$this->validarCNPJ($documentClean)) {
                return back()->withInput()->withErrors(['document' => 'CNPJ inválido.']);
            }
        } else {
            return back()->withInput()->withErrors(['document' => 'Documento deve ser um CPF (11 dígitos) ou CNPJ (14 dígitos).']);
        }
        
        try {
            Cliente::create($validated);
            
            return redirect()->route('admin.clientes.index')
                           ->with('success', 'Cliente criado com sucesso!');
                           
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erro ao criar cliente: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        $cliente->load('orcamentos.user');
        
        return view('admin.clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        return view('admin.clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'document' => ['required', 'string', 'max:20', Rule::unique('clientes')->ignore($cliente->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('clientes')->ignore($cliente->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:10',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'observations' => 'nullable|string|max:1000',
            'active' => 'boolean'
        ]);
        
        // Validar formato do documento se foi alterado
        if ($validated['document'] !== $cliente->document) {
            $documentClean = preg_replace('/[^0-9]/', '', $validated['document']);
            
            if (strlen($documentClean) === 11) {
                // CPF
                if (!$this->validarCPF($documentClean)) {
                    return back()->withInput()->withErrors(['document' => 'CPF inválido.']);
                }
            } elseif (strlen($documentClean) === 14) {
                // CNPJ
                if (!$this->validarCNPJ($documentClean)) {
                    return back()->withInput()->withErrors(['document' => 'CNPJ inválido.']);
                }
            } else {
                return back()->withInput()->withErrors(['document' => 'Documento deve ser um CPF (11 dígitos) ou CNPJ (14 dígitos).']);
            }
        }
        
        try {
            $cliente->update($validated);
            
            return redirect()->route('admin.clientes.show', $cliente)
                           ->with('success', 'Cliente atualizado com sucesso!');
                           
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erro ao atualizar cliente: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        // Verificar se o cliente possui orçamentos
        if ($cliente->orcamentos()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível excluir este cliente pois ele possui orçamentos associados.'
            ], 422);
        }
        
        try {
            $cliente->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Cliente excluído com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir cliente: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Alterna o status ativo/inativo do cliente
     */
    public function toggleStatus(Cliente $cliente)
    {
        try {
            $cliente->update(['active' => !$cliente->active]);
            
            $status = $cliente->active ? 'ativado' : 'desativado';
            
            return response()->json([
                'success' => true,
                'message' => "Cliente {$status} com sucesso!",
                'new_status' => $cliente->active
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Valida CPF
     */
    private function validarCPF(string $cpf): bool
    {
        // Elimina possível máscara
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        
        // Verifica se foi informado todos os dígitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }
        
        // Verifica se foi informada uma sequência de dígitos repetidos. Ex: 111.111.111-11
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }
        
        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Valida CNPJ
     */
    private function validarCNPJ(string $cnpj): bool
    {
        // Elimina possível máscara
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        
        // Verifica se foi informado todos os dígitos corretamente
        if (strlen($cnpj) != 14) {
            return false;
        }
        
        // Verifica se foi informada uma sequência de dígitos repetidos. Ex: 11.111.111/0001-11
        if (preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }
        
        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        
        $resto = $soma % 11;
        
        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }
        
        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        
        $resto = $soma % 11;
        
        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }
}
