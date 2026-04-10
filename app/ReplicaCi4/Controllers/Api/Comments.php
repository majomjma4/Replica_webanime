<?php

declare(strict_types=1);

namespace ReplicaCi4\Controllers\Api;

use ReplicaCi4\Controllers\BaseController;
use Throwable;

final class Comments extends BaseController
{
    public function handle()
    {
        $session = session();
        $action = (string) $this->request->getGet('action') ?: 'list';
        $writeActions = ['report', 'delete', 'add', 'moderate', 'seed'];

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

        $data = $this->request->getJSON(true) ?: [];
        $db = \Config\Database::connect();

        $resolveAdminAccess = static function (\CodeIgniter\Database\BaseConnection $dbConn, int $userId, string $sessionRole = ''): bool {
            $normalizedRole = strtolower(trim($sessionRole));
            if (in_array($normalizedRole, ['admin', 'administrador'], true)) {
                return true;
            }
            if ($userId <= 0) return false;
            try {
                $roleResult = $dbConn->table('usuarios u')
                                     ->select('COALESCE(r.nombre, \'\') AS role_name')
                                     ->join('roles r', 'r.id = u.rol_id', 'left')
                                     ->where('u.id', $userId)
                                     ->get()->getRowArray();
                                     
                $dbRole = strtolower(trim((string) ($roleResult['role_name'] ?? '')));
                return in_array($dbRole, ['admin', 'administrador'], true);
            } catch (Throwable) {
                return false;
            }
        };

        if ($action === 'list') {
            try {
                $animeLookupId = (int) $this->request->getGet('anime_mal_id') ?: 0;

                $sql = "SELECT c.id, c.cuerpo AS msg, c.puntuacion AS rating, CASE WHEN rr.report_count > 0 THEN 1 ELSE 0 END AS flagged, c.creado_en, c.autor_externo, u.nombre_mostrar AS user, u.es_premium, a.titulo AS anime, a.mal_id AS anime_mal_id, c.fuente AS source, COALESCE(r.nombre, 'Registrado') AS raw_role, COALESCE(rr.report_count, 0) AS report_count, COALESCE(lr.razon, '') AS report_reason, COALESCE(reporter.nombre_mostrar, '') AS reported_by, COALESCE(dr.estado, '') AS deleted_status, COALESCE(deleter.nombre_mostrar, '') AS deleted_by, COALESCE(mr.estado, '') AS reviewed_status, COALESCE(reviewer.nombre_mostrar, '') AS reviewed_by, mr.revisado_en AS reviewed_at FROM comentarios c INNER JOIN usuarios u ON c.usuario_id = u.id INNER JOIN anime a ON c.anime_id = a.id LEFT JOIN roles r ON r.id = u.rol_id LEFT JOIN (SELECT comentario_id, COUNT(*) AS report_count FROM reportes_comentarios WHERE estado IN ('pendiente', 'en_revision') GROUP BY comentario_id) rr ON rr.comentario_id = c.id LEFT JOIN (SELECT rc1.comentario_id, rc1.razon, rc1.reportado_por FROM reportes_comentarios rc1 INNER JOIN (SELECT comentario_id, MAX(id) AS max_id FROM reportes_comentarios WHERE estado IN ('pendiente', 'en_revision', 'Revisado') GROUP BY comentario_id) latest ON latest.max_id = rc1.id) lr ON lr.comentario_id = c.id LEFT JOIN usuarios reporter ON reporter.id = lr.reportado_por LEFT JOIN (SELECT rc2.comentario_id, rc2.estado, rc2.reportado_por FROM reportes_comentarios rc2 INNER JOIN (SELECT comentario_id, MAX(id) AS max_id FROM reportes_comentarios WHERE estado = 'Eliminado' GROUP BY comentario_id) deleted_latest ON deleted_latest.max_id = rc2.id) dr ON dr.comentario_id = c.id LEFT JOIN usuarios deleter ON deleter.id = dr.reportado_por LEFT JOIN (SELECT rc3.comentario_id, rc3.estado, rc3.revisado_por, rc3.revisado_en FROM reportes_comentarios rc3 INNER JOIN (SELECT comentario_id, MAX(id) AS max_id FROM reportes_comentarios WHERE estado = 'Revisado' GROUP BY comentario_id) reviewed_latest ON reviewed_latest.max_id = rc3.id) mr ON mr.comentario_id = c.id LEFT JOIN usuarios reviewer ON reviewer.id = mr.revisado_por";
                
                $bindings = [];
                if ($animeLookupId > 0) {
                    $sql .= " WHERE (a.mal_id = ? OR a.id = ?) AND NOT EXISTS (SELECT 1 FROM reportes_comentarios hidden_rc WHERE hidden_rc.comentario_id = c.id AND hidden_rc.estado = 'Eliminado')";
                    $bindings = [$animeLookupId, $animeLookupId];
                }
                $sql .= ' ORDER BY c.creado_en DESC';
                
                $query = $db->query($sql, $bindings);
                $rows = $query->getResultArray();
                
                $comments = [];
                foreach ($rows as $row) {
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
                return $this->response->setJSON(['success' => true, 'data' => $comments]);
            } catch (Throwable $exception) {
                return $this->response->setJSON(['success' => false, 'error' => $exception->getMessage()]);
            }
        }

        if ($action === 'add') {
            $userId = (int) ($session->get('user_id') ?? 0);
            $animeMalId = (int) ($data['anime_mal_id'] ?? 0);
            $rating = (int) ($data['rating'] ?? 0);
            $message = trim((string) ($data['message'] ?? ''));
            if ($userId <= 0 || $animeMalId <= 0 || $rating < 1 || $rating > 5 || $message === '') {
                return $this->response->setJSON(['success' => false, 'error' => 'Datos invalidos para comentar']);
            }
            try {
                $anime = $db->table('anime')->select('id')->where('mal_id', $animeMalId)->orWhere('id', $animeMalId)->get()->getRowArray();
                if (!$anime) {
                    return $this->response->setJSON(['success' => false, 'error' => 'No se encontro el anime en la base de datos']);
                }
                $db->table('comentarios')->insert([
                    'usuario_id' => $userId, 
                    'anime_id' => (int) $anime['id'], 
                    'cuerpo' => $message, 
                    'puntuacion' => $rating, 
                    'fuente' => 'usuario', 
                    'creado_en' => date('Y-m-d H:i:s')
                ]);
                return $this->response->setJSON(['success' => true, 'id' => $db->insertID()]);
            } catch (Throwable $exception) {
                return $this->response->setJSON(['success' => false, 'error' => $exception->getMessage()]);
            }
        }

        if ($action === 'report') {
            $commentId = (int) ($data['comment_id'] ?? 0);
            $reason = trim((string) ($data['reason'] ?? ''));
            $reporterId = (int) ($session->get('user_id') ?? 0);
            if ($reporterId <= 0 || $commentId <= 0 || $reason === '') {
                return $this->response->setJSON(['success' => false, 'error' => 'Datos invalidos para reportar']);
            }
            try {
                if ($db->table('comentarios')->where('id', $commentId)->countAllResults() === 0) {
                    return $this->response->setJSON(['success' => false, 'error' => 'El comentario no existe']);
                }
                if ($db->table('reportes_comentarios')->where('comentario_id', $commentId)->where('reportado_por', $reporterId)->whereIn('estado', ['pendiente', 'en_revision'])->countAllResults() > 0) {
                    return $this->response->setJSON(['success' => false, 'error' => 'Ya reportaste este comentario']);
                }

                $db->table('reportes_comentarios')->insert([
                    'comentario_id' => $commentId, 
                    'reportado_por' => $reporterId, 
                    'razon' => $reason, 
                    'estado' => 'pendiente', 
                    'creado_en' => date('Y-m-d H:i:s')
                ]);
                return $this->response->setJSON(['success' => true]);
            } catch (Throwable $exception) {
                return $this->response->setJSON(['success' => false, 'error' => $exception->getMessage()]);
            }
        }

        if ($action === 'delete') {
            $commentId = (int) ($data['id'] ?? 0);
            $userId = (int) ($session->get('user_id') ?? 0);
            $role = (string) ($session->get('role') ?? 'Registrado');
            if ($commentId <= 0 || $userId <= 0) {
                return $this->response->setJSON(['success' => false, 'error' => 'Datos invalidos']);
            }
            try {
                $ownerObj = $db->table('comentarios')->select('usuario_id')->where('id', $commentId)->get()->getRowArray();
                if (!$ownerObj) {
                    return $this->response->setJSON(['success' => false, 'error' => 'El comentario no existe']);
                }
                $ownerId = (int) $ownerObj['usuario_id'];
                $isAdmin = $resolveAdminAccess($db, $userId, $role);
                
                if ($ownerId !== $userId && !$isAdmin) {
                    return $this->response->setJSON(['success' => false, 'error' => 'Solo puedes ocultar tus propios comentarios o administrar como admin']);
                }

                if (!$isAdmin) {
                    $hasReports = $db->table('reportes_comentarios')
                                     ->where('comentario_id', $commentId)
                                     ->whereIn('estado', ['pendiente', 'en_revision', 'Revisado'])
                                     ->countAllResults() > 0;
                                     
                    if (!$hasReports) {
                        $db->table('comentarios')->where('id', $commentId)->where('usuario_id', $userId)->delete();
                        return $this->response->setJSON(['success' => true, 'mode' => 'hard_deleted']);
                    }
                }
                
                $baseReport = $db->table('reportes_comentarios')->select('id')->where('comentario_id', $commentId)->orderBy('id', 'ASC')->get()->getRowArray();
                if ($baseReport) {
                    $db->table('reportes_comentarios')->where('id', $baseReport['id'])->update([
                        'estado' => 'Eliminado', 
                        'revisado_por' => $userId, 
                        'revisado_en' => date('Y-m-d H:i:s')
                    ]);
                } else {
                    $db->table('reportes_comentarios')->insert([
                        'comentario_id' => $commentId, 
                        'reportado_por' => $userId, 
                        'razon' => 'Comentario eliminado', 
                        'estado' => 'Eliminado', 
                        'revisado_por' => $userId, 
                        'revisado_en' => date('Y-m-d H:i:s'), 
                        'creado_en' => date('Y-m-d H:i:s')
                    ]);
                }
                return $this->response->setJSON(['success' => true]);
            } catch (Throwable $exception) {
                return $this->response->setJSON(['success' => false, 'error' => $exception->getMessage()]);
            }
        }

        if ($action === 'moderate') {
            $commentId = (int) ($data['id'] ?? 0);
            $decision = trim((string) ($data['decision'] ?? ''));
            $adminId = (int) ($session->get('user_id') ?? 0);
            $role = (string) ($session->get('role') ?? '');
            
            $isModerator = $resolveAdminAccess($db, $adminId, $role);
            if ($commentId <= 0 || !$isModerator || !in_array($decision, ['review', 'delete'], true)) {
                return $this->response->setJSON(['success' => false, 'error' => 'Solo un administrador puede moderar comentarios']);
            }
            try {
                if ($db->table('comentarios')->where('id', $commentId)->countAllResults() === 0) {
                    return $this->response->setJSON(['success' => false, 'error' => 'El comentario no existe']);
                }
                
                $status = $decision === 'review' ? 'Revisado' : 'Eliminado';
                $reason = $decision === 'review' ? 'Revision administrativa' : 'Comentario eliminado por administrador';
                
                $baseReport = $db->table('reportes_comentarios')->select('id')->where('comentario_id', $commentId)->orderBy('id', 'ASC')->get()->getRowArray();
                if ($baseReport) {
                    $db->table('reportes_comentarios')->where('id', $baseReport['id'])->update([
                        'estado' => $status, 
                        'revisado_por' => $adminId, 
                        'revisado_en' => date('Y-m-d H:i:s')
                    ]);
                } else {
                    $db->table('reportes_comentarios')->insert([
                        'comentario_id' => $commentId, 
                        'reportado_por' => $adminId, 
                        'razon' => $reason, 
                        'estado' => $status, 
                        'revisado_por' => $adminId, 
                        'revisado_en' => date('Y-m-d H:i:s'), 
                        'creado_en' => date('Y-m-d H:i:s')
                    ]);
                }
                return $this->response->setJSON(['success' => true, 'status' => $status]);
            } catch (Throwable $exception) {
                return $this->response->setJSON(['success' => false, 'error' => $exception->getMessage()]);
            }
        }

        if ($action === 'seed') {
            try {
                $db->query("INSERT INTO comentarios (usuario_id, anime_id, cuerpo) SELECT u.id, a.id, 'La animacion en esta escena es absolutamente espectacular! MAPPA God.' FROM usuarios u CROSS JOIN anime a LIMIT 1");
                $db->query("INSERT INTO comentarios (usuario_id, anime_id, cuerpo) SELECT u.id, a.id, 'El desarrollo del villano es espectacular. 10/10' FROM usuarios u CROSS JOIN anime a ORDER BY u.id DESC, a.id DESC LIMIT 1");
                return $this->response->setJSON(['success' => true]);
            } catch (Throwable $exception) {
                return $this->response->setJSON(['success' => false, 'error' => $exception->getMessage()]);
            }
        }

        return $this->response->setJSON(['success' => false, 'error' => 'Accion desconocida']);
    }
}
