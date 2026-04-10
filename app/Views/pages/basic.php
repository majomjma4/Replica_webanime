<?php

declare(strict_types=1);

$pageTitle = (string) ($page['title'] ?? 'NekoraList');
$eyebrow = (string) ($page['eyebrow'] ?? 'Replica');
$heading = (string) ($page['heading'] ?? 'Pagina');
$description = (string) ($page['description'] ?? '');
$cards = is_array($page['cards'] ?? null) ? $page['cards'] : [];
?>
<!DOCTYPE html>
<html class="dark" lang="es">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?= e($pageTitle) ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script>
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              background: "#0e0e0e",
              surface: "#18181b",
              primary: "#cdbdff",
              accent: "#38bdf8"
            },
            fontFamily: {
              headline: ["Manrope"],
              body: ["Inter"]
            }
          }
        }
      };
    </script>
    <style>
      body { background: radial-gradient(circle at top, rgba(56, 189, 248, 0.12), transparent 28%), radial-gradient(circle at right top, rgba(168, 85, 247, 0.12), transparent 24%), #0e0e0e; }
      .glass { background: rgba(18, 18, 24, 0.78); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="min-h-screen text-white font-body">
    <div data-layout="header"></div>

    <main class="mx-auto max-w-7xl px-4 pb-16 pt-28 sm:px-6 lg:px-8">
        <section class="glass overflow-hidden rounded-[2rem] border border-white/10 px-6 py-8 shadow-[0_30px_80px_rgba(0,0,0,0.35)] sm:px-10">
            <p class="mb-3 text-xs font-semibold uppercase tracking-[0.35em] text-accent"><?= e($eyebrow) ?></p>
            <h1 class="font-headline text-4xl font-extrabold tracking-tight text-white sm:text-5xl"><?= e($heading) ?></h1>
            <p class="mt-4 max-w-3xl text-sm text-white/70 sm:text-base"><?= e($description) ?></p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="<?= route_path('home') ?>" class="rounded-full bg-primary/20 px-5 py-3 text-sm font-semibold text-primary transition hover:bg-primary/30">Volver al inicio</a>
                <a href="<?= route_path('series') ?>" class="rounded-full border border-white/10 px-5 py-3 text-sm font-semibold text-white/80 transition hover:border-primary/40 hover:text-white">Ver animes</a>
                <a href="<?= route_path('movies') ?>" class="rounded-full border border-white/10 px-5 py-3 text-sm font-semibold text-white/80 transition hover:border-primary/40 hover:text-white">Ver peliculas</a>
            </div>
        </section>

        <?php if ($cards !== []): ?>
        <section class="mt-10">
            <div class="mb-5 flex items-center justify-between gap-4">
                <h2 class="font-headline text-2xl font-bold">Coleccion visible</h2>
                <p class="text-sm text-white/50">Base temporal para la replica</p>
            </div>
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($cards as $card): ?>
                    <article data-anime-card data-title="<?= e((string) ($card['title'] ?? '')) ?>" class="group overflow-hidden rounded-[1.5rem] border border-white/10 bg-surface/85 shadow-[0_16px_40px_rgba(0,0,0,0.32)]">
                        <div class="aspect-[4/5] overflow-hidden bg-black/20">
                            <img
                                alt="<?= e((string) ($card['title'] ?? 'Tarjeta')) ?>"
                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                data-fallback="<?= asset_path('img/fondoanime.png') ?>"
                                src="<?= asset_path('img/fondoanime.png') ?>"
                            >
                        </div>
                        <div class="space-y-2 px-5 py-4">
                            <div class="flex items-center justify-between gap-3">
                                <span class="rounded-full bg-primary/15 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-primary"><?= e((string) ($card['type'] ?? 'Anime')) ?></span>
                                <span class="text-xs uppercase tracking-[0.2em] text-white/45"><?= e((string) ($card['year'] ?? '')) ?></span>
                            </div>
                            <h3 class="text-lg font-bold text-white"><?= e((string) ($card['title'] ?? '')) ?></h3>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <div data-layout="footer"></div>

    <script src="<?= asset_path('assets/js/shared-utils.js?v=replica-header-3') ?>"></script>
    <script src="<?= asset_path('assets/js/layout.js?v=replica-header-3') ?>"></script>
    <script src="<?= asset_path('assets/js/search.js') ?>"></script>
    <script src="<?= asset_path('assets/js/title-images.js') ?>"></script>
    <script src="<?= asset_path('assets/js/favorites.js') ?>"></script>
    <script src="<?= asset_path('assets/js/detail-links.js') ?>"></script>
    <script src="<?= asset_path('assets/js/i18n.js') ?>"></script>
</body>
</html>
