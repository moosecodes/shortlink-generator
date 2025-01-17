<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UniqueClick extends Model
{
    use HasFactory;

    protected $fillable = [
        'shortlink_id',
        'ip_address',
        'device',
        'browser',
        'referrer',
        'user_agent',
    ];

    /**
     * Boot function to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically generate UUID for the 'id' field
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    /**
     * Define the relationship to locations.
     */
    public function locations()
    {
        return $this->hasMany(Location::class, 'ip_address', 'ip_address');
    }

    public function shortlink()
    {
        return $this->belongsTo(Shortlink::class);
    }
}
