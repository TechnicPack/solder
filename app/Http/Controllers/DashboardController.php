<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers;

use App\Build;
use App\Release;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('dashboard', [
            'builds' => Build::recent(5),
            'releases' => Release::recent(5),
        ]);
    }
}
