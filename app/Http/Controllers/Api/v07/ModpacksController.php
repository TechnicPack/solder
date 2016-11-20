<?php

namespace App\Http\Controllers\Api\v07;

use App\Http\Controllers\Api\ApiController;
use App\Modpack;
use App\Client;
use App\Serializers\FlatSerializer;
use App\Transformers\v07\ModpackTransformer;
use Illuminate\Http\Request;

/**
 * Class ModpacksController.
 */
class ModpacksController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $token = $request->get('k') ?? $request->get('cid');
        $client = Client::where('token', $token)->first();

        switch ($request->input('include')) {
            case 'full':
                $collection = Modpack::whereAllowed($client)->with(
                    'builds',
                    'icon',
                    'logo',
                    'background',
                    'promoted',
                    'latest'
                )->get();

                $collection->each(function ($item) use (&$modpacks) {
                    $modpacks[$item->slug] = fractal()
                        ->item($item)
                        ->serializeWith(new FlatSerializer())
                        ->transformWith(new ModpackTransformer())
                        ->toArray();
                });

                break;
            default:
                $modpacks = Modpack::whereAllowed($client)->pluck('name', 'slug');
        }

        $response = [
            'mirror_url' => config('app.url'),
            'modpacks' => $modpacks,
        ];

        return $this->simpleJsonResponse($response);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Modpack $modpack
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $modpack)
    {
        $token = $request->get('k') ?? $request->get('cid');
        $client = Client::where('token', $token)->first();

        $modpack = Modpack::where('slug', $modpack)->first();

        if ($modpack == null || ! $modpack->allowed($client)) {
            return $this->simpleErrorResponse('Modpack does not exist/Build does not exist');
        }

        $modpack->load(
            'builds',
            'icon',
            'logo',
            'background',
            'promoted',
            'latest'
        );

        $response = fractal()
            ->item($modpack)
            ->serializeWith(new FlatSerializer())
            ->transformWith(new ModpackTransformer())
            ->toJson();

        return $this->simpleJsonResponse($response);
    }
}
