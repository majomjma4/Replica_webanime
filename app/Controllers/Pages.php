<?php

declare(strict_types=1);

namespace ReplicaCi4\Controllers;

use PDO;
use ReplicaCi4\Models\Database;

final class Pages extends BaseController
{
    public function show(string $slug): void
    {
        $pages = $this->pageMap();
        $page = $pages[$slug] ?? null;

        if (!is_array($page)) {
            http_response_code(404);
            echo 'Replica CI4: pagina no encontrada.';
            return;
        }

        $view = $slug === 'detail' ? 'pages/detail' : 'pages/basic';
        $detailSeed = $slug === 'detail' ? $this->resolveDetailSeed() : null;
        $detailRecommendations = $slug === 'detail' ? $this->resolveLocalRecommendations($detailSeed) : [];

        $this->render($view, [
            'slug' => $slug,
            'page' => $page,
            'detailSeed' => $detailSeed,
            'detailRecommendations' => $detailRecommendations,
        ]);
    }


    private function resolveDetailSeed(): ?array
    {
        $malId = trim((string) ($_GET['mal_id'] ?? $_GET['id'] ?? ''));
        $query = trim((string) ($_GET['q'] ?? ''));

        if ($malId === '' && $query === '') {
            return null;
        }

        $db = (new Database())->getConnection(false);
        if (!$db instanceof PDO) {
            return null;
        }

        if ($malId !== '') {
            $stmt = $db->prepare(
                "SELECT a.*, GROUP_CONCAT(DISTINCT g.nombre ORDER BY g.nombre SEPARATOR '||') AS genres
                 FROM anime a
                 LEFT JOIN anime_generos ag ON ag.anime_id = a.id
                 LEFT JOIN generos g ON g.id = ag.genero_id
                 WHERE a.activo = 1 AND a.mal_id = ?
                 GROUP BY a.id
                 LIMIT 1"
            );
            $stmt->execute([(int) $malId]);
            $row = $stmt->fetch();
            if (is_array($row)) {
                return $this->mapAnimeRowToDetailSeed($row);
            }
        }

        if ($query === '') {
            return null;
        }

        $stmt = $db->prepare(
            "SELECT a.*, GROUP_CONCAT(DISTINCT g.nombre ORDER BY g.nombre SEPARATOR '||') AS genres
             FROM anime a
             LEFT JOIN anime_generos ag ON ag.anime_id = a.id
             LEFT JOIN generos g ON g.id = ag.genero_id
             WHERE a.activo = 1
               AND (
                    a.titulo = ?
                    OR a.titulo_ingles = ?
                    OR a.titulo LIKE ?
                    OR a.titulo_ingles LIKE ?
               )
             GROUP BY a.id
             ORDER BY
                CASE
                    WHEN a.titulo = ? THEN 0
                    WHEN a.titulo_ingles = ? THEN 1
                    WHEN a.titulo LIKE ? THEN 2
                    WHEN a.titulo_ingles LIKE ? THEN 3
                    ELSE 4
                END,
                a.puntuacion DESC,
                a.id DESC
             LIMIT 1"
        );

        $like = '%' . $query . '%';
        $startsWith = $query . '%';
        $stmt->execute([$query, $query, $like, $like, $query, $query, $startsWith, $startsWith]);
        $row = $stmt->fetch();

        return is_array($row) ? $this->mapAnimeRowToDetailSeed($row) : null;
    }

    private function mapAnimeRowToDetailSeed(array $row): array
    {
        $genreNames = array_values(array_filter(array_map(
            'trim',
            explode('||', (string) ($row['genres'] ?? ''))
        )));

        $score = $row['puntuacion'] !== null ? (float) $row['puntuacion'] : null;
        $imageUrl = trim((string) ($row['imagen_url'] ?? ''));
        $studio = trim((string) ($row['estudio'] ?? ''));

        return [
            'source' => 'local',
            'local_id' => isset($row['id']) ? (int) $row['id'] : null,
            'display_id' => isset($row['id']) ? 'local-' . (int) $row['id'] : '',
            'mal_id' => isset($row['mal_id']) && $row['mal_id'] !== null ? (int) $row['mal_id'] : null,
            'title' => (string) ($row['titulo'] ?? ''),
            'title_english' => (string) ($row['titulo_ingles'] ?? ''),
            'type' => (string) ($row['tipo'] ?? 'TV'),
            'status' => (string) ($row['estado'] ?? ''),
            'episodes' => isset($row['episodios']) && $row['episodios'] !== null ? (int) $row['episodios'] : null,
            'season' => (string) ($row['temporada'] ?? ''),
            'year' => isset($row['anio']) && $row['anio'] !== null ? (int) $row['anio'] : null,
            'rating' => (string) ($row['clasificacion'] ?? ''),
            'duration' => null,
            'synopsis' => (string) ($row['sinopsis'] ?? ''),
            'score' => $score,
            'studios' => $studio !== '' ? [['name' => $studio]] : [],
            'genres' => array_map(static fn (string $name): array => ['name' => $name], $genreNames),
            'images' => [
                'jpg' => [
                    'image_url' => $imageUrl,
                    'large_image_url' => $imageUrl,
                ],
                'webp' => [],
            ],
            'trailer' => [
                'url' => (string) ($row['trailer_url'] ?? ''),
            ],
        ];
    }

