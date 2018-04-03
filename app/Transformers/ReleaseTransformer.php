<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Transformers;

use App\Release;
use League\Fractal\TransformerAbstract;

class ReleaseTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @param Release $release
     *
     * @return array
     */
    public function transform($release)
    {
        return [
            'id' => $release->id,
            'version' => $release->version,
        ];
    }
}
