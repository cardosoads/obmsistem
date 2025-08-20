# Esquema de Banco de Dados - Sistema de Orçamentos de Transporte

## 1. Tabelas de Autenticação e Permissões

### users
```sql
id (PK, AUTO_INCREMENT)
name (VARCHAR 255, NOT NULL)
email (VARCHAR 255, UNIQUE, NOT NULL)
email_verified_at (TIMESTAMP, NULL)
password (VARCHAR 255, NOT NULL)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### roles
```sql
id (PK, AUTO_INCREMENT)
name (VARCHAR 100, UNIQUE, NOT NULL) -- admin, user
display_name (VARCHAR 255, NOT NULL)
description (TEXT, NULL)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### permissions
```sql
id (PK, AUTO_INCREMENT)
name (VARCHAR 100, UNIQUE, NOT NULL) -- edit_budget, delete_budget, etc
display_name (VARCHAR 255, NOT NULL)
description (TEXT, NULL)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### role_permissions
```sql
id (PK, AUTO_INCREMENT)
role_id (FK -> roles.id)
permission_id (FK -> permissions.id)
created_at (TIMESTAMP)
```

### user_roles
```sql
id (PK, AUTO_INCREMENT)
user_id (FK -> users.id)
role_id (FK -> roles.id)
created_at (TIMESTAMP)
```

## 2. Tabelas de Configuração Base

### bases
```sql
id (PK, AUTO_INCREMENT)
name (VARCHAR 255, NOT NULL) -- Nome da cidade
uf (VARCHAR 2, NOT NULL) -- Estado
regional (VARCHAR 100, NOT NULL) -- Região
sigla (VARCHAR 3, NOT NULL) -- 3 caracteres, maiúsculo
supervisor (VARCHAR 255, NOT NULL)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### marcas
```sql
id (PK, AUTO_INCREMENT)
name (VARCHAR 100, NOT NULL) -- Maiúsculo, sem acentuação
mercado (VARCHAR 100, NOT NULL) -- Maiúsculo, sem acentuação
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### clientes (Integração OMIE)
```sql
id (PK, AUTO_INCREMENT)
omie_id (VARCHAR 50, UNIQUE, NULL) -- ID do cliente no OMIE
name (VARCHAR 255, NOT NULL)
document (VARCHAR 20, NULL) -- CPF/CNPJ
email (VARCHAR 255, NULL)
phone (VARCHAR 20, NULL)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### fornecedores (Integração OMIE)
```sql
id (PK, AUTO_INCREMENT)
omie_id (VARCHAR 50, UNIQUE, NULL) -- ID do fornecedor no OMIE
name (VARCHAR 255, NOT NULL)
document (VARCHAR 20, NULL) -- CPF/CNPJ
email (VARCHAR 255, NULL)
phone (VARCHAR 20, NULL)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

## 3. Tabela Centro de Custo

### centros_custo
```sql
id (PK, AUTO_INCREMENT)
cc (VARCHAR 50, NOT NULL) -- Campo digitável
cliente_id (FK -> clientes.id)
base_id (FK -> bases.id)
marca_id (FK -> marcas.id)
mercado (VARCHAR 100, NULL)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

## 4. Tabela de Impostos

### impostos
```sql
id (PK, AUTO_INCREMENT)
ano (YEAR, NOT NULL)
iss (DECIMAL 5,2, DEFAULT 5.00)
pis (DECIMAL 5,2, DEFAULT 0.65)
cofins (DECIMAL 5,2, DEFAULT 3.00)
csll (DECIMAL 5,2, DEFAULT 1.44)
irpj (DECIMAL 5,2, DEFAULT 2.40)
ad_irpj (DECIMAL 5,2, DEFAULT 0.80)
desoneracacao (DECIMAL 5,2, DEFAULT 0.00) -- Varia por ano
total_imposto (DECIMAL 5,2, NOT NULL) -- Calculado automaticamente
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

## 5. Tabela Principal de Orçamentos

### orcamentos
```sql
id (PK, AUTO_INCREMENT)
numero_orcamento (VARCHAR 20, UNIQUE, NOT NULL) -- Gerado automaticamente
data_solicitacao (DATE, NOT NULL)
cc_id (FK -> centros_custo.id)
evento (ENUM('AUMENTO_KM', 'BASE', 'INCLUSAO'), NOT NULL)
nome_rota (VARCHAR 255, NOT NULL)
id_logcare (VARCHAR 50, NULL)
cliente_dasa (VARCHAR 255, NULL)
horario (TIME, NULL)
frequencia_atendimento (VARCHAR 255, NULL)
tipo_orcamento (ENUM('PRESTADOR', 'AUMENTO_KM', 'PROPRIO_NOVA_ROTA'), NOT NULL)
status (ENUM('AGUARDANDO', 'APROVADO', 'REPROVADO'), DEFAULT 'AGUARDANDO')
data_aprovacao (DATE, NULL)
data_inicio (DATE, NULL)
data_exclusao (DATE, NULL)
data_envio (DATE, NULL)
valor_total (DECIMAL 10,2, NOT NULL)
user_id (FK -> users.id) -- Usuário que criou
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

