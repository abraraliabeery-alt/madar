@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('admin.roles.create') }}</h5>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>{{ __('admin.roles.back') }}
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('admin.roles.basic_info') }}</h6>
                            </div>
                            <div class="card-body">
                                @include('components.translations-repeater', [
                                    'locales' => $locales ?? config('locales.available', []),
                                    'namePrefix' => 'translations',
                                    'fields' => [
                                        [
                                            'type' => 'input',
                                            'key' => 'name',
                                            'label' => __('admin.roles.name'),
                                            'requiredFirst' => true,
                                        ],
                                        [
                                            'type' => 'input',
                                            'key' => 'display_name',
                                            'label' => __('admin.roles.display_name'),
                                        ],
                                        [
                                            'type' => 'textarea',
                                            'key' => 'description',
                                            'label' => __('admin.roles.description'),
                                            'rows' => 3,
                                        ],
                                    ],
                                    'addLabel' => __('admin.ui.layout.add_new'),
                                    'removeLabel' => __('admin.actions.delete'),
                                    'minItems' => 1,
                                    'maxItems' => is_array($locales ?? null) ? count($locales) : null,
                                ])
                            </div>
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="col-md-12 mt-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">{{ __('admin.roles.permissions') }}</h6>
                                <div>
                                    <button type="button" class="btn btn-sm btn-secondary" id="selectAll">{{ __('admin.roles.select_all') }}</button>
                                    <button type="button" class="btn btn-sm btn-secondary" id="deselectAll">{{ __('admin.roles.deselect_all') }}</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @foreach($permissions->groupBy(function ($permission) {
                                        $name = (string) ($permission->name ?? '');
                                        return str_contains($name, '.') ? explode('.', $name, 2)[0] : __('admin.roles.general');
                                    }) as $group => $groupPermissions)
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6 class="mb-0">{{ $group }}</h6>
                                                </div>
                                                <div class="card-body">
                                                    @foreach($groupPermissions as $permission)
                                                        <div class="form-check mb-2">
                                                            <input type="checkbox" class="form-check-input permission-checkbox" id="permission_{{ $permission->id }}" name="permissions[]" value="{{ $permission->id }}" {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                                {{ $permission->getTranslatedDisplayName() }}
                                                            </label>
                                                            @if($permission->getTranslatedDescription())
                                                                <small class="text-muted d-block">{{ $permission->getTranslatedDescription() }}</small>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ __('admin.roles.save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Select/Deselect all permissions
    $('#selectAll').click(function() {
        $('.permission-checkbox').prop('checked', true);
    });

    $('#deselectAll').click(function() {
        $('.permission-checkbox').prop('checked', false);
    });
});
</script>
@endpush
