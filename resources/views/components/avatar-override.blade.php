@props([
    'circular' => true,
    'size' => 'md',
])

@php
/**
 * @var \Voltra\FilamentSvgAvatar\Contracts\SvgAvatarServiceContract $service
 */
$service = resolve(\Voltra\FilamentSvgAvatar\Contracts\SvgAvatarServiceContract::class);
$initials = $attributes->has('alt') ? $service->getInitials($attributes->get('alt')) : null;
@endphp

<x-filament-svg-avatar::avatar
    :initials="$attributes->get('alt')"
    {{
        $attributes
            ->class([
                'fi-avatar object-cover object-center',
                'rounded-md' => ! $circular,
                'fi-circular rounded-full' => $circular,
                match ($size) {
                    'sm' => 'h-6 w-6',
                    'md' => 'h-8 w-8',
                    'lg' => 'h-10 w-10',
                    default => $size,
                },
            ])
    }}
></x-filament-svg-avatar::avatar>
