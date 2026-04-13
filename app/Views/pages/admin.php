<!DOCTYPE html>

<html class="dark" lang="en"><head>
<link rel="icon" href="img/icon3.webp" />
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>NekoraList - Solicitudes</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
              "tertiary-fixed-dim": "#cecfef",
              "error-container": "#7f2737",
              "on-tertiary-fixed-variant": "#565873",
              "surface-container-low": "#131313",
              "tertiary-container": "#ddddfe",
              "on-primary": "#4800bf",
              "surface-container": "#191a1a",
              "inverse-primary": "#6834eb",
              "on-error-container": "#ff97a3",
              "on-secondary-fixed": "#403e42",
              "on-secondary": "#211f23",
              "background": "#0e0e0e",
              "primary-container": "#4f00d0",
              "outline-variant": "#484848",
              "tertiary": "#edecff",
              "on-primary-fixed-variant": "#652fe7",
              "on-secondary-fixed-variant": "#5d5b5f",
              "surface-tint": "#cdbdff",
              "on-tertiary-container": "#4c4e68",
              "inverse-on-surface": "#565554",
              "surface-bright": "#2c2c2c",
              "error": "#ec7c8a",
              "secondary-fixed": "#e6e1e6",
              "primary-fixed-dim": "#dacdff",
              "surface-variant": "#252626",
              "surface": "#0e0e0e",
              "on-error": "#490013",
              "surface-container-highest": "#252626",
              "primary-dim": "#c0adff",
              "on-tertiary-fixed": "#3a3c55",
              "secondary": "#a09da1",
              "inverse-surface": "#fcf8f8",
              "on-surface": "#e7e5e4",
              "surface-dim": "#0e0e0e",
              "on-secondary-container": "#c2bec3"
            },
            fontFamily: {
              "headline": ["Manrope"],
              "body": ["Inter"],
              "label": ["Inter"]
            },
            borderRadius: {"DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px"},
          },
        },
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
  .editorial-shadow {
    box-shadow: 0px 20px 40px rgba(0, 0, 0, 0.4);
  }
</style>


<script src="assets/js/shared-utils.js?v=20260408a" defer></script>
<script src="assets/js/admin-layout.js?v=20260330a" defer></script>
</head>
<body class="overflow-x-hidden" data-admin-page="requests">
<!-- SideNavBar Component -->
<?php include __DIR__ . '/../partials/admin-layout.php'; ?>
<main class="ml-64 min-h-screen">
<!-- TopAppBar Component -->
<header class="fixed top-0 right-0 w-[calc(100%-16rem)] z-40 bg-[#0e0e0e]/70 backdrop-blur-xl flex justify-between items-center h-20 px-12">
<link rel="icon" href="img/icon3.webp" />
<h1 class="font-headline font-semibold text-lg text-on-surface">Solicitudes de Anime</h1>
<div class="flex items-center gap-8">
<!-- Search Bar -->
<div class="relative group">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm" data-icon="search">search</span>
<input class="bg-surface-container-lowest text-on-surface text-sm rounded-full py-2.5 pl-12 pr-6 w-64 border-none focus:ring-1 ring-primary/20 transition-all placeholder:text-on-surface-variant/50" placeholder="Buscar solicitudes..." data-admin-search type="text"/>
</div>
</div>
<!-- Trailing Actions -->
</header>
<!-- Dashboard Canvas -->
<div class="pt-28 px-12 pb-12">
<!-- Welcome/Stats Bento -->
<section class="grid grid-cols-12 gap-6 mb-12">
<div class="col-span-8 bg-surface-container-low p-8 rounded-lg flex justify-between items-end relative overflow-hidden group" data-admin-queue>
<div class="relative z-10">
<h2 class="font-headline text-4xl font-extrabold mb-2 text-on-surface tracking-tighter">Resumen de la Cola</h2>
<p class="text-on-surface-variant text-lg font-body max-w-md">Tienes <span class="text-primary font-bold" data-admin-pending-text>0 solicitudes pendientes</span> esperando revisi&oacute;n hoy. Mant&eacute;n el cat&aacute;logo actualizado.</p>
</div>
<div class="relative z-10 text-right">
<span class="block text-5xl font-extrabold font-headline text-primary-container/40" data-admin-pending-count>0</span>
<span class="text-[10px] uppercase tracking-[0.2em] font-bold text-on-surface-variant">PENDIENTES TOTAL</span>
</div>
<!-- Decorative Bleed Image -->
<div class="absolute -right-10 -bottom-10 w-64 h-64 opacity-10 group-hover:opacity-20 transition-opacity">
<img alt="Decorative Cinema" class="w-full h-full object-contain" data-alt="Abstract cinematic film reel with light leaks and purple neon glow in a dark studio setting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAE4jby1eBcNnMnbOqDXjXXE5c4ZNhhUlyI9J1pzEy3d_EDkNdqv_hB-LpDCk-LhHWfrFVl-CoVKsNy-dy_2NrgYrfZzZmy3jyXJBoFNUFOpSP134uB53-AA9tH5p45TuM_P2lXruPSTxcKkcOvgXTlic1NBHDzoxjV_qw_S7OpGoP8sz04h5TuxAMWLy8hpxf8P3e5x7ZDW61Xd_NuCcfBo-1LmypNMLbMfLveDtD4qZX5kFSZBU63sxbSVemj99wX8zgZZkTqccjh"/>
</div>
</div>
<div class="col-span-4 bg-primary-container p-8 rounded-lg flex flex-col justify-between shadow-xl shadow-primary-container/10 cursor-pointer" data-admin-quick-action>
<span class="material-symbols-outlined text-on-primary-container text-4xl" data-icon="auto_awesome">auto_awesome</span>
<div>
<h3 class="font-headline font-bold text-on-primary-container text-xl uppercase tracking-tight">ACCI&Oacute;N R&Aacute;PIDA</h3>
<p class="text-on-primary-container/70 text-sm mt-1">Aprobar autom&aacute;ticamente solicitudes de Curadores de Confianza.</p>
</div>
</div>
<div class="col-span-12 flex justify-end mt-4">
  <div class="ml-auto flex gap-2">
    <button class="px-4 py-2 rounded-full border border-emerald-400/30 bg-gradient-to-r from-emerald-300 to-emerald-500 text-[10px] font-bold uppercase tracking-widest text-emerald-950 hover:brightness-110 transition-colors" data-admin-filter-approved>Ver aprobadas</button>
    <button class="px-4 py-2 rounded-full border border-rose-400/30 bg-gradient-to-r from-rose-300 to-rose-500 text-[10px] font-bold uppercase tracking-widest text-rose-950 hover:brightness-110 transition-colors" data-admin-filter-rejected>Ver rechazadas</button>
  </div>
