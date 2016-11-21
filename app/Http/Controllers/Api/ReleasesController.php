<?php

namespace App\Http\Controllers\Api;

use App\Release;
use App\Transformers\ReleaseTransformer;
use Illuminate\Http\Request;

class ReleasesController extends ApiController
{
    /**
     * Display the specified release.
     *
     * @param Request $request
     * @param Release $release
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Release $release)
    {
        $include = $request->input('include');

        return $this
            ->item($release, new ReleaseTransformer(), 'releases')
            ->include($include)
            ->response();
    }

    /**
     * Update the specified release in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Release $release
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Release $release)
    {
        $release->update($request->input('data.attributes'));

        return $this
            ->item($release, new ReleaseTransformer(), 'releases')
            ->response();
    }

    /**
     * Remove the specified release from storage.
     *
     * @param Release $release
     * @return \Illuminate\Http\Response
     */
    public function destroy(Release $release)
    {
        $release->delete();

        return $this
            ->emptyResponse();
    }
}
