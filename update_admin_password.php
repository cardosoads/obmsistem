<?php
require_once 'vendor/autoload.php';

// Carregar as configurações do Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    echo "Atualizando senha do administrador...\n";
    
    // Encontrar o usuário admin
    $admin = User::where('email', 'admin@obmsistem.com')->first();
    
    if ($admin) {
        // Atualizar a senha
        $admin->password = Hash::make('admin123');
        $admin->save();
        
        echo "Senha do admin atualizada com sucesso!\n";
        
        // Verificar se a senha foi atualizada corretamente
        if (Hash::check('admin123', $admin->password)) {
            echo "Verificação: Senha 'admin123' confere: SIM\n";
        } else {
            echo "Verificação: Senha 'admin123' confere: NÃO\n";
        }
        
    } else {
        echo "Usuário admin@obmsistem.com não encontrado!\n";
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
?>