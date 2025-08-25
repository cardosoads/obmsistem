# Resumo Completo do Sistema de Orçamentos Antigo

## Visão Geral
O sistema de orçamentos era um módulo completo para gestão de orçamentos de transporte e logística, integrado com a API OMIE para dados de clientes e fornecedores. Foi removido em 22/08/2025 conforme solicitação do usuário.

## Estrutura de Dados

### 1. Tabela Principal: `orcamentos`

#### Campos Principais:
- **id**: Chave primária
- **data_solicitacao**: Data de solicitação do orçamento
- **centro_custo_id**: FK para centros de custo
- **numero_orcamento**: Número único gerado automaticamente
- **nome_rota**: Nome da rota de transporte
- **id_logcare**: ID do sistema LOGCARE
- **cliente_omie_id**: ID do cliente na API OMIE
- **cliente_nome**: Nome do cliente (cache da API OMIE)
- **horario**: Horário de atendimento
- **frequencia_atendimento**: Frequência de atendimento
- **tipo_orcamento**: Enum ['prestador', 'aumento_km', 'proprio_nova_rota']
- **user_id**: FK para usuário responsável
- **data_orcamento**: Data do orçamento
- **valor_total**: Valor total sem impostos
- **valor_impostos**: Valor dos impostos
- **valor_final**: Valor final com impostos
- **status**: Enum ['rascunho', 'enviado', 'aprovado', 'rejeitado', 'cancelado']
- **observacoes**: Observações gerais

#### Índices de Performance:
- `cliente_omie_id + data_orcamento`
- `status + data_orcamento`
- `user_id + data_orcamento`

### 2. Tabela: `orcamento_prestador`

#### Funcionalidade:
Gerencia orçamentos para prestadores de serviço terceirizados.

#### Campos:
- **orcamento_id**: FK para orçamento principal
- **fornecedor_omie_id**: ID do fornecedor na API OMIE
- **fornecedor_nome**: Nome do fornecedor (cache)
- **valor_referencia**: Valor de referência do fornecedor
- **qtd_dias**: Quantidade de dias de serviço
- **custo_fornecedor**: Custo calculado (valor_referencia * qtd_dias)
- **lucro_percentual**: Percentual de lucro aplicado
- **valor_lucro**: Valor do lucro calculado
- **impostos_percentual**: Percentual de impostos
- **valor_impostos**: Valor dos impostos calculado
- **valor_total**: Valor total final
- **observacoes**: Observações específicas

#### Cálculos Automáticos:
- `custo_fornecedor = valor_referencia * qtd_dias`
- `valor_lucro = custo_fornecedor * (lucro_percentual / 100)`
- `valor_impostos = (custo_fornecedor + valor_lucro) * (impostos_percentual / 100)`
- `valor_total = custo_fornecedor + valor_lucro + valor_impostos`

### 3. Tabela: `orcamento_aumento_km`

#### Funcionalidade:
Gerencia orçamentos para aumento de quilometragem em rotas existentes.

#### Campos:
- **orcamento_id**: FK para orçamento principal
- **km_dia**: Quilômetros por dia
- **qtd_dias**: Quantidade de dias
- **km_total_mes**: KM total do mês calculado
- **combustivel_km_litro**: Consumo de combustível (KM/Litro)
- **total_combustivel**: Total de combustível necessário
- **valor_combustivel**: Valor do combustível
- **hora_extra**: Valor de horas extras
- **custo_total_combustivel_he**: Custo total (combustível + HE)
- **lucro_percentual**: Percentual de lucro
- **valor_lucro**: Valor do lucro calculado
- **impostos_percentual**: Percentual de impostos
- **valor_impostos**: Valor dos impostos
- **valor_total**: Valor total final

#### Cálculos Automáticos:
- `km_total_mes = km_dia * qtd_dias`
- `total_combustivel = km_total_mes / combustivel_km_litro`
- `custo_total_combustivel_he = valor_combustivel + hora_extra`

### 4. Tabela: `orcamento_proprio_nova_rota`

