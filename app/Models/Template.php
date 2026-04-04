<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Template extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'templates';

    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'preview_image',
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

    // Accessors
    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? asset('storage/' . $this->thumbnail) : asset('images/default-template.jpg');
    }

    public function getPreviewUrlAttribute()
    {
        return $this->preview_image ? asset('storage/' . $this->preview_image) : asset('images/default-preview.jpg');
    }

    public function getConfigArrayAttribute()
    {
        return $this->config ?? [];
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
