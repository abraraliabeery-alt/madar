@extends('client.layouts.app')

@section('title', 'ط¥ظ†ط´ط§ط، ظ…ط´ط±ظˆط¹')

@section('content')
<x-bs.card title="ط¥ظ†ط´ط§ط، ظ…ط´ط±ظˆط¹">
    <x-slot name="actions">
        <a href="{{ route('client.dashboard') }}" class="btn btn-light btn-sm">ط±ط¬ظˆط¹</a>
    </x-slot>
    <form method="POST" action="{{ route('client.projects.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="card mb-4">
            <div class="card-header">
                <div class="fw-semibold">ط§ظ„ط¨ظٹط§ظ†ط§طھ ط§ظ„ط£ط³ط§ط³ظٹط©</div>
            </div>
            <div class="card-body">
                <div class="text-muted small mb-3">
                    <div>ط§ظ„ظ…طµط¯ط±: ط¬ط¯ظˆظ„ طھط±ط¬ظ…ط§طھ ط§ظ„ظ…ط´ط§ط±ظٹط¹ âں¶ ظ…ط±طھط¨ط· ط¨ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹</div>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">طµظˆط±ط© ط§ظ„ظ…ط´ط±ظˆط¹</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ط§ظ„طµظˆط±ط©)</div>
                    <div id="image-preview-wrap" class="mt-2" style="display:none;">
                        <img id="image-preview" src="" alt="ظ…ط¹ط§ظٹظ†ط© طµظˆط±ط© ط§ظ„ظ…ط´ط±ظˆط¹" class="border" style="width: 120px; height: 120px; border-radius: 50%; object-fit: contain; background: #fff;">
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">ط§ظ„ظ…ط³ط§ط¹ط¯ ط§ظ„طµظˆطھظٹ</div>
                        <div class="text-muted small mb-2">طھط­ط¯ط« ط¨ط­ط±ظٹط© ط¹ظ† ظ…ط´ط±ظˆط¹ظƒطŒ ط«ظ… ط§ط¶ط؛ط· طھط­ظ„ظٹظ„ ظˆطھط¹ط¨ط¦ط© ظ„طھط¹ط¨ط¦ط© ط§ظ„ط­ظ‚ظˆظ„ طھظ„ظ‚ط§ط¦ظٹط§ظ‹.</div>
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <button type="button" id="voice-start" class="btn btn-danger btn-sm">ًںژ™ï¸ڈ ط§ط¨ط¯ط£ ط§ظ„طھط­ط¯ط«</button>
                            <button type="button" id="voice-stop" class="btn btn-outline-secondary btn-sm" disabled>â–  ط¥ظٹظ‚ط§ظپ</button>
                            <span id="voice-status" class="text-muted small"></span>
                        </div>

                        <div class="mt-3">
                            <label for="voice-transcript" class="form-label">ط§ظ„ظ†طµ ط§ظ„ظ…ط­ظˆظ‘ظ„ ظ…ظ† ط§ظ„طµظˆطھ</label>
                            <textarea id="voice-transcript" rows="3" class="form-control" placeholder="ظ…ط«ط§ظ„: ط£ط¨ط؛ظ‰ ط¨ظ†ط§ط، ظپظٹظ„ط§ ط³ظƒظ†ظٹط© ط¯ظˆط±ظٹظ†طŒ ظ…ط³ط§ط­ط© ط§ظ„ط£ط±ط¶ 400طŒ ط§ظ„ط¹ط¸ظ… ظˆط§ظ„ط³ط¨ط§ظƒط© ظˆط§ظ„ظƒظ‡ط±ط¨ط§ط،... ظ…ظٹط²ط§ظ†ظٹطھظٹ ط¨ظٹظ† 600 ط¥ظ„ظ‰ 800 ط£ظ„ظپ ط®ظ„ط§ظ„ 5 ط´ظ‡ظˆط±"></textarea>
                        </div>

                        <div class="mt-3 d-flex flex-wrap gap-2 align-items-center">
                            <button type="button" id="voice-analyze" class="btn btn-dark btn-sm">طھط­ظ„ظٹظ„ ظˆطھط¹ط¨ط¦ط©</button>
                            <button type="button" id="voice-clear" class="btn btn-outline-secondary btn-sm">ظ…ط³ط­</button>
                            <button type="button" id="voice-undo" class="btn btn-warning btn-sm" disabled>طھط±ط§ط¬ط¹</button>
                            <span id="voice-analyze-status" class="text-muted small"></span>
                        </div>
                    </div>
                </div>

                @include('components.translations-repeater', [
                    'locales' => $locales,
                    'namePrefix' => 'translations',
                    'fields' => [
                        [
                            'type' => 'input',
                            'key' => 'name',
                            'label' => 'ط§ط³ظ… ط§ظ„ظ…ط´ط±ظˆط¹',
                            'requiredFirst' => true,
                        ],
                        [
                            'type' => 'textarea',
                            'key' => 'description',
                            'label' => 'ظˆطµظپ ط§ظ„ظ…ط´ط±ظˆط¹',
                            'rows' => 4,
                        ],
                    ],
                    'addLabel' => 'ط¥ط¶ط§ظپط© طھط±ط¬ظ…ط©',
                    'removeLabel' => 'ط­ط°ظپ',
                    'minItems' => 1,
                    'maxItems' => is_array($locales) ? count($locales) : null,
                ])
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <div class="fw-semibold">ط§ظ„ظ…ظˆظ‚ط¹</div>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <x-bs.select
                            name="city_id"
                            label="ط§ظ„ظ…ط¯ظٹظ†ط©"
                            :options="$cities"
                            option-label="localized_name"
                            placeholder="ط§ط®طھط± ط§ظ„ظ…ط¯ظٹظ†ط©"
                        />
                        <div class="form-text">ط§ظ„ظ…طµط¯ط±: ط¬ط¯ظˆظ„ ط§ظ„ظ…ط¯ظ† âں¶ ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ط§ظ„ظ…ط¯ظٹظ†ط©)</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <x-bs.select
                            name="neighborhood_id"
                            label="ط§ظ„ط­ظٹ"
                            :options="$neighborhoods"
                            placeholder="ط§ط®طھط± ط§ظ„ط­ظٹ"
                        />
                        <div class="form-text">ط§ظ„ظ…طµط¯ط±: ط¬ط¯ظˆظ„ ط§ظ„ط£ط­ظٹط§ط، âں¶ ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ط§ظ„ط­ظٹ)</div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <x-bs.select
                            name="street_id"
                            label="ط§ظ„ط´ط§ط±ط¹"
                            :options="$streets"
                            placeholder="ط§ط®طھط± ط§ظ„ط´ط§ط±ط¹"
                        />
                        <div class="form-text">ط§ظ„ظ…طµط¯ط±: ط¬ط¯ظˆظ„ ط§ظ„ط´ظˆط§ط±ط¹ âں¶ ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ط§ظ„ط´ط§ط±ط¹)</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <x-bs.input name="address" label="ط§ظ„ط¹ظ†ظˆط§ظ†" />
                        <div class="form-text">ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ط§ظ„ط¹ظ†ظˆط§ظ†)</div>
                    </div>
                </div>

                <div class="mt-2">
                    <div class="fw-semibold mb-2">ط§ظ„ظ…ظˆظ‚ط¹ ط¹ظ„ظ‰ ط§ظ„ط®ط±ظٹط·ط©</div>
                    <div class="text-muted small mb-3">ط§ط¨ط­ط« ط¹ظ† ط§ظ„ظ…ظˆظ‚ط¹ ط£ظˆ ط­ط±ظ‘ظƒ ط§ظ„ظ…ط¤ط´ط± ط¹ظ„ظ‰ ط§ظ„ط®ط±ظٹط·ط© ظ„طھط­ط¯ظٹط« ط§ظ„ط¥ط­ط¯ط§ط«ظٹط§طھ طھظ„ظ‚ط§ط¦ظٹط§ظ‹.</div>

                    <div class="row g-2 align-items-center mb-2">
                        <div class="col-12 col-lg">
                            <input id="project-map-search" type="text" class="form-control" placeholder="ط§ط¨ط­ط« ط¹ظ† ط§ظ„ظ…ط¯ظٹظ†ط© / ط§ظ„ط­ظٹ / ط§ط³ظ… ط§ظ„ط´ط§ط±ط¹" autocomplete="off" />
                        </div>
                        <div class="col-12 col-lg-auto d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary" id="project-map-search-btn">ط¨ط­ط«</button>
                            <button type="button" class="btn btn-outline-secondary" id="project-map-locate-btn">ط§ط³طھط®ط¯ظ… ظ…ظˆظ‚ط¹ظٹ</button>
                            <a href="#" class="btn btn-outline-secondary" id="project-map-open-gmaps" target="_blank" rel="noopener">ظپطھط­ ظپظٹ ط®ط±ط§ط¦ط· Google</a>
                        </div>
                    </div>

                    <div id="project-map" class="border rounded" style="height: 340px;"></div>

                    <div class="row g-3 mt-3">
                        <div class="col-12 col-md-6">
                            <label for="latitude" class="form-label">ط®ط· ط§ظ„ط¹ط±ط¶</label>
                            <input
                                type="number"
                                step="any"
                                class="form-control @error('latitude') is-invalid @enderror"
                                id="latitude"
                                name="latitude"
                                value="{{ old('latitude') }}"
                                readonly
                            >
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ط®ط· ط§ظ„ط¹ط±ط¶)</div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="longitude" class="form-label">ط®ط· ط§ظ„ط·ظˆظ„</label>
                            <input
                                type="number"
                                step="any"
                                class="form-control @error('longitude') is-invalid @enderror"
                                id="longitude"
                                name="longitude"
                                value="{{ old('longitude') }}"
                                readonly
                            >
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ط®ط· ط§ظ„ط·ظˆظ„)</div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="google_maps_url" class="form-label">ط±ط§ط¨ط· ط®ط±ط§ط¦ط· ط¬ظˆط¬ظ„</label>
                        <input
                            type="url"
                            class="form-control @error('google_maps_url') is-invalid @enderror"
                            id="google_maps_url"
                            name="google_maps_url"
                            value="{{ old('google_maps_url') }}"
                            readonly
                        >
                        @error('google_maps_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ط±ط§ط¨ط· ط®ط±ط§ط¦ط· ط¬ظˆط¬ظ„)</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <div class="fw-semibold">طھظپط§طµظٹظ„ ط§ظ„ط·ظ„ط¨</div>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <x-bs.select
                            name="project_type"
                            label="ظ†ظˆط¹ ط§ظ„ظ…ط´ط±ظˆط¹"
                            :options="[
                                ['id' => 'residential', 'name' => 'ط³ظƒظ†ظٹ'],
                                ['id' => 'commercial', 'name' => 'طھط¬ط§ط±ظٹ'],
                                ['id' => 'industrial', 'name' => 'طµظ†ط§ط¹ظٹ'],
                                ['id' => 'government', 'name' => 'ط­ظƒظˆظ…ظٹ/ظ…ط¤ط³ط³ظٹ'],
                                ['id' => 'other', 'name' => 'ط£ط®ط±ظ‰'],
                            ]"
                            option-value="id"
                            option-label="name"
                            placeholder="ط§ط®طھط±"
                        />
                        <div class="form-text">ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ظ†ظˆط¹ ط§ظ„ظ…ط´ط±ظˆط¹)</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <x-bs.select
                            name="project_category_id"
                            label="طھطµظ†ظٹظپ ط§ظ„ظ…ط´ط±ظˆط¹"
                            :options="($projectCategories ?? [])"
                            option-value="id"
                            option-label="translated_name"
                            placeholder="ط§ط®طھط±"
                        />
                        <div class="form-text">ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ طھطµظ†ظٹظپ ط§ظ„ظ…ط´ط±ظˆط¹)</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <x-bs.select
                            name="request_type"
                            label="ظ†ظˆط¹ ط§ظ„ط·ظ„ط¨"
                            :options="[
                                ['id' => 'build', 'name' => 'ط¨ظ†ط§ط، ط¬ط¯ظٹط¯'],
                                ['id' => 'renovation', 'name' => 'طھط±ظ…ظٹظ…'],
                                ['id' => 'finishing', 'name' => 'طھط´ط·ظٹط¨'],
                                ['id' => 'extension', 'name' => 'ط¥ط¶ط§ظپط©/ظ…ظ„ط­ظ‚'],
                            ]"
                            option-value="id"
                            option-label="name"
                            placeholder="ط§ط®طھط±"
                        />
                        <div class="form-text">ظ‚ط§ط¦ظ…ط© ط«ط§ط¨طھط© âں¶ ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ظ†ظˆط¹ ط§ظ„ط·ظ„ط¨)</div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <x-bs.select
                            name="scope_of_work"
                            label="ظ†ط·ط§ظ‚ ط§ظ„ط¹ظ…ظ„"
                            :options="[
                                ['id' => 'full', 'name' => 'ظƒط§ظ…ظ„'],
                                ['id' => 'structure', 'name' => 'ط¹ط¸ظ…'],
                                ['id' => 'finishing', 'name' => 'طھط´ط·ظٹط¨'],
                                ['id' => 'mep', 'name' => 'ظƒظ‡ط±ط¨ط§ط،/ط³ط¨ط§ظƒط©'],
                            ]"
                            option-value="id"
                            option-label="name"
                            placeholder="ط§ط®طھط±"
                        />
                        <div class="form-text">ظ‚ط§ط¦ظ…ط© ط«ط§ط¨طھط© âں¶ ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ظ†ط·ط§ظ‚ ط§ظ„ط¹ظ…ظ„)</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <x-bs.select
                            name="finishing_level"
                            label="ظ…ط³طھظˆظ‰ ط§ظ„طھط´ط·ظٹط¨"
                            :options="[
                                ['id' => 'economic', 'name' => 'ط§ظ‚طھطµط§ط¯ظٹ'],
                                ['id' => 'standard', 'name' => 'ظ…طھظˆط³ط·'],
                                ['id' => 'luxury', 'name' => 'ظپط§ط®ط±'],
                            ]"
                            option-value="id"
                            option-label="name"
                            placeholder="ط§ط®طھط±"
                        />
                        <div class="form-text">ظ‚ط§ط¦ظ…ط© ط«ط§ط¨طھط© âں¶ ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ظ…ط³طھظˆظ‰ ط§ظ„طھط´ط·ظٹط¨)</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <div class="fw-semibold">ظ…ظˆط§طµظپط§طھ ط§ظ„ظ…ط´ط±ظˆط¹</div>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <x-bs.input type="number" step="0.01" name="land_area" label="ظ…ط³ط§ط­ط© ط§ظ„ط£ط±ط¶ (ظ…آ²)" />
                        <div class="form-text">ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ظ…ط³ط§ط­ط© ط§ظ„ط£ط±ط¶)</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <x-bs.input type="number" step="0.01" name="built_area" label="ط§ظ„ظ…ط³ط§ط­ط© ط§ظ„ظ…ط¨ظ†ظٹط© (ظ…آ²)" />
                        <div class="form-text">ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ط§ظ„ظ…ط³ط§ط­ط© ط§ظ„ظ…ط¨ظ†ظٹط©)</div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <x-bs.input type="number" name="floors_count" label="ط¹ط¯ط¯ ط§ظ„ط£ط¯ظˆط§ط±" />
                        <div class="form-text">ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ط¹ط¯ط¯ ط§ظ„ط£ط¯ظˆط§ط±)</div>
                    </div>
                    <div class="col-12 col-md-4">
                        <x-bs.input type="number" name="rooms_count" label="ط¹ط¯ط¯ ط§ظ„ط؛ط±ظپ" />
                        <div class="form-text">ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ط¹ط¯ط¯ ط§ظ„ط؛ط±ظپ)</div>
                    </div>
                    <div class="col-12 col-md-4">
                        <x-bs.input type="number" name="bathrooms_count" label="ط¹ط¯ط¯ ط¯ظˆط±ط§طھ ط§ظ„ظ…ظٹط§ظ‡" />
                        <div class="form-text">ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ط¹ط¯ط¯ ط¯ظˆط±ط§طھ ط§ظ„ظ…ظٹط§ظ‡)</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <div class="fw-semibold">ط§ظ„ظ…ظٹط²ط§ظ†ظٹط© ظˆط§ظ„ط¬ط¯ظˆظ„ ط§ظ„ط²ظ…ظ†ظٹ</div>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <x-bs.select
                            id="project_status"
                            name="status"
                            label="ط­ط§ظ„ط© ط§ظ„ظ†ط´ط±"
                            :options="[
                                ['id' => 'draft', 'name' => 'ظ…ط³ظˆط¯ط©'],
                                ['id' => 'open_for_bids', 'name' => 'ظ…ظپطھظˆط­ ظ„ظ„ط¹ط±ظˆط¶'],
                            ]"
                            option-value="id"
                            option-label="name"
                            placeholder="ط§ط®طھط±"
                        />
                        <div class="form-text">ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ط­ط§ظ„ط© ط§ظ„ظ†ط´ط±). ظˆط¹ظ†ط¯ ظپطھط­ظ‡ ظ„ظ„ط¹ط±ظˆط¶ ظٹطھظ… ط¥ظ†ط´ط§ط، ط³ط¬ظ„ ظپظٹ ط¬ط¯ظˆظ„ ط·ظ„ط¨ط§طھ ط§ظ„طھظ†ظپظٹط°.</div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <x-bs.input type="number" step="0.01" name="budget_min" label="ط§ظ„ظ…ظٹط²ط§ظ†ظٹط© ط§ظ„ط¯ظ†ظٹط§" />
                        <div class="form-text">ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ط§ظ„ظ…ظٹط²ط§ظ†ظٹط© ط§ظ„ط¯ظ†ظٹط§) + ظ†ط³ط®ط© ظ„ظ„ط³ظˆظ‚ ظپظٹ ط¬ط¯ظˆظ„ ط·ظ„ط¨ط§طھ ط§ظ„طھظ†ظپظٹط° (ط­ظ‚ظ„ ط§ظ„ظ…ظٹط²ط§ظ†ظٹط© ط§ظ„ط¯ظ†ظٹط§)</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <x-bs.input type="number" step="0.01" name="budget_max" label="ط§ظ„ظ…ظٹط²ط§ظ†ظٹط© ط§ظ„ظ‚طµظˆظ‰" />
                        <div class="form-text">ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ط§ظ„ظ…ظٹط²ط§ظ†ظٹط© ط§ظ„ظ‚طµظˆظ‰) + ظ†ط³ط®ط© ظ„ظ„ط³ظˆظ‚ ظپظٹ ط¬ط¯ظˆظ„ ط·ظ„ط¨ط§طھ ط§ظ„طھظ†ظپظٹط° (ط­ظ‚ظ„ ط§ظ„ظ…ظٹط²ط§ظ†ظٹط© ط§ظ„ظ‚طµظˆظ‰)</div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <x-bs.input type="date" name="start_date" label="طھط§ط±ظٹط® ط§ظ„ط¨ط¯ط، ط§ظ„ظ…طھظˆظ‚ط¹" />
                        <div class="form-text">ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ طھط§ط±ظٹط® ط§ظ„ط¨ط¯ط، ط§ظ„ظ…طھظˆظ‚ط¹)</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <x-bs.input type="number" name="duration_days" label="ظ…ط¯ط© ط§ظ„طھظ†ظپظٹط° (ط¨ط§ظ„ط£ظٹط§ظ…)" />
                        <div class="form-text">ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ظ…ط¯ط© ط§ظ„طھظ†ظپظٹط°)</div>
                    </div>
                </div>

                <div id="tender-deadlines" class="row g-3 mt-1">
                    <div class="col-12 col-md-4">
                        <x-bs.input type="date" name="bid_deadline" label="ط¢ط®ط± ظ…ظˆط¹ط¯ ظ„ط§ط³طھظ„ط§ظ… ط§ظ„ط¹ط±ظˆط¶" />
                        <div class="form-text">ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ط¢ط®ط± ظ…ظˆط¹ط¯ ظ„ظ„ط¹ط±ظˆط¶) + ظ„ظ„ط³ظˆظ‚ ظپظٹ ط¬ط¯ظˆظ„ ط·ظ„ط¨ط§طھ ط§ظ„طھظ†ظپظٹط° (ط­ظ‚ظ„ ظ…ظˆط¹ط¯ ط§ظ„ط¥ط؛ظ„ط§ظ‚)</div>
                    </div>
                    <div class="col-12 col-md-4">
                        <x-bs.input type="date" name="qa_deadline" label="ط¢ط®ط± ظ…ظˆط¹ط¯ ظ„ظ„ط§ط³طھظپط³ط§ط±ط§طھ" />
                        <div class="form-text">ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ط¢ط®ط± ظ…ظˆط¹ط¯ ظ„ظ„ط§ط³طھظپط³ط§ط±ط§طھ)</div>
                    </div>
                    <div class="col-12 col-md-4">
                        <x-bs.input type="date" name="site_visit_date" label="ظ…ظˆط¹ط¯ ط§ظ„ظ…ط¹ط§ظٹظ†ط© (ط§ط®طھظٹط§ط±ظٹ)" />
                        <div class="form-text">ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ظ…ظˆط¹ط¯ ط§ظ„ظ…ط¹ط§ظٹظ†ط©)</div>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($projectAttributes) && $projectAttributes->count())
            <div class="card mb-4">
                <div class="card-header">
                    <div class="fw-semibold">ط®طµط§ط¦طµ ط§ظ„ظ…ط´ط±ظˆط¹ (ط¹ط§ظ…)</div>
                </div>
                <div class="card-body">
                    <div class="text-muted small mb-3">ط§ظ„ظ…طµط¯ط±: ط¬ط¯ظˆظ„ ط§ظ„ط®طµط§ط¦طµ + ط¬ط¯ظˆظ„ طھط±ط¬ظ…ط§طھ ط§ظ„ط®طµط§ط¦طµ âں¶ ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط±ط¨ط· ظ…ط±طھط¨ط· ط¨ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹</div>
                    <div class="vstack gap-3">
                        @foreach($projectAttributes as $attribute)
                            @php
                                $attrName = optional($attribute->translations->firstWhere('locale', app()->getLocale()))->name
                                    ?? optional($attribute->translations->first())->name
                                    ?? ('Attribute #'.$attribute->id);
                                $fieldName = 'attributes['.$attribute->id.'][value]';
                                $oldValue = old('attributes.'.$attribute->id.'.value');
                            @endphp
                            <div>
                                <label class="form-label" for="project-attr-{{ $attribute->id }}">
                                    {{ $attrName }}@if($attribute->required) <span class="text-danger">*</span>@endif
                                </label>
                                <input id="project-attr-{{ $attribute->id }}" type="text" name="{{ $fieldName }}" value="{{ $oldValue }}" class="form-control" />
                                @error('attributes.'.$attribute->id.'.value')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if(isset($ideaStageAttributes) && $ideaStageAttributes->count())
            <div class="card mb-4">
                <div class="card-header">
                    <div class="fw-semibold">ط®طµط§ط¦طµ ظ…ط±ط­ظ„ط© ط§ظ„ظپظƒط±ط©</div>
                </div>
                <div class="card-body">
                    <div class="text-muted small mb-3">ط§ظ„ظ…طµط¯ط±: ط¬ط¯ظˆظ„ ط®طµط§ط¦طµ ط§ظ„ظ…ط±ط§ط­ظ„ + ط¬ط¯ظˆظ„ طھط±ط¬ظ…ط§طھ ط®طµط§ط¦طµ ط§ظ„ظ…ط±ط§ط­ظ„ âں¶ ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط±ط¨ط· ظ…ط±طھط¨ط· ط¨ط¬ط¯ظˆظ„ ظ…ط±ط§ط­ظ„ ط§ظ„ظ…ط´ط±ظˆط¹</div>
                    <div class="vstack gap-3">
                        @foreach($ideaStageAttributes as $attribute)
                            @php
                                $attrName = optional($attribute->translations->firstWhere('locale', app()->getLocale()))->name
                                    ?? optional($attribute->translations->first())->name
                                    ?? ('Attribute #'.$attribute->id);
                                $fieldName = 'stage_attributes['.$attribute->id.']';
                                $oldValue = old('stage_attributes.'.$attribute->id);
                            @endphp
                            <div>
                                <label class="form-label" for="stage-attr-{{ $attribute->id }}">
                                    {{ $attrName }}@if($attribute->required) <span class="text-danger">*</span>@endif
                                </label>
                                @if($attribute->type === 'text')
                                    <textarea id="stage-attr-{{ $attribute->id }}" name="{{ $fieldName }}" rows="3" class="form-control">{{ $oldValue }}</textarea>
                                @else
                                    <input id="stage-attr-{{ $attribute->id }}" type="text" name="{{ $fieldName }}" value="{{ $oldValue }}" class="form-control" />
                                @endif
                                @error('stage_attributes.'.$attribute->id)
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">
                <div class="fw-semibold">ظ…طھط·ظ„ط¨ط§طھ ظˆظ…ط±ظپظ‚ط§طھ</div>
            </div>
            <div class="card-body">
                <div id="requirements-section" class="mb-3">
                    <div class="alert alert-light border mb-3">
                        <div class="fw-semibold mb-1">ظ…ط³ط§ط¹ط¯ط© ظ„ظƒطھط§ط¨ط© ظ…طھط·ظ„ط¨ط§طھ ظˆط§ط¶ط­ط©</div>
                        <div class="text-muted small">ط¹ط¨ظ‘ط¦ ط§ظ„ط­ظ‚ظˆظ„ ط§ظ„طھط§ظ„ظٹط© ظˆط³ظٹطھظ… طھظƒظˆظٹظ† ظ†طµ ظ…ط±طھط¨ طھظ„ظ‚ط§ط¦ظٹظ‹ط§طŒ ظˆظٹظ…ظƒظ†ظƒ طھط¹ط¯ظٹظ„ظ‡ ظ‚ط¨ظ„ ط§ظ„ط­ظپط¸.</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="req_scope" class="form-label">ظ…ط§ ط§ظ„ط°ظٹ طھط±ظٹط¯ طھظ†ظپظٹط°ظ‡طں</label>
                            <input type="text" id="req_scope" class="form-control" placeholder="ظ…ط«ط§ظ„: ط¨ظ†ط§ط، ط¯ظˆط±ظٹظ† ظˆظ…ظ„ط­ظ‚ + طھط´ط·ظٹط¨ ظƒط§ظ…ظ„" />
                            <div class="form-text">ظٹط³ط§ط¹ط¯ ط§ظ„ظ…ظ†ظپط° ط¹ظ„ظ‰ ظپظ‡ظ… ظ†ط·ط§ظ‚ ط§ظ„ط¹ظ…ظ„ ط¨ط³ط±ط¹ط©.</div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="req_materials" class="form-label">ط§ظ„ظ…ظˆط§ط¯/ط§ظ„ظ…ط§ط±ظƒط§طھ ط§ظ„ظ…ط·ظ„ظˆط¨ط© (ط¥ظ† ظˆط¬ط¯طھ)</label>
                            <input type="text" id="req_materials" class="form-control" placeholder="ظ…ط«ط§ظ„: ط¹ط²ظ„ ظ…ط§ط¦ظٹ ظ†ظˆط¹ ظƒط°ط§طŒ ط¯ظ‡ط§ظ† ط¬ظˆطھظ†طŒ ط³ظٹط±ط§ظ…ظٹظƒ ظ…ظ‚ط§ط³..." />
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="req_schedule" class="form-label">ط§ظ„ط¬ط¯ظˆظ„ ط§ظ„ظ…طھظˆظ‚ط¹</label>
                            <input type="text" id="req_schedule" class="form-control" placeholder="ظ…ط«ط§ظ„: ط¨ط¯ط، ط®ظ„ط§ظ„ ط´ظ‡ط±طŒ ظ…ط¯ط© ط§ظ„طھظ†ظپظٹط° 120 ظٹظˆظ…" />
                        </div>

                        <div class="col-12">
                            <label class="form-label">ظ…ط¹ظ„ظˆظ…ط§طھ طھط³ط§ط¹ط¯ ط¹ظ„ظ‰ طھط³ط¹ظٹط± ط£ط¯ظ‚</label>
                            <div class="row g-2">
                                <div class="col-12 col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="req_has_plans">
                                        <label class="form-check-label" for="req_has_plans">ظٹظˆط¬ط¯ ظ…ط®ط·ط·ط§طھ/ط±ط³ظˆظ…ط§طھ ط¬ط§ظ‡ط²ط©</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="req_needs_site_visit">
                                        <label class="form-check-label" for="req_needs_site_visit">ط£ظپط¶ظ„ ظ…ط¹ط§ظٹظ†ط© ظ‚ط¨ظ„ ط§ظ„طھط³ط¹ظٹط±</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="req_include_materials">
                                        <label class="form-check-label" for="req_include_materials">ط§ظ„ط¹ط±ط¶ ظٹط´ظ…ظ„ ط§ظ„ظ…ظˆط§ط¯ ظˆط§ظ„طھظˆط±ظٹط¯</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="req_include_labor">
                                        <label class="form-check-label" for="req_include_labor">ط§ظ„ط¹ط±ط¶ ظٹط´ظ…ظ„ ط§ظ„ط¹ظ…ط§ظ„ط© ظˆط§ظ„طھظ†ظپظٹط°</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="req_notes" class="form-label">ط´ط±ظˆط·/ظ…ظ„ط§ط­ط¸ط§طھ ط®ط§طµط©</label>
                            <textarea id="req_notes" class="form-control" rows="3" placeholder="ظ…ط«ط§ظ„: ط§ظ„ط§ظ„طھط²ط§ظ… ط¨ط§ظ„ط³ظ„ط§ظ…ط©طŒ طھظ†ط¸ظٹظپ ط§ظ„ظ…ظˆظ‚ط¹طŒ ط¶ظ…ط§ظ†... "></textarea>
                        </div>
                    </div>

                    <div class="mt-3">
                        <x-bs.textarea name="requirements" label="ظ†طµ ط§ظ„ظ…طھط·ظ„ط¨ط§طھ (ط³ظٹظڈط­ظپط¸)" rows="6" />
                        <div class="form-text">ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ط§ظ„ظ…طھط·ظ„ط¨ط§طھ)</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="attachments_files" class="form-label">ظ…ط±ظپظ‚ط§طھ (ط±ظپط¹ ظ…ظ„ظپط§طھ)</label>
                    <input type="file" class="form-control @error('attachments_files') is-invalid @enderror" id="attachments_files" name="attachments_files[]" multiple>
                    @error('attachments_files')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('attachments_files.*')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    <div class="form-text">ظٹطھظ… ط­ظپط¸ ط§ظ„ظ…ظ„ظپط§طھ ظپظٹ ط¬ط¯ظˆظ„ ظ…ط±ظپظ‚ط§طھ ط§ظ„ظ…ط´ط§ط±ظٹط¹ ظˆط±ط¨ط·ظ‡ط§ ط¨ط§ظ„ظ…ط´ط±ظˆط¹</div>
                    <div id="attachments-files-list" class="mt-2"></div>
                </div>

                <div>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#legacy-attachments" aria-expanded="false" aria-controls="legacy-attachments">
                        ط¹ظ†ط¯ظٹ ط±ظˆط§ط¨ط· ط¨ط¯ظ„ ط±ظپط¹ ظ…ظ„ظپط§طھ
                    </button>

                    <div id="legacy-attachments" class="collapse mt-3">
                        <label class="form-label">ظ…ط±ظپظ‚ط§طھ (ط±ظˆط§ط¨ط·/ط£ط³ظ…ط§ط، ظ…ظ„ظپط§طھ)</label>
                        <div class="vstack gap-2">
                            @for($i = 0; $i < 3; $i++)
                                <input type="text" name="attachments[]" value="{{ old('attachments.'.$i) }}" class="form-control" placeholder="ظ…ط«ط§ظ„: ط±ط§ط¨ط· Google Drive ط£ظˆ ط§ط³ظ… ظ…ظ„ظپ ظ…ط«ظ„ ظ…ط®ط·ط·.pdf">
                            @endfor
                        </div>
                        <div class="form-text">ط§ظ„ط­ظپط¸ ظپظٹ ط¬ط¯ظˆظ„ ط§ظ„ظ…ط´ط§ط±ظٹط¹ (ط­ظ‚ظ„ ط§ظ„ظ…ط±ظپظ‚ط§طھ) ط¨طµظٹط؛ط© ظ‚ط§ط¦ظ…ط©</div>
                        @error('attachments')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        @error('attachments.*')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-primary">ط­ظپط¸</button>
        </div>
    </form>
