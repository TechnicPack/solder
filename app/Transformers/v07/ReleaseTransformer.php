<?php

namespace App\Transformers\v07;

use App\Release;
use League\Fractal\TransformerAbstract;

/**
 * Class ReleaseTransformer.
 */
class ReleaseTransformer extends TransformerAbstract
{
    public function transform(Release $release)
    {
        return [
            'name' => $release->slug,
            'version' => $release->version,
            'md5' => @$release->archive->md5,
            'url' => @$release->archive->url,
            'filesize' => @$release->archive->size,
        ];
    }
}
