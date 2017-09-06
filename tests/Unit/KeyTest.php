<?php

namespace Tests\Unit;

use App\Key;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function tokens_can_be_validated()
    {
        factory(Key::class)->create([
            'token' => 'APIKEY1234',
            'name' => 'Test Key',
        ]);

        $this->assertTrue(Key::isValid('APIKEY1234'));
        $this->assertFalse(Key::isValid('INVALIDKEY'));
    }
}
