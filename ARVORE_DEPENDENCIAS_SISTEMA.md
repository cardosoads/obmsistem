# Árvore de Dependências do Sistema OBM

## 🌳 Estrutura Completa de Dependências

```
SISTEMA OBM
│
├── 🔧 CONFIGURAÇÕES BASE
│   ├── 🔑 Autenticação API Omie
│   │   ├── app_key (obrigatório)
│   │   ├── app_secret (obrigatório)
│   │   └── api_url (configurável)
│   ├── 🏢 Configurações da Empresa
│   │   ├── dados_empresa (razão social, CNPJ)
│   │   ├── configurações_fiscais
│   │   └── parâmetros_sistema
│   └── 🗄️ Banco de Dados
│       ├── migrations (estrutura das tabelas)
│       ├── seeders (dados iniciais)
│       └── settings (configurações criptografadas)
│
├── 📊 DADOS MESTRES (SEEDERS)
│   ├── 🏢 Bases
│   │   ├── BaseSeeder
│   │   └── Dependências: nenhuma
│   ├── 🏷️ Marcas
│   │   ├── MarcaSeeder
│   │   └── Dependências: nenhuma
│   ├── 💼 Centros de Custo
│   │   ├── CentroCustoSeeder
│   │   └── Dependências: → Marcas (marca_id)
│   ├── 📋 Grupos de Impostos
│   │   ├── GrupoImpostoSeeder
│   │   └── Dependências: nenhuma
│   ├── 💰 Impostos
│   │   ├── ImpostosSeeder
│   │   └── Dependências: → Grupos de Impostos (grupo_id)
│   └── 👤 Usuário Admin
│       ├── AdminUserSeeder
│       └── Dependências: nenhuma
│
├── 🔄 INTEGRAÇÃO API OMIE
│   ├── 🏢 Empresas
│   │   ├── Endpoint: geral/empresas/
│   │   ├── Método: ListarEmpresas
│   │   ├── Dependências: → Configurações API
│   │   └── Retorna: Lista de empresas cadastradas
│   ├── 👥 Clientes
│   │   ├── Endpoint: geral/clientes/
│   │   ├── Métodos:
│   │   │   ├── ListarClientes
│   │   │   │   ├── Parâmetros: paginação, filtros
│   │   │   │   └── Retorna: Lista paginada de clientes
│   │   │   └── ConsultarCliente
│   │   │       ├── Parâmetros: codigo_cliente_omie
│   │   │       └── Retorna: Dados completos do cliente
│   │   ├── Dependências: → Configurações API
│   │   └── Relacionamentos:
│   │       ├── → Orçamentos (cliente_omie_id)
│   │       ├── → Pedidos (cliente_id)
│   │       └── → Contratos (cliente_id)
│   ├── 🏭 Fornecedores
│   │   ├── Endpoint: geral/fornecedor/
│   │   ├── Métodos:
│   │   │   ├── ListarFornecedores
│   │   │   │   ├── Parâmetros: paginação, filtros
│   │   │   │   └── Retorna: Lista paginada de fornecedores
│   │   │   └── ConsultarFornecedor
│   │   │       ├── Parâmetros: codigo_fornecedor_omie
│   │   │       └── Retorna: Dados completos do fornecedor
│   │   ├── Dependências: → Configurações API
│   │   └── Relacionamentos:
│   │       ├── → Produtos (fornecedor_id)
│   │       ├── → Cotações (fornecedor_id)
│   │       └── → Orçamentos (fornecedor_id)
│   └── 📦 Produtos
│       ├── Endpoint: geral/produtos/
│       ├── Métodos:
│       │   ├── ListarProdutos
│       │   │   ├── Parâmetros: paginação, filtros
│       │   │   └── Retorna: Lista paginada de produtos
│       │   └── ConsultarProduto
│       │       ├── Parâmetros: codigo_produto_omie
│       │       └── Retorna: Dados completos do produto
│       ├── Dependências: → Configurações API
│       └── Relacionamentos:
│           ├── → Orçamentos (produto_id)
│           ├── → Estoque (produto_id)
│           └── → Fornecedores (produto_fornecedor)
│
├── 💼 MÓDULO DE ORÇAMENTOS
│   ├── 📋 Orçamento Principal
│   │   ├── Dependências:
│   │   │   ├── → Cliente Omie (cliente_omie_id)
│   │   │   ├── → Fornecedor Omie (fornecedor_omie_id)
│   │   │   ├── → Base (base_id)
│   │   │   ├── → Centro de Custo (centro_custo_id)
│   │   │   └── → Usuário (user_id)
│   │   ├── Campos Principais:
│   │   │   ├── informações_básicas (número, data, validade)
│   │   │   ├── dados_cliente (nome, documento, contato)
│   │   │   ├── dados_fornecedor (nome, documento, contato)
│   │   │   ├── valores_totais (subtotal, impostos, total)
│   │   │   └── status (rascunho, enviado, aprovado, rejeitado)
│   │   └── Relacionamentos:
│   │       ├── → Itens do Orçamento (1:N)
│   │       ├── → Dados do Prestador (1:1)
│   │       ├── → Aumento de KM (1:1)
│   │       └── → Histórico de Status (1:N)
│   ├── 📝 Itens do Orçamento
│   │   ├── Dependências:
│   │   │   ├── → Orçamento (orcamento_id)
│   │   │   └── → Produto Omie (produto_omie_id - opcional)
│   │   ├── Campos Principais:
│   │   │   ├── descrição_item
│   │   │   ├── quantidade
│   │   │   ├── valor_unitario
│   │   │   ├── valor_total
│   │   │   └── observações
│   │   └── Cálculos Automáticos:
│   │       ├── valor_total = quantidade × valor_unitario
│   │       ├── percentual_item = (valor_total / total_orcamento) × 100
│   │       └── impostos_item = valor_total × alíquota_impostos
│   ├── 👨‍💼 Dados do Prestador
│   │   ├── Dependências:
│   │   │   ├── → Orçamento (orcamento_id)
│   │   │   └── → Grupo de Impostos (grupo_impostos_id)
│   │   ├── Campos Principais:
│   │   │   ├── percentual_lucro
│   │   │   ├── percentual_impostos
│   │   │   ├── custo_fornecedor
│   │   │   ├── valor_final_prestador
│   │   │   └── observações
│   │   └── Cálculos Automáticos:
│   │       ├── valor_impostos = custo_fornecedor × (percentual_impostos / 100)
│   │       ├── valor_lucro = custo_fornecedor × (percentual_lucro / 100)
│   │       └── valor_final = custo_fornecedor + valor_impostos + valor_lucro
│   └── 🚗 Aumento de KM
│       ├── Dependências:
│       │   └── → Orçamento (orcamento_id)
│       ├── Campos Principais:
│       │   ├── km_total_mes
│       │   ├── total_combustivel
│       │   ├── custo_total_combustivel_he
│       │   ├── valor_km_adicional
│       │   └── observações
│       └── Cálculos Automáticos:
│           ├── custo_por_km = total_combustivel / km_total_mes
│           ├── valor_adicional = km_adicional × custo_por_km
│           └── custo_total = custo_total_combustivel_he + valor_adicional
│
├── ⚙️ SISTEMA DE CONFIGURAÇÕES
│   ├── 🔧 Settings (Configurações Gerais)
│   │   ├── Estrutura:
│   │   │   ├── key (chave única)
│   │   │   ├── value (valor criptografado)
│   │   │   ├── group (agrupamento)
│   │   │   ├── type (tipo de dado)
│   │   │   └── is_encrypted (flag de criptografia)
│   │   ├── Grupos de Configuração:
│   │   │   ├── 'omie' (configurações da API Omie)
│   │   │   ├── 'system' (configurações do sistema)
│   │   │   ├── 'email' (configurações de email)
│   │   │   └── 'pdf' (configurações de PDF)
│   │   └── Dependências: nenhuma
│   ├── 🏢 Configurações Omie
│   │   ├── omie_app_key (criptografado)
│   │   ├── omie_app_secret (criptografado)
│   │   ├── omie_api_url (configurável)
│   │   └── omie_timeout (configurável)
│   └── 📧 Configurações de Notificação
│       ├── email_notifications (S/N)
│       ├── smtp_settings (servidor, porta, usuário)
│       └── notification_templates (templates de email)
│
├── 👤 SISTEMA DE USUÁRIOS
│   ├── 🔐 Autenticação
│   │   ├── Dependências: → Laravel Breeze
│   │   ├── Funcionalidades:
│   │   │   ├── login/logout
│   │   │   ├── registro de usuários
│   │   │   ├── recuperação de senha
│   │   │   └── verificação de email
│   │   └── Middleware: auth, verified
│   ├── 👥 Gerenciamento de Usuários
│   │   ├── Dependências: → Autenticação
│   │   ├── Campos:
│   │   │   ├── name, email, password
│   │   │   ├── email_verified_at
│   │   │   ├── created_at, updated_at
│   │   │   └── remember_token
│   │   └── Relacionamentos:
│   │       ├── → Orçamentos (user_id)
│   │       └── → Logs de Atividade (user_id)
│   └── 🛡️ Controle de Acesso
│       ├── Middleware de Autenticação
│       ├── Proteção de Rotas Admin
│       └── Validação de Permissões
│
└── 🔍 SISTEMA DE TESTES E MONITORAMENTO
    ├── 🧪 Teste de API
    │   ├── Dependências:
    │   │   ├── → OmieService
    │   │   ├── → Configurações API
    │   │   └── → Sistema de Logs
    │   ├── Funcionalidades:
    │   │   ├── teste_conexao (TestConnection)
    │   │   ├── listar_endpoints (getEndpoints)
    │   │   ├── executar_chamadas (testApi)
    │   │   └── visualizar_respostas (interface web)
    │   └── Interface:
    │       ├── seleção de endpoint
    │       ├── configuração de parâmetros
    │       ├── visualização de resultados
    │       └── logs de execução
    ├── 📊 Logs e Monitoramento
    │   ├── Dependências: → Laravel Log
    │   ├── Tipos de Log:
    │   │   ├── api_requests (requisições à API Omie)
    │   │   ├── user_actions (ações dos usuários)
    │   │   ├── system_errors (erros do sistema)
    │   │   └── performance_metrics (métricas de performance)
    │   └── Armazenamento:
    │       ├── storage/logs/laravel.log
    │       ├── rotação automática de logs
    │       └── níveis: debug, info, warning, error
    └── 🔧 Diagnóstico do Sistema
        ├── Comando: php artisan omie:diagnose
        ├── Verificações:
        │   ├── conectividade_internet
        │   ├── configurações_ssl
        │   ├── chaves_api_omie
        │   ├── teste_endpoints
        │   └── performance_rede
        └── Relatório:
            ├── status_geral (OK/WARNING/ERROR)
            ├── detalhes_problemas
            ├── sugestões_correção
            └── próximos_passos
```

