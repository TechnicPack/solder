<?php

namespace App\Http\Controllers\Api\v07;

use App\Version;
use App\Resource;
use App\Serializers\FlatSerializer;
use App\Http\Controllers\Api\ApiController;
use App\Transformers\v07\VersionTransformer;

class ResourceVersionsController extends ApiController
{
    /**
     * Display the specified resource.
     *
     * @param Resource $resource
     * @param $releaseVersion
     * @return \Illuminate\Http\Response
     */
    public function show($resource, $releaseVersion)
    {
        $resource = Resource::where('slug', $resource)->first();

        $release = Version::where('resource_id', $resource->id)
            ->where('version', $releaseVersion)
            ->first();

        if (empty($resource) || empty($release)) {
            return $this->simpleErrorResponse('No mod requested/Resource does not exist/Resource version does not exist');
        }

        $response = fractal()
            ->item($release)
            ->serializeWith(new FlatSerializer())
            ->transformWith(new VersionTransformer())
            ->toJson();

        return $this->simpleJsonResponse($response);
    }
}
