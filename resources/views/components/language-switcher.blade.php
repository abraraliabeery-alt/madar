@props(['class' => '', 'showFlags' => true, 'showNames' => true, 'dropdown' => true])

@if($dropdown)
    <div class="language-switcher dropdown {{ $class }}">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            @if($showFlags)
                <span class="flag">{{ $currentLanguageData['flag'] }}</span>
            @endif
            @if($showNames)
                <span class="language-name">{{ $currentLanguageData['native'] }}</span>
            @endif
        </button>
        <ul class="dropdown-menu">
            @foreach($languageSwitcher as $language)
                <li>
                    <a class="dropdown-item {{ $language['current'] ? 'active' : '' }}" 
                       href="{{ $language['url'] }}"
                       data-language="{{ $language['code'] }}">
                        @if($showFlags)
                            <span class="flag me-2">{{ $language['flag'] }}</span>
                        @endif
                        @if($showNames)
                            <span class="language-name">{{ $language['native'] }}</span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@else
    <div class="language-switcher-inline {{ $class }}">
        @foreach($languageSwitcher as $language)
            <a href="{{ $language['url'] }}" 
               class="language-link {{ $language['current'] ? 'active' : '' }}"
               data-language="{{ $language['code'] }}">
                @if($showFlags)
                    <span class="flag">{{ $language['flag'] }}</span>
                @endif
                @if($showNames)
                    <span class="language-name">{{ $language['native'] }}</span>
                @endif
            </a>
        @endforeach
    </div>
@endif

<style>
.language-switcher .dropdown-toggle {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.language-switcher .flag {
    font-size: 1.2rem;
}

.language-switcher .language-name {
    font-weight: 500;
}

.language-switcher .dropdown-item.active {
    background-color: var(--bs-primary);
    color: white;
}

.language-switcher-inline {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.language-switcher-inline .language-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    color: var(--bs-body-color);
    padding: 0.5rem;
    border-radius: 0.375rem;
    transition: all 0.2s ease;
}

.language-switcher-inline .language-link:hover {
    background-color: var(--bs-light);
    color: var(--bs-body-color);
}

.language-switcher-inline .language-link.active {
    background-color: var(--bs-primary);
    color: white;
}

.language-switcher-inline .flag {
    font-size: 1.2rem;
}

.language-switcher-inline .language-name {
    font-weight: 500;
}

/* RTL Support */
[dir="rtl"] .language-switcher .dropdown-toggle {
    flex-direction: row-reverse;
}

[dir="rtl"] .language-switcher-inline .language-link {
    flex-direction: row-reverse;
}

[dir="rtl"] .language-switcher .flag {
    margin-left: 0.5rem;
    margin-right: 0;
}

[dir="rtl"] .language-switcher-inline .flag {
    margin-left: 0.5rem;
    margin-right: 0;
}
</style>
