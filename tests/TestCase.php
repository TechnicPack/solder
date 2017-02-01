<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseSetup;

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->setupDatabase();
    }
}
