<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Seed impostos
        $this->call(ImpostosSeeder::class);
        
        // Seed bases
        $this->call(BaseSeeder::class);
        
        // Seed marcas
        $this->call(MarcaSeeder::class);
        
        // Seed centros de custo
        $this->call(CentroCustoSeeder::class);
    }
}
