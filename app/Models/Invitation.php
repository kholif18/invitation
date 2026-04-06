<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Invitation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'invitations';

    protected $fillable = [
        'template_id',
        'template_slug',
        'groom_full_name',
        'groom_nickname',
        'groom_father_name',
        'groom_mother_name',
        'groom_address',
        'groom_photo',
        'bride_full_name',
        'bride_nickname',
        'bride_father_name',
        'bride_mother_name',
        'bride_address',
        'bride_photo',
        'has_akad',
        'akad_date',
        'akad_time',
        'akad_location',
        'has_reception',
        'receptions',
        'maps',
        'has_gift',
        'gift_image',
        'bank_accounts',
        'has_gallery',
        'gallery_photos',
        'gallery_videos',
        'is_wish_active',
        'template_settings',
        'status',
        'slug'
    ];

    protected $casts = [
        'receptions' => 'array',
        'maps' => 'array',
        'bank_accounts' => 'array',
        'gallery_photos' => 'array',
        'gallery_videos' => 'array',
        'template_settings' => 'array',
        'has_akad' => 'boolean',
        'has_reception' => 'boolean',
        'has_gift' => 'boolean',
        'has_gallery' => 'boolean',
        'is_wish_active' => 'boolean',
        'akad_date' => 'date',
        'akad_time' => 'datetime:H:i',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invitation) {
            $invitation->slug = Str::slug($invitation->groom_full_name . '-' . $invitation->bride_full_name . '-' . uniqid());
            if ($invitation->template_id) {
                $template = Template::find($invitation->template_id);
                if ($template) {
                    $invitation->template_slug = $template->slug;
                }
            }
        });
    }

    // Relationships
    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }

    public function wishes()
    {
        return $this->hasMany(Wish::class);
    }

    // Accessors
    public function getFullTitleAttribute()
    {
        return $this->groom_full_name . ' & ' . $this->bride_full_name;
    }

    public function getWeddingDateAttribute()
    {
        if ($this->has_akad && $this->akad_date) {
            return $this->akad_date;
        }
        
        if ($this->has_reception && $this->receptions && count($this->receptions) > 0) {
            return $this->receptions[0]['date'] ?? null;
        }
        
        return null;
    }

    public function getTemplateViewPathAttribute()
    {
        $template = $this->template;
        if ($template && $template->blade_file) {
            return 'templates.' . $template->slug;
        }
        return 'templates.default';
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Helper methods
    public function getReceptionDates()
    {
        if (!$this->has_reception || !$this->receptions) {
            return collect();
        }
        
        return collect($this->receptions);
    }

    public function getBankAccounts()
    {
        if (!$this->has_gift || !$this->bank_accounts) {
            return collect();
        }
        
        return collect($this->bank_accounts);
    }

    public function getGallery()
    {
        $photos = $this->gallery_photos ?? [];
        $videos = $this->gallery_videos ?? [];
        
        return [
            'photos' => collect($photos),
            'videos' => collect($videos)
        ];
    }

    public function getMaps()
    {
        return collect($this->maps ?? []);
    }

    public function getTemplateSettings()
    {
        $template = $this->template;
        $defaultSettings = $template->settings ?? [];
        $customSettings = $this->template_settings ?? [];
        
        return array_merge($defaultSettings, $customSettings);
    }
}