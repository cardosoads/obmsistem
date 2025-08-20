<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\OmieService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    /**
     * Exibe a página de configurações
     */
    public function index(): View
    {
        // Busca as configurações da API Omie
        $omieSettings = Setting::getByGroup('omie');
        
        // Busca outras configurações do sistema
        $systemSettings = Setting::getByGroup('system');
        
        return view('admin.settings', compact('omieSettings', 'systemSettings'));
    }

    /**
     * Atualiza as configurações da API Omie
     */
    public function updateOmie(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'omie_app_key' => 'required|string|max:255',
            'omie_app_secret' => 'required|string|max:255',
        ], [
            'omie_app_key.required' => 'O App Key da Omie é obrigatório.',
            'omie_app_secret.required' => 'O App Secret da Omie é obrigatório.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Salva as configurações da API Omie (criptografadas)
            Setting::set(
                'omie_app_key',
                $request->omie_app_key,
                'string',
                'omie',
                true, // Criptografar
                'Chave de aplicação da API Omie'
            );

            Setting::set(
                'omie_app_secret',
                $request->omie_app_secret,
                'string',
                'omie',
                true, // Criptografar
                'Chave secreta da API Omie'
            );

            return redirect()->back()
                ->with('success', 'Configurações da API Omie atualizadas com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Erro ao salvar configurações: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Testa a conexão com a API Omie
     */
    public function testOmieConnection(): JsonResponse
    {
        try {
            $omieService = new OmieService();
            $result = $omieService->testConnection();
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao testar conexão: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Atualiza configurações gerais do sistema
     */
    public function updateSystem(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'timezone' => 'required|string|max:50',
            'language' => 'required|string|max:10',
            'maintenance_mode' => 'boolean',
            'debug_logs' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Setting::set('timezone', $request->timezone, 'string', 'system', false, 'Fuso horário do sistema');
            Setting::set('language', $request->language, 'string', 'system', false, 'Idioma do sistema');
            Setting::set('maintenance_mode', $request->boolean('maintenance_mode'), 'boolean', 'system', false, 'Modo de manutenção');
            Setting::set('debug_logs', $request->boolean('debug_logs'), 'boolean', 'system', false, 'Logs de debug');

            return redirect()->back()
                ->with('success', 'Configurações do sistema atualizadas com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Erro ao salvar configurações: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
