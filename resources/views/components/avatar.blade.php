@php($size = $getSvgSize())

<svg width="{{ $size }}px" height="{{ $size }}px" viewBox="0 0 {{ $size }} {{ $size }}" xmlns="http://www.w3.org/2000/svg">
    <rect x="0" y="0" width="{{ $size }}" height="{{ $size }}" rx="0" style="fill:{{ $getBackgroundColor() }};"/>
    <text x="50%" y="50%" dy="{{ $getTextDy() }}" fill="{{ $getTextColor() }}" text-anchor="middle" dominant-baseline="middle" style="font-family: {{ $getFontFamily() }}; font-size: {{ $getTextSize() }}px; line-height: 1;">
        {{ $initials }}
    </text>
</svg>
