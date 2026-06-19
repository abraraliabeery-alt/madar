@props([
    'title' => null,
    'class' => '',
])

<div class="card {{ $class }}">
    @if($title)
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">{{ $title }}</h5>
            @if(isset($actions))
                <div>{{ $actions }}</div>
            @endif
        </div>
    @endif
    
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
