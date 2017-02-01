<?php
/*
 * This file is part of solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Api;

trait ApiActions
{
    /**
     * Call the given URI with an API request.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function api($method, $uri, array $data = [], array $headers = [])
    {
        $headers = array_merge($headers, [
            'Content-Type' => 'application/vnd.api+json',
            'Accept' => 'application/vnd.api+json',
        ]);

        return $this->json($method, $uri, $data, $headers);
    }

    /**
     * Visit the given URI with a GET request, expecting an API response.
     *
     * @param  string  $uri
     * @param  array  $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function getApi($uri, array $headers = [])
    {
        return $this->api('GET', $uri, [], $headers);
    }

    /**
     * Visit the given URI with a POST request, expecting an API response.
     *
     * @param  string $uri
     * @param array $data
     * @param  array $headers
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function postApi($uri, $data = [], array $headers = [])
    {
        return $this->api('POST', $uri, $data, $headers);
    }

    /**
     * Visit the given URI with a PATCH request, expecting an API response.
     *
     * @param  string $uri
     * @param array $data
     * @param  array $headers
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function patchApi($uri, $data = [], array $headers = [])
    {
        return $this->api('PATCH', $uri, $data, $headers);
    }

    /**
     * Visit the given URI with a PUT request, expecting an API response.
     *
     * @param  string $uri
     * @param array $data
     * @param  array $headers
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function putApi($uri, $data = [], array $headers = [])
    {
        return $this->api('PUT', $uri, $data, $headers);
    }

    /**
     * Visit the given URI with a DELETE request, expecting an API response.
     *
     * @param  string  $uri
     * @param  array  $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function deleteApi($uri, array $headers = [])
    {
        return $this->api('DELETE', $uri, [], $headers);
    }
}
