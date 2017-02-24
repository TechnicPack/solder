<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Exceptions;

use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidationException extends HttpException
{
    /**
     * @var Validator
     */
    public $validator;

    /**
     * Constructor.
     *
     * @param Validator $validator
     * @param \Exception $previous The previous exception
     * @param int $code The internal exception code
     */
    public function __construct(Validator $validator, \Exception $previous = null, $code = 0)
    {
        $this->validator = $validator;

        parent::__construct(422, 'The given data failed to pass validation.', $previous, [], $code);
    }
}
