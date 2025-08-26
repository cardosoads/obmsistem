<?php
require_once 'vendor/autoload.php';

// Carregar as configurações do Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    echo "Verificando usuário administrador...\n";
    
    // Verificar especificamente o usuário admin
    $admin = User::where('email', 'admin@obmsistem.com')->first();
    if ($admin) {
        echo "\nUsuário admin encontrado:\n";
        echo "ID: {$admin->id}\n";
        echo "Nome: {$admin->name}\n";
        echo "Email: {$admin->email}\n";
        echo "Password hash: " . substr($admin->password, 0, 30) . "...\n";
        
        // Testar várias senhas possíveis
        $senhas = ['password', '123456', 'admin', 'obmsistem', 'Password123'];
        
        foreach ($senhas as $senha) {
            if (Hash::check($senha, $admin->password)) {
                echo "Senha '{$senha}' confere: SIM\n";
                break;
            } else {
                echo "Senha '{$senha}' confere: NÃO\n";
            }
        }
        
        // Vamos também atualizar a senha para 'password' para garantir
        echo "\nAtualizando senha para 'password'...\n";
        $admin->password = Hash::make('password');
        $admin->save();
        echo "Senha atualizada com sucesso!\n";
        
    } else {
        echo "\nUsuário admin@obmsistem.com NÃO encontrado!\n";
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
?>