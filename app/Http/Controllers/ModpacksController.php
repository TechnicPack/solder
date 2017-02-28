<?php

namespace App\Http\Controllers;

use App\Modpack;
use Illuminate\Http\Request;

class ModpacksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modpacks = Modpack::orderBy('name', 'desc')->get();

        return view('modpacks.index', compact('modpacks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('modpacks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $modpack = Modpack::create($request->all());

        return redirect()->route('modpacks.show', $modpack->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Modpack  $modpack
     * @return \Illuminate\Http\Response
     */
    public function show(Modpack $modpack)
    {
        return view('modpacks.show', compact('modpack'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Modpack  $modpack
     * @return \Illuminate\Http\Response
     */
    public function edit(Modpack $modpack)
    {
        return view('modpacks.edit', compact('modpack'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Modpack  $modpack
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Modpack $modpack)
    {
        $modpack->update($request->all());

        return redirect()->route('modpacks.show', $modpack->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Modpack  $modpack
     * @return \Illuminate\Http\Response
     */
    public function destroy(Modpack $modpack)
    {
        $modpack->delete();

        return redirect()->route('modpacks.index')->with('status', $modpack->name.' deleted');
    }
}
