<div class="sidebar-header">
    <div class="sidebar-brand w-100">
        <div class="sidebar-brand-icon"><i class="{{ $icon }}"></i></div>
        <div class="flex-grow-1" style="min-width:0">
            <h4 class="mb-0 text-truncate">{{ $title }}</h4>
            @if(!empty($badge))
                <span class="sidebar-brand-badge">{{ $badge }}</span>
            @endif
        </div>
    </div>
</div>
