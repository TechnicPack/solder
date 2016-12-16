<?php

namespace App\Http\Controllers\Api;

use App\Mod;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Transformers\ModTransformer;
use App\Http\Requests\ModStoreRequest;
use App\Http\Requests\ModUpdateRequest;
use App\Exceptions\IdentifierConflictException;

class ModsController extends ApiController
{
    /**
     * Display a listing of the mods.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $mods = Mod::all();

        $include = $request->input('include');

        return $this
            ->collection($mods, new ModTransformer(), 'mod')
            ->include($include)
            ->response();
    }

    /**
     * Store a newly created mod in storage.
     *
     * @param ModStoreRequest $request
     * @return Response
     * @throws IdentifierConflictException
     */
    public function store(ModStoreRequest $request)
    {
        $mod = Mod::create($request->input('data.attributes'));

        if ($request->input('data.id')) {
            $mod->id = $request->input('data.id');
            $mod->save();
        }

        return $this
            ->item($mod, new ModTransformer(), 'mod')
            ->addHeader('Location', '/mods/'.$mod->getKey())
            ->response(201);
    }

    /**
     * Display the specified mod.
     *
     * @param Request $request
     * @param Mod $mod
     * @return Response
     */
    public function show(Request $request, Mod $mod)
    {
        $include = $request->input('include');

        return $this
            ->item($mod, new ModTransformer(), 'mod')
            ->include($include)
            ->response();
    }

    /**
     * Update the specified mod in storage.
     *
     * @param ModUpdateRequest $request
     * @param Mod $mod
     * @return Response
     */
    public function update(ModUpdateRequest $request, Mod $mod)
    {
        $mod->update($request->input('data.attributes'));

        return $this
            ->item($mod, new ModTransformer(), 'mod')
            ->response();
    }

    /**
     * Remove the specified mod from storage.
     *
     * @param Request $request
     * @param Mod $mod
     * @return Response
     */
    public function destroy(Request $request, Mod $mod)
    {
        if ($request->get('cascade') == true) {
            $mod->releases()->delete();
        }

        $mod->delete();

        return $this
            ->emptyResponse();
    }
}
