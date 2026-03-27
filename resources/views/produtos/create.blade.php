@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white p-3">
                    <h4 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Cadastrar Novo Produto</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('produtos.store') }}" method="POST" enctype="multipart/form-data" class="theme-marine">
    @csrf
    <div class="row g-3">
        <div class="col-md-8 mb-3">
            <label for="nome" class="form-label fw-bold text-warning">Nome do Produto</label>
            <input type="text" name="nome" id="nome" class="form-control custom-input" placeholder="Digite o nome do material" required>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label fw-bold text-warning">Categoria</label>
            <select name="categoria_id" class="form-control custom-input">
                <option value="">Selecione...</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label fw-bold text-warning">Custo</label>
            <div class="input-group">
                <span class="input-group-text bg-dark text-warning border-warning">R$</span>
                <input type="text" name="preco" class="form-control custom-input" placeholder="0,00" required>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label fw-bold text-warning">Quantidade Inicial</label>
            <input type="number" name="quantidade" class="form-control custom-input" placeholder="0" required>
        </div>

        <div class="col-12 mb-3">
            <label class="form-label fw-bold text-warning">Descrição / Observações</label>
            <textarea name="descricao" class="form-control custom-input" rows="3"></textarea>
        </div>

        <div class="col-12 mb-4">
            <label class="form-label fw-bold text-warning">Foto do Produto</label>
            <input type="file" name="imagem" class="form-control custom-input">
        </div>

        <hr class="border-secondary">
        <div class="col-12 d-flex justify-content-between">
            <a href="{{ route('produtos.index') }}" class="btn btn-outline-light">Cancelar</a>
            <button type="submit" class="btn btn-warning px-5 fw-bold">Salvar Produto</button>
        </div>
    </div>
</form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


<style>
    /* 1. ESTILO PADRÃO DOS CAMPOS (SEM FOCO) */
    .theme-marine .form-control,
    .theme-marine select,
    .theme-marine textarea,
    .custom-input {
        background-color: #1e2030 !important;
        color: #ffc107 !important; /* Texto amarelo */
        border: 1px solid rgba(255, 193, 7, 0.3) !important; /* Borda amarela sutil */
        border-radius: 6px;
        transition: all 0.2s ease-in-out;
    }

    /* 2. O SEGREDO: ESTILO QUANDO VOCÊ CLICA/DIGITA (FOCUS) */
    /* Removemos o OUTLINE (borda branca chata) e colocamos o brilho amarelo */
    .theme-marine .form-control:focus, 
    .theme-marine select:focus, 
    .theme-marine textarea:focus,
    .custom-input:focus {
        background-color: #1e2030 !important;
        color: #ffc107 !important;
        
        /* Mata a borda branca padrão do navegador */
        outline: none !important; 
        outline-offset: 0 !important;
        
        /* Define a nossa borda amarela firme */
        border-color: #ffc107 !important; 
        
        /* Cria o efeito de brilho neon amarelo (Glow) */
        box-shadow: 0 0 10px rgba(255, 193, 7, 0.7) !important; 
    }

    /* 3. CORREÇÃO ESPECÍFICA PARA INPUT GROUPS (R$) */
    .input-group-text {
        background-color: #1a1c29 !important;
        color: #ffc107 !important;
        border: 1px solid rgba(255, 193, 7, 0.3) !important;
    }

    /* 4. AJUSTE DO PLACEHOLDER (TEXTO DE EXEMPLO) */
    .form-control::placeholder {
        color: rgba(255, 193, 7, 0.4) !important;
    }
    
    /* 5. AJUSTE DAS OPÇÕES DO SELECT */
    select option {
        background-color: #1e2030;
        color: #ffc107;
    }

    /* 6. CORREÇÃO DO BOTÃO 'ESCOLHER ARQUIVO' DA FOTO */
    input[type="file"]::file-selector-button {
        background-color: #1a1c29;
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.3);
        border-radius: 4px;
    }
</style>