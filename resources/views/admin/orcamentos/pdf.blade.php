<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orçamento #{{ $orcamento->numero_orcamento }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #6b7280;
            font-size: 11px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        
        .status-rascunho { background: #f3f4f6; color: #374151; }
        .status-enviado { background: #dbeafe; color: #1e40af; }
        .status-aprovado { background: #d1fae5; color: #065f46; }
        .status-rejeitado { background: #fee2e2; color: #991b1b; }
        .status-cancelado { background: #fef3c7; color: #92400e; }
        
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            font-weight: bold;
            color: #374151;
            padding: 5px 15px 5px 0;
            width: 30%;
            vertical-align: top;
        }
        
        .info-value {
            display: table-cell;
            color: #6b7280;
            padding: 5px 0;
            vertical-align: top;
        }
        
        .financial-summary {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
        }
        
        .financial-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .financial-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #374151;
        }
        
        .financial-label {
            font-weight: 600;
            color: #374151;
        }
        
        .financial-value {
            color: #1f2937;
            font-weight: 500;
        }
        
        .observacoes {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin-top: 20px;
        }
        
        .observacoes h4 {
            color: #92400e;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .observacoes p {
            color: #78350f;
            line-height: 1.5;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
        }
        
        @media print {
            body { margin: 0; }
            .container { padding: 10px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Orçamento #{{ $orcamento->numero_orcamento }}</h1>
            <p>Gerado em {{ now()->format('d/m/Y H:i') }}</p>
        </div>

        <!-- Status -->
        <div class="text-center">
            <span class="status-badge status-{{ $orcamento->status }}">
                {{ ucfirst($orcamento->status) }}
            </span>
        </div>

        <!-- Informações Básicas -->
        <div class="section">
            <h3 class="section-title">Informações Básicas</h3>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Data da Solicitação:</div>
                    <div class="info-value">{{ $orcamento->data_solicitacao->format('d/m/Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Data do Orçamento:</div>
                    <div class="info-value">{{ $orcamento->data_orcamento->format('d/m/Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Centro de Custo:</div>
                    <div class="info-value">{{ $orcamento->centroCusto->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tipo de Orçamento:</div>
                    <div class="info-value">
                        @switch($orcamento->tipo_orcamento)
                            @case('prestador')
                                Prestador
                                @break
                            @case('aumento_km')
                                Aumento de KM
                                @break
                            @case('proprio_nova_rota')
                                Próprio Nova Rota
                                @break
                        @endswitch
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Responsável:</div>
                    <div class="info-value">{{ $orcamento->user->name }}</div>
                </div>
            </div>
        </div>

        <!-- Informações da Rota -->
        <div class="section">
            <h3 class="section-title">Informações da Rota</h3>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Nome da Rota:</div>
                    <div class="info-value">{{ $orcamento->nome_rota }}</div>
                </div>
                @if($orcamento->id_logcare)
                <div class="info-row">
                    <div class="info-label">ID Logcare:</div>
                    <div class="info-value">{{ $orcamento->id_logcare }}</div>
                </div>
                @endif
                @if($orcamento->horario)
                <div class="info-row">
                    <div class="info-label">Horário:</div>
                    <div class="info-value">{{ $orcamento->horario }}</div>
                </div>
                @endif
                @if($orcamento->frequencia_atendimento)
                <div class="info-row">
                    <div class="info-label">Frequência:</div>
                    <div class="info-value">{{ implode(', ', $orcamento->frequencia_atendimento) }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Informações do Cliente -->
        @if($orcamento->cliente_nome || $orcamento->cliente_omie_id)
        <div class="section">
            <h3 class="section-title">Informações do Cliente</h3>
            <div class="info-grid">
                @if($orcamento->cliente_nome)
                <div class="info-row">
                    <div class="info-label">Nome:</div>
                    <div class="info-value">{{ $orcamento->cliente_nome }}</div>
                </div>
                @endif
                @if($orcamento->cliente_omie_id)
                <div class="info-row">
                    <div class="info-label">ID Omie:</div>
                    <div class="info-value">{{ $orcamento->cliente_omie_id }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Dados Específicos do Prestador -->
        @if($orcamento->tipo_orcamento === 'prestador' && $orcamento->orcamentoPrestador)
        <div class="section">
            <h3 class="section-title">Dados do Prestador</h3>
            <div class="info-grid">
                @if($orcamento->orcamentoPrestador->fornecedor_nome)
                <div class="info-row">
                    <div class="info-label">Fornecedor:</div>
                    <div class="info-value">{{ $orcamento->orcamentoPrestador->fornecedor_nome }}</div>
                </div>
                @endif
                @if($orcamento->orcamentoPrestador->fornecedor_omie_id)
                <div class="info-row">
                    <div class="info-label">ID Omie Fornecedor:</div>
                    <div class="info-value">{{ $orcamento->orcamentoPrestador->fornecedor_omie_id }}</div>
                </div>
                @endif
                @if($orcamento->orcamentoPrestador->valor_referencia)
                <div class="info-row">
                    <div class="info-label">Valor de Referência:</div>
                    <div class="info-value">R$ {{ number_format($orcamento->orcamentoPrestador->valor_referencia, 2, ',', '.') }}</div>
                </div>
                @endif
                @if($orcamento->orcamentoPrestador->qtd_dias)
                <div class="info-row">
                    <div class="info-label">Quantidade de Dias:</div>
                    <div class="info-value">{{ $orcamento->orcamentoPrestador->qtd_dias }} dias</div>
                </div>
                @endif
            </div>
            
            <!-- Resumo Financeiro Prestador -->
            <div class="financial-summary">
                <h4 style="margin-bottom: 10px; color: #374151;">Resumo Financeiro</h4>
                @if($orcamento->orcamentoPrestador->custo_fornecedor)
                <div class="financial-row">
                    <span class="financial-label">Custo do Fornecedor:</span>
                    <span class="financial-value">R$ {{ number_format($orcamento->orcamentoPrestador->custo_fornecedor, 2, ',', '.') }}</span>
                </div>
                @endif
                @if($orcamento->orcamentoPrestador->valor_lucro)
                <div class="financial-row">
                    <span class="financial-label">Lucro ({{ $orcamento->orcamentoPrestador->percentual_lucro }}%):</span>
                    <span class="financial-value">R$ {{ number_format($orcamento->orcamentoPrestador->valor_lucro, 2, ',', '.') }}</span>
                </div>
                @endif
                @if($orcamento->orcamentoPrestador->valor_impostos)
                <div class="financial-row">
                    <span class="financial-label">Impostos ({{ $orcamento->orcamentoPrestador->percentual_impostos }}%):</span>
                    <span class="financial-value">R$ {{ number_format($orcamento->orcamentoPrestador->valor_impostos, 2, ',', '.') }}</span>
                </div>
                @endif
                @if($orcamento->orcamentoPrestador->valor_total)
                <div class="financial-row">
                    <span class="financial-label">Valor Total:</span>
                    <span class="financial-value">R$ {{ number_format($orcamento->orcamentoPrestador->valor_total, 2, ',', '.') }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Dados Específicos do Aumento de KM -->
        @if($orcamento->tipo_orcamento === 'aumento_km' && $orcamento->orcamentoAumentoKm)
        <div class="section">
            <h3 class="section-title">Dados do Aumento de KM</h3>
            <div class="info-grid">
                @if($orcamento->orcamentoAumentoKm->km_dia)
                <div class="info-row">
                    <div class="info-label">KM por Dia:</div>
                    <div class="info-value">{{ number_format($orcamento->orcamentoAumentoKm->km_dia, 0, ',', '.') }} km</div>
                </div>
                @endif
                @if($orcamento->orcamentoAumentoKm->qtd_dias)
                <div class="info-row">
                    <div class="info-label">Quantidade de Dias:</div>
                    <div class="info-value">{{ $orcamento->orcamentoAumentoKm->qtd_dias }} dias</div>
                </div>
                @endif
                @if($orcamento->orcamentoAumentoKm->combustivel_km_litro)
                <div class="info-row">
                    <div class="info-label">Consumo (KM/L):</div>
                    <div class="info-value">{{ number_format($orcamento->orcamentoAumentoKm->combustivel_km_litro, 2, ',', '.') }} km/l</div>
                </div>
                @endif
                @if($orcamento->orcamentoAumentoKm->valor_combustivel)
                <div class="info-row">
                    <div class="info-label">Valor do Combustível:</div>
                    <div class="info-value">R$ {{ number_format($orcamento->orcamentoAumentoKm->valor_combustivel, 2, ',', '.') }}/litro</div>
                </div>
                @endif
                @if($orcamento->orcamentoAumentoKm->hora_extra)
                <div class="info-row">
                    <div class="info-label">Hora Extra:</div>
                    <div class="info-value">R$ {{ number_format($orcamento->orcamentoAumentoKm->hora_extra, 2, ',', '.') }}</div>
                </div>
                @endif
            </div>
            
            <!-- Resumo Financeiro Aumento KM -->
            @if($orcamento->orcamentoAumentoKm->valor_total)
            <div class="financial-summary">
                <h4 style="margin-bottom: 10px; color: #374151;">Resumo Financeiro</h4>
                <div class="financial-row">
                    <span class="financial-label">Valor Total:</span>
                    <span class="financial-value">R$ {{ number_format($orcamento->orcamentoAumentoKm->valor_total, 2, ',', '.') }}</span>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Dados Específicos do Próprio Nova Rota -->
        @if($orcamento->tipo_orcamento === 'proprio_nova_rota' && $orcamento->orcamentoProprioNovaRota)
        <div class="section">
            <h3 class="section-title">Dados da Nova Rota Própria</h3>
            <div class="info-grid">
                @if($orcamento->orcamentoProprioNovaRota->nova_origem)
                <div class="info-row">
                    <div class="info-label">Nova Origem:</div>
                    <div class="info-value">{{ $orcamento->orcamentoProprioNovaRota->nova_origem }}</div>
                </div>
                @endif
                @if($orcamento->orcamentoProprioNovaRota->novo_destino)
                <div class="info-row">
                    <div class="info-label">Novo Destino:</div>
                    <div class="info-value">{{ $orcamento->orcamentoProprioNovaRota->novo_destino }}</div>
                </div>
                @endif
                @if($orcamento->orcamentoProprioNovaRota->km_nova_rota)
                <div class="info-row">
                    <div class="info-label">KM da Nova Rota:</div>
                    <div class="info-value">{{ number_format($orcamento->orcamentoProprioNovaRota->km_nova_rota, 0, ',', '.') }} km</div>
                </div>
                @endif
                @if($orcamento->orcamentoProprioNovaRota->valor_km_nova_rota)
                <div class="info-row">
                    <div class="info-label">Valor por KM:</div>
                    <div class="info-value">R$ {{ number_format($orcamento->orcamentoProprioNovaRota->valor_km_nova_rota, 2, ',', '.') }}</div>
                </div>
                @endif
                @if($orcamento->orcamentoProprioNovaRota->motivo_alteracao)
                <div class="info-row">
                    <div class="info-label">Motivo da Alteração:</div>
                    <div class="info-value">{{ $orcamento->orcamentoProprioNovaRota->motivo_alteracao }}</div>
                </div>
                @endif
            </div>
            
            <!-- Resumo Financeiro Nova Rota -->
            @if($orcamento->orcamentoProprioNovaRota->valor_total)
            <div class="financial-summary">
                <h4 style="margin-bottom: 10px; color: #374151;">Resumo Financeiro</h4>
                <div class="financial-row">
                    <span class="financial-label">Valor Total:</span>
                    <span class="financial-value">R$ {{ number_format($orcamento->orcamentoProprioNovaRota->valor_total, 2, ',', '.') }}</span>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Observações -->
        @if($orcamento->observacoes)
        <div class="observacoes">
            <h4>Observações</h4>
            <p>{{ $orcamento->observacoes }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Este documento foi gerado automaticamente pelo Sistema de Orçamentos em {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>