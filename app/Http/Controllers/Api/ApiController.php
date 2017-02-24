<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Request;
use App\Exceptions\ValidationException;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        Auth::shouldUse('api');
    }

    /**
     * Check the given request matches the endpoint.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $rules
     * @return void
     */
    public function accept(Request $request, array $rules)
    {
        foreach ($rules as $key => $value) {
            if ($request->input($key) !== $value) {
                $pointer = str_replace('.', '/', $key);
                throw new ConflictHttpException('The value of '.$pointer.' conflicts with the resource.');
            }
        }
    }

    /**
     * Throw the failed validation exception.
     *
     * @param Request $request
     * @param $validator
     */
    protected function throwValidationException(Request $request, $validator)
    {
        throw new ValidationException($validator);
    }
}
