<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageLog extends Model
{
    protected $fillable = [
        'guest_id',
        'invitation_id',
        'type',
        'message',
        'sent_at',
        'delivered_at',
        'read_at'
    ];
    
    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime'
    ];
    
    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
    
    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }
}
