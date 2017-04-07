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

use App\User;
use App\Modpack;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LegacyBuildController extends Controller
{
    protected $user;

    /**
     * LegacyBuildController constructor.
     *
     * @internal param $user
     */
    public function __construct()
    {
        $this->user = User::findByToken(request()->query('cid'));
    }

    /**
     * Display the specified build.
     *
     * @param $modpackSlug
     * @param $buildNumber
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($modpackSlug, $buildNumber)
    {
        try {
            $modpack = Modpack::whereSlug($modpackSlug)
                ->whereStatus(['authorized', 'public', 'unlisted'], $this->user)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return $this->notFoundError('Modpack does not exist');
        }

        try {
            $build = $modpack->builds()
                ->whereBuildNumber($buildNumber)
                ->whereStatus(['public', 'authorized'], $this->user)
                ->with('versions.resource')
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return $this->notFoundError('Build does not exist');
        }

        return response()->json($build);
    }
}
