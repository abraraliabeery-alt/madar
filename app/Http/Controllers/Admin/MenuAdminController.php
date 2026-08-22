<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuAdminController extends Controller
{
    public function index(Request $request)
    {
        $panel = $request->query('panel', 'public');
        if (!in_array($panel, ['public', 'admin', 'facility', 'client'], true)) {
            $panel = 'public';
        }

        $items = MenuItem::query()
            ->where('panel', $panel)
            ->orderBy('sort_order')
            ->get();

        return view('admin.menus.index', compact('panel', 'items'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'panel' => 'required|in:public,admin,facility,client',
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:menu_items,id',
            'items.*.enabled' => 'nullable|boolean',
            'items.*.sort_order' => 'required|integer|min:0',
            'items.*.visibility_modes' => 'nullable|array',
            'items.*.visibility_modes.*' => 'in:real_estate,contracting,lifecycle',
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['items'] as $row) {
                $item = MenuItem::where('id', $row['id'])->where('panel', $data['panel'])->first();
                if (!$item) {
                    continue;
                }

                $modes = $row['visibility_modes'] ?? null;
                $visibility = $item->visibility ?? [];
                $visibility['modes'] = is_array($modes) && count($modes) > 0 ? array_values($modes) : null;

                $item->update([
                    'enabled' => (bool) ($row['enabled'] ?? false),
                    'sort_order' => (int) $row['sort_order'],
                    'visibility' => $visibility,
                ]);
            }
        });

        return redirect()->back()->with('success', 'تم تحديث القوائم بنجاح');
    }
}
