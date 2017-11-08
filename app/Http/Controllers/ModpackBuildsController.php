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

use App\Build;
use App\Modpack;
use App\Package;
use Illuminate\Validation\Rule;

class ModpackBuildsController extends Controller
{
    /**
     * Show the modpack build.
     *
     * @param $modpackSlug
     * @param $buildVersion
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($modpackSlug, $buildVersion)
    {
        $modpack = Modpack::where('slug', $modpackSlug)
            ->with(['builds' => function ($query) {
                $query->orderBy('version', 'desc');
            }])
            ->firstOrFail();

        $build = Build::with(['releases.package', 'releases' => function ($query) {
            $query->join('packages', 'releases.package_id', '=', 'packages.id')
                ->orderBy('packages.name');
        }])
            ->where('modpack_id', $modpack->id)
            ->where('version', $buildVersion)
            ->firstOrFail();

        return view('builds.show', [
            'modpack' => $modpack,
            'build' => $build,
            'packages' => Package::orderBy('name')->get(),
        ]);
    }

    /**
     * Store the passed data as a build.
     *
     * @param $modpackSlug
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store($modpackSlug)
    {
        $modpack = Modpack::where('slug', $modpackSlug)->first();

        $this->authorize('update', $modpack);

        request()->validate([
            'version' => ['required', 'regex:/^[a-zA-Z0-9_-][.a-zA-Z0-9_-]*$/', Rule::unique('builds')->where('modpack_id', $modpack->id)],
            'minecraft_version' => ['required'],
            'status' => ['required', 'in:public,private,draft'],
            'required_memory' => ['nullable', 'numeric'],
        ]);

        $build = $modpack->builds()->create([
            'version' => request('version'),
            'minecraft_version' => request('minecraft_version'),
            'status' => request('status'),
            'java_version' => request('java_version'),
            'required_memory' => request('required_memory'),
            'forge_version' => request('forge_version'),
        ]);

        return redirect("/modpacks/$modpackSlug");
    }

    /**
     * Update a build.
     *
     * @param $modpackSlug
     * @param $buildVersion
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($modpackSlug, $buildVersion)
    {
        $modpack = Modpack::where('slug', $modpackSlug)->firstOrFail();
        $build = $modpack->builds()->where('version', $buildVersion)->firstOrFail();

        $this->authorize('update', $modpack);

        request()->validate([
            'version' => ['sometimes', 'required', Rule::unique('builds')->ignore($build->id)->where('modpack_id', $modpack->id)],
            'status' => ['sometimes', 'required', 'in:public,private,draft'],
            'minecraft_version' => ['sometimes', 'required'],
            'required_memory' => ['nullable', 'numeric'],
        ]);

        $build->update(request()->only([
            'version',
            'status',
            'minecraft_version',
            'java_version',
            'required_memory',
            'forge_version',
        ]));

        return redirect("/modpacks/$modpackSlug/{$build->version}");
    }

    /**
     * Remove build from application.
     *
     * @param $modpackSlug
     * @param $buildVersion
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($modpackSlug, $buildVersion)
    {
        $modpack = Modpack::where('slug', $modpackSlug)->firstOrFail();
        $this->authorize('update', $modpack);

        $build = Build::where('version', $buildVersion)
            ->where('modpack_id', $modpack->id)
            ->firstOrFail();

        $build->delete();

        return redirect("/modpacks/$modpackSlug");
    }
}
