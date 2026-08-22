@extends('client.layouts.app')

@section('title', 'طھظپط§طµظٹظ„ ط§ظ„ظ…ط´ط±ظˆط¹')

@section('content')
    @php
        $t = $project->translations->firstWhere('locale', app()->getLocale())
            ?? $project->translations->first();
        $imageUrl = $project->image ? asset('storage/' . $project->image) : null;
    @endphp

    <x-bs.card title="طھظپط§طµظٹظ„ ط§ظ„ظ…ط´ط±ظˆط¹">
        <x-slot name="actions">
            <a href="{{ route('client.dashboard') }}" class="btn btn-light btn-sm">ط±ط¬ظˆط¹</a>
        </x-slot>

        <div class="row g-4">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">طµظˆط±ط© ط§ظ„ظ…ط´ط±ظˆط¹</div>
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="طµظˆط±ط© ط§ظ„ظ…ط´ط±ظˆط¹" class="img-fluid rounded border">
                        @else
                            <div class="text-muted">ظ„ط§ طھظˆط¬ط¯ طµظˆط±ط© ظ…ط±ظپظˆط¹ط©.</div>
                        @endif
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">ط§ظ„ظ…ظˆظ‚ط¹</div>
                        <div class="small text-muted">
                            <div>ط§ظ„ظ…ط¯ظٹظ†ط©: {{ $project->city?->localized_name ?? '-' }}</div>
                            <div>ط§ظ„ط­ظٹ: {{ $project->neighborhood?->name ?? '-' }}</div>
                            <div>ط§ظ„ط´ط§ط±ط¹: {{ $project->street?->name ?? '-' }}</div>
                            <div>ط§ظ„ط¹ظ†ظˆط§ظ†: {{ $project->address ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="fw-semibold mb-1">ط§ط³ظ… ط§ظ„ظ…ط´ط±ظˆط¹</div>
                        <div class="h5 mb-3">{{ $t->name ?? ('ظ…ط´ط±ظˆط¹ ط±ظ‚ظ… ' . $project->id) }}</div>

                        @if(!empty($t?->description))
                            <div class="fw-semibold mb-1">ظˆطµظپ ط§ظ„ظ…ط´ط±ظˆط¹</div>
                            <div class="text-muted" style="white-space: pre-line;">{{ $t->description }}</div>
                            <hr>
                        @endif

                        <div class="row g-3 small">
                            <div class="col-12 col-md-6">
                                <div class="fw-semibold">ط­ط§ظ„ط© ط§ظ„ظ†ط´ط±</div>
                                <div class="text-muted">{{ $project->status ?? '-' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fw-semibold">ط¢ط®ط± ظ…ظˆط¹ط¯ ظ„ط§ط³طھظ„ط§ظ… ط§ظ„ط¹ط±ظˆط¶</div>
                                <div class="text-muted">{{ $project->bid_deadline?->format('Y-m-d') ?? '-' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fw-semibold">ط§ظ„ظ…ظٹط²ط§ظ†ظٹط© ط§ظ„ط¯ظ†ظٹط§</div>
                                <div class="text-muted">{{ $project->budget_min !== null ? number_format($project->budget_min) : '-' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fw-semibold">ط§ظ„ظ…ظٹط²ط§ظ†ظٹط© ط§ظ„ظ‚طµظˆظ‰</div>
                                <div class="text-muted">{{ $project->budget_max !== null ? number_format($project->budget_max) : '-' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fw-semibold">طھط§ط±ظٹط® ط§ظ„ط¨ط¯ط، ط§ظ„ظ…طھظˆظ‚ط¹</div>
                                <div class="text-muted">{{ $project->start_date?->format('Y-m-d') ?? '-' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fw-semibold">ظ…ط¯ط© ط§ظ„طھظ†ظپظٹط° (ط¨ط§ظ„ط£ظٹط§ظ…)</div>
                                <div class="text-muted">{{ $project->duration_days ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">ظ…ط±ظپظ‚ط§طھ ط§ظ„ظ…ط´ط±ظˆط¹</div>

                        @if($project->attachmentsFiles->count())
                            <div class="list-group">
                                @foreach($project->attachmentsFiles as $file)
                                    <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                       href="{{ asset('storage/' . $file->path) }}" target="_blank" rel="noopener">
                                        <span>{{ $file->original_name ?? 'ظ…ظ„ظپ ظ…ط±ظپظ‚' }}</span>
                                        <span class="text-muted small">طھط­ظ…ظٹظ„</span>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-muted">ظ„ط§ طھظˆط¬ط¯ ظ…ظ„ظپط§طھ ظ…ط±ظپظˆط¹ط©.</div>
                        @endif

                        @if(is_array($project->attachments) && count($project->attachments))
                            <hr>
                            <div class="fw-semibold mb-2">ط±ظˆط§ط¨ط·/ط£ط³ظ…ط§ط، ظ…ط±ظپظ‚ط§طھ (ظ†طµظٹط©)</div>
                            <ul class="mb-0">
                                @foreach($project->attachments as $item)
                                    @if(!empty($item))
                                        <li class="text-muted">{{ $item }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">ط§ظ„ظ…طھط·ظ„ط¨ط§طھ</div>
                        @if($project->requirements)
                            <div class="text-muted" style="white-space: pre-line;">{{ $project->requirements }}</div>
                        @else
                            <div class="text-muted">ظ„ط§ طھظˆط¬ط¯ ظ…طھط·ظ„ط¨ط§طھ ظ…ظƒطھظˆط¨ط©.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-bs.card>
@endsection

