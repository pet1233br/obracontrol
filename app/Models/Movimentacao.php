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
    'valor_unitario',
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

    /**
     * Retorna log de auditoria formatado: "Usuário — ação"
     */
    public function getLogAttribute(): string
    {
        $usuario = $this->user->name ?? 'Sistema';

        $observacao = $this->observacao;

        // Detecta a ação baseada na observação
        $acao = match (true) {
            str_contains($observacao ?? '', 'Cadastro') => 'CADASTRO',
            str_contains($observacao ?? '', 'Preço atualizado') => 'EDIÇÃO DE ESTOQUE/PREÇO',
            str_contains($observacao ?? '', 'Ajuste via edição') => 'EDIÇÃO DE ESTOQUE',
            str_contains($observacao ?? '', 'Baixa') => 'BAIXA MANUAL',
            default => strtoupper($observacao ?? 'MOVIMENTAÇÃO'),
        };

        return "{$usuario} — {$acao}";
    }
}