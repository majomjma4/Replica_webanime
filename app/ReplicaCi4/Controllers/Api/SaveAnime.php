<?php

namespace ReplicaCi4\Controllers\Api;

use ReplicaCi4\Controllers\BaseController;

final class SaveAnime extends BaseController
{
    public function handle()
    {
        if ($this->request->getMethod(true) !== 'POST') {
            return $this->response->setStatusCode(405)->setJSON(['success' => false, 'error' => 'Metodo no permitido']);
        }
        
        app_verify_csrf();
        
        $data = $this->request->getJSON(true);
        if (!$data || !isset($data['mal_id'])) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'error' => 'Invalid data']);
        }

        $db = \Config\Database::connect();

        $malId = (int) $data['mal_id'];
        $title = trim((string) ($data['title_english'] ?? $data['title'] ?? 'Unknown'));
        $rating = (string) ($data['rating'] ?? '');
        
        if (stripos($rating, 'Rx') !== false || stripos($rating, 'Hentai') !== false || stripos($rating, 'Erotica') !== false) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'error' => 'Contenido restringido (+18) no permitido.']);
        }

        $existingId = 0;
        $row = $db->table('anime')->where('mal_id', $malId)->get()->getRowArray();
        if ($row) {
            $existingId = (int) $row['id'];
        } else {
            $rowByTitle = $db->table('anime')->where('titulo', $title)->get()->getRowArray();
            if ($rowByTitle) {
                $existingId = (int) $rowByTitle['id'];
                $db->table('anime')->where('id', $existingId)->update(['mal_id' => $malId]);
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
            $db->transStart();

            $animeData = [
                'mal_id' => $malId,
                'titulo' => $title,
                'titulo_ingles' => $titleEnglish,
                'tipo' => $type,
                'estudio' => $studio,
                'estado' => $status,
                'episodios' => $episodes,
                'temporada' => $season,
                'anio' => $year ?: null,
                'clasificacion' => $classification,
                'sinopsis' => $synopsis,
                'imagen_url' => $imageUrl,
                'trailer_url' => $trailerUrl,
                'puntuacion' => $score
            ];

            if ($existingId <= 0) {
                $animeData['activo'] = 1;
                $animeData['creado_en'] = date('Y-m-d H:i:s');
                $db->table('anime')->insert($animeData);
                $animeId = $db->insertID();
            } else {
                $animeId = $existingId;
                $db->table('anime')->where('id', $animeId)->update($animeData);
                $db->table('anime_generos')->where('anime_id', $animeId)->delete();
            }

            if (!empty($data['genres']) && is_array($data['genres'])) {
                foreach ($data['genres'] as $genre) {
                    $genreName = trim((string) ($genre['name'] ?? ''));
                    if ($genreName === '') {
                        continue;
                    }
                    $genreRow = $db->table('generos')->where('nombre', $genreName)->get()->getRowArray();
                    if ($genreRow) {
                        $genreId = (int) $genreRow['id'];
                    } else {
                        $db->table('generos')->insert(['nombre' => $genreName]);
                        $genreId = $db->insertID();
                    }
                    $db->table('anime_generos')->insert([
                        'anime_id' => $animeId,
                        'genero_id' => $genreId
                    ]);
                }
            }

            if ($db->tableExists('anime_characters') && isset($data['characters']) && is_array($data['characters'])) {
                $db->table('anime_characters')->where('anime_id', $animeId)->delete();
                foreach (array_slice($data['characters'], 0, 10) as $character) {
                    if (empty($character['character']['mal_id'])) {
                        continue;
                    }
                    $db->table('anime_characters')->insert([
                        'anime_id' => $animeId,
                        'mal_id' => (int) ($character['character']['mal_id'] ?? 0),
                        'name' => trim((string) ($character['character']['name'] ?? 'Unknown')),
                        'role' => trim((string) ($character['role'] ?? 'Supporting')),
                        'image_url' => (string) ($character['character']['images']['jpg']['image_url'] ?? ''),
                    ]);
                }
            }

            if ($db->tableExists('anime_pictures') && isset($data['pictures']) && is_array($data['pictures'])) {
                $db->table('anime_pictures')->where('anime_id', $animeId)->delete();
                foreach ($data['pictures'] as $picture) {
                    $pictureUrl = (string) ($picture['jpg']['large_image_url'] ?? $picture['jpg']['image_url'] ?? '');
                    if ($pictureUrl !== '') {
                        $db->table('anime_pictures')->insert([
                            'anime_id' => $animeId,
                            'image_url' => $pictureUrl
                        ]);
                    }
                }
            }

            if ($db->tableExists('anime_videos') && !empty($data['videos']['promo']) && is_array($data['videos']['promo'])) {
                $db->table('anime_videos')->where('anime_id', $animeId)->delete();
                foreach ($data['videos']['promo'] as $video) {
                    $youtubeId = (string) ($video['trailer']['youtube_id'] ?? '');
                    $url = (string) ($video['trailer']['url'] ?? '');
                    $videoImage = (string) ($video['trailer']['images']['maximum_image_url'] ?? $video['trailer']['images']['large_image_url'] ?? '');
                    if ($youtubeId !== '' || $url !== '') {
                        $db->table('anime_videos')->insert([
                            'anime_id' => $animeId,
                            'youtube_id' => $youtubeId,
                            'url' => $url,
                            'image_url' => $videoImage
                        ]);
                    }
                }
            }

            if ($db->tableExists('anime_episodes') && isset($data['episodes_data']) && is_array($data['episodes_data'])) {
                $db->table('anime_episodes')->where('anime_id', $animeId)->delete();
                foreach ($data['episodes_data'] as $episode) {
                    $episodeNumber = (int) ($episode['mal_id'] ?? $episode['episode_number'] ?? 0);
                    if ($episodeNumber <= 0) {
                        continue;
                    }
                    $db->table('anime_episodes')->insert([
                        'anime_id' => $animeId,
                        'episode_number' => $episodeNumber,
                        'title' => (string) ($episode['title'] ?? ''),
                        'title_japanese' => (string) ($episode['title_japanese'] ?? ''),
                        'title_romanji' => (string) ($episode['title_romanji'] ?? ''),
                        'aired' => (string) ($episode['aired'] ?? ''),
                        'score' => isset($episode['score']) ? (float) $episode['score'] : null,
                        'filler' => !empty($episode['filler']) ? 1 : 0,
                        'recap' => !empty($episode['recap']) ? 1 : 0,
                        'synopsis' => (string) ($episode['synopsis'] ?? '')
                    ]);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setStatusCode(500)->setJSON(['success' => false, 'error' => 'Error de transaccion en base de datos.']);
            }

            return $this->response->setJSON(['success' => true, 'message' => 'Inserted new anime with deep data']);
            
        } catch (\Exception $exception) {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'error' => $exception->getMessage()]);
        }
    }
}