## 🔗 Mapa de Relacionamentos Críticos

### 1. Fluxo de Criação de Orçamento

```
CRIAÇÃO DE ORÇAMENTO
│
├── 1️⃣ VALIDAÇÕES INICIAIS
│   ├── ✅ Usuário autenticado
│   ├── ✅ Configurações API Omie válidas
│   ├── ✅ Bases cadastradas
│   └── ✅ Centros de custo disponíveis
│
├── 2️⃣ SELEÇÃO DE DADOS EXTERNOS
│   ├── 👥 Cliente (via API Omie)
│   │   ├── Busca: ListarClientes
│   │   ├── Seleção: codigo_cliente_omie
│   │   └── Validação: cliente ativo
│   └── 🏭 Fornecedor (via API Omie)
│       ├── Busca: ListarFornecedores
│       ├── Seleção: codigo_fornecedor_omie
│       └── Validação: fornecedor ativo
│
├── 3️⃣ CONFIGURAÇÃO INTERNA
│   ├── 🏢 Base (tabela local)
│   │   ├── Seleção: base_id
│   │   └── Dados: nome, localização
│   ├── 💼 Centro de Custo (tabela local)
│   │   ├── Seleção: centro_custo_id
│   │   ├── Dependência: → marca_id
│   │   └── Dados: nome, código, marca
│   └── 📋 Grupo de Impostos (tabela local)
│       ├── Seleção: grupo_impostos_id
│       ├── Dependência: → impostos relacionados
│       └── Cálculo: soma dos percentuais
│
├── 4️⃣ ADIÇÃO DE ITENS
│   ├── 📦 Produtos (opcional - via API Omie)
│   │   ├── Busca: ListarProdutos
│   │   ├── Seleção: codigo_produto_omie
│   │   └── Dados: descrição, valor_unitario
│   └── ✏️ Itens Manuais
│       ├── Descrição personalizada
│       ├── Quantidade e valor
│       └── Cálculo automático do total
│
├── 5️⃣ DADOS DO PRESTADOR
│   ├── 💰 Configuração Financeira
│   │   ├── Percentual de lucro
│   │   ├── Percentual de impostos (do grupo selecionado)
│   │   └── Custo do fornecedor
│   └── 🧮 Cálculos Automáticos
│       ├── Valor dos impostos
│       ├── Valor do lucro
│       └── Valor final do prestador
│
├── 6️⃣ AUMENTO DE KM (opcional)
│   ├── 🚗 Dados de Combustível
│   │   ├── KM total/mês
│   │   ├── Total de combustível
│   │   └── Custo total combustível + HE
│   └── 🧮 Cálculos de KM Adicional
│       ├── Custo por KM
│       ├── Valor KM adicional
│       └── Custo total final
│
└── 7️⃣ FINALIZAÇÃO
    ├── 💾 Salvamento no Banco
    │   ├── Orçamento principal
    │   ├── Itens do orçamento
    │   ├── Dados do prestador
    │   └── Dados de aumento de KM
    ├── 📊 Cálculos Finais
    │   ├── Subtotal dos itens
    │   ├── Total de impostos
    │   ├── Valor final do orçamento
    │   └── Margem de lucro total
    └── 📄 Geração de PDF
        ├── Template personalizado
        ├── Dados formatados
        └── Arquivo para download
```

