<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Build extends Model
{
    protected $fillable = [
        'slug', 'modpack_id', 'version', 'minecraft', 'published', 'private', 'min_java', 'min_memory'
    ];

    public function modpack()
    {
        $this->belongsTo(Modpack::class);
    }

}
