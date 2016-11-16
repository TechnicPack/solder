<?php

namespace App\Transformers\v07;

use App\Build;
use App\Serializers\FlatSerializer;
use League\Fractal\TransformerAbstract;

/**
 * Class BuildTransformer.
 */
class BuildTransformer extends TransformerAbstract
{
    public function transform(Build $build)
    {
        return [
            'minecraft' => isset($build->tags['minecraft']) ? $build->tags['minecraft'] : null,
            'forge' => isset($build->tags['forge']) ? $build->tags['forge'] : null,
            'java' => isset($build->tags['java']) ? $build->tags['java'] : null,
            'memory' => isset($build->tags['memory']) ? $build->tags['memory'] : null,
            'mods' => fractal()
                ->collection($build->releases)
                ->serializeWith(new FlatSerializer())
                ->transformWith(new ReleaseTransformer())
                ->toArray(),
        ];
    }
}
