<?php
namespace App\Boot;

use App\Repositories\UserRepository;

/**
 * Class Kernel - Boot the application
 * @package App\Boot
 */
class Kernel
{
    /**
     * @var ConfigManager
     */
    private $configManager;

    /**
     * Boot Application's ConfigManager, load and parse configuration
     * @return array - Configuration
     */
    private function bootConfiguration(): array
    {
        $this->configManager = new ConfigManager();
        $config = $this->configManager->loadConfiguration();
        return $config;
    }

    /**
     * Boot Routing from configuration
     * @return \Symfony\Component\Routing\RouteCollection
     */
    private function bootRouting()
    {
        $routes = $this->configManager->loadRoutes();
        return $routes;
    }

    /**
     * Boot the application
     */
    public function bootApplication()
    {
        // Boot Application Configuration - Database and Theme
        $config = $this->bootConfiguration();
        // Init application
        $this->initializeApplicaton($config);
        // Boot Routing configuration
        $routes = $this->bootRouting();
        $this->configManager->configureRouter($routes);
    }

    /**
     * Init Application configuration and Database + first user on first login
     * @param array $config
     */
    public function initializeApplicaton(array $config)
    {
        // If website is not initialized
        $path = $_SERVER['PATH_INFO'] ?? '/';
        if (
            $path !== '/configure'
            && (!isset($config['website']['initialized'])
                || !$config['website']['initialized'])
        ) {
            // Init controller
            header('Location: /configure');
            exit;
        }

        // Configure Website
        $this->configManager->configureWebsite($config);

        try {
            $userRepository = new UserRepository();
            $adminExists = $userRepository->adminExists();
        } catch (\PDOException $e) {
            $adminExists = false;
        }
        // If website is initialized but Admin User not created
        if (
            $path !== '/configure'
            && $path !== '/first-register'
            && !$adminExists
        ) {
            // Init controller
            header('Location: /first-register');
            exit;
        }
    }

    /**
     * Run The Application
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function run()
    {
        $middlewares = $this->loadMiddlewares();
        Router::route($middlewares);
    }

    /**
     * Boot Application Middlewares defined in loadMiddlewares
     */
    public function bootMiddlewares()
    {
        // Load specified Middlewares
        $middlewares = $this->loadMiddlewares();
    }

    /**
     * Configure middlewares to use -> path => MiddlewareClass::method
     * @return array - Array of middlewares to load at booting
     */
    public function loadMiddlewares()
    {
        return [
            'AuthMiddleware::middlewareCheck' => 'isAuthenticated'
        ];
    }
}