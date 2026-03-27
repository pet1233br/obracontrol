<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void 
{
    $categorias = [
    'Alvenaria', 'Ferragens', 'Elétrica', 'Hidráulica', 'Pintura', 
    'Pisos e Revestimentos', 'Ferramentas Manuais', 'Ferramentas Elétricas', 
    'Iluminação', 'Madeiras', 'Telhados', 'Jardinagem', 
    'Equipamentos de Proteção (EPI)', 'Acabamentos', 'Fixação (Parafusos/Pregos)',
    'Gesso e Drywall', 'Impermeabilização', 'Cozinha e Banheiro',
    'Segurança e Incêndio', 'Limpeza Pós-Obra'
];

foreach ($categorias as $nome) {
    App\Models\Categoria::firstOrCreate(['nome' => $nome]);
}
}
}
