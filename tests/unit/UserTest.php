<?php

namespace Tests\Unit;
use App\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function has_many_legacy_tokens()
    {
        $user = factory(User::class)->create();


    }
}
