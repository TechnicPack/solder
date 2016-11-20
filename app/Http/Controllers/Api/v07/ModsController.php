<?php

namespace App\Http\Controllers\Api\v07;

use App\Http\Controllers\Api\ApiController;
use App\Mod;
use App\Serializers\FlatSerializer;
use App\Transformers\v07\ModTransformer;

/**
 * Class ModsController.
 */
class ModsController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mods = Mod::all()->pluck('name', 'slug');

        $response = [
            'mods' => $mods,
        ];

        return response($response, 200, ['content-type' => 'application/json']);
    }

    /**
     * Display the specified resource.
     *
     * @param Mod $mod
     * @return \Illuminate\Http\Response
     */
    public function show($mod)
    {
        $mod = Mod::where('slug', $mod)->with('releases')->first();

        if ($mod == null) {
            return $this->simpleErrorResponse('No mod requested/Mod does not exist/Mod version does not exist');
        }

        $response = fractal()
            ->item($mod)
            ->serializeWith(new FlatSerializer())
            ->transformWith(new ModTransformer())
            ->toJson();

        return $this->simpleJsonResponse($response);
    }
}
