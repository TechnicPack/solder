<?php

namespace App\Http\Controllers;

use App\Build;

class BuildsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the specified resource.
     *
     * @param Build $build
     * @return \Illuminate\Http\Response
     */
    public function show(Build $build)
    {
        $build->load('modpack');

        return view('builds.show', compact('build'));
    }
}
