<?php

/*
 * This file is part of TechnicSolder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Serializers;

use League\Fractal\Serializer\JsonApiSerializer;

class JsonApiResource extends JsonApiSerializer
{
    /**
     * JsonApiResource constructor.
     */
    public function __construct()
    {
        parent::__construct(trim(config('app.url'), '/').'/api');
    }

    /**
     * Serialize an item.
     *
     * @param string $resourceKey
     * @param array $data
     *
     * @return array
     */
    public function item($resourceKey, array $data)
    {
        $resource = parent::item($resourceKey, $data);

        if ($this->shouldIncludeLinks()) {
            $resource['data']['links'] = [
                'self' => $this->baseUrl.'/'.str_plural($resourceKey).'/'.$this->getIdFromData($data),
            ];
        }

        return $resource;
    }

    /**
     * @param array $data
     * @param array $relationships
     *
     * @return array
     */
    protected function fillRelationships($data, $relationships)
    {
        if ($this->isCollection($data)) {
            foreach ($relationships as $key => $relationship) {
                $data = $this->fillRelationshipAsCollection($data, $relationship, $key);
            }
        } else { // Single resource
            foreach ($relationships as $key => $relationship) {
                $data = $this->fillRelationshipAsSingleResource($data, $relationship, $key);
            }
        }

        return $data;
    }

    /**
     * Loops over the relationships of the provided data and formats it.
     *
     * @param $data
     * @param $relationship
     * @param $key
     *
     * @return array
     */
    private function fillRelationshipAsCollection($data, $relationship, $key)
    {
        foreach ($relationship as $index => $relationshipData) {
            $data['data'][$index]['relationships'][$key] = $relationshipData;

            if ($this->shouldIncludeLinks()) {
                $data['data'][$index]['relationships'][$key] = array_merge([
                    'links' => [
                        'self' => $this->baseUrl.'/'.str_plural($data['data'][$index]['type']).'/'.$data['data'][$index]['id'].'/relationships/'.$key,
                        'related' => $this->baseUrl.'/'.str_plural($data['data'][$index]['type']).'/'.$data['data'][$index]['id'].'/'.$key,
                    ],
                ], $data['data'][$index]['relationships'][$key]);
            }
        }

        return $data;
    }

    /**
     * @param $data
     * @param $relationship
     * @param $key
     *
     * @return array
     */
    private function fillRelationshipAsSingleResource($data, $relationship, $key)
    {
        $data['data']['relationships'][$key] = $relationship[0];

        if ($this->shouldIncludeLinks()) {
            $data['data']['relationships'][$key] = array_merge([
                'links' => [
                    'self' => $this->baseUrl.'/'.str_plural($data['data']['type']).'/'.$data['data']['id'].'/relationships/'.$key,
                    'related' => $this->baseUrl.'/'.str_plural($data['data']['type']).'/'.$data['data']['id'].'/'.$key,
                ],
            ], $data['data']['relationships'][$key]);

            return $data;
        }

        return $data;
    }
}
