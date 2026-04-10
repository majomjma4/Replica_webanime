<?php

declare(strict_types=1);

namespace ReplicaCi4\Models;

use PDO;
use Exception;

final class Anime
{
    private ?PDO $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection(false);
    }

    public function getById(int $id): ?array
    {
        if (!$this->db) {
            return null;
        }

        $stmt = $this->db->prepare("SELECT * FROM anime WHERE mal_id = :id OR id = :id LIMIT 1");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $anime = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($anime) {
            $anime['generos'] = $this->getGeneros((int) $anime['id']);
            return $anime;
        }

        return $id > 0 ? $this->importFromJikanByMalId($id) : null;
    }

    public function getByTitle(string $title): ?array
    {
        if (!$this->db) {
            return null;
        }

        $cleanTitle = trim($title);
        if ($cleanTitle === '') {
            return null;
        }

        $stmt = $this->db->prepare("SELECT * FROM anime WHERE titulo = :title OR titulo_ingles = :title LIMIT 1");
        $stmt->bindValue(':title', $cleanTitle, PDO::PARAM_STR);
        $stmt->execute();
        $anime = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$anime) {
            $stmt = $this->db->prepare("SELECT * FROM anime WHERE titulo LIKE :title OR titulo_ingles LIKE :title LIMIT 1");
            $stmt->bindValue(':title', '%' . $cleanTitle . '%', PDO::PARAM_STR);
            $stmt->execute();
            $anime = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if ($anime) {
            $anime['generos'] = $this->getGeneros((int) $anime['id']);
            return $anime;
        }

        return $this->importFromJikanByTitle($cleanTitle);
    }

    public function getGeneros(int $animeId): array
    {
        if (!$this->db) {
            return [];
        }

        $stmt = $this->db->prepare(
            "SELECT g.nombre
             FROM generos g
             INNER JOIN anime_generos ag ON g.id = ag.genero_id
             WHERE ag.anime_id = :anime_id"
        );
        $stmt->bindValue(':anime_id', $animeId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
    }

    public function getFilteredGenres(): array
    {
        if (!$this->db) {
            return [];
        }

        $restricted = ["'Hentai'", "'Erotica'", "'Ecchi'", "'Yaoi'", "'Yuri'", "'Gore'", "'Harem'", "'Reverse Harem'", "'Rx'", "'Girls Love'", "'Boys Love'"];
        $sql = "SELECT nombre FROM generos WHERE nombre NOT IN (" . implode(',', $restricted) . ") ORDER BY nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
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
        if (!$this->db) {
            return [];
        }

        $restrictedGenres = ["'Hentai'", "'Erotica'", "'Ecchi'", "'Yaoi'", "'Yuri'", "'Girls Love'", "'Boys Love'"];
        $restrictedTitles = ["'%does it count if%'", "'%futanari%'"];

        $typeWhere = $movies ? "tipo = 'Movie'" : "tipo != 'Movie'";
        $sql = "SELECT * FROM anime
                WHERE {$typeWhere}
                  AND activo = 1
                  AND id NOT IN (
                    SELECT anime_id FROM anime_generos
                    WHERE genero_id IN (SELECT id FROM generos WHERE nombre IN (" . implode(',', $restrictedGenres) . "))
                  )
                  AND LOWER(titulo) NOT LIKE " . implode(" AND LOWER(titulo) NOT LIKE ", $restrictedTitles);

        if ($movies) {
            $sql .= " ORDER BY puntuacion DESC, id DESC";
        } else {
            $sql .= " ORDER BY
                        CASE
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
                          ELSE 40
                        END ASC,
                        puntuacion DESC,
                        id DESC";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $animes = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

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
        if (!$this->db) {
            return null;
        }

        $malId = (int) ($anime['mal_id'] ?? 0);
        if ($malId <= 0) {
            return null;
        }

        $check = $this->db->prepare('SELECT id FROM anime WHERE mal_id = ?');
        $check->execute([$malId]);
        if ($check->fetchColumn()) {
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
            $insert = $this->db->prepare('INSERT INTO anime (mal_id, titulo, titulo_ingles, tipo, estado, episodios, anio, clasificacion, sinopsis, imagen_url, trailer_url, puntuacion, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)');
            $insert->execute([$malId, $titulo, $tituloIngles, $tipo, $estado, $episodes, $anio ?: null, $rating, $sinopsis, $img, $trailerUrl, $score]);
            $internalId = (int) $this->db->lastInsertId();

            if (!empty($anime['genres']) && is_array($anime['genres'])) {
                foreach ($anime['genres'] as $genre) {
                    $genreName = substr((string) ($genre['name'] ?? ''), 0, 50);
                    if ($genreName === '') {
                        continue;
                    }
                    $stmt = $this->db->prepare('SELECT id FROM generos WHERE nombre = ?');
                    $stmt->execute([$genreName]);
                    $genreId = $stmt->fetchColumn();
                    if (!$genreId) {
                        $this->db->prepare('INSERT INTO generos (nombre) VALUES (?)')->execute([$genreName]);
                        $genreId = $this->db->lastInsertId();
                    }
                    $this->db->prepare('INSERT INTO anime_generos (anime_id, genero_id) VALUES (?, ?)')->execute([$internalId, $genreId]);
                }
            }

            return $this->getById($malId);
        } catch (Exception) {
            return null;
        }
    }
}
