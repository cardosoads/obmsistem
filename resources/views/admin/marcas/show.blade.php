@extends('layouts.admin')

@section('title', 'Detalhes da Marca')

@section('content')
<!-- Header com Gradiente -->
<div class="rounded-xl shadow-lg overflow-hidden mb-6" style="background: linear-gradient(135deg, #1E3951 0%, #2A4A66 100%);">
    <div class="p-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-4" style="background: rgba(248, 171, 20, 0.2);">
                    <i class="fas fa-tags text-xl" style="color: #F8AB14;"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white mb-1">Detalhes da Marca</h1>
                    <p class="text-blue-100">Visualize e gerencie as informações da marca</p>
                </div>
            </div>
            
            <a href="{{ route('admin.marcas.index') }}" 
               class="inline-flex items-center px-4 py-2 rounded-xl font-semibold transition-all duration-300 hover:shadow-lg" 
               style="background: rgba(248, 171, 20, 0.2); color: #F8AB14; border: 1px solid rgba(248, 171, 20, 0.3); hover:background: #F8AB14; hover:color: #1E3951;">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>
</div>

<!-- Breadcrumbs -->
<nav class="mb-6">
    <ol class="flex items-center space-x-2 text-sm">
        <li><a href="{{ route('admin.dashboard') }}" class="hover:underline" style="color: #1E3951;">Dashboard</a></li>
        <li><i class="fas fa-chevron-right text-xs" style="color: #1E3951;"></i></li>
        <li><a href="{{ route('admin.marcas.index') }}" class="hover:underline" style="color: #1E3951;">Marcas</a></li>
        <li><i class="fas fa-chevron-right text-xs" style="color: #1E3951;"></i></li>
        <li class="font-semibold" style="color: #F8AB14;">{{ $marca->name }}</li>
    </ol>
</nav>

<!-- Botões de Ação -->
<div class="flex justify-end space-x-3 mb-6">
    <a href="{{ route('admin.marcas.edit', $marca->id) }}" 
       class="inline-flex items-center px-4 py-2 rounded-xl font-semibold transition-all duration-300 hover:shadow-lg" 
       style="background: rgba(30, 57, 81, 0.1); color: #1E3951; hover:background: #1E3951; hover:color: white;">
        <i class="fas fa-edit mr-2"></i>Editar
    </a>
    <button onclick="toggleStatus({{ $marca->id }}, {{ $marca->ativo ? 'false' : 'true' }})" 
            class="inline-flex items-center px-4 py-2 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg" 
            style="background: {{ $marca->ativo ? 'linear-gradient(135deg, #dc2626 0%, #b91c1c 100%)' : 'linear-gradient(135deg, #16a34a 0%, #15803d 100%)' }};">
        <i class="fas fa-{{ $marca->ativo ? 'times-circle' : 'check-circle' }} mr-2"></i>
        {{ $marca->ativo ? 'Desativar' : 'Ativar' }}
    </button>
</div>

