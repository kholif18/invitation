<?php
// app/Models/Wish.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wish extends Model
{
    use HasFactory;

    protected $table = 'wishes';

    protected $fillable = [
        'invitation_id',
        'guest_id',
        'guest_name',
        'message',
        'attendance',
        'attendance_count',
        'is_approved'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'attendance_count' => 'integer'
    ];

    // Relationships
    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeAttending($query)
    {
        return $query->where('attendance', 'yes');
    }

    public function scopeNotAttending($query)
    {
        return $query->where('attendance', 'no');
    }
    
    public function scopeMaybe($query)
    {
        return $query->where('attendance', 'maybe');
    }
}