<?php
declare(strict_types=1);
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use Exception;
use PDO;

final class Requests extends BaseController
{
    public function handle(): \CodeIgniter\HTTP\ResponseInterface
    {
        $session = session();
        $action = (string) ($this->request->getGet('action') ?? 'list');
        $data = $this->request->getJSON(true) ?? [];

        $writeActions = ['moderate', 'process_quick', 'mark_read', 'decide', 'delete', 'bulk_approve'];
        if (in_array($action, $writeActions, true)) {
            if (strtoupper($this->request->getMethod()) !== 'POST') {
                return $this->response->setStatusCode(405)->setJSON(['success' => false, 'error' => 'Metodo no permitido']);
            }
            app_verify_csrf();
        } else {
            if (strtoupper($this->request->getMethod()) !== 'GET') {
                return $this->response->setStatusCode(405)->setJSON(['success' => false, 'error' => 'Metodo no permitido']);
            }
        }

        $isAdmin = $session->has('user_id') && $session->get('role') === 'Admin';
        if (!$isAdmin) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'error' => 'Acceso denegado']);
        }

        try {
            $dbConn = new \PDO("mysql:host=" . config('Database')->default['hostname'] . ";port=" . config('Database')->default['port'] . ";dbname=" . config('Database')->default['database'] . ";charset=" . config('Database')->default['charset'], config('Database')->default['username'], config('Database')->default['password'], [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
        } catch (\PDOException $e) {
            return $this->response->setJSON(['success' => false, 'error' => 'Error de conexiÃ³n a la base de datos']);
        }

        if ($action === 'list') {
            $status = $this->request->getGet('status') ?? 'pendiente';
            $page = max(1, (int) ($this->request->getGet('page') ?? 1));
            $size = max(1, min(50, (int) ($this->request->getGet('size') ?? 20)));
            $offset = ($page - 1) * $size;

            $where = 'r.estado = ?';
            $params = [$status];

            try {
                $countStmt = $dbConn->prepare("SELECT COUNT(*) AS total FROM solicitudes_anime r WHERE $where");
                $countStmt->execute($params);
                $total = (int) (($countStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0));

                $listStmt = $dbConn->prepare("SELECT r.id, r.titulo, r.tipo, r.fuente, r.estado, r.creado_en, COALESCE(u.nombre_mostrar, r.user_name, 'Usuario') AS user_display FROM solicitudes_anime r LEFT JOIN usuarios u ON u.id = r.user_id WHERE $where ORDER BY r.creado_en DESC LIMIT $size OFFSET $offset");
                $listStmt->execute($params);
                $items = $listStmt->fetchAll(PDO::FETCH_ASSOC);

                return $this->response->setJSON(['success' => true, 'total' => $total, 'page' => $page, 'size' => $size, 'items' => $items]);
            } catch (Exception $e) {
                return $this->response->setJSON(['success' => false, 'error' => 'Error de BD: ' . $e->getMessage()]);
            }
        }

        if ($action === 'decide') {
            $id = (int) ($data['id'] ?? 0);
            $decision = (string) ($data['decision'] ?? '');
            if (!$id || !in_array($decision, ['aprobado', 'rechazado'], true)) {
                return $this->response->setJSON(['success' => false, 'error' => 'Datos invalidos']);
            }
            $adminId = $session->get('user_id');
            try {
                $stmt = $dbConn->prepare("UPDATE solicitudes_anime SET estado = ?, resuelto_en = NOW(), resuelto_por = ? WHERE id = ?");
                $stmt->execute([$decision, $adminId, $id]);
                return $this->response->setJSON(['success' => true]);
            } catch (Exception $e) {
                return $this->response->setJSON(['success' => false, 'error' => 'Error de BD: ' . $e->getMessage()]);
            }
        }

        if ($action === 'delete') {
            $id = (int) ($data['id'] ?? 0);
            if (!$id) {
                return $this->response->setJSON(['success' => false, 'error' => 'ID invalido']);
            }
            try {
                $stmt = $dbConn->prepare("DELETE FROM solicitudes_anime WHERE id = ?");
                $stmt->execute([$id]);
                return $this->response->setJSON(['success' => true]);
            } catch (Exception $e) {
                return $this->response->setJSON(['success' => false, 'error' => 'Error de BD: ' . $e->getMessage()]);
            }
        }

        if ($action === 'bulk_approve') {
            $adminId = $session->get('user_id');
            try {
                $stmt = $dbConn->prepare("UPDATE solicitudes_anime SET estado = 'aprobado', resuelto_en = NOW(), resuelto_por = ? WHERE estado = 'pendiente'");
                $stmt->execute([$adminId]);
                return $this->response->setJSON(['success' => true]);
            } catch (Exception $e) {
                return $this->response->setJSON(['success' => false, 'error' => 'Error de BD: ' . $e->getMessage()]);
            }
        }

        return $this->response->setJSON(['success' => false, 'error' => 'Accion no soportada']);
    }
}
