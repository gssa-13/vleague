<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;

it('creates the ls admin user as a super admin', function () {
    $this->seed(DatabaseSeeder::class);

    $admin = User::where('email', 'admin@ls.mx')->first();

    expect($admin)->not->toBeNull()
        ->and($admin->hasRole('super-admin'))->toBeTrue();
});

it('does not duplicate the ls admin user when seeded more than once', function () {
    $this->seed(DatabaseSeeder::class);
    $this->seed(DatabaseSeeder::class);

    expect(User::where('email', 'admin@ls.mx')->count())->toBe(1);
});
