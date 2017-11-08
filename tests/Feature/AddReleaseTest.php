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
use App\Release;
use Tests\TestCase;
use App\Facades\FileHash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddReleaseTest extends TestCase
{
    use RefreshDatabase;

    protected $validFile;

    protected function setUp()
    {
        parent::setUp();
        Storage::fake();
        FileHash::shouldReceive('hash')->andReturn('generated-hash');

        $this->validFile = UploadedFile::fake()->create('fake-file.zip', 2000);
    }

    /** @test */
    public function an_admin_can_create_a_release()
    {
        $user = factory(User::class)->states('admin')->create();
        $package = factory(Package::class)->create(['slug' => 'iron-tanks']);

        $response = $this->actingAs($user)->post('library/iron-tanks/releases', [
            'version' => '1.2.3',
            'archive' => $this->validFile,
        ]);

        tap(Release::first(), function ($release) use ($package, $response) {
            $response->assertRedirect('library/iron-tanks');

            $this->assertEquals($package->id, $release->package_id);
            $this->assertEquals('1.2.3', $release->version);
            $this->assertEquals('generated-hash', $release->md5);
            $this->assertEquals('iron-tanks/iron-tanks-1.2.3.zip', $release->path);
            $this->assertEquals(2048000, $release->filesize);

            Storage::assertExists('iron-tanks/iron-tanks-1.2.3.zip');
        });
    }

    /** @test */
    public function guests_cannot_post_a_new_release()
    {
        $package = factory(Package::class)->create();

        $response = $this->post('library/'.$package->slug.'/releases', $this->validParams());

        $response->assertRedirect('login');
        $this->assertCount(0, Release::all());
    }

    /** @test */
    public function an_authorized_user_can_create_a_release()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $user->grantRole('update-package');
        $package = factory(Package::class)->create();

        $response = $this->actingAs($user)
            ->post("library/{$package->slug}/releases", $this->validParams());

        $response->assertRedirect("library/{$package->slug}");
        $this->assertCount(1, Release::all());
    }

    /** @test */
    public function an_unauthorized_user_cannot_create_a_build()
    {
        $user = factory(User::class)->create();
        $package = factory(Package::class)->create();

        $response = $this->actingAs($user)
            ->post("library/{$package->slug}/releases", $this->validParams());

        $response->assertStatus(403);
        $this->assertCount(0, Release::all());
    }

    /** @test */
    public function version_is_required()
    {
        $user = factory(User::class)->states('admin')->create();
        $package = factory(Package::class)->create();

        $response = $this->actingAs($user)
            ->from("library/{$package->slug}")
            ->post("library/{$package->slug}/releases", $this->validParams([
            'version' => '',
        ]));

        $response->assertRedirect("library/{$package->slug}");
        $response->assertSessionHasErrors('version');
        $this->assertEquals(0, Release::count());
    }

    /** @test */
    public function version_must_be_unique_for_the_modpack()
    {
        $user = factory(User::class)->states('admin')->create();
        $package = factory(Package::class)->create();
        $existingRelease = factory(Release::class)->create([
           'package_id' => $package->id,
            'version' => '1.2.3',
        ]);

        $response = $this->actingAs($user)
            ->from("library/{$package->slug}")
            ->post("library/{$package->slug}/releases", $this->validParams([
                'version' => '1.2.3',
            ]));

        $response->assertRedirect("library/{$package->slug}");
        $response->assertSessionHasErrors('version');
        $this->assertEquals(1, Release::count());
    }

    /** @test */
    public function version_may_not_contain_spaces()
    {
        $user = factory(User::class)->states('admin')->create();
        $package = factory(Package::class)->create();

        $response = $this->actingAs($user)
            ->from("library/{$package->slug}")
            ->post("library/{$package->slug}/releases", $this->validParams([
                'version' => 'invalid version',
            ]));

        $response->assertRedirect("library/{$package->slug}");
        $response->assertSessionHasErrors('version');
        $this->assertEquals(0, Release::count());
    }

    /** @test */
    public function version_may_not_contain_symbols()
    {
        $user = factory(User::class)->states('admin')->create();
        $package = factory(Package::class)->create();

        $response = $this->actingAs($user)
            ->from("library/{$package->slug}")
            ->post("library/{$package->slug}/releases", $this->validParams([
                'version' => '!@#$%^&*()',
            ]));

        $response->assertRedirect("library/{$package->slug}");
        $response->assertSessionHasErrors('version');
        $this->assertEquals(0, Release::count());
    }

    /** @test */
    public function version_cannot_start_with_a_dot()
    {
        $user = factory(User::class)->states('admin')->create();
        $package = factory(Package::class)->create();

        $response = $this->actingAs($user)
            ->from("library/{$package->slug}")
            ->post("library/{$package->slug}/releases", $this->validParams([
                'version' => '.8.0',
            ]));

        $response->assertRedirect("library/{$package->slug}");
        $response->assertSessionHasErrors('version');
        $this->assertEquals(0, Release::count());
    }

    /** @test */
    public function archive_must_be_zip_file()
    {
        $user = factory(User::class)->states('admin')->create();
        $package = factory(Package::class)->create();

        $response = $this->actingAs($user)
            ->from("library/{$package->slug}")
            ->post("library/{$package->slug}/releases", $this->validParams([
                'archive' => UploadedFile::fake()->create('invalid-file.jar', 2000),
            ]));

        $response->assertRedirect("library/{$package->slug}");
        $response->assertSessionHasErrors('archive');
        $this->assertEquals(0, Release::count());
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'version' => '1.2.3',
            'archive' => $this->validFile,
        ], $overrides);
    }
}
