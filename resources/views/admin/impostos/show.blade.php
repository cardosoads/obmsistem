@extends('layouts.admin')

@section('title', 'Visualizar Imposto')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Detalhes do Imposto: {{ $imposto->nome }}</h3>
                    <div>
                        <a href="{{ route('admin.impostos.edit', $imposto) }}" class="btn btn-warning mr-2">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('admin.impostos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- Informações Básicas -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-info-circle"></i> Informações Básicas
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Nome:</strong></td>
                                            <td>{{ $imposto->nome }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Percentual:</strong></td>
                                            <td>
                                                <span class="badge badge-primary badge-lg">
                                                    {{ $imposto->percentual_formatado }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                <span class="badge badge-{{ $imposto->ativo ? 'success' : 'danger' }}">
                                                    {{ $imposto->status_formatado }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Descrição:</strong></td>
                                            <td>
                                                @if($imposto->descricao)
                                                    {{ $imposto->descricao }}
                                                @else
                                                    <em class="text-muted">Nenhuma descrição informada</em>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informações do Sistema -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-clock"></i> Informações do Sistema
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Criado em:</strong></td>
                                            <td>{{ $imposto->created_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Atualizado em:</strong></td>
                                            <td>{{ $imposto->updated_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>ID:</strong></td>
                                            <td><code>{{ $imposto->id }}</code></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Grupos de Impostos -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-layer-group"></i> Grupos de Impostos
                                        <span class="badge badge-info ml-2">{{ $imposto->gruposImpostos->count() }}</span>
                                    </h5>
                                    @if($imposto->gruposImpostos->count() > 0)
                                        <small class="text-muted">
                                            Total combinado: {{ number_format($imposto->gruposImpostos->sum('percentual_total'), 2, ',', '.') }}%
                                        </small>
                                    @endif
                                </div>
                                <div class="card-body">
                                    @if($imposto->gruposImpostos->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Nome do Grupo</th>
                                                        <th>Descrição</th>
                                                        <th>Total do Grupo</th>
                                                        <th>Status</th>
                                                        <th>Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($imposto->gruposImpostos as $grupo)
                                                        <tr>
                                                            <td>
                                                                <strong>{{ $grupo->nome }}</strong>
                                                            </td>
                                                            <td>
                                                                @if($grupo->descricao)
                                                                    {{ Str::limit($grupo->descricao, 50) }}
                                                                @else
                                                                    <em class="text-muted">Sem descrição</em>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-secondary">
                                                                    {{ number_format($grupo->percentual_total, 2, ',', '.') }}%
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-{{ $grupo->ativo ? 'success' : 'danger' }}">
                                                                    {{ $grupo->ativo ? 'Ativo' : 'Inativo' }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('admin.grupos-impostos.show', $grupo) }}" 
                                                                   class="btn btn-sm btn-outline-info" 
                                                                   title="Visualizar grupo">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-3">Este imposto não está associado a nenhum grupo.</p>
                                            <a href="{{ route('admin.impostos.edit', $imposto) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-edit"></i> Editar e Associar a Grupos
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Calculadora de Teste -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-calculator"></i> Calculadora de Teste
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="valor_teste">Valor Base (R$)</label>
                                                <input type="number" 
                                                       class="form-control" 
                                                       id="valor_teste" 
                                                       placeholder="1000.00" 
                                                       step="0.01" 
                                                       min="0"
                                                       value="1000">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Percentual do Imposto</label>
                                                <div class="form-control-plaintext font-weight-bold text-primary">
                                                    {{ $imposto->percentual_formatado }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Valor do Imposto</label>
                                                <div class="form-control-plaintext font-weight-bold text-success" id="valor_imposto">
                                                    R$ {{ number_format(($imposto->percentual * 1000) / 100, 2, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Total com Imposto</label>
                                                <div class="form-control-plaintext font-weight-bold text-info" id="valor_total">
                                                    R$ {{ number_format(1000 + (($imposto->percentual * 1000) / 100), 2, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i> 
                                                Altere o valor base acima para ver o cálculo em tempo real
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('admin.impostos.edit', $imposto) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            
                            @if($imposto->ativo)
                                <button type="button" class="btn btn-outline-secondary ml-2" onclick="toggleStatus({{ $imposto->id }})">
                                    <i class="fas fa-eye-slash"></i> Desativar
                                </button>
                            @else
                                <button type="button" class="btn btn-outline-success ml-2" onclick="toggleStatus({{ $imposto->id }})">
                                    <i class="fas fa-eye"></i> Ativar
                                </button>
                            @endif
                            
                            <button type="button" class="btn btn-outline-danger ml-2" onclick="confirmDelete({{ $imposto->id }})">
                                <i class="fas fa-trash"></i> Excluir
                            </button>
                            
                            <a href="{{ route('admin.impostos.index') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-arrow-left"></i> Voltar à Lista
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir o imposto <strong>{{ $imposto->nome }}</strong>?</p>
                <p class="text-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Esta ação não pode ser desfeita.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Excluir
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Calculadora de teste
    $('#valor_teste').on('input', function() {
        calcularTeste();
    });
    
    // Calcular com valor inicial
    calcularTeste();
    
    function calcularTeste() {
        const percentual = {{ $imposto->percentual }};
        const valorBase = parseFloat($('#valor_teste').val()) || 0;
        
        if (valorBase > 0) {
            const valorImposto = (valorBase * percentual) / 100;
            const valorTotal = valorBase + valorImposto;
            
            $('#valor_imposto').text('R$ ' + valorImposto.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
            
            $('#valor_total').text('R$ ' + valorTotal.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
        } else {
            $('#valor_imposto').text('R$ 0,00');
            $('#valor_total').text('R$ 0,00');
        }
    }
});

// Função para alternar status
function toggleStatus(id) {
    $.ajax({
        url: `/admin/impostos/${id}/toggle-status`,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Erro ao alterar status: ' + response.message);
            }
        },
        error: function() {
            alert('Erro ao alterar status do imposto.');
        }
    });
}

// Função para confirmar exclusão
function confirmDelete(id) {
    $('#deleteForm').attr('action', `/admin/impostos/${id}`);
    $('#deleteModal').modal('show');
}
</script>
@endpush