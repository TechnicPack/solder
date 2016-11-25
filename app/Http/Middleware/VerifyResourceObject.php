<?php

namespace App\Http\Middleware;

use Closure;
use App\Exceptions\ResourceMismatchException;

class VerifyResourceObject
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param $type
     * @return mixed
     * @throws ResourceMismatchException
     */
    public function handle($request, Closure $next, $type)
    {
        if ($request->input('data.type') == $type) {
            return $next($request);
        }

        $message = sprintf('Expected a `%s` resource object, but received `%s`', $type, $request->input('data.type'));

        throw new ResourceMismatchException($message);
    }
}
