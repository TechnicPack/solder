<?php

namespace App\Transformers\v07;

use App\Version;
use League\Fractal\TransformerAbstract;

/**
 * Class VersionTransformer.
 */
class VersionTransformer extends TransformerAbstract
{
    public function transform(Version $version)
    {
        return [
            'name' => $version->slug,
            'version' => $version->version,
            'md5' => @$version->archive->md5,
            'url' => @$version->archive->url,
            'filesize' => @$version->archive->size,
        ];
    }
}
