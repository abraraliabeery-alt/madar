@extends('facility.layouts.app')

@section('title', 'إضافة فترة محاسبية جديدة')

@section('content')
<div class="container mx-auto px-4 my-10">
    <div class="flex justify-center">
        <div class="w-full max-w-6xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-gray-800 mb-0">إضافة فترة محاسبية جديدة</h4>
                        <a href="{{ route('facility.accounting.accounting-periods.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                            <i class="fas fa-arrow-left"></i>
                            <span>العودة للقائمة</span>
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('facility.accounting.accounting-periods.store') }}">
                    @csrf
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- معلومات الفترة -->
                            <div class="lg:col-span-2">
                                <div class="bg-white border border-gray-200 rounded-lg">
                                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                                        <h5 class="text-lg font-semibold text-gray-800 mb-0">معلومات الفترة</h5>
                                    </div>
                                    <div class="p-6">
                                        <div class="mb-4">
                                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم الفترة <span class="text-red-500">*</span></label>
                                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror" 
                                                   id="name" name="name" value="{{ old('name') }}" required>
                                            @error('name')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                            <p class="mt-1 text-sm text-gray-500">مثال: السنة المالية 2024، الربع الأول 2024</p>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ البداية <span class="text-red-500">*</span></label>
                                                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-500 @enderror" 
                                                       id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                                @error('start_date')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ النهاية <span class="text-red-500">*</span></label>
                                                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_date') border-red-500 @enderror" 
                                                       id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                                @error('end_date')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">وصف الفترة</label>
                                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror" 
                                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                            @error('description')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- إعدادات إضافية -->
                            <div>
                                <div class="bg-white border border-gray-200 rounded-lg">
                                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                                        <h5 class="text-lg font-semibold text-gray-800 mb-0">الإعدادات</h5>
                                    </div>
                                    <div class="p-6">
                                        <div class="mb-4">
                                            <div class="flex items-center">
                                                <input class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" type="checkbox" id="is_current" name="is_current" value="1" 
                                                       {{ old('is_current') ? 'checked' : '' }}>
                                                <label class="mr-2 block text-sm text-gray-900" for="is_current">
                                                    فترة حالية
                                                </label>
                                            </div>
                                            <p class="mt-1 text-sm text-gray-500">تحديد هذه الفترة كفترة محاسبية حالية</p>
                                        </div>

                                        <div class="mb-4">
                                            <div class="flex items-center">
                                                <input class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" type="checkbox" id="auto_close" name="auto_close" value="1" 
                                                       {{ old('auto_close') ? 'checked' : '' }}>
                                                <label class="mr-2 block text-sm text-gray-900" for="auto_close">
                                                    إغلاق تلقائي
                                                </label>
                                            </div>
                                            <p class="mt-1 text-sm text-gray-500">إغلاق الفترة تلقائياً عند انتهاء تاريخ النهاية</p>
                                        </div>

                                        <div class="mb-4">
                                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">حالة الفترة</label>
                                            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror" 
                                                    id="status" name="status">
                                                <option value="open" {{ old('status', 'open') == 'open' ? 'selected' : '' }}>مفتوحة</option>
                                                <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>مغلقة</option>
                                                <option value="locked" {{ old('status') == 'locked' ? 'selected' : '' }}>مقفلة</option>
                                            </select>
                                            @error('status')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- ملخص الفترة -->
                                <div class="bg-white border border-gray-200 rounded-lg mt-4">
                                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                                        <h5 class="text-lg font-semibold text-gray-800 mb-0">ملخص الفترة</h5>
                                    </div>
                                    <div class="p-6">
                                        <div class="mb-3">
                                            <p class="text-sm text-gray-500">المدة:</p>
                                            <div id="duration-display" class="font-semibold text-gray-900">-</div>
                                        </div>
                                        <div class="mb-3">
                                            <p class="text-sm text-gray-500">الأيام المتبقية:</p>
                                            <div id="remaining-days" class="font-semibold text-gray-900">-</div>
                                        </div>
                                        <div class="mb-3">
                                            <p class="text-sm text-gray-500">نسبة الإنجاز:</p>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div id="progress-bar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- أزرار الإجراءات -->
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-lg mt-6">
                            <div class="flex justify-end space-x-4 rtl:space-x-reverse">
                                <a href="{{ route('facility.accounting.accounting-periods.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                    <i class="fas fa-times"></i>
                                    <span>إلغاء</span>
                                </a>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-colors">
                                    <i class="fas fa-save"></i>
                                    <span>حفظ الفترة</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Calculate duration and remaining days
    function calculateDuration() {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const today = new Date();
            
            // Calculate duration in days
            const timeDiff = end.getTime() - start.getTime();
            const durationDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
            
            // Calculate remaining days
            const remainingTime = end.getTime() - today.getTime();
            const remainingDays = Math.ceil(remainingTime / (1000 * 3600 * 24));
            
            // Update display
            document.getElementById('duration-display').textContent = durationDays + ' يوم';
            document.getElementById('remaining-days').textContent = remainingDays + ' يوم';
            
            // Calculate progress
            const totalDays = durationDays;
            const passedDays = Math.max(0, Math.ceil((today.getTime() - start.getTime()) / (1000 * 3600 * 24)));
            const progress = Math.min(100, Math.max(0, (passedDays / totalDays) * 100));
            
            const progressBar = document.getElementById('progress-bar');
            progressBar.style.width = progress + '%';
            progressBar.textContent = Math.round(progress) + '%';
            
            // Color coding
            if (progress < 25) {
                progressBar.className = 'bg-green-600 h-2 rounded-full transition-all duration-300';
            } else if (progress < 75) {
                progressBar.className = 'bg-yellow-600 h-2 rounded-full transition-all duration-300';
            } else {
                progressBar.className = 'bg-red-600 h-2 rounded-full transition-all duration-300';
            }
        } else {
            document.getElementById('duration-display').textContent = '-';
            document.getElementById('remaining-days').textContent = '-';
            document.getElementById('progress-bar').style.width = '0%';
        }
    }

    // Add event listeners
    document.getElementById('start_date').addEventListener('change', calculateDuration);
    document.getElementById('end_date').addEventListener('change', calculateDuration);

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const name = document.getElementById('name').value;
        
        if (!name || !startDate || !endDate) {
            e.preventDefault();
            alert('يرجى ملء جميع الحقول المطلوبة');
            return false;
        }
        
        if (new Date(startDate) >= new Date(endDate)) {
            e.preventDefault();
            alert('تاريخ البداية يجب أن يكون قبل تاريخ النهاية');
            return false;
        }
    });

    // Auto-fill end date based on start date
    document.getElementById('start_date').addEventListener('change', function() {
        const startDate = new Date(this.value);
        if (startDate) {
            // Suggest end date (e.g., one year later)
            const endDate = new Date(startDate);
            endDate.setFullYear(endDate.getFullYear() + 1);
            endDate.setDate(endDate.getDate() - 1);
            
            document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
            calculateDuration();
        }
    });
</script>
@endpush

