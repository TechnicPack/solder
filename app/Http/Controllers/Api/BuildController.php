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

use App\Build;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BuildController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Build $build
     *
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function show(Build $build)
    {
        if (\Auth::guest() && $build->status == Build::STATE_PRIVATE) {
            throw new AuthorizationException();
        }

        if (\Auth::guest() && $build->status == Build::STATE_DRAFT) {
            throw new AuthorizationException();
        }

        return response()->json([
            'data' => [
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
            ],
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Build  $build
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Build $build)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Build  $build
     * @return \Illuminate\Http\Response
     */
    public function destroy(Build $build)
    {
        //
    }
}
