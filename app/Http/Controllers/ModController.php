<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModRequest;
use App\Mod;
use Session;

class ModController extends Controller
{
    public function index()
    {
        $mods = Mod::all();

        return view('mods.index', compact('mods'));
    }

    public function create()
    {
        return view('mods.create');
    }

    public function edit(Mod $mod)
    {
        return view('mods.edit', compact('mod'));
    }

    public function store(ModRequest $request)
    {
        $mod = Mod::create($request->all());
        Session::flash('status', 'Mod created successfully');

        return redirect()->route('mod.show', $mod->slug);
    }

    public function show(Mod $mod)
    {
        return view('mods.show', compact('mod'));
    }

    public function update(ModRequest $request, Mod $mod)
    {
        $mod->update($request->all());
        Session::flash('status', 'Mod updated successfully');

        return redirect()->route('mod.show', $mod->slug);
    }

    public function destroy(Mod $mod)
    {
        $mod->delete();
        Session::flash('status', 'Mod deleted successfully');

        return redirect()->route('mod.index');
    }
}
