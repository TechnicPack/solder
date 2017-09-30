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
    /**
     * Show a modpack.
     *
     * @param $slug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

    /**
     * Show the create modpack form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('modpacks.create');
    }

    /**
     * Store a modpack in the database.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        $modpack = Modpack::create([
            'name' => request()->name,
            'slug' => request()->slug,
            'status' => request()->status,
        ]);

        return redirect('/modpacks/'.$modpack->slug);
    }
}
