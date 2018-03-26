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
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

use ZipArchive;
//use File;

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
        $this->authorize('create', Release::class);

        $package = Package::where('slug', $packageSlug)->firstOrFail();

        request()->validate([
            'version' => ['required', 'regex:/^[a-zA-Z0-9_-][.a-zA-Z0-9_-]*$/', Rule::unique('releases')->where('package_id', $package->id)],
            'type' => ['required'],
            'file' => ['required'],
        ]);

        $file_name = $_FILES['file']['name'];

        $file = $request->file('file')->store('tmp');
        $archive = new ZipArchive();
        $archive_directory = "modpack/" . $package->slug;
        $public = storage_path('app/public/');
        $archive_name = $archive_directory . "/" . "{$package->slug}-{$request->version}.zip";
        $hash_path = url('/') . "storage/" . "{$package->slug}-{$request->version}.zip";
        $storage_path = "{$package->slug}" . "/" . "{$package->slug}-{$request->version}.zip";



        if(!Storage::exists($archive_directory)) {
            Storage::makeDirectory($archive_directory);
        }

        if($archive->open($public . "/" . $archive_name, ZipArchive::CREATE) === TRUE){
            $archive->addFile(storage_path("app/public/" . $file), "mods/". $file_name);
        }

        $archive->close();

        Storage::disk('public')->delete($file);

        Release::create([
            'package_id' => $package->id,
            'version' => $request->version,
            'path' => $storage_path,
            'md5' => FileHash::hash($hash_path),
            'filesize' => request()->file('file')->getSize(),
        ]);

        return redirect("/library/$packageSlug");
    }
}
