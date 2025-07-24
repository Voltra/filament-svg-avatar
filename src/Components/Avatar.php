<?php

declare(strict_types=1);

namespace Voltra\FilamentSvgAvatar\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Spatie\Color\Color;
use Voltra\FilamentSvgAvatar\Contracts\SvgAvatarServiceContract;

class Avatar extends Component
{
    public function __construct(
        public SvgAvatarServiceContract $service,

        /**
         * The initials to display
         */
        public string $initials,

        /**
         * Whether not to inherit the font-family
         */
        public bool $dontInheritFontFamily = false,

        /**
         * Override the background color
         */
        public ?Color $backgroundColor = null,

        /**
         * Override the text color
         */
        public ?Color $textColor = null,

        /**
         * Override the SVG size
         */
        public ?int $size = null,

        /**
         * Override the font-family
         */
        public ?string $fontFamily = null,

        /**
         * Override the text's dy
         */
        public ?string $dy = null,

        /**
         * Override the text's svg font size
         */
        public ?int $fontSize = null,
    ) {}

    /**
     * Renders a view for the avatar component
     */
    public function render(): View
    {
        return view('filament-svg-avatar::components.avatar');
    }

    /**
     * Get the background color to use for this avatar instance
     */
    public function getBackgroundColor(): Color
    {
        return $this->backgroundColor ?? $this->service->getBackgroundColor();

    }

    /**
     * Get the text color to use for this avatar instance
     */
    public function getTextColor(): Color
    {
        return $this->textColor ?? $this->service->getTextColor();

    }

    /**
     * Get the font-family to use for this avatar instance
     */
    public function getFontFamily(): string
    {
        if (! $this->dontInheritFontFamily) {
            return 'inherit';
        }

        return $this->fontFamily ?? $this->service->getFontFamily();
    }

    /**
     * Get the text dy to use for this avatar instance
     */
    public function getTextDy(): string
    {
        return $this->dy ?? $this->service->getTextDy();
    }

    /**
     * Get the text dy to use for this avatar instance
     */
    public function getSvgSize(): int
    {
        // TODO: Get the default size from the interface in the next major version change
        return $this->size ?? 500;
    }

    /**
     * Get the font-size (pixels) to use for this avatar instance
     */
    public function getTextSize(): int
    {
        // TODO: Allow for easy override
        return $this->fontSize ?? $this->getSvgSize() / 2;
    }
}
