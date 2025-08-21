<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class OmieDiagnose extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'omie:diagnose {--detailed : Exibir informações detalhadas}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnostica problemas de conectividade com a API Omie';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Iniciando diagnóstico da API Omie...');
        $this->newLine();

        $detailed = $this->option('detailed');
        $issues = [];
        $warnings = [];

        // 1. Verificar configurações do banco de dados
        $this->info('1. Verificando configurações no banco de dados...');
        $appKey = Setting::get('omie_app_key');
        $appSecret = Setting::get('omie_app_secret');

        if (empty($appKey)) {
            $issues[] = 'App Key da Omie não configurada no banco de dados';
            $this->error('   ❌ App Key não encontrada');
        } else {
            $this->info('   ✅ App Key configurada');
            if ($detailed) {
                $this->line('   📝 App Key: ' . substr($appKey, 0, 8) . '...');
            }
        }

        if (empty($appSecret)) {
            $issues[] = 'App Secret da Omie não configurada no banco de dados';
            $this->error('   ❌ App Secret não encontrada');
        } else {
            $this->info('   ✅ App Secret configurada');
            if ($detailed) {
                $this->line('   📝 App Secret: ' . substr($appSecret, 0, 8) . '...');
            }
        }

        $this->newLine();

        // 2. Verificar conectividade básica
        $this->info('2. Testando conectividade básica...');
        $apiUrl = config('services.omie.api_url', 'https://app.omie.com.br/api/v1/');
        
        try {
            $response = Http::timeout(10)->get('https://app.omie.com.br');
            if ($response->successful()) {
                $this->info('   ✅ Conectividade com omie.com.br OK');
            } else {
                $warnings[] = 'Resposta HTTP não esperada do site da Omie: ' . $response->status();
                $this->warn('   ⚠️  Resposta HTTP: ' . $response->status());
            }
        } catch (Exception $e) {
            $issues[] = 'Falha na conectividade básica: ' . $e->getMessage();
            $this->error('   ❌ Erro de conectividade: ' . $e->getMessage());
        }

        $this->newLine();

        // 3. Testar SSL/TLS
        $this->info('3. Verificando configurações SSL/TLS...');
        try {
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => true,
                    'verify_peer_name' => true,
                ]
            ]);
            
            $result = file_get_contents('https://app.omie.com.br', false, $context);
            if ($result !== false) {
                $this->info('   ✅ Certificado SSL válido');
            }
        } catch (Exception $e) {
            $issues[] = 'Problema com certificado SSL: ' . $e->getMessage();
            $this->error('   ❌ Erro SSL: ' . $e->getMessage());
        }

        $this->newLine();

        // 4. Testar API Omie se as chaves estão configuradas
        if (!empty($appKey) && !empty($appSecret)) {
            $this->info('4. Testando API Omie...');
            
            try {
                $response = Http::timeout(30)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                    ])
                    ->post($apiUrl . 'geral/clientes/', [
                        'call' => 'ListarClientes',
                        'app_key' => $appKey,
                        'app_secret' => $appSecret,
                        'param' => [[
                            'pagina' => 1,
                            'registros_por_pagina' => 1
                        ]]
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['faultstring'])) {
                        $issues[] = 'Erro da API Omie: ' . $data['faultstring'];
                        $this->error('   ❌ Erro da API: ' . $data['faultstring']);
                    } else {
                        $this->info('   ✅ API Omie respondeu com sucesso');
                        if ($detailed && isset($data['total_de_registros'])) {
                            $this->line('   📊 Total de clientes: ' . $data['total_de_registros']);
                        }
                    }
                } else {
                    $issues[] = 'Falha na requisição à API Omie: HTTP ' . $response->status();
                    $this->error('   ❌ HTTP ' . $response->status() . ': ' . $response->body());
                }
            } catch (Exception $e) {
                $issues[] = 'Exceção ao testar API Omie: ' . $e->getMessage();
                $this->error('   ❌ Exceção: ' . $e->getMessage());
            }
        } else {
            $this->warn('4. ⚠️  Pulando teste da API (chaves não configuradas)');
        }

        $this->newLine();

        // 5. Verificar configurações do servidor
        $this->info('5. Informações do ambiente...');
        $this->line('   🖥️  PHP Version: ' . PHP_VERSION);
        $this->line('   🌐 User Agent: ' . (ini_get('user_agent') ?: 'Não definido'));
        $this->line('   🔒 OpenSSL: ' . (extension_loaded('openssl') ? 'Habilitado' : 'Desabilitado'));
        $this->line('   📡 cURL: ' . (extension_loaded('curl') ? 'Habilitado' : 'Desabilitado'));
        
        if ($detailed) {
            $this->line('   ⏱️  Default timeout: ' . ini_get('default_socket_timeout') . 's');
            $this->line('   🔗 Allow URL fopen: ' . (ini_get('allow_url_fopen') ? 'Sim' : 'Não'));
        }

        $this->newLine();

        // Resumo
        $this->info('📋 RESUMO DO DIAGNÓSTICO');
        $this->line('═══════════════════════════');
        
        if (empty($issues)) {
            $this->info('✅ Nenhum problema crítico encontrado!');
        } else {
            $this->error('❌ Problemas encontrados:');
            foreach ($issues as $issue) {
                $this->line('   • ' . $issue);
            }
        }

        if (!empty($warnings)) {
            $this->newLine();
            $this->warn('⚠️  Avisos:');
            foreach ($warnings as $warning) {
                $this->line('   • ' . $warning);
            }
        }

        $this->newLine();
        $this->info('💡 PRÓXIMOS PASSOS:');
        
        if (empty($appKey) || empty($appSecret)) {
            $this->line('1. Configure as chaves da API Omie no painel administrativo');
            $this->line('2. Acesse: /admin/settings e vá para a aba "Omie API"');
        }
        
        if (!empty($issues)) {
            $this->line('3. Verifique as configurações de firewall/proxy do servidor');
            $this->line('4. Contate o suporte técnico se os problemas persistirem');
        }

        return empty($issues) ? 0 : 1;
    }
}