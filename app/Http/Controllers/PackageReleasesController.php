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

class PackageReleasesController extends Controller
{
    /**
     * Store the posted Release.
     *
     * @param Request $request
     *
     * @param $packageSlug
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, $packageSlug)
    {
        $package = Package::where('slug', $packageSlug)->firstOrFail();

        $archive = request()
            ->file('archive')
            ->storeAs($package->slug, "{$package->slug}-{$request->version}.zip");

        Release::create([
            'package_id' => $package->id,
            'version' => $request->version,
            'path' => $archive,
            'md5' => FileHash::hash(Storage::url($archive)),
            'filesize' => request()->file('archive')->getSize(),
        ]);

        return redirect("/library/$packageSlug");
    }
}
