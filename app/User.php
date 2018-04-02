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

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'is_admin',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_admin' => 'boolean',
    ];

    /**
     * A user has many Roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this
            ->belongsToMany(Role::class, 'permissions')
            ->withTimestamps();
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function grantRole($role)
    {
        $this->roles()->attach(Role::where('tag', $role)->first());
    }

    public function switchToTeam($team)
    {
        $this->current_team_id = $team->id;
        $this->save();
    }

    public function currentTeam()
    {
        if (is_null($this->current_team_id)) {
            $this->switchToTeam($this->teams->first());

            return $this->currentTeam();
        }

        return $this->teams->find($this->current_team_id);
    }

    public function getCurrentTeamAttribute()
    {
        return $this->currentTeam();
    }
}
