<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Exceptions\Displayers;

use Exception;
use App\Exceptions\ValidationException;
use Illuminate\Auth\AuthenticationException;
use GrahamCampbell\Exceptions\Displayers\DisplayerInterface;

class LoginRedirect implements DisplayerInterface
{
    /**
     * Get the error response associated with the given exception.
     *
     * @param ValidationException|Exception $exception
     * @param string $id
     * @param int $code
     * @param string[] $headers
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function display(Exception $exception, $id, $code, array $headers)
    {
        return redirect()->guest('login');
    }

    /**
     * Can we display the exception?
     *
     * @param \Exception $original
     * @param \Exception $transformed
     * @param int        $code
     *
     * @return bool
     */
    public function canDisplay(Exception $original, Exception $transformed, $code)
    {
        if ($original instanceof AuthenticationException) {
            return true;
        }

        return false;
    }

    /**
     * Do we provide verbose information about the exception?
     *
     * @return bool
     */
    public function isVerbose()
    {
        return false;
    }

    /**
     * Get the supported content type.
     *
     * @return string
     */
    public function contentType()
    {
        return 'text/html';
    }
}
