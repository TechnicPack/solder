<?php

namespace App\Http\Controllers\Api;

use App\Build;
use App\Transformers\BuildTransformer;
use Illuminate\Http\Request;

class BuildsController extends ApiController
{
    /**
     * Display the specified build.
     *
     * @param Request $request
     * @param Build $build
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Build $build)
    {
        $include = $request->input('include');

        return $this
            ->item($build, new BuildTransformer(), 'builds')
            ->include($include)
            ->response();
    }

    /**
     * Update the specified build in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Build $build
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Build $build)
    {
        $build->update($request->input('data.attributes'));

        return $this
            ->item($build, new BuildTransformer(), 'builds')
            ->response();
    }

    /**
     * Remove the specified build from storage.
     *
     * @param Build $build
     * @return \Illuminate\Http\Response
     */
    public function destroy(Build $build)
    {
        $build->delete();

        return $this
            ->emptyResponse();
    }
}
