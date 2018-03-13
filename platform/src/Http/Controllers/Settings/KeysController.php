<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Platform\Http\Controllers\Settings;

use Exception;
use Platform\Key;
use App\Http\Controllers\Controller;
use Platform\Http\Resources\KeyResource;
use Platform\Http\Resources\ClientResource;

class KeysController extends Controller
{
    /**
     * List all keys.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', Key::class);

        return KeyResource::collection(Key::all());
    }

    /**
     * Store a posted key.
     *
     * @return ClientResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store()
    {
        $this->authorize('create', Key::class);

        $this->validate(request(), [
            'name' => ['required', 'unique:keys'],
            'token' => ['required', 'unique:keys'],
        ]);

        $key = Key::create([
            'name' => request('name'),
            'token' => request('token'),
        ]);

        return new ClientResource($key);
    }

    /**
     * Delete a key.
     *
     * @param Key $key
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Key $key)
    {
        $this->authorize('delete', $key);

        try {
            $key->delete();
        } catch (Exception $e) {
            abort(500, $e->getMessage());
        }

        return response(null, 204);
    }
}
