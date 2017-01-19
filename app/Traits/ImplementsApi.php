<?php

/*
 * This file is part of TechnicSolder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;

trait ImplementsApi
{
    /**
     * Check if Transformer has related resource.
     *
     * @param TransformerAbstract $transformer
     * @param $includeName
     *
     * @return bool
     */
    private function hasRelatedResource($transformer, $includeName)
    {
        if (is_string($transformer) && class_exists($transformer)) {
            $transformer = new $transformer;
        }

        $availableIncludes = $transformer->getAvailableIncludes();

        return array_has(array_flip($availableIncludes), $includeName);
    }

    /**
     * Return the related resource data set.
     *
     * @param Model $model
     * @param $resource
     * @param $transformer
     * @param $relatedResource
     *
     * @return array
     */
    private function getRelatedResource(Model $model, $resource, $transformer, $relatedResource)
    {
        $data = fractal($model)
            ->transformWith($transformer)
            ->withResourceName($resource)
            ->parseIncludes($relatedResource)
            ->toArray();

        return $data['data']['relationships'][$relatedResource];
    }

    /**
     * The primary transformer for the controller.
     *
     * @return TransformerAbstract
     */
    abstract protected function transformer();

    /**
     * The primary resource type for data returned by the controller.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    abstract protected function resourceName();

    private function transformAndRespond($data, $includes = null, $status = 200)
    {
        $response = $this->transform($data, $includes);

        return $this->respond($response, $status);
    }

    /**
     * @param $data
     * @param $includes
     *
     * @return array
     */
    private function transform($data, $includes)
    {
        $response = fractal($data)
            ->transformWith($this->transformer())
            ->withResourceName($this->resourceName());

        if ($includes !== null) {
            $response->parseIncludes($includes);
        }

        return $response->toArray();
    }

    /**
     * @param $response
     * @param $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function respond($response, $status = 200)
    {
        return response()->json($response, $status, ['Content-Type', 'application/vnd.api+json']);
    }
}
