<?php

require 'vendor/autoload.php';

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Request;
use App\Controllers\BaseController;

// Request Object from Http foundation
$request = Request::createFromGlobals();

// Loader configuration file for routing
$fileLocator = new FileLocator([__DIR__ . '/core/config']);
$loader = new YamlFileLoader($fileLocator);
$routes = $loader->load('routing.yaml');


// Create request context for routing
$requestContext = new RequestContext();
$requestContext->fromRequest($request);


$matcher = new UrlMatcher($routes, $requestContext);

try {
    $parameters = $matcher->match($request->getPathInfo());
} catch (Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
    // Display 404.php
    http_response_code(404);
    echo BaseController::error('You are trying to access a route which does not exist');
    exit;
}

$controllerName = explode('::', $parameters['_controller'])[0];
$actionName = explode('::', $parameters['_controller'])[1];

// TODO: Check that Controller and method exists before using it
try {
    $controller = new $controllerName();
} catch (Exception $e) {
    echo BaseController::error($e->getMessage());
    exit;
}

// Unset controller and route parameters
unset($parameters['_controller']);
unset($parameters['_route']);

// Execute
try {
    echo call_user_func_array([$controller, $actionName], $parameters);
} catch (Exception $e) {
   echo BaseController::error($e->getMessage());
}

