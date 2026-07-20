<?php

namespace App\Services\Ads\Providers;

interface ProviderInterface
{
    public function key(): string;

    public function label(): string;

    public function baseUrl(): string;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function search(array $filters): array;
}
