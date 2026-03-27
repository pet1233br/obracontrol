<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movimentacao extends Model
{
    protected $table = 'movimentacoes';

    protected $fillable = [
    'produto_id', 
    'user_id', 
    'tipo', 
    'quantidade', 
    'observacao'
];

    /**
     * Relacionamento: Toda movimentação pertence a um produto.
     * Isso permite fazer: $movimentacao->produto->nome
     */
    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }

    /**
     * Relacionamento: Toda movimentação pertence a um usuário (quem fez).
     * Isso permite fazer: $movimentacao->user->name
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}