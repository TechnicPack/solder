<?php

namespace App\Transformers;

use App\Build;
use League\Fractal\TransformerAbstract;

class BuildTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['releases'];

    public function transform(Build $build)
    {
        return [
            'id' => $build->getRouteKey(),
            'version' => $build->version,
            'tags' => $build->tags,
            'published' => (bool) $build->published,
            'created_at' => $build->created_at->format('c'),
            'updated_at' => $build->updated_at->format('c'),
        ];
    }

    public function includeReleases(Build $build)
    {
        return $this->collection($build->packages, new ModTransformer(), 'package');
    }
}
