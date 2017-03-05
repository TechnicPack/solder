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

use App\Traits\HasPrivacy;
use Alsofronie\Uuid\UuidModelTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Build.
 *
 * @property string $id
 * @property string $modpack_id
 * @property string $version
 * @property string $game_version
 * @property string $changelog
 * @property string $privacy
 * @property array $arguments
 * @property bool $is_promoted
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Modpack $modpack
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Version[] $versions
 * @method static \Illuminate\Database\Query\Builder|\App\Build whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Build whereModpackId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Build whereVersion($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Build whereChangelog($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Build wherePrivacy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Build whereArguments($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Build whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Build whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Build displayable()
 * @mixin \Eloquent
 */
class Build extends Model
{
    use HasPrivacy;
    use UuidModelTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'version',
        'game_version',
        'changelog',
        'privacy',
        'arguments',
    ];

    /**
     * The model's default attributes.
     *
     * @var array
     */
    protected $attributes = [
        'privacy' => Privacy::PRIVATE,
        'is_promoted' => false,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'arguments' => 'array',
        'is_promoted' => 'boolean',
    ];

    /**
     * It belongs to a Modpack.
     */
    public function modpack()
    {
        return $this->belongsTo(Modpack::class);
    }

    /**
     * It belongs to many versions.
     */
    public function versions()
    {
        return $this->belongsToMany(Version::class);
    }

    /**
     * Flag as the promoted build.
     */
    public function promote()
    {
        self::where('modpack_id', $this->modpack_id)->update([
            'is_promoted' => false,
        ]);

        $this->is_promoted = true;
        $this->save();
    }
}
