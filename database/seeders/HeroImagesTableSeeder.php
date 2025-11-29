<?php

namespace Database\Seeders;

use App\Models\HeroImage;
use Illuminate\Database\Seeder;

class HeroImagesTableSeeder extends Seeder
{
    public function run()
    {
        HeroImage::create([
            'imagen' => 'hero1.jpg',
            'titulo' => 'Bienvenido a nuestra tienda',
            'descripcion' => 'Los mejores productos al mejor precio',
            'tipo' => 'hero',
            'orden' => 1,
            'estado' => 1,
            'enlace' => '/productos'
        ]);

        HeroImage::create([
            'imagen' => 'hero2.jpg', 
            'titulo' => 'Ofertas Especiales',
            'descripcion' => 'Descuentos increíbles todos los días',
            'tipo' => 'hero',
            'orden' => 2,
            'estado' => 1,
            'enlace' => '/ofertas'
        ]);
    }
}