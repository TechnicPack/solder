<?php

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    protected $migrate = true;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Setup testing environment.
     */
    public function setUp()
    {
        parent::setUp();
        if ($this->migrate) {
            Artisan::call('migrate');
            $this->migrate = false;
        }
    }
}