<!-- Card Principal -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6">
        <!-- Informações Básicas -->
        <div class="lg:col-span-2">
            <!-- Dados da Marca -->
            <div class="mb-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3" style="background: rgba(30, 57, 81, 0.1);">
                        <i class="fas fa-tags" style="color: #1E3951;"></i>
                    </div>
                    <h3 class="text-lg font-semibold" style="color: #1E3951;">Informações da Marca</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 rounded-xl" style="background: rgba(30, 57, 81, 0.02); border: 1px solid rgba(30, 57, 81, 0.1);">
                        <label class="block text-sm font-semibold mb-2" style="color: #1E3951;">MARCA</label>
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3" style="background: rgba(248, 171, 20, 0.1);">
                                <i class="fas fa-tag text-sm" style="color: #F8AB14;"></i>
                            </div>
                            <p class="text-lg font-bold" style="color: #1E3951;">{{ $marca->name }}</p>
                        </div>
                    </div>
                    
                    <div class="p-4 rounded-xl" style="background: rgba(30, 57, 81, 0.02); border: 1px solid rgba(30, 57, 81, 0.1);">
                        <label class="block text-sm font-semibold mb-2" style="color: #1E3951;">MERCADO</label>
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3" style="background: rgba(248, 171, 20, 0.1);">
                                <i class="fas fa-store text-sm" style="color: #F8AB14;"></i>
                            </div>
                            <p class="text-lg font-bold" style="color: #1E3951;">{{ $marca->mercado }}</p>
                        </div>
                    </div>
                    
                    <div class="p-4 rounded-xl" style="background: rgba(30, 57, 81, 0.02); border: 1px solid rgba(30, 57, 81, 0.1);">
                        <label class="block text-sm font-semibold mb-2" style="color: #1E3951;">STATUS</label>
                        <div class="flex items-center">
                            @if($marca->ativo)
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3" style="background: rgba(34, 197, 94, 0.1);">
                                    <i class="fas fa-check-circle text-sm text-green-600"></i>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                    <i class="fas fa-circle text-xs mr-2"></i>Ativo
                                </span>
                            @else
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3" style="background: rgba(239, 68, 68, 0.1);">
                                    <i class="fas fa-times-circle text-sm text-red-600"></i>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                    <i class="fas fa-circle text-xs mr-2"></i>Inativo
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações do Sistema -->
            <div>
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3" style="background: rgba(248, 171, 20, 0.1);">
                        <i class="fas fa-clock" style="color: #F8AB14;"></i>
                    </div>
                    <h3 class="text-lg font-semibold" style="color: #1E3951;">Informações do Sistema</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 rounded-xl" style="background: rgba(30, 57, 81, 0.02); border: 1px solid rgba(30, 57, 81, 0.1);">
                        <label class="block text-sm font-semibold mb-2" style="color: #1E3951;">CRIADO EM</label>
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3" style="background: rgba(248, 171, 20, 0.1);">
                                <i class="fas fa-calendar-plus text-sm" style="color: #F8AB14;"></i>
                            </div>
                            <p class="font-medium" style="color: #1E3951;">{{ $marca->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                    
                    <div class="p-4 rounded-xl" style="background: rgba(30, 57, 81, 0.02); border: 1px solid rgba(30, 57, 81, 0.1);">
                        <label class="block text-sm font-semibold mb-2" style="color: #1E3951;">ÚLTIMA ATUALIZAÇÃO</label>
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3" style="background: rgba(248, 171, 20, 0.1);">
                                <i class="fas fa-calendar-edit text-sm" style="color: #F8AB14;"></i>
                            </div>
                            <p class="font-medium" style="color: #1E3951;">{{ $marca->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card de Resumo -->
        <div>
            <div class="p-6 rounded-xl" style="background: linear-gradient(135deg, rgba(30, 57, 81, 0.05) 0%, rgba(248, 171, 20, 0.05) 100%); border: 1px solid rgba(30, 57, 81, 0.1);">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3" style="background: rgba(248, 171, 20, 0.2);">
                        <i class="fas fa-chart-bar" style="color: #F8AB14;"></i>
                    </div>
                    <h3 class="text-lg font-semibold" style="color: #1E3951;">Resumo</h3>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 rounded-lg" style="background: rgba(255, 255, 255, 0.7);">
                        <div class="flex items-center">
                            <i class="fas fa-tag mr-2" style="color: #1E3951;"></i>
                            <span class="text-sm font-medium" style="color: #1E3951;">ID da Marca</span>
                        </div>
                        <span class="font-bold" style="color: #F8AB14;">#{{ $marca->id }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 rounded-lg" style="background: rgba(255, 255, 255, 0.7);">
                        <div class="flex items-center">
                            <i class="fas fa-calendar mr-2" style="color: #1E3951;"></i>
                            <span class="text-sm font-medium" style="color: #1E3951;">Dias no Sistema</span>
                        </div>
                        <span class="font-bold" style="color: #F8AB14;">{{ $marca->created_at->diffInDays(now()) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleStatus(id, status) {
    if (confirm('Tem certeza que deseja alterar o status desta marca?')) {
        fetch(`/admin/marcas/${id}/toggle-status`, {
            method: 'PATCH',
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
                alert('Erro ao alterar status da marca.');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao alterar status da marca.');
        });
    }
}
</script>
@endsection