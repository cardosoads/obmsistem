# Configuração da API Omie

## Problema Resolvido

O erro "Falha na conexão: A chave de acesso não está preenchida ou não é válida" ocorria porque o sistema estava tentando ler as configurações da API Omie das variáveis de ambiente, mas elas estão armazenadas no banco de dados.

## Correções Implementadas

### 1. Modificação dos Services

- **OmieService**: Alterado para ler as chaves do banco de dados via `Setting::get()`
- **OmieApiService**: Alterado para ler as chaves do banco de dados via `Setting::get()`

### 2. Validações Adicionadas

- Verificação se as chaves estão configuradas antes de fazer requisições
- Mensagens de erro mais claras quando as chaves não estão configuradas
- Logs de erro quando as chaves não são encontradas

## Como Configurar as Chaves da API Omie

### 1. Acesse o Painel Administrativo

1. Faça login no sistema
2. Acesse o menu "Configurações" ou "Settings"
3. Localize a seção "Configurações da API Omie"

### 2. Configure as Chaves

1. **App Key**: Insira a chave de aplicação fornecida pela Omie
2. **App Secret**: Insira a chave secreta fornecida pela Omie
3. Clique em "Salvar Configurações"

### 3. Teste a Conexão

1. Após salvar as configurações, clique em "Testar Conexão"
2. O sistema deve retornar "Conexão estabelecida com sucesso!"

## Segurança

- As chaves são armazenadas criptografadas no banco de dados
- Nunca exponha as chaves em logs ou código
- As chaves são carregadas dinamicamente a cada requisição

## Troubleshooting

### Erro: "A chave de acesso não está preenchida ou não é válida"

**Causa**: As chaves da API Omie não estão configuradas no sistema.

**Solução**: 
1. Acesse as configurações do sistema
2. Configure as chaves da API Omie
3. Teste a conexão

### Erro: "Chaves da API Omie não configuradas" nos logs

**Causa**: O sistema não conseguiu carregar as chaves do banco de dados.

**Solução**:
1. Verifique se as chaves estão salvas no banco de dados
2. Execute: `php artisan tinker` e teste: `\App\Models\Setting::get('omie_app_key')`
3. Se retornar null, configure as chaves novamente

## Estrutura no Banco de Dados

As configurações são armazenadas na tabela `settings` com:

- `key`: 'omie_app_key' ou 'omie_app_secret'
- `value`: Valor criptografado da chave
- `group`: 'omie'
- `is_encrypted`: true
- `type`: 'string'

## Arquivos Modificados

- `app/Services/OmieService.php`
- `app/Services/OmieApiService.php`

Essas modificações garantem que o sistema funcione corretamente tanto no ambiente de desenvolvimento quanto em produção, independentemente das variáveis de ambiente configuradas.