<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvitationLink extends Model
{
    protected $fillable = [
        'invitation_id',
        'guest_id',
        'token',
        'type',
        'status',
        'views',
        'expires_at'
    ];
    
    protected $casts = [
        'expires_at' => 'datetime',
        'views' => 'integer'
    ];
    
    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }
    
    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
    
    public function incrementViews()
    {
        $this->increment('views');
    }
    
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
    
    public function isActive()
    {
        return $this->status === 'active' && !$this->isExpired();
    }
}
