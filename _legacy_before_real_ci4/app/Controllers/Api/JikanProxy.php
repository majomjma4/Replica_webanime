<?php

declare(strict_types=1);

namespace ReplicaCi4\Controllers\Api;

use ReplicaCi4\Models\Database;
use PDO;

final class JikanProxy
{
    private array $restrictedGenres = ['Hentai', 'Erotica', 'Ecchi', 'Yaoi', 'Yuri', 'Gore', 'Harem', 'Reverse Harem', 'Rx', 'Girls Love', 'Boys Love'];
    private array $restrictedTitles = ['does it count if', 'futanari', 'e-p-h-o-r-i-a'];

    public function handle(): void
    {
        error_reporting(0);
        ini_set('display_errors', '0');
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');

        $endpoint = (string) ($_GET['endpoint'] ?? '');
        if ($endpoint === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Endpoint is required']);
            return;
        }

        $db = (new Database())->getConnection();
        if (!$db) {
            return;
        }

        $cacheKey = md5($endpoint);
        $stmt = $db->prepare('SELECT response, updated_at FROM jikan_cache WHERE cache_key = ? LIMIT 1');
        $stmt->execute([$cacheKey]);
        $cached = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cached) {
            $updatedAt = strtotime((string) $cached['updated_at']);
            if ((time() - $updatedAt) < (48 * 3600)) {
                echo (string) $cached['response'];
                return;
            }
        }

        $url = 'https://api.jikan.moe/v4/' . ltrim($endpoint, '/');
        if (preg_match('/(anime|manga|top|seasons|search)/i', $url) && !str_contains($url, 'sfw=')) {
            $url .= (str_contains($url, '?') ? '&' : '?') . 'sfw=1';
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'WebAnime_CI4_Replica Jikan Proxy');
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && !empty($response)) {
            $filteredResponse = $this->filterResponse((string) $response);
            $save = $db->prepare('REPLACE INTO jikan_cache (cache_key, endpoint, response) VALUES (?, ?, ?)');
            $save->execute([$cacheKey, $endpoint, $filteredResponse]);
            echo $filteredResponse;
            return;
        }

        if ($httpCode === 429) {
            http_response_code(429);
            echo json_encode(['error' => 'Rate limited by Jikan']);
            return;
        }

        if ($cached) {
            echo $this->filterResponse((string) $cached['response']);
            return;
        }

        http_response_code($httpCode ?: 500);
        echo $response ?: json_encode(['error' => 'Jikan API failed and no cache available']);
    }

    private function filterResponse(string $json): string
    {
        $data = json_decode($json, true);
        if (!is_array($data) || !isset($data['data'])) {
            return $json;
        }

        $items = $data['data'];
        $isSingle = isset($items['mal_id']);
        if ($isSingle) {
            if ($this->isRestricted($items)) {
                return json_encode(['data' => null]);
            }
            return $json;
        }

        $filtered = array_values(array_filter($items, fn ($item) => !$this->isRestricted($item)));
        $data['data'] = $filtered;
        return json_encode($data);
    }

    private function isRestricted(array $item): bool
    {
        $title = strtolower((string) ($item['title'] ?? $item['title_english'] ?? ''));
        foreach ($this->restrictedTitles as $restricted) {
            if (str_contains($title, $restricted)) {
                return true;
            }
        }

        $genres = array_merge($item['genres'] ?? [], $item['explicit_genres'] ?? []);
        foreach ($genres as $genre) {
            $name = (string) ($genre['name'] ?? '');
            if (in_array($name, $this->restrictedGenres, true)) {
                return true;
            }
        }

        $rating = (string) ($item['rating'] ?? '');
        return str_contains($rating, 'Rx') || str_contains($rating, 'Hentai');
    }
}
