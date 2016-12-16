<?php

namespace App\Http\Controllers\Api;

use App\Modpack;
use Illuminate\Http\Request;
use App\Transformers\BuildTransformer;
use App\Http\Requests\BuildStoreRequest;

class ModpackBuildsController extends ApiController
{
    /**
     * Display a listing of the builds for a modpack.
     *
     * @param Request $request
     * @param Modpack $modpack
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, Modpack $modpack)
    {
        $builds = $modpack->builds;

        $include = $request->input('include');

        return $this
            ->collection($builds, new BuildTransformer(), 'build')
            ->include($include)
            ->response();
    }

    /**
     * Store a newly created release for a mod in storage.
     *
     * @param BuildStoreRequest $request
     * @param Modpack $modpack
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(BuildStoreRequest $request, Modpack $modpack)
    {
        $build = $modpack->builds()->create($request->input('data.attributes'));

        if ($request->input('data.id')) {
            $build->id = $request->input('data.id');
            $build->save();
        }

        return $this
            ->item($build, new BuildTransformer(), 'build')
            ->addHeader('Location', '/builds/'.$build->getKey())
            ->response(201);
    }
}
