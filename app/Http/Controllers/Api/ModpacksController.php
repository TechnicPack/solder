<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\IdentifierConflictException;
use App\Http\Requests\ModpackStoreRequest;
use App\Http\Requests\ModpackUpdateRequest;
use App\Modpack;
use App\Transformers\ModpackTransformer;
use Illuminate\Http\Request;

class ModpacksController extends ApiController
{
    /**
     * Display a listing of the modpacks.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $modpacks = Modpack::all();

        $include = $request->input('include');

        return $this
            ->collection($modpacks, new ModpackTransformer(), 'modpacks')
            ->include($include)
            ->response();
    }

    /**
     * Store a newly created modpack in storage.
     *
     * @param ModpackStoreRequest $request
     * @return \Illuminate\Http\Response
     * @throws IdentifierConflictException
     */
    public function store(ModpackStoreRequest $request)
    {
        $modpack = Modpack::create($request->input('data.attributes'));

        return $this
            ->item($modpack, new ModpackTransformer(), 'modpacks')
            ->addHeader('Location', '/modpacks/'.$modpack->getKey())
            ->response(201);
    }

    /**
     * Display the specified modpack.
     *
     * @param Request $request
     * @param Modpack $modpack
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Modpack $modpack)
    {
        $include = $request->input('include');

        return $this
            ->item($modpack, new ModpackTransformer(), 'modpacks')
            ->include($include)
            ->response();
    }

    /**
     * Update the specified modpack in storage.
     *
     * @param ModpackUpdateRequest $request
     * @param Modpack $modpack
     * @return \Illuminate\Http\Response
     */
    public function update(ModpackUpdateRequest $request, Modpack $modpack)
    {
        $modpack->update($request->input('data.attributes'));

        return $this
            ->item($modpack, new ModpackTransformer(), 'modpacks')
            ->addHeader('Location', '/modpacks/'.$modpack->getKey())
            ->response();
    }

    /**
     * Store the icon for the modpack
     *
     * @param Request $request
     * @param Modpack $modpack
     * @return \Illuminate\Http\Response
     */
    public function icon(Request $request, Modpack $modpack)
    {
        //
    }

    /**
     * Store the logo for the modpack
     *
     * @param Request $request
     * @param Modpack $modpack
     * @return \Illuminate\Http\Response
     */
    public function logo(Request $request, Modpack $modpack)
    {
        //
    }

    /**
     * Store the background for the modpack
     *
     * @param Request $request
     * @param Modpack $modpack
     * @return \Illuminate\Http\Response
     */
    public function background(Request $request, Modpack $modpack)
    {
        //
    }

    /**
     * Remove the specified modpack from storage.
     *
     * @param Modpack $modpack
     * @return \Illuminate\Http\Response
     */
    public function destroy(Modpack $modpack)
    {
        $modpack->delete();

        return $this
            ->emptyResponse();
    }
}
