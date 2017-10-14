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
use App\Package;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddPackageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_create_a_package()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)->post('library', [
            'name' => 'Buildcraft',
            'slug' => 'buildcraft',
            'author' => 'SpaceToad',
            'website_url' => 'http://mod-buildcraft.com',
            'donation_url' => 'http://mod-buildcraft.com/donate',
            'description' => 'Adding Pipes to Minecraft since 1891.',
        ]);

        tap(Package::first(), function ($package) use ($response) {
            $this->assertEquals('Buildcraft', $package->name);
            $this->assertEquals('buildcraft', $package->slug);
            $this->assertEquals('SpaceToad', $package->author);
            $this->assertEquals('http://mod-buildcraft.com', $package->website_url);
            $this->assertEquals('http://mod-buildcraft.com/donate', $package->donation_url);
            $this->assertEquals('Adding Pipes to Minecraft since 1891.', $package->description);

            $response->assertRedirect('library/buildcraft');
        });
    }

    /** @test */
    public function a_guest_cannot_create_a_build()
    {
        $response = $this->post('library', $this->validParams());

        $response->assertRedirect('login');
        $this->assertCount(0, Package::all());
    }

    /** @test */
    public function an_authorized_user_can_create_a_package()
    {
        $user = factory(User::class)->create();
        $user->grantRole('create-package');

        $response = $this->actingAs($user)
            ->post('library', $this->validParams());

        $response->assertRedirect();
        $this->assertCount(1, Package::all());
    }

    /** @test */
    public function an_unauthorized_user_cannot_create_a_package()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->post('library', $this->validParams());

        $response->assertStatus(403);
        $this->assertCount(0, Package::all());
    }

    /** @test */
    public function name_is_required()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)
            ->from('library')
            ->post('library', $this->validParams([
                'name' => '',
            ]));

        $response->assertRedirect('library');
        $response->assertSessionHasErrors('name');
        $this->assertCount(0, Package::all());
    }

    /** @test */
    public function slug_is_required()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)
            ->from('library')
            ->post('library', $this->validParams([
                'slug' => '',
            ]));

        $response->assertRedirect('library');
        $response->assertSessionHasErrors('slug');
        $this->assertCount(0, Package::all());
    }

    /** @test */
    public function slug_is_unique()
    {
        $user = factory(User::class)->states('admin')->create();
        factory(Package::class)->create(['slug' => 'buildcraft']);

        $response = $this->actingAs($user)
            ->from('library')
            ->post('library', $this->validParams([
                'slug' => 'buildcraft',
            ]));

        $response->assertRedirect('library');
        $response->assertSessionHasErrors('slug');
        $this->assertCount(1, Package::all());
    }

    /** @test */
    public function author_is_optional()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)
            ->from('library')
            ->post('library', $this->validParams([
                'author' => '',
            ]));

        $response->assertRedirect();
        $response->assertSessionMissing('errors');
        $this->assertCount(1, Package::all());
    }

    /** @test */
    public function website_url_is_a_valid_url()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)
            ->from('library')
            ->post('library', $this->validParams([
                'website_url' => 'not-a-url',
            ]));

        $response->assertRedirect('library');
        $response->assertSessionHasErrors('website_url');
        $this->assertCount(0, Package::all());
    }

    /** @test */
    public function donation_url_is_a_valid_url()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)
            ->from('library')
            ->post('library', $this->validParams([
                'donation_url' => 'not-a-url',
            ]));

        $response->assertRedirect('library');
        $response->assertSessionHasErrors('donation_url');
        $this->assertCount(0, Package::all());
    }

    /** @test */
    public function website_url_is_optional()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)
            ->from('library')
            ->post('library', $this->validParams([
                'website_url' => '',
            ]));

        $response->assertRedirect();
        $response->assertSessionMissing('errors');
        $this->assertCount(1, Package::all());
    }

    /** @test */
    public function donation_url_is_optional()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)
            ->from('library')
            ->post('library', $this->validParams([
                'donation_url' => '',
            ]));

        $response->assertRedirect();
        $response->assertSessionMissing('errors');
        $this->assertCount(1, Package::all());
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Buildcraft',
            'slug' => 'buildcraft',
            'author' => 'SpaceToad',
            'website_url' => 'http://mod-buildcraft.com',
            'donation_url' => 'http://mod-buildcraft.com/donate',
            'description' => 'Adding Pipes to Minecraft since 1891.',
        ], $overrides);
    }
}
