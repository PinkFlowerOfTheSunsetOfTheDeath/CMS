<?php
namespace App\Boot;

use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Request;
use App\Helpers\Controller;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class Router
 * @package App\Boot
 */
class Router
{
    /**
     * @var RouteCollection
     */
    private static $routes = null;

    /**
     * @param RouteCollection $config
     */
    public static function configureRouter(RouteCollection $config)
    {
        self::$routes = $config;
    }

    /**
     * @param array $middlewares
     * @param string $path
     */
    private static function executeMiddlewares(array $middlewares, string $path)
    {
        foreach ($middlewares as $middlewareCondition => $middlewareAction) {
            // parse Middleware condition
            $middlewareClass = "App\\Middlewares\\" . explode('::', $middlewareCondition)[0];
            $middlewareCheck = explode('::', $middlewareCondition)[1];

            // Create middleware object
            $middlewareObject = new $middlewareClass();
            // Execute Middleware's check
            $condition = $middlewareObject->${$middlewareCheck}($path);

            if (
                !$condition
                || !method_exists($middlewareObject, $middlewareCheck)
                || !method_exists($middlewareObject, $middlewareAction)
            ) {
                continue;
            }

            // execute middleware
            call_user_func([$middlewareObject, $middlewareAction]);
        }
    }

    /**
     * @param array $middlewares - Array of middlewares
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public static function route(array $middlewares = [])
    {
        // Request Object from Http foundation
        $request = Request::createFromGlobals();

        // Create request context for routing
        $requestContext = new RequestContext();
        $requestContext->fromRequest($request);

        // Execute Middlewares
        self::executeMiddlewares($middlewares, $request->getPathInfo());

        // Create URL Matcher
        $matcher = new UrlMatcher(self::$routes, $requestContext);

        try {
            $parameters = $matcher->match($request->getPathInfo());
        } catch (ResourceNotFoundException $e) {
            // Display 404.php
            http_response_code(404);
            echo Controller::error('You are trying to access a route which does not exist');
            exit;
        }

        $controllerName = explode('::', $parameters['_controller'])[0];
        $actionName = explode('::', $parameters['_controller'])[1];

        // TODO: Check that Controller and method exists before using it
        try {
            $controller = new $controllerName();
        } catch (Exception $e) {
            echo Controller::error($e->getMessage());
            exit;
        }

        // Unset controller and route parameters
        unset($parameters['_controller']);
        unset($parameters['_route']);

        // Execute
        try {
            echo call_user_func_array([$controller, $actionName], $parameters);
        } catch (Exception $e) {
            echo Controller::error($e->getMessage());
        }
    }
}