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
use App\Modpack;
use App\Version;
use App\Resource;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modpackCount = Modpack::all()->count();
        $buildCount = Build::all()->count();
        $resourceCount = Resource::all()->count();
        $versionCount = Version::all()->count();

        $recentBuilds = Build::with('modpack')->orderby('updated_at', 'desc')->limit(5)->get();
        $recentVersions = Version::with('resource')->latest()->limit(5)->get();

        return view('dashboard.index', compact(
            'modpackCount',
            'buildCount',
            'resourceCount',
            'versionCount',
            'recentBuilds',
            'recentVersions'
        ));
    }
}