</div>
</section>

<!-- Requests Table Container -->
<section class="bg-surface-container-low rounded-lg p-1">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="text-on-surface-variant text-[10px] uppercase tracking-[0.2em] font-bold border-b border-outline-variant/10">
<th class="px-8 py-6">USUARIO</th>
<th class="px-8 py-6">TIPO</th>
<th class="px-8 py-6">T&Iacute;TULO SOLICITADO</th>
<th class="px-8 py-6">FECHA DE ENV&Iacute;O</th>
<th class="px-8 py-6 text-right">ACCIONES</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/5"><tr><td colspan="5" class="px-8 py-10 text-center text-on-surface-variant" data-admin-empty>No hay solicitudes pendientes.</td></tr></tbody>
</table>
</div>
<!-- Table Footer / Pagination -->
<div class="mt-3 px-8 py-6 flex justify-between items-center bg-surface-container-low border-t border-outline-variant/10">
<p class="text-xs text-on-surface-variant font-body" data-admin-pending-footer>Mostrando 0 de 0 solicitudes pendientes</p>
<div class="flex items-center gap-2">
<button class="w-8 h-8 rounded-full border border-outline-variant/20 flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors disabled:opacity-20" data-admin-page-prev>
<span class="material-symbols-outlined text-sm" data-icon="chevron_left">chevron_left</span>
</button>
<button class="w-8 h-8 rounded-full border border-outline-variant/20 flex items-center justify-center text-on-surface hover:text-primary hover:border-primary transition-colors" data-admin-page-next>
<span class="material-symbols-outlined text-sm" data-icon="chevron_right">chevron_right</span>
</button>
</div>
</div>
</section>

