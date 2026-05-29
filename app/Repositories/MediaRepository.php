<?php

// app/Repositories/MediaRepository.php

namespace App\Repositories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Collection;

class MediaRepository
{
    public function all(): Collection
    {
        return Media::with('mediaType')->get();
    }

    public function findById(int $id): ?Media
    {
        return Media::find($id);
    }

    public function findTrashedById(int $id): ?Media
    {
        return Media::withTrashed()->find($id);
    }

    public function create(array $data): Media
    {
        return Media::create($data);
    }

    public function update(Media $media, array $data): Media
    {
        $media->update($data);

        return $media;
    }

    public function delete(Media $media): void
    {
        $media->delete();
    }

    public function restore(Media $media): void
    {
        $media->restore();
    }
}
