<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\UniqueClick;
use Carbon\Carbon;

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
        'expires_at',
    ];

    // Indicate that the primary key is not an incrementing integer
    public $incrementing = false;

    // Specify the primary key type
    protected $keyType = 'string';

    protected $casts = [
        'expires_at' => 'datetime',
    ];
    /**
     * Check if the shortlink is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Scope to filter active and non-expired shortlinks.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

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
