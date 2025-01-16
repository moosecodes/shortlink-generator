<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniqueClick extends Model
{
    use HasFactory;

    protected $fillable = [
        'shortlink_id',
        'ip_address',
        'device',
        'browser',
        'country',
        'referrer',
        'user_agent',
    ];

    public function shortlink()
    {
        return $this->belongsTo(Shortlink::class);
    }
}
