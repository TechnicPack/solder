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

use App\Version;
use Illuminate\Http\Request;
use App\Traits\ImplementsApi;
use App\Transformers\AssetTransformer;
use League\Fractal\TransformerAbstract;
use App\Transformers\VersionTransformer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VersionAssetsController extends ApiController
{
    use ImplementsApi;

    /**
     * Display a listing of the version assets data.
     *
     * @param Request $request
     *
     * @param Version $version
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Version $version)
    {
        return $this->transformAndRespond($version->assets, $request->query('include'));
    }

    /**
     * Display relationship data.
     *
     * @param Version $version
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws NotFoundHttpException
     */
    public function show(Version $version)
    {
        if (! $this->hasRelatedResource(VersionTransformer::class, 'assets')) {
            throw new NotFoundHttpException();
        }

        $response = $this->getRelatedResource($version, 'version', VersionTransformer::class, 'assets');

        return $this->respond($response);
    }

    /**
     * Store a newly created version in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Version $version
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Version $version)
    {
        $this->validate($request, [
            'data.attributes.filename' => 'required',
        ]);

        $version = $version->assets()->create($request->input('data.attributes'));

        $location = '/api/'.str_plural($this->resourceName()).'/'.$version->id;

        return $this->transformAndRespond($version)
            ->header('Location', $location)
            ->setStatusCode(201);
    }

    /**
     * The primary transformer for the controller.
     *
     * @return TransformerAbstract
     */
    protected function transformer()
    {
        return AssetTransformer::class;
    }

    /**
     * The primary resource type for data returned by the controller.
     *
     * @return string
     */
    protected function resourceName()
    {
        return 'asset';
    }
}
