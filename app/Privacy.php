<?php

/*
 * This file is part of TechnicSolder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use MyCLabs\Enum\Enum;

/**
 * @method static Privacy PUBLIC()
 * @method static Privacy UNLISTED()
 * @method static Privacy PRIVATE()
 */
class Privacy extends Enum
{
    const PUBLIC = 'public';
    const UNLISTED = 'unlisted';
    const PRIVATE = 'private';

    /**
     * Compares one Enum with another.
     *
     * @param Privacy $privacy
     * @return bool True if Enums are equal, false if not equal
     */
    final public function is(Privacy $privacy)
    {
        return $this->equals($privacy);
    }
}