#### Funcionalidade:
Gerencia orçamentos para novas rotas com veículos próprios.

#### Campos:
- **orcamento_id**: FK para orçamento principal
- **nova_origem**: Origem da nova rota
- **novo_destino**: Destino da nova rota
- **km_nova_rota**: Quilometragem da nova rota
- **valor_km_nova_rota**: Valor por quilômetro
- **valor_total_nova_rota**: Valor total calculado
- **motivo_alteracao**: Motivo da alteração de rota
- **observacoes**: Observações específicas

#### Cálculos Automáticos:
- `valor_total_nova_rota = km_nova_rota * valor_km_nova_rota`

### 5. Tabela: `orcamento_historico`

#### Funcionalidade:
Registra todas as alterações e ações realizadas nos orçamentos para auditoria.

#### Campos:
- **orcamento_id**: FK para orçamento principal
- **user_id**: FK para usuário que executou a ação
- **acao**: Tipo de ação realizada
- **status_anterior**: Status antes da alteração
- **status_novo**: Status após a alteração
- **dados_alterados**: JSON com dados alterados
- **observacoes**: Observações da ação
- **data_acao**: Data e hora da ação

## Modelos e Relacionamentos

### Modelo Orcamento

#### Relacionamentos:
- `belongsTo(CentroCusto::class)` - Centro de custo
- `belongsTo(User::class)` - Usuário responsável
- `hasMany(OrcamentoPrestador::class)` - Orçamentos de prestador
- `hasMany(OrcamentoAumentoKm::class)` - Aumentos de KM
- `hasMany(OrcamentoProprioNovaRota::class)` - Novas rotas próprias
- `hasMany(OrcamentoHistorico::class)` - Histórico de alterações

#### Métodos Principais:
- `getClienteOmieAttribute()`: Busca dados do cliente na API OMIE
- `calcularValorFinal()`: Calcula valor total com impostos
- `getStatusFormattedAttribute()`: Retorna status formatado

#### Scopes:
- `scopeRascunho()`: Filtra orçamentos em rascunho
- `scopeAprovado()`: Filtra orçamentos aprovados
- `scopeEnviado()`: Filtra orçamentos enviados

### Integração com API OMIE

#### Funcionalidades:
- Busca automática de dados de clientes
- Cache de nomes de clientes e fornecedores
- Sincronização de IDs OMIE

## Controller: OrcamentoController

### Métodos Principais:

#### `index(Request $request)`
- Lista orçamentos com filtros
- Filtros disponíveis: status, cliente_nome, data_inicio, data_fim, search
- Paginação de 15 itens
- Carrega relacionamentos: centroCusto, user

#### `create()`
- Exibe formulário de criação
- Carrega: bases, centros de custo, marcas

#### `storeDraft(Request $request)`
- Cria rascunho com informações básicas
- Gera número de orçamento automaticamente
- Registra no histórico
- Redireciona para edição

#### `store(Request $request)`
- Cria orçamento completo
- Suporta 3 tipos: prestador, aumento_km, proprio_nova_rota
- Cria registros específicos baseado no tipo
- Transação de banco de dados

#### Validações:
- **Campos obrigatórios**: data_solicitacao, centro_custo_id, nome_rota
- **Campos opcionais**: id_logcare, cliente_omie_id, cliente_nome, horario, frequencia_atendimento
- **Enum validations**: tipo_orcamento, status

### Geração de Números
- Formato: AUTO-YYYYMMDDHHMMSS
- Único por orçamento
- Gerado automaticamente

## Interface do Usuário

### Formulário de Criação (create.blade.php)

#### Seções:
1. **Informações Básicas**:
   - Data de solicitação
   - Centro de custo
   - Número do orçamento (auto-gerado)
   - Evento (AUMENTO DE KM, BASE, INCLUSÃO)
   - Nome da rota
   - ID LOGCARE

2. **Dados do Cliente**:
   - Pesquisa por ID ou nome
   - Preenchimento automático via API OMIE
   - Horário e frequência de atendimento

3. **Tipo de Orçamento**:
   - Prestador de Serviço
   - Aumento de KM
   - Próprio Nova Rota

