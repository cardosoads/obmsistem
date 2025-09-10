@extends('layouts.admin')

@section('title', 'Editar Combustível')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Editar Combustível</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.combustiveis.show', $combustivel->id) }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-eye mr-2"></i>Visualizar
            </a>
            <a href="{{ route('admin.combustiveis.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <form action="{{ route('admin.combustiveis.update', $combustivel->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Dados Básicos -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Dados Básicos</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="base_id" class="block text-sm font-medium text-gray-700 mb-2">Base *</label>
                    <select id="base_id" 
                            name="base_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('base_id') border-red-500 @enderror" 
                            required>
                        <option value="">Selecione a base</option>
                        @foreach($bases as $base)
                            <option value="{{ $base->id }}" {{ old('base_id', $combustivel->base_id) == $base->id ? 'selected' : '' }}>
                                {{ $base->name }} - {{ $base->uf }}
                            </option>
                        @endforeach
                    </select>
                    @error('base_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="convenio" class="block text-sm font-medium text-gray-700 mb-2">Convênio *</label>
                    <input type="text" 
                           id="convenio" 
                           name="convenio" 
                           value="{{ old('convenio', $combustivel->convenio) }}"
                           placeholder="Ex: Posto Shell, Posto Ipiranga"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('convenio') border-red-500 @enderror" 
                           required>
                    @error('convenio')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="preco_litro" class="block text-sm font-medium text-gray-700 mb-2">Preço por Litro (R$) *</label>
                    <input type="number" 
                           id="preco_litro" 
                           name="preco_litro" 
                           value="{{ old('preco_litro', $combustivel->preco_litro) }}"
                           step="0.001"
                           min="0"
                           placeholder="0,000"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('preco_litro') border-red-500 @enderror" 
                           required>
                    @error('preco_litro')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="active" 
                            name="active" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('active') border-red-500 @enderror">
                        <option value="1" {{ old('active', $combustivel->active) == '1' ? 'selected' : '' }}>Ativo</option>
                        <option value="0" {{ old('active', $combustivel->active) == '0' ? 'selected' : '' }}>Inativo</option>
                    </select>
                    @error('active')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Comparação de Preços -->
        <div class="bg-blue-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-blue-700 mb-4">Comparação de Preços</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
                <div>
                    <p class="text-sm text-blue-600">Preço Atual</p>
                    <p class="text-xl font-bold text-gray-800">R$ {{ number_format($combustivel->preco_litro, 3, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-blue-600">Preço Médio Geral</p>
                    <p class="text-xl font-bold text-blue-800">R$ {{ number_format($precoMedio, 3, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-blue-600">Menor Preço</p>
                    <p class="text-xl font-bold text-green-600">R$ {{ number_format($menorPreco, 3, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-blue-600">Maior Preço</p>
                    <p class="text-xl font-bold text-red-600">R$ {{ number_format($maiorPreco, 3, ',', '.') }}</p>
                </div>
            </div>
            
            @php
                $variacao = $precoMedio > 0 ? (($combustivel->preco_litro - $precoMedio) / $precoMedio) * 100 : 0;
            @endphp
            
            <div class="mt-4 text-center">
                @if($variacao > 0)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        <i class="fas fa-arrow-up mr-1"></i>{{ number_format($variacao, 1, ',', '.') }}% acima da média
                    </span>
                @elseif($variacao < 0)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="fas fa-arrow-down mr-1"></i>{{ number_format(abs($variacao), 1, ',', '.') }}% abaixo da média
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                        <i class="fas fa-minus mr-1"></i>Preço na média
                    </span>
                @endif
            </div>
        </div>

        <!-- Informações de Auditoria -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Informações de Auditoria</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                <div>
                    <strong>Criado em:</strong> {{ $combustivel->created_at->format('d/m/Y H:i:s') }}
                </div>
                <div>
                    <strong>Última atualização:</strong> {{ $combustivel->updated_at->format('d/m/Y H:i:s') }}
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.combustiveis.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg transition duration-200">
                <i class="fas fa-save mr-2"></i>Atualizar
            </button>
        </div>
    </form>
</div>

<!-- Outros Combustíveis na Mesma Base -->
@if($outrosCombustiveis->count() > 0)
<div class="bg-white rounded-lg shadow-md p-6 mt-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">Outros Combustíveis na Base {{ $combustivel->base->name ?? '' }}</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Convênio
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Preço/Litro
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Diferença
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($outrosCombustiveis as $outro)
                    @php
                        $diferenca = $outro->preco_litro - $combustivel->preco_litro;
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $outro->convenio }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 font-mono">
                            R$ {{ number_format($outro->preco_litro, 3, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($diferenca > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    +R$ {{ number_format($diferenca, 3, ',', '.') }}
                                </span>
                            @elseif($diferenca < 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    R$ {{ number_format($diferenca, 3, ',', '.') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Igual
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $outro->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $outro->active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.combustiveis.show', $outro->id) }}" 
                               class="text-blue-600 hover:text-blue-900" 
                               title="Ver Combustível">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Informações Adicionais -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="fas fa-info-circle text-blue-400 text-lg"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">Informações Importantes</h3>
            <div class="mt-2 text-sm text-blue-700">
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Base:</strong> Selecione a base onde o convênio está localizado</li>
                    <li><strong>Convênio:</strong> Nome do posto ou empresa fornecedora de combustível</li>
                    <li><strong>Preço por Litro:</strong> Valor atual do combustível por litro</li>
                    <li><strong>Unicidade:</strong> Não é possível ter o mesmo convênio duplicado na mesma base</li>
                    <li><strong>Histórico:</strong> Alterações de preço são registradas para controle</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
// Validação de preço em tempo real
document.getElementById('preco_litro').addEventListener('input', function() {
    const preco = parseFloat(this.value);
    const precoMedio = {{ $precoMedio }};
    const precoAtual = {{ $combustivel->preco_litro }};
    
    if (preco && precoMedio > 0) {
        const variacao = ((preco - precoMedio) / precoMedio) * 100;
        const diferencaAtual = preco - precoAtual;
        
        let alertClass = '';
        let alertText = '';
        
        if (variacao > 10) {
            alertClass = 'bg-red-100 border-red-400 text-red-700';
            alertText = `Preço ${variacao.toFixed(1)}% acima da média`;
        } else if (variacao < -10) {
            alertClass = 'bg-green-100 border-green-400 text-green-700';
            alertText = `Preço ${Math.abs(variacao).toFixed(1)}% abaixo da média`;
        } else {
            alertClass = 'bg-blue-100 border-blue-400 text-blue-700';
            alertText = `Preço dentro da faixa normal (${variacao.toFixed(1)}% da média)`;
        }
        
        if (Math.abs(diferencaAtual) > 0.001) {
            const sinal = diferencaAtual > 0 ? '+' : '';
            alertText += ` | Alteração: ${sinal}R$ ${diferencaAtual.toFixed(3)}`;
        }
        
        // Remover alerta anterior
        const alertAnterior = document.getElementById('precoAlert');
        if (alertAnterior) {
            alertAnterior.remove();
        }
        
        // Adicionar novo alerta
        const alert = document.createElement('div');
        alert.id = 'precoAlert';
        alert.className = `border px-4 py-3 rounded mt-2 ${alertClass}`;
        alert.innerHTML = `<span class="block sm:inline">${alertText}</span>`;
        
        this.parentNode.appendChild(alert);
    }
});
</script>
@endsection