    private function resolveLocalRecommendations(?array $detailSeed, int $limit = 4): array
    {
        if (!is_array($detailSeed) || $limit < 1) {
            return [];
        }

        $db = (new Database())->getConnection(false);
        if (!$db instanceof PDO) {
            return [];
        }

        $excludeId = isset($detailSeed['local_id']) ? (int) $detailSeed['local_id'] : 0;
        $type = trim((string) ($detailSeed['type'] ?? ''));
        $genreNames = array_values(array_filter(array_map(
            static fn ($genre): string => trim((string) (is_array($genre) ? ($genre['name'] ?? '') : $genre)),
            (array) ($detailSeed['genres'] ?? [])
        )));

        $recommendations = [];
        $seen = [];

        if ($genreNames !== []) {
            $genrePlaceholders = implode(',', array_fill(0, count($genreNames), '?'));
            $sql = "SELECT a.*, GROUP_CONCAT(DISTINCT g.nombre ORDER BY g.nombre SEPARATOR '||') AS genres,
                           COUNT(DISTINCT CASE WHEN g.nombre IN ($genrePlaceholders) THEN g.id END) AS match_count
                    FROM anime a
                    LEFT JOIN anime_generos ag ON ag.anime_id = a.id
                    LEFT JOIN generos g ON g.id = ag.genero_id
                    WHERE a.activo = 1 AND a.id <> ?";
            $params = $genreNames;
            $params[] = $excludeId;

            if ($type !== '') {
                $sql .= ' AND a.tipo = ?';
                $params[] = $type;
            }

            $sql .= " GROUP BY a.id
                      HAVING match_count > 0
                      ORDER BY match_count DESC, a.puntuacion DESC, a.anio DESC, a.id DESC
                      LIMIT $limit";

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            foreach ($stmt->fetchAll() as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $mapped = $this->mapAnimeRowToDetailSeed($row);
                $recommendations[] = $mapped;
                if (isset($mapped['local_id'])) {
                    $seen[(int) $mapped['local_id']] = true;
                }
            }
        }

        if (count($recommendations) >= $limit) {
            return array_slice($recommendations, 0, $limit);
        }

        $remaining = $limit - count($recommendations);
        $sql = "SELECT a.*, GROUP_CONCAT(DISTINCT g.nombre ORDER BY g.nombre SEPARATOR '||') AS genres
                FROM anime a
                LEFT JOIN anime_generos ag ON ag.anime_id = a.id
                LEFT JOIN generos g ON g.id = ag.genero_id
                WHERE a.activo = 1 AND a.id <> ?";
        $params = [$excludeId];

        if ($type !== '') {
            $sql .= ' AND a.tipo = ?';
            $params[] = $type;
        }

        $sql .= ' GROUP BY a.id ORDER BY a.puntuacion DESC, a.anio DESC, a.id DESC LIMIT ' . max($limit * 2, 8);

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        foreach ($stmt->fetchAll() as $row) {
            if (!is_array($row)) {
                continue;
            }

            $mapped = $this->mapAnimeRowToDetailSeed($row);
            $localId = isset($mapped['local_id']) ? (int) $mapped['local_id'] : 0;
            if ($localId > 0 && isset($seen[$localId])) {
                continue;
            }

            $recommendations[] = $mapped;
            if ($localId > 0) {
                $seen[$localId] = true;
            }

            if (count($recommendations) >= $limit) {
                break;
            }
        }

        return array_slice($recommendations, 0, $limit);
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
                'title' => 'NekoraList - Animes',
                'eyebrow' => 'Catalogo',
                'heading' => 'Series de anime',
                'description' => 'Pagina base para el catalogo de series. Los scripts compartidos ya pueden enriquecer estas tarjetas con imagenes y datos dinamicos.',
                'cards' => [
                    ['title' => 'One Piece', 'type' => 'Anime', 'year' => '1999'],
                    ['title' => 'Naruto: Shippuden', 'type' => 'Anime', 'year' => '2007'],
                    ['title' => 'Jujutsu Kaisen', 'type' => 'Anime', 'year' => '2020'],
                    ['title' => 'Dandadan', 'type' => 'Anime', 'year' => '2024'],
                    ['title' => 'Blue Lock', 'type' => 'Anime', 'year' => '2022'],
                    ['title' => 'Spy x Family', 'type' => 'Anime', 'year' => '2022'],
                ],
            ],
            'peliculas' => [
                'title' => 'NekoraList - Peliculas',
                'eyebrow' => 'Catalogo',
                'heading' => 'Peliculas de anime',
                'description' => 'Pagina base para peliculas, preparada para que el frontend reutilice la misma logica compartida de busqueda y enriquecimiento.',
                'cards' => [
                    ['title' => 'Your Name', 'type' => 'Pelicula', 'year' => '2016'],
                    ['title' => 'Spirited Away', 'type' => 'Pelicula', 'year' => '2001'],
                    ['title' => 'A Silent Voice', 'type' => 'Pelicula', 'year' => '2016'],
                    ['title' => 'Princess Mononoke', 'type' => 'Pelicula', 'year' => '1997'],
                    ['title' => 'Howl\'s Moving Castle', 'type' => 'Pelicula', 'year' => '2004'],
                    ['title' => 'The Boy and the Heron', 'type' => 'Pelicula', 'year' => '2023'],
                ],
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
            'detail' => [
                'title' => 'NekoraList - Detalle',
                'eyebrow' => 'Ficha',
                'heading' => 'Detalle del anime',
                'description' => 'Vista de detalle conectada solo a la replica. Consume el proxy propio y mantiene listas, favoritos y estado sin tocar el proyecto original.',
                'cards' => [],
            ],
        ];
    }
}




