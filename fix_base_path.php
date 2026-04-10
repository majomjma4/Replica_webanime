<?php
$f = 'c:\xampp\htdocs\WebAnime_CI4_Replica\app\Config\bootstrap.php';
$c = file_get_contents($f);

$find = <<<'EOD'
    function replica_base_path(): string
    {
        $scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
        $baseDir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/.');
        if ($baseDir === '' || $baseDir === '.') {
            return '/';
        }

        return $baseDir === '/' ? '/' : $baseDir . '/';
    }
EOD;

$replace = <<<'EOD'
    function replica_base_path(): string
    {
        $envBaseUrl = trim((string) app_env('app.baseURL', ''), " '\"");
        if ($envBaseUrl !== '') {
            $parsedPath = parse_url($envBaseUrl, PHP_URL_PATH);
            if (!empty($parsedPath)) {
                return rtrim($parsedPath, '/') . '/';
            }
        }

        $scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
        $baseDir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/.');
        if ($baseDir === '' || $baseDir === '.') {
            return '/';
        }

        return $baseDir === '/' ? '/' : $baseDir . '/';
    }
EOD;

// normalize endings
$find = str_replace("\r\n", "\n", $find);
$replace = str_replace("\r\n", "\n", $replace);

// get file contents without \r to easily replace
$c_lf = str_replace("\r\n", "\n", $c);
$c_lf = str_replace($find, $replace, $c_lf);

file_put_contents($f, $c_lf);
echo "Replaced in app/Config/bootstrap.php";
