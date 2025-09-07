@props([
    'type' => 'info', // info, success, warning, error
    'title' => null,
    'message' => null,
])

@php
    $styles = [
        'info' => 'bg-blue-50 border-blue-200 text-blue-900',
        'success' => 'bg-emerald-50 border-emerald-200 text-emerald-900',
        'warning' => 'bg-amber-50 border-amber-200 text-amber-900',
        'error' => 'bg-red-50 border-red-200 text-red-900',
    ];

    $iconColors = [
        'info' => 'text-blue-500',
        'success' => 'text-emerald-500',
        'warning' => 'text-amber-500',
        'error' => 'text-red-500',
    ];

    $icons = [
        'info' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'success' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'warning' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
        'error' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
    ];

    $type = $styles[$type] ? $type : 'info';
@endphp

<div {{ $attributes->merge(['class' => "rounded-xl border p-4 mb-6 shadow-sm {$styles[$type]}"]) }}>
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 {{ $iconColors[$type] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $icons[$type] }}" />
            </svg>
        </div>
        <div class="ml-3 flex-1">
            @if($title)
                <h3 class="text-sm font-semibold">{{ $title }}</h3>
            @endif
            @if($message)
                <p class="{{ $title ? 'mt-1' : '' }} text-sm">{{ $message }}</p>
            @endif
            @if(trim($slot))
                <div class="{{ $title || $message ? 'mt-2' : '' }} text-sm">{{ $slot }}</div>
            @endif
        </div>
    </div>
    @if(session('errors') && $errors->any())
        <ul class="mt-3 text-sm list-disc pl-5 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</div>
