<?php

namespace Tests;

use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Seed roles and permissions before each Feature test.
     * Unit tests do not call this because they do not use RefreshDatabase.
     */
    protected function setUp(): void
    {
        parent::setUp();

        if ($this->app !== null && $this->app->runningUnitTests()) {
            // Only seed when a database is available (Feature tests with RefreshDatabase)
            if (in_array(RefreshDatabase::class, class_uses_recursive(static::class), true)) {
                $this->seed(RolesAndPermissionsSeeder::class);
            }
        }
    }
}
