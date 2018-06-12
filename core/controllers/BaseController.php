<?php

namespace App\Controllers;

class BaseController {

    /**
     * Initialize twig environment
     * @return \Twig_Environment
     */
    private static function initializeTwig() {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../views/');
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

}