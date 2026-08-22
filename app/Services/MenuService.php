<?php

namespace App\Services;

use App\Helpers\PlatformModeHelper;
use App\Models\MenuItem;

class MenuService
{
    public static function forPanel(string $panel): array
    {
        $mode = PlatformModeHelper::getMode();

        $items = MenuItem::query()
            ->where('panel', $panel)
            ->where('enabled', true)
            ->orderBy('sort_order')
            ->get();

        $filtered = [];
        $seen = [];

        foreach ($items as $item) {
            $visibility = $item->visibility ?? [];
            $modes = $visibility['modes'] ?? null;

            if (is_array($modes) && !in_array($mode, $modes, true)) {
                continue;
            }

            $href = null;
            if (!empty($item->route_name)) {
                try {
                    $href = route($item->route_name);
                } catch (\Throwable $e) {
                    $href = null;
                }
            }

            if ($href === null && !empty($item->url)) {
                $href = $item->url;
            }

            if ($href === null) {
                continue;
            }

            if (in_array($href, $seen, true)) {
                continue;
            }

            $seen[] = $href;

            $filtered[] = [
                'key' => $item->key,
                'label' => !empty($item->label_override) ? $item->label_override : __($item->label_key),
                'icon' => $item->icon,
                'href' => $href,
                'route_name' => $item->route_name,
            ];
        }

        return $filtered;
    }
}
