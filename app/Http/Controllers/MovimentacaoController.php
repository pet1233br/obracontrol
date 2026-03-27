<?php

namespace App\Http\Controllers;

use App\Models\Movimentacao;
use Illuminate\Http\Request;

class MovimentacaoController extends Controller
{
public function index()
{
    // 1. Pega as movimentações com os relacionamentos (Eager Loading)
// Mude de latest() para oldest() só para testar:
$movimentacoes = \App\Models\Movimentacao::with(['produto', 'user'])
                    ->latest() // Os primeiros que foram criados na história
                    ->paginate(100); // Pega muita coisa de uma vez

    // 2. BUSCA AS CATEGORIAS (O que estava faltando!)
    $categorias = \App\Models\Categoria::all();

    // 3. Envia AMBAS para a view
    return view('movimentacoes.index', compact('movimentacoes', 'categorias'));
}
}