@props([
    'circular' => true,
    'size' => 'md',
])

@php($initials = $attributes->has('src') ? $attributes->get('src') : '')

<x-filament-svg-avatar::avatar
    :attributes="$attributes->class([
        'fi-avatar object-cover object-center',
        'rounded-md' => ! $circular,
        'fi-circular rounded-full' => $circular,
        match ($size) {
            'sm' => sprintf('h-6 w-6 fi-size-%s', $size),
            'md' => sprintf('h-8 w-8 fi-size-%s', $size),
            'lg' => sprintf('h-10 w-10 fi-size-%s', $size),
            default => $size,
        },
    ])->except(['src', 'alt'])"
    :initials="$initials"
></x-filament-svg-avatar::avatar>