## 6. Tabela de Detalhes por Tipo de Orçamento

### orcamento_prestador
```sql
id (PK, AUTO_INCREMENT)
orcamento_id (FK -> orcamentos.id)
fornecedor_id (FK -> fornecedores.id)
valor_referencia (DECIMAL 10,2, NOT NULL)
qtd_dias (INTEGER, NOT NULL)
custo_fornecedor (DECIMAL 10,2, NOT NULL)
lucro_percentual (DECIMAL 5,2, NOT NULL)
valor_lucro (DECIMAL 10,2, NOT NULL)
impostos_percentual (DECIMAL 5,2, NOT NULL)
valor_impostos (DECIMAL 10,2, NOT NULL)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### orcamento_aumento_km
```sql
id (PK, AUTO_INCREMENT)
orcamento_id (FK -> orcamentos.id)
km_dia (INTEGER, NOT NULL)
qtd_dias (INTEGER, NOT NULL)
km_total_mes (INTEGER, NOT NULL)
combustivel_km_litro (DECIMAL 8,2, NOT NULL)
total_combustivel (DECIMAL 10,2, NOT NULL)
valor_combustivel (DECIMAL 10,2, NOT NULL)
hora_extra (DECIMAL 10,2, DEFAULT 0.00)
custo_total_combustivel_he (DECIMAL 10,2, NOT NULL)
lucro_percentual (DECIMAL 5,2, NOT NULL)
valor_lucro (DECIMAL 10,2, NOT NULL)
impostos_percentual (DECIMAL 5,2, NOT NULL)
valor_impostos (DECIMAL 10,2, NOT NULL)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### orcamento_proprio_nova_rota
```sql
id (PK, AUTO_INCREMENT)
orcamento_id (FK -> orcamentos.id)
-- Campos específicos para fórmula própria/nova rota
-- (A ser definido conforme fórmulas específicas)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

## 7. Tabela de Histórico de Status

### orcamento_historico
```sql
id (PK, AUTO_INCREMENT)
orcamento_id (FK -> orcamentos.id)
status_anterior (VARCHAR 50, NULL)
status_novo (VARCHAR 50, NOT NULL)
observacoes (TEXT, NULL)
user_id (FK -> users.id) -- Usuário que fez a alteração
created_at (TIMESTAMP)
```

## 8. Índices e Relacionamentos

### Índices importantes:
- `users.email` (UNIQUE)
- `orcamentos.numero_orcamento` (UNIQUE)
- `orcamentos.status`
- `orcamentos.data_solicitacao`
- `centros_custo.cc`

### Relacionamentos (Foreign Keys):
- Todas as tabelas com sufixo `_id` referenciam a tabela correspondente
- Relacionamentos com `ON DELETE RESTRICT` para manter integridade
- Relacionamentos com `ON UPDATE CASCADE` para atualizações em cascata

## 9. Observações de Implementação

1. **Geração automática do número do orçamento**: Implementar através de trigger ou método no model
2. **Integração OMIE**: Campos `omie_id` permitem sincronização com API externa
3. **Cálculos automáticos**: Implementar observers/mutators para cálculos de valores
4. **Auditoria**: Tabela `orcamento_historico` mantém rastro de todas as alterações
5. **Permissões**: Sistema flexível permite criação de novos roles e permissions
6. **Impostos**: Tabela permite configuração anual dos percentuais de impostos