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
use Illuminate\Http\Request;

class ResourcesController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = Resource::orderBy('name', 'desc')->get();

        return view('resources.index', compact('resources'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('resources.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $resource = Resource::create($request->all());

        return redirect(route('versions.create', $resource->id));
    }

    /**
     * Display the specified resource.
     *
     * @param Resource $resource
     * @return \Illuminate\Http\Response
     */
    public function show(Resource $resource)
    {
        return view('resources.show', compact('resource'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function edit(Resource $resource)
    {
        return view('resources.edit', compact('resource'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Resource  $resource
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Resource $resource)
    {
        $resource->update($request->all());

        return redirect()->route('resources.show', $resource->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Resource  $resource
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resource $resource)
    {
        $resource->delete();

        return redirect()->route('resources.index')->with('status', $resource->name.' deleted');
    }
}
