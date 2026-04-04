<?php
// app/Models/Guest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Guest extends Model
{
    use HasFactory;

    protected $table = 'guests';

    protected $fillable = [
        'invitation_id',
        'name',
        'email',
        'phone',
        'sending_method',
        'attendance_status',
        'number_of_guests',
        'message',
        'invitation_code',
        'is_sent',
        'sent_at',
        'viewed_at'
    ];

    protected $casts = [
        'is_sent' => 'boolean',
        'sent_at' => 'datetime',
        'viewed_at' => 'datetime',
        'number_of_guests' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($guest) {
            $guest->invitation_code = Str::random(10) . uniqid();
        });
    }

    // Relationships
    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }

    public function wishes()
    {
        return $this->hasMany(Wish::class);
    }

    // Accessors
    public function getInvitationUrlAttribute()
    {
        return route('invitation.show', [
            'slug' => $this->invitation->slug,
            'code' => $this->invitation_code
        ]);
    }

    // Scopes
    public function scopeSent($query)
    {
        return $query->where('is_sent', true);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('attendance_status', 'confirmed');
    }

    // Helper methods
    public function markAsSent()
    {
        $this->update([
            'is_sent' => true,
            'sent_at' => now()
        ]);
    }

    public function markAsViewed()
    {
        if (!$this->viewed_at) {
            $this->update(['viewed_at' => now()]);
        }
    }
}