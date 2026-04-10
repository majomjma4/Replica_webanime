<?php

declare(strict_types=1);

use ReplicaCi4\Controllers\Api\Activity;
use ReplicaCi4\Controllers\Api\Auth;
use ReplicaCi4\Controllers\Api\JikanProxy;
use ReplicaCi4\Controllers\Api\AnimeData;
use ReplicaCi4\Controllers\Api\Comments;
use ReplicaCi4\Controllers\Api\SaveAnime;
use ReplicaCi4\Controllers\Api\Profile;
use ReplicaCi4\Controllers\Home;
use ReplicaCi4\Controllers\Pages;
use ReplicaCi4\Controllers\Partials;

require dirname(__DIR__) . '/app/Config/bootstrap.php';

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
        $partial = (string) ($routeParts[1] ?? '');
        if ($partial === 'layout') {
            (new Partials())->layout();
            break;
        }
        if ($partial === 'admin-layout') {
            (new Partials())->adminLayout();
            break;
        }
        http_response_code(404);
        echo 'Replica CI4: ruta no encontrada.';
        break;

    case 'api':
        switch ($routeParts[1] ?? '') {
            case 'auth':
            case 'auth.php':
                (new Auth())->handle();
                break;
            case 'profile':
            case 'profile.php':
                (new Profile())->handle();
                break;
            case 'activity':
            case 'activity.php':
                (new Activity())->handle();
                break;
            case 'jikan_proxy':
            case 'jikan_proxy.php':
                (new JikanProxy())->handle();
                break;
            case 'anime_data':
            case 'anime_data.php':
                (new AnimeData())->handle();
                break;
            case 'comments':
            case 'comments.php':
                (new Comments())->handle();
                break;
            case 'save_anime':
            case 'save_anime.php':
                (new SaveAnime())->handle();
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
    case 'pago':
    case 'admin':
    case 'user':
        (new Pages())->show($primaryRoute);
        break;

    case 'detail':
        $segment = trim((string) ($routeParts[1] ?? ''));
        if ($segment !== '') {
            $_GET['_detail_ref'] = $segment;
        }
        (new Pages())->show('detail');
        break;

    default:
        http_response_code(404);
        echo 'Replica CI4: ruta no encontrada.';
        break;
}
