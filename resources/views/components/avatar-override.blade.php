@props([
    'circular' => true,
    'size' => 'md',
])

@php
$initials = $attributes->has('alt') ? \Voltra\FilamentSvgAvatar\Services\Utils::initials($attributes->get('alt')) : '';
$initials = $initials ?: ($attributes->has('src') ? $attributes->get('src') : '');
@endphp

<x-filament-svg-avatar::avatar
    :attributes="$attributes->class([
        'fi-avatar object-cover object-center',
        'rounded-md' => ! $circular,
        'fi-circular rounded-full' => $circular,
        match ($size) {
            'sm' => 'h-6 w-6',
            'md' => 'h-8 w-8',
            'lg' => 'h-10 w-10',
            default => $size,
        },
    ])"
    :initials="$initials"
></x-filament-svg-avatar::avatar>
