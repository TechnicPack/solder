<?php

namespace App\Http\Controllers\Api\v07;

use App\Resource;
use App\Serializers\FlatSerializer;
use App\Http\Controllers\Api\ApiController;
use App\Transformers\v07\ResourceTransformer;

class ResourcesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = Resource::all()->pluck('name', 'slug');

        $response = [
            'mods' => $resources,
        ];

        return $this->simpleJsonResponse($response);
    }

    /**
     * Display the specified resource.
     *
     * @param string $resource
     * @return \Illuminate\Http\Response
     */
    public function show($resource)
    {
        $resource = Resource::where('slug', $resource)->with('versions')->first();

        if (empty($resource)) {
            return $this->simpleErrorResponse('No mod requested/Resource does not exist/Resource version does not exist');
        }

        $response = fractal()
            ->item($resource)
            ->serializeWith(new FlatSerializer())
            ->transformWith(new ResourceTransformer())
            ->toJson();

        return $this->simpleJsonResponse($response);
    }
}
