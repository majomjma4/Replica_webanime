<?php

declare(strict_types=1);

namespace ReplicaCi4\Controllers;

use ReplicaCi4\Models\Anime;

final class Pages extends BaseController
{
    public function detail(string $segment = ""): void
    {
        if (trim($segment) !== "") {
            $_GET["_detail_ref"] = $segment;
        }

        $this->show("detail");
    }

    public function show(string $slug): void
    {
        $pages = $this->pageMap();
        $page = $pages[$slug] ?? null;

        if (!is_array($page)) {
            http_response_code(404);
            echo 'Replica CI4: pagina no encontrada.';
            return;
        }

        if ($slug === 'series' || $slug === 'peliculas') {
            $animeModel = new Anime();
            $dbGenres = $animeModel->getFilteredGenres();
            $animes = $slug === 'peliculas' ? $animeModel->getMovies() : $animeModel->getSeries();
            $this->render('pages/' . $slug, [
                'slug' => $slug,
                'page' => $page,
                'dbGenres' => $dbGenres,
                'animes' => $animes,
            ]);
            return;
        }

        if ($slug === 'detail') {
            app_start_session();
            $detailRef = trim((string) ($_GET['_detail_ref'] ?? ''));
            $legacyId = trim((string) ($_GET['mal_id'] ?? $_GET['id'] ?? ''));
            $legacyTitle = trim((string) ($_GET['q'] ?? ''));
            if ($detailRef === '') {
                $detailRef = app_detail_ref_from_input($legacyId, $legacyTitle);
            }
            $detailQuery = $detailRef !== '' && !ctype_digit($detailRef) ? str_replace('-', ' ', $detailRef) : '';
            $isLoggedIn = isset($_SESSION['user_id']);
            $sessionRole = (string) ($_SESSION['role'] ?? 'Invitado');
            $sessionPremium = !empty($_SESSION['premium']) || $sessionRole === 'Admin';

            $this->render('pages/detail', [
                'slug' => $slug,
                'page' => $page,
                'detailRef' => $detailRef,
                'detailQuery' => $detailQuery,
                'isLoggedIn' => $isLoggedIn,
                'sessionRole' => $sessionRole,
                'sessionPremium' => $sessionPremium,
            ]);
            return;
        }

        $viewCandidates = [
            APP_ROOT . '/Views/pages/' . $slug . '.php',
            APP_ROOT . '/Views/Views/pages/' . $slug . '.php',
        ];
        $viewName = is_file($viewCandidates[0]) || is_file($viewCandidates[1]) ? 'pages/' . $slug : 'pages/basic';

        $this->render($viewName, [
            'slug' => $slug,
            'page' => $page,
        ]);
    }

    private function pageMap(): array
    {
        return [
            'destacados' => [
                'title' => 'NekoraList - Destacados',
                'eyebrow' => 'Curaduria',
                'heading' => 'Destacados de la temporada',
                'description' => 'Una vitrina de estrenos y favoritos recientes para que la replica ya tenga navegacion funcional y contenido visible.',
                'cards' => [
                    ['title' => 'Frieren: Beyond Journey\'s End', 'type' => 'Anime', 'year' => '2023'],
                    ['title' => 'Solo Leveling', 'type' => 'Anime', 'year' => '2024'],
                    ['title' => 'The Apothecary Diaries', 'type' => 'Anime', 'year' => '2023'],
                    ['title' => 'Haikyuu!! Movie', 'type' => 'Pelicula', 'year' => '2024'],
                ],
            ],
            'ranking' => [
                'title' => 'NekoraList - Ranking',
                'eyebrow' => 'Top',
                'heading' => 'Ranking del momento',
                'description' => 'Base inicial del ranking para que la interfaz no quede rota mientras seguimos migrando las pantallas completas.',
                'cards' => [
                    ['title' => 'Fullmetal Alchemist: Brotherhood', 'type' => 'Anime', 'year' => '2009'],
                    ['title' => 'Steins;Gate', 'type' => 'Anime', 'year' => '2011'],
                    ['title' => 'Attack on Titan Final Season', 'type' => 'Anime', 'year' => '2023'],
                    ['title' => 'Your Name', 'type' => 'Pelicula', 'year' => '2016'],
                ],
            ],
            'series' => [
                'title' => 'NekoraList - Series',
                'eyebrow' => 'Catalogo',
                'heading' => 'Series de anime',
                'description' => 'Catalogo principal de series.',
                'cards' => [],
            ],
            'peliculas' => [
                'title' => 'NekoraList - Peliculas',
                'eyebrow' => 'Catalogo',
                'heading' => 'Peliculas de anime',
                'description' => 'Catalogo principal de peliculas.',
                'cards' => [],
            ],
            'registro' => [
                'title' => 'NekoraList - Registro',
                'eyebrow' => 'Cuenta',
                'heading' => 'Crea tu cuenta',
                'description' => 'La replica ya tiene backend propio para autenticacion. Esta pagina deja el acceso visible mientras seguimos migrando el flujo exacto del original.',
                'cards' => [],
            ],
            'ingresar' => [
                'title' => 'NekoraList - Ingresar',
                'eyebrow' => 'Cuenta',
                'heading' => 'Inicia sesion',
                'description' => 'Usa el menu superior para autenticarte con la replica y guardar tu actividad en la base de datos propia.',
                'cards' => [],
            ],
            'admin' => [
                'title' => 'NekoraList - Admin',
                'eyebrow' => 'Panel',
                'heading' => 'Modo administrador',
                'description' => 'Seccion base del panel administrativo. La replica ya reconoce roles y sesiones desde su propia base de datos.',
                'cards' => [],
            ],
            'user' => [
                'title' => 'NekoraList - Usuario',
                'eyebrow' => 'Perfil',
                'heading' => 'Tu espacio Nekora',
                'description' => 'Aqui iremos completando la replica del perfil, favoritos y listas usando la misma base de datos local.',
                'cards' => [],
            ],
            'pago' => [
                'title' => 'NekoraList - Pago Seguro',
                'eyebrow' => 'Premium',
                'heading' => 'Pago seguro',
                'description' => 'Activa premium y desbloquea comentarios y listas avanzadas.',
                'cards' => [],
            ],

            'detail' => [
                'title' => 'NekoraList - Detalle',
                'eyebrow' => 'Ficha',
                'heading' => 'Detalle del anime',
                'description' => 'Vista de detalle conectada solo a la replica.',
                'cards' => [],
            ],
        ];
    }
}





