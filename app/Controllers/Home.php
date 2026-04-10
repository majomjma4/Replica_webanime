<?php

declare(strict_types=1);

namespace App\Controllers;

final class Home extends BaseController
{
    public function index(): void
    {
        $this->render('pages/index');
    }
}
