<?php

namespace App\Http\Controllers\Api\v07;

use App\Mod;
use App\Http\Controllers\Controller;
use App\Serializers\FlatSerializer;
use App\Transformers\v07\ModTransformer;

/**
 * Class ModsController.
 */
class ModsController extends Controller
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
    public function show(Mod $mod)
    {
        $mod->load('releases');

        $response = fractal()
            ->item($mod)
            ->serializeWith(new FlatSerializer())
            ->transformWith(new ModTransformer())
            ->toJson();

        return response($response, 200, ['content-type' => 'application/json']);
    }
}
