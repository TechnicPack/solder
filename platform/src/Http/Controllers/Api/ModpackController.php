<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Platform\Http\Controllers\Api;

use App\Modpack;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\Resource;
use Platform\Http\Resources\ModpackFullResource;

class ModpackController extends Controller
{
    /**
     * ModpackController constructor.
     */
    public function __construct()
    {
        Resource::withoutWrapping();
    }

    /**
     * Return a JSON response listing all modpacks the requester has access to.
     * If a valid API key is provided in the query string as k={key} then all
     * public and private modpacks and builds. If a valid Client token is
     * provided in the query string as cid={token} then all public modpacks and
     * builds, and any private modpacks and builds that the client has been
     * authorized for will be returned.
     *
     * Additional details can be returned by placing include=full in the query
     * string.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        Resource::wrap('modpacks');

        // TODO: This is awfully complicated...
        $modpacks = Modpack::whereToken(request('k'), request('cid'))
            ->with(['builds' => function ($query) {
                $query->whereToken(request('k'), request('cid'));
            }])->get()->keyBy('slug');

        $resource = $this->resourceClassForRequest(request());

        return $resource::collection($modpacks)->additional([
            'mirror_url' => preg_replace('/([^:])(\/{2,})/', '$1/', Storage::url('/')),
        ]);
    }

    /**
     * Return a JSON response containing details of a specific Modpack and
     * list all builds. As with the index method, an API key (k={key}) or
     * Client token (cid={token}) can be appended to the query string to
     * provide access to private modpacks and builds as authorized and
     * required.
     *
     * @param $slug
     *
     * @return ModpackFullResource
     */
    public function show($slug)
    {
        $modpack = Modpack::where('slug', $slug)
            ->whereToken(request('k'), request('cid'))
            ->with(['builds' => function ($query) {
                $query->whereToken(request('k'), request('cid'));
            }])
            ->firstOrFail();

        return new ModpackFullResource($modpack);
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function resourceClassForRequest(Request $request)
    {
        $class = 'Modpack'.studly_case($request->get('include')).'Resource';

        return 'Platform\\Http\\Resources\\'.$class;
    }
}
