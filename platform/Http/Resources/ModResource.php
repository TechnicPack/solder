<?php

namespace Platform\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Storage;

class ModResource extends Resource
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
            'name' => $this->package->name,
            'version' => $this->version,
            'md5' => $this->md5,
            'url' => Storage::url($this->path),
        ];
    }
}
