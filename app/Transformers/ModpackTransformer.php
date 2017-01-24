<?php

/*
 * This file is part of TechnicSolder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Transformers;

use Auth;
use App\Modpack;
use League\Fractal\TransformerAbstract;

class ModpackTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */
    protected $availableIncludes = [
        'builds',
    ];

    /**
     * Transform the \Modpack entity.
     * @param Modpack $modpack
     * @return array
     */
    public function transform(Modpack $modpack)
    {
        return [
            'id' => $modpack->id,
            'name' => $modpack->name,
            'slug' => $modpack->slug,
            'description' => $modpack->description,
            'overview' => $modpack->overview,
            'help' => $modpack->help,
            'license' => $modpack->license,
            'privacy' => $modpack->privacy,
            'tags' => $modpack->tags,
            'website' => $modpack->website,
            'icon' => $modpack->icon,
            'logo' => $modpack->logo,
            'background' => $modpack->background,
            'created_at' => $modpack->created_at->format('c'),
            'updated_at' => $modpack->updated_at->format('c'),
        ];
    }

    /**
     * Include Builds.
     * @param Modpack $modpack
     * @return \League\Fractal\Resource\Collection
     */
    public function includeBuilds(Modpack $modpack)
    {
        $builds = $modpack->builds()->withoutPrivacy(Auth::user())->get();

        return $this->collection($builds, BuildTransformer::class, 'build');
    }
}
