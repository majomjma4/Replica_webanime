<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use Throwable;

final class Auth extends BaseController
{
    public function handle()
    {
        $action = (string) $this->request->getGet('action') ?: 'check';
        
        if ($action === 'check') {
            if ($this->request->getMethod(true) !== 'GET') {
                return $this->response->setStatusCode(405)->setJSON(['success' => false, 'error' => 'Metodo no permitido']);
            }
        } else {
            if ($this->request->getMethod(true) !== 'POST') {
                return $this->response->setStatusCode(405)->setJSON(['success' => false, 'error' => 'Metodo no permitido']);
            }
            // CodeIgniter CSRF is automated if enabled in Filters, but we'll enforce our manual session one just in case
            app_verify_csrf();
        }

        $db = \Config\Database::connect();
        $data = $this->request->getJSON(true) ?? [];
        $session = session();

        if ($action === 'check') {
            if ($session->has('user_id')) {
                $user = $db->table('usuarios')->select('codigo_publico, es_premium, premium_vence_en')->where('id', $session->get('user_id'))->get()->getRowArray() ?: [];
                $role = (string) ($session->get('role') ?? 'Registrado');
                $isPremium = $role === 'Admin' || ((int) ($user['es_premium'] ?? 0) === 1 && (empty($user['premium_vence_en']) || strtotime((string) $user['premium_vence_en']) > time()));
                
                $session->set('premium', $isPremium);

                return $this->response->setJSON([
                    'logged' => true,
                    'username' => (string) ($session->get('username') ?? 'Usuario'),
                    'userId' => (string) ($session->get('user_id') ?? ''),
                    'publicUserId' => $user['codigo_publico'] ?? null,
                    'role' => $role,
                    'isAdmin' => $role === 'Admin',
                    'isPremium' => $isPremium,
                ]);
            }
            return $this->response->setJSON(['logged' => false, 'role' => 'Invitado', 'isPremium' => false]);
        }

        if ($action === 'logout') {
            if ($session->has('session_log_id')) {
                try {
                    $db->query('UPDATE usuarios_sesiones SET fin = NOW(), duracion = TIMESTAMPDIFF(SECOND, inicio, NOW()) WHERE id = ?', [$session->get('session_log_id')]);
                } catch (Throwable) {
                }
            }
            $session->destroy();
            setcookie(session_name(), '', time() - 3600, '/');
            return $this->response->setJSON(['success' => true]);
        }

        if ($action === 'register') {
            $username = trim((string) ($data['username'] ?? ''));
            $email = trim((string) ($data['email'] ?? ''));
            $password = (string) ($data['password'] ?? '');

            if ($username === '' || $email === '' || $password === '') {
                return $this->response->setJSON(['success' => false, 'error' => 'Faltan campos obligatorios']);
            }

            $exists = $db->table('usuarios')->where('correo', $email)->orWhere('nombre_mostrar', $username)->get()->getRow();
            if ($exists) {
                return $this->response->setJSON(['success' => false, 'error' => 'El usuario o correo ya esta registrado']);
            }

            $publicCode = $this->generatePublicUserCode($db);
            $hash = password_hash($password, PASSWORD_DEFAULT);

            try {
                $db->transStart();
                $db->table('usuarios')->insert([
                    'codigo_publico' => $publicCode,
                    'correo' => $email,
                    'hash_password' => $hash,
                    'nombre_mostrar' => $username,
                    'rol_id' => 2,
                    'activo' => 1,
                    'creado_en' => date('Y-m-d H:i:s')
                ]);
                $userId = $db->insertID();

                $db->table('usuarios_sesiones')->insert([
                    'usuario_id' => $userId,
                    'inicio' => date('Y-m-d H:i:s')
                ]);
                $sessionLogId = $db->insertID();
                $db->transComplete();

                $session->regenerate();
                $session->set([
                    'user_id' => $userId,
                    'username' => $username,
                    'role' => 'Registrado',
                    'premium' => false,
                    'session_log_id' => $sessionLogId
                ]);

                return $this->response->setJSON(['success' => true, 'username' => $username, 'role' => 'Registrado', 'userId' => $userId, 'publicUserId' => $publicCode]);
            } catch (Throwable $e) {
                return $this->response->setJSON(['success' => false, 'error' => 'Error al registrar: ' . $e->getMessage()]);
            }
        }

        if ($action === 'login') {
            $userOrEmail = trim((string) ($data['userOrEmail'] ?? ''));
            $password = (string) ($data['password'] ?? '');

            if ($userOrEmail === '' || $password === '') {
                return $this->response->setJSON(['success' => false, 'error' => 'Faltan campos obligatorios']);
            }

            $user = $db->table('usuarios u')
                       ->select('u.*, r.nombre AS role_name')
                       ->join('roles r', 'r.id = u.rol_id', 'left')
                       ->where('u.correo', $userOrEmail)
                       ->orWhere('u.nombre_mostrar', $userOrEmail)
                       ->get()->getRowArray();

            if (!$user) {
                return $this->response->setJSON(['success' => false, 'error' => 'Usuario no encontrado.']);
            }
            if (!password_verify($password, (string) ($user['hash_password'] ?? ''))) {
                return $this->response->setJSON(['success' => false, 'error' => 'La contraseÃ±a es incorrecta.']);
            }
            if (((int) ($user['bloqueado'] ?? 0) === 1) || ((int) ($user['activo'] ?? 1) !== 1)) {
                return $this->response->setJSON(['success' => false, 'blocked' => true, 'error' => 'Tu cuenta esta bloqueada o inactiva.']);
            }

            $role = (string) ($user['role_name'] ?? 'Registrado');
            $isPremium = $role === 'Admin' || ((int) ($user['es_premium'] ?? 0) === 1 && (empty($user['premium_vence_en']) || strtotime((string) $user['premium_vence_en']) > time()));
            
            $db->table('usuarios_sesiones')->insert([
                'usuario_id' => (int) $user['id'],
                'inicio' => date('Y-m-d H:i:s')
            ]);
            $sessionLogId = $db->insertID();

            $session->regenerate();
            $session->set([
                'user_id' => (int) $user['id'],
                'username' => (string) $user['nombre_mostrar'],
                'role' => $role,
                'premium' => $isPremium,
                'session_log_id' => $sessionLogId
            ]);

            return $this->response->setJSON([
                'success' => true,
                'username' => (string) $user['nombre_mostrar'],
                'userId' => (int) $user['id'],
                'publicUserId' => $user['codigo_publico'] ?? null,
                'role' => $role,
                'isAdmin' => $role === 'Admin',
                'isPremium' => $isPremium,
            ]);
        }

        if ($action === 'buy_premium') {
            if (!$session->has('user_id')) {
                return $this->response->setJSON(['success' => false, 'error' => 'Debes iniciar sesion']);
            }

            try {
                $db->query('UPDATE usuarios SET es_premium = 1, premium_vence_en = DATE_ADD(NOW(), INTERVAL 30 DAY) WHERE id = ?', [(int) $session->get('user_id')]);
                $session->set('premium', true);
                return $this->response->setJSON(['success' => true, 'isPremium' => true]);
            } catch (Throwable $e) {
                return $this->response->setJSON(['success' => false, 'error' => 'No se pudo activar premium']);
            }
        }

        if ($action === 'forgot_password') {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false, 'error' => 'Accion desconocida']);
    }

    private function generatePublicUserCode(\CodeIgniter\Database\BaseConnection $db): string
    {
        do {
            $code = 'NK-' . random_int(100000, 999999);
            $count = $db->table('usuarios')->where('codigo_publico', $code)->countAllResults();
        } while ($count > 0);

        return $code;
    }
}
