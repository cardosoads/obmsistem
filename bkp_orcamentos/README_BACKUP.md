# Backup do Sistema de Orçamentos

Este diretório contém o backup completo do sistema de orçamentos que foi removido do projeto principal.

## Data do Backup
22/08/2025

## Arquivos Incluídos

### Models
- Orcamento.php
- OrcamentoHistorico.php
- OrcamentoPrestador.php
- OrcamentoAumentoKm.php
- OrcamentoProprioNovaRota.php

### Controller
- OrcamentoController.php

### Views
- create.blade.php
- index.blade.php

### Migrações
- Todas as migrações relacionadas aos orçamentos
- 2025_01_20_000001_add_missing_fields_to_orcamentos_table.php

### Rotas Removidas
As seguintes rotas foram removidas do arquivo web.php:
- Route::resource('admin/orcamentos', OrcamentoController::class)
- Route::post('/admin/orcamentos/{orcamento}/duplicate')
- Route::patch('/admin/orcamentos/{orcamento}/status')
- Route::post('/admin/orcamentos/store-draft')

## Motivo da Remoção
Sistema de orçamentos removido conforme solicitação do usuário para limpeza do projeto.

## Como Restaurar
Para restaurar o sistema de orçamentos:
1. Copie os arquivos de volta para suas respectivas pastas
2. Execute as migrações: `php artisan migrate`
3. Adicione as rotas de volta ao web.php
4. Limpe o cache: `php artisan config:clear && php artisan route:clear`