</x-bs.card>

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    (function () {
        const statusEl = document.getElementById('project_status');
        const tenderEl = document.getElementById('tender-deadlines');
        const requirementsSectionEl = document.getElementById('requirements-section');

        const imageInput = document.getElementById('image');
        const imagePreviewWrap = document.getElementById('image-preview-wrap');
        const imagePreview = document.getElementById('image-preview');

        const attachmentsInput = document.getElementById('attachments_files');
        const attachmentsList = document.getElementById('attachments-files-list');

        let attachmentsDraft = [];

        const mapEl = document.getElementById('project-map');
        const mapSearchEl = document.getElementById('project-map-search');
        const mapSearchBtn = document.getElementById('project-map-search-btn');
        const mapLocateBtn = document.getElementById('project-map-locate-btn');
        const mapOpenGmaps = document.getElementById('project-map-open-gmaps');

        const latEl = document.getElementById('latitude');
        const lngEl = document.getElementById('longitude');
        const gmapsEl = document.getElementById('google_maps_url');

        const reqScope = document.getElementById('req_scope');
        const reqMaterials = document.getElementById('req_materials');
        const reqSchedule = document.getElementById('req_schedule');
        const reqHasPlans = document.getElementById('req_has_plans');
        const reqNeedsSiteVisit = document.getElementById('req_needs_site_visit');
        const reqIncludeMaterials = document.getElementById('req_include_materials');
        const reqIncludeLabor = document.getElementById('req_include_labor');
        const reqNotes = document.getElementById('req_notes');

        const requirementsTextarea = document.querySelector('textarea[name="requirements"]');

        const voiceStartBtn = document.getElementById('voice-start');
        const voiceStopBtn = document.getElementById('voice-stop');
        const voiceStatusEl = document.getElementById('voice-status');
        const voiceTranscriptEl = document.getElementById('voice-transcript');
        const voiceAnalyzeBtn = document.getElementById('voice-analyze');
        const voiceClearBtn = document.getElementById('voice-clear');
        const voiceUndoBtn = document.getElementById('voice-undo');
        const voiceAnalyzeStatusEl = document.getElementById('voice-analyze-status');

        const projectTypeEl = document.querySelector('select[name="project_type"]');
        const requestTypeEl = document.querySelector('select[name="request_type"]');
        const scopeOfWorkEl = document.querySelector('select[name="scope_of_work"]');
        const finishingLevelEl = document.querySelector('select[name="finishing_level"]');

        const budgetMinEl = document.querySelector('input[name="budget_min"]');
        const budgetMaxEl = document.querySelector('input[name="budget_max"]');
        const durationDaysEl = document.querySelector('input[name="duration_days"]');
        const landAreaEl = document.querySelector('input[name="land_area"]');
        const builtAreaEl = document.querySelector('input[name="built_area"]');
        const floorsCountEl = document.querySelector('input[name="floors_count"]');
        const roomsCountEl = document.querySelector('input[name="rooms_count"]');
        const bathroomsCountEl = document.querySelector('input[name="bathrooms_count"]');

        function syncTenderVisibility() {
            if (!statusEl || !tenderEl) {
                return;
            }

            const value = statusEl.value;
            const isOpen = value === 'open_for_bids';
            tenderEl.style.display = isOpen ? '' : 'none';

            if (requirementsSectionEl) {
                requirementsSectionEl.style.display = isOpen ? '' : 'none';
            }
        }

        function buildRequirementsText() {
            if (!requirementsTextarea) {
                return;
            }

            const lines = [];

            const scopeVal = (reqScope && reqScope.value ? reqScope.value.trim() : '');
            const materialsVal = (reqMaterials && reqMaterials.value ? reqMaterials.value.trim() : '');
            const scheduleVal = (reqSchedule && reqSchedule.value ? reqSchedule.value.trim() : '');
            const notesVal = (reqNotes && reqNotes.value ? reqNotes.value.trim() : '');

            lines.push('ظ…ظ„ط®طµ ط§ظ„ط·ظ„ط¨:');
            lines.push(scopeVal ? ('- ط§ظ„ظ…ط·ظ„ظˆط¨: ' + scopeVal) : '- ط§ظ„ظ…ط·ظ„ظˆط¨:');

            if (scheduleVal) {
                lines.push('- ط§ظ„ط¬ط¯ظˆظ„ ط§ظ„ظ…طھظˆظ‚ط¹: ' + scheduleVal);
            }

            if (materialsVal) {
                lines.push('- ط§ظ„ظ…ظˆط§ط¯/ط§ظ„ظ…ط§ط±ظƒط§طھ ط§ظ„ظ…ط·ظ„ظˆط¨ط©: ' + materialsVal);
            }

            const info = [];
            if (reqHasPlans && reqHasPlans.checked) info.push('ظٹظˆط¬ط¯ ظ…ط®ط·ط·ط§طھ/ط±ط³ظˆظ…ط§طھ ط¬ط§ظ‡ط²ط©');
            if (reqNeedsSiteVisit && reqNeedsSiteVisit.checked) info.push('ظٹظپط¶ظ„ ظ…ط¹ط§ظٹظ†ط© ظ‚ط¨ظ„ ط§ظ„طھط³ط¹ظٹط±');
            if (reqIncludeMaterials && reqIncludeMaterials.checked) info.push('ط§ظ„ط¹ط±ط¶ ظٹط´ظ…ظ„ ط§ظ„ظ…ظˆط§ط¯ ظˆط§ظ„طھظˆط±ظٹط¯');
            if (reqIncludeLabor && reqIncludeLabor.checked) info.push('ط§ظ„ط¹ط±ط¶ ظٹط´ظ…ظ„ ط§ظ„ط¹ظ…ط§ظ„ط© ظˆط§ظ„طھظ†ظپظٹط°');

            if (info.length) {
                lines.push('ظ…ط¹ظ„ظˆظ…ط§طھ ط¥ط¶ط§ظپظٹط©:');
                info.forEach((item) => lines.push('- ' + item));
            }

            if (notesVal) {
                lines.push('ظ…ظ„ط§ط­ط¸ط§طھ ظˆط´ط±ظˆط·:');
                lines.push(notesVal);
            }

            requirementsTextarea.value = lines.join('\n');
        }

        function bindRequirements() {
            const els = [reqScope, reqMaterials, reqSchedule, reqHasPlans, reqNeedsSiteVisit, reqIncludeMaterials, reqIncludeLabor, reqNotes];
            els.forEach((el) => {
                if (!el) return;
                el.addEventListener('input', buildRequirementsText);
                el.addEventListener('change', buildRequirementsText);
            });
            buildRequirementsText();
        }

        function formatBytes(bytes) {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        }

        function renderAttachmentsList() {
            if (!attachmentsList) {
                return;
            }

            if (!attachmentsDraft.length) {
                attachmentsList.innerHTML = '';
                return;
            }

            const items = attachmentsDraft.map((f, idx) => {
                const name = f.name || 'ظ…ظ„ظپ';
                const size = typeof f.size === 'number' ? formatBytes(f.size) : '';
                return `
                    <div class="d-flex justify-content-between align-items-center border rounded px-3 py-2 mb-2">
                        <div class="text-truncate">
                            <div class="fw-semibold text-truncate">${name}</div>
                            <div class="text-muted small">${size}</div>
                        </div>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-remove-attachment="${idx}">ط¥ط²ط§ظ„ط©</button>
                    </div>
                `;
            }).join('');

            attachmentsList.innerHTML = items;
        }

        function syncAttachmentsInput() {
            if (!attachmentsInput) {
                return;
            }

            const dt = new DataTransfer();
            attachmentsDraft.forEach((f) => dt.items.add(f));
            attachmentsInput.files = dt.files;
        }

        function bindImagePreview() {
            if (!imageInput || !imagePreviewWrap || !imagePreview) {
                return;
            }

            imageInput.addEventListener('change', function () {
                const file = imageInput.files && imageInput.files[0] ? imageInput.files[0] : null;
                if (!file) {
                    imagePreviewWrap.style.display = 'none';
                    imagePreview.src = '';
                    return;
                }

                const url = URL.createObjectURL(file);
                imagePreview.src = url;
                imagePreviewWrap.style.display = '';
            });
        }

        function bindAttachmentsPreview() {
            if (!attachmentsInput || !attachmentsList) {
                return;
            }

            attachmentsInput.addEventListener('change', function () {
                attachmentsDraft = Array.from(attachmentsInput.files || []);
                renderAttachmentsList();
            });

            attachmentsList.addEventListener('click', function (e) {
                const btn = e.target && e.target.closest ? e.target.closest('[data-remove-attachment]') : null;
                if (!btn) {
                    return;
                }

                const idx = parseInt(btn.getAttribute('data-remove-attachment'), 10);
                if (Number.isNaN(idx)) {
                    return;
                }

                attachmentsDraft.splice(idx, 1);
                syncAttachmentsInput();
                renderAttachmentsList();
            });

            attachmentsDraft = Array.from(attachmentsInput.files || []);
            renderAttachmentsList();
        }

        function bindVoiceAssist() {
            if (!voiceStartBtn || !voiceTranscriptEl) {
                return;
            }

            const supported = ('webkitSpeechRecognition' in window) || ('SpeechRecognition' in window);
            const SR = window.SpeechRecognition || window.webkitSpeechRecognition;

            const setVoiceStatus = (msg) => {
                if (voiceStatusEl) {
                    voiceStatusEl.textContent = msg || '';
                }
            };

            const setAnalyzeStatus = (msg) => {
                if (voiceAnalyzeStatusEl) {
                    voiceAnalyzeStatusEl.textContent = msg || '';
                }
            };

            let rec = null;
            let listening = false;
            let wantStop = false;
            let lastSnapshot = null;

            const strip = (s) => (s || '').replace(/[\u0610-\u061A\u064B-\u065F\u0670\u06D6-\u06ED]/g, '');
            const norm = (s) => strip(s)
                .replace(/[ط¥ط£ط¢]/g, 'ط§')
                .replace(/ظ‰/g, 'ظٹ')
                .replace(/ط¤/g, 'ظˆ')
                .replace(/ط¦/g, 'ظٹ')
                .toLowerCase();

            const toLatinDigits = (s) => (s || '')
                .replace(/[ظ -ظ©]/g, (d) => String('ظ ظ،ظ¢ظ£ظ¤ظ¥ظ¦ظ§ظ¨ظ©'.indexOf(d)))
                .replace(/[غ°-غ¹]/g, (d) => String('غ°غ±غ²غ³غ´غµغ¶غ·غ¸غ¹'.indexOf(d)));

            const numWordsMap = {
                'طµظپط±': 0,
                'ظˆط§ط­ط¯': 1,
                'ظˆط§ط­ط¯ط©': 1,
                'ط§ط«ظ†ظٹظ†': 2,
                'ط§ط«ظ†ط§ظ†': 2,
                'ط§ط«ظ†طھظٹظ†': 2,
                'ط§ط«ظ†طھط§ظ†': 2,
                'ط«ظ„ط§ط«': 3,
                'ط«ظ„ط§ط«ظ‡': 3,
                'ط«ظ„ط§ط«ط©': 3,
                'ط§ط±ط¨ط¹': 4,
                'ط§ط±ط¨ط¹ظ‡': 4,
                'ط§ط±ط¨ط¹ط©': 4,
                'ط£ط±ط¨ط¹': 4,
                'ط£ط±ط¨ط¹ط©': 4,
                'ط®ظ…ط³': 5,
                'ط®ظ…ط³ظ‡': 5,
                'ط®ظ…ط³ط©': 5,
                'ط³طھ': 6,
                'ط³طھظ‡': 6,
                'ط³طھط©': 6,
                'ط³ط¨ط¹': 7,
                'ط³ط¨ط¹ظ‡': 7,
                'ط³ط¨ط¹ط©': 7,
                'ط«ظ…ط§ظ†': 8,
                'ط«ظ…ط§ظ†ظٹظ‡': 8,
                'ط«ظ…ط§ظ†ظٹط©': 8,
                'طھط³ط¹': 9,
                'طھط³ط¹ظ‡': 9,
                'طھط³ط¹ط©': 9,
                'ط¹ط´ط±': 10,
                'ط¹ط´ط±ظ‡': 10,
                'ط¹ط´ط±ط©': 10,
            };

            function parseSimpleNumberToken(token) {
                const t = norm(toLatinDigits(token)).trim();
                if (!t) return null;
                if (Object.prototype.hasOwnProperty.call(numWordsMap, t)) {
                    return numWordsMap[t];
                }
                const m = t.match(/-?\d+(?:\.\d+)?/);
                if (m) {
                    const v = parseFloat(m[0]);
                    return Number.isFinite(v) ? v : null;
                }
                return null;
            }

            function multiplierFromText(s) {
                const n = norm(s);
                if (/(ظ…ظ„ظٹظˆظ†|ظ…ظ„ط§ظٹظٹظ†)/.test(n)) return 1000000;
                if (/(ط§ظ„ظپ|ط£ظ„ظپ|ط§ظ„ط§ظپ|ط¢ظ„ط§ظپ|ط§ظ„ط¢ظپ)/.test(n)) return 1000;
                if (/(ظ…ظ„ظٹط§ط±)/.test(n)) return 1000000000;
                return 1;
            }

            function parseMoneyValue(text) {
                const raw = toLatinDigits(text);
                const n = norm(raw);

                if (/ظ†طµ\s*ظ…ظ„ظٹظˆظ†/.test(n) || /ظ†طµظپ\s*ظ…ظ„ظٹظˆظ†/.test(n)) {
                    return 500000;
                }

                const mult = multiplierFromText(n);
                const m = n.match(/(-?\d+(?:\.\d+)?)/);
                if (m) {
                    const base = parseFloat(m[1]);
                    if (Number.isFinite(base)) {
                        return Math.round(base * mult);
                    }
                }

                const parts = n.split(/\s+/).filter(Boolean);
                for (const p of parts) {
                    const v = parseSimpleNumberToken(p);
                    if (v !== null) {
                        return Math.round(v * mult);
                    }
                }

                return null;
            }

            function extractMoneyRange(text) {
                const s = norm(toLatinDigits(text));
                const isBudgetContext = /(ظ…ظٹط²ط§ظ†|ظ…ظٹط²ط§ظ†ظٹظ‡|ظ…ظٹط²ط§ظ†ظٹط©|ظ…ظٹط²ط§ظ†ظٹطھظٹ|طھظƒظ„ظپ|ط³ط¹ط±|ظ‚ظٹظ…ط©|ط¨ط­ط¯ظˆط¯|ط­ط¯ظˆط¯|طھظ‚ط±ظٹط¨ط§|طھظ‚ط±ظٹط¨ط§ظ‹)/.test(s);
                if (!isBudgetContext) {
                    return null;
                }

                const between = s.match(/(?:ط¨ظٹظ†|ظ…ظ†)\s+([^\n]+?)\s+(?:ط§ظ„ظ‰|ط¥ظ„ظ‰|ظˆ)\s+([^\n]+)/);
                if (between) {
                    const a = parseMoneyValue(between[1]);
                    const b = parseMoneyValue(between[2]);
                    if (a !== null && b !== null) {
                        return { min: Math.min(a, b), max: Math.max(a, b) };
                    }
                    if (a !== null) return { min: a, max: null };
                    if (b !== null) return { min: null, max: b };
                }

                const upTo = s.match(/(?:ط§ظ„ظ‰|ط¥ظ„ظ‰|ط­طھظ‰|ط­ط¯ظˆط¯)\s+([^\n]+)/);
                if (upTo) {
                    const v = parseMoneyValue(upTo[1]);
                    if (v !== null) return { min: null, max: v };
                }

                const from = s.match(/(?:ظ…ظ†)\s+([^\n]+)/);
                if (from) {
                    const v = parseMoneyValue(from[1]);
                    if (v !== null) return { min: v, max: null };
                }

                const vals = [];
                const moneyTokens = s.match(/(?:\d+(?:\.\d+)?)\s*(?:ظ…ظ„ظٹظˆظ†|ظ…ظ„ط§ظٹظٹظ†|ط§ظ„ظپ|ط£ظ„ظپ|ط¢ظ„ط§ظپ|ط§ظ„ط§ظپ)?/g) || [];
                moneyTokens.forEach((t) => {
                    const v = parseMoneyValue(t);
                    if (v !== null) vals.push(v);
                });
                if (vals.length >= 2) {
                    return { min: Math.min(...vals), max: Math.max(...vals) };
                }
                if (vals.length === 1) {
                    return { min: vals[0], max: null };
                }

                return null;
            }

            function extractDurationDays(text) {
                const s = norm(toLatinDigits(text));
                const m = s.match(/(\d+|[\p{L}]+)\s*(ظٹظˆظ…|ط§ظٹط§ظ…|ط£ظٹط§ظ…|ط§ط³ط¨ظˆط¹|ط£ط³ط¨ظˆط¹|ط§ط³ط§ط¨ظٹط¹|ط£ط³ط§ط¨ظٹط¹|ط´ظ‡ط±|ط´ظ‡ظˆط±|ط³ظ†ظ‡|ط³ظ†ط©|ط³ظ†ظˆط§طھ)/u);
                if (!m) return null;
                const count = parseSimpleNumberToken(m[1]);
                if (count === null) return null;

                const unit = m[2];
                if (/ط§ط³ط¨ظˆط¹|ط£ط³ط¨ظˆط¹|ط§ط³ط§ط¨ظٹط¹|ط£ط³ط§ط¨ظٹط¹/.test(unit)) return Math.round(count * 7);
                if (/ط´ظ‡ط±|ط´ظ‡ظˆط±/.test(unit)) return Math.round(count * 30);
                if (/ط³ظ†ظ‡|ط³ظ†ط©|ط³ظ†ظˆط§طھ/.test(unit)) return Math.round(count * 365);
                return Math.round(count);
            }

            function extractArea(text, which) {
                const s = norm(toLatinDigits(text));
                const patterns = which === 'land'
                    ? [/(?:ظ…ط³ط§ط­ط©\s*ط§ظ„ط§ط±ط¶|ظ…ط³ط§ط­ظ‡\s*ط§ظ„ط§ط±ط¶|ط§ظ„ط§ط±ط¶|ط§ظ„ط£ط±ط¶)\s*(\d{2,7}(?:\.\d+)?)/]
                    : [/(?:ظ…ط³ط§ط­ط©\s*ط§ظ„ط¨ظ†ط§ط،|ظ…ط³ط§ط­ظ‡\s*ط§ظ„ط¨ظ†ط§ط،|ط§ظ„ظ…ط¨ظ†ظٹ|ط§ظ„ظ…ط¨ظ†ظ‰|ط§ظ„ط¨ظ†ط§ط،)\s*(\d{2,7}(?:\.\d+)?)/];
                for (const re of patterns) {
                    const m = s.match(re);
                    if (m) {
                        const v = parseFloat(m[1]);
                        if (Number.isFinite(v)) return v;
                    }
                }
                return null;
            }

            function extractCount(text, type) {
                const s = norm(toLatinDigits(text));
                const re = type === 'floors'
                    ? /(\d+|[\p{L}]+)\s*(ط¯ظˆط±|ط§ط¯ظˆط§ط±|ط£ط¯ظˆط§ط±)/u
                    : type === 'rooms'
                        ? /(\d+|[\p{L}]+)\s*(ط؛ط±ظپ|ط؛ط±ظپط©)/u
                        : /(\d+|[\p{L}]+)\s*(ط­ظ…ط§ظ…|ط­ظ…ط§ظ…ط§طھ)/u;
                const m = s.match(re);
                if (!m) return null;
                const v = parseSimpleNumberToken(m[1]);
                return v !== null ? Math.round(v) : null;
            }

            function snapshot() {
                lastSnapshot = {
                    scope: reqScope ? (reqScope.value || '') : '',
                    notes: reqNotes ? (reqNotes.value || '') : '',
                    requirements: requirementsTextarea ? (requirementsTextarea.value || '') : '',
                    desc0: document.querySelector('textarea[name="translations[0][description]"]')?.value || '',
                    name0: document.querySelector('input[name="translations[0][name]"]')?.value || '',
                    projectType: projectTypeEl ? (projectTypeEl.value || '') : '',
                    requestType: requestTypeEl ? (requestTypeEl.value || '') : '',
                    scopeOfWork: scopeOfWorkEl ? (scopeOfWorkEl.value || '') : '',
                    finishingLevel: finishingLevelEl ? (finishingLevelEl.value || '') : '',
                    budgetMin: budgetMinEl ? (budgetMinEl.value || '') : '',
                    budgetMax: budgetMaxEl ? (budgetMaxEl.value || '') : '',
                    durationDays: durationDaysEl ? (durationDaysEl.value || '') : '',
                    landArea: landAreaEl ? (landAreaEl.value || '') : '',
                    builtArea: builtAreaEl ? (builtAreaEl.value || '') : '',
                    floorsCount: floorsCountEl ? (floorsCountEl.value || '') : '',
                    roomsCount: roomsCountEl ? (roomsCountEl.value || '') : '',
                    bathroomsCount: bathroomsCountEl ? (bathroomsCountEl.value || '') : '',
                };
                if (voiceUndoBtn) {
                    voiceUndoBtn.disabled = false;
                }
            }

            function restoreSnapshot() {
                if (!lastSnapshot) {
                    return;
                }
                if (reqScope) reqScope.value = lastSnapshot.scope;
                if (reqNotes) reqNotes.value = lastSnapshot.notes;
                if (requirementsTextarea) requirementsTextarea.value = lastSnapshot.requirements;
                const desc0 = document.querySelector('textarea[name="translations[0][description]"]');
                if (desc0) desc0.value = lastSnapshot.desc0;
                const name0 = document.querySelector('input[name="translations[0][name]"]');
                if (name0) name0.value = lastSnapshot.name0;

                if (projectTypeEl) projectTypeEl.value = lastSnapshot.projectType;
                if (requestTypeEl) requestTypeEl.value = lastSnapshot.requestType;
                if (scopeOfWorkEl) scopeOfWorkEl.value = lastSnapshot.scopeOfWork;
                if (finishingLevelEl) finishingLevelEl.value = lastSnapshot.finishingLevel;

                if (budgetMinEl) budgetMinEl.value = lastSnapshot.budgetMin;
                if (budgetMaxEl) budgetMaxEl.value = lastSnapshot.budgetMax;
                if (durationDaysEl) durationDaysEl.value = lastSnapshot.durationDays;
                if (landAreaEl) landAreaEl.value = lastSnapshot.landArea;
                if (builtAreaEl) builtAreaEl.value = lastSnapshot.builtArea;
                if (floorsCountEl) floorsCountEl.value = lastSnapshot.floorsCount;
                if (roomsCountEl) roomsCountEl.value = lastSnapshot.roomsCount;
                if (bathroomsCountEl) bathroomsCountEl.value = lastSnapshot.bathroomsCount;

                if (voiceUndoBtn) voiceUndoBtn.disabled = true;
                lastSnapshot = null;

                buildRequirementsText();
            }

            function makeRec() {
                if (!SR) return null;
                const r = new SR();
                r.lang = 'ar-SA';
                r.interimResults = true;
                r.continuous = true;
                return r;
            }

            function start() {
                if (!supported) {
                    setVoiceStatus('ظ…طھطµظپط­ظƒ ظ„ط§ ظٹط¯ط¹ظ… ط§ظ„ط¥ظ…ظ„ط§ط، ط§ظ„طµظˆطھظٹ.');
                    return;
                }
                if (listening) return;

                rec = makeRec();
                if (!rec) {
                    setVoiceStatus('طھط¹ط°ط± طھط´ط؛ظٹظ„ ط§ظ„ط¥ظ…ظ„ط§ط، ط§ظ„طµظˆطھظٹ.');
                    return;
                }

                listening = true;
                wantStop = false;
                setVoiceStatus('ظٹطھظ… ط§ظ„ط§ط³طھظ…ط§ط¹...');
                setAnalyzeStatus('');

                rec.onresult = (e) => {
                    let final = '';
                    for (let i = e.resultIndex; i < e.results.length; i++) {
                        const rs = e.results[i];
                        if (rs.isFinal) {
                            final += (rs[0]?.transcript || '') + ' ';
                        }
                    }
                    final = final.trim();
                    if (final) {
                        voiceTranscriptEl.value = (voiceTranscriptEl.value ? (voiceTranscriptEl.value + ' ') : '') + final;
                        setVoiceStatus('طھظ… ط§ظ„طھط­ظˆظٹظ„ ط¥ظ„ظ‰ ظ†طµ.');
                    }
                };

                rec.onerror = () => {
                    setVoiceStatus('ط­ط¯ط« ط®ط·ط£ ط£ط«ظ†ط§ط، ط§ظ„ط§ط³طھظ…ط§ط¹.');
                };

                rec.onend = () => {
                    if (wantStop) {
                        listening = false;
                        setVoiceStatus('');
                        voiceStartBtn.disabled = false;
                        if (voiceStopBtn) voiceStopBtn.disabled = true;
                        if ((voiceTranscriptEl.value || '').trim()) {
                            voiceAnalyzeBtn && voiceAnalyzeBtn.click();
                        }
                        return;
                    }

                    if (listening) {
                        try {
                            rec.start();
                        } catch (_) {
                            listening = false;
                        }
                    }
                };

                try {
                    rec.start();
                } catch (_) {
                    listening = false;
                    setVoiceStatus('طھط¹ط°ط± ط¨ط¯ط، ط§ظ„ط§ط³طھظ…ط§ط¹.');
                    return;
                }

                voiceStartBtn.disabled = true;
                if (voiceStopBtn) voiceStopBtn.disabled = false;
            }

            function stop() {
                wantStop = true;
                try {
                    rec && rec.stop();
                } catch (_) {}
            }

            function analyzeAndFill() {
                const text = (voiceTranscriptEl.value || '').trim();
                if (!text) {
                    setAnalyzeStatus('ط§ظƒطھط¨/ط³ط¬ظ‘ظ„ ظ†طµط§ظ‹ ط£ظˆظ„ط§ظ‹.');
                    return;
                }

                snapshot();
                setAnalyzeStatus('ط¬ط§ط±ظٹ ط§ظ„طھط¹ط¨ط¦ط©...');

                const normalized = toLatinDigits(text).replace(/\s+/g, ' ').trim();
                const n2 = norm(normalized);
                const firstLine = normalized.split(/\n|\.|\!|\طں|\?/).map(s => s.trim()).filter(Boolean)[0] || '';
                const rest = normalized.replace(firstLine, '').trim();

                const name0 = document.querySelector('input[name="translations[0][name]"]');
                if (name0 && !name0.value && firstLine) {
                    name0.value = firstLine;
                }

                if (reqScope && !reqScope.value) {
                    reqScope.value = firstLine;
                } else if (reqNotes && !reqNotes.value) {
                    reqNotes.value = firstLine;
                }

                if (reqNotes && rest && !reqNotes.value) {
                    reqNotes.value = rest;
                } else if (reqNotes && rest && reqNotes.value && reqNotes.value.length < 40) {
                    reqNotes.value = (reqNotes.value + '\n' + rest).trim();
                }

                const desc0 = document.querySelector('textarea[name="translations[0][description]"]');
                if (desc0 && !desc0.value) {
                    desc0.value = text;
                }

                const has = (re) => re.test(n2);

                if (projectTypeEl && !projectTypeEl.value) {
                    if (has(/ط³ظƒظ†ظٹ|ظپظٹظ„ط§|ط´ظ‚ظ‡|ط´ظ‚ط©|ط¯ظˆط¨ظ„ظƒط³/)) projectTypeEl.value = 'residential';
                    else if (has(/طھط¬ط§ط±ظٹ|ظ…ط­ظ„|ظ…ظƒطھط¨|ظ…ط¹ط±ط¶/)) projectTypeEl.value = 'commercial';
                    else if (has(/طµظ†ط§ط¹ظٹ|ظ…ط³طھظˆط¯ط¹|ظ…طµظ†ط¹/)) projectTypeEl.value = 'industrial';
                    else if (has(/ط­ظƒظˆظ…ظٹ|ظ…ط¯ط±ط³ظ‡|ظ…ط¯ط±ط³ط©|ظ…ط³طھط´ظپظ‰|ط¬ط§ظ…ط¹ظ‡|ط¬ط§ظ…ط¹ط©/)) projectTypeEl.value = 'government';
                }

                if (requestTypeEl && !requestTypeEl.value) {
                    if (has(/طھط±ظ…ظٹظ…|طھط¬ط¯ظٹط¯|ط¥ط¹ط§ط¯ط©/)) requestTypeEl.value = 'renovation';
                    else if (has(/طھط´ط·ظٹط¨/)) requestTypeEl.value = 'finishing';
                    else if (has(/ط¥ط¶ط§ظپط©|ظ…ظ„ط­ظ‚|طھظˆط³ط¹ط©/)) requestTypeEl.value = 'extension';
                    else if (has(/ط¨ظ†ط§ط،|ط§ظ†ط´ط§ط،|ط¥ظ†ط´ط§ط،|ط¹ط¸ظ…/)) requestTypeEl.value = 'build';
                }

                if (scopeOfWorkEl && !scopeOfWorkEl.value) {
                    if (has(/ظƒط§ظ…ظ„|طھط³ظ„ظٹظ… ظ…ظپطھط§ط­/)) scopeOfWorkEl.value = 'full';
                    else if (has(/ط¹ط¸ظ…/)) scopeOfWorkEl.value = 'structure';
                    else if (has(/طھط´ط·ظٹط¨/)) scopeOfWorkEl.value = 'finishing';
                }

                if (finishingLevelEl && !finishingLevelEl.value) {
                    if (has(/ظپط§ط®ط±|vip|ظپط®ظ…/)) finishingLevelEl.value = 'luxury';
                    else if (has(/ظ…طھظˆط³ط·/)) finishingLevelEl.value = 'standard';
                    else if (has(/ط§ظ‚طھطµط§ط¯ظٹ/)) finishingLevelEl.value = 'economic';
                }

                const moneyRange = extractMoneyRange(normalized);
                if (moneyRange) {
                    if (budgetMinEl && !budgetMinEl.value && moneyRange.min !== null) budgetMinEl.value = moneyRange.min;
                    if (budgetMaxEl && !budgetMaxEl.value && moneyRange.max !== null) budgetMaxEl.value = moneyRange.max;
                }

                const dDays = extractDurationDays(normalized);
                if (durationDaysEl && !durationDaysEl.value && dDays !== null) {
                    durationDaysEl.value = dDays;
                }

                const landA = extractArea(normalized, 'land');
                if (landAreaEl && !landAreaEl.value && landA !== null) {
                    landAreaEl.value = landA;
                }
                const builtA = extractArea(normalized, 'built');
                if (builtAreaEl && !builtAreaEl.value && builtA !== null) {
                    builtAreaEl.value = builtA;
                }

                const floors = extractCount(normalized, 'floors');
                if (floorsCountEl && !floorsCountEl.value && floors !== null) {
                    floorsCountEl.value = floors;
                }
                const rooms = extractCount(normalized, 'rooms');
                if (roomsCountEl && !roomsCountEl.value && rooms !== null) {
                    roomsCountEl.value = rooms;
                }
                const baths = extractCount(normalized, 'baths');
                if (bathroomsCountEl && !bathroomsCountEl.value && baths !== null) {
                    bathroomsCountEl.value = baths;
                }

                buildRequirementsText();
                setAnalyzeStatus('طھظ…طھ ط§ظ„طھط¹ط¨ط¦ط©.');
            }

            voiceStartBtn.addEventListener('click', start);
            voiceStopBtn && voiceStopBtn.addEventListener('click', stop);

            voiceAnalyzeBtn && voiceAnalyzeBtn.addEventListener('click', analyzeAndFill);

            voiceClearBtn && voiceClearBtn.addEventListener('click', function () {
                voiceTranscriptEl.value = '';
                setVoiceStatus('');
                setAnalyzeStatus('');
            });

            voiceUndoBtn && voiceUndoBtn.addEventListener('click', function () {
                restoreSnapshot();
                setAnalyzeStatus('طھظ… ط§ظ„طھط±ط§ط¬ط¹.');
            });
        }

        function bindMapPicker() {
            if (!mapEl || typeof L === 'undefined') {
                return;
            }

            const defaultLat = 24.7136;
            const defaultLng = 46.6753;

            const initialLat = latEl && latEl.value !== '' ? parseFloat(latEl.value) : defaultLat;
            const initialLng = lngEl && lngEl.value !== '' ? parseFloat(lngEl.value) : defaultLng;

            const map = L.map(mapEl, {
                zoomControl: true,
            }).setView([initialLat, initialLng], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap',
            }).addTo(map);

            const marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);

            function setLatLng(lat, lng, updateView) {
                const fixedLat = Number.isFinite(lat) ? lat : defaultLat;
                const fixedLng = Number.isFinite(lng) ? lng : defaultLng;

                marker.setLatLng([fixedLat, fixedLng]);
                if (updateView) {
                    map.setView([fixedLat, fixedLng], Math.max(map.getZoom(), 14));
                }

                if (latEl) latEl.value = fixedLat;
                if (lngEl) lngEl.value = fixedLng;

                const gUrl = `https://www.google.com/maps?q=${fixedLat},${fixedLng}`;
                if (gmapsEl) gmapsEl.value = gUrl;
                if (mapOpenGmaps) mapOpenGmaps.href = gUrl;
            }

            setLatLng(initialLat, initialLng, false);

            map.on('click', function (e) {
                if (!e || !e.latlng) return;
                setLatLng(e.latlng.lat, e.latlng.lng, true);
            });

            marker.on('dragend', function (e) {
                const pos = e && e.target ? e.target.getLatLng() : null;
                if (!pos) return;
                setLatLng(pos.lat, pos.lng, false);
            });

            async function doSearch() {
                if (!mapSearchEl || !mapSearchEl.value) {
                    return;
                }

                const q = mapSearchEl.value.trim();
                if (!q) return;

                const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}&limit=1`;
                const res = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                    },
                });

                if (!res.ok) {
                    return;
                }

                const data = await res.json();
                const first = Array.isArray(data) && data.length ? data[0] : null;
                if (!first) {
                    return;
                }

                const lat = parseFloat(first.lat);
                const lng = parseFloat(first.lon);
                setLatLng(lat, lng, true);
            }

            if (mapSearchBtn) {
                mapSearchBtn.addEventListener('click', function () {
                    doSearch();
                });
            }

            if (mapSearchEl) {
                mapSearchEl.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        doSearch();
                    }
                });
            }

            if (mapLocateBtn && navigator.geolocation) {
                mapLocateBtn.addEventListener('click', function () {
                    navigator.geolocation.getCurrentPosition(function (pos) {
                        const lat = pos && pos.coords ? pos.coords.latitude : null;
                        const lng = pos && pos.coords ? pos.coords.longitude : null;
                        if (lat === null || lng === null) return;
                        setLatLng(lat, lng, true);
                    });
                });
            }
        }

        if (statusEl) {
            statusEl.addEventListener('change', syncTenderVisibility);
            syncTenderVisibility();
        }

        bindRequirements();
        bindImagePreview();
        bindAttachmentsPreview();
        bindMapPicker();
        bindVoiceAssist();
    })();
</script>
@endpush
@endsection

