<?php

namespace App\Http\Controllers\Api;

use App\Mod;
use Illuminate\Http\Request;
use App\Transformers\VersionTransformer;
use App\Http\Requests\VersionStoreRequest;
use App\Exceptions\IdentifierConflictException;

class ModVersionsController extends ApiController
{
    /**
     * Display a listing of the releases for a mod.
     *
     * @param Mod $mod
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, Mod $mod)
    {
        $versions = $mod->versions;

        $include = $request->input('include');

        return $this
            ->collection($versions, new VersionTransformer(), 'versions')
            ->include($include)
            ->response();
    }

    /**
     * Store a newly created release for a mod in storage.
     *
     * @param VersionStoreRequest $request
     * @param Mod $mod
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws IdentifierConflictException
     */
    public function store(VersionStoreRequest $request, Mod $mod)
    {
        $version = $mod->releases()->create($request->input('data.attributes'));

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
