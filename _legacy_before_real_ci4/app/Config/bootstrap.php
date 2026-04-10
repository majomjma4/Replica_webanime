<?php

declare(strict_types=1);

if (!function_exists('replica_ci4_root_path')) {
    function replica_ci4_root_path(string $path = ''): string
    {
        $base = dirname(__DIR__, 2);
        $trimmed = ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);
        return $trimmed === '' ? $base : $base . DIRECTORY_SEPARATOR . $trimmed;
    }
}


if (!defined('APP_ROOT')) {
    define('APP_ROOT', replica_ci4_root_path('app'));
}


if (!function_exists('replica_base_path')) {
    function replica_base_path(): string
    {
        $scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
        $baseDir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/.');
        if ($baseDir === '' || $baseDir === '.') {
            return '/';
        }

        return $baseDir === '/' ? '/' : $baseDir . '/';
    }
}

if (!function_exists('replica_load_env')) {
    function replica_load_env(?string $envPath = null): void
    {
        static $loaded = false;
        static $loadedPath = null;

        $path = $envPath ?? replica_ci4_root_path('.env');
        if ($loaded && $loadedPath === $path) {
            return;
        }

        if (!is_file($path) || !is_readable($path)) {
            $loaded = true;
            $loadedPath = $path;
            return;
        }

        $envVars = parse_ini_file($path, false, INI_SCANNER_RAW);
        if (!is_array($envVars)) {
            $loaded = true;
            $loadedPath = $path;
            return;
        }

        foreach ($envVars as $key => $value) {
            if (!is_string($key) || $key === '') {
                continue;
            }

            $normalizedValue = is_scalar($value) ? (string) $value : '';
            if (getenv($key) === false) {
                putenv($key . '=' . $normalizedValue);
            }
            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $normalizedValue;
            }
            if (!array_key_exists($key, $_SERVER)) {
                $_SERVER[$key] = $normalizedValue;
            }
        }

        $loaded = true;
        $loadedPath = $path;
    }
}

replica_load_env();

if (!function_exists('app_env')) {
    function app_env(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
        return $value === false || $value === null ? $default : $value;
    }
}

if (!function_exists('app_is_https')) {
    function app_is_https(): bool
    {
        $https = strtolower((string) ($_SERVER['HTTPS'] ?? ''));
        $forwardedProto = strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''));
        $forwardedSsl = strtolower((string) ($_SERVER['HTTP_X_FORWARDED_SSL'] ?? ''));
        $forwardedPort = (string) ($_SERVER['HTTP_X_FORWARDED_PORT'] ?? '');

        return ($https !== '' && $https !== 'off')
            || (($_SERVER['SERVER_PORT'] ?? null) === '443')
            || $forwardedProto === 'https'
            || $forwardedSsl === 'on'
            || $forwardedPort === '443';
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_name((string) app_env('SESSION_NAME', 'ReplicaCI4Session'));
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => app_is_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

if (!function_exists('app_start_session')) {
    function app_start_session(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['_session_initialized'])) {
            session_regenerate_id(true);
            $_SESSION['_session_initialized'] = time();
        }
    }
}

if (!function_exists('app_csrf_token')) {
    function app_csrf_token(): string
    {
        app_start_session();
        if (empty($_SESSION['_csrf_token']) || !is_string($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }
}

if (!function_exists('app_publish_csrf_cookie')) {
    function app_publish_csrf_cookie(): void
    {
        if (headers_sent()) {
            return;
        }

        setcookie('XSRF-TOKEN', app_csrf_token(), [
            'expires' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => app_is_https(),
            'httponly' => false,
            'samesite' => 'Lax',
        ]);
    }
}

if (!function_exists('app_get_json_input')) {
    function app_get_json_input(): array
    {
        $raw = file_get_contents('php://input');
        if (!is_string($raw) || trim($raw) === '') {
            return [];
        }
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : [];
    }
}

if (!function_exists('app_require_method')) {
    function app_require_method(array|string $methods): void
    {
        $allowed = array_map('strtoupper', (array) $methods);
        $actual = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

        if (!in_array($actual, $allowed, true)) {
            http_response_code(405);
            header('Allow: ' . implode(', ', $allowed));
            echo json_encode(['success' => false, 'error' => 'Metodo no permitido']);
            exit;
        }
    }
}

if (!function_exists('app_verify_csrf')) {
    function app_verify_csrf(): void
    {
        app_start_session();
        $sessionToken = (string) ($_SESSION['_csrf_token'] ?? '');
        $headerToken = (string) ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
        $cookieToken = (string) ($_COOKIE['XSRF-TOKEN'] ?? '');

        if (
            $sessionToken === ''
            || $headerToken === ''
            || $cookieToken === ''
            || !hash_equals($sessionToken, $headerToken)
            || !hash_equals($sessionToken, $cookieToken)
        ) {
            http_response_code(419);
            echo json_encode(['success' => false, 'error' => 'Token CSRF invalido']);
            exit;
        }
    }
}

if (!function_exists('asset_path')) {
    function asset_path(string $path = ''): string
    {
        $base = replica_base_path();
        $trimmed = ltrim($path, '/');
        if ($trimmed === '') {
            return rtrim($base, '/');
        }
        return $base . $trimmed;
    }
}


if (!function_exists('app_slugify')) {
    function app_slugify(string $value, string $fallback = ''): string
    {
        $trimmed = trim($value);
        if ($trimmed === '') {
            return $fallback;
        }

        $slug = strtolower(trim((string) iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $trimmed)));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? '';
        $slug = trim($slug, '-');

        return $slug !== '' ? $slug : $fallback;
    }
}

if (!function_exists('app_detail_ref_from_input')) {
    function app_detail_ref_from_input(string $malId = '', string $title = '', string $dbId = ''): string
    {
        $candidate = trim($malId) !== '' ? trim($malId) : trim($dbId);
        if ($candidate !== '' && ctype_digit($candidate)) {
            return $candidate;
        }

        return app_slugify($title, 'anime');
    }
}

if (!function_exists('detail_path')) {
    function detail_path(string $malId = '', string $title = '', string $dbId = ''): string
    {
        $detailRef = app_detail_ref_from_input($malId, $title, $dbId);
        if ($detailRef === '') {
            return asset_path('detail');
        }

        return asset_path('detail/' . rawurlencode($detailRef));
    }
}
if (!function_exists('route_path')) {
    function route_path(string $name, array $query = []): string
    {
        $routes = [
            'payment' => 'pago',
            'detail' => 'detail',
            'home' => 'index',
            'featured' => 'destacados',
            'ranking' => 'ranking',
            'series' => 'series',
            'movies' => 'peliculas',
            'register' => 'registro',
            'login' => 'ingresar',
            'admin' => 'admin',
            'user' => 'user',
        ];

        $path = $routes[$name] ?? 'index';
        $url = asset_path($path);
        if ($query) {
            $url .= '?' . http_build_query($query);
        }
        return $url;
    }
}

if (!function_exists('e')) {
    function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

app_publish_csrf_cookie();

spl_autoload_register(static function (string $class): void {
    $prefix = 'ReplicaCi4\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $path = replica_ci4_root_path('app/' . str_replace('\\', '/', $relative) . '.php');
    if (is_file($path)) {
        require_once $path;
    }
});


