<?php

namespace App\Http\Controllers;

use App\Models\Movimentacao;
use App\Models\Categoria;
use Illuminate\Http\Request;

class MovimentacaoController extends Controller
{
   public function index(Request $request)
{
    // Carregamos as relações e já pedimos a soma da coluna 'quantidade' na tabela pivô
    $query = Movimentacao::with(['produto.categoria', 'user'])
        ->with(['produto' => function($q) {
            $q->withSum('empresas as estoque_real', 'produto_empresa.quantidade');
        }]);

    // Filtro por Categoria
    if ($request->filled('categoria_id')) {
        $query->whereHas('produto', function($q) use ($request) {
            $q->where('categoria_id', $request->categoria_id);
        });
    }

    // Filtros de Data
    if ($request->filled('data_inicio')) {
        $query->whereDate('created_at', '>=', $request->data_inicio);
    }
    if ($request->filled('data_fim')) {
        $query->whereDate('created_at', '<=', $request->data_fim);
    }

    $movimentacoes = $query->latest()->paginate(10);
    $categorias = Categoria::all();

    return view('movimentacoes.index', compact('movimentacoes', 'categorias'));
}
}