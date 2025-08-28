# Estrutura Hierárquica dos Dados da API Omie

## 📊 Visão Geral da Arquitetura de Dados

```
API OMIE
├── 🏢 EMPRESAS
│   └── ListarEmpresas
│       └── Dados Retornados:
│           ├── codigo_empresa
│           ├── razao_social
│           ├── nome_fantasia
│           └── configurações_empresa
│
├── 👥 CLIENTES
│   ├── ListarClientes
│   │   ├── Parâmetros de Entrada:
│   │   │   ├── pagina (opcional)
│   │   │   ├── registros_por_pagina (opcional)
│   │   │   └── filtros_busca
│   │   └── Estrutura de Resposta:
│   │       ├── 📈 Metadados de Paginação
│   │       │   ├── total_de_registros
│   │       │   ├── total_de_paginas
│   │       │   ├── registros_por_pagina
│   │       │   └── pagina_atual
│   │       └── 📋 Array de Clientes
│   │           └── Para cada Cliente:
│   │               ├── 🆔 Identificadores
│   │               │   ├── codigo_cliente_omie (ID principal)
│   │               │   └── codigo_cliente_integracao
│   │               ├── 📝 Dados Básicos
│   │               │   ├── razao_social
│   │               │   ├── nome_fantasia
│   │               │   └── cnpj_cpf
│   │               ├── 📞 Contato
│   │               │   ├── email
│   │               │   ├── telefone1_ddd
│   │               │   ├── telefone1_numero
│   │               │   ├── telefone2_ddd
│   │               │   └── telefone2_numero
│   │               ├── 📍 Endereço
│   │               │   ├── endereco
│   │               │   ├── cidade
│   │               │   ├── estado
│   │               │   ├── cep
│   │               │   └── bairro
│   │               └── ⚙️ Status
│   │                   ├── inativo (S/N)
│   │                   └── data_inclusao
│   └── ConsultarCliente
│       ├── Parâmetros de Entrada:
│       │   └── codigo_cliente_omie (obrigatório)
│       └── Retorna: Dados completos do cliente específico
│
├── 🏭 FORNECEDORES
│   └── ListarFornecedores
│       ├── Parâmetros de Entrada:
│       │   ├── pagina (opcional)
│       │   ├── registros_por_pagina (opcional)
│       │   └── filtros_busca
│       └── Estrutura de Resposta:
│           ├── 📈 Metadados de Paginação
│           │   ├── total_de_registros
│           │   ├── total_de_paginas
│           │   ├── registros_por_pagina
│           │   └── pagina_atual
│           └── 📋 Array de Fornecedores
│               └── Para cada Fornecedor:
│                   ├── 🆔 Identificadores
│                   │   ├── codigo_fornecedor_omie (ID principal)
│                   │   └── codigo_fornecedor_integracao
│                   ├── 📝 Dados Básicos
│                   │   ├── razao_social
│                   │   ├── nome_fantasia
│                   │   └── cnpj_cpf
│                   ├── 📞 Contato
│                   │   ├── email
│                   │   ├── telefone1_ddd
│                   │   ├── telefone1_numero
│                   │   ├── telefone2_ddd
│                   │   └── telefone2_numero
│                   ├── 📍 Endereço
│                   │   ├── endereco
│                   │   ├── cidade
│                   │   ├── estado
│                   │   ├── cep
│                   │   └── bairro
│                   └── ⚙️ Status
│                       ├── inativo (S/N)
│                       └── data_inclusao
│
├── 📦 PRODUTOS
│   └── ListarProdutos
│       ├── Parâmetros de Entrada:
│       │   ├── pagina (opcional)
│       │   ├── registros_por_pagina (opcional)
│       │   └── filtros_busca
│       └── Estrutura de Resposta:
│           ├── 📈 Metadados de Paginação
│           │   ├── total_de_registros
│           │   ├── total_de_paginas
│           │   ├── registros_por_pagina
│           │   └── pagina_atual
│           └── 📋 Array de Produtos
│               └── Para cada Produto:
│                   ├── 🆔 Identificadores
│                   │   ├── codigo_produto_omie (ID principal)
│                   │   └── codigo_produto_integracao
│                   ├── 📝 Dados Básicos
│                   │   ├── descricao
│                   │   ├── codigo_produto
│                   │   ├── unidade
│                   │   └── ncm
│                   ├── 💰 Valores
│                   │   ├── valor_unitario
│                   │   ├── custo_unitario
│                   │   └── margem_lucro
│                   ├── 📊 Estoque
│                   │   ├── quantidade_estoque
│                   │   ├── estoque_minimo
│                   │   └── controlar_estoque
│                   └── ⚙️ Status
│                       ├── inativo (S/N)
│                       └── data_inclusao
│
└── 🔧 UTILITÁRIOS
    └── TestConnection
        ├── Parâmetros de Entrada: (nenhum)
        └── Estrutura de Resposta:
            ├── ✅ Sucesso
            │   ├── success: true
            │   ├── message: "Conexão estabelecida com sucesso!"
            │   ├── data: (dados de teste da API)
            │   └── debug_info:
            │       ├── status_code
            │       ├── duration_ms
            │       └── api_url
            └── ❌ Erro
                ├── success: false
                ├── message: (descrição do erro)
                ├── error: (detalhes técnicos)
                └── debug_info:
                    ├── status_code
                    ├── exception_type
                    ├── file
                    └── line
```

## 🔄 Fluxo de Processamento de Dados

### 1. Requisição à API
```
Cliente Sistema → OmieService → API Omie
├── Validação de Chaves (app_key, app_secret)
├── Montagem do Payload JSON
├── Envio da Requisição HTTP POST
└── Processamento da Resposta
```

