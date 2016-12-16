<?php

namespace App;

use Alsofronie\Uuid\UuidModelTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string id
 * @property string path
 * @property string disk
 * @property string md5
 * @property string url
 * @property int filesize
 */
class Asset extends Model
{
    use UuidModelTrait;

    const MORPH_NAME = 'resource';

    /**
     * The table associated with the model.
     */
    protected $table = 'assets';

    /**
     * Get all of the owning attachable models.
     */
    public function attachable()
    {
        return $this->morphTo();
    }

    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        // TODO: Implement function
        return '';
    }

    /**
     * @return int
     */
    public function getFilesizeAttribute()
    {
        // TODO: Implement function
        return 0;
    }
}
