<?php

namespace App\Http\Controllers\Api\v07;

use App\Http\Controllers\Controller;
use App\Modpack;
use App\Client;
use App\Serializers\FlatSerializer;
use App\Transformers\v07\ModpackTransformer;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ModpacksController
 * @package App\Http\Controllers\Api\Deprecated
 */
class ModpacksController extends Controller
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
                $collection = Modpack::permitted($client)->with(
                    'builds',
                    'icon',
                    'logo',
                    'background',
                    'promoted',
                    'latest'
                )->get();

                $modpacks = [];

                $collection->each(function ($item, $key) use (&$modpacks) {
                    $modpacks[$item->slug] = fractal()
                        ->item($item)
                        ->serializeWith(new FlatSerializer())
                        ->transformWith(new ModpackTransformer())
                        ->toArray();
                });

                break;
            default:
                $modpacks = Modpack::permitted($client)->pluck('name', 'slug');
        }

        $response = [
            'mirror_url' => config('app.url'),
            'modpacks' => $modpacks
        ];

        return response($response, 200, ['content-type' => 'application/json']);
    }

    /**
     * Display the specified resource.
     *
     * @param Modpack $modpack
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Modpack $modpack)
    {
        $token = $request->get('k') ?? $request->get('cid');
        $client = Client::where('token', $token)->first();

        if (!$client->isPermitted($modpack)) {
            throw new NotFoundHttpException();
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

        return response($response, 200, ['content-type' => 'application/json']);
    }
}