### 2. Estrutura Padrão de Resposta
```
Resposta da API Omie
├── 📊 Metadados
│   ├── status_code (HTTP)
│   ├── duration_ms (tempo de resposta)
│   └── response_size (tamanho da resposta)
├── 📋 Dados Principais
│   ├── success (boolean)
│   ├── data (array/object com os dados)
│   └── message (mensagem de status)
└── 🐛 Informações de Debug
    ├── error (em caso de erro)
    ├── debug_info (informações técnicas)
    └── logs (registros do sistema)
```

### 3. Tratamento de Erros
```
Tipos de Erro
├── 🔐 Autenticação
│   ├── Chave não configurada
│   ├── Chave inválida
│   └── Chave expirada
├── 🌐 Conectividade
│   ├── Timeout de conexão
│   ├── Erro de DNS
│   └── Certificado SSL inválido
├── 📡 API
│   ├── Endpoint não encontrado
│   ├── Parâmetros inválidos
│   └── Limite de requisições excedido
└── 🔧 Sistema
    ├── Exceções não tratadas
    ├── Erro de parsing JSON
    └── Falha na validação de dados
```

## 📈 Estrutura de Paginação

```
Paginação Omie
├── 📊 Controle de Página
│   ├── pagina (número da página atual)
│   ├── registros_por_pagina (itens por página)
│   └── total_de_paginas (total de páginas)
├── 📋 Informações de Registros
│   ├── total_de_registros (total de itens)
│   ├── registros_na_pagina (itens na página atual)
│   └── primeira_pagina / ultima_pagina (flags)
└── 🔄 Navegação
    ├── pagina_anterior (número da página anterior)
    ├── proxima_pagina (número da próxima página)
    └── has_more_pages (boolean)
```

## 🎯 Campos Principais por Entidade

### 👥 Cliente/Fornecedor
```
Entidade Pessoa
├── 🆔 Identificação
│   ├── codigo_[cliente|fornecedor]_omie (PK)
│   ├── codigo_[cliente|fornecedor]_integracao
│   └── cnpj_cpf (documento)
├── 📝 Dados Corporativos
│   ├── razao_social (nome oficial)
│   ├── nome_fantasia (nome comercial)
│   ├── inscricao_estadual
│   └── inscricao_municipal
├── 📞 Contato
│   ├── email (principal)
│   ├── telefone1_ddd + telefone1_numero
│   ├── telefone2_ddd + telefone2_numero
│   └── website
├── 📍 Localização
│   ├── endereco (logradouro)
│   ├── endereco_numero
│   ├── complemento
│   ├── bairro
│   ├── cidade
│   ├── estado (UF)
│   └── cep
└── ⚙️ Configurações
    ├── inativo (S/N)
    ├── data_inclusao
    ├── data_alteracao
    └── observacoes
```

### 📦 Produto
```
Entidade Produto
├── 🆔 Identificação
│   ├── codigo_produto_omie (PK)
│   ├── codigo_produto_integracao
│   └── codigo_produto (código interno)
├── 📝 Descrição
│   ├── descricao (nome do produto)
│   ├── descricao_detalhada
│   ├── marca
│   └── modelo
├── 📊 Classificação
│   ├── ncm (código fiscal)
│   ├── unidade (UN, KG, M, etc.)
│   ├── familia (categoria)
│   └── subfamilia (subcategoria)
├── 💰 Financeiro
│   ├── valor_unitario (preço de venda)
│   ├── custo_unitario (custo de aquisição)
│   ├── margem_lucro (percentual)
│   └── preco_minimo
├── 📦 Estoque
│   ├── quantidade_estoque (atual)
│   ├── estoque_minimo (alerta)
│   ├── estoque_maximo (limite)
│   └── controlar_estoque (S/N)
└── ⚙️ Configurações
    ├── inativo (S/N)
    ├── data_inclusao
    ├── data_alteracao
    └── observacoes
```

## 🔗 Relacionamentos e Dependências

```
Relacionamentos no Sistema
├── 🏢 Empresa
│   ├── → possui múltiplos Clientes
│   ├── → possui múltiplos Fornecedores
│   ├── → possui múltiplos Produtos
│   └── → define configurações globais
├── 👥 Cliente
│   ├── → pode ter múltiplos Orçamentos
│   ├── → pode ter múltiplos Pedidos
│   └── → vinculado a uma Empresa
├── 🏭 Fornecedor
│   ├── → pode fornecer múltiplos Produtos
│   ├── → pode ter múltiplas Cotações
│   └── → vinculado a uma Empresa
└── 📦 Produto
    ├── → pode ter múltiplos Fornecedores
    ├── → pode estar em múltiplos Orçamentos
    └── → vinculado a uma Empresa
```

---

**📝 Observações Importantes:**

1. **Identificadores Únicos**: Cada entidade possui um ID único (`codigo_*_omie`) que é a chave primária no sistema Omie
2. **Paginação**: Todas as listagens suportam paginação para otimizar performance
3. **Filtros**: Suporte a filtros por nome, documento, status, etc.
4. **Status**: Campo `inativo` controla se o registro está ativo (N) ou inativo (S)
5. **Auditoria**: Campos de data de inclusão e alteração para rastreabilidade
6. **Flexibilidade**: Campos opcionais permitem diferentes níveis de detalhamento
7. **Integração**: Códigos de integração permitem sincronização com sistemas externos

**🔧 Configuração Necessária:**
- `app_key`: Chave de aplicação fornecida pela Omie
- `app_secret`: Chave secreta fornecida pela Omie
- Ambas devem estar configuradas no painel administrativo do sistema