<!DOCTYPE html>
<html class="dark" lang="es">
<head>
<link rel="icon" href="img/icon3.png" />
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>NekoraList - Gestion de Animes</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script id="tailwind-config">
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        colors: {
          "secondary-container": "#3c3b3e",
          "on-tertiary": "#555671",
          "error-dim": "#b95463",
          "on-surface-variant": "#acabaa",
          "tertiary-fixed": "#ddddfe",
          "surface-container-lowest": "#000000",
          "on-primary-fixed": "#4700bd",
          "outline": "#767575",
          "on-primary-container": "#d6c9ff",
          "on-background": "#e7e5e4",
          "tertiary-dim": "#cecfef",
          "primary": "#cdbdff",
          "primary-fixed": "#e8deff",
          "secondary-fixed-dim": "#d8d3d8",
          "secondary-dim": "#a09da1",
          "surface-container-high": "#1f2020",
          "error-container": "#7f2737",
          "surface-container-low": "#131313",
          "tertiary-container": "#ddddfe",
          "on-primary": "#4800bf",
          "surface-container": "#191a1a",
          "error": "#ec7c8a",
          "surface-variant": "#252626",
          "surface": "#0e0e0e",
          "on-error": "#490013",
          "surface-container-highest": "#252626",
          "secondary": "#a09da1",
          "on-surface": "#e7e5e4"
        },
        fontFamily: {
          "headline": ["Manrope"],
          "body": ["Inter"],
          "label": ["Inter"]
        },
        borderRadius: {"DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px"}
      }
    }
  }
</script>
<style>
  .material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
  }
  body {
    background-color: #0e0e0e;
    color: #e7e5e4;
    font-family: 'Inter', sans-serif;
  }
