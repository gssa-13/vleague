<?php

// app/Http/Controllers/Media/MediaController.php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\CreateMediaRequest;
use App\Http\Requests\Media\UpdateMediaRequest;
use App\Models\Media;
use App\Models\MediaType;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function __construct(
        private readonly MediaService $service,
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', Media::class);

        $media = $this->service->all();

        return view('media.index', compact('media'));
    }

    public function create(): View
    {
        $this->authorize('create', Media::class);

        $mediaTypes = MediaType::all();

        return view('media.create', compact('mediaTypes'));
    }

    public function store(CreateMediaRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('media.index')
            ->with('success', 'Media uploaded successfully.');
    }

    public function edit(Media $media): View
    {
        $this->authorize('update', $media);

        $mediaTypes = MediaType::all();

        return view('media.edit', compact('media', 'mediaTypes'));
    }

    public function update(UpdateMediaRequest $request, Media $media): RedirectResponse
    {
        $this->service->update($media, $request->validated());

        return redirect()->route('media.index')
            ->with('success', 'Media updated successfully.');
    }

    public function destroy(Media $media): RedirectResponse
    {
        $this->authorize('delete', $media);

        $this->service->delete($media);

        return redirect()->route('media.index')
            ->with('success', 'Media deleted successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $media = $this->service->findTrashedById($id);

        $this->authorize('restore', $media);

        $this->service->restore($media);

        return redirect()->route('media.index')
            ->with('success', 'Media restored successfully.');
    }
}
