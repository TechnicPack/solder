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

use App\Resource;
use League\Fractal\TransformerAbstract;

class ResourceTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */
    protected $availableIncludes = [
        'versions',
    ];

    /**
     * Transform the Resource entity.
     *
     * @param Resource $resource
     *
     * @return array
     */
    public function transform(Resource $resource)
    {
        return [
            'id' => $resource->id,
            'name' => $resource->name,
            'slug' => $resource->slug,
            'author' => $resource->author,
            'description' => $resource->description,
            'website' => $resource->website,
            'created_at' => $resource->created_at->format('c'),
            'updated_at' => $resource->updated_at->format('c'),
        ];
    }

    /**
     * Include Versions.
     *
     * @param Resource $resource
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeVersions(Resource $resource)
    {
        return $this->collection($resource->versions, new VersionTransformer(), 'version');
    }
}
