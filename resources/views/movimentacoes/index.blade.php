@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">🧾 Histórico de Movimentações</h2>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('movimentacoes.index') }}" method="GET" class="row g-3">
                <div class="col-md-8">
                    <p class="text-muted mb-0 py-2">Lista de todas as entradas e saídas do estoque para auditoria.</p>
                </div>
                <div class="col-md-4 d-grid">
                    <a href="{{ route('movimentacoes.index') }}" class="btn btn-outline-secondary">Atualizar Lista</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Data/Hora</th>
                        <th>Produto</th>
                        <th>Usuário</th>
                        <th>Tipo</th>
                        <th>Qtd. Movimentada</th>
                        <th>Observação</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movimentacoes as $m)
                        <tr>
                            <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <strong>{{ $m->produto->nome ?? 'Produto Excluído' }}</strong>
                            </td>
                            <td>{{ $m->user->name ?? 'Sistema' }}</td>
                            <td>
                                <span class="badge {{ $m->tipo == 'entrada' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($m->tipo) }}
                                </span>
                            </td>
                            <td class="fw-bold {{ $m->tipo == 'entrada' ? 'text-success' : 'text-danger' }}">
                                {{ $m->tipo == 'entrada' ? '+' : '-' }} {{ $m->quantidade }} un
                            </td>
                            <td><small class="text-muted">{{ $m->observacao }}</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Nenhuma movimentação registrada até agora.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-3">
        {{ $movimentacoes->links() }}
    </div>
</div>
@endsection