@extends('layouts.app')
@section('page_title', 'Dashboard')

@section('content')
<div class="container-fluid px-2">

    <form action="{{ route('dashboard') }}" method="GET" class="row g-3 align-items-end">
        {{-- SEM @csrf AQUI PARA EVITAR ERROS DE SESSÃO NO GET --}}
        <form id="filtroForm" action="{{ route('dashboard') }}" method="GET" class="row g-3 align-items-end">

            {{-- 1. Busca por Nome --}}
            <div class="col-md-3">
                <label class="form-label text-secondary text-uppercase fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">Buscar Material</label>
                <div class="position-relative">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                    <input type="text" name="search"
                        class="form-control bg-dark border-secondary text-white ps-5 shadow-none"
                        placeholder="Buscar por nome..."
                        value="{{ request('search') }}"
                        style="border-radius: 10px; height: 48px; border-color: rgba(255,255,255,0.1) !important;">
                </div>
            </div>

            {{-- 2. Filtro Categoria --}}
            <div class="col-md-3">
                <label class="nav-label">Filtrar Categoria</label>
                <select name="categoria_id" class="tom-select" onchange="this.form.submit()">
                    <option value="">TODAS AS CATEGORIAS</option>
                    @foreach($categorias as $cat)
                    <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                        {{ strtoupper($cat->nome) }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- 3. Filtro Status --}}
            <div class="col-md-2">
                <label class="form-label text-secondary text-uppercase fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">Situação</label>
                <select name="status" class="form-select bg-dark border-secondary text-white shadow-none"
                    style="border-radius: 10px; height: 48px; border-color: rgba(255,255,255,0.1) !important;"
                    onchange="this.form.submit()">
                    <option value="">TODOS</option>
                    <option value="baixo" {{ request('status') == 'baixo' ? 'selected' : '' }}>ESTOQUE BAIXO</option>
                    <option value="ok" {{ request('status') == 'ok' ? 'selected' : '' }}>EM DIA</option>
                </select>
            </div>

            {{-- 4. Limite Alerta --}}
            <div class="col-md-1">
                <label class="form-label text-secondary text-uppercase fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">Alerta</label>
                <input type="number" name="limite" class="form-control bg-dark border-secondary text-white shadow-none text-center"
                    value="{{ request('limite', 5) }}"
                    style="border-radius: 10px; height: 48px; border-color: rgba(255,255,255,0.1) !important;">
            </div>

            {{-- 5. Botão PDF (Ação via Link para não dar conflito de POST/GET) --}}
            <div class="col-md-3 text-end">
                <button type="button" onclick="gerarPdf()" class="btn btn-danger d-inline-flex align-items-center justify-content-center fw-bold w-100"
                    style="border-radius: 10px; height: 48px; background: linear-gradient(45deg, #dc3545, #922b21); border: none;">
                    <i class="fas fa-file-pdf me-2"></i> GERAR ORÇAMENTO PDF
                </button>
            </div>
        </form>
</div>



<div class="row pt-3 g-4 mb-5">
    {{-- Total de Itens - Azul Clarinho --}}
    <div class="col-md-4">
        <div class="p-4 d-flex flex-column justify-content-between"
            style="background: linear-gradient(135deg, #2ba3f9 0%, #1a7bbd 100%); border-radius: 30px; min-height: 160px; box-shadow: 0 5px 10px rgba(43, 163, 249, 0.2);">
            <div class="d-flex justify-content-between align-items-start">
                <span class="text-white text-uppercase fw-bold" style="font-size: 0.8rem; letter-spacing: 1px; opacity: 0.9;">Total de Itens</span>
                <i class="fas fa-boxes text-white opacity-50"></i>
            </div>
            <h1 class="fw-bold m-0 text-white" style="font-family: 'Inter'; font-size: 2.5rem;">{{ $totalProdutos }}</h1>
            <div class="mt-2" style="height: 4px; background: rgba(255,255,255,0.2); border-radius: 2px;"></div>
        </div>
    </div>

    {{-- Volume em Estoque - Verde --}}
    <div class="col-md-4">
        <div class="p-4 d-flex flex-column justify-content-between"
            style="background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%); border-radius: 30px; min-height: 160px; box-shadow: 0 5px 10px  rgba(0, 176, 155, 0.2);">
            <div class="d-flex justify-content-between align-items-start">
                <span class="text-white text-uppercase fw-bold" style="font-size: 0.8rem; letter-spacing: 1px; opacity: 0.9;">Volume em Estoque</span>
                <i class="fas fa-layer-group text-white opacity-50"></i>
            </div>
            <h1 class="fw-bold m-0 text-white" style="font-family: 'Inter'; font-size: 2.5rem;">{{ $itensEmEstoque }}</h1>
            <div class="mt-2" style="height: 4px; background: rgba(255,255,255,0.2); border-radius: 2px;"></div>
        </div>
    </div>

    {{-- Alertas Críticos - Vermelho Intenso --}}
    <div class="col-md-4">
        <div class="p-4 d-flex flex-column justify-content-between"
            style="background: linear-gradient(135deg, #e52d27 0%, #b31217 100%); border-radius: 30px; min-height: 160px; box-shadow: 0 5px 10px  rgba(229, 45, 39, 0.3);">
            <div class="d-flex justify-content-between align-items-start">
                <span class="text-white text-uppercase fw-bold" style="font-size: 0.8rem; letter-spacing: 1px; opacity: 0.9;">Alertas Críticos</span>
                <i class="fas fa-exclamation-triangle text-white opacity-50"></i>
            </div>
            <h1 class="fw-bold m-0 text-white" style="font-family: 'Inter'; font-size: 2.5rem;">{{ $estoqueBaixo->count() }}</h1>
            <div class="mt-2" style="height: 4px; background: rgba(255,255,255,0.3); border-radius: 2px;"></div>
        </div>
    </div>
