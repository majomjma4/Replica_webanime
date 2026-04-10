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

// Pages
$routes->get('detail', '\\App\\Controllers\\Pages::detail');
$routes->get('detail/(:segment)', '\\App\\Controllers\\Pages::detail/$1');

$routes->get('(:segment)', '\\App\\Controllers\\Pages::show/$1');
$routes->get('(:segment).php', '\\App\\Controllers\\Pages::show/$1');
