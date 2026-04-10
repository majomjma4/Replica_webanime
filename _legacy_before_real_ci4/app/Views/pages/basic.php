<?php

$pageTitle = (string) ($page['title'] ?? 'NekoraList');
$eyebrow = (string) ($page['eyebrow'] ?? 'Replica');
$heading = (string) ($page['heading'] ?? 'Pagina');
$description = (string) ($page['description'] ?? '');
$cards = is_array($page['cards'] ?? null) ? $page['cards'] : [];
$slug = (string) ($slug ?? '');
$isSeriesCatalog = $slug === 'series';
$isMovieCatalog = $slug === 'peliculas';
$isCatalog = $isSeriesCatalog || $isMovieCatalog;
$catalogLabel = $isSeriesCatalog ? 'Series' : ($isMovieCatalog ? 'Peliculas' : 'Coleccion');
$catalogSearchPlaceholder = $isSeriesCatalog ? 'Buscar anime...' : ($isMovieCatalog ? 'Buscar pelicula...' : 'Buscar titulo...');
$catalogAccent = $isMovieCatalog ? 'from-fuchsia-500/25 via-rose-500/10 to-transparent' : 'from-cyan-500/25 via-sky-500/10 to-transparent';
$catalogBadge = $isMovieCatalog ? 'Peliculas' : 'Series';
$catalogLead = $isCatalog
    ? ($isMovieCatalog
        ? 'Explora peliculas con acceso rapido al detalle, imagenes dinamicas y filtrado en tiempo real.'
        : 'Explora series con acceso rapido al detalle, imagenes dinamicas y filtrado en tiempo real.')
    : 'Seguimos migrando esta seccion para que la replica se comporte igual que el resto del sitio.';

$buildCatalogCopy = static function (array $card): string {
    $type = trim((string) ($card['type'] ?? 'Anime'));
    $year = trim((string) ($card['year'] ?? ''));
    $parts = array_values(array_filter([$type, $year !== '' ? $year : null]));
    return $parts !== []
        ? implode(' - ', $parts)
        : 'Ficha lista para hidratarse con datos de la replica.';
};
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
      .catalog-shell { position: relative; overflow: hidden; }
      .catalog-shell::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
          radial-gradient(circle at top left, rgba(56, 189, 248, 0.18), transparent 32%),
          radial-gradient(circle at bottom right, rgba(168, 85, 247, 0.18), transparent 30%);
        pointer-events: none;
      }
      .catalog-card {
        transform: translateY(0);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
      }
      .catalog-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.38), 0 0 22px rgba(139, 92, 246, 0.14);
      }
      .catalog-poster::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, transparent 20%, rgba(5, 7, 17, 0.1) 55%, rgba(5, 7, 17, 0.8) 100%);
        pointer-events: none;
      }
    </style>
