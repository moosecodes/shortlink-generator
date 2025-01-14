<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ShortlinkMetadata extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'shortlink_id',
        'meta_key',
        'meta_value',
    ];

    protected $table = 'shortlinks_metadata';

    public function shortlink()
    {
        return $this->belongsTo(Shortlink::class, 'shortlink_id', 'id');
    }
}