### 2. Cadeia de Dependências dos Seeders

```
ORDEM DE EXECUÇÃO DOS SEEDERS
│
1️⃣ AdminUserSeeder
   └── Cria usuário administrador inicial
   └── Dependências: nenhuma
   
2️⃣ BaseSeeder
   └── Cria bases do sistema
   └── Dependências: nenhuma
   
3️⃣ MarcaSeeder ⭐
   └── Cria marcas disponíveis
   └── Dependências: nenhuma
   
4️⃣ CentroCustoSeeder
   └── Cria centros de custo
   └── Dependências: → MarcaSeeder (marca_id)
   
5️⃣ GrupoImpostoSeeder
   └── Cria grupos de impostos
   └── Dependências: nenhuma
   
6️⃣ ImpostosSeeder
   └── Cria impostos individuais
   └── Dependências: → GrupoImpostoSeeder (grupo_id)
```

### 3. Fluxo de Integração com API Omie

```
INTEGRAÇÃO API OMIE
│
├── 🔑 AUTENTICAÇÃO
│   ├── Carregamento das Chaves
│   │   ├── Setting::get('omie_app_key')
│   │   ├── Setting::get('omie_app_secret')
│   │   └── Descriptografia automática
│   ├── Validação das Chaves
│   │   ├── Verificação se não estão vazias
│   │   ├── Formato válido
│   │   └── Teste de conectividade
│   └── Configuração da Requisição
│       ├── Headers HTTP
│       ├── Timeout (30 segundos)
│       └── User-Agent personalizado
│
├── 📡 REQUISIÇÃO
│   ├── Montagem do Payload
│   │   ├── call (método da API)
│   │   ├── app_key (chave de aplicação)
│   │   ├── app_secret (chave secreta)
│   │   └── param (parâmetros específicos)
│   ├── Envio HTTP POST
│   │   ├── URL: https://app.omie.com.br/api/v1/{endpoint}
│   │   ├── Content-Type: application/json
│   │   └── Timeout configurável
│   └── Tratamento de Erros
│       ├── ConnectionException (problemas de rede)
│       ├── RequestException (erros HTTP)
│       ├── TimeoutException (timeout)
│       └── GeneralException (outros erros)
│
├── 📥 PROCESSAMENTO DA RESPOSTA
│   ├── Validação HTTP Status
│   │   ├── 200: Sucesso
│   │   ├── 4xx: Erro do cliente
│   │   └── 5xx: Erro do servidor
│   ├── Parsing JSON
│   │   ├── Decodificação da resposta
│   │   ├── Validação da estrutura
│   │   └── Tratamento de erros de parsing
│   └── Extração de Dados
│       ├── Dados principais (data)
│       ├── Metadados (paginação)
│       ├── Mensagens de erro (faultstring)
│       └── Informações de debug
│
├── 📊 FORMATAÇÃO DOS DADOS
│   ├── Padronização de Campos
│   │   ├── Mapeamento de nomes
│   │   ├── Conversão de tipos
│   │   └── Valores padrão
│   ├── Filtros e Buscas
│   │   ├── Filtro por texto
│   │   ├── Filtro por ID
│   │   └── Ordenação
│   └── Paginação
│       ├── Controle de página atual
│       ├── Registros por página
│       └── Total de registros
│
└── 📝 LOGGING E AUDITORIA
    ├── Log de Requisições
    │   ├── URL e método
    │   ├── Parâmetros enviados
    │   ├── Tempo de resposta
    │   └── Status da resposta
    ├── Log de Erros
    │   ├── Tipo de erro
    │   ├── Mensagem detalhada
    │   ├── Stack trace
    │   └── Contexto da requisição
    └── Métricas de Performance
        ├── Tempo médio de resposta
        ├── Taxa de sucesso
        ├── Frequência de erros
        └── Uso de recursos
```

---

**🎯 Pontos Críticos de Dependência:**

1. **🔑 Configurações API Omie**: Base para toda integração
2. **🏷️ Marcas → Centros de Custo**: Relacionamento obrigatório
3. **📋 Grupos → Impostos**: Estrutura hierárquica fiscal
4. **👥 Clientes/Fornecedores Omie**: Dados externos essenciais
5. **🔐 Autenticação**: Pré-requisito para todas as operações
6. **📊 Seeders**: Ordem específica de execução
7. **💾 Banco de Dados**: Estrutura base para todo o sistema

**⚠️ Falhas Críticas que Quebram o Sistema:**

- Chaves API Omie inválidas ou não configuradas
- Falha na conectividade com API Omie
- Seeders executados fora de ordem
- Usuário não autenticado tentando acessar áreas protegidas
- Referências a registros inexistentes (marca_id, grupo_id, etc.)
- Configurações de banco de dados incorretas