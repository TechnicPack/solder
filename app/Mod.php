<?php

namespace App;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;

class Mod extends Model implements SluggableInterface
{
    use SluggableTrait;

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'slug', 'name', 'description', 'author', 'link', 'donatelink',
    ];

    /**
     * Get the fields used to build and save the slug
     *
     * @var array
     */
    protected $sluggable = [
        'build_from' => 'name',
        'save_to'    => 'slug',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * A mod has many versions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function versions()
    {
        return $this->hasMany(Modversion::class);
    }
}
