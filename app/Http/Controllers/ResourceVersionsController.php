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

use App\Resource;
use App\Version;
use Illuminate\Http\Request;

class ResourceVersionsController extends Controller
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
     * @param Resource $resource
     * @return \Illuminate\Http\Response
     */
    public function index(Resource $resource)
    {
        if ($resource->hasVersions()) {
            $version = $resource->versions()->latest()->first();

            return redirect(route('versions.show', [
                'resource' => $resource->id,
                'version' => $version->id,
            ]));
        }

        return redirect(route('versions.create', $resource->id));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Resource $resource
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Resource $resource)
    {
        return view('versions.create', compact('resource'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Resource $resource
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Resource $resource)
    {
        $version = $resource->versions()->create($request->all());

        return redirect(route('versions.show', ['resource' => $resource->id, 'version' => $version->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param Resource $resource
     * @param  Version $version
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Resource $resource, Version $version)
    {
        return view('versions.show', compact('resource', 'version'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Resource $resource
     * @param Version $version
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Resource $resource, Version $version)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Resource $resource
     * @param Version $version
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Resource $resource, Version $version)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Resource $resource
     * @param Version $version
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resource $resource, Version $version)
    {
        $version->delete();

        return redirect()->route('versions.index', $resource->id)->with('status', 'version '.$version->version.' deleted');
    }
}
