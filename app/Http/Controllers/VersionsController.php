<?php

namespace App\Http\Controllers;

use App\Version;

class VersionsController extends Controller
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
     * @param Version $version
     * @return \Illuminate\Http\Response
     */
    public function show(Version $version)
    {
        $version->load('mod');

        return view('versions.show', compact('version'));
    }
}
