<?php

// app/Services/MediaService.php

namespace App\Services;

use App\Models\Media;
use App\Repositories\MediaRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class MediaService
{
    public function __construct(
        private readonly MediaRepository $repository,
    ) {}

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function findById(int $id): ?Media
    {
        return $this->repository->findById($id);
    }

    public function findTrashedById(int $id): ?Media
    {
        return $this->repository->findTrashedById($id);
    }

    /**
     * Stores the uploaded file on the public disk and persists the media record.
     */
    public function create(array $data): Media
    {
        if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
            $data['file_path'] = $data['file']->store('media', 'public');
            unset($data['file']);
        }

        return $this->repository->create($data);
    }

    public function update(Media $media, array $data): Media
    {
        if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
            $data['file_path'] = $data['file']->store('media', 'public');
            unset($data['file']);
        }

        return $this->repository->update($media, $data);
    }

    /**
     * Soft deletes the media record. The stored file is intentionally preserved.
     */
    public function delete(Media $media): void
    {
        $this->repository->delete($media);
    }

    public function restore(Media $media): void
    {
        $this->repository->restore($media);
    }
}
