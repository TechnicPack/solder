<?php

namespace App\Http\Controllers\Api;

use App\Resource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Transformers\ResourceTransformer;
use App\Http\Requests\ResourceStoreRequest;
use App\Http\Requests\ResourceUpdateRequest;
use App\Exceptions\IdentifierConflictException;

class ResourcesController extends ApiController
{
    /**
     * Display a listing of the mods.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $resource = Resource::all();

        $include = $request->input('include');

        return $this
            ->collection($resource, new ResourceTransformer(), 'resource')
            ->include($include)
            ->response();
    }

    /**
     * Store a newly created mod in storage.
     *
     * @param ResourceStoreRequest $request
     * @return Response
     * @throws IdentifierConflictException
     */
    public function store(ResourceStoreRequest $request)
    {
        $resource = Resource::create($request->input('data.attributes'));

        if ($request->input('data.id')) {
            $resource->id = $request->input('data.id');
            $resource->save();
        }

        return $this
            ->item($resource, new ResourceTransformer(), 'mod')
            ->addHeader('Location', '/mods/'.$resource->getKey())
            ->response(201);
    }

    /**
     * Display the specified mod.
     *
     * @param Request $request
     * @param Resource $resource
     * @return Response
     */
    public function show(Request $request, Resource $resource)
    {
        $include = $request->input('include');

        return $this
            ->item($resource, new ResourceTransformer(), 'resource')
            ->include($include)
            ->response();
    }

    /**
     * Update the specified mod in storage.
     *
     * @param ResourceUpdateRequest $request
     * @param Resource $resource
     * @return Response
     */
    public function update(ResourceUpdateRequest $request, Resource $resource)
    {
        $resource->update($request->input('data.attributes'));

        return $this
            ->item($resource, new ResourceTransformer(), 'resource')
            ->response();
    }

    /**
     * Remove the specified mod from storage.
     *
     * @param Request $request
     * @param Resource $resource
     * @return Response
     */
    public function destroy(Request $request, Resource $resource)
    {
        if ($request->get('cascade') == true) {
            $resource->releases()->delete();
        }

        $resource->delete();

        return $this
            ->emptyResponse();
    }
}
