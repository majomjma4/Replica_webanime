<?php

declare(strict_types=1);

namespace App\Controllers;

class UserController extends BaseController
{
    private function pageMap(string $slug): array
    {
        $map = [
            'registro' => [
                'title' => 'NekoraList - Registro',
                'eyebrow' => 'Cuenta',
                'heading' => 'Crea tu cuenta',
                'description' => 'La replica ya tiene backend propio para autenticacion.',
                'cards' => [],
            ],
            'ingresar' => [
                'title' => 'NekoraList - Ingresar',
                'eyebrow' => 'Cuenta',
                'heading' => 'Inicia sesion',
                'description' => 'Usa el menu superior para autenticarte.',
                'cards' => [],
            ],
            'user' => [
                'title' => 'NekoraList - Usuario',
                'eyebrow' => 'Perfil',
                'heading' => 'Tu espacio Nekora',
                'description' => 'Perfil, favoritos y listas.',
                'cards' => [],
            ],
            'pago' => [
                'title' => 'NekoraList - Pago Seguro',
                'eyebrow' => 'Premium',
                'heading' => 'Pago seguro',
                'description' => 'Activa premium y desbloquea comentarios y listas avanzadas.',
                'cards' => [],
            ],
        ];

        return $map[$slug] ?? [
            'title' => ucfirst($slug),
            'eyebrow' => '',
            'heading' => ucfirst($slug),
            'description' => '',
            'cards' => []
        ];
    }

    public function ingresar()
    {
        app_publish_csrf_cookie();
        $slug = 'ingresar';
        return $this->render('pages/' . $slug, [
            'slug' => $slug,
            'page' => $this->pageMap($slug),
        ]);
    }

    public function registro()
    {
        app_publish_csrf_cookie();
        $slug = 'registro';
        return $this->render('pages/' . $slug, [
            'slug' => $slug,
            'page' => $this->pageMap($slug),
        ]);
    }

    public function perfil()
    {
        app_publish_csrf_cookie();
        $slug = 'user';
        return $this->render('pages/' . $slug, [
            'slug' => $slug,
            'page' => $this->pageMap($slug),
        ]);
    }

    public function pago()
    {
        app_publish_csrf_cookie();
        $slug = 'pago';
        return $this->render('pages/' . $slug, [
            'slug' => $slug,
            'page' => $this->pageMap($slug),
        ]);
    }
}
