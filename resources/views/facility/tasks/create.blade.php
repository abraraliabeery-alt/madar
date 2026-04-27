@extends('facility.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">إضافة مهمة</h1>
    </div>

    <form action="{{ route('facility.tasks.store') }}" method="POST" class="bg-white p-6 rounded shadow max-w-3xl">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-1">نوع المهمة (حر)</label>
                <input type="text" name="type" list="task-types" value="{{ old('type') }}" class="w-full border rounded px-3 py-2" required>
                <datalist id="task-types">
                    <option value="تصوير عقار" />
                    <option value="اتصال" />
                    <option value="معاينة" />
                    <option value="تنظيف" />
                    <option value="صيانة" />
                    <option value="زيارة ميدانية" />
                    <option value="توثيق عقد" />
                    <option value="تسليم مفاتيح" />
                    <option value="متابعة مدفوعات" />
                </datalist>
                @error('type')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block mb-1">الحالة</label>
                <select name="status" class="w-full border rounded px-3 py-2" required>
                    @foreach(['open'=>'مفتوحة','assigned'=>'مُسندة','in_progress'=>'قيد التنفيذ','done'=>'منتهية','cancelled'=>'ملغاة'] as $v=>$label)
                        <option value="{{ $v }}" @selected(old('status')===$v)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('status')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block mb-1">الأولوية</label>
                <select name="priority" class="w-full border rounded px-3 py-2" required>
                    @foreach(['low'=>'منخفضة','medium'=>'متوسطة','high'=>'عالية'] as $v=>$label)
                        <option value="{{ $v }}" @selected(old('priority')===$v)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('priority')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block mb-1">الموعد النهائي</label>
                <input type="date" name="deadline" value="{{ old('deadline') }}" class="w-full border rounded px-3 py-2">
                @error('deadline')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="block mb-1">المكلّفون</label>
                <select name="assignees[]" multiple class="w-full border rounded px-3 py-2 min-h-[44px]">
                    @foreach($assignees as $u)
                        <option value="{{ $u->id }}" @selected(collect(old('assignees',[]))->contains($u->id))>{{ $u->name }}</option>
                    @endforeach
                </select>
                @error('assignees')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <button class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded">حفظ</button>
            <a href="{{ route('facility.tasks.index') }}" class="px-4 py-2 border rounded">إلغاء</a>
        </div>
    </form>
</div>
@endsection
