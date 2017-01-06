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

use App\Build;
use App\Privacy;
use Illuminate\Http\Request;
use App\Traits\ImplementsApi;
use Illuminate\Support\Facades\Auth;
use App\Transformers\BuildTransformer;
use League\Fractal\TransformerAbstract;
use Illuminate\Auth\AuthenticationException;

class BuildsController extends ApiController
{
    use ImplementsApi;

    /**
     * The primary transformer for the controller.
     *
     * @return TransformerAbstract
     */
    protected function transformer()
    {
        return BuildTransformer::class;
    }

    /**
     * The primary resource type for data returned by the controller.
     *
     * @return string
     */
    protected function resourceName()
    {
        return 'build';
    }

    /**
     * Display a listing of the build data.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $builds = Build::withoutPrivacy(Auth::user())->get();

        return $this->transformAndRespond($builds, $request->query('include'));
    }

    /**
     * Display the specified build.
     *
     * @param Request $request
     * @param Build $build
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthenticationException
     */
    public function show(Request $request, Build $build)
    {
        if (! Auth::check() && $build->privacy('private')) {
            throw new AuthenticationException();
        }

        return $this->transformAndRespond($build, $request->query('include'));
    }

    /**
     * Save a newly created build in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'data.attributes.version' => 'required',
            'data.attributes.privacy' => 'in:'.implode(',', Privacy::values()),
            'data.attributes.arguments' => 'array',
        ]);

        $build = Build::create($request->input('data.attributes'));

        $location = '/api/'.str_plural($this->resourceName()).'/'.$build->id;

        return $this->transformAndRespond($build)
            ->header('Location', $location)
            ->setStatusCode(201);
    }

    /**
     * Update the specified build in storage.
     *
     * @param Request $request
     * @param Build $build
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Build $build)
    {
        $this->accept($request, [
            'data.id' => $build->id,
            'data.type' => $this->resourceName(),
        ]);

        $this->validate($request, [
            'data.attributes.version' => 'sometimes|required',
            'data.attributes.privacy' => 'in:'.implode(',', Privacy::values()),
            'data.attributes.arguments' => 'array',
        ]);

        $build->update($request->input('data.attributes'));

        return $this->transformAndRespond($build);
    }

    /**
     * Remove the specified build from storage.
     *
     * @param Build $build
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Build $build)
    {
        $build->delete();

        return response(null, 204);
    }
}
