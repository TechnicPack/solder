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
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;

class ModpackBuildsController extends Controller
{
    /**
     * ResourceController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->only('store');
    }

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
                return $this->transformBuild($build);
            }),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Modpack $modpack
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Modpack $modpack)
    {
        $this->validate($request, [
            'data.attributes.build_number' => ['required', Rule::unique('builds', 'build_number')->where('modpack_id', $modpack->id)],
            'data.attributes.minecraft_version' => ['required'],
        ]);

        $build = $modpack->builds()->create([
            'build_number' => $request->input('data.attributes.build_number'),
            'minecraft_version' => $request->input('data.attributes.minecraft_version'),
            'status_as_string' => $request->input('data.attributes.state'),
            'arguments' => $request->input('data.attributes.arguments'),
        ]);

        return response()
            ->json(['data' => $this->transformBuild($build)], 201)
            ->withHeaders(['Location' => $build->link_self]);
    }

    private function transformBuild($build)
    {
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
    }
}
