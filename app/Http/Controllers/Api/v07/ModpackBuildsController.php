<?php

namespace App\Http\Controllers\Api\v07;

use App\Build;
use App\Client;
use App\Modpack;
use App\Http\Controllers\Controller;
use App\Serializers\FlatSerializer;
use App\Transformers\v07\BuildTransformer;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ModpackBuildsController.
 */
class ModpackBuildsController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param Modpack $modpack
     * @param $buildVersion
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Modpack $modpack, $buildVersion)
    {
        $token = $request->get('k') ?? $request->get('cid');
        $client = Client::where('token', $token)->first();

        if (! $client->isPermitted($modpack)) {
            throw new NotFoundHttpException();
        }

        $build = Build::where('modpack_id', $modpack->id)
            ->where('version', $buildVersion)
            ->firstOrFail();

        $build->load('releases');

        $response = fractal()
            ->item($build)
            ->serializeWith(new FlatSerializer())
            ->transformWith(new BuildTransformer())
            ->toJson();

        return response($response, 200, ['content-type' => 'application/json']);
    }
}
