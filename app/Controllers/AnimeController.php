<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Anime;

class AnimeController extends BaseController
{
    private function pageMap(string $slug): array
    {
        $map = [
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
            'detail' => [
                'title' => 'NekoraList - Detalle',
                'eyebrow' => 'Ficha',
                'heading' => 'Detalle del anime',
                'description' => 'Vista de detalle conectada solo a la replica.',
                'cards' => [],
            ],
        ];

        return $map[$slug] ?? [];
    }

    public function series()
    {
        app_publish_csrf_cookie();
        $animeModel = new Anime();
        $slug = 'series';
        $pageInfo = $this->pageMap($slug);

        return $this->render('pages/' . $slug, [
            'slug' => $slug,
            'page' => $pageInfo,
            'dbGenres' => $animeModel->getFilteredGenres(),
            'animes' => $animeModel->getSeries(),
        ]);
    }

    public function peliculas()
    {
        app_publish_csrf_cookie();
        $animeModel = new Anime();
        $slug = 'peliculas';
        $pageInfo = $this->pageMap($slug);

        return $this->render('pages/' . $slug, [
            'slug' => $slug,
            'page' => $pageInfo,
            'dbGenres' => $animeModel->getFilteredGenres(),
            'animes' => $animeModel->getMovies(),
        ]);
    }

    public function detail(string $segment = "")
    {
        app_publish_csrf_cookie();
        $slug = 'detail';
        $pageInfo = $this->pageMap($slug);
        
        $session = session();
        $detailRef = trim($segment !== "" ? $segment : (string) ($this->request->getGet('_detail_ref') ?? ''));
        $legacyId = trim((string) ($this->request->getGet('mal_id') ?? $this->request->getGet('id') ?? ''));
        $legacyTitle = trim((string) ($this->request->getGet('q') ?? ''));
        
        if ($detailRef === '') {
            $detailRef = app_detail_ref_from_input($legacyId, $legacyTitle);
        }
        $detailQuery = $detailRef !== '' && !ctype_digit($detailRef) ? str_replace('-', ' ', $detailRef) : '';
        
        $isLoggedIn = $session->has('user_id');
        $sessionRole = (string) ($session->get('role') ?? 'Invitado');
        $sessionPremium = !empty($session->get('premium')) || $sessionRole === 'Admin';

        return $this->render('pages/detail', [
            'slug' => $slug,
            'page' => $pageInfo,
            'detailRef' => $detailRef,
            'detailQuery' => $detailQuery,
            'isLoggedIn' => $isLoggedIn,
            'sessionRole' => $sessionRole,
            'sessionPremium' => $sessionPremium,
        ]);
    }
}
