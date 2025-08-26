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
            font-size: 11px;
            line-height: 1.5;
            color: #2d3748;
            background: #ffffff;
        }
        
        .container {
            max-width: 100%;
            margin: 0;
            padding: 25px;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            margin: -25px -25px 30px -25px;
            text-align: center;
            position: relative;
        }
        
        .header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 15px solid transparent;
            border-right: 15px solid transparent;
            border-top: 10px solid #764ba2;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .header p {
            font-size: 12px;
            opacity: 0.9;
        }
        
        .company-info {
            text-align: center;
            margin-bottom: 25px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        
        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 5px;
        }
        
        .company-details {
            font-size: 10px;
            color: #718096;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            letter-spacing: 0.5px;
        }
        
        .status-rascunho { 
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%); 
            color: #4a5568; 
            border: 1px solid #cbd5e0;
        }
        .status-enviado { 
            background: linear-gradient(135deg, #ebf8ff 0%, #bee3f8 100%); 
            color: #2b6cb0; 
            border: 1px solid #90cdf4;
        }
        .status-aprovado { 
            background: linear-gradient(135deg, #f0fff4 0%, #c6f6d5 100%); 
            color: #22543d; 
            border: 1px solid #9ae6b4;
        }
        .status-rejeitado { 
            background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%); 
            color: #742a2a; 
            border: 1px solid #fc8181;
        }
        .status-cancelado { 
            background: linear-gradient(135deg, #fffaf0 0%, #feebc8 100%); 
            color: #744210; 
            border: 1px solid #f6ad55;
        }
        
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 18px;
            padding-bottom: 8px;
            border-bottom: 2px solid #667eea;
            position: relative;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 30px;
            height: 2px;
            background: #764ba2;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-row:nth-child(even) {
            background: #f8fafc;
        }
        
        .info-label {
            display: table-cell;
            font-weight: 600;
            color: #4a5568;
            padding: 12px 20px 12px 0;
            width: 35%;
            vertical-align: top;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .info-value {
            display: table-cell;
            color: #2d3748;
            padding: 12px 0;
            vertical-align: top;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 500;
        }
        
        .financial-summary {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            border: 2px solid #667eea;
            border-radius: 12px;
            padding: 20px;
            margin-top: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .financial-summary h4 {
            color: #2d3748;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 1px solid #cbd5e0;
        }
        
        .financial-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .financial-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 13px;
            margin-top: 15px;
            padding: 15px 0 0 0;
            border-top: 2px solid #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
            padding: 12px 15px;
        }
        
        .financial-label {
            font-weight: 600;
            color: #4a5568;
        }
        
        .financial-value {
            color: #2d3748;
            font-weight: 600;
        }
        
        .financial-row:last-child .financial-label,
        .financial-row:last-child .financial-value {
            color: white;
        }
        
        .observacoes {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border-left: 5px solid #f59e0b;
            border-radius: 8px;
            padding: 20px;
            margin-top: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .observacoes h4 {
            color: #92400e;
            margin-bottom: 12px;
            font-size: 14px;
            font-weight: bold;
        }
        
        .observacoes p {
            color: #78350f;
            line-height: 1.6;
            font-weight: 500;
        }
        
        .footer {
            margin-top: 50px;
            padding: 20px;
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            border-radius: 8px;
            text-align: center;
            color: #4a5568;
            font-size: 10px;
            border-top: 3px solid #667eea;
        }
        
        .page-break {
             page-break-before: always;
         }
         
         .avoid-break {
             page-break-inside: avoid;
         }
         
         .financial-summary {
             page-break-inside: avoid;
         }
         
         .info-grid {
             page-break-inside: avoid;
         }
         
         h3.section-title {
             page-break-after: avoid;
         }
         
         @media print {
             body { 
                 margin: 0;
                 font-size: 12px;
                 line-height: 1.4;
             }
             .container { 
                 padding: 15px;
                 max-width: 100%;
             }
             .section { 
                 box-shadow: none;
                 border: 1px solid #e2e8f0;
                 margin-bottom: 15px;
             }
             .header {
                 margin-bottom: 20px;
             }
             .company-info {
                 margin-bottom: 15px;
             }
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

        <!-- Company Info -->
        <div class="company-info">
            <div class="company-name">OBM Logística e Transportes</div>
            <div class="company-details">
                CNPJ: 00.000.000/0001-00 | Telefone: (11) 0000-0000 | Email: contato@obmlogistica.com.br
            </div>
        </div>

        <!-- Status -->
        <div style="text-align: center;">
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
        <div class="section page-break">
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
        <div class="section page-break">
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
        <div class="section page-break">
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