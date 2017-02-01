<?php

/*
 * This file is part of TechnicSolder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;

trait MakesJsonApiRequests
{
    use MakesHttpRequests;

    /**
     * Visit the given URI with a GET request, expecting a JSON API response.
     *
     * @param  string  $uri
     * @param  array  $headers
     * @return $this
     */
    public function getJsonApi($uri, array $headers = [])
    {
        return $this->jsonApi('GET', $uri, [], $headers);
    }

    /**
     * Visit the given URI with a POST request, expecting a JSON API response.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return $this
     */
    public function postJsonApi($uri, array $data = [], array $headers = [])
    {
        return $this->jsonApi('POST', $uri, $data, $headers);
    }

    /**
     * Visit the given URI with a PUT request, expecting a JSON API response.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return $this
     */
    public function putJsonApi($uri, array $data = [], array $headers = [])
    {
        return $this->jsonApi('PUT', $uri, $data, $headers);
    }

    /**
     * Visit the given URI with a PATCH request, expecting a JSON API response.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return $this
     */
    public function patchJsonApi($uri, array $data = [], array $headers = [])
    {
        return $this->jsonApi('PATCH', $uri, $data, $headers);
    }

    /**
     * Visit the given URI with a DELETE request, expecting a JSON API response.
     *
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return $this
     */
    public function deleteJsonApi($uri, array $data = [], array $headers = [])
    {
        return $this->jsonApi('DELETE', $uri, $data, $headers);
    }

    /**
     * Visit the given URI with a JSON API request.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  array  $data
     * @param  array  $headers
     * @return $this
     */
    public function jsonApi($method, $uri, array $data = [], array $headers = [])
    {
        $content = json_encode($data);

        $headers = array_merge([
            'CONTENT_LENGTH' => mb_strlen($content, '8bit'),
            'CONTENT_TYPE' => 'application/vnd.api+json',
            'Accept' => 'application/vnd.api+json',
        ], $headers);

        $this->call(
            $method,
            $uri,
            [],
            [],
            [],
            $this->transformHeadersToServerVars($headers),
            $content
        );

        return $this;
    }

    /**
     * Assert that the response contains a valid JSON API response.
     */
    protected function seeJsonApi()
    {
        $this->seeHeader('Content-Type', 'application/vnd.api+json');
        $this->isJson();

        $response = $this->decodeResponseJson();

        $this->assertTrue(
            isset($response['data']) || isset($response['errors']) || isset($response['meta']),
            'A document MUST contain at least one of the following top-level members: data, errors, meta.'
        );

        $this->assertFalse(
            isset($response['data']) && isset($response['errors']),
            'The members data and errors MUST NOT coexist in the same document.'
        );

        $this->assertFalse(
            isset($response['included']) && ! isset($response['data']),
            'If a document does not contain a top-level data key, the included member MUST NOT be present either.'
        );

        // TODO: Primary data MUST be either: a single resource object, or an array of resource objects
    }

    /**
     * Assert that the response contains a valid JSON API error response.
     *
     * @param $code
     * @param null $title
     * @param array $contains
     */
    protected function seeJsonApiError($code, $title = null, $contains = [])
    {
        $this->assertResponseStatus($code);
        $this->seeJsonApi();
        $this->seeJsonSubset(['errors' => []]);

        if ($title !== null) {
            $this->seeJson(['title' => $title]);
        }

        $this->seeJsonContains($contains);
    }
}
