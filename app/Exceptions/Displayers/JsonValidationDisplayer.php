<?php

/*
 * This file is part of TechnicSolder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Exceptions\Displayers;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Exceptions\ValidationException;
use GrahamCampbell\Exceptions\Displayers\JsonApiDisplayer;
use GrahamCampbell\Exceptions\Displayers\DisplayerInterface;

class JsonValidationDisplayer extends JsonApiDisplayer implements DisplayerInterface
{
    /**
     * Get the error response associated with the given exception.
     *
     * @param \Exception $exception
     * @param string     $id
     * @param int        $code
     * @param string[]   $headers
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function display(Exception $exception, $id, $code, array $headers)
    {
        $info = $this->info->generate($exception, $id, $code);

        foreach ($exception->validator->messages()->toArray() as $pointer => $messages) {
            foreach ($messages as $message) {
                $sources[] = [
                    'pointer' => $this->makePointer($pointer),
                    'message' => $message,
                ];
            }
        }

        $error = ['id' => $id, 'status' => $info['code'], 'title' => $info['name'], 'detail' => $info['detail'], 'source' => $sources];

        return new JsonResponse(['errors' => [$error]], $code, array_merge($headers, ['Content-Type' => $this->contentType()]));
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
        if ($original instanceof ValidationException) {
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

    private function makePointer($pointer)
    {
        return str_replace('.', '/', $pointer);
    }
}
