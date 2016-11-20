<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    /**
     * @param string $message
     * @param int $status
     * @return \Illuminate\Http\Response
     */
    public function simpleErrorResponse(String $message, int $status = 404)
    {
        $error = ['error' => $message];

        return $this->simpleJsonResponse($error, $status);
    }

    /**
     * @param mixed $content
     * @param int $status
     * @return \Illuminate\Http\Response
     */
    public function simpleJsonResponse($content, int $status = 200)
    {
        if (is_array($content)) {
            $content = json_encode($content);
        }

        return response($content, $status, ['content-type' => 'application/json']);
    }
}
