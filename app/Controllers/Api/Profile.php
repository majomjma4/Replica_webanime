<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use Throwable;

final class Profile extends BaseController
{
    public function handle()
    {
        $session = session();
        if (!$session->has('user_id')) {
            return $this->response->setJSON(['success' => false, 'error' => 'No session']);
        }

        $userId = $session->get('user_id');
        $db = \Config\Database::connect();
        $action = (string) $this->request->getGet('action') ?: 'get';

        if ($action === 'get') {
            if ($this->request->getMethod(true) !== 'GET') {
                return $this->response->setStatusCode(405)->setJSON(['success' => false, 'error' => 'Metodo no permitido']);
            }

            $userRow = $db->table('usuarios')->select('codigo_publico')->where('id', $userId)->get()->getRowArray() ?: [];
            
            $metaData = $db->table('usuarios_meta')->where('usuario_id', $userId)->get()->getResultArray();
            $meta = [];
            foreach ($metaData as $row) {
                $meta[$row['meta_key']] = $row['meta_value'];
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'anidex_profile_name' => $meta['profile_name'] ?? ($session->get('username') ?? ''),
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
        }

        if ($action === 'save') {
             if ($this->request->getMethod(true) !== 'POST') {
                return $this->response->setStatusCode(405)->setJSON(['success' => false, 'error' => 'Metodo no permitido']);
            }
            app_verify_csrf();

            $data = $this->request->getJSON(true);
            if (!$data) {
                return $this->response->setJSON(['success' => false, 'error' => 'No data provided']);
            }

            try {
                $db->transStart();
                $builder = $db->table('usuarios_meta');
                foreach ($data as $key => $value) {
                    $insertData = [
                        'usuario_id' => $userId,
                        'meta_key' => $key,
                        'meta_value' => is_array($value) ? json_encode($value) : (string) $value
                    ];
                    // CI4 does not have an elegant ON DUPLICATE KEY UPDATE in QB safely without custom queries or insertBatch.
                    // We can just rely on standard save/replace logic or query bindings.
                    $exists = $builder->where(['usuario_id' => $userId, 'meta_key' => $key])->countAllResults(false);
                    if ($exists > 0) {
                        $builder->update(['meta_value' => $insertData['meta_value']]);
                    } else {
                        $builder->insert($insertData);
                    }
                }
                $db->transComplete();

                if ($db->transStatus() === false) {
                     return $this->response->setStatusCode(500)->setJSON(['success' => false, 'error' => 'Transaction failed']);
                }

                return $this->response->setJSON(['success' => true]);
            } catch (Throwable $exception) {
                return $this->response->setJSON(['success' => false, 'error' => $exception->getMessage()]);
            }
        }

        return $this->response->setJSON(['success' => false, 'error' => 'Accion desconocida']);
    }
}
