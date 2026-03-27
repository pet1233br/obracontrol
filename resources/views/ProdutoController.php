<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Produto::paginate(10);
        return view('produtos.index', compact('produtos'));
    }

    public function create()
    {
        return view('produtos.create');
    }

public function store(Request $request)
{
    // Debug obrigatório para ver o que está chegando
    // Comente depois que funcionar
    // dd($request->all());  // ← descomente isso para ver TODO o request

    $produto = new Produto();
    $produto->nome       = $request->nome;
    $produto->descricao  = $request->descricao;
    $produto->quantidade = $request->quantidade;

    // TRATAMENTO FORÇADO DO PREÇO - isso TEM que resolver
    $precoInput = trim($request->preco ?? '0');
    $precoInput = str_replace(['R$', 'r$', '$', ' '], '', $precoInput);     // remove símbolos e espaços
    $precoInput = str_replace('.', '', $precoInput);                        // remove ponto de milhar (Brasil)
    $precoInput = str_replace(',', '.', $precoInput);                       // vírgula vira ponto decimal
    $produto->preco = (float) $precoInput;                                  // força float

    // Debug do preço (descomente para testar)
    // dd('Preço original:', $request->preco, 'Preço convertido:', $produto->preco);

    if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {
        $path = $request->file('imagem')->store('produtos', 'public');
        $produto->imagem = $path;
    }

    $produto->save();

    return redirect('/produtos')->with('success', 'Produto cadastrado!');
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