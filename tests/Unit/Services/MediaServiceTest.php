<?php

// tests/Unit/Services/MediaServiceTest.php

use App\Models\Media;
use App\Repositories\MediaRepository;
use App\Services\MediaService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->repository = Mockery::mock(MediaRepository::class);
    $this->service = new MediaService($this->repository);
});

it('stores the uploaded file and creates the media via repository', function () {
    Storage::fake('public');

    $media = new Media;

    $this->repository
        ->shouldReceive('create')
        ->once()
        ->withArgs(function (array $data) {
            return $data['name'] === 'Banner'
                && $data['media_type_id'] === 3
                && str_starts_with($data['file_path'], 'media/');
        })
        ->andReturn($media);

    $file = UploadedFile::fake()->image('banner.jpg');

    $result = $this->service->create([
        'media_type_id' => 3,
        'name' => 'Banner',
        'file' => $file,
    ]);

    expect($result)->toBeInstanceOf(Media::class);
});

it('soft deletes a media record without removing the stored file', function () {
    Storage::fake('public');

    $media = new Media;
    $media->file_path = 'media/keep.jpg';

    Storage::disk('public')->put('media/keep.jpg', 'content');

    $this->repository
        ->shouldReceive('delete')
        ->once()
        ->with($media);

    $this->service->delete($media);

    Storage::disk('public')->assertExists('media/keep.jpg');
});

it('restores a media record via repository', function () {
    $media = new Media;

    $this->repository
        ->shouldReceive('restore')
        ->once()
        ->with($media);

    $this->service->restore($media);
});
