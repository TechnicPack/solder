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

class BuildVersionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Build $build
     *
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function index(Build $build)
    {
        if (\Auth::guest() && $build->status == Build::STATE_PRIVATE) {
            throw new AuthorizationException();
        }

        if (\Auth::guest() && $build->status == Build::STATE_DRAFT) {
            throw new AuthorizationException();
        }

        return response()->json([
            'data' => $build->versions->map(function ($version) {
                return [
                    'type' => 'version',
                    'id' => $version->id,
                    'attributes' => [
                        'version_number' => $version->version_number,
                        'zip_url' => $version->zip_url,
                        'zip_md5' => $version->zip_md5,
                    ],
                    'links' => [
                        'self' => $version->link_self,
                    ],
                ];
            }),
        ]);
    }

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
}
