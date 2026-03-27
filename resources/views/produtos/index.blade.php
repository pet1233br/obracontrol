@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 text-white">📦 Estoque de Materiais</h2>
        <a href="{{ route('produtos.create') }}" class="btn btn-primary">+ Novo Produto</a>
    </div>

    {{-- Filtros --}}
    <div class="card shadow-sm border-0 mb-4 bg-dark text-white">
        <div class="card-body">
            <form action="{{ route('produtos.index') }}" method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="busca" class="form-control" placeholder="🔍 Buscar por nome..." value="{{ request('busca') }}">
                </div>
                <div class="col-md-4">
                    <select name="categoria_id" class="form-select">
                        <option value="">📂 Todas as Categorias</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-warning text-dark fw-bold">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabela --}}
    <div class="card shadow-sm border-0 bg-dark">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 80px;">Imagem</th>
                        <th>Produto</th>
                        <th>Categoria</th>
                        <th>Estoque</th>
                        <th>Preço</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produtos as $p)
                        <tr>
                            <td>
                                @if($p->imagem)
                                    <img src="{{ asset('storage/' . str_replace(['public/', 'storage/'], '', $p->imagem)) }}" 
                                         class="rounded border border-warning" 
                                         style="width: 45px; height: 45px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                        <i class="bi bi-image text-warning"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="fw-bold">{{ $p->nome }}</td>
                            <td>{{ $p->categoria->nome ?? 'Sem Categoria' }}</td>
                            <td>
                                <span class="badge {{ $p->quantidade <= 5 ? 'bg-danger' : 'bg-light text-dark' }}">
                                    {{ $p->quantidade }} un
                                </span>
                            </td>
                            <td>R$ {{ number_format($p->preco, 2, ',', '.') }}</td>
                            <td class="text-center">
                                <a href="{{ route('produtos.edit', $p->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalBaixa{{ $p->id }}">
                                    Baixa
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Nenhum produto encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $produtos->appends(request()->query())->links() }}
    </div>
</div>

{{-- MODAIS - Fora da estrutura principal para não quebrar o layout --}}
@if(isset($produtos))
    @foreach($produtos as $p)
        <div class="modal fade" id="modalBaixa{{ $p->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-white text-dark">
                    <form action="{{ route('produtos.baixa', $p->id) }}" method="POST">
                        @csrf
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Retirar: {{ $p->nome }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-1">Estoque disponível: <strong>{{ $p->quantidade }} un</strong></p>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Quantidade a retirar:</label>
                                <input type="number" name="quantidade_saida" class="form-control" min="1" max="{{ $p->quantidade }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger px-4">Dar Baixa</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endif

@endsection