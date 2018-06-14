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
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public static function route()
    {
        // TODO: Manage middlewares
        // Request Object from Http foundation
        $request = Request::createFromGlobals();

        // Create request context for routing
        $requestContext = new RequestContext();
        $requestContext->fromRequest($request);


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