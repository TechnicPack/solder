<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
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
     * @param Package $package
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Package $package)
    {
        return fractal($package, new PackageTransformer())->respond();
    }
}
