# Guia de Solução de Problemas - API Omie

## Problema Identificado

A aplicação funciona corretamente no ambiente local, mas apresenta falhas de conexão com a API Omie quando executada no servidor de produção.

## Comando de Diagnóstico

Foi criado um comando Artisan para diagnosticar problemas de conectividade com a API Omie:

```bash
php artisan omie:diagnose
```

### O que o comando verifica:

1. **Configurações do Banco de Dados**
   - Verifica se as chaves `omie_app_key` e `omie_app_secret` estão configuradas
   - Mostra o status das configurações

2. **Conectividade Básica**
   - Testa conexão TCP com o servidor da API Omie
   - Verifica se a porta 443 (HTTPS) está acessível

3. **Configurações SSL/TLS**
   - Verifica se o OpenSSL está habilitado
   - Mostra a versão do OpenSSL
   - Lista certificados CA disponíveis

4. **Teste da API Omie**
   - Realiza um teste real com a API (se as chaves estiverem configuradas)
   - Mostra detalhes da resposta ou erro

5. **Informações do Ambiente**
   - Versão do PHP
   - Versão do cURL
   - Configurações de rede relevantes

## Possíveis Causas e Soluções

### 1. Chaves da API não configuradas em produção

**Sintoma:** Erro "Chaves da API não configuradas"

**Solução:**
1. Acesse o painel administrativo da aplicação
2. Vá para Configurações → Integrações → API Omie
3. Configure as chaves `App Key` e `App Secret`
4. Teste a conexão

### 2. Problemas de firewall/conectividade

**Sintoma:** Timeout ou "Connection refused"

**Verificações:**
- O servidor tem acesso à internet?
- O firewall está bloqueando conexões HTTPS de saída?
- Há proxy ou configurações de rede especiais?

**Soluções:**
- Liberar acesso HTTPS (porta 443) para `app.omie.com.br`
- Configurar proxy se necessário
- Verificar com o provedor de hospedagem

### 3. Problemas de SSL/TLS

**Sintoma:** Erros relacionados a certificados SSL

**Verificações:**
- OpenSSL está habilitado no PHP?
- Certificados CA estão atualizados?
- Versão do OpenSSL é compatível?

**Soluções:**
- Atualizar certificados CA
- Verificar configurações SSL do servidor
- Atualizar OpenSSL se necessário

### 4. Configurações de timeout

**Sintoma:** Requisições muito lentas ou timeout

**Soluções:**
- Verificar latência de rede
- Ajustar timeout nas configurações do servidor
- Otimizar conexão de rede

## Logs Detalhados

O sistema agora gera logs detalhados para todas as operações da API Omie:

### Localização dos logs:
```
storage/logs/laravel.log
```

### Tipos de logs gerados:

1. **Início de requisição:**
   ```
   [INFO] Omie API Request iniciada
   ```

2. **Teste de conectividade:**
   ```
   [INFO] Conectividade básica com API Omie OK
   [ERROR] Falha na conectividade básica com API Omie
   ```

3. **Resposta bem-sucedida:**
   ```
   [INFO] Omie API Response Success
   ```

4. **Erros de conexão:**
   ```
   [ERROR] Omie API Connection Exception
   ```

5. **Erros de requisição:**
   ```
   [ERROR] Omie API Request Exception
   ```

## Passos para Resolução

### No Servidor de Produção:

1. **Execute o diagnóstico:**
   ```bash
   php artisan omie:diagnose
   ```

2. **Configure as chaves se necessário:**
   - Acesse o painel administrativo
   - Configure as chaves da API Omie

3. **Verifique os logs:**
   ```bash
   tail -f storage/logs/laravel.log | grep -i omie
   ```

4. **Teste a funcionalidade:**
   - Tente usar uma função que depende da API Omie
   - Monitore os logs em tempo real

### Se o problema persistir:

1. **Colete informações detalhadas:**
   - Resultado completo do `omie:diagnose`
   - Logs de erro específicos
   - Configurações de rede do servidor

2. **Verifique com o provedor:**
   - Confirme se há restrições de firewall
   - Verifique configurações de proxy
   - Confirme acesso HTTPS de saída

3. **Teste manual:**
   ```bash
   curl -v https://app.omie.com.br/api/v1/geral/clientes/
   ```

## Melhorias Implementadas

### No OmieService:

1. **Logs detalhados** para todas as operações
2. **Teste de conectividade** antes de cada requisição
3. **Tratamento específico** para diferentes tipos de erro
4. **Medição de tempo** de resposta
5. **Informações de debug** incluídas nas respostas

### Comando de Diagnóstico:

1. **Verificação completa** do ambiente
2. **Testes de conectividade** automatizados
3. **Relatório detalhado** de configurações
4. **Sugestões de solução** baseadas nos resultados

## Contato para Suporte

Se o problema persistir após seguir este guia, colete as seguintes informações:

1. Resultado completo do comando `php artisan omie:diagnose`
2. Logs de erro dos últimos 30 minutos
3. Informações do servidor (provedor, configurações de rede)
4. Descrição detalhada do comportamento observado

Essas informações ajudarão a identificar rapidamente a causa raiz do problema.