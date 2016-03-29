<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modversion extends Model
{
    protected $fillable = [
        'slug', 'mod_id', 'version', 'md5'
    ];

    public function mod()
    {
        $this->belongsTo(Mod::class);
    }
}
