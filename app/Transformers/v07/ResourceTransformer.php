<?php

namespace App\Transformers\v07;

use App\Resource;
use League\Fractal\TransformerAbstract;

/**
 * Class ResourceTransformer.
 */
class ResourceTransformer extends TransformerAbstract
{
    public function transform(Resource $resource)
    {
        return [
            'name' => $resource->slug,
            'pretty_name' => $resource->name,
            'author' => $resource->author,
            'description' => $resource->description,
            'link' => $resource->link,
            'donate' => null,
            'versions' => $resource->versions->pluck('version'),
        ];
    }
}
