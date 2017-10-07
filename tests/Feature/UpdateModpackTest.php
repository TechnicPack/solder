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
use App\Modpack;
use Tests\TestCase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateModpackTest extends TestCase
{
    use RefreshDatabase;

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Iron Tanks',
            'slug' => 'iron-tanks',
            'is_published' => true,
        ], $overrides);
    }

    private function oldAttributes($overrides = [])
    {
        return array_merge([
            'name' => 'Brothers Klaus',
            'slug' => 'brothers-klaus',
            'is_published' => true,
            'icon_path' => null,
        ], $overrides);
    }

    /** @test */
    public function a_user_can_update_a_modpack()
    {
        $this->withoutExceptionHandling();
        Storage::fake();
        $file = File::image('modpack-icon.png', 50, 50);
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create([
            'name' => 'Brothers Klaus',
            'slug' => 'brothers-klaus',
            'is_published' => true,
            'icon_path' => null,
        ]);

        $response = $this->actingAs($user)->patch('/modpacks/brothers-klaus', [
            'name' => 'Iron Tanks',
            'slug' => 'iron-tanks',
            'is_published' => false,
            'modpack_icon' => $file,
        ]);

        tap($modpack->fresh(), function ($modpack) use ($file, $response) {
            $response->assertRedirect('/modpacks/iron-tanks');

            $this->assertEquals('Iron Tanks', $modpack->name);
            $this->assertEquals('iron-tanks', $modpack->slug);
            $this->assertFalse($modpack->is_published);
            $this->assertNotNull($modpack->icon_path);

            Storage::assertExists($modpack->icon_path);
            $this->assertFileEquals($file->getPathname(), Storage::path($modpack->icon_path)
            );
        });
    }

    /** @test */
    public function a_guest_cannot_update_a_modpack()
    {
        $modpack = factory(Modpack::class)->create($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ]));

        $response = $this->patch('/modpacks/brothers-klaus', $this->validParams());

        $response->assertRedirect('/login');
        $this->assertArraySubset($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ]), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function name_cannot_be_blank()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ]));

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->patch('/modpacks/brothers-klaus', [
            'name' => '',
        ]);

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('name');
        $this->assertArraySubset($this->oldAttributes($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ])), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function slug_cannot_be_blank()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ]));

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->patch('/modpacks/brothers-klaus', [
            'slug' => '',
        ]);

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('slug');
        $this->assertArraySubset($this->oldAttributes($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ])), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function slug_must_be_unique()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ]));
        $otherModpack = factory(Modpack::class)->create([
            'slug' => 'big-dig',
        ]);

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->patch('/modpacks/brothers-klaus', [
            'slug' => 'big-dig',
        ]);

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('slug');
        $this->assertArraySubset($this->oldAttributes($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ])), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function slug_must_be_alpha_dash()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ]));

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->patch('/modpacks/brothers-klaus', [
            'slug' => 'invalid slug',
        ]);

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('slug');
        $this->assertArraySubset($this->oldAttributes($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ])), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function all_parameters_are_optional()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ]));

        $response = $this->actingAs($user)->patch('/modpacks/brothers-klaus', [
            // empty set
        ]);

        $response->assertRedirect('/modpacks/brothers-klaus');
        $this->assertArraySubset($this->oldAttributes(), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function original_slug_can_be_resubmitted()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ]));

        $response = $this->actingAs($user)->patch('/modpacks/brothers-klaus', [
            'slug' => 'brothers-klaus',
        ]);

        $response->assertRedirect('/modpacks/brothers-klaus');
        $this->assertArraySubset($this->oldAttributes($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ])), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function is_published_cannot_be_blank()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ]));

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->patch('/modpacks/brothers-klaus', [
            'is_published' => '',
        ]);

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('is_published');
        $this->assertArraySubset($this->oldAttributes($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ])), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function is_published_is_boolean()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ]));

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->patch('/modpacks/brothers-klaus', [
            'is_published' => 'invalid',
        ]);

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('is_published');
        $this->assertArraySubset($this->oldAttributes($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ])), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function modpack_icon_must_be_an_image()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ]));

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->patch('/modpacks/brothers-klaus', [
            'modpack_icon' => File::create('not-an-icon.pdf'),
        ]);

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('modpack_icon');
        $this->assertArraySubset($this->oldAttributes($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ])), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function modpack_icon_must_50px_wide()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ]));

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->patch('/modpacks/brothers-klaus', [
            'modpack_icon' => File::image('modpack_icon.png', 49, 49),
        ]);

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('modpack_icon');
        $this->assertArraySubset($this->oldAttributes($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ])), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function modpack_icon_must_have_square_aspect_ratio()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ]));

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->patch('/modpacks/brothers-klaus', [
            'modpack_icon' => File::image('poster.png', 100, 101),
        ]);

        $response->assertRedirect('/modpacks/brothers-klaus');
        $response->assertSessionHasErrors('modpack_icon');
        $this->assertArraySubset($this->oldAttributes($this->oldAttributes([
            'slug' => 'brothers-klaus',
        ])), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function modpack_icon_is_optional()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create($this->oldAttributes([
            'slug' => 'brothers-klaus',
            'icon_path' => '/icons/old_icon.png',
        ]));

        $response = $this->actingAs($user)->from('/modpacks/brothers-klaus')->patch('/modpacks/brothers-klaus', [
            'modpack_icon' => '',
        ]);

        tap(Modpack::first(), function ($modpack) use ($response) {
            $response->assertRedirect('/modpacks/brothers-klaus');
            $response->assertSessionMissing('errors');

            $this->assertEquals('/icons/old_icon.png', $modpack->icon_path);
        });
    }
}
