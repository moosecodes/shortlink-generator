<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Metadata extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'metadatas';

    protected $fillable = [
        'shortlink_id',
        'meta_key',
        'meta_value',
    ];

    public function shortlink()
    {
        return $this->belongsTo(Shortlink::class, 'shortlink_id', 'id');
    }
}
