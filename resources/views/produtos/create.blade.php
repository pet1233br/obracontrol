@extends('layouts.app')
@section('page_title', 'Novo Produto')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">

        {{-- Cabeçalho --}}
        <div class="d-flex justify-content-between align-items-center  pb-3 border-bottom border-dark" style="--bs-border-opacity: .1;">
            <div class="d-flex align-items-center">
                <i class="fas fa-plus-circle fs-4 me-3 text-warning"></i>
                <h3 class="m-0" style="font-family: 'Oswald'; text-transform: uppercase;">Cadastrar Novo Produto</h3>
            </div>

            <a href="{{ route('produtos.index') }}" class="btn btn-outline-light d-flex align-items-center shadow-sm" style="border-radius: 10px; font-weight: 600; padding: 10px 20px;">
                <i class="fas fa-arrow-left me-2"></i> VOLTAR
            </a>
        </div>

        {{-- Formulário --}}
        <div class="mb-4 p-5 shadow-lg" style="background: rgba(255,255,255,0.01); border: 1px solid #2e3144; border-radius: 20px;">
            <form action="{{ route('produtos.store') }}" method="POST" enctype="multipart/form-data" class="row g-4">
                @csrf

                <div class="col-md-7">
                    <label class="form-label text-secondary text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">Nome do Produto</label>
                    <input type="text" name="nome" class="form-control bg-dark border-secondary text-white ps-3 shadow-none @error('nome') is-invalid @enderror"
                        placeholder="Ex: Cimento CP-II" value="{{ old('nome') }}" required
                        style="border-radius: 10px; height: 50px;">
                </div>

                <div class="col-md-5">
                    <label class="form-label text-secondary text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">Categoria</label>
                    <select name="categoria_id" class="form-select bg-dark border-secondary text-white shadow-none @error('categoria_id') is-invalid @enderror"
                        style="border-radius: 10px; height: 50px; cursor: pointer;">
                        <option value="">SELECIONE...</option>
                        @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ strtoupper($categoria->nome) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- COLE ISTO NO LUGAR --}}
                <div class="col-md-4">
                    <label class="form-label text-secondary text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">Preço de Custo (R$)</label>
                    <input type="text" name="preco" id="preco_mask" class="form-control bg-dark border-secondary text-white ps-3 shadow-none"
                        placeholder="0,00" value="{{ old('preco') }}" required
                        style="border-radius: 10px; height: 50px;">
                </div>

                <div class="col-md-4">
                    <label class="form-label text-secondary text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">Qtd. em Estoque</label>
                    <input type="number" name="quantidade" class="form-control bg-dark border-secondary text-white ps-3 shadow-none"
                        placeholder="0" value="{{ old('quantidade') }}" required
                        style="border-radius: 10px; height: 50px;">
                </div>

                <div class="col-md-4">
                    <label class="form-label text-secondary text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">Avisar quando chegar em:</label>
                    <input type="number" name="estoque_minimo" class="form-control bg-dark border-secondary text-white ps-3 shadow-none"
                        placeholder="Ex: 5" value="{{ old('estoque_minimo', 5) }}" required
                        style="border-radius: 10px; height: 50px;">
                </div>


                <div class="mb-3">
                    <label class="small text-secondary fw-bold text-uppercase mb-2">Fornecedor / Empresa</label>
                    <div class="position-relative">
                        <i class="fas fa-truck position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                        <select name="empresa_id" class="form-select bg-dark border-0 text-white ps-5 shadow-none" style="border-radius: 10px; height: 48px;">
                            <option value="">Selecione uma empresa</option>
                            @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id }}">{{ $empresa->nome }}</option>
                            @endforeach
                        </select>
                    </div>



                    <div class="col-12">
                        <label class="form-label text-secondary text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">Descrição / Observações</label>
                        <textarea name="descricao" class="form-control bg-dark border-secondary text-white ps-3 shadow-none" rows="3"
                            style="border-radius: 10px;"></textarea>
                    </div>

                    {{-- Foto com Preview --}}
                    <div class="col-md-12">
                        <label class="form-label text-secondary text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">Foto do Produto</label>
                        <div class="d-flex align-items-center gap-4 p-3 bg-black border border-secondary" style="border-radius: 15px;">

                            <div id="preview-container" class="bg-dark rounded-circle d-flex align-items-center justify-content-center border border-secondary shadow-sm"
                                style="width: 70px; height: 70px; overflow: hidden;">
                                <i class="fas fa-image fs-3 text-secondary" id="placeholder-icon"></i>
                                <img id="image-preview" src="#" alt="Preview" style="display: none; width: 100%; height: 100%; object-fit: cover;">
                            </div>

                            <div class="flex-grow-1">
                                <input type="file" name="imagem" id="imagem-input" class="form-control bg-dark border-secondary text-white shadow-none @error('imagem') is-invalid @enderror"
                                    style="border-radius: 10px; height: 45px;">
                                <div class="form-text text-muted" style="font-size: 0.75rem;">Selecione uma imagem (PNG, JPG).</div>
                            </div>
                        </div>
                    </div>


                    @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 12px; background: rgba(220, 53, 69, 0.1); color: #ff6b6b;">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li><i class="fas fa-exclamation-triangle me-2"></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
            </form>
        </div>
    </div>
</div>

<style>
    input[type="file"]::-webkit-file-upload-button {
        background: #2e3144;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 4px 12px;
        margin-right: 10px;
        cursor: pointer;
    }
</style>
@endsection

{{-- A seção de scripts sempre fora do @section('content') para evitar bugs --}}
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('imagem-input');
        const preview = document.getElementById('image-preview');
        const icon = document.getElementById('placeholder-icon');

        if (input) {
            input.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                        icon.style.display = 'none';
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });
        }
    });
</script>
@endsection