# 🚨 CORREÇÃO URGENTE - Coluna id_protocolo Ausente no Forge

## 📋 Problema Identificado

**Erro:** `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'id_protocolo' in 'field list'`

**Causa:** A migração `2025_01_27_000002_add_id_protocolo_to_orcamentos_table` não foi executada com sucesso em produção.

## 🔧 Soluções Disponíveis

### Opção 1: Executar Migrações Pendentes (Recomendado)

```bash
# No terminal do Forge, execute:
php artisan migrate:status
php artisan migrate --force
```

### Opção 2: Script de Correção Manual

Se a Opção 1 não funcionar, execute o script de correção:

```bash
# No terminal do Forge:
php fix_id_protocolo_production.php
```

### Opção 3: Comando SQL Direto

Como último recurso, execute diretamente no MySQL:

```sql
USE obm;
ALTER TABLE orcamentos 
ADD COLUMN id_protocolo VARCHAR(255) NULL 
COMMENT 'ID de Protocolo digitado pelo usuário' 
AFTER centro_custo_id;

-- Marcar migração como executada
INSERT INTO migrations (migration, batch) 
VALUES ('2025_01_27_000002_add_id_protocolo_to_orcamentos_table', 
        (SELECT MAX(batch) + 1 FROM (SELECT batch FROM migrations) as temp));
```

## 🔍 Verificação

Após executar qualquer uma das opções, verifique:

```bash
# Verificar se a coluna existe:
php check_id_protocolo_column.php

# Testar o formulário de orçamento
```

## 📊 Status das Migrações

### Migrações Críticas que Devem Estar Executadas:

1. ✅ `2025_08_22_081248_create_orcamentos_table` - Cria tabela orcamentos
2. ❌ `2025_01_27_000002_add_id_protocolo_to_orcamentos_table` - Adiciona coluna id_protocolo
3. ❓ `2025_08_25_000001_add_percentual_fields_to_orcamentos_table` - Adiciona campos percentuais

### Verificações Implementadas:

- ✅ Verificação de existência de tabela antes de modificar
- ✅ Verificação de existência de coluna antes de adicionar
- ✅ Tratamento gracioso de dependências ausentes
- ✅ Rollback seguro mesmo com dependências ausentes

## 🚀 Próximos Passos

1. **Executar uma das soluções acima**
2. **Testar o formulário de orçamento**
3. **Verificar logs de erro**
4. **Confirmar que não há mais erros de coluna ausente**

## 📞 Suporte

Se o problema persistir:

1. Verificar logs do Laravel: `tail -f storage/logs/laravel.log`
2. Verificar logs do MySQL
3. Executar `php artisan migrate:status` para ver status completo

---

**⚠️ IMPORTANTE:** Faça backup do banco antes de executar qualquer correção!