<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Api;

use App\Token;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LegacyTokenController extends ApiController
{
    /**
     * Display the specified token.
     *
     * @param $tokenValue
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($tokenValue)
    {
        try {
            $token = Token::whereValue($tokenValue)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return $this->notFoundError('Invalid key provided.');
        }

        return response()->json([
            'name' => $token->name,
            'valid' => 'Key validated.',
        ]);
    }
}
