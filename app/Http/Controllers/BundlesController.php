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

use App\Bundle;

class BundlesController extends Controller
{
    /**
     * Store a posted bundle.
     */
    public function store()
    {
        $bundle = Bundle::create([
            'build_id' => request()->build_id,
            'release_id' => request()->release_id,
        ]);

        return redirect('/modpacks/'.$bundle->build->modpack->slug.'/'.$bundle->build->version);
    }

    /**
     * Delete a bundle.
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy()
    {
        Bundle::where('build_id', request()->build_id)
            ->where('release_id', request()->release_id)
            ->firstOrFail()->delete();

        return response(null, 204);
    }
}
