<?php

namespace App\Http\Controllers\Api;

use App\Resource;
use Illuminate\Http\Request;
use App\Transformers\VersionTransformer;
use App\Http\Requests\VersionStoreRequest;
use App\Exceptions\IdentifierConflictException;

class ResourceVersionsController extends ApiController
{
    /**
     * Display a listing of the versions for a resource.
     *
     * @param Request $request
     * @param Resource $resource
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, Resource $resource)
    {
        $versions = $resource->versions;

        $include = $request->input('include');

        return $this
            ->collection($versions, new VersionTransformer(), 'versions')
            ->include($include)
            ->response();
    }

    /**
     * Store a newly created version for a resource in storage.
     *
     * @param VersionStoreRequest $request
     * @param Resource $resource
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws IdentifierConflictException
     */
    public function store(VersionStoreRequest $request, Resource $resource)
    {
        $version = $resource->versions()->create($request->input('data.attributes'));

        if ($request->input('data.id')) {
            $version->id = $request->input('data.id');
            $version->save();
        }

        return $this
            ->item($version, new VersionTransformer(), 'version')
            ->addHeader('Location', '/versions/'.$version->getKey())
            ->response(201);
    }
}
