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
     * @param $version
     * @return \Illuminate\Http\Response
     */
    public function show($resourceSlug, $versionString)
    {
        $resource = Resource::where('slug', $resourceSlug)->first();

        $version = Version::where('resource_id', $resource->id)
            ->where('version', $versionString)
            ->first();

        if (empty($resource) || empty($version)) {
            return $this->simpleErrorResponse('No mod requested/Resource does not exist/Resource version does not exist');
        }

        $response = fractal()
            ->item($version)
            ->serializeWith(new FlatSerializer())
            ->transformWith(new VersionTransformer())
            ->toJson();

        return $this->simpleJsonResponse($response);
    }
}
