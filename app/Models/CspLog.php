<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CspLog extends Model
{
    protected $fillable = [
        'document_uri',
        'blocked_uri',
        'violated_directive',
        'effective_directive',
        'ip_address',
    ];
}