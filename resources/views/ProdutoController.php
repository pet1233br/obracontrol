<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
  public function index()
{
    $produtos = Produto::with('empresas', 'categoria')->get();

    return view('produtos.index', compact('produtos'));
}

    public function create()
    {
        return view('produtos.create');
    }

public function store(Request $request) 
{
    $produto = new Produto();
    $produto->nome       = $request->nome;
    $produto->descricao  = $request->descricao;
    $produto->quantidade = $request->quantidade;

    // --- SEU TRATAMENTO FORÇADO ---
    $precoInput = trim($request->preco ?? '0');
    $precoInput = str_replace(['R$', 'r$', '$', ' '], '', $precoInput);
    $precoInput = str_replace('.', '', $precoInput);
    $precoInput = str_replace(',', '.', $precoInput);
    $produto->preco = (float) $precoInput; 

    // --- SALVAMENTO DA IMAGEM ---
    if ($request->hasFile('imagem')) {
        // Salva e guarda o caminho exato
        $path = $request->file('imagem')->store('produtos', 'public');
        $produto->imagem = $path;
    }

    $produto->save(); // Isso envia pro banco

    return redirect('/produtos');
}
    private function formatarPreco($valor)
    {
        if (empty($valor)) {
            return 0.0;
        }

        // Remove tudo que não seja dígito, vírgula ou ponto
        $valor = preg_replace('/[^\d,\.]/', '', trim($valor));

        // Conta quantas vírgulas e pontos existem
        $virgulas = substr_count($valor, ',');
        $pontos   = substr_count($valor, '.');

        // Caso mais comum no Brasil: 1.234,56 ou 1234,56
        if ($virgulas === 1 && $pontos <= 1) {
            // Remove pontos (milhar) e troca vírgula por ponto decimal
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);
        }
        // Caso americano/inglês: 1,234.56 (menos comum aqui, mas tratamos)
        elseif ($pontos === 1 && $virgulas >= 1) {
            $valor = str_replace(',', '', $valor); // remove vírgulas de milhar
            // o ponto já é decimal
        }
        // Se tiver mais de um separador → tenta o mais provável (último separador é decimal)
        else {
            // Remove todos os pontos e vírgulas exceto o último
            $ultimoSeparador = strrpos($valor, ',') > strrpos($valor, '.') ? ',' : '.';
            $valor = str_replace([$ultimoSeparador === ',' ? '.' : ',', $ultimoSeparador], ['', '.'], $valor);
        }

        // Converte para float
        $floatValor = (float) $valor;

        // Retorna com precisão (o Eloquent converte bem para DECIMAL)
        return $floatValor;
    }
}