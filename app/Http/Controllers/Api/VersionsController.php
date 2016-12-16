<?php

namespace App\Http\Controllers\Api;

use App\Version;
use Illuminate\Http\Request;
use App\Transformers\VersionTransformer;

class VersionsController extends ApiController
{
    /**
     * Display a listing of versions.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $versions = Version::all();

        $include = $request->input('include');

        return $this
            ->collection($versions, new VersionTransformer(), 'version')
            ->include($include)
            ->response();
    }

    /**
     * Display the specified version.
     *
     * @param Request $request
     * @param Version $version
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Version $version)
    {
        $include = $request->input('include');

        return $this
            ->item($version, new VersionTransformer(), 'versions')
            ->include($include)
            ->response();
    }

    /**
     * Update the specified version in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Version $version
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Version $version)
    {
        $version->update($request->input('data.attributes'));

        return $this
            ->item($version, new VersionTransformer(), 'version')
            ->response();
    }

    /**
     * Remove the specified version from storage.
     *
     * @param Version $version
     * @return \Illuminate\Http\Response
     */
    public function destroy(Version $version)
    {
        $version->delete();

        return $this
            ->emptyResponse();
    }
}
