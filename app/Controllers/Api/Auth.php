<?php

declare(strict_types=1);

namespace ReplicaCi4\Controllers\Api;

use ReplicaCi4\Models\Database;
use PDO;
use Throwable;

final class Auth
{
    public function handle(): void
    {
        header('Content-Type: application/json; charset=UTF-8');
        app_start_session();

        $action = (string) ($_GET['action'] ?? 'check');
        $data = app_get_json_input();

        if ($action === 'check') {
            app_require_method('GET');
        } else {
            app_require_method('POST');
            app_verify_csrf();
        }

        $dbConn = (new Database())->getConnection();
        if (!$dbConn) {
            return;
        }

        if ($action === 'check') {
            if (isset($_SESSION['user_id'])) {
                $stmt = $dbConn->prepare('SELECT codigo_publico, es_premium, premium_vence_en FROM usuarios WHERE id = ?');
                $stmt->execute([$_SESSION['user_id']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
                $role = (string) ($_SESSION['role'] ?? 'Registrado');
                $isPremium = $role === 'Admin' || ((int) ($user['es_premium'] ?? 0) === 1 && (empty($user['premium_vence_en']) || strtotime((string) $user['premium_vence_en']) > time()));
                $_SESSION['premium'] = $isPremium;

                echo json_encode([
                    'logged' => true,
                    'username' => (string) ($_SESSION['username'] ?? 'Usuario'),
                    'userId' => (string) ($_SESSION['user_id'] ?? ''),
                    'publicUserId' => $user['codigo_publico'] ?? null,
                    'role' => $role,
                    'isAdmin' => $role === 'Admin',
                    'isPremium' => $isPremium,
                ]);
                return;
            }

            echo json_encode(['logged' => false, 'role' => 'Invitado', 'isPremium' => false]);
            return;
        }

        if ($action === 'logout') {
            if (isset($_SESSION['session_log_id'])) {
                try {
                    $dbConn->prepare('UPDATE usuarios_sesiones SET fin = NOW(), duracion = TIMESTAMPDIFF(SECOND, inicio, NOW()) WHERE id = ?')->execute([$_SESSION['session_log_id']]);
                } catch (Throwable) {
                }
            }
            session_unset();
            session_destroy();
            setcookie(session_name(), '', time() - 3600, '/');
            setcookie('XSRF-TOKEN', '', time() - 3600, '/');
            echo json_encode(['success' => true]);
            return;
        }

        if ($action === 'register') {
            $username = trim((string) ($data['username'] ?? ''));
            $email = trim((string) ($data['email'] ?? ''));
            $password = (string) ($data['password'] ?? '');
            if ($username === '' || $email === '' || $password === '') {
                echo json_encode(['success' => false, 'error' => 'Faltan campos obligatorios']);
                return;
            }

            $stmt = $dbConn->prepare('SELECT id FROM usuarios WHERE correo = ? OR nombre_mostrar = ?');
            $stmt->execute([$email, $username]);
            if ($stmt->fetch()) {
                echo json_encode(['success' => false, 'error' => 'El usuario o correo ya esta registrado']);
                return;
            }

            $publicCode = $this->generatePublicUserCode($dbConn);
            $hash = password_hash($password, PASSWORD_DEFAULT);

            try {
                $dbConn->beginTransaction();
                $stmt = $dbConn->prepare('INSERT INTO usuarios (codigo_publico, correo, hash_password, nombre_mostrar, rol_id, activo, creado_en) VALUES (?, ?, ?, ?, 2, 1, NOW())');
                $stmt->execute([$publicCode, $email, $hash, $username]);
                $userId = (int) $dbConn->lastInsertId();
                $dbConn->prepare('INSERT INTO usuarios_sesiones (usuario_id, inicio) VALUES (?, NOW())')->execute([$userId]);
                $sessionLogId = (int) $dbConn->lastInsertId();
                $dbConn->commit();

                session_regenerate_id(true);
                $_SESSION['user_id'] = $userId;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = 'Registrado';
                $_SESSION['premium'] = false;
                $_SESSION['session_log_id'] = $sessionLogId;

                echo json_encode(['success' => true, 'username' => $username, 'role' => 'Registrado', 'userId' => $userId, 'publicUserId' => $publicCode]);
            } catch (Throwable $exception) {
                if ($dbConn->inTransaction()) {
                    $dbConn->rollBack();
                }
                echo json_encode(['success' => false, 'error' => 'Error al registrar el usuario: ' . $exception->getMessage()]);
            }
            return;
        }

        if ($action === 'login') {
            $userOrEmail = trim((string) ($data['userOrEmail'] ?? ''));
            $password = (string) ($data['password'] ?? '');
            if ($userOrEmail === '' || $password === '') {
                echo json_encode(['success' => false, 'error' => 'Faltan campos obligatorios']);
                return;
            }

            $stmt = $dbConn->prepare('SELECT u.*, r.nombre AS role_name FROM usuarios u LEFT JOIN roles r ON r.id = u.rol_id WHERE u.correo = ? OR u.nombre_mostrar = ? LIMIT 1');
            $stmt->execute([$userOrEmail, $userOrEmail]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                echo json_encode(['success' => false, 'error' => 'Usuario no encontrado.']);
                return;
            }
            if (!password_verify($password, (string) ($user['hash_password'] ?? ''))) {
                echo json_encode(['success' => false, 'error' => 'La contraseña es incorrecta.']);
                return;
            }
            if (((int) ($user['bloqueado'] ?? 0) === 1) || ((int) ($user['activo'] ?? 1) !== 1)) {
                echo json_encode(['success' => false, 'blocked' => true, 'error' => 'Tu cuenta esta bloqueada o inactiva.']);
                return;
            }

            $role = (string) ($user['role_name'] ?? 'Registrado');
            $isPremium = $role === 'Admin' || ((int) ($user['es_premium'] ?? 0) === 1 && (empty($user['premium_vence_en']) || strtotime((string) $user['premium_vence_en']) > time()));
            $dbConn->prepare('INSERT INTO usuarios_sesiones (usuario_id, inicio) VALUES (?, NOW())')->execute([(int) $user['id']]);
            $sessionLogId = (int) $dbConn->lastInsertId();

            session_regenerate_id(true);
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['username'] = (string) $user['nombre_mostrar'];
            $_SESSION['role'] = $role;
            $_SESSION['premium'] = $isPremium;
            $_SESSION['session_log_id'] = $sessionLogId;

            echo json_encode([
                'success' => true,
                'username' => (string) $user['nombre_mostrar'],
                'userId' => (int) $user['id'],
                'publicUserId' => $user['codigo_publico'] ?? null,
                'role' => $role,
                'isAdmin' => $role === 'Admin',
                'isPremium' => $isPremium,
            ]);
            return;
        }

        if ($action === 'forgot_password') {
            echo json_encode(['success' => true]);
            return;
        }

        echo json_encode(['success' => false, 'error' => 'Accion desconocida']);
    }

    private function generatePublicUserCode(PDO $dbConn): string
    {
        do {
            $code = 'NK-' . random_int(100000, 999999);
            $stmt = $dbConn->prepare('SELECT COUNT(*) FROM usuarios WHERE codigo_publico = ?');
            $stmt->execute([$code]);
        } while ((int) $stmt->fetchColumn() > 0);

        return $code;
    }
}
