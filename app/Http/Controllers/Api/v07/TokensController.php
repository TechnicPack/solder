<?php

namespace App\Http\Controllers\Api\v07;

use App\Client;
use App\Http\Controllers\Api\ApiController;

class TokensController extends ApiController
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

        if (empty($client)) {
            return $this->simpleErrorResponse('Invalid key provided.');
        }

        $response = [
            'name' => $client->name,
            'valid' => 'Key validated.',
            'created_at' => $client->created_at,
        ];

        return $this->simpleJsonResponse($response);
    }
}
