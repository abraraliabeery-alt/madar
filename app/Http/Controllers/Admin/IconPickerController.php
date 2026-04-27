<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IconPickerController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $query = strtolower((string) $request->get('q', ''));

        $icons = config('icon_picker.icons', []);

        if ($query !== '') {
            $icons = array_values(array_filter($icons, function ($icon) use ($query) {
                $class = strtolower($icon['class'] ?? '');
                $name = strtolower($icon['name'] ?? '');
                $tags = strtolower(implode(' ', $icon['tags'] ?? []));

                return str_contains($class, $query)
                    || str_contains($name, $query)
                    || str_contains($tags, $query);
            }));
        }

        return response()->json([
            'data' => $icons,
        ]);
    }
}
