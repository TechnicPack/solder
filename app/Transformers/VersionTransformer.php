<?php

namespace App\Transformers;

use App\Version;
use League\Fractal\TransformerAbstract;

class VersionTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['mod', 'builds', 'asset'];

    public function transform(Version $version)
    {
        return [
            'id' => $version->getRouteKey(),
            'version' => $version->version,
            'url' => @$version->archive->url,
            'created_at' => $version->created_at->format('c'),
            'updated_at' => $version->updated_at->format('c'),
        ];
    }

    public function includeBuilds(Version $version)
    {
        return fractal()
            ->collection($version->builds)
            ->transformWith(new self())
            ->withResourceName('build')
            ->getResource();
    }

    public function includeAsset(Version $version)
    {
        if (! $asset = $version->archive) {
            return $this->null();
        }

        return fractal()
            ->item($version->archive)
            ->transformWith(new AssetTransformer())
            ->withResourceName('asset')
            ->getResource();
    }

    public function includeMod(Version $version)
    {
        return $this->item($version->mod, new ModTransformer(), 'mod');
    }
}
