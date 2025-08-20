@extends('layouts.admin')

@section('title', 'Detalhes da Base')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detalhes da Base</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.bases.edit', $base->id) }}" 
               class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <button onclick="toggleStatus({{ $base->id }}, {{ $base->ativo ? 'false' : 'true' }})" 
                    class="{{ $base->ativo ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas {{ $base->ativo ? 'fa-toggle-on' : 'fa-toggle-off' }} mr-2"></i>
                {{ $base->ativo ? 'Desativar' : 'Ativar' }}
            </button>
            <a href="{{ route('admin.bases.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações Principais -->
        <div class="lg:col-span-2">
            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Informações Básicas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Base (Cidade)</label>
                        <p class="text-gray-900 font-medium">{{ $base->city ?? $base->nome }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">UF</label>
                        <p class="text-gray-900 font-mono">{{ $base->uf ?? $base->estado }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Regional</label>
                        <p class="text-gray-900">{{ $base->regional ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Sigla</label>
                        <p class="text-gray-900 font-mono">{{ $base->sigla ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Supervisor</label>
                        <p class="text-gray-900">{{ $base->supervisor ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status</label>
                        <p class="text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $base->active ?? $base->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $base->active ?? $base->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>


        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Datas -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Informações do Sistema</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Criado em</label>
                        <p class="text-gray-900">{{ $base->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Atualizado em</label>
                        <p class="text-gray-900">{{ $base->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Orçamentos Associados -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Orçamentos Associados</h3>
                
                @if($base->orcamentos->count() > 0)
                    <div class="space-y-2">
                        @foreach($base->orcamentos->take(5) as $orcamento)
                            <div class="flex justify-between items-center p-2 bg-white rounded border">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">#{{ $orcamento->id }}</p>
                                    <p class="text-xs text-gray-500">{{ $orcamento->created_at->format('d/m/Y') }}</p>
                                </div>
                                <a href="{{ route('admin.orcamentos.show', $orcamento->id) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    Ver detalhes
                                </a>
                            </div>
                        @endforeach
                        
                        @if($base->orcamentos->count() > 5)
                            <p class="text-sm text-gray-500 text-center mt-2">
                                E mais {{ $base->orcamentos->count() - 5 }} orçamentos...
                            </p>
                        @endif
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Nenhum orçamento encontrado para esta base.</p>
                @endif
            </div>

            <!-- Estatísticas -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Estatísticas</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total de Orçamentos:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $base->orcamentos->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Orçamentos Ativos:</span>
                        <span class="text-sm font-medium text-green-600">
                            {{ $base->orcamentos->where('ativo', true)->count() }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Orçamentos Inativos:</span>
                        <span class="text-sm font-medium text-red-600">
                            {{ $base->orcamentos->where('ativo', false)->count() }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Mapa (se houver coordenadas) -->
            @if($base->latitude && $base->longitude)
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Localização no Mapa</h3>
                    <div class="aspect-w-16 aspect-h-9">
                        <iframe 
                            src="https://www.google.com/maps/embed/v1/place?key=YOUR_API_KEY&q={{ $base->latitude }},{{ $base->longitude }}&zoom=15" 
                            class="w-full h-48 rounded border" 
                            frameborder="0" 
                            style="border:0" 
                            allowfullscreen="" 
                            aria-hidden="false" 
                            tabindex="0">
                        </iframe>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        Coordenadas: {{ $base->latitude }}, {{ $base->longitude }}
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleStatus(id, status) {
    if (confirm('Tem certeza que deseja alterar o status desta base?')) {
        fetch(`/admin/bases/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ ativo: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao alterar status: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao alterar status da base.');
        });
    }
}
</script>
@endsection