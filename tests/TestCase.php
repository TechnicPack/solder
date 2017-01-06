<?php

/*
 * This file is part of TechnicSolder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    use DatabaseSetup;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->setupDatabase();
    }

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
     * Replace application exception handler for debugging.
     */
    protected function disableExceptionHandling()
    {
        $this->app->instance(\Illuminate\Contracts\Debug\ExceptionHandler::class,
            new class extends \App\Exceptions\Handler {
                public function __construct()
                {
                }

                public function report(Exception $e)
                {
                }

                public function render($request, Exception $e)
                {
                    throw $e;
                }
            });
    }

}
