<?php

namespace App\Http\Controllers\Api\v07;

use App\Mod;
use App\Release;
use App\Serializers\FlatSerializer;
use App\Http\Controllers\Api\ApiController;
use App\Transformers\v07\ReleaseTransformer;

/**
 * Class ModReleasesController.
 */
class ModReleasesController extends ApiController
{
    /**
     * Display the specified resource.
     *
     * @param Mod $mod
     * @param $releaseVersion
     *
     * @return \Illuminate\Http\Response
     */
    public function show($mod, $releaseVersion)
    {
        $mod = Mod::where('slug', $mod)->first();

        $release = Release::where('mod_id', $mod->id)
            ->where('version', $releaseVersion)
            ->first();

        if (empty($mod) || empty($release)) {
            return $this->simpleErrorResponse('No mod requested/Mod does not exist/Mod version does not exist');
        }

        $response = fractal()
            ->item($release)
            ->serializeWith(new FlatSerializer())
            ->transformWith(new ReleaseTransformer())
            ->toJson();

        return $this->simpleJsonResponse($response);
    }
}
