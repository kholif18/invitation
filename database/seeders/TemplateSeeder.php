<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Template: Classic Elegant
        $classicElegant = Template::updateOrCreate(
            ['slug' => 'classic-elegant'],
            [
                'name' => 'Classic Elegant',
                'category' => 'classic',
                'description' => 'Template undangan elegan dengan nuansa klasik yang timeless. Dilengkapi dengan warna emas dan coklat yang mewah, cocok untuk pernikahan dengan tema tradisional yang elegan.',
                'blade_file' => 'templates.classic-elegant.index',
                'css_file' => 'assets/templates/classic-elegant/css/style.css',
                'js_file' => 'assets/templates/classic-elegant/js/script.js',
                'version' => '1.0.0',
                'author' => 'WeddingInv System',
                'author_url' => null,
                'is_active' => true,
                'is_default' => true,
                'config' => [
                    'colors' => ['#8B4513', '#DAA520', '#FDF5E6', '#5C3317'],
                    'fonts' => ['Playfair Display', 'Poppins', 'Montserrat'],
                    'layouts' => ['default', 'modern', 'simple']
                ],
                'settings' => [
                    'enable_music' => true,
                    'show_countdown' => true,
                    'show_gallery' => true,
                    'show_gift' => true,
                    'show_rsvp' => true,
                    'primary_color' => '#8B4513',
                    'secondary_color' => '#DAA520',
                    'accent_color' => '#FDF5E6',
                    'text_color' => '#333333',
                    'primary_font' => 'Poppins',
                    'title_font' => 'Playfair Display'
                ]
            ]
        );
        
        $this->command->info('Classic Elegant template created successfully!');
        $this->command->info('Template ID: ' . $classicElegant->id);
    }
}
