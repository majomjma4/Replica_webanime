<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

final class Anime extends Model
{
    protected $table = 'anime';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'mal_id', 'titulo', 'titulo_ingles', 'tipo', 'estado', 
        'episodios', 'anio', 'clasificacion', 'sinopsis', 
        'imagen_url', 'trailer_url', 'puntuacion', 'activo'
    ];

    public function getById(int $id): ?array
    {
        $anime = $this->where('mal_id', $id)
                      ->orWhere('id', $id)
                      ->first();

        if ($anime) {
            $anime['generos'] = $this->getGeneros((int) $anime['id']);
            return $anime;
        }

        return $id > 0 ? $this->importFromJikanByMalId($id) : null;
    }

    public function getByTitle(string $title): ?array
    {
        $cleanTitle = trim($title);
        if ($cleanTitle === '') {
            return null;
        }

        $anime = $this->where('titulo', $cleanTitle)
                      ->orWhere('titulo_ingles', $cleanTitle)
                      ->first();

        if (!$anime) {
            $anime = $this->like('titulo', $cleanTitle)
                          ->orLike('titulo_ingles', $cleanTitle)
                          ->first();
        }

        if ($anime) {
            $anime['generos'] = $this->getGeneros((int) $anime['id']);
            return $anime;
        }

        return $this->importFromJikanByTitle($cleanTitle);
    }

    public function getGeneros(int $animeId): array
    {
        $results = $this->db->table('generos g')
            ->select('g.nombre')
            ->join('anime_generos ag', 'g.id = ag.genero_id')
            ->where('ag.anime_id', $animeId)
            ->get()->getResultArray();
        return array_column($results, 'nombre');
    }

    public function getFilteredGenres(): array
    {
        $restricted = ['Hentai', 'Erotica', 'Ecchi', 'Yaoi', 'Yuri', 'Gore', 'Harem', 'Reverse Harem', 'Rx', 'Girls Love', 'Boys Love'];
        $results = $this->db->table('generos')
            ->select('nombre')
            ->whereNotIn('nombre', $restricted)
            ->orderBy('nombre', 'ASC')
            ->get()->getResultArray();
        return array_column($results, 'nombre');
    }

    public function getMovies(): array
    {
        return $this->getCatalogByType(true);
    }

    public function getSeries(): array
    {
        return $this->getCatalogByType(false);
    }

    private function getCatalogByType(bool $movies): array
    {
        $restrictedGenres = ['Hentai', 'Erotica', 'Ecchi', 'Yaoi', 'Yuri', 'Girls Love', 'Boys Love'];
        $restrictedTitles = ['%does it count if%', '%futanari%'];

        $builder = $this->builder();
        if ($movies) {
            $builder->where('tipo', 'Movie');
        } else {
            $builder->where('tipo !=', 'Movie');
        }

        $builder->where('activo', 1);

        $subquery = $this->db->table('anime_generos')
            ->select('anime_id')
            ->whereIn('genero_id', function ($db) use ($restrictedGenres) {
                return $db->select('id')->from('generos')->whereIn('nombre', $restrictedGenres);
            });

        $builder->whereNotIn('id', $subquery);

        foreach ($restrictedTitles as $rt) {
            $builder->notLike('LOWER(titulo)', strtolower(trim($rt, '%')));
        }

        if ($movies) {
            $builder->orderBy('puntuacion', 'DESC')->orderBy('id', 'DESC');
        } else {
            $customOrder = "CASE
                WHEN titulo LIKE 'Shingeki no Kyojin%' THEN 0
                WHEN titulo = 'Fullmetal Alchemist: Brotherhood' THEN 1
                WHEN titulo = 'Steins;Gate' THEN 2
                WHEN titulo LIKE 'Hunter x Hunter%' THEN 3
                WHEN titulo LIKE 'Kimetsu no Yaiba%' THEN 4
                WHEN titulo LIKE 'Jujutsu Kaisen%' THEN 5
                WHEN titulo LIKE 'Chainsaw Man%' THEN 6
                WHEN titulo LIKE 'Spy x Family%' THEN 7
                WHEN titulo LIKE 'Haikyuu!!%' THEN 8
                WHEN titulo LIKE 'Boku no Hero Academia%' THEN 9
                WHEN titulo LIKE 'One Piece%' THEN 10
                WHEN titulo LIKE 'Naruto%' THEN 11
                WHEN titulo LIKE 'Bleach%' THEN 12
                WHEN titulo LIKE 'Sousou no Frieren%' THEN 13
                WHEN titulo = 'Gintama' THEN 80
                WHEN titulo LIKE 'Gintama%' THEN 90
                ELSE 40 END";
            $builder->orderBy($customOrder, 'ASC', false);
            $builder->orderBy('puntuacion', 'DESC');
            $builder->orderBy('id', 'DESC');
        }

        $animes = $builder->get()->getResultArray();

        foreach ($animes as &$anime) {
            $anime['generos_str'] = implode(',', array_map('strtolower', $this->getGeneros((int) $anime['id'])));
        }
        unset($anime);

        return $animes;
    }

    private function translateToSpanish(string $text): string
    {
        if (trim($text) === '') {
            return '';
        }

        $url = 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=es&dt=t&q=' . urlencode($text);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        $res = curl_exec($ch);
        curl_close($ch);

        $translated = '';
        if ($res) {
            $json = json_decode($res, true);
            if (isset($json[0]) && is_array($json[0])) {
                foreach ($json[0] as $segment) {
                    $translated .= $segment[0] ?? '';
                }
            }
        }

        return $translated !== '' ? $translated : $text;
    }

    private function importFromJikanByMalId(int $malId, int $retries = 2): ?array
    {
        $url = 'https://api.jikan.moe/v4/anime/' . $malId . '/full';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'WebAnimeAuto/1.0');
        $res = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status === 429 && $retries > 0) {
            sleep(2);
            return $this->importFromJikanByMalId($malId, $retries - 1);
        }

        if ($status !== 200 || !$res) {
            return null;
        }

        $data = json_decode($res, true);
        return isset($data['data']) && is_array($data['data']) ? $this->saveJikanDataToDB($data['data']) : null;
    }

    private function importFromJikanByTitle(string $title, int $retries = 2): ?array
    {
        $url = 'https://api.jikan.moe/v4/anime?q=' . urlencode($title) . '&limit=1';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'WebAnimeAuto/1.0');
        $res = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status === 429 && $retries > 0) {
            sleep(2);
            return $this->importFromJikanByTitle($title, $retries - 1);
        }

        if ($status !== 200 || !$res) {
            return null;
        }

        $data = json_decode($res, true);
        return !empty($data['data'][0]) && is_array($data['data'][0]) ? $this->saveJikanDataToDB($data['data'][0]) : null;
    }

    private function saveJikanDataToDB(array $anime): ?array
    {
        $malId = (int) ($anime['mal_id'] ?? 0);
        if ($malId <= 0) {
            return null;
        }

        $exists = $this->where('mal_id', $malId)->first();
        if ($exists) {
            return $this->getById($malId);
        }

        $titulo = substr((string) ($anime['title'] ?? 'Desconocido'), 0, 255);
        $tituloIngles = substr((string) ($anime['title_english'] ?? ''), 0, 255);
        $tipo = (string) ($anime['type'] ?? 'TV');
        $estado = substr((string) ($anime['status'] ?? ''), 0, 50);
        $episodes = isset($anime['episodes']) ? (int) $anime['episodes'] : null;
        $anio = isset($anime['year']) ? (int) $anime['year'] : (int) ($anime['aired']['prop']['from']['year'] ?? 0);
        $rating = substr((string) ($anime['rating'] ?? ''), 0, 50);
        $sinopsis = $this->translateToSpanish((string) ($anime['synopsis'] ?? ''));
        $img = (string) ($anime['images']['webp']['large_image_url'] ?? $anime['images']['jpg']['large_image_url'] ?? '');
        $trailerUrl = (string) ($anime['trailer']['url'] ?? '');
        $score = isset($anime['score']) ? (float) $anime['score'] : null;

        try {
            $internalId = $this->insert([
                'mal_id' => $malId,
                'titulo' => $titulo,
                'titulo_ingles' => $tituloIngles,
                'tipo' => $tipo,
                'estado' => $estado,
                'episodios' => $episodes,
                'anio' => $anio ?: null,
                'clasificacion' => $rating,
                'sinopsis' => $sinopsis,
                'imagen_url' => $img,
                'trailer_url' => $trailerUrl,
                'puntuacion' => $score,
                'activo' => 1
            ]);

            if (!empty($anime['genres']) && is_array($anime['genres'])) {
                foreach ($anime['genres'] as $genre) {
                    $genreName = substr((string) ($genre['name'] ?? ''), 0, 50);
                    if ($genreName === '') continue;
                    
                    $genreRow = $this->db->table('generos')->where('nombre', $genreName)->get()->getRowArray();
                    if (!$genreRow) {
                        $this->db->table('generos')->insert(['nombre' => $genreName]);
                        $genreId = $this->db->insertID();
                    } else {
                        $genreId = $genreRow['id'];
                    }
                    $this->db->table('anime_generos')->insert([
                        'anime_id' => $internalId,
                        'genero_id' => $genreId
                    ]);
                }
            }

            return $this->getById($malId);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getCatalog(int $page = 1, int $perPage = 50, string $search = '', string $status = 'ALL', string $type = 'ALL', string $year = ''): array
    {
        $restrictedGenres = ['Hentai', 'Erotica', 'Ecchi', 'Yaoi', 'Yuri', 'Girls Love', 'Boys Love'];
        $restrictedTitles = ['%does it count if%', '%futanari%'];
        
        $builder = $this->builder();

        $subquery = $this->db->table('anime_generos')
            ->select('anime_id')
            ->whereIn('genero_id', function ($db) use ($restrictedGenres) {
                return $db->from('generos')->select('id')->whereIn('nombre', $restrictedGenres);
            });

        $builder->whereNotIn('id', $subquery);

        foreach ($restrictedTitles as $rt) {
            $builder->notLike('LOWER(titulo)', strtolower(trim($rt, '%')));
        }

        if (!empty($search)) {
            $builder->groupStart()
                ->like('titulo', $search)
                ->orLike('tipo', $search)
                ->orLike('estado', $search)
                ->orLike('estudio', $search)
                ->orWhere("CAST(anio AS CHAR) LIKE '%" . $this->db->escapeLikeString($search) . "%'")
                ->groupEnd();
        }

        if ($status !== 'ALL' && !empty($status)) {
            $statusMap = [
                'EN EMISION' => ['en emision', 'en emisi?n', 'currently airing'],
                'FINALIZADO' => ['finished airing', 'finalizado', 'finalizada'],
                'PROXIMAMENTE' => ['not yet aired', 'proximamente', 'pr?ximamente'],
            ];
            $normalizedStatus = strtoupper($status);
            if (isset($statusMap[$normalizedStatus])) {
                $builder->groupStart();
                foreach ($statusMap[$normalizedStatus] as $statusValue) {
                    $builder->orWhere('LOWER(estado)', $statusValue);
                }
                $builder->groupEnd();
            }
        }

        if ($type !== 'ALL' && !empty($type)) {
            $builder->where('UPPER(tipo)', strtoupper($type));
        }

        if (!empty($year)) {
            $builder->where("CAST(anio AS CHAR) LIKE '%" . $this->db->escapeLikeString($year) . "%'");
        }

        $total = $builder->countAllResults(false);
        $totalPages = max(1, (int)ceil($total / max(1, $perPage)));
        $page = min(max(1, $page), $totalPages);
        $offset = ($page - 1) * $perPage;

        $builder->orderBy('id', 'DESC')->limit($perPage, $offset);
        $animes = $builder->get()->getResultArray();

        $airingSubquery = $this->db->table('anime_generos')
            ->select('anime_id')
            ->whereIn('genero_id', function ($db) use ($restrictedGenres) {
                return $db->from('generos')->select('id')->whereIn('nombre', $restrictedGenres);
            });
            
        $airingCount = $this->db->table('anime')
            ->whereIn('LOWER(estado)', ['en emision', 'currently airing'])
            ->whereNotIn('id', $airingSubquery)
            ->countAllResults();

        return [
            'data' => $animes,
            'total' => $total,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'airingCount' => $airingCount
        ];
    }
}
