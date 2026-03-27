<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\MovimentacaoController;

// --- ROTAS PÚBLICAS (Login e Cadastro) ---

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Redireciona tentativa de acesso GET ao register para a home
Route::get('/register', function() {
    return redirect()->route('login');
});


// --- ROTAS PROTEGIDAS (Apenas usuários logados) ---

// --- ROTAS PROTEGIDAS (Apenas usuários logados) ---
Route::middleware(['auth'])->group(function() {
    
    // Mudamos de uma função simples para o método 'dashboard' no Controller
    Route::get('/dashboard', [ProdutoController::class, 'dashboard'])->name('dashboard');

    // CRUD de Produtos
    Route::resource('produtos', ProdutoController::class);

    // Histórico de Movimentações (LOG)
    Route::get('/movimentacoes', [MovimentacaoController::class, 'index'])->name('movimentacoes.index');

    Route::get('/orcamento-pdf', [ProdutoController::class, 'gerarOrcamento'])->name('orcamento.pdf');

    Route::post('/produtos/{id}/baixa', [ProdutoController::class, 'baixa'])->name('produtos.baixa');
});

