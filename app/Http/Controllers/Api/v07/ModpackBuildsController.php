<?php

namespace App\Http\Controllers\Api\v07;

use App\Build;
use App\Client;
use App\Http\Controllers\Api\ApiController;
use App\Modpack;
use App\Serializers\FlatSerializer;
use App\Transformers\v07\BuildTransformer;
use Illuminate\Http\Request;

/**
 * Class ModpackBuildsController.
 */
class ModpackBuildsController extends ApiController
{
    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Modpack $modpack
     * @param $buildVersion
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $modpack, $buildVersion)
    {
        $token = $request->get('k') ?? $request->get('cid');
        $client = Client::where('token', $token)->first();
        $modpack = Modpack::where('slug', $modpack)->first();
        $build = Build::where('modpack_id', $modpack->id)
            ->with('releases')
            ->where('version', $buildVersion)
            ->first();

        if ($build == null || $modpack == null || ! $modpack->allowed($client)) {
            return $this->simpleErrorResponse('Modpack does not exist/Build does not exist');
        }

        $response = fractal()
            ->item($build)
            ->serializeWith(new FlatSerializer())
            ->transformWith(new BuildTransformer())
            ->toJson();

        return $this->simpleJsonresponse($response);
    }
}
