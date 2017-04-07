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

use App\Http\Controllers\Controller;
use Tremby\LaravelGitVersion\GitVersionHelper;

class ApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'api' => config('app.name'),
            'version' => GitVersionHelper::getVersion(),
            'stream' => config('app.env'),
        ]);
    }
}
