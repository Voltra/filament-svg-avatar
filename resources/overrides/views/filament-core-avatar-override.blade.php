@props([
    'circular' => true,
    'size' => 'md',
])

<x-filament-svg-avatar::avatar-override
    :attributes="$attributes->except(['alt'])"
    :circular="$circular"
    :size="$size"
></x-filament-svg-avatar::avatar-override>