</head>
<body class="min-h-screen text-white font-body">
    <div data-layout="header"></div>

    <main class="mx-auto max-w-7xl px-4 pb-16 pt-28 sm:px-6 lg:px-8">
        <section class="catalog-shell glass overflow-hidden rounded-[2rem] border border-white/10 px-6 py-8 shadow-[0_30px_80px_rgba(0,0,0,0.35)] sm:px-10">
            <div class="relative z-10 flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <p class="mb-3 text-xs font-semibold uppercase tracking-[0.35em] text-accent"><?= e($eyebrow) ?></p>
                    <h1 class="font-headline text-4xl font-extrabold tracking-tight text-white sm:text-5xl"><?= e($heading) ?></h1>
                    <p class="mt-4 max-w-3xl text-sm text-white/70 sm:text-base"><?= e($description) ?></p>
                    <p class="mt-3 max-w-2xl text-sm text-white/55"><?= e($catalogLead) ?></p>
                </div>

                <div class="w-full max-w-xl space-y-4">
                    <?php if ($isCatalog): ?>
                        <label class="block">
                            <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.28em] text-white/45">Busqueda rapida</span>
                            <div class="relative">
                                <span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-white/45">search</span>
                                <input
                                    id="filter-search"
                                    data-catalog-search="1"
                                    type="search"
                                    placeholder="<?= e($catalogSearchPlaceholder) ?>"
                                    class="w-full rounded-[1.4rem] border border-white/10 bg-black/25 py-3 pl-12 pr-4 text-sm text-white outline-none transition placeholder:text-white/35 focus:border-primary/40 focus:bg-black/35"
                                >
                            </div>
                        </label>
                    <?php endif; ?>

                    <div class="flex flex-wrap gap-3 text-xs">
                        <div class="rounded-full border border-white/10 bg-white/5 px-4 py-2 font-semibold uppercase tracking-[0.2em] text-white/70">
                            <?= e((string) count($cards)) ?> titulos
                        </div>
                        <div class="rounded-full border border-white/10 bg-white/5 px-4 py-2 font-semibold uppercase tracking-[0.2em] text-white/70">
                            <?= e($catalogBadge) ?>
                        </div>
                        <div class="rounded-full border border-white/10 bg-white/5 px-4 py-2 font-semibold uppercase tracking-[0.2em] text-white/70">
                            Replica local
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative z-10 mt-6 flex flex-wrap gap-3">
                <a href="<?= route_path('home') ?>" class="rounded-full bg-primary/20 px-5 py-3 text-sm font-semibold text-primary transition hover:bg-primary/30">Volver al inicio</a>
                <a href="<?= route_path('series') ?>" class="rounded-full border border-white/10 px-5 py-3 text-sm font-semibold text-white/80 transition hover:border-primary/40 hover:text-white">Ver animes</a>
                <a href="<?= route_path('movies') ?>" class="rounded-full border border-white/10 px-5 py-3 text-sm font-semibold text-white/80 transition hover:border-primary/40 hover:text-white">Ver peliculas</a>
            </div>
        </section>

        <?php if ($cards !== []): ?>
        <section class="mt-10">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/45"><?= e($catalogLabel) ?></p>
                    <h2 class="font-headline text-2xl font-bold"><?= $isCatalog ? e($heading) : 'Coleccion visible' ?></h2>
                </div>
                <p class="text-sm text-white/50"><?= e($isCatalog ? 'Selecciona cualquier tarjeta para abrir su detalle.' : 'Seccion en migracion.') ?></p>
            </div>
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <?php foreach ($cards as $card): ?>
                    <?php
                    $cardTitle = (string) ($card['title'] ?? '');
                    $cardType = (string) ($card['type'] ?? ($isMovieCatalog ? 'Pelicula' : 'Anime'));
                    $cardYear = (string) ($card['year'] ?? '');
                    $cardHref = route_path('detail') . '?q=' . rawurlencode($cardTitle);
                    $cardCopy = $buildCatalogCopy($card);
                    ?>
                    <a
                        href="<?= e($cardHref) ?>"
                        data-anime-card
                        data-title="<?= e($cardTitle) ?>"
                        data-year="<?= e($cardYear) ?>"
                        class="catalog-card group cursor-pointer overflow-hidden rounded-[1.6rem] border border-white/10 bg-surface/85 shadow-[0_16px_40px_rgba(0,0,0,0.32)]"
                    >
                        <div class="catalog-poster relative aspect-[2/3] overflow-hidden bg-black/20">
                            <img
                                alt="<?= e($cardTitle !== '' ? $cardTitle : 'Tarjeta') ?>"
                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                data-fallback="<?= asset_path('img/fondoanime.png') ?>"
                                src="<?= asset_path('img/fondoanime.png') ?>"
                            >
                            <div class="absolute inset-x-0 top-0 flex items-start justify-between gap-3 p-4">
                                <span class="rounded-full bg-black/45 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-white/80 backdrop-blur"><?= e($cardType) ?></span>
                                <?php if ($cardYear !== ''): ?>
                                    <span data-card-year class="rounded-full bg-black/45 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-white/75 backdrop-blur"><?= e($cardYear) ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="absolute inset-x-0 bottom-0 p-4">
                                <div class="inline-flex rounded-full bg-gradient-to-r <?= e($catalogAccent) ?> px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-white">
                                    <?= e($catalogBadge) ?>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3 px-5 py-4">
                            <h3 class="min-h-[3.5rem] text-lg font-bold text-white"><?= e($cardTitle) ?></h3>
                            <p class="text-sm leading-6 text-white/55"><?= e($cardCopy) ?></p>
                            <div class="flex items-center justify-between gap-3 pt-1 text-sm text-white/65">
                                <span class="inline-flex items-center gap-2">
                                    <span class="material-symbols-outlined text-base">info</span>
                                    Abrir detalle
                                </span>
                                <span class="material-symbols-outlined text-lg transition group-hover:translate-x-1">arrow_forward</span>
                            </div>
                        </div>
                    </a>
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

