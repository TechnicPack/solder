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
use App\Release;
use App\Facades\FileHash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReleasesController extends Controller
{
    /**
     * Show the create form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('releases.create', [
            'packages' => Package::all(),
        ]);
    }

    /**
     * Store the posted Release.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $package = Package::findOrFail($request->package_id);

        $archive = request()
            ->file('archive')
            ->storeAs($package->slug, "{$package->slug}-{$request->version}.zip");

        Release::create([
            'package_id' => $request->package_id,
            'version' => $request->version,
            'path' => $archive,
            'md5' => FileHash::hash(Storage::url($archive)),
        ]);

        return redirect('/dashboard');
    }
}
