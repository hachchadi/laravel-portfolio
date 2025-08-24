<?php

namespace App\Services;

class AccessibilityService
{
    /**
     * Generate ARIA attributes for navigation items
     */
    public function getNavigationAriaAttributes(string $section, bool $isActive = false): array
    {
        return [
            'role' => 'menuitem',
            'aria-current' => $isActive ? 'page' : 'false',
            'aria-label' => "Navigate to {$section} section",
        ];
    }

    /**
     * Generate ARIA attributes for buttons
     */
    public function getButtonAriaAttributes(string $action, bool $isPressed = false): array
    {
        return [
            'role' => 'button',
            'aria-pressed' => $isPressed ? 'true' : 'false',
            'aria-label' => $action,
        ];
    }

    /**
     * Generate ARIA attributes for form fields
     */
    public function getFormFieldAriaAttributes(string $fieldName, bool $hasError = false, ?string $errorId = null): array
    {
        $attributes = [
            'aria-label' => ucfirst(str_replace('_', ' ', $fieldName)),
            'aria-required' => 'true',
        ];

        if ($hasError && $errorId) {
            $attributes['aria-describedby'] = $errorId;
            $attributes['aria-invalid'] = 'true';
        }

        return $attributes;
    }

    /**
     * Generate ARIA attributes for modals
     */
    public function getModalAriaAttributes(string $modalId, string $title): array
    {
        return [
            'role' => 'dialog',
            'aria-modal' => 'true',
            'aria-labelledby' => "{$modalId}-title",
            'aria-describedby' => "{$modalId}-description",
        ];
    }

    /**
     * Generate ARIA attributes for image galleries
     */
    public function getGalleryAriaAttributes(int $currentIndex, int $total): array
    {
        return [
            'role' => 'img',
            'aria-label' => "Image {$currentIndex} of {$total}",
            'aria-live' => 'polite',
        ];
    }

    /**
     * Generate skip link for keyboard navigation
     */
    public function generateSkipLinks(): array
    {
        return [
            ['href' => '#main-content', 'text' => 'Skip to main content'],
            ['href' => '#navigation', 'text' => 'Skip to navigation'],
            ['href' => '#footer', 'text' => 'Skip to footer'],
        ];
    }

    /**
     * Validate color contrast ratio
     */
    public function validateColorContrast(string $foreground, string $background): array
    {
        // Convert hex to RGB
        $fgRgb = $this->hexToRgb($foreground);
        $bgRgb = $this->hexToRgb($background);

        // Calculate relative luminance
        $fgLuminance = $this->getRelativeLuminance($fgRgb);
        $bgLuminance = $this->getRelativeLuminance($bgRgb);

        // Calculate contrast ratio
        $contrastRatio = ($fgLuminance + 0.05) / ($bgLuminance + 0.05);
        if ($contrastRatio < 1) {
            $contrastRatio = 1 / $contrastRatio;
        }

        return [
            'ratio' => round($contrastRatio, 2),
            'aa_normal' => $contrastRatio >= 4.5,
            'aa_large' => $contrastRatio >= 3,
            'aaa_normal' => $contrastRatio >= 7,
            'aaa_large' => $contrastRatio >= 4.5,
        ];
    }

    /**
     * Convert hex color to RGB
     */
    private function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2)),
        ];
    }

    /**
     * Calculate relative luminance
     */
    private function getRelativeLuminance(array $rgb): float
    {
        $colors = [];
        
        foreach ($rgb as $color) {
            $color = $color / 255;
            
            if ($color <= 0.03928) {
                $color = $color / 12.92;
            } else {
                $color = pow(($color + 0.055) / 1.055, 2.4);
            }
            
            $colors[] = $color;
        }

        return 0.2126 * $colors[0] + 0.7152 * $colors[1] + 0.0722 * $colors[2];
    }
}