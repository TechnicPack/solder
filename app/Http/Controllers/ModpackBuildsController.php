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
     * @param Modpack $modpack
     * @param $buildVersion
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Modpack $modpack, $buildVersion)
    {
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
     * @param Modpack $modpack
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Modpack $modpack)
    {
        $this->authorize('update', $modpack);

        request()->validate([
            'version' => ['required', 'regex:/^[a-zA-Z0-9_-][.a-zA-Z0-9_-]*$/', Rule::unique('builds')->where('modpack_id', $modpack->id)],
            'minecraft_version' => ['required'],
            'status' => ['required', 'in:public,private,draft'],
            'required_memory' => ['nullable', 'numeric'],
        ]);

        $modpack->builds()->create([
            'version' => request('version'),
            'minecraft_version' => request('minecraft_version'),
            'status' => request('status'),
            'java_version' => request('java_version'),
            'required_memory' => request('required_memory'),
            'forge_version' => request('forge_version'),
        ]);

        return redirect()->route('modpacks.show', $modpack);
    }

    /**
     * Update a build.
     *
     * @param Modpack $modpack
     * @param $buildVersion
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @internal param $modpackSlug
     */
    public function update(Modpack $modpack, $buildVersion)
    {
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

        return redirect()->route('builds.show', [$modpack, $build]);
    }

    /**
     * Remove build from application.
     *
     * @param Modpack $modpack
     * @param $buildVersion
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Modpack $modpack, $buildVersion)
    {
        $this->authorize('update', $modpack);

        $build = Build::where('version', $buildVersion)
            ->where('modpack_id', $modpack->id)
            ->firstOrFail();

        $build->delete();

        return redirect()->route('modpacks.show', $modpack);
    }
}
