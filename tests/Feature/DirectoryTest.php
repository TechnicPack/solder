<?php

namespace Tests\Feature;

use App\Modpack;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DirectoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function directory_of_modpacks_are_loaded_with_directory_view()
    {
        $modpackC = factory(Modpack::class)->create(['name' => 'Gamma']);
        $modpackB = factory(Modpack::class)->create(['name' => 'Beta']);
        $modpackA = factory(Modpack::class)->create(['name' => 'Alpha']);

        Route::view('/test-directory', 'partials.directory');

        $response = $this->get('/test-directory');

        $response->data('directory')->assertEquals([
            $modpackA,
            $modpackB,
            $modpackC
        ]);
    }
}
