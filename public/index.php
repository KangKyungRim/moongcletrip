<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/bootstrap.php';
require_once __DIR__ . '/../config/variables.php';

header("Access-Control-Allow-Origin: *");

ini_set('session.cache_expire', 7200);
ini_set('session.gc_maxlifetime', 31536000);
ini_set('session.use_trans_sid', 0);
ini_set('url_rewriter.tags', '');
ini_set("session.gc_probability", 1);
ini_set("session.gc_divisor", 100);
ini_set('session.cookie_secure', true);
ini_set('session.cookie_httponly', true);
ini_set("session.cookie_samesite", "None");

session_save_path(__DIR__ . '/../sessions');
session_cache_limiter('nocache, must_revalidate');

session_set_cookie_params([
    'lifetime' => 31536000,
    'path' => '/',
]);

if (strpos($_SERVER['REQUEST_URI'], '/api/webhook/onda') === false && session_status() === PHP_SESSION_NONE) {
    session_start();
}

Database::getInstance();

function loadRoutes()
{
    $routes = [];
    foreach (glob(__DIR__ . '/../app/Routes/*.php') as $file) {
        $routes = array_merge_recursive($routes, include $file);
    }
    return $routes;
}

function route($method, $uri)
{
    $routes = loadRoutes();

    foreach ($routes[$method] as $route => $action) {
        $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $route);
        $pattern = str_replace('/', '\/', $pattern);
        if (preg_match('/^' . $pattern . '$/', $uri, $matches)) {
            array_shift($matches);
            return call_user_func_array($action, $matches);
        }
    }

    http_response_code(404);
    echo '404 - Not Found';
}

$headers = getallheaders();

if (!empty($headers['Devicetoken'])) {
    $_SESSION['device_token'] = $headers['Devicetoken'];
    setcookie('deviceValidate', $headers['Devicetoken'], time() + (60 * 60 * 24 * 365), '/', $_ENV['HOST_NAME'], true, true);
}


// 요청 URI 및 메서드를 가져옵니다.
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 요청 URI를 라우팅 테이블과 매칭하여 해당하는 컨트롤러를 호출합니다.
$response = route($method, $uri);
echo $response;
