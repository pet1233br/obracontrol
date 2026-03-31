@extends('layouts.app')
@section('page_title', 'Histórico de Movimentações')

@section('content')
<div class="container-fluid px-2">

    {{-- FILTROS DE AUDITORIA (Substituindo o cabeçalho antigo) --}}
    <div class="mb-3">
        <form action="{{ route('movimentacoes.index') }}" method="GET" class="row g-3 align-items-end">

            {{-- Filtro por Categoria --}}
            <div class="col-md-5">
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

            {{-- Filtro por Data Inicial --}}
            <div class="col-md-3">
                <label class="form-label text-secondary text-uppercase fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">Desde:</label>
                <input type="date" name="data_inicio" class="form-control bg-dark border-secondary text-white shadow-none"
                    value="{{ request('data_inicio') }}"
                    style="border-radius: 10px; height: 48px; border-color: rgba(255,255,255,0.1) !important;">
            </div>

            {{-- Filtro por Data Final --}}
            <div class="col-md-3">
                <label class="form-label text-secondary text-uppercase fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">Até:</label>
                <input type="date" name="data_fim" class="form-control bg-dark border-secondary text-white shadow-none"
                    value="{{ request('data_fim') }}"
                    style="border-radius: 10px; height: 48px; border-color: rgba(255,255,255,0.1) !important;">
            </div>

            {{-- Botão Resetar (Ícone) --}}
            <div class="col-md-2 text-end">
                <a href="{{ route('movimentacoes.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center fw-bold w-100"
                    style="border-radius: 10px; height: 48px; transition: 0.3s;">
                    <i class="fas fa-undo me-2"></i> LIMPAR
                </a>
            </div>
        </form>
    </div>

    <div class="movimentacoes-list">
        @forelse($movimentacoes as $m)
        <div class="card mb-3 border-0 shadow-sm" style="background: rgba(46, 49, 68, 0.1); border-radius: 15px; backdrop-filter: blur(5px); border: 1px solid rgba(255,255,255,0.05) !important;">
            <div class="card-body p-3">
                {{-- USAMOS FLEXBOX PARA FORÇAR UMA LINHA E ALINHAMENTO --}}
                <div class="d-flex align-items-center text-center text-md-start w-100" style="gap: 10px;">

                    {{-- DATA E HORA - LARGURA FIXA E BORDAS SUTIS --}}
                    <div class="flex-shrink-0 border-end border-secondary pe-3" style="width: 15%; --bs-border-opacity: .1;">
                        <div class="text-secondary text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Data / Hora</div>
                        <div class="text-white fw-bold">{{ $m->created_at->format('d/m/Y') }}</div>
                        <small class="text-muted">{{ $m->created_at->format('H:i') }}h</small>
                    </div>

                    {{-- PRODUTO E CATEGORIA - LARGURA FLEXÍVEL --}}
                    <div class="flex-grow-1 border-end border-secondary px-3" style="width: 25%; --bs-border-opacity: .1;">
                        <div class="text-secondary text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Material / Produto</div>
                        <div class="text-warning fw-bold text-uppercase" style="font-family: 'Oswald'; font-size: 1.1rem;">
                            {{ $m->produto->nome ?? 'Excluído' }}
                        </div>
                        <small class="text-secondary">{{ $m->produto->categoria->nome ?? 'Geral' }}</small>
                    </div>

                    {{-- TIPO (ENTRADA/SAÍDA) - LARGURA MENOR E FIXA --}}
                    <div class="flex-shrink-0 border-end border-secondary px-3 text-center" style="width: 15%; --bs-border-opacity: .1;">
                        <div class="text-secondary text-uppercase fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 1px;">Tipo</div>
                        @if($m->tipo == 'entrada')
                        <span class="badge w-100 py-2" style="background: rgba(25, 135, 84, 0.2); color: #28a745; border: 1px solid #28a745; border-radius: 8px; font-size: 0.7rem;">ENTRADA</span>
                        @else
                        <span class="badge w-100 py-2" style="background: rgba(220, 53, 69, 0.2); color: #dc3545; border: 1px solid #dc3545; border-radius: 8px; font-size: 0.7rem;">SAÍDA</span>
                        @endif
                    </div>

                    {{-- QUANTIDADE MOVIMENTADA --}}
                    <div class="flex-shrink-0 border-end border-secondary px-3 text-center" style="width: 15%; --bs-border-opacity: .1;">
                        <div class="text-secondary text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Movimentado</div>
                        <div class="h3 m-0 fw-bold {{ $m->tipo == 'entrada' ? 'text-success' : 'text-danger' }}" style="font-family: 'Oswald';">
                            {{ $m->tipo == 'entrada' ? '+' : '-' }}{{ $m->quantidade }}
                        </div>
                    </div>

                    {{-- SALDO EM ESTOQUE (SOMA DAS EMPRESAS) --}}
                    {{-- SALDO EM ESTOQUE (VALOR REAL CALCULADO NO CONTROLLER) --}}
                    <div class="flex-shrink-0 border-end border-secondary px-3 text-center" style="width: 15%; --bs-border-opacity: .1;">
                        <div class="text-secondary text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Saldo Total</div>
                        <div class="h3 m-0 fw-bold text-white" style="font-family: 'Oswald';">
                            {{-- Usamos o estoque_real que vem do Controller com withSum --}}
                            {{ $m->produto->estoque_real ?? 0 }}
                            <small style="font-size: 0.8rem; font-weight: normal; margin-left: 2px;">un</small>
                        </div>
                    </div>

                    {{-- OBSERVAÇÃO - LARGURA FLEXÍVEL --}}
                    <div class="flex-grow-1 ps-3" style="width: 15%;">
                        <div class="text-secondary text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Observação</div>
                        <div class="text-secondary small italic" style="font-style: italic; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $m->observacao }}">
                            "{{ $m->observacao ?: 'Sem observações.' }}"
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5 text-secondary">Nenhuma movimentação encontrada.</div>
        @endforelse
    </div>

    {{-- PAGINAÇÃO --}}
    {{-- PAGINAÇÃO CUSTOMIZADA --}}
    <div class="py-5 d-flex justify-content-center">
        <div class="custom-pagination">
            {{ $movimentacoes->appends(request()->query())->links() }}
        </div>
    </div>
