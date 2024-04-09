@props([
    'circular' => true,
    'size' => 'md',
])

<x-filament-svg-avatar::avatar-override
    :attributes="$attributes"
    :circular="$circular"
    :size="$size"
></x-filament-svg-avatar::avatar-override>
