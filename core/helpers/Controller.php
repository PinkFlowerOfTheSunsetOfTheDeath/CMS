<?php

namespace App\Helpers;
use App\Boot\ThemeManager;

class Controller {
    public static $viewDirectory = __DIR__ . '/../views/';

    /**
     * Initialize twig environment
     * @return \Twig_Environment
     */
    private static function initializeTwig() {
        $loader = new \Twig_Loader_Filesystem(self::$viewDirectory);
        return new \Twig_Environment($loader);
    }

    /**
     * Render a views and pass it parameters
     * @param string $fileName - Name of the views file to load
     * @param array $variables - Custom variables to pass to the views,
     * @return string - HTML Structure of the retrieved views
     * @throws \Twig_Error_Loader - Could not load given Directory
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(string $fileName, array $variables = []): string {
        $twig = self::initializeTwig();
        return $twig->render($fileName, $variables);
    }

    /**
     * Render Front office views from active theme
     * @param string $fileName - Name of the file to load
     * @param array $data
     */
    public function renderFront(string $fileName, array $data = [])
    {
        // TODO: Check that theme/file/dir exists
        $viewDir = ThemeManager::getTheme();
        $file = __DIR__ . "/../../public/content/themes/$viewDir/$fileName";

        if (!file_exists($file)) {
            // le chemin de la vue ne correspond pas à un fichier existant
            throw new \InvalidArgumentException('Fichier de vue '.$fileName.' non trouvé');
        }
        ob_start();
        require $file;
        echo ob_get_clean();
        exit;
    }

    /**
     * Render the error views, and pass it Error Message
     * @param string $message - Error message to display to the user
     * @return string - HTML Structure of the error views
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public static function error(string $message) {
        $twig = self::initializeTwig();
        return $twig->render('error.html.twig', ['message' => $message]);
    }

    /**
     * Redirect a user to a certain URL
     * @param string $path - Path to
     */
    public function redirect(string $path)
    {
        header("Location: $path");
        exit;
    }

    /**
     * Redirect User to given url with error
     * @param string $path - Path to redirect to
     * @param string $error - Error message to display
     */
    public function redirectWithError(string $path, string $error): void
    {
        $redirectUrl = "$path?error=$error";
        $this->redirect($redirectUrl);
    }
}
