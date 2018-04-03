<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Transformers;

use App\Package;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class PackageTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */
    protected $availableIncludes = [
        'releases',
    ];

    /**
     * Turn this item object into a generic array.
     *
     * @param Package $package
     *
     * @return array
     */
    public function transform($package)
    {
        return [
            'id' => $package->id,
            'name' => $package->name,
        ];
    }

    /**
     * Include Releases.
     *
     * @param Package $package
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeReleases($package)
    {
        $releases = $package->releases;

        return $this->collection($releases, new ReleaseTransformer);
    }
}