4. **Seções Dinâmicas** (baseadas no tipo):
   - Formulários específicos para cada tipo
   - Cálculos automáticos em JavaScript
   - Validações em tempo real

#### Funcionalidades JavaScript:
- Pesquisa de prestadores com autocomplete
- Cálculos automáticos de valores
- Validação de formulários
- Interface responsiva

### Lista de Orçamentos (index.blade.php)

#### Funcionalidades:
- Tabela responsiva com orçamentos
- Filtros por status, cliente, datas
- Busca textual
- Paginação
- Ações: visualizar, editar, duplicar, alterar status

#### Colunas Exibidas:
- Número do orçamento
- Cliente
- Rota
- Status
- Valor total
- Data
- Ações

## Status do Orçamento

### Estados Possíveis:
- **rascunho**: Orçamento em elaboração
- **enviado**: Orçamento enviado ao cliente
- **aguardando**: Aguardando resposta do cliente
- **aprovado**: Orçamento aprovado pelo cliente
- **rejeitado**: Orçamento rejeitado pelo cliente
- **cancelado**: Orçamento cancelado internamente
- **expirado**: Orçamento vencido

### Fluxo de Status:
1. rascunho → enviado
2. enviado → aguardando
3. aguardando → aprovado/rejeitado
4. qualquer → cancelado

## Funcionalidades Especiais

### 1. Duplicação de Orçamentos
- Copia todos os dados do orçamento original
- Gera novo número automaticamente
- Mantém status como rascunho
- Preserva relacionamentos

### 2. Histórico de Alterações
- Registra todas as mudanças
- Rastreabilidade completa
- Dados em JSON para alterações específicas
- Auditoria de usuários

### 3. Cálculos Automáticos
- Valores calculados em tempo real
- Percentuais de lucro e impostos
- Totalizações automáticas
- Validações de consistência

### 4. Integração OMIE
- Sincronização de clientes
- Cache de dados para performance
- Tratamento de erros de API
- Fallback para dados locais

## Rotas Removidas

```php
// Rotas principais
Route::resource('admin/orcamentos', OrcamentoController::class);

// Rotas específicas
Route::post('/admin/orcamentos/{orcamento}/duplicate', [OrcamentoController::class, 'duplicate']);
Route::patch('/admin/orcamentos/{orcamento}/status', [OrcamentoController::class, 'updateStatus']);
Route::post('/admin/orcamentos/store-draft', [OrcamentoController::class, 'storeDraft']);
```

## Dependências Removidas

### Models:
- Relacionamentos em CentroCusto
- Relacionamentos em Cliente
- Relacionamentos em Marca
- Relacionamentos em User

### Controllers:
- Referências em outros controllers
- Validações de orçamentos em exclusões
- Carregamento de relacionamentos

### Views:
- Seções de orçamentos em outras views
- Links para criação de orçamentos
- Estatísticas de orçamentos
- Listagens de orçamentos relacionados

## Considerações Técnicas

### Performance:
- Índices otimizados para consultas frequentes
- Eager loading de relacionamentos
- Cache de dados da API OMIE
- Paginação eficiente

### Segurança:
- Validações server-side completas
- Proteção CSRF
- Autorização por usuário
- Sanitização de dados

### Manutenibilidade:
- Código modular e bem estruturado
- Comentários detalhados
- Padrões de nomenclatura consistentes
- Separação de responsabilidades

## Backup e Restauração

### Arquivos Salvos:
- 5 Models completos
- 1 Controller principal
- 2 Views principais
- 5 Migrações de banco
- Documentação completa

### Para Restaurar:
1. Copiar arquivos para pastas originais
2. Executar migrações: `php artisan migrate`
3. Adicionar rotas ao web.php
4. Limpar cache: `php artisan config:clear && php artisan route:clear`
5. Restaurar relacionamentos nos models existentes

---

**Data do Backup**: 22/08/2025  
**Motivo da Remoção**: Limpeza do projeto conforme solicitação do usuário  
**Status**: Sistema completamente removido e documentado