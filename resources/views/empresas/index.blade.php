@extends('layouts.app')

@section('page_title', 'Configurações de Estoque')

@section('content')
<style>
    :root {
        --dash-yellow: #ffc107;
        --dash-cyan: #0dcaf0;
        --card-bg: rgba(255, 255, 255, 0.03);
        --input-bg: rgba(0, 0, 0, 0.2);

        /* Cor para as letras dentro dos inputs */
        --text-input: #e0e0e0;

        /* Cor para o placeholder (aquele texto que some quando digita) */
        --text-placeholder: rgba(255, 255, 255, 0.4);
    }

    /* Fundo e Container Principal */
    .container-fluid {

        min-height: 100vh;
        color: white;
    }

    /* Cards Estilo Glassmorphism */
    .config-card {
        background: var(--card-bg);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 20px;
        transition: transform 0.3s ease, border-color 0.3s ease;
    }

    .config-card:hover {
        border-color: rgba(255, 255, 255, 0.15) !important;
        transform: translateY(-2px);
    }

    /* Inputs Modernos */
    .form-control {
        background: var(--input-bg) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;

        /* AQUI ESTÁ A MÁGICA */
        color: var(--text-input) !important;

        border-radius: 12px !important;
        padding: 12px 15px !important;
        font-size: 0.9rem;
    }

    .form-control::placeholder {
        color: var(--text-placeholder) !important;
    }

    /* Garante que o texto continue visível quando o input está em foco */
    .form-control:focus {
        color: #fff !important;
        /* Brilha um pouco mais no foco */
        border-color: var(--dash-yellow) !important;
    }

    .form-control:focus {
        box-shadow: 0 0 15px rgba(255, 193, 7, 0.1) !important;
        border-color: var(--dash-yellow) !important;
    }

    /* Tabelas Premium */
    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .custom-table tr {
        background: rgba(255, 255, 255, 0.02);
        transition: 0.3s;
    }

    .custom-table tr:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .custom-table td {
        padding: 15px;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .custom-table td:first-child {
        border-left: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 12px 0 0 12px;
    }

    .custom-table td:last-child {
        border-right: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 0 12px 12px 0;
    }

    /* Tipografia e Botões */
    .section-title {
        font-family: 'Oswald', sans-serif;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        font-size: 0.9rem;
    }

    /* ... seus outros estilos ... */

    .btn-custom {
        border-radius: 12px !important;
        padding: 14px 20px !important;
        font-family: 'Oswald', sans-serif !important;
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        letter-spacing: 1.2px !important;
        text-transform: uppercase !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        border: none !important;
        position: relative;
        overflow: hidden;
    }

    /* Botão Empresa (Amarelo) */
    .btn-empresa {
        background: #ffc107 !important;
        color: #000 !important;
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.2);
    }

    .btn-empresa:hover {
        background: #ffca2c !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 193, 7, 0.4);
    }

    .btn-empresa:active {
        transform: translateY(0);
    }

    /* Botão Categoria (Ciano) */
    .btn-categoria {
        background: #0dcaf0 !important;
        color: #000 !important;
        box-shadow: 0 4px 15px rgba(13, 202, 240, 0.2);
    }

    .btn-categoria:hover {
        background: #31d2f2 !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(13, 202, 240, 0.4);
    }

    .btn-categoria:active {
        transform: translateY(0);
    }

    /* Efeito de brilho ao passar o mouse */
    .btn-custom::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.5s;
    }

    .btn-custom:hover::after {
        left: 100%;
    }
</style>

