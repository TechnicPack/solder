<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $guarded = [];

    /**
     * Belongs to a resource.
     */
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function getLinkSelfAttribute()
    {
        return \Config::get('app.url')."/api/versions/{$this->id}";
    }

    public function getZipUrlAttribute()
    {
        return \Storage::url($this->zip_path);
    }

    public function toArray()
    {
        return [
            'name' => $this->resource->slug,
            'version' => $this->version_number,
            'url' => $this->zip_url,
            'md5' => $this->zip_md5,
        ];
    }
}
