<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature;

use App\User;
use App\Build;
use App\Modpack;
use App\Package;
use App\Release;
use BuildFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowBuildTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_view_the_requested_build()
    {
        $user = factory(User::class)->create();
        $modpackA = factory(Modpack::class)->create(['slug' => 'modpack-a']);
        $buildA = BuildFactory::createForModpack($modpackA, ['version' => '1.2.3']);

        $modpackB = factory(Modpack::class)->create(['slug' => 'modpack-b']);
        $buildB = BuildFactory::createForModpack($modpackB, ['version' => '1.0.0']);
        $buildC = BuildFactory::createForModpack($modpackB, ['version' => '1.2.3']);

        $response = $this->actingAs($user)->get('/modpacks/modpack-b/1.2.3');

        $response->assertStatus(200);
        $response->assertViewIs('builds.show');
        $this->assertTrue($response->data('build')->is($buildC));
    }

    /** @test */
    public function guests_cannnot_view_build_page()
    {
        $modpack = factory(Modpack::class)->create(['slug' => 'example-modpack']);
        BuildFactory::createForModpack($modpack, ['version' => '1.2.3']);

        $response = $this->get('/modpacks/example-modpack/1.2.3');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function build_releases_are_listed_alphabetically()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'example-modpack']);
        $build = BuildFactory::createForModpack($modpack, ['version' => '1.2.3']);
        $packageC = factory(Package::class)->create(['name' => 'Package C']);
        $releaseC = factory(Release::class)->create(['package_id' => $packageC->id]);
        $build->releases()->attach($releaseC);
        $packageB = factory(Package::class)->create(['name' => 'Package B']);
        $releaseB = factory(Release::class)->create(['package_id' => $packageB->id]);
        $build->releases()->attach($releaseB);
        $packageA = factory(Package::class)->create(['name' => 'Package A']);
        $releaseA = factory(Release::class)->create(['package_id' => $packageA->id]);
        $build->releases()->attach($releaseA);

        $response = $this->actingAs($user)->get('/modpacks/example-modpack/1.2.3');

        $response->data('build')->releases->assertEquals([
            $releaseA,
            $releaseB,
            $releaseC,
        ]);
    }

    /** @test */
    public function only_releases_for_the_build_are_included()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'example-modpack']);
        $buildA = BuildFactory::createForModpack($modpack, ['version' => '1.2.3']);
        $packageA = factory(Package::class)->create(['name' => 'Package A']);
        $releaseA = factory(Release::class)->create(['package_id' => $packageA->id]);
        $buildA->releases()->attach($releaseA);
        $buildB = BuildFactory::createForModpack($modpack, ['version' => '4.5.6']);
        $packageB = factory(Package::class)->create(['name' => 'Package B']);
        $releaseB = factory(Release::class)->create(['package_id' => $packageB->id]);
        $buildB->releases()->attach($releaseB);

        $response = $this->actingAs($user)->get('/modpacks/example-modpack/1.2.3');

        $response->data('build')->releases->assertContains($releaseA);
        $response->data('build')->releases->assertNotContains($releaseB);
    }

    /** @test */
    public function a_user_cannot_view_a_non_existent_modpack()
    {
        $user = factory(User::class)->create();
        factory(Build::class)->create(['version' => '1.2.3']);

        $response = $this->actingAs($user)->get('/modpacks/fake-modpack/1.2.3');

        $response->assertStatus(404);
    }

    /** @test */
    public function a_user_cannot_view_a_non_existent_build()
    {
        $user = factory(User::class)->create();
        factory(Modpack::class)->create(['slug' => 'example-modpack']);

        $response = $this->actingAs($user)->get('/modpacks/example-modpack/fake-version');

        $response->assertStatus(404);
    }

    /** @test */
    public function packages_are_listed_in_alphabetic_order()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create(['slug' => 'example-modpack']);
        BuildFactory::createForModpack($modpack, ['version' => '1.2.3']);

        $packageC = factory(Package::class)->create(['name' => 'Package C']);
        $packageB = factory(Package::class)->create(['name' => 'Package B']);
        $packageA = factory(Package::class)->create(['name' => 'Package A']);

        $response = $this->actingAs($user)->get('/modpacks/example-modpack/1.2.3');

        $response->data('packages')->assertEquals([
            $packageA,
            $packageB,
            $packageC,
        ]);
    }

    /** @test */
    public function modpack_includes_builds_in_reverse_order()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create([
            'slug' => 'example-modpack',
        ]);
        $buildA = BuildFactory::createForModpack($modpack, ['version' => '1.0.0a']);
        $buildB = BuildFactory::createForModpack($modpack, ['version' => '1.0.0b']);
        $buildC = BuildFactory::createForModpack($modpack, ['version' => '10.5']);

        $response = $this->actingAs($user)->get('/modpacks/example-modpack/1.0.0a');

        $response->data('modpack')->builds->assertEquals([
            $buildC,
            $buildB,
            $buildA,
        ]);
    }
}
