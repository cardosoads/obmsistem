# Configuração do Scheduler Laravel

## Agendamento Automático da Sincronização

O sistema foi configurado para executar automaticamente a sincronização de centros de custo com a API Omie todos os dias às 02:00.

### Configuração no Servidor

Para que o agendamento funcione, é necessário configurar um cron job no servidor que execute o scheduler do Laravel a cada minuto:

```bash
* * * * * cd /caminho/para/o/projeto && php artisan schedule:run >> /dev/null 2>&1
```

### Configuração Local (Desenvolvimento)

Para testar o scheduler localmente, execute:

```bash
php artisan schedule:work
```

Ou para executar apenas uma vez:

```bash
php artisan schedule:run
```

### Configuração Atual

- **Comando**: `omie:sync-centros-custo`
- **Frequência**: Diariamente às 02:00
- **Características**:
  - `withoutOverlapping()`: Evita execuções simultâneas
  - `runInBackground()`: Executa em segundo plano
  - Logs de sucesso e falha configurados

### Verificação

Para verificar se o scheduler está funcionando:

1. Verifique os logs em `storage/logs/laravel.log`
2. Execute `php artisan schedule:list` para ver todos os agendamentos
3. Execute `php artisan schedule:test` para testar os agendamentos

### Personalização

Para alterar a frequência ou horário, edite o arquivo `routes/console.php`:

```php
// Exemplos de outras frequências:
Schedule::command('omie:sync-centros-custo')->hourly();           // A cada hora
Schedule::command('omie:sync-centros-custo')->dailyAt('08:00');   // Diariamente às 08:00
Schedule::command('omie:sync-centros-custo')->weekly();           // Semanalmente
Schedule::command('omie:sync-centros-custo')->monthly();          // Mensalmente
```

### Monitoramento

O sistema registra logs automáticos:
- **Sucesso**: "Sincronização de centros de custo executada com sucesso"
- **Falha**: "Falha na sincronização automática de centros de custo"

Estes logs podem ser monitorados para garantir que a sincronização está funcionando corretamente.