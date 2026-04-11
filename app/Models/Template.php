<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Template extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'templates';

    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'category',
        'description',
        'blade_file',
        'css_file',
        'js_file',
        'config',
        'settings',
        'version',
        'author',
        'author_url',
        'is_active',
        'is_default'
    ];

    protected $casts = [
        'config' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($template) {
            $template->slug = Str::slug($template->name);
        });

        static::saving(function ($template) {
            if ($template->is_default) {
                // Only one template can be default
                static::where('id', '!=', $template->id)->update(['is_default' => false]);
            }
        });
    }

    // Relationships
    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function getThumbnailUrlAttribute()
    {
        // PRIORITAS 1: Thumbnail dari folder template (bawaan sistem)
        $templateThumbnail = $this->getTemplateThumbnailFromAssets();
        if ($templateThumbnail) {
            return $templateThumbnail;
        }
        
        // PRIORITAS 2: Thumbnail yang diupload via form (storage)
        if ($this->thumbnail && Storage::disk('public')->exists($this->thumbnail)) {
            return Storage::url($this->thumbnail);
        }
        
        // PRIORITAS 3: Default thumbnail berdasarkan kategori (fallback)
        return $this->getDefaultThumbnailByCategory();
    }

    /**
     * Cari thumbnail di folder template assets (untuk template bawaan sistem)
     */
    private function getTemplateThumbnailFromAssets()
    {
        // List kemungkinan lokasi thumbnail di folder template
        $possiblePaths = [
            "assets/templates/{$this->slug}/images/thumbnail.jpg",
            "assets/templates/{$this->slug}/images/thumbnail.png",
            "assets/templates/{$this->slug}/thumbnail.jpg",
            "assets/templates/{$this->slug}/thumbnail.png",
            "assets/templates/{$this->slug}/images/thumb.jpg",
            "assets/templates/{$this->slug}/screenshot.jpg",
        ];
        
        foreach ($possiblePaths as $path) {
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }
        
        return null;
    }

    /**
     * Get default thumbnail based on category (fallback terakhir)
     */
    private function getDefaultThumbnailByCategory()
    {
        $defaultThumbnails = [
            'classic' => '/assets/templates/defaults/thumbnails/classic.jpg',
            'modern' => '/assets/templates/defaults/thumbnails/modern.jpg',
            'minimalist' => '/assets/templates/defaults/thumbnails/minimalist.jpg',
            'elegant' => '/assets/templates/defaults/thumbnails/elegant.jpg',
            'rustic' => '/assets/templates/defaults/thumbnails/rustic.jpg',
            'default' => '/assets/templates/defaults/thumbnails/default-template.jpg',
        ];
        
        $thumbnailPath = $defaultThumbnails[$this->category] ?? $defaultThumbnails['default'];
        
        if (!file_exists(public_path($thumbnailPath))) {
            return 'https://placehold.co/600x400/F0F0F0/999999?text=' . urlencode($this->name ?? 'Template');
        }
        
        return asset($thumbnailPath);
    }

    public function getConfigArrayAttribute()
    {
        return $this->config ?? [];
    }

    // Accessor untuk CSS URL
    public function getCssUrlAttribute()
    {
        if ($this->css_file && file_exists(public_path($this->css_file))) {
            return asset($this->css_file);
        }
        return null;
    }
    
    // Accessor untuk JS URL
    public function getJsUrlAttribute()
    {
        if ($this->js_file && file_exists(public_path($this->js_file))) {
            return asset($this->js_file);
        }
        return null;
    }
    
    // Method helper
    public function hasThumbnail()
    {
        return $this->thumbnail && Storage::disk('public')->exists($this->thumbnail);
    }
    
    public function getCategoryLabelAttribute()
    {
        $categories = [
            'classic' => 'Classic',
            'modern' => 'Modern',
            'minimalist' => 'Minimalist',
            'elegant' => 'Elegant',
            'rustic' => 'Rustic',
            'vintage' => 'Vintage',
            'simple' => 'Simple',
        ];
        
        return $categories[$this->category] ?? ucfirst($this->category);
    }
    
    public function getStatusBadgeAttribute()
    {
        if (!$this->is_active) {
            return '<span class="badge badge-secondary">Inactive</span>';
        }
        if ($this->is_default) {
            return '<span class="badge badge-primary">Default</span>';
        }
        return '<span class="badge badge-success">Active</span>';
    }
    
    // Helper methods
    public function getAvailableColors()
    {
        return $this->config['colors'] ?? ['primary' => '#000000', 'secondary' => '#ffffff'];
    }

    public function getAvailableFonts()
    {
        return $this->config['fonts'] ?? ['primary' => 'Arial', 'secondary' => 'Arial'];
    }

    public function getLayouts()
    {
        return $this->config['layouts'] ?? ['default'];
    }
}
