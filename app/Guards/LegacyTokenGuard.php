<?php

/*
 * This file is part of solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Guards;

use App\Token;
use Illuminate\Http\Request;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;

class LegacyTokenGuard implements Guard
{
    use GuardHelpers;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The name of the query string items from the request containing the API token.
     *
     * @var string
     */
    protected $inputKeys;

    /**
     * The name of the token "column" in persistent storage.
     *
     * @var string
     */
    protected $storageKey;

    /**
     * Create a new authentication guard.
     *
     * @param  \Illuminate\Contracts\Auth\UserProvider  $provider
     * @param  \Illuminate\Http\Request  $request
     */
    public function __construct(UserProvider $provider, Request $request)
    {
        $this->request = $request;
        $this->provider = $provider;
        $this->inputKeys = ['k', 'cid'];
        $this->storageKey = 'token';
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        if (! $token = $this->getTokenForRequest()) {
            return;
        }

        // TODO: reaching into the Token model is not ideal
        if (! $userId = Token::where($this->storageKey, $token)->pluck('user_id')->first()) {
            return;
        }

        $user = $this->provider->retrieveById($userId);

        return $this->user = $user;
    }

    /**
     * Get the token for the current request.
     *
     * @return string
     */
    public function getTokenForRequest()
    {
        return array_first($this->request->query(), function ($value, $key) {
            return array_has(array_flip($this->inputKeys), $key);
        });
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        $token = array_first($credentials, function ($value, $key) {
            return array_has(array_flip($this->inputKeys), $key);
        });

        if ($token === null) {
            return false;
        }

        $credentials = [$this->storageKey => $token];

        // TODO: reaching into the Token model is not ideal
        $userId = Token::where($credentials)->pluck('user_id')->first();

        if ($this->provider->retrieveById($userId)) {
            return true;
        }

        return false;
    }
}