</style>
</head>
<body class="flex min-h-screen overflow-x-hidden" data-admin-page="manage">
<div data-admin-sidebar></div>
<main class="ml-64 flex-1 flex flex-col min-h-screen bg-surface">
  <header class="fixed top-0 right-0 z-40 flex h-20 w-[calc(100%-16rem)] items-center justify-between bg-[#0e0e0e]/85 px-12 backdrop-blur-xl border-b border-outline-variant/10">
    <div class="flex items-center gap-4">
      <h1 class="font-headline text-lg font-semibold text-on-surface">Gestionar Catálogo</h1>
          </div>
    <div></div>
  </header>

  <section class="mt-20 p-12 space-y-10">
    <div class="flex flex-wrap items-end justify-between gap-6">
      <div>
        <p class="mb-2 text-xs font-bold uppercase tracking-[0.25em] text-primary">Vista General Del Catálogo</p>
        <h2 class="font-headline text-5xl font-extrabold tracking-tight text-on-surface">Administrar Anime</h2>
        <p class="mt-3 max-w-2xl text-sm text-on-surface-variant">Organiza el catálogo, revisa estados y mantén la lista limpia desde un solo lugar.</p>
      </div>
      <div class="flex flex-wrap gap-3">
        <button class="flex items-center gap-2 rounded-full border border-[#9a8cff]/35 bg-gradient-to-r from-[#9a8cff] to-[#74d8ff] px-6 py-3 text-sm font-bold text-slate-950 shadow-xl shadow-[#7f8cff]/20 transition hover:brightness-110" data-admin-link="a%C3%B1adir.php">
          <span class="material-symbols-outlined text-[18px]">add</span>
          AÑADIR NUEVO ANIME
        </button>
      </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_minmax(360px,1.35fr)]">
      <div class="rounded-[28px] border border-outline-variant/10 bg-gradient-to-br from-surface-container-low to-surface-container-high p-8 shadow-2xl text-center min-h-[220px] flex flex-col items-center justify-center">
        <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-on-surface-variant">Títulos Totales</p>
        <h3 class="mt-5 font-headline text-5xl font-extrabold tracking-tight text-on-surface"><?= $totalAnimes ?></h3>
        <p class="mt-4 text-sm text-on-surface-variant">Catálogo visible en esta vista.</p>
      </div>
      <div class="rounded-[28px] border border-outline-variant/10 bg-gradient-to-br from-surface-container-low to-surface-container-high p-8 shadow-2xl text-center min-h-[220px] flex flex-col items-center justify-center">
        <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-on-surface-variant">En Emision</p>
        <h3 class="mt-5 font-headline text-5xl font-extrabold tracking-tight text-on-surface"><?= $airingCount ?></h3>
        <p class="mt-4 text-sm text-on-surface-variant">Series activas detectadas.</p>
      </div>
      <div class="rounded-[28px] border border-outline-variant/10 bg-surface-container-low p-6 shadow-2xl">
        <div class="mb-4 flex items-center justify-between gap-4">
          <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-on-surface-variant">Busqueda Y Filtros</p>
            <p class="mt-2 text-xs text-on-surface-variant">Busca por nombre, a&ntilde;o, tipo o estado.</p>
          </div>
          <button class="rounded-full border border-outline-variant/20 px-4 py-2 text-[10px] font-bold uppercase tracking-[0.18em] text-on-surface-variant transition hover:border-primary hover:text-primary" data-admin-filter-clear>Limpiar</button>
        </div>
        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
          <label class="text-[10px] font-bold uppercase tracking-[0.18em] text-on-surface-variant">Buscar
            <input class="mt-2 w-full rounded-full border border-outline-variant/20 bg-surface-container-highest px-4 py-3 text-sm text-on-surface placeholder:text-outline focus:border-primary focus:ring-0" placeholder="Nombre, a&ntilde;o, tipo o estado" type="text" data-admin-manage-search value="<?= htmlspecialchars($searchQuery) ?>"/>
          </label>
          <label class="text-[10px] font-bold uppercase tracking-[0.18em] text-on-surface-variant">Estado
            <select class="mt-2 w-full rounded-full border border-outline-variant/20 bg-surface-container-highest px-4 py-3 text-sm text-on-surface focus:border-primary focus:ring-0" data-admin-filter-status-select>
              <option value="ALL" <?= strtoupper($statusFilter) === "ALL" || $statusFilter === "" ? "selected" : "" ?>>Todos</option>
              <option value="EN EMISION" <?= strtoupper($statusFilter) === "EN EMISION" ? "selected" : "" ?>>En emision</option>
              <option value="FINALIZADO" <?= strtoupper($statusFilter) === "FINALIZADO" ? "selected" : "" ?>>Finalizado</option>
              <option value="PROXIMAMENTE" <?= strtoupper($statusFilter) === "PROXIMAMENTE" ? "selected" : "" ?>>Proximamente</option>
            </select>
          </label>
          <label class="text-[10px] font-bold uppercase tracking-[0.18em] text-on-surface-variant">Tipo
            <select class="mt-2 w-full rounded-full border border-outline-variant/20 bg-surface-container-highest px-4 py-3 text-sm text-on-surface focus:border-primary focus:ring-0" data-admin-filter-type-select>
              <option value="ALL" <?= strtoupper($typeFilter) === "ALL" || $typeFilter === "" ? "selected" : "" ?>>Todos</option>
              <option value="TV" <?= strtoupper($typeFilter) === "TV" ? "selected" : "" ?>>TV</option>
              <option value="MOVIE" <?= strtoupper($typeFilter) === "MOVIE" ? "selected" : "" ?>>Movie</option>
              <option value="OVA" <?= strtoupper($typeFilter) === "OVA" ? "selected" : "" ?>>OVA</option>
              <option value="SPECIAL" <?= strtoupper($typeFilter) === "SPECIAL" ? "selected" : "" ?>>Special</option>
            </select>
          </label>
          <label class="text-[10px] font-bold uppercase tracking-[0.18em] text-on-surface-variant">Año
            <input class="mt-2 w-full rounded-full border border-outline-variant/20 bg-surface-container-highest px-4 py-3 text-sm text-on-surface placeholder:text-outline focus:border-primary focus:ring-0" placeholder="Ej: 2022" type="text" data-admin-filter-year-input value="<?= htmlspecialchars($yearFilter) ?>"/>
          </label>
        </div>
      </div>
    </div>

    <div class="overflow-hidden rounded-[30px] border border-outline-variant/10 bg-surface-container-low shadow-2xl">
      <div class="max-w-full overflow-x-auto">
        <table class="w-full min-w-0 border-collapse text-left table-fixed">
          <thead>
            <tr class="bg-surface-container-high/50 text-[10px] font-bold uppercase tracking-[0.24em] text-on-surface-variant">
              <th class="px-6 py-5 whitespace-nowrap">ID</th>
              <th class="px-1 py-5">Serie</th>
              <th class="px-6 py-5">Estudio</th>
              <th class="px-6 py-5 whitespace-nowrap text-center">Fecha De Estreno</th>
              <th class="px-5 py-5 whitespace-nowrap text-center">Estado</th>
              <th class="px-6 py-5 text-right whitespace-nowrap">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-outline-variant/10" data-admin-manage-body>
            <?php foreach ($animes as $a): ?>
              <?php
                $estadoRaw = trim((string) ($a['estado'] ?? ''));
                $estadoLower = strtolower($estadoRaw);
                if ($estadoLower === 'finished airing') {
                    $estadoLabel = 'FINALIZADO';
                } elseif ($estadoLower === 'currently airing' || $estadoLower === 'en emision') {
                    $estadoLabel = 'EN EMISION';
                } elseif ($estadoLower === 'not yet aired') {
                    $estadoLabel = 'PROXIMAMENTE';
                } elseif ($estadoRaw !== '') {
                    $estadoLabel = strtoupper($estadoRaw);
                } else {
                    $estadoLabel = 'DESCONOCIDO';
                }
                $isAiring = in_array($estadoLower, ['en emision', 'currently airing'], true);
                $statusClass = $isAiring ? 'bg-primary/10 text-primary' : 'bg-on-surface-variant/10 text-on-surface-variant';
                $estudioValue = trim((string) ($a['estudio'] ?? ''));
                $estudioLabel = $estudioValue !== '' ? $estudioValue : 'Sin dato';
              ?>
              <tr class="group transition-colors hover:bg-surface-container-high/30" data-anime-id="<?= (int) $a['id'] ?>" data-mal-id="<?= (int) ($a['mal_id'] ?? 0) ?>" data-anime-title="<?= htmlspecialchars((string) ($a['titulo'] ?? 'Sin titulo'), ENT_QUOTES) ?>" data-anime-type="<?= htmlspecialchars((string) ($a['tipo'] ?? 'Anime'), ENT_QUOTES) ?>" data-anime-studio="<?= htmlspecialchars($estudioLabel, ENT_QUOTES) ?>" data-anime-year="<?= htmlspecialchars((string) ($a['anio'] ?? ''), ENT_QUOTES) ?>" data-anime-status="<?= htmlspecialchars($estadoLabel, ENT_QUOTES) ?>" data-anime-image="<?= htmlspecialchars((string) ($a['imagen_url'] ?? ''), ENT_QUOTES) ?>" data-anime-synopsis="<?= htmlspecialchars((string) ($a['sinopsis'] ?? ''), ENT_QUOTES) ?>" data-anime-season="<?= htmlspecialchars((string) ($a['temporada'] ?? ''), ENT_QUOTES) ?>" data-anime-episodes="<?= htmlspecialchars((string) ($a['episodios'] ?? ''), ENT_QUOTES) ?>">
                <td class="px-6 py-5 font-mono text-xs text-primary/70 whitespace-nowrap">#AE-<?= str_pad((string) $a['id'], 4, '0', STR_PAD_LEFT) ?></td>
                <td class="pl-0 pr-4 py-5">
                  <div class="flex items-center gap-3 min-w-0 -ml-8">
                    <div class="h-20 w-14 min-w-[3.5rem] overflow-hidden rounded-md bg-surface-container-highest flex-shrink-0">
                      <img alt="<?= htmlspecialchars((string) $a['titulo']) ?>" class="h-full w-full object-cover object-center" src="<?= htmlspecialchars((string) ($a['imagen_url'] ?? '')) ?>" onerror="this.style.display='none'; this.parentElement.querySelector('[data-admin-cover-fallback]').classList.remove('hidden');"/>
                      <div class="hidden h-full w-full items-center justify-center bg-surface-container-high text-[10px] font-bold uppercase tracking-[0.18em] text-on-surface-variant" data-admin-cover-fallback>Sin portada</div>
                    </div>
                    <div class="min-w-0 ml-1">
                      <p class="font-bold leading-tight text-on-surface break-words"><?= htmlspecialchars((string) ($a['titulo'] ?? 'Sin titulo')) ?></p>
                      <span class="mt-2 inline-flex rounded-full border border-white/10 bg-surface-container-high px-3 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-on-surface-variant"><?= htmlspecialchars((string) ($a['tipo'] ?? 'Anime')) ?></span>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-5 text-sm text-on-surface-variant break-words" data-admin-studio-cell><?= htmlspecialchars($estudioLabel) ?></td>
                <td class="px-6 py-5 text-center text-sm text-on-surface-variant whitespace-nowrap"><?= htmlspecialchars((string) ($a['anio'] ?? 'N/A')) ?></td>
                <td class="px-5 py-5 whitespace-nowrap text-center"><span class="inline-flex whitespace-nowrap rounded-full px-3 py-1 text-[10px] font-bold uppercase <?= $statusClass ?>"><?= htmlspecialchars($estadoLabel) ?></span></td>
                <td class="px-6 py-5 text-right whitespace-nowrap">
                  <div class="flex justify-end gap-3">
                    <button class="flex h-9 w-9 items-center justify-center rounded-full bg-surface-container-high text-on-surface-variant transition hover:bg-primary/20 hover:text-primary" data-admin-edit>
                      <span class="material-symbols-outlined text-lg">edit</span>
                    </button>
                    <button class="flex h-9 w-9 items-center justify-center rounded-full bg-surface-container-high text-on-surface-variant transition hover:bg-error/20 hover:text-error" data-admin-delete>
                      <span class="material-symbols-outlined text-lg">delete</span>
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($animes)): ?>
              <tr data-admin-empty-row>
                <td colspan="6" class="px-8 py-14 text-center text-sm text-on-surface-variant">No hay animes registrados por ahora.</td>
              </tr>
            <?php else: ?>
              <tr class="hidden" data-admin-empty-row>
                <td colspan="6" class="px-8 py-14 text-center text-sm text-on-surface-variant">No hay resultados con los filtros actuales.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <div class="flex items-center justify-between border-t border-outline-variant/10 bg-surface-container-high/20 px-8 py-6">
        <p class="text-xs text-on-surface-variant" data-admin-manage-footer>Mostrando <span class="font-bold text-on-surface" data-admin-manage-total><?= $rangeEnd ?></span> de <span class="font-bold text-on-surface"><?= $totalAnimes ?></span> títulos</p>
        <div class="flex items-center gap-2">
          <a class="flex h-10 min-w-10 items-center justify-center rounded-full border border-outline-variant/20 px-3 text-xs font-bold text-on-surface-variant transition <?= $page <= 1 ? 'pointer-events-none opacity-30' : 'hover:border-primary hover:text-primary' ?>" href="<?= htmlspecialchars($buildPageUrl(max(1, $page - 1))) ?>">&lt;</a>
          <?php for ($i = $pageStart; $i <= $pageEnd; $i++): ?>
            <?php if ($i === $page): ?>
              <span class="flex h-10 min-w-10 items-center justify-center rounded-full bg-primary px-3 text-xs font-bold text-on-primary"><?= $i ?></span>
            <?php else: ?>
              <a class="flex h-10 min-w-10 items-center justify-center rounded-full border border-outline-variant/20 px-3 text-xs font-bold text-on-surface-variant transition hover:border-primary hover:text-primary" href="<?= htmlspecialchars($buildPageUrl($i)) ?>"><?= $i ?></a>
            <?php endif; ?>
          <?php endfor; ?>
          <a class="flex h-10 min-w-10 items-center justify-center rounded-full border border-outline-variant/20 px-3 text-xs font-bold text-on-surface-variant transition <?= $page >= $totalPages ? 'pointer-events-none opacity-30' : 'hover:border-primary hover:text-primary' ?>" href="<?= htmlspecialchars($buildPageUrl(min($totalPages, $page + 1))) ?>">&gt;</a>
        </div>
      </div>
    </div>
  </section>
