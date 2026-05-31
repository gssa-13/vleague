<?php

namespace App\Http\Controllers\Localization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Localization\UpdateLocaleRequest;
use App\Services\LocaleService;
use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    public function __construct(
        private readonly LocaleService $localeService,
    ) {}

    public function update(UpdateLocaleRequest $request): RedirectResponse
    {
        $this->localeService->updateLocale(
            $request->validated('locale'),
            $request->user(),
        );

        return back();
    }
}
