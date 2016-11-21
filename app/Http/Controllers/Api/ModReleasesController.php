<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\IdentifierConflictException;
use App\Http\Requests\ReleaseStoreRequest;
use App\Mod;
use App\Release;
use App\Transformers\ReleaseTransformer;
use Illuminate\Http\Request;

class ModReleasesController extends ApiController
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
        $releases = $mod->releases;

        $include = $request->input('include');

        return $this
            ->collection($releases, new ReleaseTransformer(), 'releases')
            ->include($include)
            ->response();
    }

    /**
     * Store a newly created release for a mod in storage.
     *
     * @param ReleaseStoreRequest $request
     * @param Mod $mod
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws IdentifierConflictException
     */
    public function store(ReleaseStoreRequest $request, Mod $mod)
    {
        $release = $mod->releases()->create($request->input('data.attributes'));

        return $this
            ->item($release, new ReleaseTransformer(), 'releases')
            ->addHeader('Location', '/releases/'.$release->getKey())
            ->response(201);
    }
}
