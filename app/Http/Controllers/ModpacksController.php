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
use App\NullFile;
use Illuminate\Validation\Rule;

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
                $query->orderBy('created_at', 'desc');
            }])
            ->firstOrFail();

        return view('modpacks.show', [
            'modpack' => $modpack,
        ]);
    }

    /**
     * Store a modpack in the database.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        $this->authorize('create', Modpack::class);

        request()->validate([
            'name' => ['required'],
            'slug' => ['required', 'unique:modpacks', 'alpha_dash'],
            'status' => ['required', 'in:public,private,draft'],
            'modpack_icon' => ['nullable', 'image', Rule::dimensions()->minWidth(50)->ratio(1)],
        ]);

        Modpack::create([
            'name' => request('name'),
            'slug' => request('slug'),
            'status' => request('status'),
            'icon_path' => request('modpack_icon', new NullFile)->store('modpack_icons'),
        ]);

        return redirect('/modpacks/'.request('slug'));
    }

    /**
     * Update a modpack with the request data.
     *
     * @param $slug
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($slug)
    {
        $modpack = Modpack::where('slug', $slug)->first();

        $this->authorize('update', $modpack);

        request()->validate([
            'name' => ['sometimes', 'required'],
            'slug' => ['sometimes', 'required', 'alpha_dash', Rule::unique('modpacks')->ignore($modpack->id)],
            'status' => ['sometimes', 'required', 'in:public,private,draft'],
            'modpack_icon' => ['nullable', 'image', Rule::dimensions()->minWidth(50)->ratio(1)],
            'latest_build_id' => ['sometimes', Rule::exists('builds', 'id')->where('modpack_id', $modpack->id)],
            'recommended_build_id' => ['sometimes', Rule::exists('builds', 'id')->where('modpack_id', $modpack->id)],
        ]);

        $modpack->update(request()->only([
            'name',
            'slug',
            'status',
            'recommended_build_id',
            'latest_build_id',
        ]));

        // TODO: Dispatch this as a job to resize image then update
        if (request()->has('modpack_icon') && request('modpack_icon') != null) {
            $modpack->update([
                'icon_path' => request('modpack_icon')->store('modpack_icons'),
            ]);
        }

        return redirect('/modpacks/'.$modpack->slug);
    }

    /**
     * Delete a modpack from the database.
     *
     * @param $slug
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($slug)
    {
        $modpack = Modpack::where('slug', $slug)->firstOrFail();

        $this->authorize('delete', $modpack);

        $modpack->delete();

        return redirect('/');
    }
}
