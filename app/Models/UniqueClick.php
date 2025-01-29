<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Location;
use App\Models\Shortlink;

class UniqueClick extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'shortlink_id',
        'ip_address',
        'device',
        'browser',
        'referrer',
        'user_agent',
    ];

    protected $keyType = 'string';

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
