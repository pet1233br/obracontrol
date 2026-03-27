@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-warning text-dark p-3">
                    <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Editar Produto</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('produtos.update', $produto->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-bold">Nome</label>
                                <input type="text" name="nome" class="form-control" value="{{ $produto->nome }}" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Categoria</label>
                                <select name="categoria_id" class="form-select" required>
                                    @foreach($categorias as $cat)
                                        <option value="{{ $cat->id }}" {{ $produto->categoria_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Preço</label>
                                <input type="text" name="preco" class="form-control" value="{{ number_format($produto->preco, 2, ',', '.') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Quantidade em Estoque</label>
                                <input type="number" name="quantidade" class="form-control" value="{{ $produto->quantidade }}" required>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold d-block">Imagem Atual</label>
                                @if($produto->imagem)
                                    <img src="{{ asset('storage/' . $produto->imagem) }}" class="img-thumbnail mb-2" style="height: 100px;">
                                @endif
                                <input type="file" name="imagem" class="form-control">
                                <small class="text-muted">Deixe em branco para manter a imagem atual.</small>
                            </div>

                            <div class="col-12 d-flex justify-content-between mt-4">
                                <a href="{{ route('produtos.index') }}" class="btn btn-light border">Voltar</a>
                                <button type="submit" class="btn btn-primary px-5">Atualizar Dados</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection