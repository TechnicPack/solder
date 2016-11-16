<?php

namespace App\Http\Controllers\Api\v07;

use App\Mod;
use App\Release;
use App\Http\Controllers\Controller;
use App\Serializers\FlatSerializer;
use App\Transformers\v07\ReleaseTransformer;

/**
 * Class ModReleasesController.
 */
class ModReleasesController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param Mod $mod
     * @param $releaseVersion
     * @return \Illuminate\Http\Response
     */
    public function show(Mod $mod, $releaseVersion)
    {
        $release = Release::where('mod_id', $mod->id)
            ->where('version', $releaseVersion)
            ->firstOrFail();

        $response = fractal()
            ->item($release)
            ->serializeWith(new FlatSerializer())
            ->transformWith(new ReleaseTransformer())
            ->toJson();

        return response($response, 200, ['content-type' => 'application/json']);
    }
}
