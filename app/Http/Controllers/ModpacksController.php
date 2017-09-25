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

use App\Modpack;

class ModpacksController extends Controller
{
    public function show($slug)
    {
        $modpack = Modpack::where('slug', $slug)
            ->with(['builds' => function ($query) {
                $query->orderBy('version', 'desc');
            }])
            ->firstOrFail();

        return view('modpacks.show', [
            'modpack' => $modpack,
        ]);
    }
}
