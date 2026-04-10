<?php

declare(strict_types=1);

namespace ReplicaCi4\Controllers\Api;

use ReplicaCi4\Models\Database;
use Throwable;

final class Activity
{
    public function handle(): void
    {
        header('Content-Type: application/json; charset=UTF-8');
        app_require_method('POST');
        app_verify_csrf();
        app_start_session();

        $userId = $_SESSION['user_id'] ?? null;
        $role = $_SESSION['role'] ?? 'Invitado';
        if (!$userId || $role === 'Invitado') {
            echo json_encode(['success' => true, 'message' => 'Modo invitado: no se registra actividad']);
            return;
        }

        $data = app_get_json_input();
        $action = (string) ($data['action'] ?? '');
        if ($action === '') {
            echo json_encode(['success' => false, 'error' => 'Accion no especificada']);
            return;
        }

        $dbConn = (new Database())->getConnection();
        if (!$dbConn) {
            return;
        }

        try {
            if ($action === 'time_sync') {
                $delta = (float) ($data['delta'] ?? 0);
                if ($delta > 0) {
                    $stmt = $dbConn->prepare("INSERT INTO usuarios_meta (usuario_id, meta_key, meta_value) VALUES (?, 'profile_hours', ?) ON DUPLICATE KEY UPDATE meta_value = CAST(meta_value AS DECIMAL(10,4)) + ?");
                    $stmt->execute([$userId, $delta, $delta]);
                }
            } else {
                $details = (string) ($data['details'] ?? '');
                $stmt = $dbConn->prepare('INSERT INTO usuarios_actividad (usuario_id, accion, detalles) VALUES (?, ?, ?)');
                $stmt->execute([$userId, $action, $details]);
            }

            echo json_encode(['success' => true]);
        } catch (Throwable $exception) {
            echo json_encode(['success' => false, 'error' => $exception->getMessage()]);
        }
    }
}
