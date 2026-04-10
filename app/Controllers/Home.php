<?php

declare(strict_types=1);

namespace ReplicaCi4\Controllers;

final class Home extends BaseController
{
    public function index(): void
    {
        $this->render('pages/index');
    }
}
