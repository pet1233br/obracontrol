<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Movimentacao;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;

class ProdutoController extends Controller
{
    // 1. LISTAGEM COM BUSCA E FILTRO
    // 1. LISTAGEM COM BUSCA E FILTRO
    // 1. LISTAGEM COM BUSCA E FILTRO
    public function index(Request $request)
    {
        $categorias = Categoria::all();
        $query = Produto::query();

        // FILTRO DE CATEGORIA: Certifique-se que o 'name' no seu <select> é 'filter_categoria'
        if ($request->filled('filter_categoria')) {
            $query->where('categoria_id', $request->filter_categoria);
        }

        // FILTRO DE BUSCA
        if ($request->filled('busca')) {
            $query->where('nome', 'like', '%' . $request->busca . '%');
        }

        $produtos = $query->paginate(10);

        // Busca as movimentações para a barra lateral
        $ultimasMovimentacoes = Movimentacao::with('produto')->latest()->take(5)->get();

        return view('produtos.index', compact('produtos', 'categorias', 'ultimasMovimentacoes'));
    }

    // 2. TELA DE NOVO PRODUTO
    public function create()
    {
        // 1. Você precisa buscar as empresas no banco de dados
        $empresas = Empresa::all();

        // 2. Buscar as categorias (se você usar no form)
        $categorias = Categoria::all();

        // 3. Enviar as variáveis para a View dentro do compact()
        return view('produtos.create', compact('empresas', 'categorias'));
    }
    /**
     * Exibe o Histórico de Movimentações com filtros
     */
    public function movimentacoes(Request $request)
    {
        $query = Movimentacao::with(['produto', 'produto.categoria', 'user'])
            ->latest();

        // Filtro por categoria
        if ($request->filled('categoria_id') && $request->categoria_id != '') {
            $query->whereHas('produto', function ($q) use ($request) {
                $q->where('categoria_id', $request->categoria_id);
            });
        }

        // Filtro por data
        if ($request->filled('data_inicio')) {
            try {
                $inicio = \Carbon\Carbon::createFromFormat('Y-m-d', $request->data_inicio)->startOfDay();
                $query->where('created_at', '>=', $inicio);
            } catch (\Exception $e) {
            }
        }

        if ($request->filled('data_fim')) {
            try {
                $fim = \Carbon\Carbon::createFromFormat('Y-m-d', $request->data_fim)->endOfDay();
                $query->where('created_at', '<=', $fim);
            } catch (\Exception $e) {
            }
        }

        $movimentacoes = $query->paginate(15);

        $categorias = Categoria::all();

        return view('movimentacoes.index', compact('movimentacoes', 'categorias'));
    }

    // 3. SALVAR NOVO PRODUTO
    public function store(Request $request)
    {
        // 1. Validação básica
        $request->validate([
            'nome' => 'required',
            'categoria_id' => 'required',
            'quantidade' => 'required|integer',
            'empresa_id' => 'required',
            'preco' => 'required', // Garante que o preço venha do formulário
        ]);

        // 2. TRATAMENTO DO PREÇO (Converte "1.500,00" para 1500.00)
        $precoRaw = $request->preco;
        $precoLimpo = str_replace('.', '', $precoRaw);
        $precoLimpo = str_replace(',', '.', $precoLimpo);

        // 3. TRATAMENTO DA IMAGEM
        $caminhoImagem = null;
        if ($request->hasFile('imagem')) {
            $caminhoImagem = $request->file('imagem')->store('produtos', 'public');
        }

        // 4. CRIAÇÃO DO PRODUTO (Aqui estava o erro: antes estava 'preco' => 0)
        // No passo 4 do seu método store():
        $produto = Produto::create([
            'nome'           => $request->nome,
            'categoria_id'   => $request->categoria_id,
            'quantidade'     => $request->quantidade,
            'descricao'      => $request->descricao,
            'preco'          => (float) $precoLimpo,
            'imagem'         => $caminhoImagem,
            'estoque_minimo' => $request->estoque_minimo ?? 0, // ADICIONE ESTA LINHA
        ]);

        // 5. VÍNCULO COM A EMPRESA (Tabela Pivô)
        $produto->empresas()->attach($request->empresa_id, [
            'quantidade'   => $request->quantidade,
            'preco_compra' => (float) $precoLimpo,
        ]);

        // 6. REGISTRO NO HISTÓRICO
        \App\Models\Movimentacao::create([
            'produto_id' => $produto->id,
            'user_id'    => auth()->id() ?? 1,
            'tipo'       => 'entrada',
            'quantidade' => $request->quantidade,
            'observacao' => 'Cadastro inicial do produto',
        ]);

        return redirect()->route('produtos.index')->with('success', 'Produto cadastrado com sucesso!');
    }

