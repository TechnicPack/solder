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
use Illuminate\Support\Facades\Storage;

class Release extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * A Release belongs to a Package.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get created date formatted for humans.
     *
     * @return string
     */
    public function getCreatedAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get the full download url for the file.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return Storage::url($this->path);
    }

    public function getFilenameAttribute()
    {
        return basename($this->path);
    }

    /**
     * Get the most recent releases and associated packages.
     *
     * @param $count
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public static function recent($count)
    {
        return self::latest()->take($count)->with('package')->get();
    }
}
