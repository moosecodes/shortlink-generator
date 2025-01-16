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
        'short_code',
        'short_url',
        'is_active',
        'original_url',
        'total_clicks',
        'unique_clicks',
    ];

    // Indicate that the primary key is not an incrementing integer
    public $incrementing = false;

    // Specify the primary key type
    protected $keyType = 'string';

    public function uniqueClicks()
    {
        return $this->hasMany(UniqueClick::class);
    }

    public function metadata()
    {
        return $this->hasMany(ShortlinkMetadata::class, 'shortlink_id');
    }
}
