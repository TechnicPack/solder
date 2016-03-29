<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Mod extends Model implements SluggableInterface
{
    use SluggableTrait;
    
    protected $fillable = [
        'slug', 'name', 'description', 'author', 'link', 'donatelink'
    ];

    protected $sluggable = [
        'build_from' => 'name',
        'save_to' => 'slug'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function versions()
    {
        $this->hasMany(Modversion::class);
    }
}
