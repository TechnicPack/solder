<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Platform\Http\Resources\Api;

use Illuminate\Http\Resources\Json\Resource;

class BuildResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'minecraft' => $this->minecraft_version,
            'java' => $this->java_version,
            'memory' => (int) $this->required_memory,
            'forge' => $this->forge_version,
            'mods' => ModResource::collection($this->releases),
        ];
    }
}
