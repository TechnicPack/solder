<?php

namespace App\Transformers\v07;

use App\Mod;
use League\Fractal\TransformerAbstract;

/**
 * Class ModTransformer.
 */
class ModTransformer extends TransformerAbstract
{
    public function transform(Mod $mod)
    {
        return [
            'name' => $mod->slug,
            'pretty_name' => $mod->name,
            'author' => $mod->author,
            'description' => $mod->description,
            'link' => $mod->link,
            'donate' => null,
            'versions' => $mod->releases->pluck('version'),
        ];
    }
}
