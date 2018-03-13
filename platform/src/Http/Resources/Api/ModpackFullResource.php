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

class ModpackFullResource extends Resource
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
            'name' => $this->slug,
            'display_name' => $this->name,
            'recommended' => $this->recommended_build->version,
            'latest' => $this->latest_build->version,
            'builds' => $this->builds->pluck('version'),
        ];
    }
}
