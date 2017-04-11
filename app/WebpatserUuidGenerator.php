<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use Webpatser\Uuid\Uuid as WebpatserUuid;

class WebpatserUuidGenerator implements UuidGenerator
{
    public function generate()
    {
        return WebpatserUuid::generate(4)->string;
    }
}
