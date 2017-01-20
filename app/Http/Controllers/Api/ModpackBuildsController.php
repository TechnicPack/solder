<?php

/*
 * This file is part of TechnicSolder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Api;

use Auth;
use App\Modpack;
use App\Privacy;
use Illuminate\Http\Request;
use App\Traits\ImplementsApi;
use App\Transformers\BuildTransformer;
use League\Fractal\TransformerAbstract;
use App\Transformers\ModpackTransformer;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ModpackBuildsController extends ApiController
{
    use ImplementsApi;

    /**
     * Display a listing of the modpack builds data.
     *
     * @param Request $request
     *
     * @param Modpack $modpack
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthenticationException
     */
    public function index(Request $request, Modpack $modpack)
    {
        if (! Auth::check() && $modpack->privacy('private')) {
            throw new AuthenticationException();
        }

        $data = $modpack->builds()->withoutPrivacy(Auth::check())->get();

        return $this->transformAndRespond($data, $request->query('include'));
    }

    /**
     * Display relationship data.
     *
     * @param Modpack $modpack
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws AuthenticationException
     * @throws NotFoundHttpException
     */
    public function show(Modpack $modpack)
    {
        if (! Auth::check() && $modpack->privacy('private')) {
            throw new AuthenticationException();
        }

        if (! $this->hasRelatedResource(ModpackTransformer::class, 'builds')) {
            throw new NotFoundHttpException();
        }

        $response = $this->getRelatedResource($modpack, 'modpack', ModpackTransformer::class, 'builds');

        return $this->respond($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Modpack $modpack
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Modpack $modpack)
    {
        $this->validate($request, [
            'data.attributes.version' => 'required|unique:builds,version,NULL,id,modpack_id,'.$modpack->id,
            'data.attributes.privacy' => 'in:'.implode(',', Privacy::values()),
            'data.attributes.arguments' => 'array',
        ]);

        $build = $modpack->builds()->create($request->input('data.attributes'));

        $location = '/api/'.str_plural($this->resourceName()).'/'.$build->id;

        return $this->transformAndRespond($build)
            ->header('Location', $location)
            ->setStatusCode(201);
    }

    /**
     * The primary transformer for the controller.
     *
     * @return TransformerAbstract
     */
    protected function transformer()
    {
        return BuildTransformer::class;
    }

    /**
     * The primary resource type for data returned by the controller.
     *
     * @return string
     */
    protected function resourceName()
    {
        return 'build';
    }
}
