<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class AdminRoleController extends Controller
{
    /**
     * عرض قائمة الأدوار
     */
    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])->paginate(15);
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * عرض صفحة إنشاء دور جديد
     */
    public function create()
    {
        $permissions = Permission::with('translations')->active()->get();
        $locales = config('locales.available');
        return view('admin.roles.create', compact('permissions', 'locales'));
    }

    /**
     * حفظ دور جديد
     */
    public function store(Request $request)
    {
        $availableLocales = array_keys(config('locales.available', []));
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
            'translations' => 'nullable|array',
            'translations.*.locale' => 'required_with:translations|string|in:' . implode(',', $availableLocales) . '|distinct',
            'translations.*.name' => 'required_with:translations|string|max:255',
            'translations.*.display_name' => 'nullable|string|max:255',
            'translations.*.description' => 'nullable|string',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $incomingTranslations = $request->input('translations');
        if (!is_array($incomingTranslations) || !count($incomingTranslations)) {
            $incomingTranslations = [];
            if ($request->filled('name') || $request->filled('description')) {
                $incomingTranslations[] = [
                    'locale' => app()->getLocale(),
                    'name' => $request->input('name'),
                    'display_name' => $request->input('name'),
                    'description' => $request->input('description'),
                ];
            }
        }

        $firstTranslationName = null;
        foreach ($incomingTranslations as $t) {
            if (!empty($t['name'])) {
                $firstTranslationName = $t['name'];
                break;
            }
        }

        if (!$firstTranslationName) {
            return back()
                ->withErrors(['translations' => 'يجب إدخال اسم الدور في ترجمة واحدة على الأقل'])
                ->withInput();
        }

        $role = Role::create([]);

        foreach ($incomingTranslations as $translationData) {
            if (empty($translationData['locale']) || empty($translationData['name'])) {
                continue;
            }

            $role->translations()->create([
                'locale' => $translationData['locale'],
                'name' => $translationData['name'],
                'display_name' => $translationData['display_name'] ?? $translationData['name'],
                'description' => $translationData['description'] ?? null,
            ]);
        }

        if ($request->has('permissions')) {
            $role->permissions()->attach($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'تم إنشاء الدور بنجاح');
    }

    /**
     * عرض صفحة تعديل الدور
     */
    public function edit(Role $role)
    {
        $role->load(['permissions.translations', 'translations']);
        $permissions = Permission::with('translations')->active()->get();
        $locales = config('locales.available');
        return view('admin.roles.edit', compact('role', 'permissions', 'locales'));
    }

    /**
     * تحديث الدور
     */
    public function update(Request $request, Role $role)
    {
        $availableLocales = array_keys(config('locales.available', []));
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
            'translations' => 'nullable|array',
            'translations.*.locale' => 'required_with:translations|string|in:' . implode(',', $availableLocales) . '|distinct',
            'translations.*.name' => 'required_with:translations|string|max:255',
            'translations.*.display_name' => 'nullable|string|max:255',
            'translations.*.description' => 'nullable|string',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $incomingTranslations = $request->input('translations');
        if (!is_array($incomingTranslations) || !count($incomingTranslations)) {
            $incomingTranslations = [];
            if ($request->filled('name') || $request->filled('description')) {
                $incomingTranslations[] = [
                    'locale' => app()->getLocale(),
                    'name' => $request->input('name'),
                    'display_name' => $request->input('name'),
                    'description' => $request->input('description'),
                ];
            }
        }

        $firstTranslationName = null;
        foreach ($incomingTranslations as $t) {
            if (!empty($t['name'])) {
                $firstTranslationName = $t['name'];
                break;
            }
        }

        if (!$firstTranslationName) {
            return back()
                ->withErrors(['translations' => 'يجب إدخال اسم الدور في ترجمة واحدة على الأقل'])
                ->withInput();
        }

        $incomingLocales = [];
        foreach ($incomingTranslations as $translationData) {
            if (empty($translationData['locale']) || empty($translationData['name'])) {
                continue;
            }

            $incomingLocales[] = $translationData['locale'];

            $role->translations()->updateOrCreate(
                [
                    'locale' => $translationData['locale'],
                ],
                [
                    'name' => $translationData['name'],
                    'display_name' => $translationData['display_name'] ?? $translationData['name'],
                    'description' => $translationData['description'] ?? null,
                ]
            );
        }

        $role->translations()->whereNotIn('locale', $incomingLocales)->delete();

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'تم تحديث الدور بنجاح');
    }

    /**
     * حذف الدور
     */
    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف الدور لوجود مستخدمين مرتبطين به');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'تم حذف الدور بنجاح');
    }

    /**
     * عرض تفاصيل الدور
     */
    public function show(Role $role)
    {
        $role->load(['permissions.translations', 'users']);
        return view('admin.roles.show', compact('role'));
    }
}