<div class="container-fluid py-4">
    <div class="row g-4">

        <div class="col-lg-4">
            <div class="config-card p-4 mb-4">
                <h6 class="section-title text-warning mb-4"><i class="fas fa-building me-2"></i> Novo Fornecedor</h6>
                <form action="{{ route('empresas.store') }}" method="POST">
                    @csrf
                    <input type="text" name="nome" class="form-control mb-3" placeholder="Nome da Empresa" required>
                    <input type="text" name="cnpj" class="form-control mb-4" placeholder="CNPJ (Opcional)">
                    <button type="submit" class="btn btn-custom btn-empresa w-100">
                        <i class="fas fa-plus-circle me-2"></i> Cadastrar Empresa
                    </button>
                </form>
            </div>

            <div class="config-card p-4">
                <h6 class="section-title text-info mb-4"><i class="fas fa-tags me-2"></i> Nova Categoria</h6>
                <form action="{{ route('categorias.store') }}" method="POST">
                    @csrf
                    <input type="text" name="nome" class="form-control mb-4" placeholder="Ex: Ferragens, Elétrica..." required>
                    <button type="submit" class="btn btn-custom btn-categoria w-100">
                        <i class="fas fa-tag me-2"></i> Salvar Categoria
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="config-card p-4 h-100">
                <h6 class="section-title text-secondary mb-4">Gerenciamento de Dados</h6>


                <div class="table-responsive mb-5">
                    <table class="custom-table">
                        <thead>
                            <tr style="background: transparent;">
                                <th class="text-muted small ps-3">FORNECEDOR</th>
                                <th class="text-center text-muted small">ITENS</th>
                                <th class="text-end text-muted small pe-3">AÇÕES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($empresas as $e)
                            <tr>
                                <td class="fw-bold text-white ps-3">{{ $e->nome }}</td>
                                <td class="text-center">
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2">
                                        {{ $e->produtos_count ?? 0 }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <button onclick="editEmpresa({{ $e->id }}, '{{ addslashes($e->nome) }}', '{{ $e->cnpj ?? '' }}')" class="btn btn-sm btn-outline-light border-0 opacity-75"><i class="fas fa-edit"></i></button>
                                    <button onclick="confirmDeleteEmpresa({{ $e->id }}, '{{ addslashes($e->nome) }}')" class="btn btn-sm btn-outline-danger border-0 opacity-75"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Nenhuma empresa cadastrada.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr style="background: transparent;">
                                <th class="text-muted small ps-3">CATEGORIA</th>
                                <th class="text-end text-muted small pe-3">AÇÕES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categorias as $c)
                            <tr>
                                <td class="fw-bold text-white ps-3">{{ $c->nome }}</td>
                                <td class="text-end pe-3">
                                    <button onclick="editCategoria({{ $c->id }}, '{{ addslashes($c->nome) }}')" class="btn btn-sm btn-outline-light border-0 opacity-75"><i class="fas fa-edit"></i></button>
                                    <button onclick="confirmDeleteCategoria({{ $c->id }}, '{{ addslashes($c->nome) }}')" class="btn btn-sm btn-outline-danger border-0 opacity-75"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-4">Nenhuma categoria cadastrada.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editEmpresaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: #0d0d0d; border: 1px solid rgba(255,255,255,0.1); border-radius: 20px;">
            <div class="modal-body text-center p-5">
                <h4 class="text-white mb-4 section-title">Editar Fornecedor</h4>
                <form id="formEditEmpresa" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" id="edit_empresa_id">
                    <div class="mb-3 text-start">
                        <label class="text-muted small text-uppercase fw-bold mb-2 ps-1">Nome da Empresa</label>
                        <input type="text" id="edit_empresa_nome" name="nome" class="form-control" required>
                    </div>
                    <div class="mb-4 text-start">
                        <label class="text-muted small text-uppercase fw-bold mb-2 ps-1">CNPJ</label>
                        <input type="text" id="edit_empresa_cnpj" name="cnpj" class="form-control">
                    </div>
                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-outline-light w-100 py-3 fw-bold" data-bs-dismiss="modal">CANCELAR</button>
                        <button type="submit" class="btn btn-yellow w-100 py-3">SALVAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editCategoriaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: #0d0d0d; border: 1px solid rgba(255,255,255,0.1); border-radius: 20px;">
            <div class="modal-body text-center p-5">
                <h4 class="text-white mb-4 section-title text-info">Editar Categoria</h4>
                <form id="formEditCategoria" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" id="edit_categoria_id">
                    <div class="mb-4 text-start">
                        <label class="text-muted small text-uppercase fw-bold mb-2 ps-1">Nome da Categoria</label>
                        <input type="text" id="edit_categoria_nome" name="nome" class="form-control" required>
                    </div>
                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-outline-light w-100 py-3 fw-bold" data-bs-dismiss="modal">CANCELAR</button>
                        <button type="submit" class="btn btn-outline-cyan w-100 py-3">ATUALIZAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: #0d0d0d; border: 1px solid rgba(255,255,255,0.1); border-radius: 24px;">
            <div class="modal-body text-center p-5">
                <div class="mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; background: rgba(220, 53, 69, 0.1); border-radius: 50%;">
                    <i class="fas fa-trash-alt text-danger fa-2x"></i>
                </div>
                <h3 class="text-white mb-2 section-title">Remover Item?</h3>
                <p class="text-muted mb-4 small" id="delete_message">Esta ação não pode ser desfeita.</p>
                <div class="d-flex gap-3">
                    <button type="button" class="btn btn-outline-light w-100 py-3" data-bs-dismiss="modal">CANCELAR</button>
                    <form id="formDelete" method="POST" style="display: contents;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100 py-3 fw-bold" style="border-radius: 12px;">EXCLUIR</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalEditEmpresa = new bootstrap.Modal(document.getElementById('editEmpresaModal'));
        const modalEditCategoria = new bootstrap.Modal(document.getElementById('editCategoriaModal'));
        const modalDelete = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));

        window.editEmpresa = function(id, nome, cnpj) {
            document.getElementById('edit_empresa_id').value = id;
            document.getElementById('edit_empresa_nome').value = nome;
            document.getElementById('edit_empresa_cnpj').value = cnpj || '';
            document.getElementById('formEditEmpresa').action = '/empresas/' + id;
            modalEditEmpresa.show();
        }

        window.editCategoria = function(id, nome) {
            document.getElementById('edit_categoria_id').value = id;
            document.getElementById('edit_categoria_nome').value = nome;
            document.getElementById('formEditCategoria').action = '/categorias/' + id;
            modalEditCategoria.show();
        }

        window.confirmDeleteEmpresa = function(id, nome) {
            document.getElementById('formDelete').action = '/empresas/' + id;
            document.getElementById('delete_message').innerHTML = `Excluir empresa: <strong>${nome}</strong>?`;
            modalDelete.show();
        }

        window.confirmDeleteCategoria = function(id, nome) {
            document.getElementById('formDelete').action = '/categorias/' + id;
            document.getElementById('delete_message').innerHTML = `Excluir categoria: <strong>${nome}</strong>?`;
            modalDelete.show();
        }
    });
</script>
@endpush