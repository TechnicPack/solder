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

use App\Package;

class PackagesController extends Controller
{
    /**
     * Display all packages.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('packages.index', [
            'packages' => Package::orderBy('name')->get(),
        ]);
    }

    /**
     * Show details of a specific package and its releases.
     *
     * @param $packageSlug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($packageSlug)
    {
        $package = Package::where('slug', $packageSlug)
            ->with(['releases' => function ($query) {
                $query->orderBy('version', 'desc');
            }])
            ->first();

        return view('packages.show', [
            'package' => $package,
            'packages' => Package::orderBy('name')->get(),
        ]);
    }
}
