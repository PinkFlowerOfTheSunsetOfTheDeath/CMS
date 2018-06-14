<?php
namespace App\Boot;

/**
 * Class ThemeManager
 * @package App\Boot
 */
class ThemeManager
{
    /**
     * @var string - theme name
     */
    public static $theme = '';

    public static function setTheme(string $theme)
    {
        // Create front controller -> render method calls ::getTheme
        self::$theme = $theme;
    }

    /**
     * Get current active Theme
     * @return string - Current active theme
     */
    public static function getTheme(): string
    {
        return self::$theme;
    }
}