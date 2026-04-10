<?php

declare(strict_types=1);

namespace ReplicaCi4\Controllers;

abstract class BaseController extends \App\Controllers\BaseController
{
    protected function render(string $view, array $data = []): void
    {
        $candidates = [
            replica_ci4_root_path('app/Views/' . $view . '.php'),
            replica_ci4_root_path('app/Views/Views/' . $view . '.php'),
        ];

        $viewPath = null;
        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                $viewPath = $candidate;
                break;
            }
        }

        if ($viewPath === null) {
            http_response_code(404);
            echo 'Vista no encontrada';
            return;
        }

        extract($data, EXTR_SKIP);
        require $viewPath;
    }
}
