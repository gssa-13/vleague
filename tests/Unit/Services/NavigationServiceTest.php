<?php

// tests/Unit/Services/NavigationServiceTest.php

use App\Models\NavigationItem;
use App\Models\User;
use App\Repositories\NavigationRepository;
use App\Services\NavigationService;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->repository = Mockery::mock(NavigationRepository::class);
    $this->service = new NavigationService($this->repository);
});

it('returns all active items when user has no permission filter', function () {
    $items = new Collection([
        new NavigationItem(['label' => 'Dashboard', 'permission_name' => null]),
        new NavigationItem(['label' => 'Venues', 'permission_name' => null]),
    ]);

    $user = Mockery::mock(User::class);
    $user->shouldReceive('hasPermissionTo')->never();

    $this->repository
        ->shouldReceive('allActive')
        ->once()
        ->andReturn($items);

    $result = $this->service->getVisibleItems($user);

    expect($result)->toHaveCount(2);
});

it('filters out items whose permission_name the user does not have', function () {
    $publicItem = new NavigationItem(['label' => 'Dashboard', 'permission_name' => null]);
    $restrictedItem = new NavigationItem(['label' => 'Venues', 'permission_name' => 'venues.view']);

    $items = new Collection([$publicItem, $restrictedItem]);

    $user = Mockery::mock(User::class);
    $user->shouldReceive('hasPermissionTo')
        ->with('venues.view')
        ->once()
        ->andReturn(false);

    $this->repository
        ->shouldReceive('allActive')
        ->once()
        ->andReturn($items);

    $result = $this->service->getVisibleItems($user);

    expect($result)->toHaveCount(1)
        ->and($result->first()->label)->toBe('Dashboard');
});

it('includes items whose permission_name the user has', function () {
    $restrictedItem = new NavigationItem(['label' => 'Venues', 'permission_name' => 'venues.view']);
    $items = new Collection([$restrictedItem]);

    $user = Mockery::mock(User::class);
    $user->shouldReceive('hasPermissionTo')
        ->with('venues.view')
        ->once()
        ->andReturn(true);

    $this->repository
        ->shouldReceive('allActive')
        ->once()
        ->andReturn($items);

    $result = $this->service->getVisibleItems($user);

    expect($result)->toHaveCount(1);
});
