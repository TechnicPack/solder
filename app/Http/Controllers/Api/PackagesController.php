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

use App\Package;
use App\Http\Controllers\Controller;
use App\Transformers\PackageTransformer;

class PackagesController extends Controller
{
    /**
     * Return all packages.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return fractal(Package::all(), new PackageTransformer())->respond();
    }

    /**
     * Return details about a specific package.
     *
     * @param $packageId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($packageId)
    {
        $package = Package::findOrFail($packageId);

        return fractal($package, new PackageTransformer())->respond();
    }
}
