<?php

declare(strict_types=1);

namespace App\Controllers;

final class Home extends BaseController
{
    private function pageMap(string $slug): array
    {
        $map = [
            'destacados' => [
                'title' => 'NekoraList - Destacados',
                'eyebrow' => 'Curaduria',
                'heading' => 'Destacados de la temporada',
                'description' => 'Estrenos y favoritos recientes.',
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
                'description' => 'Base inicial del ranking.',
                'cards' => [
                    ['title' => 'Fullmetal Alchemist: Brotherhood', 'type' => 'Anime', 'year' => '2009'],
                    ['title' => 'Steins;Gate', 'type' => 'Anime', 'year' => '2011'],
                    ['title' => 'Attack on Titan Final Season', 'type' => 'Anime', 'year' => '2023'],
                    ['title' => 'Your Name', 'type' => 'Pelicula', 'year' => '2016'],
                ],
            ],
        ];

        return $map[$slug] ?? [];
    }

    public function index(): void
    {
        $this->render('pages/index');
    }

    public function destacados()
    {
        app_publish_csrf_cookie();
        $slug = 'destacados';
        return $this->render('pages/' . $slug, [
            'slug' => $slug,
            'page' => $this->pageMap($slug),
        ]);
    }

    public function ranking()
    {
        app_publish_csrf_cookie();
        $slug = 'ranking';
        return $this->render('pages/' . $slug, [
            'slug' => $slug,
            'page' => $this->pageMap($slug),
        ]);
    }
}
