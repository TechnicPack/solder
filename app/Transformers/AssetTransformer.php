<?php

namespace App\Transformers;

use App\Asset;
use League\Fractal\TransformerAbstract;

class AssetTransformer extends TransformerAbstract
{
    public function transform(Asset $asset)
    {
        return [
            'id' => $asset->getRouteKey(),
            'url' => $asset->url,
            'size' => (int) $asset->filesize,
            'md5' => $asset->md5,
        ];
    }
}
