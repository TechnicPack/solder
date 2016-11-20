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
    public function show($mod)
    {
        $mod = Mod::where('slug', $mod)->with('releases')->first();

        if ($mod == null) {
            $error = ['error' => 'No mod requested/Mod does not exist/Mod version does not exist'];

            return response($error, 404, ['content-type' => 'application/json']);
        }

        $response = fractal()
            ->item($mod)
            ->serializeWith(new FlatSerializer())
            ->transformWith(new ModTransformer())
            ->toJson();

        return response($response, 200, ['content-type' => 'application/json']);
    }
}
