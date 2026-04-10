<?php

namespace ReplicaCi4\Controllers\Api;

use Exception;
use PDO;
use ReplicaCi4\Models\Database;

final class SaveAnime
{
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
        app_require_method('POST');
        app_verify_csrf();
        app_start_session();

        $data = app_get_json_input();
        if (!$data || !isset($data['mal_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid data']);
            return;
        }

        $db = (new Database())->getConnection(false);
        if (!$db instanceof PDO) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'DB Connection Error']);
            return;
        }

        $malId = (int) $data['mal_id'];
        $title = trim((string) ($data['title_english'] ?? $data['title'] ?? 'Unknown'));
        $rating = (string) ($data['rating'] ?? '');
        if (stripos($rating, 'Rx') !== false || stripos($rating, 'Hentai') !== false || stripos($rating, 'Erotica') !== false) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Contenido restringido (+18) no permitido.']);
            return;
        }

        $stmt = $db->prepare('SELECT id FROM anime WHERE mal_id = ? LIMIT 1');
        $stmt->execute([$malId]);
        $existingId = (int) ($stmt->fetchColumn() ?: 0);
        if ($existingId <= 0) {
            $stmt = $db->prepare('SELECT id FROM anime WHERE titulo = ? LIMIT 1');
            $stmt->execute([$title]);
            $existingId = (int) ($stmt->fetchColumn() ?: 0);
            if ($existingId > 0) {
                $db->prepare('UPDATE anime SET mal_id = ? WHERE id = ?')->execute([$malId, $existingId]);
            }
        }

        $studioNames = [];
        if (!empty($data['studios']) && is_array($data['studios'])) {
            foreach ($data['studios'] as $studio) {
                $name = trim((string) ($studio['name'] ?? ''));
                if ($name !== '') {
                    $studioNames[] = $name;
                }
            }
        }

        $statusRaw = trim((string) ($data['status'] ?? 'Unknown'));
        $statusLower = strtolower($statusRaw);
        $status = match ($statusLower) {
            'finished airing' => 'Finalizado',
            'currently airing', 'en emision' => 'En emision',
            'not yet aired' => 'Proximamente',
            default => $statusRaw,
        };

        $episodes = isset($data['episodes']) ? (int) $data['episodes'] : 0;
        $season = (string) ($data['season'] ?? 'Unknown');
        $year = isset($data['year']) ? (int) $data['year'] : (int) ($data['aired']['prop']['from']['year'] ?? 0);
        $synopsis = (string) ($data['synopsis'] ?? '');
        $imageUrl = (string) ($data['images']['jpg']['large_image_url'] ?? $data['images']['jpg']['image_url'] ?? '');
        $score = isset($data['score']) ? (float) $data['score'] : 0.0;
        $titleEnglish = trim((string) ($data['title_english'] ?? ''));
        $classification = trim((string) ($data['rating'] ?? ''));
        $trailerUrl = (string) ($data['trailer']['url'] ?? '');
        $type = (string) ($data['type'] ?? 'TV');
        $studio = implode(', ', $studioNames);

        try {
            $db->beginTransaction();

            if ($existingId <= 0) {
                $stmt = $db->prepare('INSERT INTO anime (mal_id, titulo, titulo_ingles, tipo, estudio, estado, episodios, temporada, anio, clasificacion, sinopsis, imagen_url, trailer_url, puntuacion, activo, creado_en) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())');
                $stmt->execute([$malId, $title, $titleEnglish, $type, $studio, $status, $episodes, $season, $year ?: null, $classification, $synopsis, $imageUrl, $trailerUrl, $score]);
                $animeId = (int) $db->lastInsertId();
            } else {
                $animeId = $existingId;
                $stmt = $db->prepare('UPDATE anime SET mal_id = ?, titulo = ?, titulo_ingles = ?, tipo = ?, estudio = ?, estado = ?, episodios = ?, temporada = ?, anio = ?, clasificacion = ?, sinopsis = ?, imagen_url = ?, trailer_url = ?, puntuacion = ? WHERE id = ?');
                $stmt->execute([$malId, $title, $titleEnglish, $type, $studio, $status, $episodes, $season, $year ?: null, $classification, $synopsis, $imageUrl, $trailerUrl, $score, $animeId]);
                $db->prepare('DELETE FROM anime_generos WHERE anime_id = ?')->execute([$animeId]);
            }

            if (!empty($data['genres']) && is_array($data['genres'])) {
                foreach ($data['genres'] as $genre) {
                    $genreName = trim((string) ($genre['name'] ?? ''));
                    if ($genreName === '') {
                        continue;
                    }
                    $genreStmt = $db->prepare('SELECT id FROM generos WHERE nombre = ?');
                    $genreStmt->execute([$genreName]);
                    $genreId = (int) ($genreStmt->fetchColumn() ?: 0);
                    if ($genreId <= 0) {
                        $db->prepare('INSERT INTO generos (nombre) VALUES (?)')->execute([$genreName]);
                        $genreId = (int) $db->lastInsertId();
                    }
                    $db->prepare('INSERT INTO anime_generos (anime_id, genero_id) VALUES (?, ?)')->execute([$animeId, $genreId]);
                }
            }

            $db->commit();

            if ($this->tableExists($db, 'anime_characters') && isset($data['characters']) && is_array($data['characters'])) {
                $db->prepare('DELETE FROM anime_characters WHERE anime_id = ?')->execute([$animeId]);
                $charStmt = $db->prepare('INSERT INTO anime_characters (anime_id, mal_id, name, role, image_url) VALUES (?, ?, ?, ?, ?)');
                foreach (array_slice($data['characters'], 0, 10) as $character) {
                    if (empty($character['character']['mal_id'])) {
                        continue;
                    }
                    $charStmt->execute([
                        $animeId,
                        (int) ($character['character']['mal_id'] ?? 0),
                        trim((string) ($character['character']['name'] ?? 'Unknown')),
                        trim((string) ($character['role'] ?? 'Supporting')),
                        (string) ($character['character']['images']['jpg']['image_url'] ?? ''),
                    ]);
                }
            }

            if ($this->tableExists($db, 'anime_pictures') && isset($data['pictures']) && is_array($data['pictures'])) {
                $db->prepare('DELETE FROM anime_pictures WHERE anime_id = ?')->execute([$animeId]);
                $picStmt = $db->prepare('INSERT INTO anime_pictures (anime_id, image_url) VALUES (?, ?)');
                foreach ($data['pictures'] as $picture) {
                    $pictureUrl = (string) ($picture['jpg']['large_image_url'] ?? $picture['jpg']['image_url'] ?? '');
                    if ($pictureUrl !== '') {
                        $picStmt->execute([$animeId, $pictureUrl]);
                    }
                }
            }

            if ($this->tableExists($db, 'anime_videos') && !empty($data['videos']['promo']) && is_array($data['videos']['promo'])) {
                $db->prepare('DELETE FROM anime_videos WHERE anime_id = ?')->execute([$animeId]);
                $videoStmt = $db->prepare('INSERT INTO anime_videos (anime_id, youtube_id, url, image_url) VALUES (?, ?, ?, ?)');
                foreach ($data['videos']['promo'] as $video) {
                    $youtubeId = (string) ($video['trailer']['youtube_id'] ?? '');
                    $url = (string) ($video['trailer']['url'] ?? '');
                    $videoImage = (string) ($video['trailer']['images']['maximum_image_url'] ?? $video['trailer']['images']['large_image_url'] ?? '');
                    if ($youtubeId !== '' || $url !== '') {
                        $videoStmt->execute([$animeId, $youtubeId, $url, $videoImage]);
                    }
                }
            }

            if ($this->tableExists($db, 'anime_episodes') && isset($data['episodes_data']) && is_array($data['episodes_data'])) {
                $db->prepare('DELETE FROM anime_episodes WHERE anime_id = ?')->execute([$animeId]);
                $episodeStmt = $db->prepare('INSERT INTO anime_episodes (anime_id, episode_number, title, title_japanese, title_romanji, aired, score, filler, recap, synopsis) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
                foreach ($data['episodes_data'] as $episode) {
                    $episodeNumber = (int) ($episode['mal_id'] ?? $episode['episode_number'] ?? 0);
                    if ($episodeNumber <= 0) {
                        continue;
                    }
                    $episodeStmt->execute([
                        $animeId,
                        $episodeNumber,
                        (string) ($episode['title'] ?? ''),
                        (string) ($episode['title_japanese'] ?? ''),
                        (string) ($episode['title_romanji'] ?? ''),
                        (string) ($episode['aired'] ?? ''),
                        isset($episode['score']) ? (float) $episode['score'] : null,
                        !empty($episode['filler']) ? 1 : 0,
                        !empty($episode['recap']) ? 1 : 0,
                        (string) ($episode['synopsis'] ?? ''),
                    ]);
                }
            }

            echo json_encode(['success' => true, 'message' => 'Inserted new anime with deep data']);
        } catch (Exception $exception) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $exception->getMessage()]);
        }
    }
}
