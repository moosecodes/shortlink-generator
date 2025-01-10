<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UniqueClick;

class Shortlink extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_url',
        'short_code',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'is_active',
        'total_clicks',
        'unique_clicks',
    ];

    public function uniqueClicks()
    {
        return $this->hasMany(UniqueClick::class);
    }
}
