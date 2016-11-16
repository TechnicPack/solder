<?php

namespace App\Http\Controllers\Api\v07;

use App\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class KeysController.
 */
class TokensController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param $token
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     */
    public function verify($token)
    {
        $client = Client::global()->where('token', $token)->first();

        if (is_null($client)) {
            return response([
                'error' => 'Invalid key provided.',
            ], 404, ['content-type' => 'application/json']);
        }

        return response([
            'name' => $client->name,
            'valid' => 'Key validated.',
            'created_at' => $client->created_at,
        ], 200, ['content-type' => 'application/json']);
    }
}
