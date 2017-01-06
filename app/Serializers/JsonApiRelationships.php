<?php
/*
 * This file is part of solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Serializers;

class JsonApiRelationships extends JsonApiResource
{
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

        return $resource['data']['relationships']['build'];
    }
}
