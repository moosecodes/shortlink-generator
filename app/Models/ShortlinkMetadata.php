<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortlinkMetadata extends Model
{
    protected $table = 'shortlinks_metadata';
    protected $fillable = ['shortlink_id', 'key', 'value'];

    public function shortlink()
    {
        return $this->belongsTo(Shortlink::class, 'shortlink_id', 'id');
    }
}
