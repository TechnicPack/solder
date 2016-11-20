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
            ->where('version', $buildVersion)
            ->first();

        if ($build == null || $modpack == null || ! $modpack->allowed($client)) {
            $error = ['error' => 'Modpack does not exist/Build does not exist'];
            return response($error, 404, ['content-type' => 'application/json']);
        }

        $build->load('releases');

        $response = fractal()
            ->item($build)
            ->serializeWith(new FlatSerializer())
            ->transformWith(new BuildTransformer())
            ->toJson();

        return response($response, 200, ['content-type' => 'application/json']);
    }
}
