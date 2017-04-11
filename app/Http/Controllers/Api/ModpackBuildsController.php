<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Api;

use App\Modpack;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;

class ModpackBuildsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Modpack $modpack
     *
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function index(Modpack $modpack)
    {
        if (\Auth::guest() && $modpack->status == Modpack::STATUS_PRIVATE) {
            throw new AuthorizationException();
        }

        if (\Auth::guest()) {
            $modpack->load(['builds' => function ($query) {
                $query->whereStatus('public');
            }]);
        }

        return response()->json([
            'data' => $modpack->builds->map(function ($build) {
                return [
                    'type' => 'build',
                    'id' => $build->id,
                    'attributes' => [
                        'build_number' => $build->build_number,
                        'minecraft_version' => $build->minecraft_version,
                        'state' => $build->status_as_string,
                        'arguments' => $build->arguments,
                    ],
                    'links' => [
                        'self' => $build->link_self,
                    ],
                ];
            }),
        ]);
    }

    /**
     * Show the form for creating a new resource.
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Modpack $modpack)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Modpack  $modpack
     * @return \Illuminate\Http\Response
     */
    public function edit(Modpack $modpack)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Modpack  $modpack
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Modpack $modpack)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Modpack  $modpack
     * @return \Illuminate\Http\Response
     */
    public function destroy(Modpack $modpack)
    {
        //
    }
}
