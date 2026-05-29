<?php

// tests/Unit/Services/VenueServiceTest.php

use App\Models\Venue;
use App\Repositories\VenueRepository;
use App\Services\VenueService;

beforeEach(function () {
    $this->repository = Mockery::mock(VenueRepository::class);
    $this->service = new VenueService($this->repository);
});

it('creates a venue via repository', function () {
    $venue = new Venue;

    $this->repository
        ->shouldReceive('create')
        ->once()
        ->with(['name' => 'Lindavista', 'city' => 'CDMX'])
        ->andReturn($venue);

    $result = $this->service->create(['name' => 'Lindavista', 'city' => 'CDMX']);

    expect($result)->toBeInstanceOf(Venue::class);
});

it('updates a venue via repository', function () {
    $venue = new Venue;

    $this->repository
        ->shouldReceive('update')
        ->once()
        ->with($venue, ['name' => 'Updated'])
        ->andReturn($venue);

    $result = $this->service->update($venue, ['name' => 'Updated']);

    expect($result)->toBeInstanceOf(Venue::class);
});

it('soft deletes a venue via repository', function () {
    $venue = new Venue;

    $this->repository
        ->shouldReceive('delete')
        ->once()
        ->with($venue);

    $this->service->delete($venue);
});

it('restores a soft-deleted venue via repository', function () {
    $venue = new Venue;

    $this->repository
        ->shouldReceive('restore')
        ->once()
        ->with($venue);

    $this->service->restore($venue);
});
