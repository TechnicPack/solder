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

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Users legacy tokens.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tokens()
    {
        return $this->hasMany(Token::class);
    }

    /**
     * Users legacy tokens.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function modpacks()
    {
        return $this->belongsToMany(Modpack::class);
    }

    public static function findByToken($value)
    {
        if ($value === null) {
            return;
        }

        return self::whereHas('tokens', function ($query) use ($value) {
            $query->where('value', $value);
        })->first();
    }

    public function addToken($name, $value)
    {
        $this->tokens()->create([
            'name' => $name,
            'value' => $value,
        ]);

        return $this;
    }
}
