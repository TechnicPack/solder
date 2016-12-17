<?php

namespace App\Http\Controllers;

use App\Release;

class ReleasesController extends Controller
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
     * @param Release $release
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Release $release)
    {
        $release->load('mod');

        return view('releases.show', compact('release'));
    }
}
