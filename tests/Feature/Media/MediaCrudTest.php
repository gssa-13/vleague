<?php

// tests/Feature/Media/MediaCrudTest.php

use App\Models\ActivityLog;
use App\Models\Media;
use App\Models\MediaType;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('redirects unauthenticated user away from media index', function () {
    $this->get(route('media.index'))
        ->assertRedirect(route('login'));
});

it('returns 403 when user lacks media.view permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('media.index'))
        ->assertForbidden();
});

it('lists media when user has media.view permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('media.view');

    Media::factory()->count(2)->create();

    $this->actingAs($viewer)
        ->get(route('media.index'))
        ->assertOk()
        ->assertViewIs('media.index');
});

it('excludes soft-deleted media from the default listing', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('media.view');

    $media = Media::factory()->create();
    $media->delete();

    $response = $this->actingAs($viewer)->get(route('media.index'));

    $items = $response->viewData('media');
    expect($items->contains('id', $media->id))->toBeFalse();
});

it('restores a soft-deleted media when actor has media.restore permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('media.restore');

    $media = Media::factory()->create();
    $media->delete();

    $this->actingAs($actor)
        ->post(route('media.restore', $media->id))
        ->assertRedirect(route('media.index'));

    $this->assertNotSoftDeleted('media', ['id' => $media->id]);
});

it('records an activity log entry when a media record is created', function () {
    Storage::fake('public');

    $actor = User::factory()->create();
    $actor->givePermissionTo('media.create');

    $mediaType = MediaType::factory()->create();

    $this->actingAs($actor)
        ->post(route('media.store'), [
            'media_type_id' => $mediaType->id,
            'name' => 'Logged Media',
            'file' => UploadedFile::fake()->image('x.jpg'),
        ]);

    $media = Media::where('name', 'Logged Media')->first();

    expect(
        ActivityLog::where('action', 'created')
            ->where('model', Media::class)
            ->where('record_id', $media->id)
            ->exists()
    )->toBeTrue();
});