</main>

<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm" data-admin-edit-modal>
  <div class="w-[min(720px,92vw)] rounded-3xl border border-outline-variant/20 bg-surface-container-low p-6 shadow-2xl">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-[10px] uppercase tracking-[0.3em] text-on-surface-variant">Editar título</p>
        <h3 class="font-headline text-2xl font-bold text-on-surface">Detalles del anime</h3>
      </div>
      <button class="flex h-9 w-9 items-center justify-center rounded-full bg-surface-container-high text-on-surface-variant hover:text-on-surface" data-admin-edit-close>
        <span class="material-symbols-outlined text-lg">close</span>
      </button>
    </div>
    <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
      <label class="text-xs text-on-surface-variant">ID
        <input class="mt-2 w-full rounded-xl border border-outline-variant/20 bg-surface-container-highest px-4 py-2 text-sm text-on-surface" data-admin-field="id" readonly/>
      </label>
      <label class="text-xs text-on-surface-variant">Título
        <input class="mt-2 w-full rounded-xl border border-outline-variant/20 bg-surface-container-highest px-4 py-2 text-sm text-on-surface" data-admin-field="title"/>
      </label>
      <label class="text-xs text-on-surface-variant">Tipo
        <input class="mt-2 w-full rounded-xl border border-outline-variant/20 bg-surface-container-highest px-4 py-2 text-sm text-on-surface" data-admin-field="type"/>
      </label>
      <label class="text-xs text-on-surface-variant">Estudio
        <input class="mt-2 w-full rounded-xl border border-outline-variant/20 bg-surface-container-highest px-4 py-2 text-sm text-on-surface" data-admin-field="studio"/>
      </label>
      <label class="text-xs text-on-surface-variant">Temporada
        <input class="mt-2 w-full rounded-xl border border-outline-variant/20 bg-surface-container-highest px-4 py-2 text-sm text-on-surface" data-admin-field="season"/>
      </label>
      <label class="text-xs text-on-surface-variant">Episodios
        <input class="mt-2 w-full rounded-xl border border-outline-variant/20 bg-surface-container-highest px-4 py-2 text-sm text-on-surface" data-admin-field="episodes"/>
      </label>
      <label class="text-xs text-on-surface-variant">Año
        <input class="mt-2 w-full rounded-xl border border-outline-variant/20 bg-surface-container-highest px-4 py-2 text-sm text-on-surface" data-admin-field="date"/>
      </label>
      <label class="text-xs text-on-surface-variant">Estado
        <select class="mt-2 w-full rounded-xl border border-outline-variant/20 bg-surface-container-highest px-4 py-2 text-sm text-on-surface" data-admin-field="status">
          <option>EN EMISION</option>
          <option>FINALIZADO</option>
          <option>PROXIMAMENTE</option>
          <option>PAUSADO</option>
        </select>
      </label>
      <label class="text-xs text-on-surface-variant md:col-span-2">Sinopsis
        <textarea class="mt-2 min-h-[120px] w-full rounded-md border border-outline-variant/20 bg-surface-container-highest px-4 py-3 text-sm text-on-surface resize-none overflow-y-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden" data-admin-field="synopsis"></textarea>
      </label>
      <label class="text-xs text-on-surface-variant md:col-span-2">Portada (URL)
        <input class="mt-2 w-full rounded-xl border border-outline-variant/20 bg-surface-container-highest px-4 py-2 text-sm text-on-surface" data-admin-field="cover"/>
      </label>
    </div>
    <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
      <button class="rounded-full border border-error/40 px-4 py-2 text-xs uppercase tracking-widest text-error" data-admin-edit-delete>Eliminar título</button>
      <div class="flex gap-2">
        <button class="rounded-full border border-outline-variant/30 px-4 py-2 text-xs uppercase tracking-widest text-on-surface-variant" data-admin-edit-cancel>Cancelar</button>
        <button class="rounded-full bg-primary px-5 py-2 text-xs font-bold uppercase tracking-widest text-on-primary" data-admin-edit-save>Guardar cambios</button>
      </div>
    </div>
  </div>
