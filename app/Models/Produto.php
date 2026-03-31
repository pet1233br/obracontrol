<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'quantidade',
        'preco', // Se quiser mudar para 'preco_medio' no futuro, mude aqui também!
        'imagem',
        'codigo',
        'estoque_minimo',
        'categoria_id',
    ];

    /**
     * RELAÇÕES
     */

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function movimentacoes()
    {
        return $this->hasMany(Movimentacao::class);
    }

    /**
     * Relação Muitos-para-Muitos com Empresas
     * Aqui é onde guardamos as entradas de cada fornecedor
     */
    public function empresas()
    {
        return $this->belongsToMany(Empresa::class, 'produto_empresa')
            ->withPivot('quantidade', 'preco_custo') // Agora batendo com o nome da sua migration
            ->withTimestamps();
    }

    /**
     * ATRIBUTOS DINÂMICOS (ACCESSORS)
     */

    public function getEstoqueTotalAttribute()
    {
        // Soma a coluna 'quantidade' da relação com empresas na tabela pivô
        return $this->empresas()->sum('produto_empresa.quantidade');
    }
    public function getPrecoMedioAttribute()
    {
        $totalCusto = $this->empresas->sum(function ($empresa) {
            return $empresa->pivot->quantidade * $empresa->pivot->preco_custo;
        });

        $totalQuantidade = $this->estoque_total;

        return $totalQuantidade > 0 ? ($totalCusto / $totalQuantidade) : 0;
    }
    public function getPreprecisaReporAttribute()
    {
        return $this->estoque_total <= $this->estoque_minimo;
    }
}
