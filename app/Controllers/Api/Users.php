<?php
declare(strict_types=1);
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use Exception;
use PDO;

final class Users extends BaseController
{
    public function handle(): \CodeIgniter\HTTP\ResponseInterface
    {
        $action = (string) ($this->request->getGet('action') ?? 'list');
        $writeActions = ['toggle_status', 'block', 'delete', 'reset_password', 'toggle_admin'];
        if (in_array($action, $writeActions, true)) {
            if ($this->request->getMethod(true) !== 'POST') {
                return $this->response->setStatusCode(405)->setJSON(['success' => false, 'error' => 'Metodo no permitido']);
            }
            app_verify_csrf();
        } else {
            if ($this->request->getMethod(true) !== 'GET') {
                return $this->response->setStatusCode(405)->setJSON(['success' => false, 'error' => 'Metodo no permitido']);
            }
        }

        $session = session();
        $isAdmin = $session->has('user_id') && $session->get('role') === 'Admin';
        if (!$isAdmin) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'error' => 'Acceso denegado']);
        }

        $data = $this->request->getJSON(true) ?? [];
        try {
            $dbConfig = config('Database');
            $dsn = "mysql:host={$dbConfig->default['hostname']};port={$dbConfig->default['port']};dbname={$dbConfig->default['database']};charset={$dbConfig->default['charset']}";
            $dbConn = new PDO($dsn, $dbConfig->default['username'], $dbConfig->default['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'error' => 'Error de conexiÃ³n a la base de datos']);
        }

        $isProtectedPrimaryAdmin = static function (PDO $dbConn, int $userId): bool {
            if ($userId <= 0) return false;
            try {
                $stmt = $dbConn->prepare("SELECT u.nombre_mostrar, COALESCE(r.nombre, '') AS role_name FROM usuarios u LEFT JOIN roles r ON r.id = u.rol_id WHERE u.id = ? LIMIT 1");
                $stmt->execute([$userId]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
                $username = strtolower(trim((string) ($row['nombre_mostrar'] ?? '')));
                $roleName = strtolower(trim((string) ($row['role_name'] ?? '')));
                return $username === 'admin99' && $roleName === 'admin';
            } catch (Exception $e) {
                return false;
            }
        };

        try {
            $dbConn->exec("UPDATE usuarios SET activo = 1, bloqueado = 0, bloqueado_en = NULL, motivo_bloqueo = NULL, penalizacion_hasta = NULL WHERE bloqueado = 1 AND penalizacion_hasta IS NOT NULL AND penalizacion_hasta <= NOW()");
        } catch (Exception $e) {}

        if ($action === 'list') {
            try {
                $stmt = $dbConn->query("SELECT u.id, u.codigo_publico AS public_id, u.nombre_mostrar AS name, u.correo AS email, u.creado_en, u.activo, u.bloqueado, u.motivo_bloqueo, u.penalizacion_hasta, u.es_premium, COALESCE(r.nombre, 'Registrado') AS base_role FROM usuarios u LEFT JOIN roles r ON r.id = u.rol_id ORDER BY u.creado_en DESC");
                $users = [];
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $isBlocked = ((int) $row['activo'] !== 1) || ((int) $row['bloqueado'] === 1);
                    $baseRole = trim((string) ($row['base_role'] ?? 'Registrado'));
                    $isAdminRow = strtolower($baseRole) === 'admin';
                    $users[] = ['id' => (int) $row['id'], 'public_id' => $row['public_id'], 'name' => $row['name'] ?: 'Usuario', 'role' => $isAdminRow ? 'Admin' : (((int) $row['es_premium'] === 1) ? 'Premium' : 'Registrado'), 'email' => $row['email'], 'date' => date('d/m/Y', strtotime($row['creado_en'])), 'status' => $isBlocked ? 'BLOQUEADO' : 'ACTIVO', 'is_premium' => ((int) $row['es_premium'] === 1), 'is_admin' => $isAdminRow, 'block_reason' => $row['motivo_bloqueo'] ?: '', 'penalty_until' => $row['penalizacion_hasta'] ? date('d/m/Y H:i', strtotime($row['penalizacion_hasta'])) : null];
                }

                $statsStmt = $dbConn->query("SELECT COUNT(*) AS total_users, SUM(CASE WHEN LOWER(COALESCE(r.nombre, 'registrado')) = 'admin' THEN 1 ELSE 0 END) AS admin_users, SUM(CASE WHEN u.activo = 1 AND (u.bloqueado = 0 OR u.bloqueado IS NULL) THEN 1 ELSE 0 END) AS active_accounts, SUM(CASE WHEN u.activo <> 1 OR u.bloqueado = 1 THEN 1 ELSE 0 END) AS blocked_users, SUM(CASE WHEN LOWER(COALESCE(r.nombre, 'registrado')) <> 'admin' AND u.es_premium = 1 THEN 1 ELSE 0 END) AS premium_users, SUM(CASE WHEN EXISTS (SELECT 1 FROM usuarios_sesiones us WHERE us.usuario_id = u.id AND us.fin IS NULL AND us.inicio >= DATE_SUB(NOW(), INTERVAL 24 HOUR)) THEN 1 ELSE 0 END) AS active_now FROM usuarios u LEFT JOIN roles r ON r.id = u.rol_id");
                $stats = $statsStmt->fetch(PDO::FETCH_ASSOC) ?: [];
                return $this->response->setJSON(['success' => true, 'data' => $users, 'stats' => ['total_users' => (int) ($stats['total_users'] ?? 0), 'admin_users' => (int) ($stats['admin_users'] ?? 0), 'active_accounts' => (int) ($stats['active_accounts'] ?? 0), 'blocked_users' => (int) ($stats['blocked_users'] ?? 0), 'premium_users' => (int) ($stats['premium_users'] ?? 0), 'active_now' => (int) ($stats['active_now'] ?? 0)]]);
            } catch (Exception $e) {
                return $this->response->setJSON(['success' => false, 'error' => $e->getMessage()]);
            }
        }

        if ($action === 'toggle_status') {
            $userId = (int) ($data['id'] ?? 0);
            $adminId = (int) ($session->get('user_id') ?? 0);
            $status = (int) ($data['activo'] ?? 1);
            if ($userId <= 0) return $this->response->setJSON(['success' => false, 'error' => 'ID de usuario invalido']);
            if ($userId === $adminId) return $this->response->setJSON(['success' => false, 'error' => 'No puedes cambiar tu propio estado desde aquÃ­']);
            if ($isProtectedPrimaryAdmin($dbConn, $userId)) return $this->response->setJSON(['success' => false, 'error' => 'No puedes cambiar el estado del administrador principal']);
            try {
                $isBlocked = $status === 1 ? 0 : 1;
                $stmt = $dbConn->prepare("UPDATE usuarios SET activo = ?, bloqueado = ?, bloqueado_en = CASE WHEN ? = 1 THEN NOW() ELSE NULL END, bloqueado_por = CASE WHEN ? = 1 THEN ? ELSE bloqueado_por END, desbloqueado_en = CASE WHEN ? = 0 THEN NOW() ELSE desbloqueado_en END, desbloqueado_por = CASE WHEN ? = 0 THEN ? ELSE desbloqueado_por END, motivo_bloqueo = CASE WHEN ? = 1 THEN motivo_bloqueo ELSE NULL END, penalizacion_hasta = CASE WHEN ? = 1 THEN penalizacion_hasta ELSE NULL END WHERE id = ?");
                $stmt->execute([$status, $isBlocked, $isBlocked, $isBlocked, $adminId > 0 ? $adminId : null, $isBlocked, $isBlocked, $adminId > 0 ? $adminId : null, $isBlocked, $isBlocked, $userId]);
                return $this->response->setJSON(['success' => true]);
            } catch (Exception $e) {
                return $this->response->setJSON(['success' => false, 'error' => $e->getMessage()]);
            }
        }

        if ($action === 'block') {
            $userId = (int) ($data['id'] ?? 0);
            $adminId = (int) ($session->get('user_id') ?? 0);
            $presetReason = trim((string) ($data['reason'] ?? ''));
            $customReason = trim((string) ($data['custom_reason'] ?? ''));
            $penalty = trim((string) ($data['penalty'] ?? ''));
            if ($userId <= 0) return $this->response->setJSON(['success' => false, 'error' => 'ID de usuario invalido']);
            if ($userId === $adminId) return $this->response->setJSON(['success' => false, 'error' => 'No puedes bloquear tu propia cuenta']);
            if ($isProtectedPrimaryAdmin($dbConn, $userId)) return $this->response->setJSON(['success' => false, 'error' => 'No puedes bloquear al administrador principal']);
            
            $finalReason = $customReason !== '' ? $customReason : $presetReason;
            if ($finalReason === '') return $this->response->setJSON(['success' => false, 'error' => 'Debes indicar un motivo de bloqueo']);
            
            $penaltyUntil = null;
            $penaltyMap = ['24h' => '+1 day', '3d' => '+3 days', '7d' => '+7 days', '30d' => '+30 days', 'permanente' => null];
            if (!array_key_exists($penalty, $penaltyMap)) return $this->response->setJSON(['success' => false, 'error' => 'Debes elegir un tiempo de penalizaciÃ³n vÃ¡lido']);
            
            if ($penaltyMap[$penalty] !== null) {
                $penaltyUntil = date('Y-m-d H:i:s', strtotime($penaltyMap[$penalty]));
            }
            try {
                $stmt = $dbConn->prepare("UPDATE usuarios SET activo = 0, bloqueado = 1, bloqueado_en = NOW(), bloqueado_por = ?, desbloqueado_en = NULL, desbloqueado_por = NULL, motivo_bloqueo = ?, penalizacion_hasta = ? WHERE id = ?");
                $stmt->execute([$adminId > 0 ? $adminId : null, $finalReason, $penaltyUntil, $userId]);
                return $this->response->setJSON(['success' => true, 'penalty_until' => $penaltyUntil, 'reason' => $finalReason]);
            } catch (Exception $e) {
                return $this->response->setJSON(['success' => false, 'error' => $e->getMessage()]);
            }
        }

        if ($action === 'delete') {
            $userId = (int) ($data['id'] ?? 0);
            $adminId = (int) ($session->get('user_id') ?? 0);
            if ($userId === $adminId) return $this->response->setJSON(['success' => false, 'error' => 'No puedes eliminar tu propia cuenta desde aquÃ­']);
            if ($userId <= 0) return $this->response->setJSON(['success' => false, 'error' => 'ID de usuario invalido']);
            if ($isProtectedPrimaryAdmin($dbConn, $userId)) return $this->response->setJSON(['success' => false, 'error' => 'No puedes eliminar al administrador principal']);
            try {
                $dbConn->beginTransaction();
                $dbConn->prepare("DELETE FROM usuarios_meta WHERE usuario_id = ?")->execute([$userId]);
                $dbConn->prepare("DELETE FROM usuarios_actividad WHERE usuario_id = ?")->execute([$userId]);
                $dbConn->prepare("DELETE FROM usuarios_reportes WHERE usuario_id = ?")->execute([$userId]);
                $dbConn->prepare("DELETE FROM usuarios_sesiones WHERE usuario_id = ?")->execute([$userId]);
                $dbConn->prepare("DELETE FROM usuarios_vistas WHERE usuario_id = ?")->execute([$userId]);
                $dbConn->prepare("DELETE FROM comentarios WHERE usuario_id = ?")->execute([$userId]);
                $dbConn->prepare("DELETE FROM solicitudes_anime WHERE usuario_id = ?")->execute([$userId]);
                $dbConn->prepare("DELETE FROM usuarios WHERE id = ?")->execute([$userId]);
                $dbConn->commit();
                return $this->response->setJSON(['success' => true]);
            } catch (Exception $e) {
                if ($dbConn->inTransaction()) $dbConn->rollBack();
                return $this->response->setJSON(['success' => false, 'error' => $e->getMessage()]);
            }
        }

        if ($action === 'reset_password') {
            $userId = (int) ($data['id'] ?? 0);
            $adminId = (int) ($session->get('user_id') ?? 0);
            $newPassword = trim((string) ($data['password'] ?? ''));
            if ($userId <= 0) return $this->response->setJSON(['success' => false, 'error' => 'ID de usuario invalido']);
            if ($isProtectedPrimaryAdmin($dbConn, $userId)) return $this->response->setJSON(['success' => false, 'error' => 'No puedes cambiar la contraseÃ±a del administrador principal']);
            if (mb_strlen($newPassword) < 6) return $this->response->setJSON(['success' => false, 'error' => 'La contraseÃ±a debe tener al menos 6 caracteres']);
            
            try {
                $currentStmt = $dbConn->prepare("SELECT hash_password FROM usuarios WHERE id = ? LIMIT 1");
                $currentStmt->execute([$userId]);
                $currentHash = (string) ($currentStmt->fetchColumn() ?: '');
                if ($currentHash !== '' && password_verify($newPassword, $currentHash)) {
                    return $this->response->setJSON(['success' => false, 'error' => 'La nueva contraseÃ±a no puede ser igual a la actual']);
                }
                $hash = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $dbConn->prepare("UPDATE usuarios SET hash_password = ?, password_actualizado_en = NOW(), password_actualizado_por = ? WHERE id = ?");
                $stmt->execute([$hash, $adminId > 0 ? $adminId : null, $userId]);
                $metaStmt = $dbConn->prepare("SELECT DATE_FORMAT(password_actualizado_en, '%d/%m/%Y %H:%i') AS changed_at FROM usuarios WHERE id = ? LIMIT 1");
                $metaStmt->execute([$userId]);
                $changedAt = (string) ($metaStmt->fetchColumn() ?: '');
                return $this->response->setJSON(['success' => true, 'changed_at' => $changedAt]);
            } catch (Exception $e) {
                return $this->response->setJSON(['success' => false, 'error' => $e->getMessage()]);
            }
        }

        if ($action === 'toggle_admin') {
            $userId = (int) ($data['id'] ?? 0);
            $adminId = (int) ($session->get('user_id') ?? 0);
            $makeAdmin = !empty($data['make_admin']);
            if ($userId <= 0) return $this->response->setJSON(['success' => false, 'error' => 'ID de usuario invalido']);
            if ($isProtectedPrimaryAdmin($dbConn, $userId)) return $this->response->setJSON(['success' => false, 'error' => 'No puedes modificar el rol del administrador principal']);
            try {
                $roleStmt = $dbConn->prepare("SELECT id FROM roles WHERE LOWER(nombre) = ? LIMIT 1");
                $targetRoleName = $makeAdmin ? 'admin' : 'registrado';
                $roleStmt->execute([$targetRoleName]);
                $targetRoleId = (int) ($roleStmt->fetchColumn() ?: 0);
                if ($targetRoleId <= 0) return $this->response->setJSON(['success' => false, 'error' => 'No se encontrÃ³ el rol solicitado']);
                
                $stmt = $dbConn->prepare("UPDATE usuarios SET rol_id = ?, rol_actualizado_en = NOW(), rol_actualizado_por = ? WHERE id = ?");
                $stmt->execute([$targetRoleId, $adminId > 0 ? $adminId : null, $userId]);
                $premiumStmt = $dbConn->prepare("SELECT es_premium FROM usuarios WHERE id = ? LIMIT 1");
                $premiumStmt->execute([$userId]);
                $isPremiumUser = (int) ($premiumStmt->fetchColumn() ?: 0) === 1;
                $nextRole = $makeAdmin ? 'Admin' : ($isPremiumUser ? 'Premium' : 'Registrado');
                return $this->response->setJSON(['success' => true, 'role' => $nextRole]);
            } catch (Exception $e) {
                return $this->response->setJSON(['success' => false, 'error' => $e->getMessage()]);
            }
        }

        return $this->response->setJSON(['success' => false, 'error' => 'Accion desconocida']);
    }
}
