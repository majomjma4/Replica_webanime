<?php

declare(strict_types=1);

namespace ReplicaCi4\Controllers\Api;

use ReplicaCi4\Models\Database;
use Throwable;

final class Profile
{
    public function handle(): void
    {
        header('Content-Type: application/json; charset=UTF-8');
        app_start_session();

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'error' => 'No session']);
            return;
        }

        $dbConn = (new Database())->getConnection();
        if (!$dbConn) {
            return;
        }

        $action = (string) ($_GET['action'] ?? 'get');

        if ($action === 'get') {
            app_require_method('GET');
            $userStmt = $dbConn->prepare('SELECT codigo_publico FROM usuarios WHERE id = ?');
            $userStmt->execute([$userId]);
            $userRow = $userStmt->fetch() ?: [];

            $stmt = $dbConn->prepare('SELECT meta_key, meta_value FROM usuarios_meta WHERE usuario_id = ?');
            $stmt->execute([$userId]);
            $meta = $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);

            echo json_encode([
                'success' => true,
                'data' => [
                    'anidex_profile_name' => $meta['profile_name'] ?? ($_SESSION['username'] ?? ''),
                    'anidex_profile_desc' => $meta['profile_desc'] ?? 'Explorador de animes en la replica CI4',
                    'anidex_profile_color' => $meta['profile_color'] ?? '',
                    'anidex_profile_avatar' => $meta['profile_avatar'] ?? '',
                    'anidex_profile_member_since' => $meta['profile_member_since'] ?? date('Y'),
                    'anidex_user_id' => (string) $userId,
                    'anidex_public_user_id' => $userRow['codigo_publico'] ?? null,
                    'anidex_profile_hours' => $meta['profile_hours'] ?? '0',
                    'anidex_profile_prefs' => json_decode($meta['anidex_profile_prefs'] ?? '[]', true),
                    'anidex_continue_v1' => json_decode($meta['anidex_continue_v1'] ?? '[]', true),
                    'anidex_my_list_v1' => json_decode($meta['my_list'] ?? '[]', true),
                    'anidex_favorites_v1' => json_decode($meta['favorites'] ?? '[]', true),
                    'anidex_status_v1' => json_decode($meta['status_list'] ?? '[]', true),
                ],
            ]);
            return;
        }

        if ($action === 'save') {
            app_require_method('POST');
            app_verify_csrf();
            $data = app_get_json_input();
            if (!$data) {
                echo json_encode(['success' => false, 'error' => 'No data provided']);
                return;
            }

            try {
                $dbConn->beginTransaction();
                $stmt = $dbConn->prepare('INSERT INTO usuarios_meta (usuario_id, meta_key, meta_value) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE meta_value = VALUES(meta_value)');
                foreach ($data as $key => $value) {
                    $stmt->execute([$userId, $key, is_array($value) ? json_encode($value) : (string) $value]);
                }
                $dbConn->commit();
                echo json_encode(['success' => true]);
            } catch (Throwable $exception) {
                if ($dbConn->inTransaction()) {
                    $dbConn->rollBack();
                }
                echo json_encode(['success' => false, 'error' => $exception->getMessage()]);
            }
            return;
        }

        echo json_encode(['success' => false, 'error' => 'Accion desconocida']);
    }
}
