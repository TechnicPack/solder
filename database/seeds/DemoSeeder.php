<?php

/*
 * This file is part of TechnicSolder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ==========
        // Boot up faker to help add some sugar to the demo data
        // ==========

        $faker = Faker\Factory::create();

        // ==========
        // Setup a User account and legacy api token
        // ==========

        /** @var \App\User $user */
        $user = factory(\App\User::class)->create([
            'name' => 'Demo User',
            'password' => bcrypt('secret'),
        ]);

        $user->legacyTokens()->create([
            'name' => 'Test Token',
            'token' => 'test-token',
        ]);

        // ==========
        // Create a couple Resources with versions
        // ==========

        /** @var \App\Resource $ironTanks */
        $ironTanks = factory(\App\Resource::class)->create([
            'name' => 'Iron Tanks',
            'author' => 'Indemnity83',
            'description' => $faker->sentence,
            'website' => $faker->url,
        ]);

        $ironTanks->versions()->create([
            'version' => '1.0.1.50',
        ]);

        $ironTanks->versions()->create([
            'version' => '1.0.2.54-beta',
        ]);

        /** @var \App\Resource $buildcraft */
        $buildcraft = factory(\App\Resource::class)->create([
            'name' => 'Buildcraft',
            'author' => 'Buildcraft Team',
            'description' => $faker->sentence,
            'website' => $faker->url,
        ]);

        $buildcraft->versions()->create([
            'version' => '7.1.17',
        ]);

        $buildcraft->versions()->create([
            'version' => '7.1.18',
        ]);

        /** @var \App\Resource $ironChests */
        $ironChests = factory(\App\Resource::class)->create([
            'name' => 'Iron Chests',
        ]);

        $ironChests->versions()->create([
            'version' => '6.0.125.770',
        ]);

        // ==========
        // Build a public Modpack
        // ==========

        /** @var \App\Modpack $modpack */
        $modpack = factory(\App\Modpack::class)->create([
            'name' => 'Demo Modpack',
            'privacy' => \App\Privacy::PUBLIC,
            'description' => $faker->sentence,
            'overview' => $faker->paragraphs(3, true),
            'help' => $faker->paragraphs(4, true),
            'license' => $faker->paragraphs(1, true),
            'tags' => $faker->words,
            'website' => $faker->url,
            'icon' => $faker->imageUrl(50, 50),
            'logo' => $faker->imageUrl(150, 120),
            'background' => $faker->imageUrl(900, 1000),
        ]);

        /** @var \App\Build $build */
        $build = $modpack->builds()->create([
            'version' => '1.0.0',
            'privacy' => \App\Privacy::PUBLIC,
            'game_version' => $faker->numerify('1.#.#'),
            'changelog' => $faker->sentences(4, true),
            'arguments' => [
                'forge' => $faker->numerify('#.#.#'),
                'memory' => $faker->biasedNumberBetween(512, 4096),
            ],
        ]);

        $build->versions()->attach($ironTanks->versions->first());
        $build->versions()->attach($buildcraft->versions->first());

        $build = $modpack->builds()->create([
            'version' => '1.1.0',
            'privacy' => \App\Privacy::UNLISTED,
            'game_version' => $faker->numerify('1.#.#'),
            'changelog' => $faker->sentences(4, true),
            'arguments' => [
                'memory' => $faker->biasedNumberBetween(512, 4096),
            ],
        ]);

        $build->versions()->attach($ironTanks->versions->last());
        $build->versions()->attach($buildcraft->versions->first());

        $build = $modpack->builds()->create([
            'version' => '2.0.0',
            'privacy' => \App\Privacy::PRIVATE,
            'game_version' => $faker->numerify('1.#.#'),
            'changelog' => $faker->sentences(4, true),
            'arguments' => [
                'forge' => $faker->numerify('#.#.#'),
            ],
        ]);

        $build->versions()->attach($ironTanks->versions->last());
        $build->versions()->attach($buildcraft->versions->last());

        // ==========
        // Build a sneaky unlisted Modpack
        // ==========

        $modpack = factory(\App\Modpack::class)->create([
            'name' => 'Secret Modpack',
            'privacy' => \App\Privacy::UNLISTED,
            'description' => $faker->sentence,
            'overview' => $faker->paragraphs(3, true),
            'help' => $faker->paragraphs(4, true),
            'license' => $faker->paragraphs(1, true),
            'tags' => $faker->words,
            'website' => $faker->url,
            'icon' => $faker->imageUrl(50, 50),
            'logo' => $faker->imageUrl(150, 120),
            'background' => $faker->imageUrl(900, 1000),
        ]);

        $build = $modpack->builds()->create([
            'version' => '0.1.3',
            'privacy' => \App\Privacy::PUBLIC,
            'game_version' => $faker->numerify('1.#.#'),
            'changelog' => $faker->sentences(4, true),
            'arguments' => [
                'forge' => $faker->numerify('#.#.#'),
                'memory' => $faker->biasedNumberBetween(512, 4096),
            ],
        ]);

        $build->versions()->attach($ironTanks->versions->first());
        $build->versions()->attach($ironChests->versions->first());
        $build->versions()->attach($buildcraft->versions->first());

        // ==========
        // Build a completely private modpack
        // ==========

        $modpack = factory(\App\Modpack::class)->create([
            'name' => 'Private Modpack',
            'privacy' => \App\Privacy::PRIVATE,
            'description' => $faker->sentence,
            'overview' => $faker->paragraphs(3, true),
            'help' => $faker->paragraphs(4, true),
            'license' => $faker->paragraphs(1, true),
            'tags' => $faker->words,
            'website' => $faker->url,
            'icon' => $faker->imageUrl(50, 50),
            'logo' => $faker->imageUrl(150, 120),
            'background' => $faker->imageUrl(900, 1000),
        ]);

        $build = $modpack->builds()->create([
            'version' => '0.1.0-alpha',
            'privacy' => \App\Privacy::PRIVATE,
            'game_version' => $faker->numerify('1.#.#'),
            'changelog' => $faker->sentences(4, true),
            'arguments' => array_combine($faker->words, $faker->words),
        ]);

        $build->versions()->attach($ironChests->versions->first());
        $build->versions()->attach($buildcraft->versions->last());
    }
}
