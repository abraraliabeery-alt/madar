<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class AdminPermissionController extends Controller
{
    /**
     * عرض قائمة الصلاحيات
     */
    public function index(Request $request)
    {
        $query = Permission::with(['translations', 'roles']);

        // فلترة حسب الحالة
        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->is_active);
        }

        // فلترة حسب المجموعة
        if ($request->has('group') && $request->group) {
            $query->where('name', 'like', $request->group . '.%');
        }

        // البحث
        if ($request->has('search') && $request->search) {
            $query->whereHas('translations', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $permissions = $query->paginate(15);
        $groups = Permission::select('name')
            ->get()
            ->map(function ($permission) {
                return $permission->getGroupName();
            })
            ->unique()
            ->sort()
            ->values();

        return view('admin.permissions.index', compact('permissions', 'groups'));
    }

    /**
     * عرض صفحة إنشاء صلاحية جديدة
     */
    public function create()
    {
        $groups = Permission::select('name')
            ->get()
            ->map(function ($permission) {
                return $permission->getGroupName();
            })
            ->unique()
            ->sort()
            ->values();

        return view('admin.permissions.create', compact('groups'));
    }

    /**
     * حفظ صلاحية جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'guard_name' => 'nullable|string|max:255',
            'translations' => 'array',
            'translations.*.locale' => 'required|string|max:2',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.display_name' => 'nullable|string|max:255',
            'translations.*.description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $permission = Permission::create([
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->boolean('is_active', true),
                'guard_name' => $request->guard_name ?? 'web',
            ]);

            // حفظ الترجمات
            if ($request->has('translations')) {
                foreach ($request->translations as $translation) {
                    $permission->translations()->create([
                        'locale' => $translation['locale'],
                        'name' => $translation['name'],
                        'display_name' => $translation['display_name'] ?? $translation['name'],
                        'description' => $translation['description'] ?? '',
                    ]);
                }
            }
        });

        return redirect()->route('admin.permissions.index')
            ->with('success', 'تم إنشاء الصلاحية بنجاح');
    }

    /**
     * عرض تفاصيل الصلاحية
     */
    public function show(Permission $permission)
    {
        $permission->load(['translations', 'roles']);
        return view('admin.permissions.show', compact('permission'));
    }

    /**
     * عرض صفحة تعديل الصلاحية
     */
    public function edit(Permission $permission)
    {
        $permission->load('translations');
        $groups = Permission::select('name')
            ->get()
            ->map(function ($permission) {
                return $permission->getGroupName();
            })
            ->unique()
            ->sort()
            ->values();

        return view('admin.permissions.edit', compact('permission', 'groups'));
    }

    /**
     * تحديث الصلاحية
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'guard_name' => 'nullable|string|max:255',
            'translations' => 'array',
            'translations.*.locale' => 'required|string|max:2',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.display_name' => 'nullable|string|max:255',
            'translations.*.description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $permission) {
            $permission->update([
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->boolean('is_active', true),
                'guard_name' => $request->guard_name ?? 'web',
            ]);

            // تحديث الترجمات
            if ($request->has('translations')) {
                $permission->translations()->delete();
                foreach ($request->translations as $translation) {
                    $permission->translations()->create([
                        'locale' => $translation['locale'],
                        'name' => $translation['name'],
                        'display_name' => $translation['display_name'] ?? $translation['name'],
                        'description' => $translation['description'] ?? '',
                    ]);
                }
            }
        });

        return redirect()->route('admin.permissions.index')
            ->with('success', 'تم تحديث الصلاحية بنجاح');
    }

    /**
     * حذف الصلاحية
     */
    public function destroy(Permission $permission)
    {
        // التحقق من وجود أدوار مرتبطة
        if ($permission->roles()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف الصلاحية لأنها مرتبطة بأدوار');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'تم حذف الصلاحية بنجاح');
    }

    /**
     * تبديل حالة الصلاحية
     */
    public function toggleStatus(Permission $permission)
    {
        $permission->update(['is_active' => !$permission->is_active]);

        return redirect()->back()
            ->with('success', 'تم تحديث حالة الصلاحية بنجاح');
    }

    /**
     * عرض إحصائيات الصلاحيات
     */
    public function statistics()
    {
        $stats = [
            'total_permissions' => Permission::count(),
            'active_permissions' => Permission::where('is_active', true)->count(),
            'inactive_permissions' => Permission::where('is_active', false)->count(),
            'permissions_by_group' => Permission::select('name')
                ->get()
                ->groupBy(function ($permission) {
                    return $permission->getGroupName();
                })
                ->map(function ($group) {
                    return $group->count();
                }),
            'most_used_permissions' => Permission::withCount('roles')
                ->orderBy('roles_count', 'desc')
                ->take(10)
                ->get(),
        ];

        return view('admin.permissions.statistics', compact('stats'));
    }

    /**
     * تصدير الصلاحيات
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        
        $permissions = Permission::with(['translations', 'roles'])
            ->when($request->has('is_active'), function ($query) use ($request) {
                $query->where('is_active', $request->is_active);
            })
            ->when($request->has('group'), function ($query) use ($request) {
                $query->where('name', 'like', $request->group . '.%');
            })
            ->get();

        if ($format === 'csv') {
            return $this->exportToCsv($permissions);
        } elseif ($format === 'pdf') {
            return $this->exportToPdf($permissions);
        }

        return $this->exportToExcel($permissions);
    }

    /**
     * تصدير إلى CSV
     */
    private function exportToCsv($permissions)
    {
        $filename = 'permissions_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($permissions) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['Name', 'Description', 'Group', 'Status', 'Roles Count', 'Created At']);
            
            // Data
            foreach ($permissions as $permission) {
                fputcsv($file, [
                    $permission->name,
                    $permission->description,
                    $permission->getGroupName(),
                    $permission->is_active ? 'Active' : 'Inactive',
                    $permission->roles->count(),
                    $permission->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * تصدير إلى Excel
     */
    private function exportToExcel($permissions)
    {
        // Implementation for Excel export
        return response()->json(['message' => 'Excel export not implemented yet']);
    }

    /**
     * تصدير إلى PDF
     */
    private function exportToPdf($permissions)
    {
        // Implementation for PDF export
        return response()->json(['message' => 'PDF export not implemented yet']);
    }
}
