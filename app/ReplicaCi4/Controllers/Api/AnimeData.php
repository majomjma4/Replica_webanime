<?php

namespace ReplicaCi4\Controllers\Api;

use ReplicaCi4\Controllers\BaseController;

final class AnimeData extends BaseController
{
    private array $restrictedGenres = ['Hentai', 'Erotica', 'Ecchi', 'Yaoi', 'Yuri', 'Gore', 'Harem', 'Reverse Harem', 'Rx', 'Girls Love', 'Boys Love'];
    private array $restrictedTitles = ['does it count if', 'futanari'];

    public function handle()
    {
        $q = trim((string) $this->request->getGet('q'));
        $malId = trim((string) $this->request->getGet('mal_id'));
        $id = trim((string) $this->request->getGet('id'));

        $db = \Config\Database::connect();
        
        $animeItem = null;
        if ($id !== '') {
            $animeItem = $db->table('anime')->where('id', (int) $id)->get()->getRowArray();
        }

        if (!$animeItem && $malId !== '') {
            $animeItem = $db->table('anime')->where('mal_id', (int) $malId)->get()->getRowArray();

            if (!$animeItem && ctype_digit($malId)) {
                $animeItem = $db->table('anime')->where('id', (int) $malId)->get()->getRowArray();
            }
        }

        if (!$animeItem && $q !== '') {
            $animeItem = $db->table('anime')->where('titulo', $q)->get()->getRowArray();

            if (!$animeItem) {
                $animeItem = $db->table('anime')->like('titulo', $q, 'both')->get()->getRowArray();
            }
        }

        if (!$animeItem) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'error' => 'Not found in local DB']);
        }

        $title = strtolower((string) ($animeItem['titulo'] ?? ''));
        foreach ($this->restrictedTitles as $restrictedTitle) {
            if (str_contains($title, $restrictedTitle)) {
                return $this->response->setStatusCode(403)->setJSON(['success' => false, 'error' => 'Restricted content']);
            }
        }

        $genres = $db->table('generos g')
                     ->select('g.nombre')
                     ->join('anime_generos ag', 'g.id = ag.genero_id')
                     ->where('ag.anime_id', (int) $animeItem['id'])
                     ->get()->getResultColumn('nombre') ?: [];
                     
        foreach ($genres as $genreName) {
            if (in_array($genreName, $this->restrictedGenres, true)) {
                return $this->response->setStatusCode(403)->setJSON(['success' => false, 'error' => 'Restricted content']);
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

        if ($db->tableExists('anime_characters')) {
            $chars = $db->table('anime_characters')->where('anime_id', (int) $animeItem['id'])->get()->getResultArray();
            foreach ($chars as $character) {
                $payload['characters'][] = [
                    'character' => [
                        'mal_id' => (int) ($character['mal_id'] ?? 0),
                        'name' => (string) ($character['name'] ?? 'Unknown'),
                        'images' => ['jpg' => ['image_url' => (string) ($character['image_url'] ?? '')]],
                    ],
                    'role' => (string) ($character['role'] ?? 'Supporting'),
                ];
            }
        }

        if ($db->tableExists('anime_pictures')) {
            $pics = $db->table('anime_pictures')->where('anime_id', (int) $animeItem['id'])->get()->getResultArray();
            foreach ($pics as $picture) {
                $imageUrl = (string) ($picture['image_url'] ?? '');
                $payload['pictures'][] = ['jpg' => ['image_url' => $imageUrl, 'large_image_url' => $imageUrl]];
            }
        }

        if ($db->tableExists('anime_videos')) {
            $vids = $db->table('anime_videos')->where('anime_id', (int) $animeItem['id'])->get()->getResultArray();
            foreach ($vids as $video) {
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
        }

        if ($db->tableExists('anime_episodes')) {
            $payload['episodes_data'] = $db->table('anime_episodes')
                                           ->select('episode_number, title, title_japanese, title_romanji, aired, score, filler, recap, synopsis')
                                           ->where('anime_id', (int) $animeItem['id'])
                                           ->orderBy('episode_number', 'ASC')
                                           ->get()->getResultArray();
        }

        return $this->response->setJSON(['success' => true, 'data' => $payload]);
    }
}
