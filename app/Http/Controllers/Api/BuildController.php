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
use App\Modpack;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;

class BuildController extends Controller
{
    /**
     * ResourceController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->only('store');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $modpackId = $request->input('relationships.modpack.data.id');

        $this->validate($request, [
            'relationships.modpack.data.id' => ['bail', 'required'],
            'data.attributes.build_number' => ['required', 'unique:builds,build_number,NULL,NULL,modpack_id,'.$modpackId], // could this be done in a closure?
            'data.attributes.minecraft_version' => ['required'],
        ]);

        $modpack = Modpack::findOrFail($modpackId);

        $build = $modpack->builds()->create([
            'build_number' => $request->input('data.attributes.build_number'),
            'minecraft_version' => $request->input('data.attributes.minecraft_version'),
            'status_as_string' => $request->input('data.attributes.state'),
            'arguments' => $request->input('data.attributes.arguments'),
        ]);

        return response()
            ->json([
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
            ], 201)
            ->withHeaders(['Location' => $build->link_self]);
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
