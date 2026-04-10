<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use Throwable;

final class Activity extends BaseController
{
    public function handle()
    {
        if ($this->request->getMethod(true) !== 'POST') {
            return $this->response->setStatusCode(405)->setJSON(['success' => false, 'error' => 'Metodo no permitido']);
        }
        
        app_verify_csrf();
        
        $session = session();
        $userId = $session->get('user_id');
        $role = $session->get('role') ?? 'Invitado';
        
        if (!$userId || $role === 'Invitado') {
            return $this->response->setJSON(['success' => true, 'message' => 'Modo invitado: no se registra actividad']);
        }

        $data = $this->request->getJSON(true) ?: [];
        $action = (string) ($data['action'] ?? '');
        if ($action === '') {
            return $this->response->setJSON(['success' => false, 'error' => 'Accion no especificada']);
        }

        $db = \Config\Database::connect();

        try {
            if ($action === 'time_sync') {
                $delta = (float) ($data['delta'] ?? 0);
                if ($delta > 0) {
                    $builder = $db->table('usuarios_meta');
                    $exists = $builder->where(['usuario_id' => $userId, 'meta_key' => 'profile_hours'])->get()->getRowArray();
                    
                    if ($exists) {
                        $newHours = (float) $exists['meta_value'] + $delta;
                        $builder->where(['usuario_id' => $userId, 'meta_key' => 'profile_hours'])
                                ->update(['meta_value' => (string) $newHours]);
                    } else {
                        $builder->insert([
                            'usuario_id' => $userId, 
                            'meta_key' => 'profile_hours', 
                            'meta_value' => (string) $delta
                        ]);
                    }
                }
            } else {
                $details = (string) ($data['details'] ?? '');
                $db->table('usuarios_actividad')->insert([
                    'usuario_id' => $userId,
                    'accion' => $action,
                    'detalles' => $details
                ]);
            }

            return $this->response->setJSON(['success' => true]);
        } catch (Throwable $exception) {
            return $this->response->setJSON(['success' => false, 'error' => $exception->getMessage()]);
        }
    }
}
