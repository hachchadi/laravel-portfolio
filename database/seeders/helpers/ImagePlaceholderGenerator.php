<?php

namespace Database\Seeders\Helpers;

use Illuminate\Support\Facades\Storage;

class ImagePlaceholderGenerator
{
    /**
     * Create a simple placeholder image file
     */
    public static function createPlaceholder(string $path, int $width = 800, int $height = 600, string $text = 'Placeholder'): void
    {
        // Create a simple SVG placeholder
        $svg = self::generateSvgPlaceholder($width, $height, $text);
        
        // Save the SVG as a placeholder
        Storage::disk('public')->put($path, $svg);
    }

    /**
     * Generate SVG placeholder content
     */
    private static function generateSvgPlaceholder(int $width, int $height, string $text): string
    {
        $colors = [
            '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
            '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6366F1'
        ];
        
        $bgColor = $colors[array_rand($colors)];
        $textColor = '#FFFFFF';
        
        return <<<SVG
<svg width="{$width}" height="{$height}" xmlns="http://www.w3.org/2000/svg">
    <rect width="100%" height="100%" fill="{$bgColor}"/>
    <text x="50%" y="50%" font-family="Arial, sans-serif" font-size="24" fill="{$textColor}" text-anchor="middle" dominant-baseline="middle">{$text}</text>
    <text x="50%" y="60%" font-family="Arial, sans-serif" font-size="14" fill="{$textColor}" text-anchor="middle" dominant-baseline="middle">{$width}x{$height}</text>
</svg>
SVG;
    }

    /**
     * Create avatar placeholder
     */
    public static function createAvatarPlaceholder(string $path, string $name): void
    {
        $initials = self::getInitials($name);
        $svg = self::generateAvatarSvg($initials);
        Storage::disk('public')->put($path, $svg);
    }

    /**
     * Get initials from name
     */
    private static function getInitials(string $name): string
    {
        $words = explode(' ', $name);
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper($word[0]);
            }
        }
        
        return substr($initials, 0, 2);
    }

    /**
     * Generate avatar SVG
     */
    private static function generateAvatarSvg(string $initials): string
    {
        $colors = [
            '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
            '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6366F1'
        ];
        
        $bgColor = $colors[array_rand($colors)];
        
        return <<<SVG
<svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
    <circle cx="100" cy="100" r="100" fill="{$bgColor}"/>
    <text x="50%" y="50%" font-family="Arial, sans-serif" font-size="60" fill="white" text-anchor="middle" dominant-baseline="middle">{$initials}</text>
</svg>
SVG;
    }
}