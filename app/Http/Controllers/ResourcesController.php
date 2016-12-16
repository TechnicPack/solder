<?php

namespace App\Http\Controllers;

use App\Resource;

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
        return view('resources.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Resource $resource
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show(Resource $resource)
    {
        return view('resources.show', compact('resource'));
    }
}
