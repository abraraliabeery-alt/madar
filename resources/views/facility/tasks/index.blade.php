@extends('facility.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">المهام</h1>
        <div class="flex gap-2">
            <form action="{{ route('facility.tasks.generate-reminders') }}" method="POST">
                @csrf
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded" title="توليد مهام تذكير للفواتير والحجوزات">
                    توليد مهام التذكير
                </button>
            </form>
            <a href="{{ route('facility.tasks.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded">
                إضافة مهمة
            </a>
        </div>

    @if(isset($kpis))
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4">
            <div class="text-sm text-gray-500">إجمالي المهام</div>
            <div class="text-2xl font-bold">{{ number_format($kpis['total'] ?? 0) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-sm text-gray-500">مفتوحة</div>
            <div class="text-2xl font-bold text-amber-600">{{ number_format($kpis['open'] ?? 0) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-sm text-gray-500">منتهية هذا الأسبوع</div>
            <div class="text-2xl font-bold text-green-600">{{ number_format($kpis['done_week'] ?? 0) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-sm text-gray-500">متأخرة</div>
            <div class="text-2xl font-bold text-red-600">{{ number_format($kpis['overdue'] ?? 0) }}</div>
        </div>
        <div class="bg-white rounded shadow p-4">
            <div class="text-sm text-gray-500">مستحقة اليوم</div>
            <div class="text-2xl font-bold text-blue-600">{{ number_format($kpis['due_today'] ?? 0) }}</div>
        </div>
    </div>
    @endif
    </div>

    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3 bg-white p-4 rounded shadow mb-6">
        <input type="text" name="type" value="{{ request('type') }}" placeholder="نوع المهمة (حر)" class="border rounded px-3 py-2">
        <select name="status" class="border rounded px-3 py-2">
            <option value="">كل الحالات</option>
            @foreach(['open'=>'مفتوحة','assigned'=>'مُسندة','in_progress'=>'قيد التنفيذ','done'=>'منتهية','cancelled'=>'ملغاة'] as $v=>$label)
                <option value="{{ $v }}" @selected(request('status')===$v)>{{ $label }}</option>
            @endforeach
        </select>
        <select name="priority" class="border rounded px-3 py-2">
            <option value="">كل الأولويات</option>
            @foreach(['low'=>'منخفضة','medium'=>'متوسطة','high'=>'عالية'] as $v=>$label)
                <option value="{{ $v }}" @selected(request('priority')===$v)>{{ $label }}</option>
            @endforeach
        </select>
        <select name="assignee_id" class="border rounded px-3 py-2">
            <option value="">كل المكلّفين</option>
            @foreach($assignees as $u)
                <option value="{{ $u->id }}" @selected(request('assignee_id')==$u->id)>{{ $u->name }}</option>
            @endforeach
        </select>
        <div class="flex gap-2">
            <button class="bg-gray-800 text-white px-4 py-2 rounded">تصفية</button>
            <a href="{{ route('facility.tasks.index') }}" class="px-4 py-2 border rounded">تفريغ</a>
        </div>
    </form>

    <div class="bg-white rounded shadow p-4 mb-6">
        <form action="{{ route('facility.tasks.quick') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
            @csrf
            <div class="md:col-span-2">
                <label class="block mb-1 text-sm">إضافة سريعة - نوع المهمة</label>
                <input type="text" name="type" placeholder="مثال: تصوير عقار" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block mb-1 text-sm">الموعد النهائي</label>
                <input type="date" name="deadline" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block mb-1 text-sm">الحالة</label>
                <select name="status" class="w-full border rounded px-3 py-2">
                    @foreach(['open'=>'مفتوحة','assigned'=>'مُسندة','in_progress'=>'قيد التنفيذ','done'=>'منتهية','cancelled'=>'ملغاة'] as $v=>$label)
                        <option value="{{ $v }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-1 text-sm">الأولوية</label>
                <select name="priority" class="w-full border rounded px-3 py-2">
                    @foreach(['low'=>'منخفضة','medium'=>'متوسطة','high'=>'عالية'] as $v=>$label)
                        <option value="{{ $v }}" @selected($v==='medium')>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block mb-1 text-sm">المكلّفون</label>
                <select name="assignees[]" multiple class="w-full border rounded px-3 py-2 min-h-[42px]">
                    @foreach($assignees as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded">إضافة</button>
            </div>
        </form>
    </div>

    @if(isset($workload) && count($workload))
    <div class="bg-white rounded shadow p-4 mb-6">
        <h2 class="text-lg font-semibold mb-3">توزيع العبء على الفريق (مهام مفتوحة)</h2>
        <div class="flex flex-wrap gap-3">
            @foreach($workload as $wl)
                <div class="border rounded px-3 py-2 text-sm">
                    <span class="font-medium">{{ $wl['name'] }}</span>
                    <span class="mx-1 text-gray-400">•</span>
                    <span>{{ $wl['open_tasks'] }} مهام</span>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50 text-gray-700 text-right">
                    <th class="px-6 py-3">#</th>
                    <th class="px-6 py-3">النوع</th>
                    <th class="px-6 py-3">الحالة</th>
                    <th class="px-6 py-3">الأولوية</th>
                    <th class="px-6 py-3">الموعد النهائي</th>
                    <th class="px-6 py-3">المكلّفون</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                    <tr class="border-t">
                        <td class="px-6 py-3">{{ $task->id }}</td>
                        <td class="px-6 py-3">{{ $task->type }}</td>
                        <td class="px-6 py-3">{{ __('statuses.'.$task->status) ?? $task->status }}</td>
                        <td class="px-6 py-3">{{ __('priorities.'.$task->priority) ?? $task->priority }}</td>
                        <td class="px-6 py-3">{{ optional($task->deadline)->format('Y-m-d') }}</td>
                        <td class="px-6 py-3">
                            <div class="flex flex-wrap gap-1">
                                @foreach($task->users as $u)
                                    <span class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $u->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-3 text-left">
                            <a href="{{ route('facility.tasks.edit', $task) }}" class="text-indigo-600 hover:underline">تعديل</a>
                            <form action="{{ route('facility.tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('حذف المهمة؟');">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-6 text-center text-gray-500">لا توجد مهام</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $tasks->links() }}
    </div>
</div>
@endsection
