<?php

namespace App\Http\Controllers\Api;

use App\Release;
use App\Transformers\BuildTransformer;
use Illuminate\Http\Request;

class ReleaseBuildsController extends ApiController
{
    /**
     * Display a listing of the builds for a modpack.
     *
     * @param Request $request
     * @param Release $release
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, Release $release)
    {
        $builds = $release->builds;

        $include = $request->input('include');

        return $this
            ->collection($builds, new BuildTransformer(), 'build')
            ->include($include)
            ->response();
    }
}
