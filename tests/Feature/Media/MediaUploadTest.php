<?php

// tests/Feature/Media/MediaUploadTest.php

use App\Models\Media;
use App\Models\MediaType;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('uploads a file using the fake disk and persists the media record', function () {
    Storage::fake('public');

    $actor = User::factory()->create();
    $actor->givePermissionTo('media.create');

    $mediaType = MediaType::factory()->create();
    $file = UploadedFile::fake()->image('banner.jpg');

    $this->actingAs($actor)
        ->post(route('media.store'), [
            'media_type_id' => $mediaType->id,
            'name' => 'Home banner',
            'file' => $file,
        ])
        ->assertRedirect(route('media.index'));

    $media = Media::where('name', 'Home banner')->first();

    expect($media)->not->toBeNull();
    Storage::disk('public')->assertExists($media->file_path);
});

it('keeps the stored file when a media record is soft deleted', function () {
    Storage::fake('public');

    $actor = User::factory()->create();
    $actor->givePermissionTo(['media.create', 'media.delete']);

    $mediaType = MediaType::factory()->create();
    $file = UploadedFile::fake()->image('promo.png');

    $this->actingAs($actor)
        ->post(route('media.store'), [
            'media_type_id' => $mediaType->id,
            'name' => 'Promo',
            'file' => $file,
        ]);

    $media = Media::where('name', 'Promo')->first();
    $path = $media->file_path;

    $this->actingAs($actor)
        ->delete(route('media.destroy', $media))
        ->assertRedirect(route('media.index'));

    $this->assertSoftDeleted('media', ['id' => $media->id]);

    // File is preserved even after the record is soft deleted
    Storage::disk('public')->assertExists($path);
});

it('fails validation when uploading without a file', function () {
    Storage::fake('public');

    $actor = User::factory()->create();
    $actor->givePermissionTo('media.create');

    $mediaType = MediaType::factory()->create();

    $this->actingAs($actor)
        ->post(route('media.store'), [
            'media_type_id' => $mediaType->id,
            'name' => 'No file',
        ])
        ->assertSessionHasErrors('file');
});
