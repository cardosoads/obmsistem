@extends('layouts.admin')

@section('title', 'Detalhes da Base')

@section('content')
<!-- Header com gradiente -->
<div class="rounded-xl shadow-lg mb-6" style="background: linear-gradient(135deg, #1E3951 0%, #2A4A66 100%);">
    <div class="p-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-xl flex items-center justify-center" style="background: rgba(248, 171, 20, 0.2);">
                    <i class="fas fa-map-marker-alt text-3xl" style="color: #F8AB14;"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white">{{ $base->name }}</h1>
                    <p class="text-blue-100">{{ $base->uf }} • {{ $base->regional ?? 'Regional não definida' }}</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.bases.edit', $base->id) }}" 
                   class="inline-flex items-center px-4 py-2 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg" 
                   style="background: #F8AB14;">
                    <i class="fas fa-edit mr-2"></i>Editar
                </a>
                <button onclick="toggleStatus({{ $base->id }}, {{ $base->active ? 'false' : 'true' }})" 
                        class="inline-flex items-center px-4 py-2 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg" 
                        style="background: {{ $base->active ? '#F8AB14' : '#1E3951' }};">
                    <i class="fas {{ $base->active ? 'fa-toggle-on' : 'fa-toggle-off' }} mr-2"></i>
                    {{ $base->active ? 'Desativar' : 'Ativar' }}
                </button>
                <button onclick="deleteBase({{ $base->id }})" 
                        class="inline-flex items-center px-4 py-2 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg" 
                        style="background: rgba(255, 255, 255, 0.2);">
                    <i class="fas fa-trash mr-2"></i>Excluir
                </button>
                <a href="{{ route('admin.bases.index') }}" 
                   class="inline-flex items-center px-4 py-2 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg" 
                   style="background: rgba(255, 255, 255, 0.2);">
                    <i class="fas fa-arrow-left mr-2"></i>Voltar
                </a>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações Principais -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="p-6 border-b" style="background: linear-gradient(135deg, rgba(30, 57, 81, 0.05), rgba(30, 57, 81, 0.1)); border-color: rgba(30, 57, 81, 0.1);">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: #1E3951;">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold" style="color: #1E3951;">Informações Básicas</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-500">Base (Cidade)</label>
                            <p class="text-lg font-semibold" style="color: #1E3951;">{{ $base->name }}</p>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-500">UF</label>
                            <p class="text-lg font-mono font-semibold" style="color: #1E3951;">{{ $base->uf }}</p>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-500">Regional</label>
                            <p class="text-lg font-medium text-gray-700">{{ $base->regional ?? '-' }}</p>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-500">Sigla</label>
                            <p class="text-lg font-mono font-semibold" style="color: #1E3951;">{{ $base->sigla ?? '-' }}</p>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-500">Supervisor</label>
                            <p class="text-lg font-medium text-gray-700">{{ $base->supervisor ?? '-' }}</p>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold text-white" 
                                  style="background: {{ $base->active ? '#F8AB14' : '#1E3951' }};">
                                <i class="fas {{ $base->active ? 'fa-check-circle' : 'fa-times-circle' }} mr-2"></i>
                                {{ $base->active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Informações do Sistema -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-4 border-b" style="background: linear-gradient(135deg, rgba(30, 57, 81, 0.05), rgba(30, 57, 81, 0.1)); border-color: rgba(30, 57, 81, 0.1);">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: #1E3951;">
                            <i class="fas fa-clock text-white text-sm"></i>
                        </div>
                        <h3 class="text-base font-semibold" style="color: #1E3951;">Informações do Sistema</h3>
                    </div>
                </div>
                <div class="p-4 space-y-4">
                    <div class="flex items-center justify-between py-2 border-b" style="border-color: rgba(30, 57, 81, 0.1);">
                        <span class="text-sm text-gray-600">Criado em:</span>
                        <span class="text-sm font-semibold" style="color: #1E3951;">{{ $base->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm text-gray-600">Atualizado em:</span>
                        <span class="text-sm font-semibold" style="color: #1E3951;">{{ $base->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Status da Base -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-4 border-b" style="background: linear-gradient(135deg, rgba(30, 57, 81, 0.05), rgba(30, 57, 81, 0.1)); border-color: rgba(30, 57, 81, 0.1);">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: {{ $base->active ? '#F8AB14' : '#1E3951' }};">
                            <i class="fas {{ $base->active ? 'fa-check-circle' : 'fa-times-circle' }} text-white text-sm"></i>
                        </div>
                        <h3 class="text-base font-semibold" style="color: #1E3951;">Status da Base</h3>
                    </div>
                </div>
                <div class="p-4">
                    <div class="text-center">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3" style="background: {{ $base->active ? 'rgba(248, 171, 20, 0.1)' : 'rgba(30, 57, 81, 0.1)' }};">
                            <i class="fas {{ $base->active ? 'fa-check-circle' : 'fa-times-circle' }} text-2xl" style="color: {{ $base->active ? '#F8AB14' : '#1E3951' }};"></i>
                        </div>
                        <p class="text-lg font-semibold mb-1" style="color: {{ $base->active ? '#F8AB14' : '#1E3951' }};">
                            {{ $base->active ? 'Base Ativa' : 'Base Inativa' }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $base->active ? 'Operando normalmente' : 'Fora de operação' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Mapa (se houver coordenadas) -->
            @if($base->latitude && $base->longitude)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-4 border-b" style="background: linear-gradient(135deg, rgba(30, 57, 81, 0.05), rgba(30, 57, 81, 0.1)); border-color: rgba(30, 57, 81, 0.1);">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: #F8AB14;">
                                <i class="fas fa-map text-white text-sm"></i>
                            </div>
                            <h3 class="text-base font-semibold" style="color: #1E3951;">Localização no Mapa</h3>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="aspect-w-16 aspect-h-9 mb-3">
                            <iframe 
                                src="https://www.google.com/maps/embed/v1/place?key=YOUR_API_KEY&q={{ $base->latitude }},{{ $base->longitude }}&zoom=15" 
                                class="w-full h-48 rounded-xl" 
                                frameborder="0" 
                                style="border:0" 
                                allowfullscreen="" 
                                aria-hidden="false" 
                                tabindex="0">
                            </iframe>
                        </div>
                        <div class="text-center">
                            <p class="text-xs font-medium" style="color: #1E3951;">
                                <i class="fas fa-map-pin mr-1" style="color: #F8AB14;"></i>
                                {{ $base->latitude }}, {{ $base->longitude }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleStatus(id, status) {
    if (confirm('Tem certeza que deseja alterar o status desta base?')) {
        fetch(`/admin/bases/${id}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ active: status })
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

function deleteBase(id) {
    if (confirm('Tem certeza que deseja excluir esta base? Esta ação não pode ser desfeita.')) {
        fetch(`/admin/bases/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/admin/bases';
            } else {
                alert('Erro ao excluir base: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao excluir base.');
        });
    }
}
</script>
@endsection