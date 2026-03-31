@extends('layouts.app')
@section('page_title', 'Estoque de Materiais')

@section('content')

<style>
    /* 1. ESTRUTURA DE CARDS (Substitui a tabela rígida) */

    :root {
        --dash-blue: #007bff;
        --dash-green: #28a745;
        --dash-red: #dc3545;
        --dash-bg: #0f1021;
        --card-bg: rgba(255, 255, 255, 0.05);
        --text-input: #e0e0e0;
    }


    .custom-table-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding-bottom: 30px;
    }

    .table-header-grid {
        display: grid;
        grid-template-columns: 80px 2fr 1fr 1fr 1fr 140px;
        padding: 10px 20px;
        color: rgba(255, 255, 255, 0.4);
        text-transform: uppercase;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 1.5px;
    }

    .product-card {
        background: rgba(255, 255, 255, 0.01);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
    }

    .product-card:hover {
        background: rgba(255, 255, 255, 0.06);
        border-color: rgba(3, 3, 3, 0.3);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.36);

    }

    .badge-estoque {
        background: rgba(40, 167, 69, 0.1);
        color: #2ecc71;
        border: 1px solid rgba(46, 204, 113, 0.3);
        box-shadow: 0 0 10px rgba(46, 204, 113, 0.1);
    }

    .badge-alerta {
        background: rgba(220, 53, 69, 0.1);
        color: #ff4757;
        border: 1px solid rgba(255, 71, 87, 0.3);
        box-shadow: 0 0 10px rgba(255, 71, 87, 0.1);
    }


    .product-main-row {
        display: grid;
        grid-template-columns: 80px 2fr 1fr 1fr 1fr 140px;
        align-items: center;
        padding: 18px 20px;
        cursor: pointer;
    }

    /* 2. ANIMAÇÃO DE EXPANSÃO "DO PRODUTO" */
    .product-details {
        max-height: 0;
        overflow: hidden;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        background: rgba(0, 0, 0, 0.15);
        opacity: 0;
    }

    .product-card.active {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(255, 193, 7, 0.5);
    }

    .product-card.active .product-details {
        max-height: 600px;
        opacity: 1;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }

    .details-inner {
        padding: 25px;
    }

    /* 3. COMPONENTES VISUAIS */
    .company-box {
        min-height: 60px;
        /* Garante altura para as duas linhas de texto */
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 10px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .company-box:hover {
        background: rgba(255, 193, 7, 0.05);
        border-color: rgba(255, 193, 7, 0.2);
    }

    .chevron-icon {
        transition: transform 0.4s ease;
        opacity: 0.3;
    }

    .product-card.active .chevron-icon {
        transform: rotate(180deg);
        opacity: 1;
        color: #ffc107;
    }

    /* 4. FILTROS ESTILIZADOS */
    .filter-section {
        background: rgba(255, 255, 255, 0.02);
        border-radius: 15px;
        padding: 20px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    /* 5. MODAL BLUR PREMIUM */
    .modal-backdrop.show {
        backdrop-filter: blur(15px) !important;
        background-color: rgba(0, 0, 0, 0.8) !important;
    }
</style>

<div class="container-fluid px-2">
    {{-- Seção de Filtros e Busca --}}
    <div class="mb-3">
        {{-- FORMULÁRIO ÚNICO COM ID PARA O JS FUNCIONAR --}}
        <form action="{{ route('produtos.index') }}" method="GET" id="form-filtro" class="row g-3 align-items-center">

            {{-- Busca por Nome --}}
            <div class="col-md-6">
                <label class="form-label text-secondary text-uppercase fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">Buscar </label>
                <div class="position-relative">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                    <input type="text" name="busca" class="form-control bg-dark border-secondary text-white ps-5 shadow-none"
                        placeholder="Procurar material..." value="{{ request('busca') }}" style="border-radius: 10px; height: 48px; background:rgba (0 , 0, 0, 0);">
                </div>
            </div>

            {{-- Filtro de Categorias --}}

            <div class="col-md-3">
                <label class="form-label text-secondary text-uppercase fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 1px;"></label>

                <select name="filter_categoria"
                    id="filter-categoria"
                    class="tom-select">

                    <option value="" selected>TODAS AS CATEGORIAS</option>

                    @foreach($categorias as $cat)
                    <option value="{{ $cat->id }}"
                        {{ request('filter_categoria') == $cat->id ? 'selected' : '' }}>
                        {{ strtoupper($cat->nome) }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Botão Novo --}}
            <div class="col-md-3 text-end">
                <label class="form-label text-secondary text-uppercase fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 1px;"></label>

                <a href="{{ route('produtos.create') }}" class="btn btn-primary w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center"
                    style="border-radius: 10px; height: 48px;">
                    <i class="fas fa-plus-circle me-2"></i> NOVO PRODUTO
                </a>
            </div>
        </form>
    </div>

    {{-- Cabeçalho da "Tabela" --}}
    <div class="table-header-grid d-none d-lg-grid">
        <div>Foto</div>
        <div>Produto / Descrição</div>
        <div>Categoria</div>
        <div>Estoque</div>
        <div>Total estoque</div>
        <div class="text-center">Ações</div>
    </div>

    {{-- Lista de Cards --}}
    <div class="custom-table-container">
        @forelse($produtos as $p)
        <div class="product-card" id="card-{{ $p->id }}">
            {{-- Linha Visível --}}
            <div class="product-main-row" onclick="toggleDetails({{ $p->id }})">
                <div class="text-center">
                    @if($p->imagem)
                    <img src="{{ asset('storage/' . str_replace(['public/', 'storage/'], '', $p->imagem)) }}"
                        class="rounded-3" style="width: 50px; height: 50px; object-fit: cover; border: 1px solid rgba(255,255,255,0.1);">
                    @else
                    <div class="bg-dark rounded-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border: 1px solid rgba(255,255,255,0.1);">
                        <i class="fas fa-tools text-secondary"></i>
                    </div>
                    @endif
                </div>
                <div>
                    <span class="text-white fw-bold d-block fs-5" style="font-family: 'Oswald'; letter-spacing: 0.5px;">{{ $p->nome }}</span>
                    <span class="text-muted small">REF: #{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div>
                    <span class="badge bg-dark border border-secondary border-opacity-25 text-secondary fw-normal px-3 py-2">
                        {{ $p->categoria->nome ?? 'Sem Categoria' }}
                    </span>
                </div>
                <div>
                    @php
                    // Soma a quantidade de todas as empresas vinculadas a este produto específico
                    $estoqueReal = $p->empresas->sum('pivot.quantidade');
                    @endphp

                    @if($estoqueReal <= 5)
                        <span class="text-danger fw-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ $estoqueReal }} un
                        </span>
                        @else
                        <span class="text-white">{{ $estoqueReal }} un</span>
                        @endif
                </div>



                <div class="text-warning fw-bold">

                    R$ {{ number_format((float)$p->preco, 2, ',', '.') }}
                </div>

                <div class="text-center d-flex justify-content-center gap-2">
                    <a href="{{ route('produtos.edit', $p->id) }}" class="btn btn-sm btn-outline-light border-0"
                        onclick="event.stopPropagation();" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-danger border-0"
                        data-bs-toggle="modal" data-bs-target="#modalExcluir{{ $p->id }}"
                        onclick="event.stopPropagation();" title="Excluir">
                        <i class="fas fa-trash"></i>
                    </button>
                    <div class="actions">
                        <i class="fas fa-chevron-down ms-3 icon-seta-detalhe"></i>
                    </div>
                </div>
            </div>

            {{-- Detalhes Ocultos (Expande do Card) --}}
            <div class="product-details">
                <div class="details-inner">
                    <div class="row align-items-center mb-4">
                        <div class="col-auto">
                            <h6 class="text-warning fw-bold mb-0 text-uppercase small" style="letter-spacing: 1px;">
                                <i class="fas fa-layer-group me-2"></i> Detalhamento de Estoque
                            </h6>
                        </div>
                        <div class="col">
                            <hr class="border-secondary opacity-25">
                        </div>
                    </div>

                    <div class="row g-3">
                        @forelse($p->empresas as $empresa)
                        <div class="col-md-6">
                            <div class="company-box">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning bg-opacity-10 p-2 rounded me-3 text-warning">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-white small fw-bold">{{ $empresa->nome }}</span>
                                        <span class="text-danger fw-bold" style="font-size: 0.75rem;">
                                            R$ {{ number_format((float)($empresa->pivot->preco_custo ?? 0), 2, ',', '.') }} <small class="text-muted">un.</small>
                                        </span>
                                    </div>
                                </div>
                                <span class="badge bg-dark border border-secondary px-3">{{ $empresa->pivot->quantidade }} un</span>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-muted">Nenhuma empresa vinculada.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5 text-muted bg-dark bg-opacity-25 rounded-4 border border-secondary border-dashed">
            <i class="fas fa-search fa-3x mb-3 opacity-25"></i>
            <p>Nenhum material encontrado com esses filtros.</p>
        </div>
        @endforelse
    </div>

    {{-- Paginação --}}
    <div class="mt-4">
        {{ $produtos->appends(request()->query())->links() }}
    </div>

    {{-- MODAL DE EXCLUSÃO (Premium com Blur) --}}
    @foreach($produtos as $p)
    <div class="modal fade" id="modalExcluir{{ $p->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg"
                style="background: rgba(15, 15, 15, 0.98); border-radius: 25px; border: 1px solid rgba(255, 0, 0, 0.2) !important;">
                <div class="modal-body text-center p-5">
                    <div class="mb-4 d-inline-block p-4 rounded-circle" style="background: rgba(255, 0, 0, 0.1);">
                        <i class="fas fa-trash-alt text-danger" style="font-size: 3.5rem;"></i>
                    </div>
                    <h2 class="text-white fw-bold mb-3" style="font-family: 'Oswald'; letter-spacing: 1px;">REMOVER MATERIAL?</h2>
                    <p class="text-secondary mb-4">Você está prestes a excluir permanentemente o item:<br>
                        <strong class="text-white fs-4">{{ strtoupper($p->nome) }}</strong>
                    </p>

                    <div class="row g-3 mt-4">
                        <div class="col-6">
                            <button class="btn btn-outline-light w-100 py-3 fw-bold" data-bs-dismiss="modal" style="border-radius: 12px;">CANCELAR</button>
                        </div>
                        <div class="col-6">
                            <form action="{{ route('produtos.destroy', $p->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger w-100 py-3 fw-bold shadow-lg" style="border-radius: 12px; background-color: #dc3545;">EXCLUIR DEFINITIVAMENTE</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <script>
        function toggleDetails(id) {
            const card = document.getElementById(`card-${id}`);
            if (card) card.classList.toggle('active');
        }

        document.addEventListener("DOMContentLoaded", function() {

            new TomSelect("#filter-categoria", {
                placeholder: "TODAS AS CATEGORIAS",
                allowEmptyOption: true,
                maxOptions: null,
                onChange: function(value) {
                    document.getElementById('form-filtro').submit();
                }
            });

            // Busca com Enter
            const inputBusca = document.querySelector("input[name='busca']");
            if (inputBusca) {
                inputBusca.addEventListener('keypress', function(e) {
                    if (e.which === 13) {
                        document.getElementById('form-filtro').submit();
                    }
                });
            }
        });
    </script>
    @endsection