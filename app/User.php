<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Alsofronie\Uuid\UuidModelTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property string id
 * @property string name
 * @property string email
 * @property string password
 */
class User extends Authenticatable
{
    use Notifiable;
    use UuidModelTrait;
    use HasApiTokens;

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

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
