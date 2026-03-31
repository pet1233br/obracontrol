<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria; // Importante para salvar no banco

class CategoriaController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validação básica
        $request->validate([
            'nome' => 'required|max:255|unique:categorias,nome'
        ]);

        // 2. Salva no banco de dados
        Categoria::create([
            'nome' => $request->nome
        ]);

        // 3. Volta para a tela de empresas com mensagem de sucesso
        return redirect()->back()->with('success', 'Categoria cadastrada com sucesso!');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|max:255|unique:categorias,nome,' . $id
        ]);

        $categoria = Categoria::findOrFail($id);
        $categoria->update([
            'nome' => $request->nome
        ]);

        return redirect()->back()->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);

        // Verifica se existem produtos ligados a essa categoria antes de deletar
        if ($categoria->produtos()->count() > 0) {
            return redirect()->back()->with('error', 'Não é possível excluir uma categoria que possui produtos!');
        }

        $categoria->delete();

        return redirect()->back()->with('success', 'Categoria excluída com sucesso!');
    }
}