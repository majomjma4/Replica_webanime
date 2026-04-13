<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Anime;

class AdminController extends BaseController
{
    private function pageMap(string $slug): array
    {
        $map = [
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
            'admin' => [
                'title' => 'NekoraList - Admin',
                'eyebrow' => 'Panel',
                'heading' => 'Modo administrador',
                'description' => 'Seccion base del panel administrativo.',
                'cards' => [],
            ],
        ];

        return $map[$slug] ?? [
            'title' => 'Admin Panel',
            'eyebrow' => 'Admin',
            'heading' => 'Admin Panel',
            'description' => '',
            'cards' => []
        ];
    }

    public function dashboard()
    {
        app_publish_csrf_cookie();
        $slug = 'admin';
        return $this->render('pages/' . $slug, [
            'slug' => $slug,
            'page' => $this->pageMap($slug),
        ]);
    }

    public function anadir()
    {
        app_publish_csrf_cookie();
        $slug = 'anadir';
        return $this->render('pages/' . $slug, [
            'slug' => $slug,
            'page' => $this->pageMap($slug),
        ]);
    }

    public function gestion()
    {
        app_publish_csrf_cookie();
        $slug = 'gestion';
        
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

        return $this->render('pages/gestion', [
            'slug' => $slug,
            'pageInfo' => $this->pageMap($slug),
            'page' => $pageData, // Compatible con la vista antigua si lo usaba
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
    }

    public function gesus()
    {
        app_publish_csrf_cookie();
        $slug = 'gesus';
        return $this->render('pages/' . $slug, [
            'slug' => $slug,
            'page' => $this->pageMap($slug),
        ]);
    }

    public function gescom()
    {
        app_publish_csrf_cookie();
        $slug = 'gescom';
        return $this->render('pages/' . $slug, [
            'slug' => $slug,
            'page' => $this->pageMap($slug),
        ]);
    }
}
