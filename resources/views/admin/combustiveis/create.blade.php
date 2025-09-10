@extends('layouts.admin')

@section('title', 'Novo Combustível')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Novo Combustível</h1>
        <a href="{{ route('admin.combustiveis.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    <form action="{{ route('admin.combustiveis.store') }}" method="POST" class="space-y-6">
        @csrf
        
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
                            <option value="{{ $base->id }}" {{ old('base_id', request('base_id')) == $base->id ? 'selected' : '' }}>
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
                           value="{{ old('convenio') }}"
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
                           value="{{ old('preco_litro') }}"
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
                        <option value="1" {{ old('active', '1') == '1' ? 'selected' : '' }}>Ativo</option>
                        <option value="0" {{ old('active') == '0' ? 'selected' : '' }}>Inativo</option>
                    </select>
                    @error('active')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Informações Adicionais -->
        <div class="bg-blue-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-blue-700 mb-4">Informações sobre Preços</h3>
            <div id="precoInfo" class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
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
        </div>

        <!-- Botões de Ação -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.combustiveis.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg transition duration-200">
                <i class="fas fa-save mr-2"></i>Salvar
            </button>
        </div>
    </form>
</div>

<!-- Combustíveis Existentes na Base Selecionada -->
<div id="combustiveisBase" class="bg-white rounded-lg shadow-md p-6 mt-6" style="display: none;">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">Combustíveis Existentes na Base</h3>
    <div id="listaCombustiveis">
        <!-- Será preenchido via JavaScript -->
    </div>
</div>

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
                    <li><strong>Atualização:</strong> Os preços devem ser atualizados regularmente</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('base_id').addEventListener('change', function() {
    const baseId = this.value;
    const combustiveisDiv = document.getElementById('combustiveisBase');
    const listaCombustiveis = document.getElementById('listaCombustiveis');
    
    if (baseId) {
        // Buscar combustíveis da base selecionada
        fetch(`/admin/combustiveis/base/${baseId}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    let html = '<div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200">';
                    html += '<thead class="bg-gray-50">';
                    html += '<tr>';
                    html += '<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Convênio</th>';
                    html += '<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Preço/Litro</th>';
                    html += '<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>';
                    html += '</tr>';
                    html += '</thead><tbody class="bg-white divide-y divide-gray-200">';
                    
                    data.forEach(combustivel => {
                        const statusClass = combustivel.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                        const statusText = combustivel.active ? 'Ativo' : 'Inativo';
                        
                        html += '<tr>';
                        html += `<td class="px-4 py-2 text-sm text-gray-900">${combustivel.convenio}</td>`;
                        html += `<td class="px-4 py-2 text-sm font-mono text-gray-900">R$ ${parseFloat(combustivel.preco_litro).toLocaleString('pt-BR', {minimumFractionDigits: 3})}</td>`;
                        html += `<td class="px-4 py-2"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass}">${statusText}</span></td>`;
                        html += '</tr>';
                    });
                    
                    html += '</tbody></table></div>';
                    listaCombustiveis.innerHTML = html;
                    combustiveisDiv.style.display = 'block';
                } else {
                    listaCombustiveis.innerHTML = '<p class="text-gray-500 text-center py-4">Nenhum combustível cadastrado para esta base.</p>';
                    combustiveisDiv.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Erro ao buscar combustíveis:', error);
                combustiveisDiv.style.display = 'none';
            });
    } else {
        combustiveisDiv.style.display = 'none';
    }
});

// Validação de preço em tempo real
document.getElementById('preco_litro').addEventListener('input', function() {
    const preco = parseFloat(this.value);
    const precoMedio = {{ $precoMedio }};
    const menorPreco = {{ $menorPreco }};
    const maiorPreco = {{ $maiorPreco }};
    
    if (preco && precoMedio > 0) {
        const variacao = ((preco - precoMedio) / precoMedio) * 100;
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