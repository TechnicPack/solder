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

class StorageController extends Controller
{
    public function getModFile($slug, $file_name)
    {
        $path = storage_path('app/public/modpack/'.$slug.'/'.$file_name);
        return response()->download($path);
    }

    public function getForgeFile($file_name)
    {
        $path = storage_path('app/public/forge/'.$file_name);
        return response()->download($path);
    }

    public function getModpackIconsFile($file_name)
    {
        $path = storage_path('app/public/modpack_icons/'.$file_name);
        return response()->download($path);

    }
}
