<?php

declare(strict_types=1);

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Home
$routes->get('/', '\\ReplicaCi4\\Controllers\\Home::index');
$routes->get('index', '\\ReplicaCi4\\Controllers\\Home::index');

// Partials
$routes->get('partials/layout', '\\ReplicaCi4\\Controllers\\Partials::layout');
$routes->get('partials/admin-layout', '\\ReplicaCi4\\Controllers\\Partials::adminLayout');

// API (compat: sin y con .php)
$routes->match(['get','post','options'], 'api/auth', '\\ReplicaCi4\\Controllers\\Api\\Auth::handle');
$routes->match(['get','post','options'], 'api/auth.php', '\\ReplicaCi4\\Controllers\\Api\\Auth::handle');
$routes->match(['get','post','options'], 'api/profile', '\\ReplicaCi4\\Controllers\\Api\\Profile::handle');
$routes->match(['get','post','options'], 'api/profile.php', '\\ReplicaCi4\\Controllers\\Api\\Profile::handle');
$routes->match(['get','post','options'], 'api/activity', '\\ReplicaCi4\\Controllers\\Api\\Activity::handle');
$routes->match(['get','post','options'], 'api/activity.php', '\\ReplicaCi4\\Controllers\\Api\\Activity::handle');
$routes->match(['get','post','options'], 'api/jikan_proxy', '\\ReplicaCi4\\Controllers\\Api\\JikanProxy::handle');
$routes->match(['get','post','options'], 'api/jikan_proxy.php', '\\ReplicaCi4\\Controllers\\Api\\JikanProxy::handle');
$routes->match(['get','post','options'], 'api/anime_data', '\\ReplicaCi4\\Controllers\\Api\\AnimeData::handle');
$routes->match(['get','post','options'], 'api/anime_data.php', '\\ReplicaCi4\\Controllers\\Api\\AnimeData::handle');
$routes->match(['get','post','options'], 'api/comments', '\\ReplicaCi4\\Controllers\\Api\\Comments::handle');
$routes->match(['get','post','options'], 'api/comments.php', '\\ReplicaCi4\\Controllers\\Api\\Comments::handle');
$routes->match(['get','post','options'], 'api/save_anime', '\\ReplicaCi4\\Controllers\\Api\\SaveAnime::handle');
$routes->match(['get','post','options'], 'api/save_anime.php', '\\ReplicaCi4\\Controllers\\Api\\SaveAnime::handle');
$routes->match(['get','post','options'], 'api/admin', '\\ReplicaCi4\\Controllers\\Api\\Admin::handle');
$routes->match(['get','post','options'], 'api/admin.php', '\\ReplicaCi4\\Controllers\\Api\\Admin::handle');
$routes->match(['get','post','options'], 'api/requests', '\\ReplicaCi4\\Controllers\\Api\\Requests::handle');
$routes->match(['get','post','options'], 'api/requests.php', '\\ReplicaCi4\\Controllers\\Api\\Requests::handle');
$routes->match(['get','post','options'], 'api/users', '\\ReplicaCi4\\Controllers\\Api\\Users::handle');
$routes->match(['get','post','options'], 'api/users.php', '\\ReplicaCi4\\Controllers\\Api\\Users::handle');

// Pages
$routes->get('detail', '\\ReplicaCi4\\Controllers\\Pages::detail');
$routes->get('detail/(:segment)', '\\ReplicaCi4\\Controllers\\Pages::detail/$1');

$routes->get('(:segment)', '\\ReplicaCi4\\Controllers\\Pages::show/$1');
$routes->get('(:segment).php', '\\ReplicaCi4\\Controllers\\Pages::show/$1');