<!-- Approved Modal -->
<div id="admin-approved-modal" class="fixed inset-0 z-[80] hidden items-center justify-center p-6">
  <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" data-admin-approved-close></div>
  <div class="relative flex h-[92vh] w-[96%] max-w-6xl flex-col rounded-3xl border border-white/10 bg-surface-container-low p-6 shadow-[0_30px_80px_rgba(0,0,0,0.55)]">
    <div class="flex items-start justify-between mb-4">
      <div>
        <h3 class="font-headline text-2xl font-bold">Solicitudes Aprobadas</h3>
        <p class="text-sm text-on-surface-variant">Listado de solicitudes aprobadas</p>
      </div>
      <div class="flex items-center gap-2">
        <button class="rounded-full bg-surface-container-high w-9 h-9 flex items-center justify-center text-on-surface-variant hover:text-on-surface" type="button" data-admin-approved-search-toggle>
          <span class="material-symbols-outlined text-[18px]">search</span>
        </button>
        <input class="hidden bg-surface-container-low text-on-surface text-xs rounded-full py-2 px-4 w-64 border-none focus:ring-1 ring-primary/20 transition-all placeholder:text-on-surface-variant/50" placeholder="Buscar (tí­tulo o fecha)..." data-admin-approved-search />
        <button class="rounded-full bg-surface-container-high w-9 h-9 flex items-center justify-center text-on-surface-variant hover:text-on-surface" type="button" data-admin-approved-close>
          <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
      </div>
    </div><div class="min-h-0 flex-1 rounded-2xl border border-white/10 bg-surface-container-low/70 p-3 overflow-hidden">
      <div class="w-full" data-admin-approved-list></div>
    </div>
    <div class="mt-3 flex items-center justify-between border-t border-outline-variant/10 px-6 py-4" data-admin-approved-footer>
      <p class="text-xs text-on-surface-variant font-body" data-admin-approved-footer-text>Mostrando 0 de 0 solicitudes aprobadas</p>
      <div class="flex items-center gap-2">
        <button class="w-8 h-8 rounded-full border border-outline-variant/20 flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors disabled:opacity-20" data-admin-approved-prev>
          <span class="material-symbols-outlined text-sm">chevron_left</span>
        </button>
        <button class="w-8 h-8 rounded-full border border-outline-variant/20 flex items-center justify-center text-on-surface hover:text-primary hover:border-primary transition-colors" data-admin-approved-next>
          <span class="material-symbols-outlined text-sm">chevron_right</span>
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Rejected Modal -->
<div id="admin-rejected-modal" class="fixed inset-0 z-[80] hidden items-center justify-center p-6">
  <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" data-admin-rejected-close></div>
  <div class="relative flex h-[92vh] w-[96%] max-w-6xl flex-col rounded-3xl border border-white/10 bg-surface-container-low p-6 shadow-[0_30px_80px_rgba(0,0,0,0.55)]">
    <div class="flex items-start justify-between mb-4">
      <div>
        <h3 class="font-headline text-2xl font-bold">Solicitudes Rechazadas</h3>
        <p class="text-sm text-on-surface-variant">Listado de solicitudes rechazadas</p>
      </div>
      <div class="flex items-center gap-2">
        <button class="rounded-full bg-surface-container-high w-9 h-9 flex items-center justify-center text-on-surface-variant hover:text-on-surface" type="button" data-admin-rejected-search-toggle>
          <span class="material-symbols-outlined text-[18px]">search</span>
        </button>
        <input class="hidden bg-surface-container-low text-on-surface text-xs rounded-full py-2 px-4 w-64 border-none focus:ring-1 ring-primary/20 transition-all placeholder:text-on-surface-variant/50" placeholder="Buscar (título o fecha)..." data-admin-rejected-search />
        <button class="rounded-full bg-surface-container-high w-9 h-9 flex items-center justify-center text-on-surface-variant hover:text-on-surface" type="button" data-admin-rejected-close>
          <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
      </div>
    </div><div class="min-h-0 flex-1 rounded-2xl border border-white/10 bg-surface-container-low/70 p-3 overflow-hidden">
      <div class="w-full" data-admin-rejected-list></div>
    </div>
    <div class="mt-3 flex items-center justify-between border-t border-outline-variant/10 px-6 py-4" data-admin-rejected-footer>
      <p class="text-xs text-on-surface-variant font-body" data-admin-rejected-footer-text>Mostrando 0 de 0 solicitudes rechazadas</p>
      <div class="flex items-center gap-2">
        <button class="w-8 h-8 rounded-full border border-outline-variant/20 flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors disabled:opacity-20" data-admin-rejected-prev>
          <span class="material-symbols-outlined text-sm">chevron_left</span>
        </button>
        <button class="w-8 h-8 rounded-full border border-outline-variant/20 flex items-center justify-center text-on-surface hover:text-primary hover:border-primary transition-colors" data-admin-rejected-next>
          <span class="material-symbols-outlined text-sm">chevron_right</span>
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Confirm Modal -->
<div id="admin-delete-modal" class="fixed inset-0 z-[90] hidden items-center justify-center p-6">
  <div class="absolute inset-0 bg-black/75 backdrop-blur-sm" data-admin-delete-cancel></div>
  <div class="relative w-full max-w-md rounded-3xl border border-white/10 bg-surface-container-low p-6 shadow-[0_30px_80px_rgba(0,0,0,0.55)]">
    <div class="mb-4 flex items-center gap-3">
      <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-rose-400/20 bg-rose-500/10 text-rose-200">
        <span class="material-symbols-outlined text-[22px]">delete</span>
      </div>
      <div>
        <h3 class="font-headline text-xl font-bold text-on-surface">Eliminar peticion</h3>
        <p class="text-sm text-on-surface-variant">Esta acción no se puede deshacer.</p>
      </div>
    </div>
    <p class="mb-6 text-sm leading-6 text-on-surface-variant">Deseas eliminar para siempre esta peticion?</p>
    <div class="flex justify-end gap-3">
      <button type="button" class="rounded-full border border-white/10 bg-surface-container-high px-5 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-on-surface-variant transition hover:text-on-surface" data-admin-delete-cancel>Cancelar</button>
      <button type="button" class="rounded-full border border-rose-400/20 bg-gradient-to-r from-rose-400 to-rose-500 px-5 py-2 text-xs font-bold uppercase tracking-[0.18em] text-rose-950 transition hover:brightness-110" data-admin-delete-confirm>Eliminar</button>
    </div>
  </div>
