<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectCategory;
use App\Models\ProjectCategoryTranslation;
use Illuminate\Support\Facades\Storage;

class AdminProjectCategoryController extends Controller
{
    public function index()
    {
        $projectCategories = ProjectCategory::withCount('projects')->paginate(15);
        return view('admin.project-categories.index', compact('projectCategories'));
    }

    public function create()
    {
        $projectCategories = ProjectCategory::where('parent_id', null)->get();
        $locales = config('locales.available');
        return view('admin.project-categories.create', compact('projectCategories', 'locales'));
    }

    public function store(Request $request)
    {
        $availableLocales = array_keys(config('locales.available', []));
        $request->validate([
            'parent_id' => 'nullable|exists:project_categories,id',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer|min:0',
            'translations' => 'nullable|array',
            'translations.*.locale' => 'required_with:translations|string|in:' . implode(',', $availableLocales) . '|distinct',
            'translations.*.name' => 'required_with:translations|string|max:255',
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
                ->withErrors(['translations' => 'يجب إدخال اسم التصنيف في ترجمة واحدة على الأقل'])
                ->withInput();
        }

        $categoryData = $request->except(['icon', 'image', 'translations', 'name', 'description']);

        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('uploads/project-categories/icons', 'public');
            $categoryData['icon'] = $iconPath;
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/project-categories/images', 'public');
            $categoryData['image'] = $imagePath;
        }

        $projectCategory = ProjectCategory::create($categoryData);

        foreach ($incomingTranslations as $translationData) {
            if (empty($translationData['locale']) || empty($translationData['name'])) {
                continue;
            }

            ProjectCategoryTranslation::create([
                'project_category_id' => $projectCategory->id,
                'locale' => $translationData['locale'],
                'name' => $translationData['name'],
                'description' => $translationData['description'] ?? null,
            ]);
        }

        return redirect()->route('admin.project-categories.index')
            ->with('success', 'تم إنشاء تصنيف المشاريع بنجاح');
    }

    public function edit(ProjectCategory $projectCategory)
    {
        $projectCategories = ProjectCategory::where('parent_id', null)
            ->where('id', '!=', $projectCategory->id)
            ->get();
        $locales = config('locales.available');
        return view('admin.project-categories.edit', compact('projectCategory', 'projectCategories', 'locales'));
    }

    public function update(Request $request, ProjectCategory $projectCategory)
    {
        $availableLocales = array_keys(config('locales.available', []));
        $request->validate([
            'parent_id' => 'nullable|exists:project_categories,id',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer|min:0',
            'translations' => 'nullable|array',
            'translations.*.locale' => 'required_with:translations|string|in:' . implode(',', $availableLocales) . '|distinct',
            'translations.*.name' => 'required_with:translations|string|max:255',
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
                ->withErrors(['translations' => 'يجب إدخال اسم التصنيف في ترجمة واحدة على الأقل'])
                ->withInput();
        }

        $categoryData = $request->except(['icon', 'image', 'translations', 'name', 'description']);

        if ($request->hasFile('icon')) {
            if ($projectCategory->icon) {
                Storage::disk('public')->delete($projectCategory->icon);
            }
            $iconPath = $request->file('icon')->store('uploads/project-categories/icons', 'public');
            $categoryData['icon'] = $iconPath;
        }

        if ($request->hasFile('image')) {
            if ($projectCategory->image) {
                Storage::disk('public')->delete($projectCategory->image);
            }
            $imagePath = $request->file('image')->store('uploads/project-categories/images', 'public');
            $categoryData['image'] = $imagePath;
        }

        $projectCategory->update($categoryData);

        $incomingLocales = [];
        foreach ($incomingTranslations as $translationData) {
            if (empty($translationData['locale']) || empty($translationData['name'])) {
                continue;
            }

            $incomingLocales[] = $translationData['locale'];

            ProjectCategoryTranslation::updateOrCreate(
                [
                    'project_category_id' => $projectCategory->id,
                    'locale' => $translationData['locale'],
                ],
                [
                    'name' => $translationData['name'],
                    'description' => $translationData['description'] ?? null,
                ]
            );
        }

        $projectCategory->translations()->whereNotIn('locale', $incomingLocales)->delete();

        return redirect()->route('admin.project-categories.index')
            ->with('success', 'تم تحديث تصنيف المشاريع بنجاح');
    }

    public function destroy(ProjectCategory $projectCategory)
    {
        if ($projectCategory->projects()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف التصنيف لوجود مشاريع مرتبطة به');
        }

        if ($projectCategory->icon) {
            Storage::disk('public')->delete($projectCategory->icon);
        }
        if ($projectCategory->image) {
            Storage::disk('public')->delete($projectCategory->image);
        }

        $projectCategory->delete();

        return redirect()->route('admin.project-categories.index')
            ->with('success', 'تم حذف تصنيف المشاريع بنجاح');
    }

    public function toggleStatus(ProjectCategory $projectCategory)
    {
        $projectCategory->update(['is_active' => !$projectCategory->is_active]);

        return redirect()->back()
            ->with('success', 'تم تحديث حالة التصنيف بنجاح');
    }

    public function toggleFeatured(ProjectCategory $projectCategory)
    {
        $projectCategory->update(['is_featured' => !$projectCategory->is_featured]);

        return redirect()->back()
            ->with('success', 'تم تحديث حالة الميزة بنجاح');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:project_categories,id',
            'categories.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->categories as $categoryData) {
            ProjectCategory::where('id', $categoryData['id'])
                ->update(['order' => $categoryData['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم ترتيب التصنيفات بنجاح'
        ]);
    }

    public function statistics()
    {
        $stats = [
            'total_categories' => ProjectCategory::count(),
            'active_categories' => ProjectCategory::where('is_active', true)->count(),
            'featured_categories' => ProjectCategory::where('is_featured', true)->count(),
            'parent_categories' => ProjectCategory::where('parent_id', null)->count(),
            'sub_categories' => ProjectCategory::where('parent_id', '!=', null)->count(),
            'categories_with_projects' => ProjectCategory::has('projects')->count(),
            'top_categories' => ProjectCategory::withCount('projects')
                ->orderBy('projects_count', 'desc')
                ->take(10)
                ->get(),
        ];

        return view('admin.project-categories.statistics', compact('stats'));
    }

    public function checkParent(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:project_categories,id',
        ]);

        $projectCategory = ProjectCategory::find($request->category_id);

        return response()->json([
            'id' => $projectCategory->id,
            'name' => $projectCategory->name,
            'is_main_category' => $projectCategory->parent_id === null,
        ]);
    }

    public function show(ProjectCategory $projectCategory)
    {
        $projectCategory->load(['parent', 'children', 'projects']);

        $projectCategory->projects_count = $projectCategory->projects()->count();
        $projectCategory->children_count = $projectCategory->children()->count();

        foreach ($projectCategory->children as $child) {
            $child->projects_count = $child->projects()->count();
        }

        return view('admin.project-categories.show', compact('projectCategory'));
    }
}
