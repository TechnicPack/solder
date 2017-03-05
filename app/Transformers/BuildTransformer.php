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

use App\Build;
use League\Fractal\TransformerAbstract;

class BuildTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */
    protected $availableIncludes = [
        'modpack',
    ];

    /**
     * Transform the \Modpack entity.
     * @param Build $build
     * @return array
     */
    public function transform(Build $build)
    {
        return [
            'id' => $build->id,
            'version' => $build->version,
            'changelog' => $build->changelog,
            'privacy' => $build->privacy,
            'arguments' => $build->arguments,
            'game_version' => $build->game_version,
            'is_promoted' => $build->is_promoted,
            'created_at' => $build->created_at->format('c'),
            'updated_at' => $build->updated_at->format('c'),
        ];
    }

    /**
     * Include Modpack.
     * @param Build $build
     * @return \League\Fractal\Resource\Item
     */
    public function includeModpack(Build $build)
    {
        return $this->item($build->modpack, new ModpackTransformer(), 'modpack');
    }
}
