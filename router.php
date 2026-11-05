<?php
/**
 * Router for PHP's built-in server (local testing only).
 *   php -S 127.0.0.1:8080 router.php
 * On Hostinger the .htaccess rules do the same job.
 */
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$path = '/' . ltrim(rawurldecode($path), '/');

// Static assets are served directly.
if ($path !== '/' && is_file(__DIR__ . $path)) {
    return false;
}

if (preg_match('#^/(data|includes)(/|$)#', $path)) {
    http_response_code(404);
    exit('Not found');
}

// Admin area.
if ($path === '/admin') {
    header('Location: /admin/', true, 302);
    exit;
}
if (preg_match('#^/admin/(.*)$#', $path, $m)) {
    $rel = $m[1] === '' ? 'index.php' : $m[1];
    $file = __DIR__ . '/admin/' . $rel;
    if (is_dir($file)) {
        $file = rtrim($file, '/') . '/index.php';
    }
    if (is_file($file)) {
        require $file;
        return true;
    }
    http_response_code(404);
    exit('Not found');
}

$routes = [
    '#^/$#' => ['index.php', []],
    '#^/cn/?$#' => ['index.php', ['lang' => 'cn']],
    '#^/products/([a-z0-9-]+)\.html$#' => ['product.php', ['slug' => 1]],
    '#^/news/([a-z0-9-]+)\.html$#' => ['news-detail.php', ['slug' => 1]],
    '#^/cn/products/([a-z0-9-]+)\.html$#' => ['product.php', ['lang' => 'cn', 'slug' => 1]],
    '#^/cn/news/([a-z0-9-]+)\.html$#' => ['news-detail.php', ['lang' => 'cn', 'slug' => 1]],
    '#^/cn/([a-z0-9-]+)\.html$#' => ['$1.php', ['lang' => 'cn']],
    '#^/([a-z0-9-]+)\.html$#' => ['$1.php', []],
];

foreach ($routes as $pattern => [$script, $params]) {
    if (!preg_match($pattern, $path, $m)) {
        continue;
    }
    foreach ($params as $key => $value) {
        $_GET[$key] = is_int($value) ? ($m[$value] ?? '') : $value;
    }
    if (str_contains($script, '$1')) {
        $script = preg_replace($pattern, $script, $path);
    }
    $file = __DIR__ . '/' . $script;
    if (!is_file($file)) {
        break;
    }
    require $file;
    return true;
}

http_response_code(404);
$_GET = [];
require __DIR__ . '/404.php';
return true;
