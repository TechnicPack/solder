<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Traits;

use App\Privacy;
use Illuminate\Database\Eloquent\Builder;

trait HasPrivacy
{
    /**
     * Get the privacy object for the model.
     *
     * @param null|string $privacy
     * @return Privacy|bool
     */
    public function privacy($privacy = null)
    {
        if ($privacy !== null) {
            return $this->privacy()->equals(new Privacy($privacy));
        }

        return new Privacy($this->privacy);
    }

    /**
     * Filter results for display based on authenticated user permissions.
     *
     * @param Builder $query
     * @param bool $skip
     *
     * @return Builder $query
     */
    public function scopeWithoutPrivacy(Builder $query, $skip = false)
    {
        if ($skip) {
            return $query;
        }

        return $query->where('privacy', Privacy::PUBLIC);
    }

    /**
     * Filter results for display based on authenticated user permissions.
     *
     * @param Builder $query
     * @param bool $ignorePrivacy
     *
     * @return Builder $query
     */
    public function scopeIgnorePrivacy(Builder $query, $ignorePrivacy = true)
    {
        if ($ignorePrivacy) {
            return $query;
        }

        return $query->where('privacy', Privacy::PUBLIC);
    }
}
