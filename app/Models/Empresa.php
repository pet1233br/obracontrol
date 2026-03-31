<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'cnpj'];

    // Relacionamento com Produtos (Muitos para Muitos)
    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'produto_empresa')
                    ->withPivot('quantidade', 'preco_compra')
                    ->withTimestamps();
    }
}