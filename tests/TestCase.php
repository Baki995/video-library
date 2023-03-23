<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();

        // Set up the test database and seed it with test data
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');
    }

}
