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
use App\Package;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdatePackageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_update_a_package()
    {
        $user = factory(User::class)->states('admin')->create();
        $package = factory(Package::class)->create([
            'name' => 'Original Name',
            'slug' => 'original-slug',
            'author' => 'Original Author',
            'website_url' => 'http://original-web.com',
            'donation_url' => 'http://original-donate.com',
            'description' => 'Original description.',
        ]);

        $response = $this->actingAs($user)
            ->patch('/library/original-slug', [
                'name' => 'New Name',
                'slug' => 'new-slug',
                'author' => 'New Author',
                'website_url' => 'http://new-web.com',
                'donation_url' => 'http://new-donate.com',
                'description' => 'New description.',
            ]);

        tap($package->fresh(), function ($package) use ($response) {
            $response->assertRedirect('/library/new-slug');

            $this->assertEquals('New Name', $package->name);
            $this->assertEquals('new-slug', $package->slug);
            $this->assertEquals('New Author', $package->author);
            $this->assertEquals('http://new-web.com', $package->website_url);
            $this->assertEquals('http://new-donate.com', $package->donation_url);
            $this->assertEquals('New description.', $package->description);
        });
    }

    /** @test */
    public function an_authorized_user_can_update_a_modpack()
    {
        $user = factory(User::class)->create();
        $user->grantRole('update-package');
        $package = factory(Package::class)->create($this->originalParams());

        $response = $this->actingAs($user)
            ->patch("library/{$package->slug}", $this->validParams());

        $response->assertRedirect("library/{$package->fresh()->slug}");
        $this->assertArraySubset($this->validParams(), $package->fresh()->getAttributes());
    }

    /** @test */
    public function an_unauthorized_user_cannot_update_a_modpack()
    {
        $user = factory(User::class)->create();
        $package = factory(Package::class)->create($this->originalParams());

        $response = $this->actingAs($user)
            ->patch("library/{$package->slug}", $this->validParams());

        $response->assertStatus(403);
        $this->assertArraySubset($this->originalParams(), $package->fresh()->getAttributes());
    }

    /** @test */
    public function a_guest_is_asked_to_login_before_updating_package()
    {
        $user = factory(User::class)->create();
        $package = factory(Package::class)->create($this->originalParams());

        $response = $this->patch('/library/original-slug', $this->validParams());

        $response->assertRedirect('login');
        $this->assertArraySubset($this->originalParams(), $package->fresh()->getAttributes());
    }

    /** @test */
    public function attempting_to_update_a_package_with_invalid_slug_returns_404()
    {
        $user = factory(User::class)->states('admin')->create();
        $package = factory(Package::class)->create($this->originalParams());

        $response = $this->actingAs($user)->patch('/library/invalid-slug', $this->validParams());

        $response->assertStatus(404);
        $this->assertArraySubset($this->originalParams(), $package->fresh()->getAttributes());
    }

    /** @test */
    public function name_is_required()
    {
        $user = factory(User::class)->states('admin')->create();
        $package = factory(Package::class)->create($this->originalParams());

        $response = $this->actingAs($user)
            ->from("library/{$package->slug}")
            ->patch("library/{$package->slug}", $this->validParams([
                'name' => '',
            ]));

        $response->assertRedirect("library/{$package->slug}");
        $response->assertSessionHasErrors('name');
        $this->assertArraySubset($this->originalParams(), $package->fresh()->getAttributes());
    }

    /** @test */
    public function slug_is_required()
    {
        $user = factory(User::class)->states('admin')->create();
        $package = factory(Package::class)->create($this->originalParams());

        $response = $this->actingAs($user)
            ->from("library/{$package->slug}")
            ->patch("library/{$package->slug}", $this->validParams([
                'slug' => '',
            ]));

        $response->assertRedirect("library/{$package->slug}");
        $response->assertSessionHasErrors('slug');
        $this->assertArraySubset($this->originalParams(), $package->fresh()->getAttributes());
    }

    /** @test */
    public function slug_is_unique()
    {
        $user = factory(User::class)->states('admin')->create();
        factory(Package::class)->create(['slug' => 'existing-slug']);
        $package = factory(Package::class)->create($this->originalParams());

        $response = $this->actingAs($user)
            ->from("library/{$package->slug}")
            ->patch("library/{$package->slug}", $this->validParams([
                'slug' => 'existing-slug',
            ]));

        $response->assertRedirect("library/{$package->slug}");
        $response->assertSessionHasErrors('slug');
        $this->assertArraySubset($this->originalParams(), $package->fresh()->getAttributes());
    }

    /** @test */
    public function slug_is_alpha_dash()
    {
        $user = factory(User::class)->states('admin')->create();
        $package = factory(Package::class)->create($this->originalParams());

        $response = $this->actingAs($user)
            ->from("library/{$package->slug}")
            ->patch("library/{$package->slug}", $this->validParams([
                'slug' => 'spaces & symbols',
            ]));

        $response->assertRedirect("library/{$package->slug}");
        $response->assertSessionHasErrors('slug');
        $this->assertArraySubset($this->originalParams(), $package->fresh()->getAttributes());
    }

    /** @test */
    public function original_slug_can_be_resubmitted()
    {
        $user = factory(User::class)->states('admin')->create();
        $package = factory(Package::class)->create($this->originalParams([
            'slug' => 'original-slug',
        ]));

        $response = $this->actingAs($user)
            ->from("library/{$package->slug}")
            ->patch("library/{$package->slug}", $this->validParams([
                'slug' => 'original-slug',
            ]));

        $response->assertRedirect("library/{$package->slug}");
        $response->assertSessionMissing('errors');
        $this->assertArraySubset($this->validParams([
            'slug' => 'original-slug',
        ]), $package->fresh()->getAttributes());
    }

    /** @test */
    public function all_parameters_are_optional()
    {
        $user = factory(User::class)->states('admin')->create();
        $package = factory(Package::class)->create($this->originalParams());

        $response = $this->actingAs($user)
            ->from("library/{$package->slug}")
            ->patch("library/{$package->slug}", [
                // empty set
            ]);

        $response->assertRedirect("library/{$package->slug}");
        $response->assertSessionMissing('errors');
        $this->assertArraySubset($this->originalParams(), $package->fresh()->getAttributes());
    }

    /**
     * @return array
     */
    private function originalParams($overrides = [])
    {
        return array_merge([
            'name' => 'Original Name',
            'slug' => 'original-slug',
            'author' => 'Original Author',
            'website_url' => 'http://original-web.com',
            'donation_url' => 'http://original-donate.com',
            'description' => 'Original description.',
        ], $overrides);
    }

    /**
     * @return array
     */
    private function validParams($overrides = []): array
    {
        return array_merge([
            'name' => 'New Name',
            'slug' => 'new-slug',
            'author' => 'New Author',
            'website_url' => 'http://new-web.com',
            'donation_url' => 'http://new-donate.com',
            'description' => 'New description.',
        ], $overrides);
    }
}
