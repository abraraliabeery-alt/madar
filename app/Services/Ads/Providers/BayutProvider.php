<?php

namespace App\Services\Ads\Providers;

class BayutProvider extends BaseProvider
{
    public function key(): string
    {
        return 'bayut';
    }

    public function label(): string
    {
        return 'بيوت';
    }

    public function baseUrl(): string
    {
        return 'https://bayut.sa';
    }

    public function search(array $filters): array
    {
        // Placeholder provider: until we get the exact results URL pattern from you, we just return []
        return [];
    }
}
