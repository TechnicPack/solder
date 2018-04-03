<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Settings;

use App\Team;
use App\Http\Controllers\Controller;

class TeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['data' => Team::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'name' => ['required'],
            'slug' => ['required', 'unique:teams'],
        ]);

        $team = Team::create([
            'name' => request('name'),
            'slug' => request('slug'),
        ]);

        return response()->json(['data' => $team], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Team $team
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Team $team)
    {
        $team->delete();

        return response(null, 204);
    }
}
