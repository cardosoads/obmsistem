// Funcionalidades JavaScript para o módulo de orçamentos

// Função para mostrar alertas
function showAlert(type, message) {
    const alertContainer = document.getElementById('alert-container');
    if (!alertContainer) {
        // Criar container de alertas se não existir
        const container = document.createElement('div');
        container.id = 'alert-container';
        container.className = 'fixed top-4 right-4 z-50';
        document.body.appendChild(container);
    }
    
    const alertDiv = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    
    alertDiv.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg mb-4 transform transition-all duration-300 translate-x-full`;
    alertDiv.innerHTML = `
        <div class="flex items-center justify-between">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    
    document.getElementById('alert-container').appendChild(alertDiv);
    
    // Animar entrada
    setTimeout(() => {
        alertDiv.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remover após 5 segundos
    setTimeout(() => {
        alertDiv.classList.add('translate-x-full');
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 300);
    }, 5000);
}

// Função para atualizar status do orçamento
function updateOrcamentoStatus(orcamentoId, newStatus, selectElement) {
    const currentStatus = selectElement.dataset.currentStatus;
    
    if (newStatus === currentStatus) {
        return;
    }
    
    // Desabilita o select durante a requisição
    selectElement.disabled = true;
    
    // Obter token CSRF
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    fetch(`/admin/orcamentos/${orcamentoId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            selectElement.dataset.currentStatus = newStatus;
            showAlert('success', 'Status atualizado com sucesso!');
            
            // Atualizar badge de status se existir
            const statusBadge = document.querySelector(`[data-status-badge="${orcamentoId}"]`);
            if (statusBadge) {
                updateStatusBadge(statusBadge, newStatus);
            }
        } else {
            // Reverte o select
            selectElement.value = currentStatus;
            showAlert('error', data.message || 'Erro ao atualizar status');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        // Reverte o select
        selectElement.value = currentStatus;
        showAlert('error', 'Erro ao atualizar status');
    })
    .finally(() => {
        // Reabilita o select
        selectElement.disabled = false;
    });
}

// Função para atualizar o badge de status
function updateStatusBadge(badge, status) {
    // Remove classes antigas
    badge.className = badge.className.replace(/bg-\w+-\d+/g, '');
    
    // Mapear status para cores
    const statusColors = {
        'em_andamento': 'bg-gray-100 text-gray-800',
        'enviado': 'bg-blue-100 text-blue-800',
        'aguardando': 'bg-yellow-100 text-yellow-800',
        'aprovado': 'bg-green-100 text-green-800',
        'rejeitado': 'bg-red-100 text-red-800',
        'cancelado': 'bg-red-100 text-red-800',
        'expirado': 'bg-gray-100 text-gray-800'
    };
    
    const statusLabels = {
        'em_andamento': 'Em Andamento',
        'enviado': 'Enviado',
        'aguardando': 'Aguardando',
        'aprovado': 'Aprovado',
        'rejeitado': 'Rejeitado',
        'cancelado': 'Cancelado',
        'expirado': 'Expirado'
    };
    
    badge.className = `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusColors[status] || 'bg-gray-100 text-gray-800'}`;
    badge.textContent = statusLabels[status] || status;
}

// Função para confirmar exclusão
function confirmDelete(orcamentoId, orcamentoNumero) {
    if (confirm(`Tem certeza que deseja excluir o orçamento ${orcamentoNumero}?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/orcamentos/${orcamentoId}`;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        form.appendChild(methodInput);
        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// Função para duplicar orçamento
function duplicateOrcamento(orcamentoId) {
    if (confirm('Deseja criar uma cópia deste orçamento?')) {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        fetch(`/admin/orcamentos/${orcamentoId}/duplicate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Orçamento duplicado com sucesso!');
                // Redirecionar para o novo orçamento após 2 segundos
                setTimeout(() => {
                    window.location.href = `/admin/orcamentos/${data.orcamento_id}/edit`;
                }, 2000);
            } else {
                showAlert('error', data.message || 'Erro ao duplicar orçamento');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao duplicar orçamento');
        });
    }
}

// Função para limpar filtros
function clearFilters() {
    const form = document.getElementById('filters-form');
    if (form) {
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            if (input.type === 'text' || input.type === 'date') {
                input.value = '';
            } else if (input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            }
        });
        form.submit();
    }
}

// Inicialização quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    // Configurar event listeners para selects de status
    const statusSelects = document.querySelectorAll('.status-select');
    statusSelects.forEach(select => {
        select.addEventListener('change', function() {
            const orcamentoId = this.dataset.orcamentoId;
            const newStatus = this.value;
            updateOrcamentoStatus(orcamentoId, newStatus, this);
        });
    });
    
    // Configurar auto-hide para alertas existentes
    const existingAlerts = document.querySelectorAll('[data-alert]');
    existingAlerts.forEach(alert => {
        const closeBtn = alert.querySelector('[data-close-alert]');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                alert.classList.add('opacity-0', 'transform', 'scale-95');
                setTimeout(() => {
                    alert.remove();
                }, 300);
            });
        }
        
        // Auto-hide após 5 segundos
        setTimeout(() => {
            if (alert.parentNode) {
                alert.classList.add('opacity-0', 'transform', 'scale-95');
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 300);
            }
        }, 5000);
    });
    
    // Configurar tooltips se houver biblioteca
    if (typeof tippy !== 'undefined') {
        tippy('[data-tippy-content]');
    }
});

// Exportar funções para uso global
window.updateOrcamentoStatus = updateOrcamentoStatus;
window.confirmDelete = confirmDelete;
window.duplicateOrcamento = duplicateOrcamento;
window.clearFilters = clearFilters;
window.showAlert = showAlert;