<?php

/*
 * This file is part of TechnicSolder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Transformers;

use App\Version;
use League\Fractal\TransformerAbstract;

class VersionTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */
    protected $availableIncludes = [
        'assets',
    ];

    /**
     * Transform the Version entity.
     *
     * @param Version $version
     *
     * @return array
     */
    public function transform(Version $version)
    {
        return [
            'id' => $version->id,
            'version' => $version->version,
            'created_at' => $version->created_at->format('c'),
            'updated_at' => $version->updated_at->format('c'),
        ];
    }

    /**
     * Include Assets.
     *
     * @param Version $version
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeAssets(Version $version)
    {
        return $this->collection($version->assets, AssetTransformer::class, 'asset');
    }
}
