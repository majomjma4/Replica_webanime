<!DOCTYPE html>
<html class="dark" lang="es">
  <head>
    <script data-ui-preload>document.documentElement.classList.add("preload-ui");</script>
    <style>
  .preload-ui body { opacity: 0; }
          @media (max-width: 1279px) {
        .filter-panel {
          width: 100%;
          min-height: auto;
        }
      }
      @media (max-width: 1024px) {
        .hover-preview-panel {
          width: min(92vw, 680px);
        }
      }
      @media (max-width: 768px) {
        .hover-preview-panel {
          min-height: auto;
          flex-direction: column;
          gap: 1rem;
          padding: 1rem;
        }
        .hover-preview-poster {
          width: 100%;
          flex: 0 0 auto;
          max-width: 240px;
          margin: 0 auto;
        }
        .hover-preview-info {
          gap: 0.85rem;
        }
      }
</style>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>NekoraList - Series</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
 tailwind.config = {
 darkMode: "class",
 theme: {
 extend: {
 colors: {
 "primary-dim": "#c0adff",
 "primary-fixed-dim": "#dacdff",
 "background": "#0e0e0e",
 "on-background": "#e7e5e4",
 "surface-bright": "#2c2c2c",
 "on-surface-variant": "#acabaa",
 "tertiary-dim": "#cecfef",
 "on-tertiary-container": "#4c4e68",
 "on-secondary-fixed-variant": "#5d5b5f",
 "on-tertiary-fixed-variant": "#565873",
 "inverse-surface": "#fcf8f8",
 "error-container": "#7f2737",
 "on-primary": "#4800bf",
 "on-secondary-fixed": "#403e42",
 "on-error-container": "#ff97a3",
 "surface-container-highest": "#252626",
 "on-surface": "#e7e5e4",
 "outline": "#767575",
 "on-error": "#490013",
 "tertiary-fixed": "#ddddfe",
 "surface-container-lowest": "#000000",
 "surface-tint": "#cdbdff",
 "surface": "#0e0e0e",
 "tertiary-container": "#ddddfe",
 "surface-variant": "#252626",
 "inverse-on-surface": "#565554",
 "inverse-primary": "#6834eb",
 "surface-container-high": "#1f2020",
 "primary-container": "#4f00d0",
 "primary": "#cdbdff",
 "secondary-fixed": "#e6e1e6",
 "on-primary-fixed-variant": "#652fe7",
 "outline-variant": "#484848",
 "on-secondary-container": "#c2bec3",
 "on-tertiary-fixed": "#3a3c55",
 "secondary-dim": "#a09da1",
 "secondary-container": "#3c3b3e",
 "error": "#ec7c8a",
 "on-primary-container": "#d6c9ff",
 "tertiary": "#edecff",
 "surface-container-low": "#131313",
 "surface-container": "#191a1a",
 "secondary-fixed-dim": "#d8d3d8",
 "surface-dim": "#0e0e0e",
 "on-tertiary": "#555671",
 "on-secondary": "#211f23",
 "tertiary-fixed-dim": "#cecfef",
 "error-dim": "#b95463",
 "secondary": "#a09da1",
 "primary-fixed": "#e8deff",
 "on-primary-fixed": "#4700bd"
 },
 fontFamily: {
 "headline": ["Manrope"],
 "body": ["Inter"],
 "label": ["Inter"]
 },
 borderRadius: {
 "DEFAULT": "1rem",
 "lg": "1.5rem",
 "xl": "2rem",
 "full": "9999px"
 },
 boxShadow: {
 "elevated": "0 16px 40px rgba(0,0,0,0.35)",
 "card": "0 10px 24px rgba(0,0,0,0.28)"
 },
 transitionTimingFunction: {
 "snappy": "cubic-bezier(0.2,0,0,1)"
 }
 }
 }
 }
    </script>
    <style>
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .filter-panel {
        overflow: visible;
        max-height: none;
        width: calc(100% + 1.7rem);
        min-height: calc(100vh - 10rem);
        padding-bottom: 1.5rem;
      }

    /* Normalize popular anime card sizes */
    section[aria-label="Grid de anime"] [data-anime-card] .space-y-2 > .flex {
      flex-wrap: nowrap;
      overflow: hidden;
    }
    section[aria-label="Grid de anime"] [data-anime-card] .space-y-2 > .flex > span {
      white-space: nowrap;
    }
    section[aria-label="Grid de anime"] [data-anime-card] h3 {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    [data-anime-card] {
      border: 1px solid transparent;
      transition: transform 300ms cubic-bezier(0.2,0,0,1), border-color 300ms ease, box-shadow 300ms ease;
    }
    [data-anime-card]:hover {
      border-color: rgba(168, 85, 247, 0.35);
      box-shadow: 0 12px 28px rgba(0, 0, 0, 0.25), 0 0 16px rgba(168, 85, 247, 0.18);
    }
    .hover-preview-modal {
      position: fixed;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      pointer-events: none;
      transition: opacity 220ms ease;
      z-index: 999;
    }
    .hover-preview-modal.is-open {
      opacity: 1;
      pointer-events: auto;
    }
    .hover-preview-backdrop {
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.75);
      backdrop-filter: blur(8px);
    }
    .hover-preview-panel {
      position: relative;
      z-index: 1;
      width: min(88vw, 450px);
      min-height: 380px;
      display: flex;
      align-items: stretch;
      gap: 22px;
      padding: 26px;
      cursor: pointer;
      border-radius: 1.2rem;
      background: rgba(16, 16, 20, 0.96);
      border: 1px solid rgba(255, 255, 255, 0.08);
      box-shadow: 0 24px 50px rgba(0, 0, 0, 0.55);
      transform: scale(0.96);
      opacity: 0;
      transition: opacity 220ms ease, transform 220ms ease;
    }
    .hover-preview-modal.is-open .hover-preview-panel {
      opacity: 1;
      transform: scale(1);
    }
    .hover-preview-poster {
      width: 200px;
      flex: 0 0 200px;
      aspect-ratio: 2 / 3;
      border-radius: 0.9rem;
      overflow: hidden;
      background: #111113;
    }
    .hover-preview-poster img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
    .hover-preview-info {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      gap: 0.5rem;
      min-width: 0;
      flex: 1;
    }
    .hover-preview-title {
      font-weight: 700;
      font-size: 1.25rem;
      line-height: 1.2;
    }
    .hover-preview-meta {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 0.45rem;
      font-size: 0.75rem;
      color: rgba(229, 231, 235, 0.78);
    }
    .hover-preview-score,
    .hover-preview-year,
    .hover-preview-extra {
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
      justify-content: center;
      width: 100%;
      min-width: 0;
      text-align: center;
      white-space: nowrap;
      padding: 0.22rem 0.55rem;
      border-radius: 9999px;
      background: linear-gradient(135deg, rgba(99,102,241,0.35), rgba(168,85,247,0.35));
      border: 1px solid rgba(168, 85, 247, 0.45);
      box-shadow: 0 0 10px rgba(168, 85, 247, 0.25);
    }
    .hover-preview-meta > span:only-child,
    .hover-preview-meta > span:last-child:nth-child(odd) {
      grid-column: 1 / -1;
    }
    .hover-preview-score {
      color: #cdbdff;
      font-weight: 700;
    }
    .hover-preview-score .material-symbols-outlined {
      font-size: 0.8rem;
    }
    .hover-preview-synopsis {
      font-size: 0.86rem;
      line-height: 1.45;
      color: rgba(229, 231, 235, 0.75);
      flex: 1;
      display: flex;
      align-items: center;
      text-align: center;
    }
    .hover-preview-genres {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 0.4rem;
      margin-top: 0.25rem;
    }
    .hover-preview-genres span {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 100%;
      min-width: 0;
      text-align: center;
      white-space: nowrap;
      font-size: 0.7rem;
      padding: 0.18rem 0.55rem;
      border-radius: 9999px;
      background: linear-gradient(135deg, rgba(34,211,238,0.28), rgba(99,102,241,0.25));
      border: 1px solid rgba(59, 130, 246, 0.35);
      color: rgba(240, 249, 255, 0.95);
      box-shadow: 0 0 10px rgba(56, 189, 248, 0.18);
    }
    .hover-preview-genres span:only-child,
    .hover-preview-genres span:last-child:nth-child(odd),
    .hover-preview-genres span.is-wide {
      grid-column: 1 / -1;
    }
    .hover-preview-genres.is-stacked {
      grid-template-columns: 1fr;
    }
    .hover-preview-genres.is-stacked span {
      grid-column: 1 / -1;
    }
    </style>
    <link rel="icon" href="<?= asset_path('img/icon3.png') ?>" />


  </head>
  <body class="bg-background text-on-background font-body selection:bg-[#AC85DB]/30 selection:text-[#996AD2]">
    <div class="min-h-screen">
      <!-- Navbar Component -->
      <div data-layout="header"></div>

      <main class="mx-auto max-w-screen-2xl px-4 sm:px-6 pb-[clamp(0.9rem,1.6vw,1.6rem)] pt-24 sm:pt-28">
          <div class="grid grid-cols-1 gap-10 lg:grid-cols-12">
            <!-- Sidebar Component -->
          <aside class="lg:col-span-2">
            <section class="rounded-lg bg-surface-container-low p-6 filter-panel" aria-label="Filtros">
            <h2 class="font-headline text-xl font-bold text-on-surface">Filtros</h2>
            <div class="space-y-6">
                <div class="space-y-2">
                  <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant" for="filter-search">Buscar</label>
                  <div class="flex items-center gap-2 rounded-xl bg-surface-container-high px-4 py-3">
                    <span class="material-symbols-outlined text-primary">search</span>
                    <input id="catalog-search-input" name="catalog_search_input" data-catalog-search="1" class="w-full bg-transparent border-none focus:ring-0" type="search" placeholder="Ej: Frieren" aria-label="Buscar anime" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"/>
                  </div>
                </div>
                <div class="space-y-2">
                  <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant">G&eacute;neros</label>

                  <!-- Dropdown de g&eacute;neros renderizado por assets/js/filters.js -->
                </div>
                <div class="space-y-4">
                  <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant" for="filter-year">A&ntilde;o</label>
                    <select id="filter-year" class="w-full rounded-xl bg-surface-container-high px-4 py-3 text-on-surface" aria-label="Filtrar por a&ntilde;o">
                      <option>Todos</option>
                      <option>2026</option>
                      <option>2023</option>
                      <option>2022</option>
                    </select>
                  </div>
                  <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant" for="filter-type">Tipo</label>
                    <select id="filter-type" class="w-full rounded-xl bg-surface-container-high px-4 py-3 text-on-surface" aria-label="Filtrar por tipo">
                      <option>Todos</option>
                      <option>Serie</option>
                      <option>Pel&iacute;cula</option>
                      <option>OVA</option>
                    </select>
                  </div>
                  <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant" for="filter-status">Estado</label>
                    <select id="filter-status" class="w-full rounded-xl bg-surface-container-high px-4 py-3 text-on-surface" aria-label="Filtrar por estado">
                      <option>Todos</option>
                      <option>En emisi&oacute;n</option>
                      <option>Finalizado</option>
                    </select>
                  </div>
                </div>
                <div class="flex gap-3">
                  <button class="flex-1 rounded-full bg-surface-container-high py-3 text-sm font-semibold text-on-surface" type="button">Reiniciar</button>
                  <button class="flex-1 rounded-full bg-primary py-3 text-sm font-bold text-on-primary" type="button">Aplicar</button>
                </div>
              </div>
            </section>
            <section class="mt-6 rounded-lg bg-surface-container-low p-4" aria-label="Ranking">
              <div class="mb-3 flex items-center justify-between gap-3">
                <h3 class="text-sm font-bold uppercase tracking-widest text-on-surface-variant">Top 5 Ranking</h3>
                <a class="text-xs font-semibold text-primary hover:text-primary-dim" href="ranking\.php">Ver ranking</a>
              </div>
              <div id="sidebar-ranking" data-sidebar-ranking data-ranking-type="anime" class="space-y-3">
                <div class="text-xs text-on-surface-variant">Cargando ranking...</div>
              </div>
            </section>
          </aside>

          <!-- Catalog Content -->
          <section class="lg:col-span-10" aria-label="Resultados de cat&aacute;logo">
            <header class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
              <div>
                <h1 class="font-headline text-4xl font-extrabold text-on-surface">Descubrimiento</h1>
              </div>
              <div class="flex items-center gap-3 text-sm text-on-surface-variant">
                <span>Ordenar por:</span>
                <button class="flex items-center gap-1 font-bold text-on-surface" type="button" aria-label="Ordenar resultados">
                  Popularidad <span class="material-symbols-outlined text-sm">expand_more</span>
                </button>
              </div>
            </header>

            <!-- States (loading/empty/error) -->
            <section class="space-y-6" aria-live="polite">
              <div class="hidden" data-state="loading">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
                  <article class="animate-pulse rounded-lg bg-surface-container-low p-4">
                    <div class="aspect-[2/3] rounded-lg bg-surface-container-high"></div>
                    <div class="space-y-3">
                      <div class="h-3 w-48 rounded-full bg-surface-container-high"></div>
                      <div class="h-5 w-48/4 rounded-lg bg-surface-container-high"></div>
                      <div class="h-3 w-48/2 rounded-full bg-surface-container-high"></div>
                    </div>
                  </article>
                  <article class="animate-pulse rounded-lg bg-surface-container-low p-4">
                    <div class="aspect-[2/3] rounded-lg bg-surface-container-high"></div>
                    <div class="space-y-3">
                      <div class="h-3 w-48 rounded-full bg-surface-container-high"></div>
                      <div class="h-5 w-48/3 rounded-lg bg-surface-container-high"></div>
                      <div class="h-3 w-48/2 rounded-full bg-surface-container-high"></div>
                    </div>
                  </article>
                  <article class="animate-pulse rounded-lg bg-surface-container-low p-4">
                    <div class="aspect-[2/3] rounded-lg bg-surface-container-high"></div>
                    <div class="space-y-3">
                      <div class="h-3 w-48 rounded-full bg-surface-container-high"></div>
                      <div class="h-5 w-48/5 rounded-lg bg-surface-container-high"></div>
                      <div class="h-3 w-48/2 rounded-full bg-surface-container-high"></div>
                    </div>
                  </article>
                </div>
              </div>

              <div class="hidden" data-state="empty">
                <div class="rounded-lg bg-surface-container-low p-10 text-center">
                  <h2 class="text-2xl font-bold text-on-surface">Sin resultados</h2>
                  <p class="mt-2 text-on-surface-variant">Prueba otros filtros para ver m&aacute;s t&iacute;tulos disponibles.</p>
                </div>
              </div>

              <div class="hidden" data-state="error">
                <div class="rounded-lg bg-error-container/30 p-10 text-center">
                  <h2 class="text-2xl font-bold text-on-error-container">Ocurri&oacute; un error</h2>
                  <p class="mt-2 text-on-error-container/80">No pudimos cargar el cat&aacute;logo. Int&eacute;ntalo nuevamente</p>
                  <button class="mt-6 rounded-full bg-primary px-6 py-3 text-sm font-bold text-on-primary" type="button">Reintentar</button>
                </div>
              </div>
            </section>

            <!-- AnimeCard Component -->
            <section class="mt-[clamp(0.6rem,0.9vw,0.8rem)] grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6" aria-label="Grid de anime">
              <?php if (isset($dbGenres)) : ?>
                  <script>window.DB_GENRES = <?= json_encode($dbGenres) ?>;</script>
              <?php endif; ?>
              <?php
                  if (!empty($animes)) {
                      foreach ($animes as $a) {
                          $generos_str = '';
                          if (!empty($a['generos'])) {
                              if (is_array($a['generos'])) {
                                  $generos_str = implode(', ', array_filter(array_map('strval', $a['generos'])));
                              } else {
                                  $generos_str = (string) $a['generos'];
                              }
                          } elseif (!empty($a['generos_str'])) {
                              $generos_str = (string) $a['generos_str'];
                          }
              ?>
              <article class="group rounded-lg bg-surface-container-low p-4 transition-transform duration-300 ease-snappy hover:scale-[1.02]" 
                       data-anime-card 
                       data-title="<?= htmlspecialchars(strtolower($a['titulo'])) ?>" 
                       data-genres="<?= htmlspecialchars($generos_str) ?>" 
                       data-year="<?= htmlspecialchars($a['anio'] ?? '') ?>" 
                       data-type="<?= htmlspecialchars($a['tipo']) ?>" 
                       data-status="<?= htmlspecialchars($a['estado'] ?? 'Desconocido') ?>"
                       data-mal-id="<?= htmlspecialchars($a['mal_id'] ?? '') ?>">
                <a class="block" href="<?= detail_path((string) ($a['mal_id'] ?? ''), (string) $a['titulo'], (string) ($a['id'] ?? '')) ?>" aria-label="<?= htmlspecialchars($a['titulo']) ?>" data-mal-id="<?= htmlspecialchars((string)($a['mal_id'] ?? $a['id'])) ?>">
                  <div class="relative aspect-[2/3] overflow-hidden rounded-lg bg-surface-container-high">
                    <img alt="<?= htmlspecialchars($a['titulo']) ?>" 
                         class="h-full w-full object-cover transition-transform duration-500 ease-snappy group-hover:scale-[1.03]" 
                         src="<?= htmlspecialchars($a['imagen_url'] ?? 'img/fondoanime.png') ?>" loading="lazy" referrerpolicy="no-referrer"/>
                    <span class="absolute left-3 bottom-3 rounded-full bg-surface/90 px-4 py-1.5 text-sm font-bold text-on-surface shadow-lg"><?= htmlspecialchars($a['anio'] ?? '') ?></span>
                    <?php if (!empty($a['puntuacion'])) { ?>
                    <span class="anidex-score-badge absolute top-3 right-3 bg-surface-container-lowest/80 backdrop-blur px-2 py-1 rounded text-xs font-bold text-primary flex items-center gap-1">
                       <span class="material-symbols-outlined text-[10px]" style="font-variation-settings: 'FILL' 1;">star</span>
                       <span><?= number_format((float)$a['puntuacion'], 1) ?></span>
                    </span>
                    <?php } ?>
                  </div>
                  <div class="space-y-2 mt-2">
                    <h3 class="font-headline text-lg font-bold text-on-surface" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($a['titulo']) ?></h3>
                  </div>
                </a>
              </article>
              <?php 
                      }
                  } else {
                      echo "<p class='col-span-full text-center text-on-surface-variant'>No hay series disponibles.</p>";
                  }
              ?>
            </section>

            <div class="mt-12 flex justify-center hidden">
              <button class="rounded-full border border-primary/30 bg-primary/10 px-8 py-4 text-sm font-bold text-primary transition-colors hover:bg-primary/20 inline-flex items-center gap-2" type="button" aria-label="Cargar m&aacute;s resultados">
                <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">add_circle</span>
                Cargar m&aacute;s
              </button>
            </div>
          </section>
        </div>
      </main>

      <!-- Footer Component -->
      <div data-layout="footer"></div>
      <script src="<?= asset_path('assets/js/layout.js?v=theme1') ?>"></script>
    <script src="<?= asset_path('assets/js/shared-utils.js?v=3') ?>"></script>
    </div>
    <script src="<?= asset_path('assets/js/i18n.js') ?>"></script>
    <script src="<?= asset_path('assets/js/title-images.js?v=1774473995,33386') ?>"></script>
    <script src="<?= asset_path('assets/js/search.js?v=popular4') ?>"></script>
    <script src="<?= asset_path('assets/js/detail-links.js?v=5') ?>"></script>
    <script src="<?= asset_path('assets/js/filters.js?v=final5') ?>"></script>
    <script src="<?= asset_path('assets/js/load-more.js?v=1774473995,33386') ?>"></script>
    <script>
  document.addEventListener("DOMContentLoaded", () => {
    if (window.AniDexI18n) window.AniDexI18n.init();
    if (window.AniDexTitleImages) window.AniDexTitleImages.init();
    if (window.AniDexSearch) window.AniDexSearch.init();
    if (window.AniDexDetailLinks) window.AniDexDetailLinks.init();
    if (window.AniDexFilters) window.AniDexFilters.init();
    const purgeBlockedSeries = () => {
      document.querySelectorAll('[data-anime-card]').forEach((card) => {
        const title = ((card.getAttribute('data-title') || '') + ' ' + (card.querySelector('h3,h4,h5')?.textContent || '')).toLowerCase();
        if (title.includes('does it count if you lose your innocence to an android') || title.includes('does it count if') || title.includes('futanari')) {
          card.remove();
        }
      });
    };
    purgeBlockedSeries();
    new MutationObserver(() => purgeBlockedSeries()).observe(document.body, { childList: true, subtree: true });
  });
</script>
    <script data-ui-unlock>document.documentElement.classList.remove("preload-ui");</script>
  </body>
</html>









