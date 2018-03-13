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

use App\Http\Controllers\Controller;

class DescribeApi extends Controller
{
    /**
     * Return a JSON response describing the API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke()
    {
        return response()->json([
            'api' => 'SolderIO',
            'version' => config('app.version'),
            'stream' => config('app.env'),
        ]);
    }
}
