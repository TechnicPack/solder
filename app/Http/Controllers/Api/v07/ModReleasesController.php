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
    public function show($mod, $releaseVersion)
    {
        $mod = Mod::where('slug', $mod)->first();

        $release = Release::where('mod_id', $mod->id)
            ->where('version', $releaseVersion)
            ->first();

        if ($mod == null || $release == null) {
            $error = ['error' => 'No mod requested/Mod does not exist/Mod version does not exist'];

            return response($error, 404, ['content-type' => 'application/json']);
        }

        $response = fractal()
            ->item($release)
            ->serializeWith(new FlatSerializer())
            ->transformWith(new ReleaseTransformer())
            ->toJson();

        return response($response, 200, ['content-type' => 'application/json']);
    }
}
