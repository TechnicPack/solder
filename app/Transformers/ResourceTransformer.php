<?php

namespace App\Transformers;

use App\Resource;
use League\Fractal\TransformerAbstract;

class ResourceTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['versions'];

    public function transform(Resource $resource)
    {
        return [
            'name' => $resource->name,
            'slug' => $resource->slug,
            'id' => $resource->getRouteKey(),
            'author' => $resource->author,
            'description' => $resource->description,
            'link' => $resource->link,
            'created_at' => $resource->created_at->format('c'),
            'updated_at' => $resource->updated_at->format('c'),
        ];
    }

    public function includeVersions(Resource $mod)
    {
        return fractal()
            ->collection($mod->versions)
            ->transformWith(new VersionTransformer())
            ->withResourceName('versions')
            ->getResource();
    }
}
