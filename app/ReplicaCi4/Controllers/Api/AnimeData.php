<?php

namespace ReplicaCi4\Controllers\Api;

use PDO;
use ReplicaCi4\Models\Database;

final class AnimeData
{
    private array $restrictedGenres = ['Hentai', 'Erotica', 'Ecchi', 'Yaoi', 'Yuri', 'Gore', 'Harem', 'Reverse Harem', 'Rx', 'Girls Love', 'Boys Love'];
    private array $restrictedTitles = ['does it count if', 'futanari'];

    private function tableExists(PDO $db, string $table): bool
    {
        try {
            $stmt = $db->prepare('SHOW TABLES LIKE ?');
            $stmt->execute([$table]);
            return (bool) $stmt->fetchColumn();
        } catch (\Throwable) {
            return false;
        }
    }

    public function handle(): void
    {
        header('Content-Type: application/json; charset=UTF-8');

        $q = trim((string) ($_GET['q'] ?? ''));
        $malId = trim((string) ($_GET['mal_id'] ?? ''));
        $id = trim((string) ($_GET['id'] ?? ''));

        $db = (new Database())->getConnection(false);
        if (!$db instanceof PDO) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'DB Connection Error']);
            return;
        }

        $animeItem = null;
        if ($id !== '') {
            $stmt = $db->prepare('SELECT * FROM anime WHERE id = ? LIMIT 1');
            $stmt->execute([(int) $id]);
            $animeItem = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }

        if (!$animeItem && $malId !== '') {
            $stmt = $db->prepare('SELECT * FROM anime WHERE mal_id = ? LIMIT 1');
            $stmt->execute([(int) $malId]);
            $animeItem = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

            if (!$animeItem && ctype_digit($malId)) {
                $stmt = $db->prepare('SELECT * FROM anime WHERE id = ? LIMIT 1');
                $stmt->execute([(int) $malId]);
                $animeItem = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
            }
        }

        if (!$animeItem && $q !== '') {
            $stmt = $db->prepare('SELECT * FROM anime WHERE titulo = ? LIMIT 1');
            $stmt->execute([$q]);
            $animeItem = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

            if (!$animeItem) {
                $stmt = $db->prepare('SELECT * FROM anime WHERE titulo LIKE ? LIMIT 1');
                $stmt->execute(['%' . $q . '%']);
                $animeItem = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
            }
        }

        if (!$animeItem) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Not found in local DB']);
            return;
        }

        $title = strtolower((string) ($animeItem['titulo'] ?? ''));
        foreach ($this->restrictedTitles as $restrictedTitle) {
            if (str_contains($title, $restrictedTitle)) {
                http_response_code(403);
                echo json_encode(['success' => false, 'error' => 'Restricted content']);
                return;
            }
        }

        $genreStmt = $db->prepare('SELECT g.nombre FROM generos g JOIN anime_generos ag ON g.id = ag.genero_id WHERE ag.anime_id = ?');
        $genreStmt->execute([(int) $animeItem['id']]);
        $genres = $genreStmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
        foreach ($genres as $genreName) {
            if (in_array($genreName, $this->restrictedGenres, true)) {
                http_response_code(403);
                echo json_encode(['success' => false, 'error' => 'Restricted content']);
                return;
            }
        }

        $studioNames = array_values(array_filter(array_map('trim', explode(',', (string) ($animeItem['estudio'] ?? '')))));
        $seasonValue = strtolower(trim((string) ($animeItem['temporada'] ?? '')));

        $payload = [
            'mal_id' => !empty($animeItem['mal_id']) ? (int) $animeItem['mal_id'] : (int) $animeItem['id'],
            'title' => (string) $animeItem['titulo'],
            'title_english' => trim((string) ($animeItem['titulo_ingles'] ?? '')) ?: (string) $animeItem['titulo'],
            'title_japanese' => (string) $animeItem['titulo'],
            'type' => (string) ($animeItem['tipo'] ?? 'TV'),
            'episodes' => isset($animeItem['episodios']) ? (int) $animeItem['episodios'] : 0,
            'status' => (string) ($animeItem['estado'] ?? ''),
            'year' => isset($animeItem['anio']) ? (int) $animeItem['anio'] : 0,
            'season' => $seasonValue,
            'score' => isset($animeItem['puntuacion']) ? (float) $animeItem['puntuacion'] : 0,
            'synopsis' => (string) ($animeItem['sinopsis'] ?? ''),
            'rating' => (string) ($animeItem['clasificacion'] ?? ''),
            'duration' => '',
            'rank' => null,
            'images' => [
                'jpg' => [
                    'large_image_url' => (string) ($animeItem['imagen_url'] ?? ''),
                    'image_url' => (string) ($animeItem['imagen_url'] ?? ''),
                ],
            ],
            'genres' => array_map(static fn ($name) => ['name' => $name], $genres),
            'studios' => array_map(static fn ($name) => ['name' => $name], $studioNames),
            'characters' => [],
            'pictures' => [],
            'videos' => ['promo' => []],
            'episodes_data' => [],
        ];

        if ($this->tableExists($db, 'anime_characters')) {
            try {
                $charStmt = $db->prepare('SELECT * FROM anime_characters WHERE anime_id = ?');
                $charStmt->execute([(int) $animeItem['id']]);
                while ($character = $charStmt->fetch(PDO::FETCH_ASSOC)) {
                    $payload['characters'][] = [
                        'character' => [
                            'mal_id' => (int) ($character['mal_id'] ?? 0),
                            'name' => (string) ($character['name'] ?? 'Unknown'),
                            'images' => ['jpg' => ['image_url' => (string) ($character['image_url'] ?? '')]],
                        ],
                        'role' => (string) ($character['role'] ?? 'Supporting'),
                    ];
                }
            } catch (\Throwable) {
                $payload['characters'] = [];
            }
        }

        if ($this->tableExists($db, 'anime_pictures')) {
            try {
                $picStmt = $db->prepare('SELECT * FROM anime_pictures WHERE anime_id = ?');
                $picStmt->execute([(int) $animeItem['id']]);
                while ($picture = $picStmt->fetch(PDO::FETCH_ASSOC)) {
                    $imageUrl = (string) ($picture['image_url'] ?? '');
                    $payload['pictures'][] = ['jpg' => ['image_url' => $imageUrl, 'large_image_url' => $imageUrl]];
                }
            } catch (\Throwable) {
                $payload['pictures'] = [];
            }
        }

        if ($this->tableExists($db, 'anime_videos')) {
            try {
                $videoStmt = $db->prepare('SELECT * FROM anime_videos WHERE anime_id = ?');
                $videoStmt->execute([(int) $animeItem['id']]);
                while ($video = $videoStmt->fetch(PDO::FETCH_ASSOC)) {
                    $payload['videos']['promo'][] = [
                        'trailer' => [
                            'youtube_id' => (string) ($video['youtube_id'] ?? ''),
                            'url' => (string) ($video['url'] ?? ''),
                            'images' => [
                                'maximum_image_url' => (string) ($video['image_url'] ?? ''),
                                'large_image_url' => (string) ($video['image_url'] ?? ''),
                            ],
                        ],
                    ];
                }
            } catch (\Throwable) {
                $payload['videos'] = ['promo' => []];
            }
        }

        if ($this->tableExists($db, 'anime_episodes')) {
            try {
                $episodeStmt = $db->prepare('SELECT episode_number, title, title_japanese, title_romanji, aired, score, filler, recap, synopsis FROM anime_episodes WHERE anime_id = ? ORDER BY episode_number ASC');
                $episodeStmt->execute([(int) $animeItem['id']]);
                $payload['episodes_data'] = $episodeStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            } catch (\Throwable) {
                $payload['episodes_data'] = [];
            }
        }

        echo json_encode(['success' => true, 'data' => $payload], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
