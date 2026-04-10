<?php

declare(strict_types=1);

namespace ReplicaCi4\Controllers\Api;

use ReplicaCi4\Controllers\BaseController;

final class JikanProxy extends BaseController
{
    private array $restrictedGenres = ['Hentai', 'Erotica', 'Ecchi', 'Yaoi', 'Yuri', 'Gore', 'Harem', 'Reverse Harem', 'Rx', 'Girls Love', 'Boys Love'];
    private array $restrictedTitles = ['does it count if', 'futanari', 'e-p-h-o-r-i-a'];

    public function handle()
    {
        $endpoint = (string) $this->request->getGet('endpoint');
        if ($endpoint === '') {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Endpoint is required']);
        }

        $db = \Config\Database::connect();
        if (!$db->tableExists('jikan_cache')) {
            // Early bypass if table does not exist somehow
            return $this->fetchFromApi($endpoint, null);
        }

        $cacheKey = md5($endpoint);
        $cached = $db->table('jikan_cache')->where('cache_key', $cacheKey)->get()->getRowArray();

        if ($cached) {
            $updatedAt = strtotime((string) $cached['updated_at']);
            if ((time() - $updatedAt) < (48 * 3600)) {
                return $this->response->setHeader('Content-Type', 'application/json')->setBody((string) $cached['response']);
            }
        }

        return $this->fetchFromApi($endpoint, $cached, $cacheKey, $db);
    }

    private function fetchFromApi(string $endpoint, ?array $cached = null, ?string $cacheKey = null, $db = null)
    {
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
            
            if ($db && $cacheKey) {
                // Implement REPLACE INTO or Update/Insert based on existence
                $exists = $db->table('jikan_cache')->where('cache_key', $cacheKey)->countAllResults(false) > 0;
                if ($exists) {
                    $db->table('jikan_cache')->where('cache_key', $cacheKey)->update([
                        'endpoint' => $endpoint,
                        'response' => $filteredResponse,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                } else {
                    $db->table('jikan_cache')->insert([
                        'cache_key' => $cacheKey,
                        'endpoint' => $endpoint,
                        'response' => $filteredResponse,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
            return $this->response->setHeader('Content-Type', 'application/json')->setBody($filteredResponse);
        }

        if ($httpCode === 429) {
            return $this->response->setStatusCode(429)->setJSON(['error' => 'Rate limited by Jikan']);
        }

        if ($cached) {
            return $this->response->setHeader('Content-Type', 'application/json')->setBody($this->filterResponse((string) $cached['response']));
        }

        $statusCode = $httpCode ?: 500;
        $body = $response ?: json_encode(['error' => 'Jikan API failed and no cache available']);
        return $this->response->setStatusCode($statusCode)->setHeader('Content-Type', 'application/json')->setBody($body);
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
