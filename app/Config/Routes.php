<?php

declare(strict_types=1);

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Home
$routes->get('/', '\\App\\Controllers\\Home::index');
$routes->get('index', '\\App\\Controllers\\Home::index');

// Partials
$routes->get('partials/layout', '\\App\\Controllers\\Partials::layout');
$routes->get('partials/admin-layout', '\\App\\Controllers\\Partials::adminLayout');

// API (compat: sin y con .php)
$routes->match(['get','post','options'], 'api/auth', '\\App\\Controllers\\Api\\Auth::handle');
$routes->match(['get','post','options'], 'api/auth.php', '\\App\\Controllers\\Api\\Auth::handle');
$routes->match(['get','post','options'], 'api/profile', '\\App\\Controllers\\Api\\Profile::handle');
$routes->match(['get','post','options'], 'api/profile.php', '\\App\\Controllers\\Api\\Profile::handle');
$routes->match(['get','post','options'], 'api/activity', '\\App\\Controllers\\Api\\Activity::handle');
$routes->match(['get','post','options'], 'api/activity.php', '\\App\\Controllers\\Api\\Activity::handle');
$routes->match(['get','post','options'], 'api/jikan_proxy', '\\App\\Controllers\\Api\\JikanProxy::handle');
$routes->match(['get','post','options'], 'api/jikan_proxy.php', '\\App\\Controllers\\Api\\JikanProxy::handle');
$routes->match(['get','post','options'], 'api/anime_data', '\\App\\Controllers\\Api\\AnimeData::handle');
$routes->match(['get','post','options'], 'api/anime_data.php', '\\App\\Controllers\\Api\\AnimeData::handle');
$routes->match(['get','post','options'], 'api/comments', '\\App\\Controllers\\Api\\Comments::handle');
$routes->match(['get','post','options'], 'api/comments.php', '\\App\\Controllers\\Api\\Comments::handle');
$routes->match(['get','post','options'], 'api/save_anime', '\\App\\Controllers\\Api\\SaveAnime::handle');
$routes->match(['get','post','options'], 'api/save_anime.php', '\\App\\Controllers\\Api\\SaveAnime::handle');
$routes->match(['get','post','options'], 'api/admin', '\\App\\Controllers\\Api\\Admin::handle');
$routes->match(['get','post','options'], 'api/admin.php', '\\App\\Controllers\\Api\\Admin::handle');
$routes->match(['get','post','options'], 'api/requests', '\\App\\Controllers\\Api\\Requests::handle');
$routes->match(['get','post','options'], 'api/requests.php', '\\App\\Controllers\\Api\\Requests::handle');
$routes->match(['get','post','options'], 'api/users', '\\App\\Controllers\\Api\\Users::handle');
$routes->match(['get','post','options'], 'api/users.php', '\\App\\Controllers\\Api\\Users::handle');

// Anime Controller
$routes->get('series', '\\App\\Controllers\\AnimeController::series');
$routes->get('peliculas', '\\App\\Controllers\\AnimeController::peliculas');
$routes->get('detail', '\\App\\Controllers\\AnimeController::detail');
$routes->get('detail/(:segment)', '\\App\\Controllers\\AnimeController::detail/$1');

// Home Controller (Destacados y Ranking)
$routes->get('destacados', '\\App\\Controllers\\Home::destacados');
$routes->get('ranking', '\\App\\Controllers\\Home::ranking');

// User Controller
$routes->get('registro', '\\App\\Controllers\\UserController::registro');
$routes->get('ingresar', '\\App\\Controllers\\UserController::ingresar');

$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('user', '\\App\\Controllers\\UserController::perfil');
    $routes->get('pago', '\\App\\Controllers\\UserController::pago');
});

// Admin Controller
$routes->group('admin_panel', ['filter' => 'admin'], function($routes) {
    $routes->get('/', '\\App\\Controllers\\AdminController::dashboard');
    $routes->get('admin', '\\App\\Controllers\\AdminController::dashboard');
    $routes->get('gestion', '\\App\\Controllers\\AdminController::gestion');
    $routes->get('gesus', '\\App\\Controllers\\AdminController::gesus');
    $routes->get('gescom', '\\App\\Controllers\\AdminController::gescom');
    $routes->get('anadir', '\\App\\Controllers\\AdminController::anadir');
});

// Fallbacks legacy without admin prefix (to avoid breaking current nav, but filtered)
$routes->group('', ['filter' => 'admin'], function($routes) {
    $routes->get('admin', '\\App\\Controllers\\AdminController::dashboard');
    $routes->get('gestion', '\\App\\Controllers\\AdminController::gestion');
    $routes->get('gesus', '\\App\\Controllers\\AdminController::gesus');
    $routes->get('gescom', '\\App\\Controllers\\AdminController::gescom');
    $routes->get('anadir', '\\App\\Controllers\\AdminController::anadir');
});
