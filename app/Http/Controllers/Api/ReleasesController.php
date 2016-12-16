<?php

namespace App\Http\Controllers\Api;

use App\Release;
use Illuminate\Http\Request;
use App\Transformers\ReleaseTransformer;

class ReleasesController extends ApiController
{
    /**
     * Display a listing of releases.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $releases = Release::all();

        $include = $request->input('include');

        return $this
            ->collection($releases, new ReleaseTransformer(), 'release')
            ->include($include)
            ->response();
    }

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
            ->item($release, new ReleaseTransformer(), 'release')
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
            ->item($release, new ReleaseTransformer(), 'release')
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
