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

use App\Resource;
use Illuminate\Http\Request;
use App\Traits\ImplementsApi;
use League\Fractal\TransformerAbstract;
use App\Transformers\VersionTransformer;
use App\Transformers\ResourceTransformer;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResourceVersionsController extends ApiController
{
    use ImplementsApi;

    /**
     * Display a listing of the resource versions data.
     *
     * @param Request $request
     *
     * @param resource $resource
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthenticationException
     */
    public function index(Request $request, Resource $resource)
    {
        return $this->transformAndRespond($resource->versions, $request->query('include'));
    }

    /**
     * Display relationship data.
     *
     * @param resource $resource
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws AuthenticationException
     * @throws NotFoundHttpException
     */
    public function show(Resource $resource)
    {
        if (! $this->hasRelatedResource(ResourceTransformer::class, 'versions')) {
            throw new NotFoundHttpException();
        }

        $response = $this->getRelatedResource($resource, 'resource', ResourceTransformer::class, 'versions');

        return $this->respond($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param resource $resource
     *
     * @return Response
     */
    public function store(Request $request, Resource $resource)
    {
        $this->validate($request, [
            'data.attributes.version' => 'required|unique:versions,version,NULL,id,resource_id,'.$resource->id,
        ]);

        $version = $resource->versions()->create($request->input('data.attributes'));

        $location = '/api/'.str_plural($this->resourceName()).'/'.$version->id;

        return $this->transformAndRespond($version)
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
        return VersionTransformer::class;
    }

    /**
     * The primary resource type for data returned by the controller.
     *
     * @return string
     */
    protected function resourceName()
    {
        return 'version';
    }
}
