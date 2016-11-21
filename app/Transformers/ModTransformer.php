<?php

namespace App\Transformers;

use App\Mod;
use League\Fractal\TransformerAbstract;

class ModTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['releases'];

    public function transform(Mod $mod)
    {
        return [
            'name' => $mod->name,
            'slug' => $mod->slug,
            'id' => $mod->getRouteKey(),
            'author' => $mod->author,
            'description' => $mod->description,
            'link' => $mod->link,
            'created_at' => $mod->created_at->format('c'),
            'updated_at' => $mod->updated_at->format('c'),
        ];
    }

    public function includeReleases(Mod $mod)
    {
        return fractal()
            ->collection($mod->releases)
            ->transformWith(new ReleaseTransformer())
            ->withResourceName('release')
            ->getResource();
    }
}
