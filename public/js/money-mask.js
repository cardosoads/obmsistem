/**
 * Máscara de valores monetários no padrão brasileiro usando brazilian-values
 * Formato: R$ 10.000,00
 */

// Importa a biblioteca brazilian-values
// Como instalamos via npm, vamos tentar importar de diferentes formas
let BrazilianValues = null;

// Tenta carregar a biblioteca de diferentes formas
if (typeof require !== 'undefined') {
    try {
        BrazilianValues = require('brazilian-values');
    } catch (e) {
        console.log('Não foi possível carregar brazilian-values via require');
    }
}

// Se não conseguiu carregar via require, tenta via CDN
if (!BrazilianValues && typeof window !== 'undefined' && !window.BrazilianValues) {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/brazilian-values';
    script.onload = function() {
        console.log('Brazilian Values carregado via CDN');
        BrazilianValues = window.BrazilianValues;
    };
    document.head.appendChild(script);
}

// Função para formatar valor como moeda brasileira para input
function formatMoneyForInput(value) {
    // Se o valor já contém vírgula, significa que já está formatado
    // Preserva exatamente como está para evitar reformatação
    if (value.toString().includes(',')) {
        return value.toString();
    }
    
    // Remove tudo que não é dígito
    let cleanValue = value.toString().replace(/\D/g, '');
    
    // Se não há valor, retorna vazio
    if (!cleanValue) return '';
    
    // Remove zeros à esquerda mas mantém pelo menos um dígito
    cleanValue = cleanValue.replace(/^0+/, '') || '0';
    
    // Limita a 15 dígitos para evitar overflow
    if (cleanValue.length > 15) {
        cleanValue = cleanValue.substring(0, 15);
    }
    
    // Trata cada dígito como centavo
    if (cleanValue.length === 1) {
        // 1 dígito: "1" → "0,01"
        return '0,0' + cleanValue;
    } else if (cleanValue.length === 2) {
        // 2 dígitos: "12" → "0,12"
        return '0,' + cleanValue;
    } else {
        // 3+ dígitos: separa os últimos 2 como centavos
        let reaisPart = cleanValue.slice(0, -2);
        let centavosPart = cleanValue.slice(-2);
        
        // Formata a parte dos reais com separadores de milhares
        let reaisFormatted = '';
        
        // Adiciona pontos a cada 3 dígitos da direita para esquerda
        for (let i = reaisPart.length - 1, count = 0; i >= 0; i--, count++) {
            if (count > 0 && count % 3 === 0) {
                reaisFormatted = '.' + reaisFormatted;
            }
            reaisFormatted = reaisPart[i] + reaisFormatted;
        }
        
        return reaisFormatted + ',' + centavosPart;
    }
}

// Função para formatar valor como moeda brasileira (para exibição)
function formatMoney(value) {
    // Tenta usar brazilian-values se disponível
    if (typeof BrazilianValues !== 'undefined' && BrazilianValues.formatToBRL) {
        try {
            // Sempre converte para número usando getNumericValue para consistência
            let numericValue = getNumericValue(value);
            
            // Usa brazilian-values para formatar
            let formatted = BrazilianValues.formatToBRL(numericValue);
            
            // Remove o R$ para retornar apenas o valor
            return formatted.replace('R$ ', '');
        } catch (error) {
            console.warn('Erro ao usar brazilian-values, usando fallback:', error);
        }
    }
    
    // Fallback: usa a formatação para input
    return formatMoneyForInput(value);
}

// Função fallback para formatação manual (mantém a lógica original)
function formatMoneyFallback(cleanValue) {
    // Garante que sempre temos pelo menos 2 dígitos para os centavos
    if (cleanValue.length === 1) {
        // 1 dígito: "1" → "0,01"
        return '0,0' + cleanValue;
    } else if (cleanValue.length === 2) {
        // 2 dígitos: "12" → "0,12"
        return '0,' + cleanValue;
    } else {
        // 3+ dígitos: SEMPRE separa os últimos 2 como centavos
        let centavosPart = cleanValue.slice(-2);
        let reaisPart = cleanValue.slice(0, -2);
        
        if (!reaisPart) {
            reaisPart = '0';
        }
        
        // Formata a parte dos reais com separadores de milhares
        let reaisFormatted = '';
        for (let i = reaisPart.length - 1, count = 0; i >= 0; i--, count++) {
            if (count > 0 && count % 3 === 0) {
                reaisFormatted = '.' + reaisFormatted;
            }
            reaisFormatted = reaisPart[i] + reaisFormatted;
        }
        
        return reaisFormatted + ',' + centavosPart;
    }
}

