<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Location extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'ip_address',
        'driver',
        'country_name',
        'country_code',
        'region_code',
        'region_name',
        'city_name',
        'zip_code',
        'iso_code',
        'postal_code',
        'latitude',
        'longitude',
        'metro_code',
        'area_code',
        'timezone',
    ];

    /**
     * Get the unique click that owns the location.
     */
    public function uniqueClick()
    {
        return $this->belongsTo(UniqueClick::class, 'ip_address', 'ip_address');
    }
}
