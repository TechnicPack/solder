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
use Illuminate\Validation\Rule;

class BundlesController extends Controller
{
    /**
     * Store a posted bundle.
     */
    public function store()
    {
        $this->authorize('create', Bundle::class);

        $this->validate(request(), [
            'release_id' => Rule::unique('build_release', 'release_id')->where('build_id', request('build_id')),
        ]);

        $bundle = Bundle::create([
            'build_id' => request()->build_id,
            'release_id' => request()->release_id,
        ]);

        return response()->json([
            'status' => 'success',
            'redirect' => '/modpacks/'.$bundle->build->modpack->slug.'/'.$bundle->build->version,
        ]);
    }

    /**
     * Delete a bundle.
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy()
    {
        $bundle = Bundle::where('build_id', request()->build_id)
            ->where('release_id', request()->release_id)
            ->firstOrFail();

        $this->authorize('delete', $bundle);

        $bundle->delete();

        return response(null, 204);
    }
}
