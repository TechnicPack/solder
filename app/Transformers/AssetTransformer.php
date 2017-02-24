<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Transformers;

use App\Asset;
use League\Fractal\TransformerAbstract;

class AssetTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */
    protected $availableIncludes = [
    ];

    /**
     * Transform the Version entity.
     *
     * @param Asset $asset
     *
     * @return array
     */
    public function transform(Asset $asset)
    {
        return [
            'id' => $asset->id,
            'filename' => $asset->filename,
            'location' => $asset->location,
            'filesize' => $asset->filesize,
            'md5' => $asset->md5,
            'url' => $asset->url,
            'created_at' => $asset->created_at->format('c'),
            'updated_at' => $asset->updated_at->format('c'),
        ];
    }
}
