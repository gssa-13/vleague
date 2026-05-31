<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\App;
use InvalidArgumentException;

class LocaleService
{
    public const SESSION_KEY = 'locale';

    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}

    public function applyLocale(?Authenticatable $user = null): string
    {
        $locale = $this->resolveLocale($user);
        App::setLocale($locale);

        return $locale;
    }

    public function updateLocale(string $locale, ?Authenticatable $user = null): string
    {
        if (! $this->isSupported($locale)) {
            throw new InvalidArgumentException("Unsupported locale [{$locale}].");
        }

        session([self::SESSION_KEY => $locale]);

        if ($user instanceof User) {
            $this->userRepository->update($user, ['locale' => $locale]);
        }

        App::setLocale($locale);

        return $locale;
    }

    public function resolveLocale(?Authenticatable $user = null): string
    {
        $userLocale = $user instanceof User ? $user->locale : null;

        return $this->firstSupported([
            $userLocale,
            session(self::SESSION_KEY),
            config('locales.default'),
            config('app.locale'),
            config('locales.fallback'),
            config('app.fallback_locale'),
            'en',
        ]);
    }

    /**
     * @return array<int, string>
     */
    public function supportedLocaleCodes(): array
    {
        return array_keys(config('locales.supported', []));
    }

    public function isSupported(?string $locale): bool
    {
        return is_string($locale) && array_key_exists($locale, config('locales.supported', []));
    }

    /**
     * @param  array<int, mixed>  $candidates
     */
    private function firstSupported(array $candidates): string
    {
        foreach ($candidates as $candidate) {
            if ($this->isSupported($candidate)) {
                return $candidate;
            }
        }

        return 'en';
    }
}
