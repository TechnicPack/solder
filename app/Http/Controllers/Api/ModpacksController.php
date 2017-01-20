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

use Auth;
use Storage;
use Exception;
use App\Modpack;
use App\Privacy;
use Illuminate\Http\Request;
use App\Traits\ImplementsApi;
use League\Fractal\TransformerAbstract;
use App\Transformers\ModpackTransformer;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\LengthRequiredHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class ModpacksController extends ApiController
{
    use ImplementsApi;

    /**
     * The primary transformer for the controller.
     *
     * @return TransformerAbstract
     */
    protected function transformer()
    {
        return ModpackTransformer::class;
    }

    /**
     * The primary resource type for data returned by the controller.
     *
     * @return string
     */
    protected function resourceName()
    {
        return 'modpack';
    }

    /**
     * Display a listing of the modpack data.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $modpacks = Modpack::withoutPrivacy(Auth::user())->get();

        return $this->transformAndRespond($modpacks, $request->query('include'));
    }

    /**
     * Display the specified modpack.
     *
     * @param Request $request
     * @param Modpack $modpack
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws AuthenticationException
     */
    public function show(Request $request, Modpack $modpack)
    {
        if (! Auth::check() && $modpack->privacy('private')) {
            throw new AuthenticationException();
        }

        return $this->transformAndRespond($modpack, $request->query('include'));
    }

    /**
     * Save a newly created model in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'data.attributes.name' => 'required',
            'data.attributes.slug' => 'sometimes|required|unique:modpacks,slug|alpha_dash',
            'data.attributes.privacy' => 'in:'.implode(',', Privacy::values()),
            'data.attributes.tags' => 'array',
            'data.attributes.website' => 'url',
        ]);

        $modpack = Modpack::create($request->input('data.attributes'));

        $location = '/api/'.str_plural($this->resourceName()).'/'.$modpack->id;

        return $this->transformAndRespond($modpack)
            ->header('Location', $location)
            ->setStatusCode(201);
    }

    /**
     * Update the specified model in storage.
     *
     * @param Request $request
     * @param Modpack $modpack
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Modpack $modpack)
    {
        $this->accept($request, [
            'data.id' => $modpack->id,
            'data.type' => $this->resourceName(),
        ]);

        $this->validate($request, [
            'data.attributes.name' => 'sometimes|required',
            'data.attributes.slug' => 'sometimes|required|alpha_dash|unique:modpacks,slug,'.$modpack->id,
            'data.attributes.privacy' => 'in:'.implode(',', Privacy::values()),
            'data.attributes.tags' => 'array',
            'data.attributes.website' => 'url',
        ]);

        $modpack->update($request->input('data.attributes'));

        return $this->transformAndRespond($modpack);
    }

    /**
     * Put an asset for the specified modpack in storage.
     *
     * @param Request $request
     * @param Modpack $modpack
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function upload(Request $request, Modpack $modpack, $asset)
    {
        $assetTypes = collect([
            'icon',
            'logo',
            'background',
        ]);

        if (! $assetTypes->contains($asset)) {
            throw new AccessDeniedHttpException();
        }

        // TODO: Implement an application/json handler
        switch ($request->header('content-type')) {
            case 'image/gif':
            case 'image/png':
            case 'image/jpg':
            case 'image/jpeg':
                if ($request->header('content-length') == null) {
                    throw new LengthRequiredHttpException();
                }

                if ($request->header('content-length') > config('solder.modpacks.'.$asset.'.max_filesize')) {
                    throw new HttpException(413);
                }

                $guesser = ExtensionGuesser::getInstance();
                $filename = $asset.'_'.$modpack->id.'.'.$guesser->guess($request->header('content-type'));
                $contents = $request->getContent(true);
                break;
            default:
                throw new UnsupportedMediaTypeHttpException();
        }

        if (! Storage::put($path = 'modpacks/'.$filename, $contents, 'public')) {
            throw new Exception('Failed to put file');
        }

        $modpack->forceFill([
            $asset => Storage::url($path),
        ])->save();

        $data = fractal()
            ->item($modpack)
            ->transformWith(new ModpackTransformer())
            ->withResourceName('modpack');

        return response()->json($data)->header('Content-Type', 'application/vnd.api+json');
    }

    /**
     * Remove the specified modpack from storage.
     *
     * @param Modpack $modpack
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Modpack $modpack)
    {
        $modpack->delete();

        return response(null, 204);
    }
}