</div>

<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm" data-admin-delete-modal>
  <div class="w-[min(460px,92vw)] rounded-3xl border border-outline-variant/20 bg-surface-container-low p-6 shadow-2xl">
    <div class="flex items-start justify-between gap-4">
      <div>
        <p class="text-[10px] uppercase tracking-[0.3em] text-on-surface-variant">Eliminar anime</p>
        <h3 class="mt-2 font-headline text-2xl font-bold text-on-surface">Confirmar eliminacion</h3>
        <p class="mt-3 text-sm text-on-surface-variant">Deseas eliminar para siempre <span class="font-semibold text-on-surface" data-admin-delete-name>este anime</span>? Esta acción también lo borra de la base de datos.</p>
      </div>
      <button class="flex h-9 w-9 items-center justify-center rounded-full bg-surface-container-high text-on-surface-variant hover:text-on-surface" data-admin-delete-cancel>
        <span class="material-symbols-outlined text-lg">close</span>
      </button>
    </div>
    <div class="mt-6 flex justify-end gap-3">
      <button class="rounded-full border border-outline-variant/30 px-4 py-2 text-xs uppercase tracking-widest text-on-surface-variant" data-admin-delete-cancel>Cancelar</button>
      <button class="rounded-full bg-gradient-to-r from-rose-400 to-rose-500 px-5 py-2 text-xs font-bold uppercase tracking-widest text-rose-950" data-admin-delete-confirm>Aceptar</button>
    </div>
  </div>