</div>

<!-- Bottom Note -->

</div>
</main>

<script data-admin-pagination-script>
(function () {
  function initAdmin() {
    const requestsApi = "<?= asset_path('api/requests') ?>";
    const tbody = document.querySelector('tbody');
    if (!tbody) return;

    let page = 1;
    const size = 4;
    let total = 0;
    let items = [];
    let searchQuery = '';
    let statusFilter = 'pendiente';

    const approvedModal = document.getElementById('admin-approved-modal');
    const rejectedModal = document.getElementById('admin-rejected-modal');
    const deleteModal = document.getElementById('admin-delete-modal');
    const deleteConfirmBtn = deleteModal ? deleteModal.querySelector('[data-admin-delete-confirm]') : null;
    const deleteCancelEls = deleteModal ? deleteModal.querySelectorAll('[data-admin-delete-cancel]') : [];
    const approvedCloseEls = approvedModal ? approvedModal.querySelectorAll('[data-admin-approved-close]') : [];
    const rejectedCloseEls = rejectedModal ? rejectedModal.querySelectorAll('[data-admin-rejected-close]') : [];
    const approvedList = document.querySelector('[data-admin-approved-list]');
    const rejectedList = document.querySelector('[data-admin-rejected-list]');
    const approvedFooterText = document.querySelector('[data-admin-approved-footer-text]');
    const rejectedFooterText = document.querySelector('[data-admin-rejected-footer-text]');
    const approvedPrev = document.querySelector('[data-admin-approved-prev]');
    const approvedNext = document.querySelector('[data-admin-approved-next]');
    const rejectedPrev = document.querySelector('[data-admin-rejected-prev]');
    const rejectedNext = document.querySelector('[data-admin-rejected-next]');
    const approvedSearchInput = document.querySelector('[data-admin-approved-search]');
    const rejectedSearchInput = document.querySelector('[data-admin-rejected-search]');
    const approvedSearchToggle = document.querySelector('[data-admin-approved-search-toggle]');
    const rejectedSearchToggle = document.querySelector('[data-admin-rejected-search-toggle]');

    let approvedSearchQuery = '';
    let rejectedSearchQuery = '';
    const modalState = {
      aprobado: { page: 1, size: 4, total: 0 },
      rechazado: { page: 1, size: 4, total: 0 }
    };

    const emptyText = 'No hay solicitudes pendientes.';
    const emptyTextApproved = 'No hay solicitudes aprobadas.';
    const emptyTextRejected = 'No hay solicitudes rechazadas.';

    const formatDate = (dateStr) => {
      if (!dateStr) return '';
      const d = new Date(String(dateStr).replace(' ', 'T'));
      if (Number.isNaN(d.getTime())) return dateStr;
      return d.toLocaleString('es-EC', { dateStyle: 'medium', timeStyle: 'short' });
    };

    const enhanceModalSearch = (input, toggle) => {
      if (!input || !toggle) return null;
      input.classList.add('hidden');
      input.classList.remove('bg-surface-container-low', 'rounded-full', 'py-2', 'px-4', 'border-none', 'focus:ring-1', 'transition-all');
      input.classList.add('bg-transparent', 'text-on-surface', 'text-xs', 'w-64', 'border-none', 'p-0', 'focus:ring-0');

      const wrap = document.createElement('div');
      wrap.className = 'hidden items-center gap-2 rounded-full bg-surface-container-low px-3 py-2 ring-1 ring-primary/20';

      const clearBtn = document.createElement('button');
      clearBtn.type = 'button';
      clearBtn.className = 'hidden text-on-surface-variant hover:text-on-surface text-sm font-bold leading-none';
      clearBtn.textContent = 'x';

      const parent = input.parentElement;
      parent.insertBefore(wrap, input);
      wrap.appendChild(input);
      wrap.appendChild(clearBtn);

      return { input, toggle, wrap, clearBtn };
    };

    const approvedSearchEls = enhanceModalSearch(approvedSearchInput, approvedSearchToggle);
    const rejectedSearchEls = enhanceModalSearch(rejectedSearchInput, rejectedSearchToggle);

    const setSearchVisibility = (searchEls, visible) => {
      if (!searchEls) return;
      searchEls.wrap.classList.toggle('hidden', !visible);
      searchEls.wrap.classList.toggle('flex', visible);
      searchEls.input.classList.toggle('hidden', !visible);
      if (!visible) searchEls.input.blur();
    };

    const syncSearchClear = (searchEls) => {
      if (!searchEls) return;
      const hasValue = !!(searchEls.input.value || '').trim();
      searchEls.clearBtn.classList.toggle('hidden', !hasValue);
      searchEls.clearBtn.classList.toggle('inline-flex', hasValue);
    };

    const resetModalSearch = (searchEls, setQuery) => {
      if (!searchEls) return false;
      const hadValue = !!(searchEls.input.value || '').trim();
      searchEls.input.value = '';
      setQuery('');
      syncSearchClear(searchEls);
      setSearchVisibility(searchEls, false);
      return hadValue;
    };

    const confirmDelete = () => new Promise((resolve) => {
      if (!deleteModal || !deleteConfirmBtn) {
        resolve(false);
        return;
      }

      deleteModal.classList.remove('hidden');
      deleteModal.classList.add('flex');

      const cleanup = () => {
        deleteModal.classList.add('hidden');
        deleteModal.classList.remove('flex');
        deleteConfirmBtn.removeEventListener('click', onConfirm);
        deleteCancelEls.forEach((el) => el.removeEventListener('click', onCancel));
      };

      const onConfirm = () => {
        cleanup();
        resolve(true);
      };

      const onCancel = () => {
        cleanup();
        resolve(false);
      };

      deleteConfirmBtn.addEventListener('click', onConfirm);
      deleteCancelEls.forEach((el) => el.addEventListener('click', onCancel));
    });

    const requestDelete = async (id) => {
      const confirmed = await confirmDelete();
      if (!confirmed) return false;
      const res = await fetch(`${requestsApi}?action=delete`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
      });
      const json = await res.json();
      if (!json.success) throw new Error(json.error || 'No se pudo eliminar la peticion');
      return true;
    };

    async function loadData() {
      const params = new URLSearchParams({
        action: 'list',
        status: statusFilter,
        page: String(page),
        size: String(size)
      });
      if (searchQuery) params.set('q', searchQuery);

      try {
        const res = await fetch(`${requestsApi}?${params.toString()}`);
        const json = await res.json();
        if (!json.success) throw new Error(json.error || 'Error al cargar solicitudes');
        total = json.total || 0;
        items = json.items || [];
      } catch (err) {
        total = 0;
        items = [];
        console.error(err);
      }

      render();
    }

    function render() {
      const start = (page - 1) * size;
      let html = '';

      items.forEach((item, idx) => {
        const n = start + idx + 1;
        html += `
<tr class="group hover:bg-surface-container-high transition-colors" data-admin-request-row data-id="${item.id}">
  <td class="px-8 py-6">
    <div class="flex items-center gap-3">
      <div class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center text-[10px] font-bold text-primary">${String(n).padStart(2, '0')}</div>
      <div>
        <p class="text-sm font-semibold text-on-surface">${item.user_display || 'Usuario'}</p>
      </div>
    </div>
  </td>
  <td class="px-8 py-6">
    <span class="inline-flex min-w-[92px] justify-center rounded-full border border-white/10 bg-surface-container-high px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-on-surface">${item.tipo || 'Anime'}</span>
  </td>
  <td class="px-8 py-6">
    <p class="text-base font-bold font-headline text-on-surface tracking-tight">${item.título || ''}</p>
    <p class="text-xs text-on-surface-variant italic">Source: ${item.fuente || 'Sin fuente'}</p>
  </td>
  <td class="px-6 py-6 whitespace-nowrap">
    <p class="text-xs text-on-surface-variant font-body whitespace-nowrap">${formatDate(item.creado_en)}</p>
  </td>
  <td class="px-8 py-6">
    <div class="flex justify-end items-center gap-3">
      <button class="w-10 h-10 rounded-full flex items-center justify-center border border-rose-400/15 bg-gradient-to-br from-surface-container-high to-surface-container-highest text-rose-200/80 hover:from-rose-500/20 hover:to-rose-400/10 hover:text-rose-100 transition-all" data-admin-delete>
        <span class="material-symbols-outlined text-[18px]">delete</span>
      </button>
      <button class="w-10 h-10 rounded-full flex items-center justify-center bg-surface-container-highest text-on-surface-variant hover:bg-surface-container-lowest hover:text-error transition-all" data-admin-reject>
        <span class="material-symbols-outlined text-sm">close</span>
      </button>
      <button class="px-5 py-2 rounded-full bg-surface-container-highest text-on-surface font-headline font-bold text-xs uppercase tracking-tight hover:bg-primary hover:text-on-primary transition-all flex items-center gap-2" data-admin-approve>
        <span class="material-symbols-outlined text-[18px]">check</span>
        APROBAR
      </button>
    </div>
  </td>
</tr>`;
      });

      const emptyByStatus = statusFilter === 'aprobado' ? emptyTextApproved : statusFilter === 'rechazado' ? emptyTextRejected : emptyText;
      tbody.innerHTML = html || `<tr><td colspan="5" class="px-8 py-10 text-center text-on-surface-variant" data-admin-empty>${emptyByStatus}</td></tr>`;

      const countEl = document.querySelector('[data-admin-pending-count]');
      const textEl = document.querySelector('[data-admin-pending-text]');
      const footerEl = document.querySelector('[data-admin-pending-footer]');
      const displayTotal = total || items.length;
      const shownCount = displayTotal ? Math.min(displayTotal, start + items.length) : 0;
      if (countEl) countEl.textContent = String(displayTotal);
      if (textEl) textEl.textContent = displayTotal ? `${displayTotal} solicitudes pendientes` : '0 solicitudes pendientes';
      if (footerEl) footerEl.textContent = displayTotal ? `Mostrando ${shownCount} de ${displayTotal} solicitudes pendientes` : 'Mostrando 0 de 0 solicitudes pendientes';

      const prev = document.querySelector('[data-admin-page-prev]');
      const next = document.querySelector('[data-admin-page-next]');
      const maxPage = Math.max(1, Math.ceil(total / size));
      if (prev) prev.disabled = page <= 1;
      if (next) next.disabled = page >= maxPage;

      const queueText = document.querySelector('[data-admin-queue] p');
      if (queueText) {
        queueText.innerHTML = `Tienes <span class="text-primary font-bold" data-admin-pending-text>${displayTotal} solicitudes pendientes</span> esperando revisi&oacute;n hoy. Mant&eacute;n el cat&aacute;logo actualizado.`;
      }
    }

    const renderModalList = (el, modalItems, emptyLabel, targetStatus) => {
      if (!el) return;
      if (!modalItems || modalItems.length === 0) {
        el.innerHTML = `<div class="px-8 py-10 text-center text-on-surface-variant">${emptyLabel}</div>`;
        return;
      }

      const rows = modalItems.map((item, idx) => {
        const user = item.user_display || 'Usuario';
        const tipo = item.tipo || 'Anime';
        const título = item.título || '';
        const fecha = formatDate(item.creado_en);
        const actionLabel = targetStatus === 'aprobado' ? 'APROBAR' : 'RECHAZAR';
        const actionClass = targetStatus === 'aprobado' ? 'bg-emerald-400/10 text-emerald-200 hover:bg-emerald-400/20' : 'bg-rose-400/10 text-rose-200 hover:bg-rose-400/20';

        return `
<tr class="group hover:bg-surface-container-high transition-colors" data-admin-modal-row data-id="${item.id}" data-target="${targetStatus}">
  <td class="px-8 py-6">
    <div class="flex items-center gap-3">
      <div class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center text-[10px] font-bold text-primary">${String(idx + 1).padStart(2, '0')}</div>
      <div>
        <p class="text-sm font-semibold text-on-surface">${user}</p>
      </div>
    </div>
  </td>
  <td class="px-8 py-6">
    <span class="inline-flex min-w-[92px] justify-center rounded-full border border-white/10 bg-surface-container-high px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-on-surface">${tipo}</span>
  </td>
  <td class="px-8 py-6">
    <p class="text-base font-bold font-headline text-on-surface tracking-tight">${título}</p>
  </td>
  <td class="px-6 py-6 whitespace-nowrap">
    <p class="text-xs text-on-surface-variant font-body whitespace-nowrap">${fecha}</p>
  </td>
  <td class="px-8 py-6">
    <div class="flex justify-end items-center gap-3">
      <button class="w-10 h-10 rounded-full flex items-center justify-center border border-rose-400/15 bg-gradient-to-br from-surface-container-high to-surface-container-highest text-rose-200/80 hover:from-rose-500/20 hover:to-rose-400/10 hover:text-rose-100 transition-all" data-admin-modal-delete>
        <span class="material-symbols-outlined text-[18px]">delete</span>
      </button>
      <button class="px-5 py-2 rounded-full bg-surface-container-highest text-on-surface font-headline font-bold text-xs uppercase tracking-tight transition-all flex items-center gap-2 ${actionClass}" data-admin-modal-action>${actionLabel}</button>
    </div>
  </td>
</tr>`;
      }).join('');

      el.innerHTML = `
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
  <thead>
    <tr class="text-on-surface-variant text-[10px] uppercase tracking-[0.2em] font-bold border-b border-outline-variant/10">
      <th class="px-8 py-6">USUARIO</th>
      <th class="px-8 py-6">TIPO</th>
      <th class="px-8 py-6">T&Iacute;TULO SOLICITADO</th>
      <th class="px-8 py-6">FECHA DE ENV&Iacute;O</th>
      <th class="px-8 py-6 text-right">ACCIONES</th>
    </tr>
  </thead>
  <tbody class="divide-y divide-outline-variant/5">${rows}</tbody>
</table>
</div>`;
    };

    const openApprovedModal = async (resetPage = false) => {
      if (!approvedModal) return;
      approvedModal.classList.remove('hidden');
      approvedModal.classList.add('flex');
      document.body.style.overflow = 'hidden';
      try {
        const state = modalState.aprobado;
        if (resetPage) state.page = 1;
        renderModalList(approvedList, [], 'Cargando solicitudes aprobadas...', 'rechazado');
        const res = await fetch(`${requestsApi}?action=list&status=aprobado&page=${state.page}&size=${state.size}&q=${encodeURIComponent(approvedSearchQuery)}`, { credentials: 'same-origin' });
        const json = await res.json();
        if (!json.success) throw new Error(json.error || 'No se pudieron cargar las solicitudes aprobadas.');
        state.total = json.total || 0;
        if (approvedFooterText) approvedFooterText.textContent = `Mostrando ${Math.min(state.total, state.page * state.size)} de ${state.total} solicitudes aprobadas`;
        renderModalList(approvedList, json.items || [], 'No hay solicitudes aprobadas.', 'rechazado');
      } catch (e) {
        console.error(e);
        renderModalList(approvedList, [], 'No se pudieron cargar las solicitudes aprobadas.', 'rechazado');
      }
    };

    const openRejectedModal = async (resetPage = false) => {
      if (!rejectedModal) return;
      rejectedModal.classList.remove('hidden');
      rejectedModal.classList.add('flex');
      document.body.style.overflow = 'hidden';
      try {
        const state = modalState.rechazado;
        if (resetPage) state.page = 1;
        renderModalList(rejectedList, [], 'Cargando solicitudes rechazadas...', 'aprobado');
        const res = await fetch(`${requestsApi}?action=list&status=rechazado&page=${state.page}&size=${state.size}&q=${encodeURIComponent(rejectedSearchQuery)}`, { credentials: 'same-origin' });
        const json = await res.json();
        if (!json.success) throw new Error(json.error || 'No se pudieron cargar las solicitudes rechazadas.');
        state.total = json.total || 0;
        if (rejectedFooterText) rejectedFooterText.textContent = `Mostrando ${Math.min(state.total, state.page * state.size)} de ${state.total} solicitudes rechazadas`;
        renderModalList(rejectedList, json.items || [], 'No hay solicitudes rechazadas.', 'aprobado');
      } catch (e) {
        console.error(e);
        renderModalList(rejectedList, [], 'No se pudieron cargar las solicitudes rechazadas.', 'aprobado');
      }
    };

    const closeApprovedModal = () => {
      if (!approvedModal) return;
      resetModalSearch(approvedSearchEls, (value) => { approvedSearchQuery = value; modalState.aprobado.page = 1; });
      approvedModal.classList.add('hidden');
      approvedModal.classList.remove('flex');
      document.body.style.overflow = '';
    };

    const closeRejectedModal = () => {
      if (!rejectedModal) return;
      resetModalSearch(rejectedSearchEls, (value) => { rejectedSearchQuery = value; modalState.rechazado.page = 1; });
      rejectedModal.classList.add('hidden');
      rejectedModal.classList.remove('flex');
      document.body.style.overflow = '';
    };

    const handleModalPager = (status, direction) => {
      const state = modalState[status];
      const maxPage = Math.max(1, Math.ceil((state.total || 0) / state.size));
      if (direction === 'prev' && state.page > 1) state.page -= 1;
      if (direction === 'next' && state.page < maxPage) state.page += 1;
      if (status === 'aprobado') openApprovedModal(false);
      if (status === 'rechazado') openRejectedModal(false);
    };

    const handleModalAction = async (e) => {
      const btn = e.target.closest('[data-admin-modal-action]');
      const deleteBtn = e.target.closest('[data-admin-modal-delete]');
      if (!btn && !deleteBtn) return;
      const row = e.target.closest('[data-admin-modal-row]');
      if (!row) return;
      const id = Number(row.getAttribute('data-id'));
      const target = row.getAttribute('data-target');
      if (!id) return;
      try {
        if (deleteBtn) {
          const deleted = await requestDelete(id);
          if (!deleted) return;
        } else {
          if (!target) return;
          await fetch(`${requestsApi}?action=decide`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, decision: target })
          });
        }
        if (approvedModal && !approvedModal.classList.contains('hidden')) await openApprovedModal(false);
        if (rejectedModal && !rejectedModal.classList.contains('hidden')) await openRejectedModal(false);
        loadData();
      } catch (err) {
        console.error(err);
      }
    };

    const toggleSearch = (searchEls) => {
      if (!searchEls) return;
      const show = searchEls.wrap.classList.contains('hidden');
      setSearchVisibility(searchEls, show);
      if (show) {
        searchEls.input.focus();
        searchEls.input.select();
      }
    };

    const prev = document.querySelector('[data-admin-page-prev]');
    const next = document.querySelector('[data-admin-page-next]');
    if (prev) prev.addEventListener('click', (e) => {
      e.preventDefault();
      if (page > 1) {
        page -= 1;
        loadData();
      }
    });
    if (next) next.addEventListener('click', (e) => {
      e.preventDefault();
      const maxPage = Math.max(1, Math.ceil(total / size));
      if (page < maxPage) {
        page += 1;
        loadData();
      }
    });

    tbody.addEventListener('click', async (e) => {
      const approveBtn = e.target.closest('[data-admin-approve]');
      const rejectBtn = e.target.closest('[data-admin-reject]');
      const deleteBtn = e.target.closest('[data-admin-delete]');
      if (!approveBtn && !rejectBtn && !deleteBtn) return;
      const row = e.target.closest('[data-admin-request-row]');
      if (!row) return;
      const id = Number(row.getAttribute('data-id'));
      if (!id) return;
      try {
        if (deleteBtn) {
          const deleted = await requestDelete(id);
          if (deleted) loadData();
          return;
        }
        const decision = approveBtn ? 'aprobado' : 'rechazado';
        await fetch(`${requestsApi}?action=decide`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id, decision })
        });
        loadData();
      } catch (err) {
        console.error(err);
      }
    });

    const quick = document.querySelector('[data-admin-quick-action]');
    if (quick) {
      quick.addEventListener('click', () => {
        fetch(`${requestsApi}?action=bulk_approve`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({})
        }).then(() => {
          page = 1;
          loadData();
        }).catch(console.error);
      });
    }

    const search = document.querySelector('[data-admin-search]');
    if (search) {
      let t = null;
      search.addEventListener('input', () => {
        clearTimeout(t);
        t = setTimeout(() => {
          searchQuery = (search.value || '').trim();
          page = 1;
          loadData();
        }, 300);
      });
    }

    approvedCloseEls.forEach((el) => el.addEventListener('click', closeApprovedModal));
    rejectedCloseEls.forEach((el) => el.addEventListener('click', closeRejectedModal));
    if (approvedList) approvedList.addEventListener('click', handleModalAction);
    if (rejectedList) rejectedList.addEventListener('click', handleModalAction);
    if (approvedPrev) approvedPrev.addEventListener('click', () => handleModalPager('aprobado', 'prev'));
    if (approvedNext) approvedNext.addEventListener('click', () => handleModalPager('aprobado', 'next'));
    if (rejectedPrev) rejectedPrev.addEventListener('click', () => handleModalPager('rechazado', 'prev'));
    if (rejectedNext) rejectedNext.addEventListener('click', () => handleModalPager('rechazado', 'next'));
    if (approvedSearchToggle) approvedSearchToggle.addEventListener('click', () => toggleSearch(approvedSearchEls));
    if (rejectedSearchToggle) rejectedSearchToggle.addEventListener('click', () => toggleSearch(rejectedSearchEls));

    if (approvedSearchEls) {
      approvedSearchEls.clearBtn.addEventListener('click', () => {
        const hadValue = resetModalSearch(approvedSearchEls, (value) => { approvedSearchQuery = value; modalState.aprobado.page = 1; });
        if (hadValue) openApprovedModal(true);
      });
      approvedSearchEls.input.addEventListener('blur', () => {
        if (!(approvedSearchEls.input.value || '').trim()) setSearchVisibility(approvedSearchEls, false);
      });
    }

    if (rejectedSearchEls) {
      rejectedSearchEls.clearBtn.addEventListener('click', () => {
        const hadValue = resetModalSearch(rejectedSearchEls, (value) => { rejectedSearchQuery = value; modalState.rechazado.page = 1; });
        if (hadValue) openRejectedModal(true);
      });
      rejectedSearchEls.input.addEventListener('blur', () => {
        if (!(rejectedSearchEls.input.value || '').trim()) setSearchVisibility(rejectedSearchEls, false);
      });
    }

    let tA = null;
    if (approvedSearchInput) {
      approvedSearchInput.addEventListener('input', () => {
        syncSearchClear(approvedSearchEls);
        clearTimeout(tA);
        tA = setTimeout(() => {
          approvedSearchQuery = (approvedSearchInput.value || '').trim();
          modalState.aprobado.page = 1;
          openApprovedModal(false);
        }, 300);
      });
    }

    let tR = null;
    if (rejectedSearchInput) {
      rejectedSearchInput.addEventListener('input', () => {
        syncSearchClear(rejectedSearchEls);
        clearTimeout(tR);
        tR = setTimeout(() => {
          rejectedSearchQuery = (rejectedSearchInput.value || '').trim();
          modalState.rechazado.page = 1;
          openRejectedModal(false);
        }, 300);
      });
    }

    const filterApproved = document.querySelector('[data-admin-filter-approved]');
    const filterRejected = document.querySelector('[data-admin-filter-rejected]');
    if (filterApproved) filterApproved.addEventListener('click', () => openApprovedModal(true));
    if (filterRejected) filterRejected.addEventListener('click', () => openRejectedModal(true));

    loadData();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAdmin);
  } else {
    initAdmin();
  }
})();
</script>
</body></html>







