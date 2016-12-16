<?php

namespace App\Transformers;

use App\Build;
use League\Fractal\TransformerAbstract;

class BuildTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['versions', 'modpack', 'assets'];

    public function transform(Build $build)
    {
        return [
            'id' => $build->getRouteKey(),
            'version' => $build->version,
            'tags' => $build->tags,
            'published_at' => $build->published_at->format('c'),
            'created_at' => $build->created_at->format('c'),
            'updated_at' => $build->updated_at->format('c'),
        ];
    }

    public function includeVersions(Build $build)
    {
        return $this->collection($build->versions, new VersionTransformer(), 'version');
    }

    public function includeModpack(Build $build)
    {
        return $this->item($build->modpack, new ModpackTransformer(), 'modpack');
    }
}
