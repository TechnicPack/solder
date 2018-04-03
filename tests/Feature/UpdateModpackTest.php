<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Feature;

use App\User;
use App\Build;
use App\Modpack;
use Tests\TestCase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateModpackTest extends TestCase
{
    use RefreshDatabase;

    protected $validFile;

    protected function setUp()
    {
        parent::setUp();
        Storage::fake();

        $this->validFile = File::image('modpack-icon.png', 50, 50);
    }

    /** @test */
    public function an_admin_can_update_a_modpack()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create([
            'name' => 'Brothers Klaus',
            'slug' => 'brothers-klaus',
            'status' => 'private',
            'icon_path' => null,
            'recommended_build_id' => null,
            'latest_build_id' => null,
        ]);
        $build = $modpack->builds()->save(factory(Build::class)->make());

        $response = $this->actingAs($user)->patch("/modpacks/{$modpack->slug}", [
            'name' => 'Iron Tanks',
            'slug' => 'iron-tanks',
            'status' => 'public',
            'modpack_icon' => $this->validFile,
            'recommended_build_id' => $build->id,
            'latest_build_id' => $build->id,
        ]);

        tap($modpack->fresh(), function ($modpack) use ($response, $build) {
            $response->assertRedirect('/modpacks/iron-tanks');

            $this->assertEquals('Iron Tanks', $modpack->name);
            $this->assertEquals('iron-tanks', $modpack->slug);
            $this->assertEquals('public', $modpack->status);
            $this->assertEquals($build->id, $modpack->recommended_build_id);
            $this->assertEquals($build->id, $modpack->latest_build_id);
            $this->assertNotNull($modpack->icon_path);

            Storage::assertExists($modpack->icon_path);
            $this->assertFileEquals($this->validFile->getPathname(), Storage::path($modpack->icon_path)
            );
        });
    }

    /** @test */
    public function an_authorized_user_who_is_a_collaborator_can_update_a_modpack()
    {
        $user = factory(User::class)->create();
        $user->grantRole('update-modpack');
        $modpack = factory(Modpack::class)->create($this->originalParams());
        $modpack->addCollaborator($user->id);

        $response = $this->actingAs($user)
            ->patch("/modpacks/{$modpack->slug}", $this->validParams());

        $response->assertRedirect("/modpacks/{$modpack->fresh()->slug}");
        $this->assertArraySubset($this->validParams(), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function an_authorized_user_who_is_not_a_collaborator_cannot_update_a_modpack()
    {
        $user = factory(User::class)->create();
        $user->grantRole('update-modpack');

        $modpack = factory(Modpack::class)->create($this->originalParams());

        $response = $this->actingAs($user)
            ->patch("/modpacks/{$modpack->slug}", $this->validParams());

        $response->assertStatus(403);
        $this->assertArraySubset($this->originalParams(), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function an_unauthorized_user_cannot_update_a_modpack()
    {
        $user = factory(User::class)->create();
        $modpack = factory(Modpack::class)->create($this->originalParams());

        $response = $this->actingAs($user)
            ->patch("/modpacks/{$modpack->slug}", $this->validParams());

        $response->assertStatus(403);
        $this->assertArraySubset($this->originalParams(), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function a_guest_cannot_update_a_modpack()
    {
        $modpack = factory(Modpack::class)->create($this->originalParams());

        $response = $this->patch("/modpacks/{$modpack->slug}", $this->validParams());

        $response->assertRedirect('/login');
        $this->assertArraySubset($this->originalParams(), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function name_cannot_be_blank()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create($this->originalParams([
            'name' => 'Original Name',
        ]));

        $response = $this->actingAs($user)
            ->from("/modpacks/{$modpack->slug}")
            ->patch("/modpacks/{$modpack->slug}", $this->validParams([
                'name' => '',
            ]));

        $response->assertRedirect("/modpacks/{$modpack->slug}");
        $response->assertSessionHasErrors('name');
        $this->assertArraySubset($this->originalParams([
            'name' => 'Original Name',
        ]), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function slug_cannot_be_blank()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create($this->originalParams([
            'slug' => 'original-slug',
        ]));

        $response = $this->actingAs($user)
            ->from("/modpacks/{$modpack->slug}")
            ->patch("/modpacks/{$modpack->slug}", $this->validParams([
                'slug' => '',
            ]));

        $response->assertRedirect("/modpacks/{$modpack->slug}");
        $response->assertSessionHasErrors('slug');
        $this->assertArraySubset($this->originalParams([
            'slug' => 'original-slug',
        ]), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function slug_must_be_unique()
    {
        $user = factory(User::class)->states('admin')->create();
        $otherModpack = factory(Modpack::class)->create(['slug' => 'other-modpack']);
        $modpack = factory(Modpack::class)->create($this->originalParams([
            'slug' => 'original-slug',
        ]));

        $response = $this->actingAs($user)
            ->from("/modpacks/{$modpack->slug}")
            ->patch("/modpacks/{$modpack->slug}", $this->validParams([
                'slug' => 'other-modpack',
            ]));

        $response->assertRedirect("/modpacks/{$modpack->slug}");
        $response->assertSessionHasErrors('slug');
        $this->assertArraySubset($this->originalParams([
            'slug' => 'original-slug',
        ]), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function slug_must_be_alpha_dash()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create($this->originalParams([
            'slug' => 'original-slug',
        ]));

        $response = $this->actingAs($user)
            ->from("/modpacks/{$modpack->slug}")
            ->patch("/modpacks/{$modpack->slug}", $this->validParams([
                'slug' => 'spaces & symbols',
            ]));

        $response->assertRedirect("/modpacks/{$modpack->slug}");
        $response->assertSessionHasErrors('slug');
        $this->assertArraySubset($this->originalParams([
            'slug' => 'original-slug',
        ]), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function all_attributes_are_optional()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create($this->originalParams(['slug' => 'brothers-klaus']));

        $response = $this->actingAs($user)
            ->patch("/modpacks/{$modpack->slug}", [
                // empty set
            ]);

        $response->assertSessionMissing('errors');
        $response->assertRedirect("/modpacks/{$modpack->slug}");
        $this->assertArraySubset($this->originalParams(['slug' => 'brothers-klaus']), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function original_slug_can_be_resubmitted()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create($this->originalParams([
            'slug' => 'original-slug',
        ]));

        $response = $this->actingAs($user)
            ->from("/modpacks/{$modpack->slug}")
            ->patch("/modpacks/{$modpack->slug}", $this->validParams([
                'slug' => 'original-slug',
            ]));

        $response->assertRedirect('/modpacks/original-slug');
        $response->assertSessionMissing('errors');
        $this->assertArraySubset($this->validParams([
            'slug' => 'original-slug',
        ]), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function status_cannot_be_blank()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create($this->originalParams([
            'status' => 'public',
        ]));

        $response = $this->actingAs($user)
            ->from("/modpacks/{$modpack->slug}")
            ->patch("/modpacks/{$modpack->slug}", $this->validParams([
                'status' => '',
            ]));

        $response->assertRedirect("/modpacks/{$modpack->slug}");
        $response->assertSessionHasErrors('status');
        $this->assertArraySubset($this->originalParams([
            'status' => 'public',
        ]), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function status_is_valid()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create($this->originalParams([
            'slug' => 'brothers-klaus',
        ]));

        $response = $this->actingAs($user)->from("/modpacks/{$modpack->slug}")->patch("/modpacks/{$modpack->slug}", [
            'status' => 'invalid',
        ]);

        $response->assertRedirect("/modpacks/{$modpack->slug}");
        $response->assertSessionHasErrors('status');
        $this->assertArraySubset($this->originalParams($this->originalParams([
            'slug' => 'brothers-klaus',
        ])), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function modpack_icon_must_be_an_image()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create($this->originalParams([
            'slug' => 'brothers-klaus',
        ]));

        $response = $this->actingAs($user)->from("/modpacks/{$modpack->slug}")->patch("/modpacks/{$modpack->slug}", [
            'modpack_icon' => File::create('not-an-icon.pdf'),
        ]);

        $response->assertRedirect("/modpacks/{$modpack->slug}");
        $response->assertSessionHasErrors('modpack_icon');
        $this->assertArraySubset($this->originalParams($this->originalParams([
            'slug' => 'brothers-klaus',
        ])), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function modpack_icon_must_50px_wide()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create($this->originalParams([
            'slug' => 'brothers-klaus',
        ]));

        $response = $this->actingAs($user)->from("/modpacks/{$modpack->slug}")->patch("/modpacks/{$modpack->slug}", [
            'modpack_icon' => File::image('modpack_icon.png', 49, 49),
        ]);

        $response->assertRedirect("/modpacks/{$modpack->slug}");
        $response->assertSessionHasErrors('modpack_icon');
        $this->assertArraySubset($this->originalParams($this->originalParams([
            'slug' => 'brothers-klaus',
        ])), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function modpack_icon_must_have_square_aspect_ratio()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create($this->originalParams([
            'slug' => 'brothers-klaus',
        ]));

        $response = $this->actingAs($user)->from("/modpacks/{$modpack->slug}")->patch("/modpacks/{$modpack->slug}", [
            'modpack_icon' => File::image('poster.png', 100, 101),
        ]);

        $response->assertRedirect("/modpacks/{$modpack->slug}");
        $response->assertSessionHasErrors('modpack_icon');
        $this->assertArraySubset($this->originalParams($this->originalParams([
            'slug' => 'brothers-klaus',
        ])), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function modpack_icon_is_optional()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create($this->originalParams([
            'slug' => 'brothers-klaus',
            'icon_path' => '/icons/old_icon.png',
        ]));

        $response = $this->actingAs($user)->from("/modpacks/{$modpack->slug}")->patch("/modpacks/{$modpack->slug}", [
            'modpack_icon' => '',
        ]);

        tap(Modpack::first(), function ($modpack) use ($response) {
            $response->assertRedirect("/modpacks/{$modpack->slug}");
            $response->assertSessionMissing('errors');

            $this->assertEquals('/icons/old_icon.png', $modpack->icon_path);
        });
    }

    /** @test */
    public function latest_build_id_must_be_a_related_build()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create($this->originalParams(['slug' => 'brothers-klaus']));

        $response = $this->actingAs($user)
            ->from("/modpacks/{$modpack->slug}")
            ->patch("/modpacks/{$modpack->slug}", $this->validParams([
                'latest_build_id' => '99',
            ]));

        $response->assertRedirect("/modpacks/{$modpack->slug}");
        $response->assertSessionHasErrors('latest_build_id');
        $this->assertArraySubset($this->originalParams(), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function recommended_build_id_must_be_a_related_build()
    {
        $user = factory(User::class)->states('admin')->create();
        $modpack = factory(Modpack::class)->create($this->originalParams(['slug' => 'brothers-klaus']));

        $response = $this->actingAs($user)
            ->from("/modpacks/{$modpack->slug}")
            ->patch("/modpacks/{$modpack->slug}", $this->validParams([
                'recommended_build_id' => '99',
            ]));

        $response->assertRedirect("/modpacks/{$modpack->slug}");
        $response->assertSessionHasErrors('recommended_build_id');
        $this->assertArraySubset($this->originalParams(), $modpack->fresh()->getAttributes());
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Iron Tanks',
            'slug' => 'iron-tanks',
            'status' => 'public',
        ], $overrides);
    }

    private function originalParams($overrides = [])
    {
        return array_merge([
            'name' => 'Brothers Klaus',
            'slug' => 'brothers-klaus',
            'status' => 'private',
            'icon_path' => null,
            'recommended_build_id' => null,
            'latest_build_id' => null,
        ], $overrides);
    }
}
