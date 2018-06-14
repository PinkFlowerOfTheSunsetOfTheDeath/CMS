<?php
namespace App\Boot;

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
        $this->configManager->configureWebsite($config);
        // Boot Routing configuration
        $routes = $this->bootRouting();
        $this->configManager->configureRouter($routes);
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