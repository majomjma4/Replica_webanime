<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/Config/bootstrap.php';

use ReplicaCi4\Controllers\Api\Activity;
use ReplicaCi4\Controllers\Api\Auth;
use ReplicaCi4\Controllers\Api\JikanProxy;
use ReplicaCi4\Controllers\Api\Profile;
use ReplicaCi4\Controllers\Home;
use ReplicaCi4\Controllers\Pages;
use ReplicaCi4\Controllers\Partials;

$requestUri = (string) ($_SERVER['REQUEST_URI'] ?? '/');
$scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
$baseDir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/.');
$path = (string) parse_url($requestUri, PHP_URL_PATH);
$replicaDir = '/' . basename(dirname(__DIR__));
$appUrlPath = rtrim((string) parse_url((string) app_env('APP_URL', ''), PHP_URL_PATH), '/');

$prefixes = array_values(array_filter([
    $baseDir,
    $appUrlPath,
    $replicaDir,
], static fn (string $prefix): bool => $prefix !== '' && $prefix !== '/'));

usort($prefixes, static fn (string $a, string $b): int => strlen($b) <=> strlen($a));

foreach ($prefixes as $prefix) {
    if (str_starts_with($path, $prefix . '/')) {
        $path = substr($path, strlen($prefix));
        break;
    }

    if ($path === $prefix) {
        $path = '/';
        break;
    }
}

$route = trim($path, '/');
$normalizedRoute = preg_replace('/\.php$/i', '', $route) ?? $route;
$routeParts = $normalizedRoute === '' ? [] : explode('/', $normalizedRoute);
$primaryRoute = $routeParts[0] ?? '';

switch ($primaryRoute) {
    case '':
    case 'index':
        (new Home())->index();
        break;

    case 'partials':
        if (($routeParts[1] ?? '') === 'layout') {
            (new Partials())->layout();
            break;
        }
        http_response_code(404);
        echo 'Replica CI4: ruta no encontrada.';
        break;

    case 'api':
        switch ($routeParts[1] ?? '') {
            case 'auth':
                (new Auth())->handle();
                break;
            case 'profile':
                (new Profile())->handle();
                break;
            case 'activity':
                (new Activity())->handle();
                break;
            case 'jikan_proxy':
                (new JikanProxy())->handle();
                break;
            default:
                http_response_code(404);
                echo 'Replica CI4: ruta no encontrada.';
                break;
        }
        break;

    case 'destacados':
    case 'ranking':
    case 'series':
    case 'peliculas':
    case 'registro':
    case 'ingresar':
    case 'admin':
    case 'user':
    case 'detail':
        (new Pages())->show($primaryRoute);
        break;

    default:
        http_response_code(404);
        echo 'Replica CI4: ruta no encontrada.';
        break;
}
