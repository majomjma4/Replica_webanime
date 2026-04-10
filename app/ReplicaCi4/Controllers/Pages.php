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
        app_publish_csrf_cookie();
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
            $session = session();
            $detailRef = trim((string) ($this->request->getGet('_detail_ref') ?? ''));
            $legacyId = trim((string) ($this->request->getGet('mal_id') ?? $this->request->getGet('id') ?? ''));
            $legacyTitle = trim((string) ($this->request->getGet('q') ?? ''));
            if ($detailRef === '') {
                $detailRef = app_detail_ref_from_input($legacyId, $legacyTitle);
            }
            $detailQuery = $detailRef !== '' && !ctype_digit($detailRef) ? str_replace('-', ' ', $detailRef) : '';
            $isLoggedIn = $session->has('user_id');
            $sessionRole = (string) ($session->get('role') ?? 'Invitado');
            $sessionPremium = !empty($session->get('premium')) || $sessionRole === 'Admin';

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

        if ($slug === 'gestion') {
            $session = session();
            
            $animeModel = new Anime();
            $pageNum = max(1, (int) ($this->request->getGet('page') ?? 1));
            $perPage = 50;
            $searchQuery = trim((string) ($this->request->getGet('q') ?? ''));
            $statusFilter = trim((string) ($this->request->getGet('status') ?? 'ALL'));
            $typeFilter = trim((string) ($this->request->getGet('type') ?? 'ALL'));
            $yearFilter = trim((string) ($this->request->getGet('year') ?? ''));

            $catalog = $animeModel->getCatalog($pageNum, $perPage, $searchQuery, $statusFilter, $typeFilter, $yearFilter);

            $animes = $catalog['data'];
            $totalAnimes = $catalog['total'];
            $totalPages = $catalog['totalPages'];
            $pageData = $catalog['currentPage'];
            $airingCount = $catalog['airingCount'];

            $offset = ($pageData - 1) * $perPage;
            $rangeStart = $totalAnimes > 0 ? $offset + 1 : 0;
            $rangeEnd = $totalAnimes > 0 ? min($offset + count($animes), $totalAnimes) : 0;
            $pageStart = max(1, $pageData - 2);
            $pageEnd = min($totalPages, $pageData + 2);

            $queryParams = [
                'q' => $searchQuery,
                'status' => $statusFilter,
                'type' => $typeFilter,
                'year' => $yearFilter,
            ];
            
            $buildPageUrl = static function (int $targetPage) use ($queryParams): string {
                $params = array_filter(array_merge($queryParams, ['page' => $targetPage]), static fn ($value) => $value !== '' && $value !== 'ALL' && $value !== null);
                return '?' . http_build_query($params);
            };

            $this->render('pages/gestion', [
                'slug' => $slug,
                'pageInfo' => $page,
                'page' => $pageData,
                'pageData' => $pageData,
                'perPage' => $perPage,
                'searchQuery' => $searchQuery,
                'statusFilter' => $statusFilter,
                'typeFilter' => $typeFilter,
                'yearFilter' => $yearFilter,
                'totalAnimes' => $totalAnimes,
                'totalPages' => $totalPages,
                'animes' => $animes,
                'airingCount' => $airingCount,
                'rangeStart' => $rangeStart,
                'rangeEnd' => $rangeEnd,
                'pageStart' => $pageStart,
                'pageEnd' => $pageEnd,
                'buildPageUrl' => $buildPageUrl,
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
            'anadir' => [
                'title' => 'NekoraList - Añadir Anime',
                'eyebrow' => 'Administracion',
                'heading' => 'Añadir Anime',
                'description' => 'Añade animes manualmente o usando Jikan proxy.',
                'cards' => [],
            ],
            'gestion' => [
                'title' => 'NekoraList - Gestionar Catalogo',
                'eyebrow' => 'Administracion',
                'heading' => 'Gestion de Catalogo',
                'description' => 'Vista de todos los animes importados en CI4.',
                'cards' => [],
            ],
            'gesus' => [
                'title' => 'NekoraList - Gestionar Usuarios',
                'eyebrow' => 'Administracion',
                'heading' => 'Gestion de Usuarios',
                'description' => 'Administracion de cuentas CI4.',
                'cards' => [],
            ],
            'gescom' => [
                'title' => 'NekoraList - Gestionar Comentarios',
                'eyebrow' => 'Administracion',
                'heading' => 'Gestion de Comentarios',
                'description' => 'Moderacion de reportes y comentarios.',
                'cards' => [],
            ],
        ];
    }
}





