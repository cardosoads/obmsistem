<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = [
            [
                'omie_id' => '12345',
                'name' => 'Empresa ABC Ltda',
                'document' => '12.345.678/0001-90',
                'email' => 'contato@empresaabc.com.br',
                'phone' => '(11) 99999-9999',
                'active' => true
            ],
            [
                'omie_id' => '12346',
                'name' => 'João Silva',
                'document' => '123.456.789-01',
                'email' => 'joao.silva@email.com',
                'phone' => '(11) 88888-8888',
                'active' => true
            ],
            [
                'omie_id' => '12347',
                'name' => 'Maria Santos Comércio',
                'document' => '98.765.432/0001-10',
                'email' => 'maria@mariasantos.com.br',
                'phone' => '(21) 77777-7777',
                'active' => true
            ],
            [
                'omie_id' => '12348',
                'name' => 'Pedro Oliveira',
                'document' => '987.654.321-00',
                'email' => 'pedro.oliveira@gmail.com',
                'phone' => '(31) 66666-6666',
                'active' => true
            ],
            [
                'omie_id' => '12349',
                'name' => 'Tech Solutions Ltda',
                'document' => '11.222.333/0001-44',
                'email' => 'contato@techsolutions.com.br',
                'phone' => '(41) 55555-5555',
                'active' => true
            ]
        ];

        foreach ($clientes as $clienteData) {
            Cliente::updateOrCreate(
                ['omie_id' => $clienteData['omie_id']],
                $clienteData
            );
        }
    }
}
