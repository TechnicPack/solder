<?php

namespace App\Serializers;

use League\Fractal\Serializer\ArraySerializer;

/**
 * Class FlatSerializer.
 */
class FlatSerializer extends ArraySerializer
{
    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function collection($resourceKey, array $data)
    {
        return $data;
    }
}
