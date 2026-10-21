<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.maintenance.request_title') }} <span class="text-red-500">*</span></label>
        <input type="text" name="title" value="{{ old('title', $maintenance->title ?? '') }}" required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @error('title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.maintenance.product') }}</label>
        <select name="product_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">{{ __('facility.maintenance.select_product') }}</option>
            @foreach($products as $id => $name)
                <option value="{{ $id }}" {{ old('product_id', $maintenance->product_id ?? request('product_id')) == $id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        @error('product_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.maintenance.tenant') }}</label>
        <select name="tenant_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">{{ __('facility.maintenance.select_tenant') }}</option>
            @foreach($tenants as $id => $name)
                <option value="{{ $id }}" {{ old('tenant_id', $maintenance->tenant_id ?? '') == $id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        @error('tenant_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.maintenance.type') }} <span class="text-red-500">*</span></label>
        <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @foreach(\App\Models\MaintenanceRequest::TYPES as $type)
                <option value="{{ $type }}" {{ old('type', $maintenance->type ?? 'maintenance') === $type ? 'selected' : '' }}>{{ __('facility.maintenance.types.' . $type) }}</option>
            @endforeach
        </select>
        @error('type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.maintenance.priority') }} <span class="text-red-500">*</span></label>
        <select name="priority" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @foreach(\App\Models\MaintenanceRequest::PRIORITIES as $priority)
                <option value="{{ $priority }}" {{ old('priority', $maintenance->priority ?? 'medium') === $priority ? 'selected' : '' }}>{{ __('facility.maintenance.priorities.' . $priority) }}</option>
            @endforeach
        </select>
        @error('priority') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.maintenance.assigned_to') }}</label>
        <select name="assigned_to" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">{{ __('facility.maintenance.unassigned') }}</option>
            @foreach($users as $id => $name)
                <option value="{{ $id }}" {{ old('assigned_to', $maintenance->assigned_to ?? '') == $id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        @error('assigned_to') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.maintenance.scheduled_at') }}</label>
        <input type="date" name="scheduled_at" value="{{ old('scheduled_at', isset($maintenance->scheduled_at) ? $maintenance->scheduled_at->format('Y-m-d') : '') }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @error('scheduled_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.maintenance.cost') }}</label>
        <input type="number" step="0.01" min="0" name="cost" value="{{ old('cost', $maintenance->cost ?? '') }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @error('cost') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    @if(isset($maintenance))
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.maintenance.status') }}</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @foreach(\App\Models\MaintenanceRequest::STATUSES as $status)
                    <option value="{{ $status }}" {{ old('status', $maintenance->status) === $status ? 'selected' : '' }}>{{ __('facility.maintenance.statuses.' . $status) }}</option>
                @endforeach
            </select>
            @error('status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
    @endif

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('facility.maintenance.description') }}</label>
        <textarea name="description" rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $maintenance->description ?? '') }}</textarea>
        @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>
</div>
