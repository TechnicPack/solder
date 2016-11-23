<?php

namespace App\Http\Controllers;

use App\Mod;

class ModsController extends Controller
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
        return view('mods.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Mod $mod
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show(Mod $mod)
    {
        return view('mods.show', compact('mod'));
    }
}
