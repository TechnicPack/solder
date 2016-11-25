<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\IdentifierConflictException;
use App\Http\Requests\ReleaseStoreRequest;
use App\Mod;
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
            ->collection($releases, new ReleaseTransformer(), 'release')
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

        if ($request->input('data.id')) {
            $release->id = $request->input('data.id');
            $release->save();
        }

        return $this
            ->item($release, new ReleaseTransformer(), 'release')
            ->addHeader('Location', '/releases/'.$release->getKey())
            ->response(201);
    }
}
