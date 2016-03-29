<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModpackRequest;
use App\Modpack;

class ModpackController extends Controller
{
    public function index()
    {
        $modpacks = Modpack::all();

        return view('modpacks.index', compact('modpacks'));
    }

    public function create()
    {
        return view('modpacks.create');
    }

    public function edit(Modpack $modpack)
    {
        return view('modpacks.edit', compact('modpack'));
    }

    public function store(ModpackRequest $request)
    {
        $modpack = Modpack::create($request->all());
        $request->session()->flash('status', 'Modpack created successfully');

        return redirect()->route('modpack.show', $modpack->slug);
    }

    public function show(Modpack $modpack)
    {
        return view('modpacks.show', compact('modpack'));
    }

    public function update(ModpackRequest $request, Modpack $modpack)
    {
        $modpack->update($request->all());
        $request->session()->flash('status', 'Modpack updated successfully');

        return redirect()->route('modpack.show', $modpack->slug);
    }

    public function destroy(Modpack $modpack)
    {
        $modpack->delete();
        $request->session()->flash('status', 'Modpack deleted successfully');

        return redirect()->route('modpacks.index');
    }
}
