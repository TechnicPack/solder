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

    /** @test */
    public function users_can_view_the_add_release_form()
    {
        $user = factory(User::class)->create();
        factory(Package::class, 5)->create();

        $response = $this->actingAs($user)->get('/releases/new');

        $response->assertStatus(200);
        $response->assertViewHas('packages');
    }

    /** @test */
    public function guests_cannot_view_the_add_release_form()
    {
        $response = $this->get('/releases/new');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function adding_a_valid_release()
    {
        $user = factory(User::class)->create();
        $package = factory(Package::class)->create([
            'slug' => 'example-package',
        ]);
        Storage::fake();
        FileHash::shouldReceive('hash')->andReturn('generated-hash');

        $response = $this->actingAs($user)->post('/releases', [
            'package_id' => $package->id,
            'version' => '1.2.3',
            'archive' => UploadedFile::fake()->create('fake-file.zip'),
        ]);

        tap(Release::first(), function ($release) use ($package, $response) {
            $response->assertRedirect('/dashboard');

            $this->assertEquals($package->id, $release->package_id);
            $this->assertEquals('1.2.3', $release->version);
            $this->assertEquals('generated-hash', $release->md5);
            $this->assertEquals('example-package/example-package-1.2.3.zip', $release->path);
            Storage::disk()->assertExists('example-package/example-package-1.2.3.zip');
        });
    }

    /** @test */
    public function guests_cannot_post_a_new_release()
    {
        $package = factory(Package::class)->create();
        Storage::fake();

        $response = $this->post('/releases', [
            'package_id' => $package->id,
            'version' => '1.2.3',
            'archive' => UploadedFile::fake()->create('fake-file.zip'),
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertCount(0, Release::all());
    }
}