</div>

<script>
  (function () {
    const isReloadNavigation = () => {
      try {
        const navEntry = performance.getEntriesByType("navigation")?.[0];
        if (navEntry && navEntry.type) return navEntry.type === "reload";
        return performance.navigation?.type === 1;
      } catch {
        return false;
      }
    };

    if (isReloadNavigation() && window.location.search) {
      window.location.replace(window.location.pathname);
      return;
    }
    const input = document.querySelector('[data-admin-manage-search]');
    const statusSelect = document.querySelector('[data-admin-filter-status-select]');
    const typeSelect = document.querySelector('[data-admin-filter-type-select]');
    const yearInput = document.querySelector('[data-admin-filter-year-input]');
    const rows = Array.from(document.querySelectorAll('[data-admin-manage-body] tr')).filter((row) => !row.hasAttribute('data-admin-empty-row'));
    const missingStudioRows = rows.filter((row) => {
      const studioCell = row.querySelector('[data-admin-studio-cell]');
      return studioCell && studioCell.textContent.trim().toLowerCase() === 'sin dato' && Number(row.getAttribute('data-mal-id') || 0) > 0;
    });
    const emptyRow = document.querySelector('[data-admin-empty-row]');
    const modal = document.querySelector('[data-admin-edit-modal]');
    const deleteModal = document.querySelector('[data-admin-delete-modal]');
    const deleteName = document.querySelector('[data-admin-delete-name]');
    if (!modal || !deleteModal) return;

    let currentRow = null;
    let currentDeleteRow = null;

    const field = (name) => modal.querySelector(`[data-admin-field="${name}"]`);
    const closeModal = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); };
    const openModal = () => { modal.classList.remove('hidden'); modal.classList.add('flex'); };
    const closeDeleteModal = () => { deleteModal.classList.add('hidden'); deleteModal.classList.remove('flex'); currentDeleteRow = null; };
    const openDeleteModal = (row) => { currentDeleteRow = row; if (deleteName) deleteName.textContent = row?.getAttribute('data-anime-title') || 'este anime'; deleteModal.classList.remove('hidden'); deleteModal.classList.add('flex'); };

    const fetchAndSaveStudio = async (row) => {
      const animeId = Number(row.getAttribute('data-anime-id') || 0);
      const malId = Number(row.getAttribute('data-mal-id') || 0);
      const studioCell = row.querySelector('[data-admin-studio-cell]');
      if (!animeId || !malId || !studioCell) return;
      if (row.dataset.studioLoading === '1') return;
      row.dataset.studioLoading = '1';
      try {
        const res = await fetch(`api/jikan_proxy.php?endpoint=${encodeURIComponent(`anime/${malId}/full`)}`);
        if (!res.ok) return;
        const json = await res.json();
        const studios = Array.isArray(json?.data?.studios) ? json.data.studios : [];
        const studioNames = studios.map((studio) => (studio?.name || '').trim()).filter(Boolean);
        if (!studioNames.length) return;
        const estudio = studioNames.join(', ');
        studioCell.textContent = estudio;
        await fetch('api/admin.php?action=update_studio', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: animeId, estudio })
        });
      } catch (error) {
        console.error(error);
      } finally {
        row.dataset.studioLoading = '0';
      }
    };

    const backfillVisibleStudios = () => {
      missingStudioRows.forEach((row) => {
        if (row.style.display === 'none') return;
        const studioCell = row.querySelector('[data-admin-studio-cell]');
        if (!studioCell || studioCell.textContent.trim().toLowerCase() !== 'sin dato') return;
        fetchAndSaveStudio(row);
      });
    };


    const submitFilters = () => {
      const params = new URLSearchParams();
      const term = (input?.value || '').trim();
      const status = statusSelect?.value || 'ALL';
      const type = typeSelect?.value || 'ALL';
      const year = (yearInput?.value || '').trim();
      if (term) params.set('q', term);
      if (status && status !== 'ALL') params.set('status', status);
      if (type && type !== 'ALL') params.set('type', type);
      if (year) params.set('year', year);
      window.location.search = params.toString();
    };

    let searchTimer = null;
    if (input) input.addEventListener('input', () => {
      clearTimeout(searchTimer);
      searchTimer = setTimeout(submitFilters, 250);
    });

    if (statusSelect) statusSelect.addEventListener('change', submitFilters);

    if (typeSelect) typeSelect.addEventListener('change', submitFilters);

    let yearTimer = null;
    if (yearInput) {
      yearInput.addEventListener('input', () => {
        clearTimeout(yearTimer);
        yearTimer = setTimeout(submitFilters, 250);
      });
    }

    const clearBtn = document.querySelector('[data-admin-filter-clear]');
    if (clearBtn) {
      clearBtn.addEventListener('click', () => {
        window.location.search = '';
      });
    }

    document.addEventListener('click', (e) => {
      const editBtn = e.target.closest('[data-admin-edit]');
      const deleteBtn = e.target.closest('[data-admin-delete]');

      if (deleteBtn) {
        const row = deleteBtn.closest('tr');
        if (row) openDeleteModal(row);
        return;
      }

      if (!editBtn) return;
      const row = editBtn.closest('tr');
      if (!row) return;
      currentRow = row;
      const cells = row.querySelectorAll('td');
      if (cells[0]) field('id').value = row.getAttribute('data-anime-id') || cells[0].textContent.trim();
      if (cells[1]) {
        const title = cells[1].querySelector('p.font-bold');
        const type = row.getAttribute('data-anime-type') || '';
        const img = row.getAttribute('data-anime-image') || '';
        if (title) field('title').value = title.textContent.trim();
        field('type').value = type.trim();
        field('cover').value = img;
      }
      field('studio').value = row.getAttribute('data-anime-studio') || '';
      field('date').value = row.getAttribute('data-anime-year') || '';
      field('status').value = (row.getAttribute('data-anime-status') || 'EN EMISION').trim();
      field('synopsis').value = row.getAttribute('data-anime-synopsis') || '';
      field('season').value = row.getAttribute('data-anime-season') || '';
      field('episodes').value = row.getAttribute('data-anime-episodes') || '';
      openModal();
    });

    modal.addEventListener('click', (e) => {
      if (e.target === modal) closeModal();
    });

    const closeBtn = modal.querySelector('[data-admin-edit-close]');
    const cancelBtn = modal.querySelector('[data-admin-edit-cancel]');
    const saveBtn = modal.querySelector('[data-admin-edit-save]');
    const deleteInModal = modal.querySelector('[data-admin-edit-delete]');

    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

    if (saveBtn) {
      saveBtn.addEventListener('click', async () => {
        if (!currentRow) return;
        const animeId = Number(currentRow.getAttribute('data-anime-id') || field('id').value || 0);
        const payload = {
          id: animeId,
          título: field('title').value.trim(),
          tipo: field('type').value.trim(),
          estudio: field('studio').value.trim(),
          anio: field('date').value.trim(),
          estado: field('status').value.trim(),
          imagen_url: field('cover').value.trim(),
          sinopsis: field('synopsis').value.trim(),
          temporada: field('season').value.trim(),
          episodios: field('episodes').value.trim()
        };

        try {
          const res = await fetch('api/admin.php?action=update_anime', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
          });
          const json = await res.json();
          if (!json.success) throw new Error(json.error || 'No se pudo guardar');
          window.location.reload();
        } catch (error) {
          console.error(error);
          alert('No se pudo guardar el anime.');
        }
      });
    }

    if (deleteInModal) {
      deleteInModal.addEventListener('click', () => {
        if (currentRow) {
          closeModal();
          openDeleteModal(currentRow);
        }
      });
    }

    deleteModal.addEventListener('click', (e) => {
      if (e.target === deleteModal) {
        closeDeleteModal();
        return;
      }

      if (e.target.closest('[data-admin-delete-cancel]')) {
        closeDeleteModal();
      }
    });

    const confirmDeleteBtn = deleteModal.querySelector('[data-admin-delete-confirm]');
    if (confirmDeleteBtn) {
      confirmDeleteBtn.addEventListener('click', async () => {
        if (!currentDeleteRow) return;
        const animeId = Number(currentDeleteRow.getAttribute('data-anime-id') || 0);

        try {
          const res = await fetch('api/admin.php?action=delete_anime', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: animeId })
          });
          const json = await res.json();
          if (!json.success) throw new Error(json.error || 'No se pudo eliminar');
          closeDeleteModal();
          window.location.reload();
        } catch (error) {
          console.error(error);
          alert('No se pudo eliminar el anime.');
        }
      });
    }

    backfillVisibleStudios();
  })();
</script>
<script src="assets/js/admin-layout.js?v=20260330a"></script>
</body>
</html>




