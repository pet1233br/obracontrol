@extends('layouts.app')
@section('page_title', 'Editar Produto: ' . $produto->nome)

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">

        <div class="d-flex justify-content-between align-items-center pb-3 border-bottom border-dark" style="--bs-border-opacity: .2;">
            <div class="d-flex align-items-center">
                <i class="fas fa-edit fs-4 me-3 text-warning"></i>
                <h3 class="m-0" style="font-family: 'Oswald'; text-transform: uppercase;">Editar Produto</h3>
            </div>
            <a href="{{ route('produtos.index') }}" class="btn btn-outline-light d-flex align-items-center shadow-sm" style="border-radius: 10px; font-weight: 600; padding: 10px 20px;">
                <i class="fas fa-arrow-left me-2"></i> VOLTAR
            </a>
        </div>

        <div class="mb-4 pt-2 p-3 shadow-lg" style="background: rgba(255,255,255,0.01); border: 1px solid #2e3144; border-radius: 20px;">

            <form action="{{ route('produtos.update', $produto->id) }}" method="POST" enctype="multipart/form-data" class="row g-4">
                @csrf
                @method('PUT')

                {{-- Nome --}}
                <div class="col-md-7">
                    <label class="nav-label text-secondary text-uppercase fw-bold small">Nome do Produto</label>
                    <input type="text" name="nome" class="form-control bg-dark border-secondary text-white ps-3 shadow-none" value="{{ old('nome', $produto->nome) }}" style="border-radius: 10px; height: 50px;">
                </div>

                {{-- Categoria --}}
                <div class="col-md-5">
                    <label class="nav-label text-secondary text-uppercase fw-bold small">CATEGORIA</label>
                    <select name="categoria_id" class="tom-select" style="width: 100% !important;">
                        @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ $produto->categoria_id == $cat->id ? 'selected' : '' }}>{{ strtoupper($cat->nome) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Seção de Entradas por Empresa --}}
                <div class="col-12 mt-4">
                    <div class="p-4 rounded-4" style=" border: 1px dashed rgba(255,193,7,0.3);">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="form-label text-secondary text-uppercase fw-bold m-0" style="font-size: 0.8rem; letter-spacing: 1px;">
                                <i class="fas fa-truck-loading me-2"></i> Entradas de Estoque por Empresa
                            </label>
                            <button type="button" onclick="addEmpresa()" class="btn btn-sm btn-warning fw-bold px-3" style="border-radius: 8px;">
                                <i class="fas fa-plus me-1"></i> ADICIONAR
                            </button>
                        </div>

                        <div id="container-empresas">
                            @forelse($produto->empresas as $empresaRelacionada)
                            <div class="row g-2 mb-2 empresa-item align-items-center">
                                <div class="col-md-7">
                                    <select name="empresas[]" class="form-select bg-dark border-secondary text-white shadow-none" style="border-radius: 8px;">
                                        @foreach($allEmpresas as $e)
                                        <option value="{{ $e->id }}" {{ $e->id == $empresaRelacionada->id ? 'selected' : '' }}>{{ $e->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="quantidades[]" oninput="calcularTudo()" class="form-control bg-dark border-secondary text-white qtd-input shadow-none text-center"
                                        value="{{ $empresaRelacionada->pivot->quantidade }}" placeholder="Qtd" style="border-radius: 8px;">
                                </div>
                                {{-- NOVO: Preço Unitário por Empresa --}}
                                <div class="col-md-2">
                                    <input type="text" name="precos_unitarios[]"
                                        class="form-control bg-dark border-secondary text-danger preco-input shadow-none text-center"
                                        value="{{ number_format($empresaRelacionada->pivot->preco_custo ?? 0, 2, ',', '.') }}"
                                        placeholder="0,00" style="border-radius: 8px;">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-outline-danger border-0 w-100" onclick="removerEmpresa(this)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            @empty
                            {{-- Linha vazia caso não haja empresas --}}
                            <div class="row g-2 mb-2 empresa-item align-items-center">
                                <div class="col-md-7">
                                    <select name="empresas[]" class="form-select bg-dark border-secondary text-white shadow-none" style="border-radius: 8px;">
                                        <option value="">Selecione a Empresa</option>
                                        @foreach($allEmpresas as $e)
                                        <option value="{{ $e->id }}">{{ $e->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="quantidades[]" oninput="calcularTudo()" class="form-control bg-dark border-secondary text-white qtd-input shadow-none text-center" placeholder="0">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="precos_unitarios[]" oninput="calcularTudo()" class="form-control bg-dark border-secondary text-danger preco-input shadow-none text-center" placeholder="0,00">
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Estoque Total --}}
                <div class="col-md-6 mt-4">
                    <label class="form-label text-secondary text-uppercase fw-bold small">Estoque Total Calculado</label>
                    <input type="number" id="quantidade_total" name="quantidade" class="form-control bg-dark border-secondary text-white fw-bold shadow-none"
                        value="{{ $produto->quantidade }}" readonly style="border-radius:10px; height: 50px;">
                    <small class="text-muted italic mt-1 d-block">Soma das quantidades acima.</small>
                </div>

                {{-- Preço Médio --}}
                <div class="col-md-6 mt-4">
                    <label class="form-label text-danger text-uppercase fw-bold small">Preço Médio (R$)</label>
                    <input type="text" id="preco_medio_display" class="form-control bg-dark border-secondary text-white fw-bold shadow-none"
                        value="{{ number_format($produto->preco, 2, ',', '.') }}" readonly style="border-radius: 10px; height: 50px;">
                    {{-- Campo oculto para enviar o valor real ao banco --}}
                    <input type="hidden" name="preco" id="preco_final">
                </div>

                {{-- Imagem e Botão Salvar --}}
                {{-- Campo Imagem --}}
                <div class="col-md-12 mt-4">
                    <label class="form-label text-secondary text-uppercase fw-bold small">Imagem do Produto</label>
                    <div class="d-flex align-items-center gap-4 p-3 bg-black border border-secondary" style="border-radius: 15px;">
                        @if($produto->imagem)
                        <img src="{{ asset('storage/' . str_replace(['public/', 'storage/'], '', $produto->imagem)) }}"
                            class="rounded-circle border border-secondary shadow-sm"
                            style="width: 70px; height: 70px; object-fit: cover;">
                        @else
                        <div class="bg-dark rounded-circle d-flex align-items-center justify-content-center border border-secondary shadow-sm"
                            style="width: 70px; height: 70px;">
                            <i class="fas fa-image fs-3 text-secondary"></i>
                        </div>
                        @endif
                        <div class="flex-grow-1">
                            <input type="file" name="imagem" class="form-control bg-dark border-secondary text-white shadow-none @error('imagem') is-invalid @enderror"
                                style="border-radius: 10px; height: 45px;">
                            <div class="form-text text-muted" style="font-size: 0.75rem;">Selecione para substituir a imagem atual.</div>
                        </div>
                    </div>
                    @error('imagem')
                    <div class="invalid-feedback text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mt-5 text-end">
                    <button type="submit" class="btn btn-warning d-flex align-items-center shadow-lg w-100 justify-content-center" style="border-radius: 12px; font-weight: 700; padding: 18px 30px;">
                        <i class="fas fa-save me-2 fs-5"></i> ATUALIZAR ESTOQUE COMPLETO
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    // Monitora as entradas para calcular o total e a média
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('qtd-input') || e.target.classList.contains('preco-input')) {
            calcularTudo();
        }
    });

    // MÁSCARA DE DINHEIRO CORRIGIDA (Tipo 22 -> 22,00)
    document.addEventListener('blur', function(e) {
        if (e.target.classList.contains('preco-input')) {
            let valor = e.target.value.replace(/\./g, '').replace(',', '.');
            if (valor !== '' && !isNaN(valor)) {
                // Formata para o padrão brasileiro ao sair do campo
                e.target.value = parseFloat(valor).toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
            calcularTudo();
        }
    }, true);

    function addEmpresa() {
        const container = document.getElementById('container-empresas');
        // Em vez de clonar (que traz lixo), vamos inserir o HTML limpo
        const html = `
            <div class="row g-2 mb-2 empresa-item align-items-center">
                <div class="col-md-7">
                    <select name="empresas[]" class="form-select bg-dark border-secondary text-white shadow-none" style="border-radius: 8px;">
                        <option value="">Selecione a Empresa</option>
                        @foreach($allEmpresas as $e)
                        <option value="{{ $e->id }}">{{ $e->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="quantidades[]" class="form-control bg-dark border-secondary text-white qtd-input shadow-none text-center" placeholder="0">
                </div>
                <div class="col-md-2">
                    <input type="text" name="precos_unitarios[]" class="form-control bg-dark border-secondary text-danger preco-input shadow-none text-center" placeholder="0,00">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger border-0 w-100" onclick="removerEmpresa(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', html);
    }

    function removerEmpresa(btn) {
        btn.closest('.empresa-item').remove();
        calcularTudo();
    }

    function calcularTudo() {
        let totalQtd = 0;
        let custoTotalGeral = 0;

        document.querySelectorAll('.empresa-item').forEach(linha => {
            // Quantidade
            const inputQtd = linha.querySelector('.qtd-input');
            const qtd = parseFloat(inputQtd.value) || 0;

            // Preço Unitário (Limpeza de string PT-BR para Float JS)
            const inputPreco = linha.querySelector('.preco-input');
            let precoRaw = inputPreco.value.replace(/\./g, '').replace(',', '.');
            const preco = parseFloat(precoRaw) || 0;

            totalQtd += qtd;
            custoTotalGeral += (qtd * preco);
        });

        // Atualiza Quantidade Total
        const campoTotalQtd = document.getElementById('quantidade_total');
        if (campoTotalQtd) campoTotalQtd.value = totalQtd;

        // Atualiza Preço Médio (Média Ponderada)
        const campoPrecoMedio = document.getElementById('preco_medio_display');
        const campoPrecoFinal = document.getElementById('preco_final'); // O hidden que vai pro banco

        let media = totalQtd > 0 ? (custoTotalGeral / totalQtd) : 0;

        if (campoPrecoMedio) {
            campoPrecoMedio.value = media.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Valor "limpo" para o banco de dados (ex: 1250.50)
        if (campoPrecoFinal) {
            campoPrecoFinal.value = media.toFixed(2);
        }
    }

    document.addEventListener('DOMContentLoaded', calcularTudo);
</script>
@endsection