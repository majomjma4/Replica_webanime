<?php
declare(strict_types=1);
namespace ReplicaCi4\Controllers\Api;

use ReplicaCi4\Controllers\BaseController;
use Exception;
use PDO;

final class Admin extends BaseController
{
    public function handle(): \CodeIgniter\HTTP\ResponseInterface
    {
        $session = session();
        $action = (string) ($this->request->getGet('action') ?? '');
        $writeActions = ['add_anime', 'update_studio', 'update_anime', 'delete_anime'];
        if (in_array($action, $writeActions, true)) {
            if ($this->request->getMethod(true) !== 'POST') {
                return $this->response->setStatusCode(405)->setJSON(['success' => false, 'error' => 'Metodo no permitido']);
            }
            app_verify_csrf();
        } else {
            if ($this->request->getMethod(true) !== 'GET' && $this->request->getMethod(true) !== 'POST') {
                return $this->response->setStatusCode(405)->setJSON(['success' => false, 'error' => 'Metodo no permitido']);
            }
        }

        $isAdmin = $session->has('user_id') && $session->get('role') === 'Admin';
        if (!$isAdmin) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'error' => 'Acceso denegado']);
        }

        $data = $this->request->getJSON(true) ?? [];
        if ($action === '' || (!in_array($action, ['list', 'add_anime', 'update_studio', 'update_anime', 'delete_anime'], true) && empty($data))) {
            return $this->response->setJSON(['success' => false, 'error' => 'Peticion invalida']);
        }

        try {
            $dbConfig = config('Database');
            $dsn = "mysql:host={$dbConfig->default['hostname']};port={$dbConfig->default['port']};dbname={$dbConfig->default['database']};charset={$dbConfig->default['charset']}";
            $dbConn = new PDO($dsn, $dbConfig->default['username'], $dbConfig->default['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (\PDOException $e) {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'error' => 'Error de conexión a la base de datos']);
        }

        if ($action === 'add_anime') {
            $titulo = trim((string) ($data['titulo'] ?? ''));
            $sinopsis = trim((string) ($data['sinopsis'] ?? ''));
            $estadoRaw = trim((string) ($data['estado'] ?? ''));
            $estadoLower = strtolower($estadoRaw);
            if ($estadoLower === 'finished airing') {
                $estado = 'Finalizado';
            } elseif ($estadoLower === 'currently airing' || $estadoLower === 'en emision') {
                $estado = 'En emision';
            } elseif ($estadoLower === 'not yet aired') {
                $estado = 'Proximamente';
            } else {
                $estado = $estadoRaw;
            }
            $tipoContenido = strtolower(trim((string) ($data['tipo_contenido'] ?? 'anime')));
            $tipoFormato = strtoupper(trim((string) ($data['tipo_formato'] ?? 'ALL')));
            if ($tipoContenido === 'pelicula') {
                $tipo = 'MOVIE';
            } elseif (in_array($tipoFormato, ['TV', 'OVA', 'ONA', 'SPECIAL', 'SHORT'], true)) {
                $tipo = $tipoFormato;
            } else {
                $tipo = 'TV';
            }
            $estudio = trim((string) ($data['estudio'] ?? ''));
            $temporada = trim((string) ($data['temporada'] ?? ''));
            $anio = (int) ($data['anio'] ?? 0);
            $episodios = (int) ($data['episodios'] ?? 0);
            $imagen_url = trim((string) ($data['imagen_url'] ?? ''));
            $generos = is_array($data['generos'] ?? null) ? $data['generos'] : [];

            if ($titulo === '') {
                return $this->response->setJSON(['success' => false, 'error' => 'El titulo es obligatorio']);
            }

            try {
                $dbConn->beginTransaction();
                $stmt = $dbConn->prepare("INSERT INTO anime (titulo, tipo, estudio, estado, episodios, temporada, anio, sinopsis, imagen_url, puntuacion, activo, creado_por, creado_en) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0.0, 1, 1, NOW())");
                $stmt->execute([$titulo, $tipo, $estudio, $estado, $episodios, $temporada, $anio, $sinopsis, $imagen_url]);
                $anime_id = $dbConn->lastInsertId();

                foreach ($generos as $g_name) {
                    $gStmt = $dbConn->prepare("SELECT id FROM generos WHERE nombre = ?");
                    $gStmt->execute([$g_name]);
                    $g_row = $gStmt->fetch(PDO::FETCH_ASSOC);
                    $g_id = $g_row ? $g_row['id'] : null;
                    if (!$g_id) {
                        $iStmt = $dbConn->prepare("INSERT INTO generos (nombre) VALUES (?)");
                        $iStmt->execute([$g_name]);
                        $g_id = $dbConn->lastInsertId();
                    }
                    $lStmt = $dbConn->prepare("INSERT INTO anime_generos (anime_id, genero_id) VALUES (?, ?)");
                    $lStmt->execute([$anime_id, $g_id]);
                }

                $dbConn->commit();
                return $this->response->setJSON(['success' => true]);
            } catch (Exception $e) {
                if ($dbConn->inTransaction()) {
                    $dbConn->rollBack();
                }
                return $this->response->setJSON(['success' => false, 'error' => 'Error de BD: ' . $e->getMessage()]);
            }
        }

        if ($action === 'update_studio') {
            $animeId = (int) ($data['id'] ?? 0);
            $estudio = trim((string) ($data['estudio'] ?? ''));
            if ($animeId <= 0) {
                return $this->response->setJSON(['success' => false, 'error' => 'ID invalido']);
            }
            try {
                $stmt = $dbConn->prepare("UPDATE anime SET estudio = ? WHERE id = ?");
                $stmt->execute([$estudio, $animeId]);
                return $this->response->setJSON(['success' => true]);
            } catch (Exception $e) {
                return $this->response->setJSON(['success' => false, 'error' => 'Error de BD: ' . $e->getMessage()]);
            }
        }

        if ($action === 'update_anime') {
            $animeId = (int) ($data['id'] ?? 0);
            $titulo = trim((string) ($data['titulo'] ?? ''));
            $tipo = trim((string) ($data['tipo'] ?? ''));
            $estudio = trim((string) ($data['estudio'] ?? ''));
            $anio = trim((string) ($data['anio'] ?? ''));
            $estadoRaw = trim((string) ($data['estado'] ?? ''));
            $estadoLower = strtolower($estadoRaw);
            if ($estadoLower === 'finished airing') {
                $estado = 'Finalizado';
            } elseif ($estadoLower === 'currently airing' || $estadoLower === 'en emision') {
                $estado = 'En emision';
            } elseif ($estadoLower === 'not yet aired') {
                $estado = 'Proximamente';
            } else {
                $estado = $estadoRaw;
            }
            $imagen_url = trim((string) ($data['imagen_url'] ?? ''));
            $sinopsis = trim((string) ($data['sinopsis'] ?? ''));
            $temporada = trim((string) ($data['temporada'] ?? ''));
            $episodios = trim((string) ($data['episodios'] ?? ''));

            if ($animeId <= 0 || $titulo === '') {
                return $this->response->setJSON(['success' => false, 'error' => 'Datos invalidos']);
            }

            try {
                $stmt = $dbConn->prepare("UPDATE anime SET titulo = ?, tipo = ?, estudio = ?, anio = ?, estado = ?, imagen_url = ?, sinopsis = ?, temporada = ?, episodios = ? WHERE id = ?");
                $stmt->execute([$titulo, $tipo, $estudio, $anio !== '' ? (int) $anio : null, $estado, $imagen_url, $sinopsis, $temporada, $episodios !== '' ? (int) $episodios : null, $animeId]);
                return $this->response->setJSON(['success' => true]);
            } catch (Exception $e) {
                return $this->response->setJSON(['success' => false, 'error' => 'Error de BD: ' . $e->getMessage()]);
            }
        }

        if ($action === 'delete_anime') {
            $animeId = (int) ($data['id'] ?? 0);
            if ($animeId <= 0) {
                return $this->response->setJSON(['success' => false, 'error' => 'ID invalido']);
            }
            try {
                $dbConn->beginTransaction();
                $dbConn->prepare("DELETE FROM anime_generos WHERE anime_id = ?")->execute([$animeId]);
                $dbConn->prepare("DELETE FROM anime_characters WHERE anime_id = ?")->execute([$animeId]);
                $dbConn->prepare("DELETE FROM anime_pictures WHERE anime_id = ?")->execute([$animeId]);
                $dbConn->prepare("DELETE FROM anime_videos WHERE anime_id = ?")->execute([$animeId]);
                $dbConn->prepare("DELETE FROM anime WHERE id = ?")->execute([$animeId]);
                $dbConn->commit();
                return $this->response->setJSON(['success' => true]);
            } catch (Exception $e) {
                if ($dbConn->inTransaction()) {
                    $dbConn->rollBack();
                }
                return $this->response->setJSON(['success' => false, 'error' => 'Error de BD: ' . $e->getMessage()]);
            }
        }

        return $this->response->setJSON(['success' => false, 'error' => 'Accion desconocida']);
    }
}
