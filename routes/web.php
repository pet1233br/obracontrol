<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\MovimentacaoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\CategoriaController;

// --- ROTAS PÚBLICAS ---
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', function () {
    return redirect()->route('login');
});

// --- ROTAS PROTEGIDAS (AUTH) ---
Route::middleware(['auth'])->group(function () {

    // Dashboard e Orçamentos
    Route::get('/dashboard', [ProdutoController::class, 'dashboard'])->name('dashboard');
    Route::get('/orcamento-pdf', [ProdutoController::class, 'gerarOrcamento'])->name('orcamento.pdf');

    // CRUD Produtos e Baixas
    Route::resource('produtos', ProdutoController::class);
    Route::post('/produtos/{id}/baixa', [ProdutoController::class, 'baixa'])->name('produtos.baixa');

    // Movimentações (Histórico)
    Route::get('/movimentacoes', [MovimentacaoController::class, 'index'])->name('movimentacoes.index');

    // === CONFIGURAÇÕES DE ESTOQUE (Empresas e Categorias) ===
    
    // Rota da API de CNPJ (Sempre antes do resource de empresas)
    Route::get('/empresas/verificar-cnpj', [EmpresaController::class, 'verificarCnpj'])->name('empresas.verificar.cnpj');

    // Resources completos para habilitar Update e Destroy
    Route::resource('empresas', EmpresaController::class);
    Route::resource('categorias', CategoriaController::class);

    // Página Principal de Configurações
    Route::get('/configuracoes/estoque', [EmpresaController::class, 'index'])->name('config.estoque');
});