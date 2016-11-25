<?php

namespace App\Http\Controllers\Api;

use App\Build;
use App\Transformers\ReleaseTransformer;
use Illuminate\Http\Request;

class BuildReleasesController extends ApiController
{
    /**
     * Display a listing of the builds for a modpack.
     *
     * @param Request $request
     * @param Build $build
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, Build $build)
    {
        $releases = $build->releases;

        $include = $request->input('include');

        return $this
            ->collection($releases, new ReleaseTransformer(), 'release')
            ->include($include)
            ->response();
    }
}