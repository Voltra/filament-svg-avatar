<?php

declare(strict_types=1);

// config for voltra/filament-svg-avatar
return [
    /**
     * Global config of the SVG size
     *
     * @type ?int
     *
     * @default 500
     */
    'svgSize' => null,

    /**
     * Global config for the avatar's background color (as a hex code)
     *
     * @type ?string
     */
    'backgroundColor' => null,

    /**
     * Global config for the avatar's text color (as a hex code)
     *
     * @type ?string
     */
    'textColor' => null,

    /**
     * Global config for whether to disallow the plugin from overriding colors
     *
     * @type bool
     *
     * @default false
     */
    'disallowPluginOverride' => false,

    /**
     * Global config for the avatar's font-family
     *
     * @type ?string
     *
     * @default \Filament\Facades\Filament::getFontFamily()
     */
    'fontFamily' => null,

    /**
     * Global config of the SVG size
     *
     * @type ?string
     *
     * @default '.1em'
     */
    'textDy' => null,
];
