<?php

declare(strict_types=1);

namespace ReplicaCi4\Controllers;

final class Partials extends BaseController
{
    public function layout(): void
    {
        $this->render('partials/layout');
    }

    public function adminLayout(): void
    {
        $this->render('partials/admin-layout');
    }
}