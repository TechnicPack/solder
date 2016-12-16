<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use League\Fractal\Serializer\JsonApiSerializer;

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

    protected $serializer;
    protected $resource;
    protected $headers;

    public function __construct()
    {
        $this->serializer = new JsonApiSerializer(config('app.url').'/api');
        $this->resource = fractal()->serializeWith($this->serializer);
        $this->headers = new Collection([
            'content-type' => 'application/vnd.api+json',
        ]);
    }

    protected function collection($collection, $transformer, $resource)
    {
        $this->resource
            ->collection($collection)
            ->transformWith($transformer)
            ->withResourceName($resource);

        return $this;
    }

    protected function item($item, $transformer, $resource)
    {
        $this->resource
            ->item($item)
            ->transformWith($transformer)
            ->withResourceName($resource);

        return $this;
    }

    protected function include($include)
    {
        $this->resource
            ->parseIncludes($include);

        return $this;
    }

    protected function response($status = 200)
    {
        return response(
            $this->resource->toJson(),
            $status,
            $this->headers->toArray()
        );
    }

    protected function emptyResponse($status = 204)
    {
        return response(
            null,
            $status,
            $this->headers->toArray()
        );
    }

    protected function addHeader($name, $value)
    {
        $this->headers[$name] = $value;

        return $this;
    }
}