</div>

{{-- 4. SEÇÕES LADO A LADO: COMPRAS E MOVIMENTAÇÕES --}}
<div class="row g-4">

    {{-- PRECISAM DE COMPRA (ESTILO IGUAL AO DE MOVIMENTAÇÕES) --}}
    <div class="col-md-6">
        <div class="p-4" style="background: rgba(255,255,255,0.01);  border-radius: 40px;">
            <h5 class="mb-4 text-uppercase fw-bold" style="font-family: 'Oswald'; font-size: 1rem; color: #dc3545;">
                <i class="fas fa-shopping-basket me-2"></i> Precisam de Compra
            </h5>
            <div class="list-group list-group-flush bg-transparent">
                @forelse($estoqueBaixo as $item)
                <div class="list-group-item bg-transparent border-bottom border-dark px-0 py-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="me-3 d-flex align-items-center justify-content-center rounded-circle"
                            style="width: 35px; height: 35px; background: rgba(220, 53, 69, 0.1); color: #dc3545;">
                            <i class="fas fa-exclamation"></i>
                        </div>
                        <div>
                            <h6 class="m-0 text-white" style="font-size: 0.95rem;">{{ $item->nome }}</h6>
                            <small class="text-secondary">{{ $item->categoria->nome ?? 'Geral' }}</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="fw-bold text-danger">{{ $item->quantidade }}</span>
                        <small class="text-muted d-block" style="font-size: 0.7rem;">UNIDADES</small>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-secondary">Tudo em dia! ✅</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ÚLTIMAS MOVIMENTAÇÕES --}}

    <div class="col-md-6">
        <div class="p-4" style="background: rgba(255,255,255,0.01); border-radius: 40px;">
            <h5 class="mb-4 text-uppercase fw-bold text-info" style="font-family: 'Oswald'; font-size: 1rem;">
                <i class="fas fa-history me-2"></i> Últimas Atividades
            </h5>

            <div class="list-group list-group-flush bg-transparent">

                @if($ultimasMovimentacoes->isEmpty())
                <div class="text-center py-5 text-secondary">
                    <i class="fas fa-inbox fa-2x mb-3 opacity-50"></i>
                    <p>Nenhuma movimentação registrada ainda.</p>
                </div>
                @else

                {{-- <p class="text-white">Registros no banco: {{ \App\Models\Movimentacao::count() }}</p> --}}

                @foreach($ultimasMovimentacoes->take(4) as $mov)
                <div class="list-group-item bg-transparent border-bottom border-dark px-0 py-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="me-3 d-flex align-items-center justify-content-center rounded-circle"
                            style="width: 35px; height: 35px; 
                                        background: rgba(255,255,255,0.05); 
                                        color: {{ $mov->tipo == 'entrada' ? '#198754' : '#0dcaf0' }};">
                            <i class="fas {{ $mov->tipo == 'entrada' ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                        </div>
                        <div>
                            <h6 class="m-0 text-white" style="font-size: 0.95rem;">
                                {{ $mov->produto->nome ?? 'Produto excluído' }}
                            </h6>
                            <small class="text-secondary">
                                {{ $mov->tipo == 'entrada' ? 'Entrada no Estoque' : 'Saída para Obra' }}
                            </small>
                            @if($mov->observacao)
                            <small class="text-muted d-block">{{ $mov->observacao }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="fw-bold {{ $mov->tipo == 'entrada' ? 'text-success' : 'text-info' }}">
                            {{ $mov->tipo == 'entrada' ? '+' : '-' }}{{ $mov->quantidade }}
                        </span>
                        <small class="text-muted d-block">{{ $mov->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializa todos os TomSelects primeiro
            document.querySelectorAll('.tom-select').forEach((el) => {
                // Armazenamos a instância para uso futuro se necessário
                el.tomselect = new TomSelect(el, {
                    allowEmptyOption: true, // Garante que "TODAS AS CATEGORIAS" funcione
                    create: false,
                    controlInput: null,
                    render: {
                        option: function(data, escape) {
                            return '<div class="option">' + escape(data.text.toUpperCase()) + '</div>';
                        },
                        item: function(data, escape) {
                            return '<div class="item">' + escape(data.text.toUpperCase()) + '</div>';
                        }
                    }
                });
            });

            const form = document.querySelector('form[action*="dashboard"]');

            if (form) {
                // Garante que o ENTER em qualquer campo apenas filtre a tela
                form.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        // Evita o comportamento padrão do Enter (que às vezes tenta submeter arquivos)
                        e.preventDefault();

                        const exportInput = form.querySelector('input[name="export"]');
                        if (exportInput) exportInput.remove();

                        form.submit();
                    }
                });
            }
        });

        function gerarPdf() {
            // Captura os valores de forma segura, tratando caso o elemento não exista
            const search = document.querySelector('input[name="search"]')?.value || '';
            const categoria = document.querySelector('select[name="categoria_id"]')?.value || '';
            const status = document.querySelector('select[name="status"]')?.value || '';
            const limite = document.querySelector('input[name="limite"]')?.value || '';

            // Constrói a URL para nova aba - Evita conflitos de CSRF e estados de formulário
            const params = new URLSearchParams({
                search: search,
                categoria_id: categoria,
                status: status,
                limite: limite,
                export: 'pdf'
            });

            window.open("{{ route('dashboard') }}?" + params.toString(), '_blank');
        }
    </script>
    @endsection