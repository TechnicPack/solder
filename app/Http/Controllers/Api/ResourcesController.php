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
use App\Transformers\ResourceTransformer;

class ResourcesController extends ApiController
{
    use ImplementsApi;

    /**
     * The primary transformer for the controller.
     *
     * @return TransformerAbstract
     */
    protected function transformer()
    {
        return ResourceTransformer::class;
    }

    /**
     * The primary resource type for data returned by the controller.
     *
     * @return string
     */
    protected function resourceName()
    {
        return 'resource';
    }

    /**
     * Display a listing of the resource data.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return $this->transformAndRespond(Resource::all(), $request->query('include'));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param resource $resource
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Resource $resource)
    {
        return $this->transformAndRespond($resource, $request->query('include'));
    }

    /**
     * Save a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'data.attributes.name' => 'required',
            'data.attributes.slug' => 'sometimes|required|alpha_dash|unique:resources,slug',
        ]);

        $resource = Resource::create($request->input('data.attributes'));

        $location = '/api/'.str_plural($this->resourceName()).'/'.$resource->id;

        return $this->transformAndRespond($resource)
            ->header('Location', $location)
            ->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param resource $resource
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Resource $resource)
    {
        $this->accept($request, [
            'data.id' => $resource->id,
            'data.type' => $this->resourceName(),
        ]);

        $this->validate($request, [
            'data.attributes.name' => 'sometimes|required',
            'data.attributes.slug' => 'sometimes|required|alpha_dash|unique:resources,slug,'.$resource->id,
        ]);

        $resource->update($request->input('data.attributes'));

        return $this->transformAndRespond($resource);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param resource $resource
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resource $resource)
    {
        $resource->delete();

        return response(null, 204);
    }
}