</div> {{-- Fecha o container-fluid --}}

<style>
    :root {

        --text-input: #e0e0e0;
    }



    /* 1. Esconde a info de texto bugada ("Showing X to Y...") */
    .custom-pagination nav div:first-child {
        display: none !important;
    }

    /* 2. Container da Paginação */
    .custom-pagination .pagination {
        display: flex !important;
        gap: 10px !important;
        border: none !important;
    }

    /* 3. Estilo Geral dos Botões (Números, Next, Prev) */
    .custom-pagination .page-link {
        background-color: rgba(46, 49, 68, 0.6) !important;
        /* Cor dos seus cards */
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: #a0aec0 !important;
        /* Cinza claro */
        border-radius: 12px !important;
        padding: 10px 18px !important;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* 4. Página Ativa (Amarelo ObraControl) */
    .custom-pagination .page-item.active .page-link {
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
        color: #1a1c23 !important;
        /* Texto escuro no fundo amarelo */
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
    }

    /* 5. Hover nos Botões */
    .custom-pagination .page-link:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: #fff !important;
        border-color: rgba(255, 255, 255, 0.3) !important;
        transform: translateY(-2px);
    }

    /* 6. Botões Desativados */
    .custom-pagination .page-item.disabled .page-link {
        background-color: rgba(0, 0, 0, 0.2) !important;
        border-color: transparent !important;
        color: #4a5568 !important;
        cursor: not-allowed;
    }

    /* Remove aquela borda chata que o Bootstrap coloca no foco */
    .page-link:focus {
        box-shadow: none !important;
    }
</style>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inicializa o TomSelect para o filtro de Categoria
        document.querySelectorAll('.tom-select').forEach((el) => {
            new TomSelect(el, {
                allowEmptyOption: true, // Importante para o "TODAS AS CATEGORIAS"
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
    });
</script>
@endsection