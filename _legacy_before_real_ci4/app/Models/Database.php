<?php

declare(strict_types=1);

namespace ReplicaCi4\Models;

use PDO;
use PDOException;

final class Database
{
    private ?PDO $conn = null;

    public function getConnection(bool $respondOnError = true): ?PDO
    {
        if ($this->conn instanceof PDO) {
            return $this->conn;
        }

        $host = (string) app_env('DB_HOST', '127.0.0.1');
        $dbName = (string) app_env('DB_NAME', 'webanime_ci4_replica');
        $username = (string) app_env('DB_USER', 'root');
        $password = (string) app_env('DB_PASS', '');
        $port = (string) app_env('DB_PORT', '3306');

        try {
            $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $dbName);
            $this->conn = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $exception) {
            if ($respondOnError) {
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Error de conexion replica: ' . $exception->getMessage()]);
            }
            return null;
        }

        return $this->conn;
    }
}


