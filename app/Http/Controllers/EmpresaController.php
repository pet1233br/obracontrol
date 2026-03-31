<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\CategoriaController;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::withCount('produtos')
            ->orderBy('nome')
            ->get();

        $categorias = Categoria::orderBy('nome')->get();

        return view('empresas.index', compact('empresas', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:empresas,nome',
            'cnpj' => 'nullable|string|max:18|unique:empresas,cnpj',
        ]);

        Empresa::create($request->only(['nome', 'cnpj']));

        return redirect()->back()->with('success', 'Empresa cadastrada com sucesso!');
    }

    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:empresas,nome,' . $empresa->id,
            'cnpj' => 'nullable|string|max:18|unique:empresas,cnpj,' . $empresa->id,
        ]);

        $empresa->update($request->only(['nome', 'cnpj']));

        return redirect()->back()->with('success', 'Empresa atualizada com sucesso!');
    }

    public function destroy(Empresa $empresa)
    {
        if ($empresa->produtos()->count() > 0) {
            return redirect()->back()->with('error', 'Não é possível excluir esta empresa pois ela possui produtos cadastrados.');
        }

        $empresa->delete();
        return redirect()->back()->with('success', 'Empresa excluída com sucesso!');
    }

    // Verificar CNPJ
    public function verificarCnpj(Request $request)
    {
        $cnpj = $request->input('cnpj');
        if (empty($cnpj)) {
            return response()->json(['error' => 'CNPJ não informado'], 400);
        }

        $cnpjLimpo = preg_replace('/[^0-9]/', '', $cnpj);

        if (strlen($cnpjLimpo) !== 14) {
            return response()->json(['error' => 'CNPJ inválido'], 400);
        }

        try {
            $response = Http::timeout(10)->get("https://brasilapi.com.br/api/cnpj/v1/{$cnpjLimpo}");

            if ($response->successful()) {
                $dados = $response->json();
                return response()->json([
                    'success' => true,
                    'razao_social' => $dados['razao_social'] ?? null,
                    'nome_fantasia' => $dados['nome_fantasia'] ?? null,
                    'situacao' => $dados['descricao_situacao_cadastral'] ?? null,
                    'sugestao_nome' => $dados['nome_fantasia'] ?: $dados['razao_social'] ?? null,
                ]);
            }

            return response()->json(['error' => 'CNPJ não encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao consultar API'], 500);
        }
    }
}
