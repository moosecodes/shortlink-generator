<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClicksOverTime extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'shortlink_id',
        'ip_address',
        'referrer',
        'clicked_at'
    ];

    protected $keyType = 'string';

    public function shortlink()
    {
        return $this->belongsTo(Shortlink::class);
    }
}
