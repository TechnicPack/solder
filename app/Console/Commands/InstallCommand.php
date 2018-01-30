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

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'solder:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the commands necessary to prepare Solder for use';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->welcome();
        $this->createEnvFile();
        $this->generateAppKey();
        $this->migrateDatabase();
        $this->generateApiKey();
        $this->linkStorage();
        $this->goodbye();
    }

    /**
     * Present the welcome message.
     */
    private function welcome()
    {
        $this->info('>> Welcome to the Solder installation process! <<');
    }

    /**
     * Copy .env.example to .env if it doesn't already exist.
     */
    private function createEnvFile()
    {
        if (file_exists('.env')) {
            return;
        }

        if (copy('.env.example', '.env')) {
            $this->line('.env file successfully created');
        }
    }

    /**
     * Generate the application key.
     */
    private function generateAppKey()
    {
        if (strlen(config('app.key')) > 0) {
            return;
        }

        if ($this->call('key:generate')) {
            $this->line('~ Secret key properly generated');
        }
    }

    /**
     * Configure db connection and migrate database.
     */
    private function migrateDatabase()
    {
        $this->updateEnvironmentFile([
            'DB_DATABASE' => $this->ask('Database name', 'solder'),
            'DB_HOST' => $this->ask('Database host', 'localhost'),
            'DB_PORT' => $this->ask('Database port', 3306),
            'DB_USERNAME' => $this->ask('Database user'),
            'DB_PASSWORD' => $this->secret('Database password ("null" for no password)'),
        ]);

        if ($this->confirm('Do you want to migrate the database?', false)) {
            if ($this->call('migrate', ['--seed', '--force'])) {
                $this->line('~ Database successfully migrated');
            }
        }
    }

    /**
     * Generate the API keys.
     */
    private function generateApiKey()
    {
        if ($this->call('passport:keys')) {
            $this->line('~ Api keys properly generated');
        }
    }

    /**
     * Link storage path into public directory.
     */
    private function linkStorage()
    {
        if ($this->call('storage:link')) {
            $this->line('~ File storage successfully linked');
        }
    }

    /**
     * Present the goodbye message.
     */
    private function goodbye()
    {
        $this->info('>> The installation process is complete. Enjoy your supercharged modpacks! <<');
    }

    /**
     * Update the .env file with the given key, value pairs.
     *
     * @param $updatedValues
     */
    private function updateEnvironmentFile($updatedValues)
    {
        $envFile = $this->laravel->environmentFilePath();

        foreach ($updatedValues as $key => $value) {
            file_put_contents($envFile, preg_replace(
                "/{$key}=(.*)/",
                "{$key}={$value}",
                file_get_contents($envFile)
            ));
        }
    }
}
