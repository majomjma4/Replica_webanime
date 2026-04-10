<?php

declare(strict_types=1);

namespace ReplicaCi4\Controllers;

abstract class BaseController
{
    protected function render(string $view, array $data = []): void
    {
        $viewPath = replica_ci4_root_path('app/Views/' . $view . '.php');
        if (!is_file($viewPath)) {
            http_response_code(404);
            echo 'Vista no encontrada';
            return;
        }

        extract($data, EXTR_SKIP);
        require $viewPath;
    }
}