    // 4. TELA DE EDIÇÃO
    public function edit($id)
    {
        $produto = Produto::with('empresas')->findOrFail($id);
        $categorias = Categoria::all();
        $allEmpresas = Empresa::all();

        return view('produtos.edit', compact('produto', 'categorias', 'allEmpresas'));
    }

    // ←←←← AQUI NÃO PODE TER NADA SOLTO ←←←←

    // 6. EXCLUIR
    public function destroy($id)
    {
        $produto = Produto::findOrFail($id);
        if ($produto->imagem) {
            Storage::disk('public')->delete($produto->imagem);
        }
        $produto->delete();
        return redirect()->route('produtos.index')->with('success', 'Excluído!');
    }

    // 7. DASHBOARD
    public function dashboard(Request $request)
    {
        // 1. Iniciamos a consulta base
        $queryBase = Produto::query();

        // 2. FILTRO DE BUSCA (A Lupa que você adicionou)
        if ($request->filled('search')) {
            $queryBase->where('nome', 'like', '%' . $request->search . '%');
        }

        // 3. FILTRO DE CATEGORIA
        if ($request->filled('categoria_id')) {
            $queryBase->where('categoria_id', $request->categoria_id);
        }

        // 4. FILTRO DE SITUAÇÃO (Status: Baixo ou OK)
        $limite = $request->get('limite', 5);
        if ($request->filled('status')) {
            if ($request->status == 'baixo') {
                $queryBase->where('quantidade', '<=', $limite);
            } elseif ($request->status == 'ok') {
                $queryBase->where('quantidade', '>', $limite);
            }
        }

        // 5. SE CLICAR NO BOTÃO GERAR PDF (Ação do Orçamento)
        if ($request->get('export') == 'pdf') {
            $produtosParaPdf = (clone $queryBase)->get();
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.orcamento', ['produtos' => $produtosParaPdf]);
            return $pdf->download('orcamento_gerado.pdf');
        }

        // 6. MÉTRICAS DO TOPO (Respeitando os filtros)
        $totalProdutos = (clone $queryBase)->count();

        // Soma o estoque real dos produtos filtrados
        $idsFiltrados = (clone $queryBase)->pluck('id');
        $itensEmEstoque = \DB::table('produto_empresa')
            ->whereIn('produto_id', $idsFiltrados)
            ->sum('quantidade');

        // 7. LISTA DE ESTOQUE BAIXO (Para a coluna da esquerda)
        $estoqueBaixo = (clone $queryBase)
            ->where('quantidade', '<=', $limite)
            ->with('categoria')
            ->get();

        // 8. DADOS EXTRAS
        $categorias = \App\Models\Categoria::all();
        $ultimasMovimentacoes = \App\Models\Movimentacao::with('produto')->latest()->take(5)->get();

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
    }
    /**
     * Atualiza um produto existente
     */
    /**
     * Atualiza um produto existente + registra movimentação no histórico
     */
    public function update(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);

        // 1. DADOS BÁSICOS
        $dados = $request->only(['nome', 'categoria_id', 'descricao']);

