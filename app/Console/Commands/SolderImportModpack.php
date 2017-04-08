<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Console\Commands;

use App\Modpack;
use App\Resource;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use TechnicPack\SolderClient\SolderClient;

class SolderImportModpack extends Command
{
    protected $modpack;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'solder:import-modpack {modpack-slug : The modpack slug to import} {--url= : The URL of the solder API to import from} {--key= : API Key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a modpack, its builds and resources from another solder install.';

    /**
     * The API connection object.
     *
     * @var SolderClient
     */
    protected $solderClient;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->init();

        $modpackSlug = $this->argument('modpack-slug');

        $response = $this->solderClient->getModpack($modpackSlug);

        $this->updateOrCreateModpack($response);

        foreach ($response->builds as $buildNumber) {
            $this->info("{$response->display_name} $buildNumber");

            $buildResponse = $this->solderClient->getBuild($modpackSlug, $buildNumber);

            $this->updateOrCreateBuild($buildNumber, $buildResponse);
            $this->downloadResourcesForBuild($buildNumber, $buildResponse->mods);

            $this->line("\n");
        }
    }

    /**
     * Initialize the command.
     */
    private function init()
    {
        $this->solderClient = SolderClient::factory($this->option('url'), $this->option('key'));
    }

    /**
     * @param $buildNumber
     * @param $modVersions
     */
    private function downloadResourcesForBuild($buildNumber, $modVersions)
    {
        $bar = $this->output->createProgressBar(count($modVersions));

        $build = $this->modpack->builds()->whereBuildNumber($buildNumber)->firstOrFail();

        foreach ($modVersions as $modVersion) {
            $version = $this->findOrCreateVersion($modVersion);

            $build->versions()->attach($version);

            $bar->advance();
        }

        $bar->finish();
    }

    /**
     * @param $modpack
     */
    private function updateOrCreateModpack($modpack)
    {
        $this->modpack = Modpack::updateOrCreate([
            'slug' => $modpack->name,
        ], [
            'name' => $modpack->display_name,
            'status' => Modpack::STATUS_PUBLIC,
            'recommended' => $modpack->recommended,
            'latest' => $modpack->latest,
        ]);
    }

    /**
     * @param $buildNumber
     * @param $build
     *
     * @return mixed
     */
    private function updateOrCreateBuild($buildNumber, $build)
    {
        return $this->modpack->builds()->updateOrCreate([
            'build_number' => $buildNumber,
        ], [
            'minecraft_version' => $build->minecraft,
            'status' => Modpack::STATUS_PUBLIC,
        ]);
    }

    /**
     * @param $modVersion
     *
     * @return mixed
     */
    private function findOrCreateVersion($modVersion)
    {
        $version = Resource::firstOrCreate(['slug' => $modVersion->name])
            ->versions()->firstOrCreate(['version_number' => $modVersion->version]);

        if ($modVersion->md5 != $version->zip_md5) {
            $filename = "{$modVersion->name}/{$modVersion->name}-{$modVersion->version}.zip";

            Storage::put($filename, fopen($modVersion->url, 'r'));

            $version->update([
                'zip_url' => Storage::url($filename),
                'zip_md5' => $modVersion->md5,
            ]);
        }

        return $version;
    }
}
