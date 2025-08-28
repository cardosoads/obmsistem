# Exemplos de Estrutura JSON - API Omie

## 📋 Exemplos Práticos de Respostas da API

### 1. 👥 ListarClientes - Resposta Completa

```json
{
  "success": true,
  "data": {
    "pagina": 1,
    "total_de_paginas": 5,
    "registros_por_pagina": 20,
    "total_de_registros": 87,
    "clientes_cadastro": [
      {
        "codigo_cliente_omie": 12345,
        "codigo_cliente_integracao": "CLI001",
        "razao_social": "Empresa Exemplo Ltda",
        "nome_fantasia": "Exemplo Corp",
        "cnpj_cpf": "12.345.678/0001-90",
        "inscricao_estadual": "123456789",
        "inscricao_municipal": "987654321",
        "endereco": "Rua das Flores, 123",
        "endereco_numero": "123",
        "complemento": "Sala 456",
        "bairro": "Centro",
        "cidade": "São Paulo",
        "estado": "SP",
        "cep": "01234-567",
        "telefone1_ddd": "11",
        "telefone1_numero": "99999-8888",
        "telefone2_ddd": "11",
        "telefone2_numero": "88888-7777",
        "email": "contato@exemplo.com.br",
        "website": "www.exemplo.com.br",
        "inativo": "N",
        "data_inclusao": "15/01/2024",
        "data_alteracao": "20/01/2024",
        "observacoes": "Cliente preferencial"
      }
    ]
  },
  "status_code": 200,
  "duration_ms": 1250.75
}
```

### 2. 🏭 ListarFornecedores - Resposta Completa

```json
{
  "success": true,
  "data": {
    "pagina": 1,
    "total_de_paginas": 3,
    "registros_por_pagina": 20,
    "total_de_registros": 45,
    "fornecedor_cadastro": [
      {
        "codigo_fornecedor_omie": 67890,
        "codigo_fornecedor_integracao": "FOR001",
        "razao_social": "Fornecedor ABC Ltda",
        "nome_fantasia": "ABC Suprimentos",
        "cnpj_cpf": "98.765.432/0001-10",
        "inscricao_estadual": "987654321",
        "inscricao_municipal": "123456789",
        "endereco": "Av. Industrial, 456",
        "endereco_numero": "456",
        "complemento": "Galpão 2",
        "bairro": "Distrito Industrial",
        "cidade": "Guarulhos",
        "estado": "SP",
        "cep": "07123-456",
        "telefone1_ddd": "11",
        "telefone1_numero": "77777-6666",
        "email": "vendas@abc.com.br",
        "inativo": "N",
        "data_inclusao": "10/01/2024",
        "data_alteracao": "18/01/2024"
      }
    ]
  },
  "status_code": 200,
  "duration_ms": 980.25
}
```

### 3. 📦 ListarProdutos - Resposta Completa

```json
{
  "success": true,
  "data": {
    "pagina": 1,
    "total_de_paginas": 8,
    "registros_por_pagina": 50,
    "total_de_registros": 387,
    "produto_servico_cadastro": [
      {
        "codigo_produto_omie": 54321,
        "codigo_produto_integracao": "PROD001",
        "codigo_produto": "ABC-123",
        "descricao": "Parafuso Phillips M6x20",
        "descricao_detalhada": "Parafuso Phillips cabeça panela, aço inox, M6x20mm",
        "unidade": "UN",
        "ncm": "73181500",
        "marca": "FastTech",
        "modelo": "FT-M6-20",
        "familia": "Fixadores",
        "subfamilia": "Parafusos",
        "valor_unitario": 2.50,
        "custo_unitario": 1.80,
        "margem_lucro": 38.89,
        "preco_minimo": 2.00,
        "quantidade_estoque": 1500,
        "estoque_minimo": 100,
        "estoque_maximo": 2000,
        "controlar_estoque": "S",
        "inativo": "N",
        "data_inclusao": "05/01/2024",
        "data_alteracao": "22/01/2024",
        "observacoes": "Produto em promoção"
      }
    ]
  },
  "status_code": 200,
  "duration_ms": 1450.30
}
```

### 4. 🏢 ListarEmpresas - Resposta Completa

```json
{
  "success": true,
  "data": {
    "empresas_cadastro": [
      {
        "codigo_empresa": 1,
        "razao_social": "Minha Empresa Principal Ltda",
        "nome_fantasia": "Empresa Principal",
        "cnpj": "11.222.333/0001-44",
        "inscricao_estadual": "111222333",
        "endereco": "Rua Principal, 100",
        "cidade": "São Paulo",
        "estado": "SP",
        "cep": "01000-000",
        "telefone": "(11) 1111-2222",
        "email": "contato@minhaempresa.com.br",
        "ativa": "S",
        "data_cadastro": "01/01/2024"
      }
    ]
  },
  "status_code": 200,
  "duration_ms": 650.15
}
```

### 5. 🔧 TestConnection - Resposta de Sucesso

```json
{
  "success": true,
  "message": "Conexão estabelecida com sucesso!",
  "data": {
    "pagina": 1,
    "total_de_paginas": 1,
    "registros_por_pagina": 1,
    "total_de_registros": 1,
    "clientes_cadastro": [
      {
        "codigo_cliente_omie": 1,
        "razao_social": "Cliente Teste",
        "nome_fantasia": "Teste",
        "cnpj_cpf": "00.000.000/0001-00"
      }
    ]
  },
  "debug_info": {
    "status_code": 200,
    "duration_ms": 850.45,
    "api_url": "https://app.omie.com.br/api/v1/"
  }
}
```

