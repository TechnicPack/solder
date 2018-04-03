<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
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
        return view('dashboard.show', [
            'builds' => Build::recent(5),
            'releases' => Release::recent(5),
        ]);
    }
}
