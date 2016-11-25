<?php

namespace App\Transformers;

use App\Release;
use League\Fractal\TransformerAbstract;

class ReleaseTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['mod', 'builds', 'asset'];

    public function transform(Release $release)
    {
        return [
            'id' => $release->getRouteKey(),
            'version' => $release->version,
            'url' => @$release->archive->url,
            'created_at' => $release->created_at->format('c'),
            'updated_at' => $release->updated_at->format('c'),
        ];
    }

    public function includeBuilds(Release $release)
    {
        return fractal()
            ->collection($release->builds)
            ->transformWith(new self())
            ->withResourceName('build')
            ->getResource();
    }

    public function includeAsset(Release $release)
    {
        if (! $asset = $release->archive) {
            return $this->null();
        }

        return fractal()
            ->item($release->archive)
            ->transformWith(new AssetTransformer())
            ->withResourceName('asset')
            ->getResource();
    }

    public function includeMod(Release $release)
    {
        return $this->item($release->mod, new ModTransformer(), 'mod');
    }
}
