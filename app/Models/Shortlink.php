<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\UniqueClick;

class Shortlink extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'short_code',
        'short_url',
        'is_active',
        'is_premium',
        'original_url',
        'total_clicks',
        'unique_clicks',
    ];

    // Indicate that the primary key is not an incrementing integer
    public $incrementing = false;

    // Specify the primary key type
    protected $keyType = 'string';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function uniqueClicks()
    {
        return $this->hasMany(UniqueClick::class);
    }

    public function metadatas()
    {
        return $this->hasMany(Metadata::class, 'shortlink_id');
    }
}
