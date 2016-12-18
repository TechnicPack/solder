<?php

namespace App\Http\Controllers\Api;

use App\Build;
use Illuminate\Http\Request;
use App\Transformers\VersionTransformer;

class BuildVersionsController extends ApiController
{
    /**
     * Display a listing of the builds for a modpack.
     *
     * @param Request $request
     * @param Build $build
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, Build $build)
    {
        $versions = $build->versions;

        $include = $request->input('include');

        return $this
            ->collection($versions, new VersionTransformer(), 'version')
            ->include($include)
            ->response();
    }
}
