<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Exceptions\Transformers;

use Exception;
use Illuminate\Auth\AuthenticationException;
use GrahamCampbell\Exceptions\Transformers\TransformerInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthTransformer implements TransformerInterface
{
    /**
     * Transform the provided exception.
     *
     * @param \Exception $exception
     *
     * @return \Exception
     */
    public function transform(Exception $exception)
    {
        if ($exception instanceof AuthenticationException) {
            $exception = new UnauthorizedHttpException(null, $exception->getMessage(), $exception, $exception->getCode());
        }

        return $exception;
    }
}
