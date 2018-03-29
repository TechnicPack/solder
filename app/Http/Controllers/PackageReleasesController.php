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

use ZipArchive;
use App\Package;
use App\Release;
use App\Facades\FileHash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
        $this->authorize('create', Release::class);

        $package = Package::where('slug', $packageSlug)->firstOrFail();

        request()->validate([
            'version' => ['required', 'regex:/^[a-zA-Z0-9_-][.a-zA-Z0-9_-]*$/', Rule::unique('releases')->where('package_id', $package->id)],
            'type' => ['required'],
            'file' => ['required', 'min:1', 'mimes:zip,jar,cfg,json'],
        ]);
        $file_name = $_FILES['file']['name'];
        $file_info = pathinfo($file_name);
        $tmp_file = $request->file('file')->store('tmp');
        $package_name = "{$package->slug}-{$request->version}.zip";
        if (! Storage::exists("modpack/{$package->slug}")) {
            Storage::makeDirectory("modpack/{$package->slug}");
        }
        if ($file_info['extension'] == 'zip') {
            Storage::move($tmp_file, "modpack/{$package->slug}/".$package_name);
        } else {
            $archive = new ZipArchive();
            $archive_path = storage_path("app/public/modpack/{$package->slug}");
            if ($archive->open($archive_path.'/'.$package_name, ZipArchive::CREATE) === TRUE){
                $archive->addFile(storage_path('app/public/'.$tmp_file), request()->input('type').'/'.$file_name);
            }
            $archive->close();

        }
        Storage::disk('public')->delete($tmp_file);

        $hash_path = url('/').'/'.'storage/'.'{$package->slug}/'.$package_name;

        Release::create([
            'package_id' => $package->id,
            'version' => $request->version,
            'path' => $package_name,
            'md5' => FileHash::hash($hash_path),
            'filesize' => request()->file('file')->getSize(),
        ]);

        return redirect("/library/$packageSlug");
    }
}
