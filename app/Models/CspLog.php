<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CspLog extends Model
{
    protected $table = 'csp_logs';
    
    protected $fillable = [
        'document_uri',
        'blocked_uri',
        'violated_directive',
        'effective_directive',
        'ip_address',
        'user_agent',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function getDomainAttribute()
    {
        if (empty($this->blocked_uri)) return null;
        $parsed = parse_url($this->blocked_uri);
        return $parsed['host'] ?? null;
    }
}