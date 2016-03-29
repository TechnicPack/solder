<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Modpack extends Model implements SluggableInterface
{
    use SluggableTrait;

    protected $fillable = [
        'slug', 'name', 'hidden', 'private'
    ];

    protected $sluggable = [
        'build_from' => 'name',
        'save_to' => 'slug'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function builds()
    {
        $this->hasMany(Build::class);
    }

}
