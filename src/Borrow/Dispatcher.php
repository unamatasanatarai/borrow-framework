<?php
namespace Borrow;

class Dispatcher{

    static public function dispatch()
    {
        $dispatcher = \FastRoute\cachedDispatcher(
            function (\FastRoute\RouteCollector $r) {
                require_once ROOT . 'app/config/routes.php';
            }, [
                'cacheFile'     => STORAGE . 'cache/routes.cache',
                'cacheDisabled' => getenv('APP_DEBUG')
            ]
        );

        $routeInfo = $dispatcher->dispatch(
            $_SERVER['REQUEST_METHOD'],
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
        );

        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                http_response_code(404);
                throw new \Exception('Not Found', 1);
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                http_response_code(405);
                throw new \Exception('Method Not Allowed', 1);
                break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                if (strpos($handler, '@') !== false) {
                    $handler .= '@index';
                }
                list($class, $method) = explode('@', $handler);
                $obj = new $class($method, $vars);
                break;
        }
    }
}
