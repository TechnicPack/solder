<?php

/*
 * This file is part of TechnicSolder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Api;

use App\Asset;
use Illuminate\Http\Request;
use App\Traits\ImplementsApi;
use App\Transformers\AssetTransformer;
use League\Fractal\TransformerAbstract;

class AssetsController extends ApiController
{
    use ImplementsApi;

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

    /**
     * Display a listing of the asset data.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return $this->transformAndRespond(Asset::all(), $request->query('include'));
    }

    /**
     * Display the specified asset.
     *
     * @param Request $request
     * @param Asset $asset
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Asset $asset)
    {
        return $this->transformAndRespond($asset, $request->query('include'));
    }

    /**
     * Save a newly created asset in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'data.attributes.filename' => 'required',
        ]);

        $asset = Asset::create($request->input('data.attributes'));

        $location = '/api/'.str_plural($this->resourceName()).'/'.$asset->id;

        return $this->transformAndRespond($asset)
            ->header('Location', $location)
            ->setStatusCode(201);
    }

    /**
     * Update the specified asset in storage.
     *
     * @param Request $request
     * @param Asset $asset
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Asset $asset)
    {
        $this->accept($request, [
            'data.id' => $asset->id,
            'data.type' => $this->resourceName(),
        ]);

        $this->validate($request, [
            'data.attributes.filename' => 'sometimes|required',
        ]);

        $asset->update($request->input('data.attributes'));

        return $this->transformAndRespond($asset);
    }

    /**
     * Remove the specified asset from storage.
     *
     * @param Asset $asset
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Asset $asset)
    {
        $asset->delete();

        return response(null, 204);
    }
}
