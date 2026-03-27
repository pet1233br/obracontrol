<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Movimentacao;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProdutoController extends Controller
{
    // 1. LISTAGEM COM BUSCA E FILTRO
// 1. LISTAGEM COM BUSCA E FILTRO
public function index(Request $request)
{
    $query = Produto::with('categoria');

    // --- A LINHA QUE RESOLVE O PROBLEMA ---
    // Só traz produtos que tenham MAIS de 0 no estoque
    $query->where('quantidade', '>', 0);

    if ($request->filled('busca')) {
        $query->where('nome', 'LIKE', '%' . $request->busca . '%');
    }

    if ($request->filled('categoria_id')) {
        $query->where('categoria_id', $request->categoria_id);
    }

    $produtos = $query->orderBy('nome', 'asc')->paginate(10);
    $categorias = Categoria::all();

    return view('produtos.index', compact('produtos', 'categorias'));
}

    // 2. TELA DE NOVO PRODUTO
public function create()
{
    $categorias = \App\Models\Categoria::all();
    return view('produtos.create', compact('categorias'));
}

    // 3. SALVAR NOVO PRODUTO
    public function store(Request $request)
    {
        $produto = new Produto();
        $produto->nome         = $request->nome;
        $produto->categoria_id = $request->categoria_id;
        $produto->descricao    = $request->descricao;
        $produto->quantidade   = $request->quantidade;
        $produto->preco        = $this->formatarPreco($request->preco);

        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {
            $path = $request->file('imagem')->store('produtos', 'public');
            $produto->imagem = $path;
        }

        $produto->save();

        Movimentacao::create([
            'produto_id' => $produto->id,
            'user_id'    => Auth::id(),
            'tipo'       => 'entrada',
            'quantidade' => $produto->quantidade,
            'observacao' => 'Cadastro inicial do produto',
        ]);

        return redirect('/produtos')->with('success', 'Produto cadastrado com sucesso!');
    }

    // 4. TELA DE EDIÇÃO
    public function edit($id)
    {
        $produto = Produto::findOrFail($id);
        $categorias = Categoria::all();
        return view('produtos.edit', compact('produto', 'categorias'));
    }

public function update(Request $request, $id)
{
    $produtoOriginal = \App\Models\Produto::findOrFail($id);
    $quantidadeAntiga = $produtoOriginal->getOriginal('quantidade');

    $dados = $request->all();

    // --- CORREÇÃO DO PREÇO AQUI ---
    if(isset($dados['preco'])){
        // Remove pontos de milhar e troca vírgula por ponto
        $dados['preco'] = str_replace(',', '.', str_replace('.', '', $dados['preco']));
    }

    if ($request->hasFile('imagem')) {
        $path = $request->file('imagem')->store('produtos', 'public');
        $dados['imagem'] = $path;
    }

    // Agora o $dados['preco'] está limpo (ex: 24.90) e o update vai funcionar
    $produtoOriginal->update($dados); 

    // --- LOG DE MOVIMENTAÇÃO ---
// --- LOG DE MOVIMENTAÇÃO (SUBSTITUA O ANTERIOR POR ESTE) ---
    $novaQuantidade = (int) $request->quantidade; 

    if ($novaQuantidade !== (int) $quantidadeAntiga) {
        $diferenca = $novaQuantidade - $quantidadeAntiga;
        
        $mov = new \App\Models\Movimentacao();
        $mov->produto_id = $id;
        $mov->user_id    = auth()->id() ?? 1; // Se não tiver user, usa o ID 1
        $mov->tipo       = $diferenca > 0 ? 'entrada' : 'saida';
        $mov->quantidade = abs($diferenca);
        $mov->observacao = 'Ajuste manual via edição de produto';
        
        // O save() vai disparar um erro de verdade se o banco rejeitar
        $mov->save(); 
    }

    return redirect()->route('produtos.index')->with('success', 'Atualizado com sucesso!');
}

    // 6. EXCLUIR
    public function destroy($id)
    {
        $produto = Produto::findOrFail($id);
        if ($produto->imagem) { Storage::disk('public')->delete($produto->imagem); }
        $produto->delete();
        return redirect()->route('produtos.index')->with('success', 'Excluído!');
    }

    // 7. DASHBOARD
  public function dashboard(Request $request)
{
    // 1. Criamos uma consulta base
    $queryBase = Produto::query();

    // 2. Se o usuário escolheu uma categoria, filtramos TUDO por ela
    if ($request->filled('categoria_id')) {
        $queryBase->where('categoria_id', $request->categoria_id);
    }

    // 3. AGORA AS SOMAS SÃO DINÂMICAS:
    // O total de produtos e a soma de itens agora respeitam a categoria escolhida
    $totalProdutos = (clone $queryBase)->count();
    $itensEmEstoque = (clone $queryBase)->sum('quantidade');

    // 4. Filtro de Itens Críticos (Quantidade baixa dentro da categoria)
    $limite = $request->get('limite', 5);
    $estoqueBaixo = (clone $queryBase)
        ->where('quantidade', '<=', $limite)
        ->with('categoria')
        ->get();
    
    // 5. Categorias para o Select (isso sempre pega todas)
    $categorias = \App\Models\Categoria::all();

    // 6. Últimas movimentações (Geral do sistema)
    $ultimasMovimentacoes = \App\Models\Movimentacao::with('produto')
        ->latest()
        ->take(5)
        ->get();

    return view('dashboard', compact(
        'totalProdutos', 
        'itensEmEstoque', 
        'estoqueBaixo', 
        'ultimasMovimentacoes',
        'categorias'
    ));
}

    // FUNÇÃO AUXILIAR DE PREÇO
    private function formatarPreco($valor)
    {
        if (empty($valor)) return 0.0;
        $valor = preg_replace('/[^\d,\.]/', '', trim($valor));
        $ultimoSeparador = strrpos($valor, ',') > strrpos($valor, '.') ? ',' : '.';
        if ($ultimoSeparador === ',') {
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);
        } else {
            $valor = str_replace(',', '', $valor);
        }
        return (float) $valor;
    }
    public function gerarOrcamento(Request $request)
{
    $query = Produto::query();

    if ($request->filled('categoria_id')) {
        $query->where('categoria_id', $request->categoria_id);
    }
    
    $limite = $request->get('limite', 5);
    $produtos = $query->where('quantidade', '<=', $limite)->get();

    // CORREÇÃO AQUI: De Barrier para Barryvdh
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.orcamento', compact('produtos'));

    return $pdf->download('orcamento_compra.pdf');
}public function baixa(Request $request, $id)
{
    $produto = Produto::findOrFail($id);
    $qtdRetirada = $request->quantidade_saida;

    // Validação básica para não tirar mais do que tem
    if ($produto->quantidade < $qtdRetirada) {
        return redirect()->back()->with('error', 'Quantidade insuficiente em estoque!');
    }

    // Subtrai do estoque
    $produto->decrement('quantidade', $qtdRetirada);

    // Registra no histórico (Log)
    \App\Models\Movimentacao::create([
        'produto_id' => $produto->id,
        'user_id'    => auth()->id(),
        'tipo'       => 'saida',
        'quantidade' => $qtdRetirada,
        'observacao' => 'Retirada rápida via painel',
    ]);

    return redirect()->back()->with('success', 'Baixa realizada com sucesso!');
}
}