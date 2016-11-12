<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

function dd()
{
    echo '<pre>';
    var_dump(func_get_args());
    echo '</pre>';
    exit;
}

$app = new app\App();

$routes = $app->routes;

$url = $_SERVER['PATH_INFO'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'];

if (isset($routes[$url])) {

    if ($method === 'GET') {
        $request = $_GET;
    } else {
        $json = file_get_contents('php://input');
        $request = json_decode($json, true);
    }

    $class = $routes[$url];

    if (is_callable($class)) {
        $class();
    } else {
        $data = explode('@', $class);

        $ref = new ReflectionClass($data[0]);
        $ref->getMethod($data[1])->invokeArgs($ref->newInstance(), [$request]);
    }
}