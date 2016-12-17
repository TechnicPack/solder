<?php

namespace App\Http\Controllers;

use App\Modpack;

class ModpacksController extends Controller
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
        return view('modpacks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Modpack $modpack
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Modpack $modpack)
    {
        return view('modpacks.show', compact('modpack'));
    }
}
