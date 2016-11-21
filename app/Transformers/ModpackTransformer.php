<?php

namespace App\Transformers;

use App\Modpack;
use League\Fractal\TransformerAbstract;

/**
 * Class ModpackTransformer
 */
class ModpackTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'builds',
        'icon',
        'logo',
        'background',
    ];

    public function transform(Modpack $modpack)
    {
        return [
            'name' => $modpack->name,
            'id' => $modpack->getRouteKey(),
            'published' => (bool) $modpack->published,
            'created_at' => $modpack->created_at->format('c'),
            'updated_at' => $modpack->updated_at->format('c'),
        ];
    }

    public function includeBuilds(Modpack $modpack)
    {
    }

    public function includeIcon(Modpack $modpack)
    {
    }

    public function includeLogo(Modpack $modpack)
    {
    }

    public function includeBackground(Modpack $modpack)
    {
    }
}
