<?php

namespace App\Services\Ads\Providers;

class HarajProvider extends BaseProvider
{
    public function key(): string
    {
        return 'haraj';
    }

    public function label(): string
    {
        return 'حراج';
    }

    public function baseUrl(): string
    {
        return 'https://haraj.com.sa';
    }

    public function search(array $filters): array
    {
        // Placeholder provider: until we get the exact results URL pattern from you, we just return []
        return [];
    }
}