        if ($request->filled('preco')) {
            // Pegamos o valor (ex: "10,48")
            $valor = $request->preco;

            // Remove espaços, pontos de milhar e troca vírgula por ponto
            $valor = str_replace(' ', '', $valor);
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);

            // Agora o PHP entende como 10.48 (float)
            $dados['preco'] = (float) $valor;
        }

        if ($request->hasFile('imagem')) {
            $caminho = $request->file('imagem')->store('produtos', 'public');
            $dados['imagem'] = $caminho;
        }

        $produto->update($dados);

        // 2. ATUALIZAÇÃO DE ESTOQUE (PIVOT)
        if ($request->has('empresas') && is_array($request->empresas)) {
            $quantidades = $request->input('quantidades', []);
            $precosUnitarios = $request->input('precos_unitarios', []);

            $quantidadeAntiga = $produto->quantidade;
            $quantidadeNovaReal = array_sum($quantidades);

            $dadosPivot = [];
            foreach ($request->empresas as $index => $empresaId) {
                if ($empresaId) {
                    $precoLinha = str_replace('.', '', $precosUnitarios[$index] ?? '0');
                    $precoLinha = (float) str_replace(',', '.', $precoLinha);

                    // IMPORTANTE: Use 'preco_custo' se foi esse o nome na migration!
                    $dadosPivot[$empresaId] = [
                        'quantidade' => (int) ($quantidades[$index] ?? 0),
                        'preco_custo' => $precoLinha
                    ];
                }
            }

            $produto->empresas()->sync($dadosPivot);

            // 3. HISTÓRICO
            $diferenca = $quantidadeNovaReal - $quantidadeAntiga;
            if ($diferenca != 0) {
                \App\Models\Movimentacao::create([
                    'produto_id' => $produto->id,
                    'user_id'    => auth()->id() ?? 1,
                    'tipo'       => $diferenca > 0 ? 'entrada' : 'saida',
                    'quantidade' => abs($diferenca),
                    'observacao' => 'Ajuste via edição: ' . ($diferenca > 0 ? 'Entrada' : 'Saída') . ' de ' . abs($diferenca) . ' un.',
                ]);
            }

            $produto->quantidade = $quantidadeNovaReal;
            $produto->save();
        }

        return redirect()->route('produtos.index')->with('success', 'Atualizado com sucesso!');
    }
    public function baixa(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);
        $qtdRetirada = $request->quantidade_saida ?? $request->quantidade;

        if (!$qtdRetirada || $qtdRetirada <= 0) {
            return redirect()->back()->with('error', 'Informe uma quantidade válida!');
        }

        if ($produto->quantidade < $qtdRetirada) {
            return redirect()->back()->with('error', 'Estoque insuficiente!');
        }

        // 1. Atualiza o estoque total do produto
        $produto->decrement('quantidade', $qtdRetirada);

        // 2. IMPORTANTE: Atualiza a tabela pivô (Lógica proporcional ou primeira empresa encontrada)
        // Aqui estamos tirando da primeira empresa vinculada para simplificar
        $empresaPivot = $produto->empresas()->first();
        if ($empresaPivot) {
            $novaQtdPivot = $empresaPivot->pivot->quantidade - $qtdRetirada;
            $produto->empresas()->updateExistingPivot($empresaPivot->id, [
                'quantidade' => max(0, $novaQtdPivot)
            ]);
        }

        // 3. REGISTRA A SAÍDA NO HISTÓRICO (Corrigido para 'saida')
        Movimentacao::create([
            'produto_id' => $produto->id,
            'user_id'    => auth()->id() ?? 1,
            'tipo'       => 'saida', // Antes estava 'entrada'
            'quantidade' => $qtdRetirada,
            'observacao' => $request->observacao ?? 'Baixa de estoque manual',
        ]);

        return redirect()->back()->with('success', 'Baixa realizada com sucesso!');
    }
}
