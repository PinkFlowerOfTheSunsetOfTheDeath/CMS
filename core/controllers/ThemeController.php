<?php
namespace App\Controllers;
use App\Entities\Theme;
use App\Helpers\Controller;

/**
 * Class ThemeController
 * @package App\Controllers
 */
class ThemeController extends Controller
{
    const THEME_DIR = __DIR__ . '/../../public/content/themes/';

    /**
     * @return string - HTML Layour for themes management page
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function listAction(): string
    {
        // Scan theme directories
        $themeDirs = scandir(self::THEME_DIR);
        // Retrieve only theme directories
        $themes = array_filter($themeDirs, function($themeDir) {
            $noDirs = ['.', '..'];
           return !in_array($themeDir, $noDirs);
        });

        $themes = array_map(function($theme) {
            $themeData = [
                'image' => "/content/themes/$theme/screenshot.jpg",
                'name' => $theme
            ];
            return new Theme($themeData);
        }, $themes);

        return $this->render('themes/list.html.twig', ['themes' => $themes ?? []]);
    }
}