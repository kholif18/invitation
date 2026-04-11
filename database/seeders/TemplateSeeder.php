<?php

// database/seeders/TemplateSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar template bawaan sistem
        $templates = [
            [
                'name' => 'Classic Elegant',
                'slug' => 'classic-elegant',
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
                // TIDAK ADA FIELD THUMBNAIL - akan diambil dari folder assets
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
            ],
            [
                'name' => 'Modern Minimalist',
                'slug' => 'modern-minimalist',
                'category' => 'modern',
                'description' => 'Template modern dengan desain minimalis dan clean. Cocok untuk pasangan muda yang menginginkan undangan simpel namun elegan.',
                'blade_file' => 'templates.modern-minimalist.index',
                'css_file' => 'assets/templates/modern-minimalist/css/style.css',
                'js_file' => 'assets/templates/modern-minimalist/js/script.js',
                'version' => '1.0.0',
                'author' => 'WeddingInv System',
                'author_url' => null,
                'is_active' => true,
                'is_default' => false,
                'config' => [
                    'colors' => ['#2C3E50', '#3498DB', '#ECF0F1', '#BDC3C7'],
                    'fonts' => ['Montserrat', 'Open Sans', 'Lato'],
                    'layouts' => ['default', 'dark', 'light']
                ],
                'settings' => [
                    'enable_music' => true,
                    'show_countdown' => true,
                    'show_gallery' => true,
                    'show_gift' => true,
                    'show_rsvp' => true,
                    'primary_color' => '#2C3E50',
                    'secondary_color' => '#3498DB',
                    'accent_color' => '#ECF0F1',
                    'text_color' => '#333333',
                    'primary_font' => 'Montserrat',
                    'title_font' => 'Montserrat'
                ]
            ],
            [
                'name' => 'Rustic Wedding',
                'slug' => 'rustic-wedding',
                'category' => 'rustic',
                'description' => 'Template dengan nuansa rustic dan natural. Cocok untuk pernikahan outdoor atau garden party.',
                'blade_file' => 'templates.rustic-wedding.index',
                'css_file' => 'assets/templates/rustic-wedding/css/style.css',
                'js_file' => 'assets/templates/rustic-wedding/js/script.js',
                'version' => '1.0.0',
                'author' => 'WeddingInv System',
                'author_url' => null,
                'is_active' => true,
                'is_default' => false,
                'config' => [
                    'colors' => ['#8B4513', '#A0522D', '#F4A460', '#DEB887'],
                    'fonts' => ['Playfair Display', 'Lato', 'Raleway'],
                    'layouts' => ['default', 'vintage', 'bohemian']
                ],
                'settings' => [
                    'enable_music' => true,
                    'show_countdown' => true,
                    'show_gallery' => true,
                    'show_gift' => true,
                    'show_rsvp' => true,
                    'primary_color' => '#8B4513',
                    'secondary_color' => '#A0522D',
                    'accent_color' => '#F4A460',
                    'text_color' => '#333333',
                    'primary_font' => 'Lato',
                    'title_font' => 'Playfair Display'
                ]
            ],
            [
                'name' => 'Elegant Floral',
                'slug' => 'elegant-floral',
                'category' => 'elegant',
                'description' => 'Template dengan motif floral yang elegan. Cocok untuk pernikahan dengan tema bunga dan kesan feminin.',
                'blade_file' => 'templates.elegant-floral.index',
                'css_file' => 'assets/templates/elegant-floral/css/style.css',
                'js_file' => 'assets/templates/elegant-floral/js/script.js',
                'version' => '1.0.0',
                'author' => 'WeddingInv System',
                'author_url' => null,
                'is_active' => true,
                'is_default' => false,
                'config' => [
                    'colors' => ['#FF69B4', '#FFB6C1', '#FFF0F5', '#DB7093'],
                    'fonts' => ['Playfair Display', 'Great Vibes', 'Poppins'],
                    'layouts' => ['default', 'romantic', 'spring']
                ],
                'settings' => [
                    'enable_music' => true,
                    'show_countdown' => true,
                    'show_gallery' => true,
                    'show_gift' => true,
                    'show_rsvp' => true,
                    'primary_color' => '#FF69B4',
                    'secondary_color' => '#FFB6C1',
                    'accent_color' => '#FFF0F5',
                    'text_color' => '#333333',
                    'primary_font' => 'Poppins',
                    'title_font' => 'Playfair Display'
                ]
            ]
        ];
        
        foreach ($templates as $templateData) {
            $template = Template::updateOrCreate(
                ['slug' => $templateData['slug']],
                $templateData
            );
            $this->command->info("Template '{$template->name}' created/updated successfully!");
        }
        
        $this->command->info('All default templates seeded successfully!');
    }
}