<?php
namespace App\Controllers;
use App\Boot\ConfigManager;
use App\Boot\ThemeManager;
use App\Entities\Theme;
use App\Helpers\Controller;

/**
 * Class ThemeController
 * @package App\Controllers
 */
class ThemeController extends Controller
{
    const THEME_DIR = __DIR__ . '/../../public/content/themes/';
    const ERROR__NOT_FOUND = 'Theme not found: ';

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
                'name' => $theme,
                'selected' => $theme == ThemeManager::getTheme()
            ];
            return new Theme($themeData);
        }, $themes);

        return $this->render('themes/list.html.twig', ['themes' => $themes ?? []]);
    }

    /**
     * Select a theme to display in front office
     * @param string $name - Name of the theme to select
     */
    public function selectAction(string $name)
    {
        $themeDirs = scandir(self::THEME_DIR);

        // if theme not found from custom themes directory, redirect to theme listing
        if (!in_array($name, $themeDirs)) {
            $error = self::ERROR__NOT_FOUND . $name;
            $this->redirectWithError('/themes', $error);
            exit;
        }

        // Update website configuration with new active theme
        $configManager = new ConfigManager();
        $configManager->updateActiveTheme($name);

        // Redirect to themes listing page
        $this->redirect('/themes');
    }
}