<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedDomain extends Model
{
    protected $table = 'csp_blocked_domains';
    
    protected $fillable = [
        'domain',
        'action',
        'reason',
        'hit_count'
    ];

    public function incrementHitCount()
    {
        $this->increment('hit_count');
    }
}