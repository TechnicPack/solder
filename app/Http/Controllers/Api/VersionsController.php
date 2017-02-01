<?php

/*
 * This file is part of TechnicSolder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Api;

use App\Resource;
use App\Version;
use Illuminate\Http\Request;
use App\Traits\ImplementsApi;
use League\Fractal\TransformerAbstract;
use App\Transformers\VersionTransformer;

class VersionsController extends ApiController
{
    use ImplementsApi;

    /**
     * The primary transformer for the controller.
     *
     * @return TransformerAbstract
     */
    protected function transformer()
    {
        return VersionTransformer::class;
    }

    /**
     * The primary resource type for data returned by the controller.
     *
     * @return string
     */
    protected function resourceName()
    {
        return 'version';
    }

    /**
     * Display a listing of the version data.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return $this->transformAndRespond(Version::all(), $request->query('include'));
    }

    /**
     * Display the specified version.
     *
     * @param Request $request
     * @param Version $version
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Version $version)
    {
        return $this->transformAndRespond($version, $request->query('include'));
    }

    /**
     * Save a newly created version in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'data.attributes.version' => 'required',
            'relationships.resource.data.id' => 'required|exists:resources,id',
        ]);

        $resource = Resource::findOrFail($request->input('relationships.resource.data.id'));

        $version = $resource->versions()->create($request->input('data.attributes'));

        $location = '/api/'.str_plural($this->resourceName()).'/'.$version->id;

        return $this->transformAndRespond($version)
            ->header('Location', $location)
            ->setStatusCode(201);
    }

    /**
     * Update the specified version in storage.
     *
     * @param Request $request
     * @param Version $version
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Version $version)
    {
        $this->accept($request, [
            'data.id' => $version->id,
            'data.type' => $this->resourceName(),
        ]);

        $this->validate($request, [
            'data.attributes.version' => 'sometimes|required',
        ]);

        $version->update($request->input('data.attributes'));

        return $this->transformAndRespond($version);
    }

    /**
     * Remove the specified version from storage.
     *
     * @param Version $version
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Version $version)
    {
        $version->delete();

        return response(null, 204);
    }
}