// Função para aplicar máscara em tempo real
function applyMoneyMask(input) {
    // Salva a posição do cursor
    let cursorPosition = input.selectionStart;
    let oldValue = input.value;
    
    // Se o valor já está formatado corretamente (contém vírgula), não aplica máscara
    if (oldValue.includes(',') && oldValue.match(/^\d{1,3}(\.\d{3})*,\d{2}$/)) {
        // Valor já está no formato correto, apenas atualiza o valor numérico
        updateNumericValue(input, oldValue);
        return;
    }
    
    // Remove tudo que não é dígito do valor atual
    let cleanValue = input.value.replace(/\D/g, '');
    
    // Limita a 15 dígitos para evitar valores muito grandes
    if (cleanValue.length > 15) {
        cleanValue = cleanValue.substring(0, 15);
    }
    
    // Aplica a formatação específica para input (cada dígito como centavo)
    let formattedValue = formatMoneyForInput(cleanValue);
    
    // Só atualiza se o valor mudou para evitar loops
    if (formattedValue !== oldValue) {
        input.value = formattedValue;
        
        // Coloca o cursor sempre no final para facilitar a digitação
        let newCursorPosition = formattedValue.length;
        input.setSelectionRange(newCursorPosition, newCursorPosition);
    }
    
    // Atualiza o valor numérico para cálculos
    updateNumericValue(input, formattedValue);
}

// Função para converter valor formatado para numérico
function getNumericValue(formattedValue) {
    if (!formattedValue) return 0;
    
    // Detecta se o valor contém vírgula (já formatado) ou são apenas dígitos
    if (formattedValue.toString().includes(',')) {
        // Valor já formatado: remove pontos de milhares e substitui vírgula por ponto
        let cleanValue = formattedValue
            .replace(/\./g, '')
            .replace(',', '.');
        
        return parseFloat(cleanValue) || 0;
    } else {
        // Valor são apenas dígitos: trata como centavos
        let cleanValue = formattedValue.toString().replace(/\D/g, '');
        if (!cleanValue) return 0;
        
        // Remove zeros à esquerda
        cleanValue = cleanValue.replace(/^0+/, '') || '0';
        
        // Converte centavos para reais (divide por 100)
        return parseFloat(cleanValue) / 100;
    }
}

// Função para atualizar valor numérico em campo oculto ou data attribute
function updateNumericValue(input, formattedValue) {
    let numericValue = getNumericValue(formattedValue);
    input.setAttribute('data-numeric-value', numericValue);
    
    // Se existe um campo oculto correspondente, atualiza ele também
    let hiddenField = document.querySelector(`input[name="${input.name}_numeric"]`);
    if (hiddenField) {
        hiddenField.value = numericValue;
    }
}

// Função para inicializar máscaras em campos específicos
function initMoneyMasks() {
    // Seleciona todos os campos que devem ter máscara de dinheiro
    const moneyFields = document.querySelectorAll('.money-mask');
    
    moneyFields.forEach(field => {
        // Aplica máscara no valor inicial se existir
        if (field.value) {
            applyMoneyMask(field);
        }
        
        // Adiciona event listeners
        field.addEventListener('input', function() {
            applyMoneyMask(this);
        });
        
        field.addEventListener('focus', function() {
            // Se o campo estiver zerado, limpa para permitir digitação desde o primeiro dígito
            const numericValue = getNumericValue(this.value);
            if (numericValue === 0) {
                this.value = '';
            }
        });
        
        field.addEventListener('blur', function() {
            // Se o campo estiver vazio, define como 0,00
            if (this.value === '' || this.value.trim() === '') {
                this.value = '0,00';
                updateNumericValue(this, '0,00');
            } else {
                // Aplica a máscara para garantir formatação correta
                applyMoneyMask(this);
            }
        });
        
        // Previne entrada de caracteres não numéricos
        field.addEventListener('keydown', function(e) {
            // Permite números, backspace, delete, tab, enter e setas
            const allowedKeys = ['Backspace', 'Delete', 'Tab', 'Enter', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'];
            if (!/[0-9]/.test(e.key) && !allowedKeys.includes(e.key)) {
                e.preventDefault();
            }
        });
    });
}

// Função para preparar formulário antes do envio
function prepareFormSubmission() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Encontrar todos os campos com máscara monetária
            const moneyFields = form.querySelectorAll('.money-mask');
            
            moneyFields.forEach(field => {
                // Converter valor formatado para numérico apenas no momento do envio
                const numericValue = getNumericValue(field.value);
                
                // Atualizar o campo com valor numérico para envio
                field.value = numericValue;
            });
        });
    });
}

// Inicializa quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    initMoneyMasks();
    prepareFormSubmission();
});

// Função para ser chamada externamente quando novos campos são adicionados dinamicamente
window.reinitMoneyMasks = function() {
    initMoneyMasks();
    prepareFormSubmission();
};

// Função para obter valor numérico de um campo (para uso em cálculos)
window.getFieldNumericValue = function(fieldId) {
    const field = document.getElementById(fieldId);
    if (field) {
        return getNumericValue(field.value);
    }
    return 0;
};