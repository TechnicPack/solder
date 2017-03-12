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
use App\Resource;
use Illuminate\Http\Request;

class ModpackBuildsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Modpack $modpack
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Modpack $modpack)
    {
        $builds = $modpack->builds()->orderBy('version', 'desc')->get();

        return view('builds.index', compact('modpack', 'builds'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Modpack $modpack
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Modpack $modpack)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Modpack $modpack
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Modpack $modpack)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Modpack $modpack
     * @param  \App\Build $build
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Modpack $modpack, Build $build)
    {
        $resources = Resource::with('versions')->get();

        return view('builds.show', compact('modpack', 'build', 'resources'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Modpack $modpack
     * @param  \App\Build $build
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Modpack $modpack, Build $build)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Modpack $modpack
     * @param  \App\Build $build
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Modpack $modpack, Build $build)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Modpack $modpack
     * @param  \App\Build $build
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Modpack $modpack, Build $build)
    {
        $build->delete();

        return redirect()->route('builds.index', $modpack->id)->with('status', 'build '.$build->version.' deleted');
    }
}
