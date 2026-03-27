<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria; // Importação correta do Model

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       public function run(): void 
{
    $categorias = [
        'Alvenaria',
        'Ferragens',
        'Elétrica',
        'Hidráulica',
        'Pintura',
        'Pisos e Revestimentos',
        'Ferramentas Manuais',
        'Ferramentas Elétricas',
        'Iluminação',
        'Madeiras',
        'Telhados',
        'Jardinagem',
        'Equipamentos de Proteção (EPI)',
        'Acabamentos',
        'Fixação (Parafusos/Pregos)',
    ];

    foreach ($categorias as $nome) {
        \App\Models\Categoria::firstOrCreate(['nome' => $nome]);
    }
}
    }
}