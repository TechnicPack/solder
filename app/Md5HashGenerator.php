<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

class Md5HashGenerator implements FileHashGenerator
{
    public function hash($url)
    {
        return md5_file($url);
    }
}
