<?php

namespace App\Services\Ads\Providers;

class DealAppProvider extends BaseProvider
{
    public function key(): string
    {
        return 'dealapp';
    }

    public function label(): string
    {
        return 'ديل';
    }

    public function baseUrl(): string
    {
        return 'https://dealapp.sa';
    }

    public function search(array $filters): array
    {
        // Placeholder provider: dealapp is often a JS-heavy app; we will finalize scraping after you provide a results URL pattern.
        return [];
    }
}
