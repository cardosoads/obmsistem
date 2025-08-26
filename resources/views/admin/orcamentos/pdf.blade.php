<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orçamento #{{ $orcamento->numero_orcamento }}</title>
    <style>
        @page {
            margin: 15mm;
            size: A4;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12pt;
            line-height: 1em;
            color: #2d3748;
            background: #fff;
        }
        
        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 30mm 30mm 20mm 30mm;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30pt;
            padding: 24pt;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 3pt;
            page-break-after: avoid;
        }
        
        .header h1 {
            font-size: 18pt;
            font-weight: 600;
            margin-bottom: 12pt;
            color: #2d3748;
            line-height: 1em;
        }
        
        .header p {
            font-size: 12pt;
            color: #718096;
            line-height: 1em;
        }
        
        .company-info {
            background: #f7fafc;
            padding: 18pt;
            border: 1px solid #e2e8f0;
            border-radius: 3pt;
            margin-bottom: 24pt;
            text-align: center;
            page-break-after: avoid;
        }
        
        .company-name {
            font-size: 14pt;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 12pt;
            line-height: 1em;
        }
        
        .company-details {
            font-size: 11pt;
            color: #718096;
            line-height: 1em;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: 500;
            text-transform: uppercase;
            margin-bottom: 15px;
            border: 1px solid;
        }
        
        .status-rascunho { 
            background: #fffaf0;
            color: #744210;
            border-color: #f6ad55;
        }
        .status-enviado { 
            background: #ebf8ff;
            color: #2b6cb0;
            border-color: #90cdf4;
        }
        .status-aprovado { 
            background: #f0fff4;
            color: #22543d;
            border-color: #68d391;
        }
        .status-rejeitado { 
            background: #fff5f5;
            color: #742a2a;
            border-color: #fc8181;
        }
        .status-cancelado { 
            background: #fffaf0;
            color: #744210;
            border-color: #f6ad55;
        }
        
        .section {
            margin-bottom: 18pt;
            page-break-inside: avoid;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 3pt;
            padding: 12pt;
        }
        
        .section-title {
            background: #f7fafc;
            padding: 10px 15px;
            font-size: 14pt;
            font-weight: bold;
            color: #2d3748;
            border-bottom: 2px solid #3b82f6;
            margin: 0 0 12pt 0;
            line-height: 1em;
        }
        
        .info-grid {
            padding: 0;
            display: table;
            width: 100%;
        }
        
        .info-row {
            display: table-row;
            margin-bottom: 6pt;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            display: table-cell;
            font-weight: bold;
            color: #4a5568;
            padding: 4pt 12pt 4pt 0;
            width: 40%;
            vertical-align: top;
            line-height: 1em;
        }
        
        .info-value {
            display: table-cell;
            color: #2d3748;
            padding: 4pt 0;
            vertical-align: top;
            line-height: 1em;
        }
        
        .financial-summary {
            background: #f7fafc;
            padding: 12pt;
            border: 1px solid #e2e8f0;
            border-radius: 3pt;
            margin-top: 18pt;
            page-break-inside: avoid;
        }
        
        .financial-summary h4 {
            color: #2d3748;
            font-size: 11px;
            margin-bottom: 15px;
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 600;
        }
        
        .financial-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6pt 0;
            border-bottom: 1px solid #e2e8f0;
            line-height: 1em;
        }
        
        .financial-row:last-child {
            border-bottom: none;
            background: #2d3748;
            color: white;
            padding: 12px 15px;
            margin: 10px -15px -15px -15px;
            border-radius: 0 0 4px 4px;
            font-weight: 600;
            border: 2px solid #2d3748;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .financial-label {
            font-weight: 600;
            color: #4a5568;
            line-height: 1em;
        }
        
        .financial-value {
            color: #2d3748;
            font-weight: bold;
            font-size: 14pt;
            line-height: 1em;
        }
        
        .financial-row:last-child .financial-label,
        .financial-row:last-child .financial-value {
            color: white;
        }
        
        .observacoes {
            background: #fffaf0;
            border: 1px solid #f6ad55;
            border-radius: 3pt;
            padding: 18pt;
            margin-top: 24pt;
            page-break-inside: avoid;
        }
        
        .observacoes h4 {
            color: #744210;
            margin-bottom: 12pt;
            font-size: 12pt;
            font-weight: 600;
            line-height: 1em;
        }
        
        .observacoes p {
            color: #744210;
            line-height: 1em;
            font-size: 12pt;
        }
        
        .footer {
            margin-top: 36pt;
            padding: 18pt;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #718096;
            font-size: 10pt;
            page-break-inside: avoid;
        }
        
        .footer p {
            margin-bottom: 6pt;
            line-height: 1em;
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
         
         /* Controle inteligente de quebras de página */
         .section:nth-child(n+3) {
             page-break-before: auto;
         }
         
         .financial-summary {
             page-break-before: avoid;
         }
         
         @media print {
             body { 
                 margin: 0;
                 font-size: 12pt;
                 line-height: 1em;
             }
             .container { 
                 padding: 30mm 30mm 20mm 30mm;
                 max-width: 100%;
             }
             .section { 
                 box-shadow: none;
                 border: 1px solid #e2e8f0;
                 margin-bottom: 18pt;
             }
             .header {
                 margin-bottom: 24pt;
             }
             .company-info {
                 margin-bottom: 18pt;
             }
             .page-break {
                 page-break-before: always;
             }
             
             /* Evita quebras órfãs e viúvas */
             h1, h2, h3, h4 {
                 page-break-after: avoid;
             }
             
             .info-row {
                 page-break-inside: avoid;
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
                    <div class="info-value">{{ \Carbon\Carbon::parse($orcamento->data_solicitacao)->format('d/m/Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Data do Orçamento:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($orcamento->data_orcamento)->format('d/m/Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Centro de Custo:</div>
                    <div class="info-value">{{ $orcamento->centroCusto->name ?? 'N/A' }}</div>
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
                            @default
                                {{ $orcamento->tipo_orcamento }}
                        @endswitch
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Responsável:</div>
                    <div class="info-value">{{ $orcamento->user->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Criado em:</div>
                    <div class="info-value">{{ $orcamento->created_at->format('d/m/Y H:i:s') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Última Atualização:</div>
                    <div class="info-value">{{ $orcamento->updated_at->format('d/m/Y H:i:s') }}</div>
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
                @if($orcamento->orcamentoPrestador->fornecedor_cnpj)
                <div class="info-row">
                    <div class="info-label">CNPJ:</div>
                    <div class="info-value">{{ $orcamento->orcamentoPrestador->fornecedor_cnpj }}</div>
                </div>
                @endif
                @if($orcamento->orcamentoPrestador->fornecedor_endereco)
                <div class="info-row">
                    <div class="info-label">Endereço:</div>
                    <div class="info-value">{{ $orcamento->orcamentoPrestador->fornecedor_endereco }}</div>
                </div>
                @endif
                @if($orcamento->orcamentoPrestador->fornecedor_telefone)
                <div class="info-row">
                    <div class="info-label">Telefone:</div>
                    <div class="info-value">{{ $orcamento->orcamentoPrestador->fornecedor_telefone }}</div>
                </div>
                @endif
                @if($orcamento->orcamentoPrestador->fornecedor_email)
                <div class="info-row">
                    <div class="info-label">E-mail:</div>
                    <div class="info-value">{{ $orcamento->orcamentoPrestador->fornecedor_email }}</div>
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
                @if($orcamento->orcamentoPrestador->percentual_lucro)
                <div class="info-row">
                    <div class="info-label">Percentual de Lucro:</div>
                    <div class="info-value">{{ number_format($orcamento->orcamentoPrestador->percentual_lucro, 2, ',', '.') }}%</div>
                </div>
                @endif
                @if($orcamento->orcamentoPrestador->percentual_impostos)
                <div class="info-row">
                    <div class="info-label">Percentual de Impostos:</div>
                    <div class="info-value">{{ number_format($orcamento->orcamentoPrestador->percentual_impostos, 2, ',', '.') }}%</div>
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
                <div class="financial-row total-highlight">
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
            <h3 class="section-title">Dados Operacionais</h3>
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
                @php
                    $kmTotal = ($orcamento->orcamentoAumentoKm->km_dia ?? 0) * ($orcamento->orcamentoAumentoKm->qtd_dias ?? 0);
                @endphp
                <div class="info-row">
                    <div class="info-label">KM Total/Mês:</div>
                    <div class="info-value">{{ number_format($kmTotal, 0, ',', '.') }} km</div>
                </div>
            </div>
        </div>
        
        <div class="section">
            <h3 class="section-title">Cálculos de Combustível</h3>
            <div class="info-grid">
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
                @php
                    $consumo = $orcamento->orcamentoAumentoKm->combustivel_km_litro ?? 1;
                    $totalCombustivel = $consumo > 0 ? $kmTotal / $consumo : 0;
                    $custoTotalCombustivel = $totalCombustivel * ($orcamento->orcamentoAumentoKm->valor_combustivel ?? 0);
                @endphp
                <div class="info-row">
                    <div class="info-label">Total Combustível:</div>
                    <div class="info-value">{{ number_format($totalCombustivel, 2, ',', '.') }} litros</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Custo Total Combustível:</div>
                    <div class="info-value">R$ {{ number_format($custoTotalCombustivel, 2, ',', '.') }}</div>
                </div>
            </div>
        </div>
        
        <div class="section">
            <h3 class="section-title">Hora Extra</h3>
            <div class="info-grid">
                @if($orcamento->orcamentoAumentoKm->hora_extra)
                <div class="info-row">
                    <div class="info-label">Valor Hora Extra:</div>
                    <div class="info-value">R$ {{ number_format($orcamento->orcamentoAumentoKm->hora_extra, 2, ',', '.') }}</div>
                </div>
                @endif
                <div class="info-row">
                    <div class="info-label">Custo Total (Combustível + HE):</div>
                    <div class="info-value">R$ {{ number_format($custoTotalCombustivel + ($orcamento->orcamentoAumentoKm->hora_extra ?? 0), 2, ',', '.') }}</div>
                </div>
            </div>
            
            <!-- Resumo Financeiro Aumento KM -->
            @if($orcamento->orcamentoAumentoKm->valor_total)
            <div class="financial-summary">
                <h4 style="margin-bottom: 10px; color: #2d3748;">Resumo Financeiro</h4>
                <div class="financial-row total-highlight">
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
                <h4 style="margin-bottom: 10px; color: #2d3748;">Resumo Financeiro</h4>
                <div class="financial-row total-highlight">
                    <span class="financial-label">Valor Total:</span>
                    <span class="financial-value">R$ {{ number_format($orcamento->orcamentoProprioNovaRota->valor_total, 2, ',', '.') }}</span>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Observações do Aumento de KM -->
        @if($orcamento->tipo_orcamento === 'aumento_km' && $orcamento->orcamentoAumentoKm && $orcamento->orcamentoAumentoKm->observacoes)
        <div class="section">
            <h3 class="section-title">Observações do Aumento de KM</h3>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Observações:</div>
                    <div class="info-value">{{ $orcamento->orcamentoAumentoKm->observacoes }}</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Observações Gerais -->
        @if($orcamento->observacoes)
        <div class="observacoes">
            <h4>Observações</h4>
            <p>{{ $orcamento->observacoes }}</p>
        </div>
        @endif

        <!-- Informações do Sistema -->
        <div class="section">
            <h3 class="section-title">Informações do Sistema</h3>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Documento Gerado em:</div>
                    <div class="info-value">{{ now()->format('d/m/Y H:i:s') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Versão do Sistema:</div>
                    <div class="info-value">OBM Logística v1.0</div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Este documento foi gerado automaticamente pelo Sistema de Orçamentos em {{ now()->format('d/m/Y H:i:s') }}</p>
            <p>OBM Logística e Transportes - Todos os direitos reservados</p>
        </div>
    </div>
</body>
</html>