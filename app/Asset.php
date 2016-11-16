<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\UuidModelTrait;

/**
 * @property string id
 * @property string path
 * @property string disk
 * @property string md5
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
}