### 6. ❌ Resposta de Erro - Chave Inválida

```json
{
  "success": false,
  "message": "Falha na conexão: A chave de acesso não está preenchida ou não é válida",
  "error": {
    "faultCode": "SOAP-ENV:Client-5030",
    "faultString": "A chave de acesso não está preenchida ou não é válida"
  },
  "debug_info": {
    "status_code": 500,
    "api_url": "https://app.omie.com.br/api/v1/",
    "exception_type": "RequestException"
  }
}
```

### 7. ❌ Resposta de Erro - Timeout de Conexão

```json
{
  "success": false,
  "message": "Erro de conexão com a API Omie: Connection timeout",
  "error": "Connection timeout after 30 seconds",
  "error_type": "connection",
  "debug_info": {
    "duration_ms": 30000,
    "exception_type": "ConnectionException"
  }
}
```

## 🔄 Estrutura de Requisição (Payload)

### Exemplo de Payload para ListarClientes

```json
{
  "call": "ListarClientes",
  "app_key": "sua_app_key_aqui",
  "app_secret": "sua_app_secret_aqui",
  "param": [
    {
      "pagina": 1,
      "registros_por_pagina": 20,
      "apenas_importado_api": "N",
      "ordenar_por": "CODIGO",
      "ordem_decrescente": "N",
      "filtrar_por_data_de": "01/01/2024",
      "filtrar_por_data_ate": "31/01/2024"
    }
  ]
}
```

### Exemplo de Payload para ConsultarCliente

```json
{
  "call": "ConsultarCliente",
  "app_key": "sua_app_key_aqui",
  "app_secret": "sua_app_secret_aqui",
  "param": [
    {
      "codigo_cliente_omie": 12345
    }
  ]
}
```

## 📊 Estrutura de Dados Formatados no Sistema

### Cliente Formatado (usado internamente)

```json
{
  "id": 12345,
  "omie_id": 12345,
  "nome": "Empresa Exemplo Ltda",
  "nome_fantasia": "Exemplo Corp",
  "razao_social": "Empresa Exemplo Ltda",
  "documento": "12.345.678/0001-90",
  "email": "contato@exemplo.com.br",
  "telefone": "1199999-8888",
  "cidade": "São Paulo",
  "estado": "SP",
  "ativo": true
}
```

### Fornecedor Formatado (usado internamente)

```json
{
  "id": 67890,
  "omie_id": 67890,
  "nome": "Fornecedor ABC Ltda",
  "razao_social": "Fornecedor ABC Ltda",
  "nome_fantasia": "ABC Suprimentos",
  "cnpj_cpf": "98.765.432/0001-10",
  "email": "vendas@abc.com.br",
  "telefone1_ddd": "11",
  "telefone1_numero": "77777-6666"
}
```

## 🎯 Campos Obrigatórios vs Opcionais

### ✅ Campos Obrigatórios

**Cliente/Fornecedor:**
- `codigo_*_omie` (gerado automaticamente)
- `razao_social` OU `nome_fantasia`
- `cnpj_cpf` (para pessoa jurídica)

**Produto:**
- `codigo_produto_omie` (gerado automaticamente)
- `descricao`
- `unidade`
- `valor_unitario`

### ⚪ Campos Opcionais

**Cliente/Fornecedor:**
- `codigo_*_integracao`
- `inscricao_estadual`
- `endereco`, `cidade`, `estado`, `cep`
- `telefone1_ddd`, `telefone1_numero`
- `email`
- `observacoes`

**Produto:**
- `codigo_produto_integracao`
- `codigo_produto`
- `descricao_detalhada`
- `ncm`
- `marca`, `modelo`
- `custo_unitario`
- `quantidade_estoque`

## 🔍 Filtros Disponíveis

### Filtros para ListarClientes

```json
{
  "pagina": 1,
  "registros_por_pagina": 50,
  "apenas_importado_api": "N",
  "ordenar_por": "CODIGO|RAZAO_SOCIAL|NOME_FANTASIA",
  "ordem_decrescente": "S|N",
  "filtrar_apenas_clientes_omie": "S|N",
  "filtrar_por_data_de": "dd/mm/aaaa",
  "filtrar_por_data_ate": "dd/mm/aaaa",
  "filtrar_por_codigo_cliente_omie": 12345,
  "filtrar_por_cnpj_cpf": "12.345.678/0001-90",
  "filtrar_por_razao_social": "Empresa",
  "filtrar_por_nome_fantasia": "Fantasia",
  "filtrar_por_cidade": "São Paulo",
  "filtrar_por_estado": "SP",
  "filtrar_por_inativo": "S|N"
}
```

---

**📝 Notas Importantes:**

1. **Formato de Data**: Sempre no formato brasileiro `dd/mm/aaaa`
2. **Valores Booleanos**: Representados como `"S"` (Sim) ou `"N"` (Não)
3. **Números**: IDs são inteiros, valores monetários são decimais
4. **Encoding**: Todas as strings em UTF-8
5. **Paginação**: Máximo de 500 registros por página
6. **Rate Limit**: Máximo de 300 requisições por minuto
7. **Timeout**: Requisições têm timeout de 30 segundos
8. **Logs**: Todas as requisições são logadas para auditoria