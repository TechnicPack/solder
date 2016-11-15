<?php

namespace App\Transformers\v07;

use App\Modpack;
use League\Fractal\TransformerAbstract;

/**
 * Class ModpackTransformer
 * @package App\Transformers
 */
class ModpackTransformer extends TransformerAbstract
{
    public function transform(Modpack $modpack)
    {
        return [
            'name' => $modpack->slug,
            'display_name' => $modpack->name,
            'url' => $modpack->link,
            'icon' => @$modpack->icon->url,
            'icon_md5' => @$modpack->icon->hash,
            'logo' => @$modpack->logo->url,
            'logo_md5' => @$modpack->logo->hash,
            'background' => @$modpack->background->url,
            'background_md5' => @$modpack->background->hash,
            'recommended' => @$modpack->promoted->version,
            'latest' => @$modpack->latest->version,
            'builds' => $modpack->builds->pluck('version')
        ];
    }
}
