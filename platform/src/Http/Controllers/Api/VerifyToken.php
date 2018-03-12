<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Platform\Http\Controllers\Api;

use Platform\Key;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VerifyToken extends Controller
{
    /**
     * Return a JSON response confirming the validity of a given API key.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke($token)
    {
        try {
            $key = Key::where('token', $token)->firstOrFail();

            return response()->json([
                'name' => $key->name,
                'valid' => 'Key Validated.',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Key does not exist.']);
        }
    }
}
