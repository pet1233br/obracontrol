@extends('layouts.app')

@section('content')

<div class="card shadow-sm border-0 mb-4 bg-light">
    <div class="card-body">
        <form action="{{ route('dashboard') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">Filtrar Categoria Crítica</label>
                <select name="categoria_id" class="form-select form-select-sm">
                    <option value="">Todas as categorias</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Alerta abaixo de:</label>
                <input type="number" name="limite" class="form-control form-control-sm" value="{{ request('limite', 5) }}">
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-sm btn-dark">Filtrar Painel</button>
            </div>
            <div class="col-md-1 d-grid">
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">Resetar</a>
            </div>
            <div>
                <a href="{{ route('orcamento.pdf', request()->all()) }}" class="btn btn-danger btn-sm">
                    <i class="bi bi-file-earmark-pdf"></i> Gerar Orçamento em PDF
                </a>
            </div>
        </form>
    </div>
</div>

<div class="container my-5">
    <h2 class="mb-4">📊 Painel de Controle</h2>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm border-0">
                <div class="card-body">
                    <h6>Total de Produtos</h6>
                    <h2 class="fw-bold">{{ $totalProdutos }}</h2>
                    <i class="bi bi-box-seam float-end fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm border-0">
                <div class="card-body">
                    <h6>Itens em Estoque (Total)</h6>
                    <h2 class="fw-bold">{{ $itensEmEstoque }}</h2>
                    <i class="bi bi-stack float-end fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card {{ $estoqueBaixo->count() > 0 ? 'bg-danger' : 'bg-secondary' }} text-white shadow-sm border-0">
                <div class="card-body">
                    <h6>Alertas de Estoque Baixo</h6>
                    <h2 class="fw-bold">{{ $estoqueBaixo->count() }}</h2>
                    <i class="bi bi-exclamation-triangle float-end fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold">⚠️ Produtos que precisam de Compra</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr><th>Produto</th><th>Qtd</th></tr>
                        </thead>
                        <tbody>
                            @forelse($estoqueBaixo as $item)
                                <tr><td>{{ $item->nome }}</td><td class="text-danger fw-bold">{{ $item->quantidade }}</td></tr>
                            @empty
                                <tr><td colspan="2" class="text-center">Tudo em dia! ✅</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">🕒 Últimas Atividades</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($ultimasMovimentacoes as $mov)
                            <li class="list-group-item">
                                <span class="badge {{ $mov->tipo == 'entrada' ? 'bg-success' : 'bg-danger' }} me-2">
                                    {{ $mov->tipo == 'entrada' ? '+' : '-' }}
                                </span>
                                <strong>{{ $mov->produto->nome ?? 'Produto' }}</strong>
                                <small class="text-muted float-end">{{ $mov->created_at->diffForHumans() }}</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection