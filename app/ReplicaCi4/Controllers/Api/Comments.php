<?php

declare(strict_types=1);

namespace ReplicaCi4\Controllers\Api;

use Exception;
use PDO;
use ReplicaCi4\Models\Database;

final class Comments
{
    public function handle(): void
    {
        header('Content-Type: application/json; charset=UTF-8');

        $action = (string) ($_GET['action'] ?? 'list');
        $writeActions = ['report', 'delete', 'add', 'moderate', 'seed'];
        if (in_array($action, $writeActions, true)) {
            app_require_method('POST');
            app_verify_csrf();
            app_start_session();
        } else {
            app_require_method('GET');
            app_start_session();
        }

        $data = app_get_json_input();
        $db = (new Database())->getConnection(false);
        if (!$db instanceof PDO) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Error de conexion a la base de datos']);
            return;
        }

        $resolveAdminAccess = static function (PDO $dbConn, int $userId, string $sessionRole = ''): bool {
            $normalizedRole = strtolower(trim($sessionRole));
            if (in_array($normalizedRole, ['admin', 'administrador'], true)) {
                return true;
            }
            if ($userId <= 0) {
                return false;
            }
            try {
                $stmt = $dbConn->prepare("SELECT COALESCE(r.nombre, '') AS role_name FROM usuarios u LEFT JOIN roles r ON r.id = u.rol_id WHERE u.id = ? LIMIT 1");
                $stmt->execute([$userId]);
                $dbRole = strtolower(trim((string) $stmt->fetchColumn()));
                return in_array($dbRole, ['admin', 'administrador'], true);
            } catch (Exception) {
                return false;
            }
        };

        $resolveSessionUserId = static function (PDO $dbConn, int $userId, string $username = ''): int {
            $cleanUsername = trim($username);
            if ($cleanUsername !== '') {
                try {
                    $stmt = $dbConn->prepare('SELECT id FROM usuarios WHERE nombre_mostrar = ? LIMIT 1');
                    $stmt->execute([$cleanUsername]);
                    $resolved = (int) ($stmt->fetchColumn() ?: 0);
                    if ($resolved > 0) {
                        return $resolved;
                    }
                } catch (Exception) {
                }
            }
            return $userId > 0 ? $userId : 0;
        };

        if ($action === 'list') {
            try {
                $animeLookupId = isset($_GET['anime_mal_id']) ? (int) $_GET['anime_mal_id'] : 0;
                $sql = "SELECT c.id, c.cuerpo AS msg, c.puntuacion AS rating, CASE WHEN rr.report_count > 0 THEN 1 ELSE 0 END AS flagged, c.creado_en, c.autor_externo, u.nombre_mostrar AS user, u.es_premium, a.titulo AS anime, a.mal_id AS anime_mal_id, c.fuente AS source, COALESCE(r.nombre, 'Registrado') AS raw_role, COALESCE(rr.report_count, 0) AS report_count, COALESCE(lr.razon, '') AS report_reason, COALESCE(reporter.nombre_mostrar, '') AS reported_by, COALESCE(dr.estado, '') AS deleted_status, COALESCE(deleter.nombre_mostrar, '') AS deleted_by, COALESCE(mr.estado, '') AS reviewed_status, COALESCE(reviewer.nombre_mostrar, '') AS reviewed_by, mr.revisado_en AS reviewed_at FROM comentarios c INNER JOIN usuarios u ON c.usuario_id = u.id INNER JOIN anime a ON c.anime_id = a.id LEFT JOIN roles r ON r.id = u.rol_id LEFT JOIN (SELECT comentario_id, COUNT(*) AS report_count FROM reportes_comentarios WHERE estado IN ('pendiente', 'en_revision') GROUP BY comentario_id) rr ON rr.comentario_id = c.id LEFT JOIN (SELECT rc1.comentario_id, rc1.razon, rc1.reportado_por FROM reportes_comentarios rc1 INNER JOIN (SELECT comentario_id, MAX(id) AS max_id FROM reportes_comentarios WHERE estado IN ('pendiente', 'en_revision', 'Revisado') GROUP BY comentario_id) latest ON latest.max_id = rc1.id) lr ON lr.comentario_id = c.id LEFT JOIN usuarios reporter ON reporter.id = lr.reportado_por LEFT JOIN (SELECT rc2.comentario_id, rc2.estado, rc2.reportado_por FROM reportes_comentarios rc2 INNER JOIN (SELECT comentario_id, MAX(id) AS max_id FROM reportes_comentarios WHERE estado = 'Eliminado' GROUP BY comentario_id) deleted_latest ON deleted_latest.max_id = rc2.id) dr ON dr.comentario_id = c.id LEFT JOIN usuarios deleter ON deleter.id = dr.reportado_por LEFT JOIN (SELECT rc3.comentario_id, rc3.estado, rc3.revisado_por, rc3.revisado_en FROM reportes_comentarios rc3 INNER JOIN (SELECT comentario_id, MAX(id) AS max_id FROM reportes_comentarios WHERE estado = 'Revisado' GROUP BY comentario_id) reviewed_latest ON reviewed_latest.max_id = rc3.id) mr ON mr.comentario_id = c.id LEFT JOIN usuarios reviewer ON reviewer.id = mr.revisado_por";
                if ($animeLookupId > 0) {
                    $sql .= " WHERE (a.mal_id = :anime_lookup_id OR a.id = :anime_lookup_id) AND NOT EXISTS (SELECT 1 FROM reportes_comentarios hidden_rc WHERE hidden_rc.comentario_id = c.id AND hidden_rc.estado = 'Eliminado')";
                }
                $sql .= ' ORDER BY c.creado_en DESC';
                $stmt = $db->prepare($sql);
                if ($animeLookupId > 0) {
                    $stmt->bindValue(':anime_lookup_id', $animeLookupId, PDO::PARAM_INT);
                }
                $stmt->execute();
                $comments = [];
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $isAdmin = strtolower((string) ($row['raw_role'] ?? '')) === 'admin';
                    $isPremium = (int) ($row['es_premium'] ?? 0) === 1;
                    $roleStr = $isAdmin ? 'ADMINISTRADOR' : ($isPremium ? 'PREMIUM' : 'REGISTRADO');
                    $displayName = trim((string) ($row['source'] ?? '')) === 'jikan' && !empty($row['autor_externo']) ? $row['autor_externo'] : $row['user'];
                    $comments[] = [
                        'id' => (int) $row['id'],
                        'user' => '@' . $displayName,
                        'tag' => $row['flagged'] ? 'REPORTADO' : $roleStr,
                        'anime' => $row['anime'],
                        'anime_mal_id' => (int) ($row['anime_mal_id'] ?? 0),
                        'ep' => 'General Discussion',
                        'msg' => $row['msg'],
                        'rating' => (int) ($row['rating'] ?? 0),
                        'source' => $row['source'] ?: 'usuario',
                        'date' => date('M d, Y h:i A', strtotime((string) $row['creado_en'])),
                        'flagged' => (bool) $row['flagged'],
                        'report_count' => (int) ($row['report_count'] ?? 0),
                        'report_reason' => (string) ($row['report_reason'] ?? ''),
                        'reported_by' => !empty($row['reported_by']) ? '@' . $row['reported_by'] : '',
                        'deleted_status' => (($row['deleted_status'] ?? '') !== '' ? 'Eliminado' : ''),
                        'deleted_by' => !empty($row['deleted_by']) ? '@' . $row['deleted_by'] : '',
                        'reviewed_status' => (string) ($row['reviewed_status'] ?? ''),
                        'reviewed_by' => !empty($row['reviewed_by']) ? '@' . $row['reviewed_by'] : '',
                        'reviewed_at' => !empty($row['reviewed_at']) ? date('M d, Y h:i A', strtotime((string) $row['reviewed_at'])) : '',
                    ];
                }
                echo json_encode(['success' => true, 'data' => $comments]);
            } catch (Exception $exception) {
                echo json_encode(['success' => false, 'error' => $exception->getMessage()]);
            }
            return;
        }

        if ($action === 'add') {
            $userId = (int) ($_SESSION['user_id'] ?? 0);
            $animeMalId = (int) ($data['anime_mal_id'] ?? 0);
            $rating = (int) ($data['rating'] ?? 0);
            $message = trim((string) ($data['message'] ?? ''));
            if ($userId <= 0 || $animeMalId <= 0 || $rating < 1 || $rating > 5 || $message === '') {
                echo json_encode(['success' => false, 'error' => 'Datos invalidos para comentar']);
                return;
            }
            try {
                $animeStmt = $db->prepare('SELECT id FROM anime WHERE mal_id = ? OR id = ? LIMIT 1');
                $animeStmt->execute([$animeMalId, $animeMalId]);
                $animeId = (int) ($animeStmt->fetchColumn() ?: 0);
                if ($animeId <= 0) {
                    echo json_encode(['success' => false, 'error' => 'No se encontro el anime en la base de datos']);
                    return;
                }
                $stmt = $db->prepare("INSERT INTO comentarios (usuario_id, anime_id, cuerpo, puntuacion, fuente, creado_en) VALUES (?, ?, ?, ?, 'usuario', NOW())");
                $stmt->execute([$userId, $animeId, $message, $rating]);
                echo json_encode(['success' => true, 'id' => (int) $db->lastInsertId()]);
            } catch (Exception $exception) {
                echo json_encode(['success' => false, 'error' => $exception->getMessage()]);
            }
            return;
        }

        if ($action === 'report') {
            $commentId = (int) ($data['comment_id'] ?? 0);
            $reason = trim((string) ($data['reason'] ?? ''));
            $reporterId = (int) ($_SESSION['user_id'] ?? 0);
            if ($reporterId <= 0 || $commentId <= 0 || $reason === '') {
                echo json_encode(['success' => false, 'error' => 'Datos invalidos para reportar']);
                return;
            }
            try {
                $checkStmt = $db->prepare('SELECT id FROM comentarios WHERE id = ?');
                $checkStmt->execute([$commentId]);
                if (!$checkStmt->fetchColumn()) {
                    echo json_encode(['success' => false, 'error' => 'El comentario no existe']);
                    return;
                }
                $dupStmt = $db->prepare("SELECT id FROM reportes_comentarios WHERE comentario_id = ? AND reportado_por = ? AND estado IN ('pendiente', 'en_revision')");
                $dupStmt->execute([$commentId, $reporterId]);
                if ($dupStmt->fetchColumn()) {
                    echo json_encode(['success' => false, 'error' => 'Ya reportaste este comentario']);
                    return;
                }
                $stmt = $db->prepare("INSERT INTO reportes_comentarios (comentario_id, reportado_por, razon, estado, creado_en) VALUES (?, ?, ?, 'pendiente', NOW())");
                $stmt->execute([$commentId, $reporterId, $reason]);
                echo json_encode(['success' => true]);
            } catch (Exception $exception) {
                echo json_encode(['success' => false, 'error' => $exception->getMessage()]);
            }
            return;
        }

        if ($action === 'delete') {
            $commentId = (int) ($data['id'] ?? 0);
            $userId = (int) ($_SESSION['user_id'] ?? 0);
            $role = (string) ($_SESSION['role'] ?? 'Registrado');
            if ($commentId <= 0 || $userId <= 0) {
                echo json_encode(['success' => false, 'error' => 'Datos invalidos']);
                return;
            }
            try {
                $ownerStmt = $db->prepare('SELECT usuario_id FROM comentarios WHERE id = ?');
                $ownerStmt->execute([$commentId]);
                $ownerId = (int) ($ownerStmt->fetchColumn() ?: 0);
                if ($ownerId <= 0) {
                    echo json_encode(['success' => false, 'error' => 'El comentario no existe']);
                    return;
                }
                $isAdmin = $resolveAdminAccess($db, $userId, $role);
                if ($ownerId !== $userId && !$isAdmin) {
                    echo json_encode(['success' => false, 'error' => 'Solo puedes ocultar tus propios comentarios o administrar como admin']);
                    return;
                }
                if (!$isAdmin) {
                    $reportCheckStmt = $db->prepare("SELECT COUNT(*) FROM reportes_comentarios WHERE comentario_id = ? AND estado IN ('pendiente', 'en_revision', 'Revisado')");
                    $reportCheckStmt->execute([$commentId]);
                    $hasReports = (int) ($reportCheckStmt->fetchColumn() ?: 0) > 0;
                    if (!$hasReports) {
                        $deleteStmt = $db->prepare('DELETE FROM comentarios WHERE id = ? AND usuario_id = ?');
                        $deleteStmt->execute([$commentId, $userId]);
                        echo json_encode(['success' => true, 'mode' => 'hard_deleted']);
                        return;
                    }
                }
                $baseStmt = $db->prepare('SELECT id FROM reportes_comentarios WHERE comentario_id = ? ORDER BY id ASC LIMIT 1');
                $baseStmt->execute([$commentId]);
                $baseReportId = (int) ($baseStmt->fetchColumn() ?: 0);
                if ($baseReportId > 0) {
                    $stmt = $db->prepare('UPDATE reportes_comentarios SET estado = ?, revisado_por = ?, revisado_en = NOW() WHERE id = ?');
                    $stmt->execute(['Eliminado', $userId, $baseReportId]);
                } else {
                    $stmt = $db->prepare("INSERT INTO reportes_comentarios (comentario_id, reportado_por, razon, estado, revisado_por, revisado_en, creado_en) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
                    $stmt->execute([$commentId, $userId, 'Comentario eliminado', 'Eliminado', $userId]);
                }
                echo json_encode(['success' => true]);
            } catch (Exception $exception) {
                echo json_encode(['success' => false, 'error' => $exception->getMessage()]);
            }
            return;
        }

        if ($action === 'moderate') {
            $commentId = (int) ($data['id'] ?? 0);
            $decision = trim((string) ($data['decision'] ?? ''));
            $adminId = $resolveSessionUserId($db, (int) ($_SESSION['user_id'] ?? 0), (string) ($_SESSION['username'] ?? ''));
            $role = (string) ($_SESSION['role'] ?? '');
            $isModerator = $resolveAdminAccess($db, $adminId, $role);
            if ($commentId <= 0 || !$isModerator || !in_array($decision, ['review', 'delete'], true)) {
                echo json_encode(['success' => false, 'error' => 'Solo un administrador puede moderar comentarios']);
                return;
            }
            try {
                $checkStmt = $db->prepare('SELECT id FROM comentarios WHERE id = ?');
                $checkStmt->execute([$commentId]);
                if (!$checkStmt->fetchColumn()) {
                    echo json_encode(['success' => false, 'error' => 'El comentario no existe']);
                    return;
                }
                $status = $decision === 'review' ? 'Revisado' : 'Eliminado';
                $reason = $decision === 'review' ? 'Revision administrativa' : 'Comentario eliminado por administrador';
                $baseStmt = $db->prepare('SELECT id FROM reportes_comentarios WHERE comentario_id = ? ORDER BY id ASC LIMIT 1');
                $baseStmt->execute([$commentId]);
                $baseReportId = (int) ($baseStmt->fetchColumn() ?: 0);
                if ($baseReportId > 0) {
                    $stmt = $db->prepare('UPDATE reportes_comentarios SET estado = ?, revisado_por = ?, revisado_en = NOW() WHERE id = ?');
                    $stmt->execute([$status, $adminId, $baseReportId]);
                } else {
                    $stmt = $db->prepare("INSERT INTO reportes_comentarios (comentario_id, reportado_por, razon, estado, revisado_por, revisado_en, creado_en) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
                    $stmt->execute([$commentId, $adminId, $reason, $status, $adminId]);
                }
                echo json_encode(['success' => true, 'status' => $status]);
            } catch (Exception $exception) {
                echo json_encode(['success' => false, 'error' => $exception->getMessage()]);
            }
            return;
        }

        if ($action === 'seed') {
            try {
                $db->exec("INSERT INTO comentarios (usuario_id, anime_id, cuerpo) SELECT u.id, a.id, 'La animacion en esta escena es absolutamente espectacular! MAPPA God.' FROM usuarios u CROSS JOIN anime a LIMIT 1; INSERT INTO comentarios (usuario_id, anime_id, cuerpo) SELECT u.id, a.id, 'El desarrollo del villano es espectacular. 10/10' FROM usuarios u CROSS JOIN anime a ORDER BY u.id DESC, a.id DESC LIMIT 1;");
                echo json_encode(['success' => true]);
            } catch (Exception $exception) {
                echo json_encode(['success' => false, 'error' => $exception->getMessage()]);
            }
            return;
        }

        echo json_encode(['success' => false, 'error' => 'Accion desconocida']);
    }
}
