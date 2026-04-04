<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('templates')->insert([
            [
                'name' => 'Tema Jawa Klasik',
                'slug' => 'tema-jawa-klasik',
                'category' => 'jawa',
                'description' => 'Template undangan pernikahan dengan nuansa Jawa klasik yang elegan',
                'is_active' => true,
                'is_default' => true,
                'config' => json_encode([
                    'colors' => ['primary' => '#8B4513', 'secondary' => '#D2691E'],
                    'fonts' => ['primary' => 'Poppins', 'secondary' => 'Playfair Display'],
                    'layouts' => ['default', 'modern', 'simple']
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Modern Minimalis',
                'slug' => 'modern-minimalis',
                'category' => 'modern',
                'description' => 'Template undangan modern dengan desain minimalis dan clean',
                'is_active' => true,
                'is_default' => false,
                'config' => json_encode([
                    'colors' => ['primary' => '#2C3E50', 'secondary' => '#34495E'],
                    'fonts' => ['primary' => 'Montserrat', 'secondary' => 'Open Sans'],
                    'layouts' => ['default', 'grid', 'masonry']
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Elegant Gold',
                'slug' => 'elegant-gold',
                'category' => 'elegant',
                'description' => 'Template mewah dengan aksen emas yang elegan',
                'is_active' => true,
                'is_default' => false,
                'config' => json_encode([
                    'colors' => ['primary' => '#C5A059', 'secondary' => '#2C2C2C'],
                    'fonts' => ['primary' => 'Cormorant Garamond', 'secondary' => 'Lato'],
                    'layouts' => ['default', 'luxury', 'royal']
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
