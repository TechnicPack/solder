<?php

namespace App\Http\Controllers;

use App\Build;
use App\Http\Requests\BuildRequest;
use App\Modpack;

/**
 * Class BuildController.
 */
class BuildController extends Controller
{
    public function index()
    {
        $builds = Build::all();

        return view('builds.index', compact('builds'));
    }

    public function create(Modpack $modpack)
    {
        $build = new Build();

        return view('builds.create', compact('modpack', 'build'));
    }

    public function edit(Modpack $modpack, Build $build)
    {
        return view('builds.edit', compact('modpack', 'build'));
    }

    public function store(BuildRequest $request, Modpack $modpack)
    {
        $build = new Build($request->all());
        $modpack->addBuild($build);

        session()->flash('status', 'Build created successfully');

        return redirect(route('modpack.build.show', [$modpack->slug, $build->id]));
    }

    public function show(Modpack $modpack, Build $build)
    {
        return view('builds.show', compact('modpack', 'build'));
    }

    public function update(BuildRequest $request, Modpack $modpack, Build $build)
    {
        $build->fill($request->all());
        $build->save();

        session()->flash('status', 'Build updated successfully');

        return redirect(route('modpack.show', $modpack->slug));
    }

    public function destroy(BuildRequest $request, Build $build)
    {
        $build->delete();
        session()->flash('status', 'Build deleted successfully');

        return redirect(route('builds.index'));
    }
}
