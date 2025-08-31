// Debug script para testar a busca de clientes
console.log('=== DEBUG: Iniciando teste de busca de clientes ===');

// Verificar se os elementos existem
const clienteSearch = document.getElementById('cliente_omie_search');
const clienteDropdown = document.getElementById('client_dropdown');
const clienteLoading = document.getElementById('client_loading');
const clienteResults = document.getElementById('client_results');
const clienteNoResults = document.getElementById('client_no_results');

console.log('Elementos encontrados:');
console.log('- cliente_omie_search:', clienteSearch ? 'OK' : 'ERRO - Não encontrado');
console.log('- client_dropdown:', clienteDropdown ? 'OK' : 'ERRO - Não encontrado');
console.log('- client_loading:', clienteLoading ? 'OK' : 'ERRO - Não encontrado');
console.log('- client_results:', clienteResults ? 'OK' : 'ERRO - Não encontrado');
console.log('- client_no_results:', clienteNoResults ? 'OK' : 'ERRO - Não encontrado');

// Verificar se as funções existem
console.log('\nFunções disponíveis:');
console.log('- buscarClientesOmie:', typeof buscarClientesOmie !== 'undefined' ? 'OK' : 'ERRO - Não definida');
console.log('- showDropdown:', typeof showDropdown !== 'undefined' ? 'OK' : 'ERRO - Não definida');
console.log('- hideDropdown:', typeof hideDropdown !== 'undefined' ? 'OK' : 'ERRO - Não definida');
console.log('- showLoading:', typeof showLoading !== 'undefined' ? 'OK' : 'ERRO - Não definida');
console.log('- hideLoading:', typeof hideLoading !== 'undefined' ? 'OK' : 'ERRO - Não definida');

// Testar evento de input
if (clienteSearch) {
    console.log('\n=== Testando evento de input ===');
    clienteSearch.value = 'Diag';
    const inputEvent = new Event('input', { bubbles: true });
    clienteSearch.dispatchEvent(inputEvent);
    console.log('Evento de input disparado com valor: "Diag"');
    
    // Verificar após 1 segundo se o loading apareceu
    setTimeout(() => {
        console.log('\n=== Status após 1 segundo ===');
        console.log('- Dropdown visível:', !clienteDropdown.classList.contains('hidden'));
        console.log('- Loading visível:', !clienteLoading.classList.contains('hidden'));
        console.log('- Valor do campo:', clienteSearch.value);
    }, 1000);
    
    // Verificar após 3 segundos se os resultados apareceram
    setTimeout(() => {
        console.log('\n=== Status após 3 segundos ===');
        console.log('- Dropdown visível:', !clienteDropdown.classList.contains('hidden'));
        console.log('- Loading visível:', !clienteLoading.classList.contains('hidden'));
        console.log('- Resultados visíveis:', !clienteResults.classList.contains('hidden'));
        console.log('- No results visível:', !clienteNoResults.classList.contains('hidden'));
        console.log('- Conteúdo dos resultados:', clienteResults.innerHTML.length > 0 ? 'Tem conteúdo' : 'Vazio');
    }, 3000);
}

console.log('\n=== Fim do teste de debug ===');