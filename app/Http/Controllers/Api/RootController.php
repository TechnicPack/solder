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

use Tremby\LaravelGitVersion\GitVersionHelper;

class RootController extends ApiController
{
    public function index()
    {
        return response([
            'api' => config('app.name'),
            'version' => GitVersionHelper::getVersion(),
            'stream' => config('app.env'),
        ]);
    }
}
