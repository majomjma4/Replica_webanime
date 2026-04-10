<!DOCTYPE html>
<html class="dark" lang="es"><head>
<link rel="icon" href="img/icon3.png" />
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>NekoraList - Gestion de Comentarios</title>
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
              "on-background": "#e7e5e4",
              "tertiary-dim": "#cecfef",
              "primary": "#cdbdff",
              "surface-container-high": "#1f2020",
              "error-container": "#7f2737",
              "surface-container-low": "#131313",
              "tertiary-container": "#ddddfe",
              "on-primary": "#4800bf",
              "surface-container": "#191a1a",
              "error": "#ec7c8a",
              "primary-fixed-dim": "#dacdff",
              "surface-variant": "#252626",
              "surface": "#0e0e0e",
              "on-error": "#490013",
              "on-surface": "#e7e5e4"
            },
            fontFamily: {
              "headline": ["Manrope"],
              "body": ["Inter"]
            },
            borderRadius: {"DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px"},
          },
        },
      }
    </script>
<style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { background-color: #0e0e0e; color: #e7e5e4; font-family: 'Inter', sans-serif; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-background text-on-background selection:bg-primary-container selection:text-on-primary-container" data-admin-page="comments">
<div data-admin-sidebar></div>
<main class="ml-64 pt-28 px-12 pb-12 min-h-screen">
<div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
<div class="bg-surface-container-low p-6 rounded-lg border-l-4 border-primary shadow-lg">
<p class="text-on-surface-variant text-xs uppercase font-bold tracking-widest mb-1">TOTAL COMENTARIOS</p>
<h3 class="text-3xl font-headline font-extrabold tracking-tighter" id="stat-total">0</h3>
<p class="text-[10px] text-primary mt-2 flex items-center gap-1">
<span class="material-symbols-outlined text-xs">trending_up</span> Base instalada
</p>
</div>
<div class="bg-surface-container-low p-6 rounded-lg border-l-4 border-error shadow-lg">
<p class="text-on-surface-variant text-xs uppercase font-bold tracking-widest mb-1">CONTENIDO REPORTADO</p>
<h3 class="text-3xl font-headline font-extrabold tracking-tighter text-error" id="stat-flagged">0</h3>
<p class="text-[10px] text-on-surface-variant mt-2 italic">Moderacion requerida</p>
</div>
<button class="bg-surface-container-low p-6 rounded-lg border-l-4 border-amber-400 shadow-lg text-left transition hover:-translate-y-0.5 hover:border-amber-300/80 hover:bg-surface-container focus:outline-none focus:ring-2 focus:ring-amber-300/40" data-open-reviewed-modal type="button">
<p class="text-on-surface-variant text-xs uppercase font-bold tracking-widest mb-1">COMENTARIOS REVISADOS</p>
<h3 class="text-3xl font-headline font-extrabold tracking-tighter text-amber-300" id="stat-reviewed">0</h3>
<p class="text-[10px] text-on-surface-variant mt-2 italic">Ya gestionados en base de datos</p>
</button>
<button class="bg-surface-container-low p-6 rounded-lg border-l-4 border-rose-400 shadow-lg text-left transition hover:-translate-y-0.5 hover:border-rose-300/80 hover:bg-surface-container focus:outline-none focus:ring-2 focus:ring-rose-300/40" data-open-report-history-modal type="button">
<p class="text-on-surface-variant text-xs uppercase font-bold tracking-widest mb-1">HISTORIAL DE REPORTES</p>
<h3 class="text-3xl font-headline font-extrabold tracking-tighter text-rose-300" id="stat-report-history">0</h3>
<p class="text-[10px] text-on-surface-variant mt-2 italic">Comentarios marcados por usuarios</p>
</button>
</div>
<section class="bg-surface-container-low rounded-lg shadow-2xl overflow-hidden">
<div class="px-8 py-6 flex flex-wrap items-center justify-between gap-4 border-b border-outline/10">
<h2 class="font-headline font-bold text-xl tracking-tight">Interacciones Recientes</h2>
<div class="flex flex-wrap items-center gap-3">
<label class="flex items-center gap-3 rounded-full border border-outline/30 bg-surface-container px-4 py-3 min-w-[320px]">
<span class="material-symbols-outlined text-on-surface-variant">search</span>
<input class="w-full border-0 bg-transparent p-0 text-sm text-on-surface placeholder:text-on-surface-variant focus:ring-0" id="comments-search" placeholder="Buscar por usuario, anime, comentario o fecha" type="text"/>
</label>
<select class="rounded-full border border-outline/30 bg-surface-container px-4 py-3 text-sm text-on-surface focus:border-primary focus:ring-0" id="comments-role-filter">
<option value="all">Todos</option>
<option value="reportado">Reportado</option>
<option value="revisado">Revisado</option>
<option value="premium">Premium</option>
<option value="registrado">Registrado</option>
<option value="administrador">Administrador</option>
</select>
</div>
</div>
<div class="w-full">
<table class="w-full table-fixed text-left border-collapse" id="comments-table">
<thead>
<tr class="bg-surface-container text-on-surface-variant uppercase text-[10px] font-bold tracking-[0.2em]">
<th class="px-4 py-4 w-[16%]">USUARIO</th>
<th class="px-4 py-4 w-[18%]">SERIE DE ANIME</th>
<th class="px-4 py-4 w-[26%]">FRAGMENTO DEL COMENTARIO</th>
<th class="px-3 py-4 w-[10%] text-center">ESTRELLAS</th>
<th class="px-3 py-4 w-[12%] text-center">FUENTE</th>
<th class="px-4 py-4 w-[10%]">FECHA</th>
<th class="px-4 py-4 w-[8%] text-center">ACCIONES</th>
</tr>
</thead>
<tbody class="divide-y divide-outline/5" id="comments-tbody">
<tr id="row-template" class="hidden hover:bg-surface-container-high transition-all duration-300 group">
<td class="px-4 py-5 align-top">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-xs" data-avatar>?</div>
<div>
<p class="text-sm font-bold text-on-surface" data-user>@User</p>
<p class="text-[10px] text-primary/70 uppercase font-bold" data-tag>TAG</p>
</div>
</div>
</td>
<td class="px-4 py-5 align-top">
<p class="text-sm font-headline font-bold text-on-surface break-words" data-anime>Anime</p>
<p class="text-[10px] text-on-surface-variant" data-ep>Comentario general</p>
</td>
<td class="px-4 py-5 align-top">
<p class="text-sm text-on-surface-variant line-clamp-2 break-words leading-relaxed italic" data-msg>Message</p>
<p class="mt-2 hidden text-[11px] font-semibold uppercase tracking-[0.18em] text-rose-300" data-report-meta>Reportado</p>
</td>
<td class="px-3 py-5 align-top text-center">
<div class="inline-flex max-w-full items-center gap-1 rounded-full border border-white/10 bg-white/5 px-2 py-2 text-amber-300" data-rating-wrap>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="text-xs font-bold" data-rating>0/5</span>
</div>
</td>
<td class="px-3 py-5 align-top text-center">
<span class="inline-flex w-full justify-center rounded-full border border-white/10 bg-white/5 px-2 py-2 text-[10px] font-bold uppercase tracking-[0.14em] text-on-surface-variant" data-source>USUARIO</span>
</td>
<td class="px-4 py-5 align-top">
<p class="text-xs break-words text-on-surface-variant" data-date>Date</p>
</td>
<td class="px-4 py-5 align-top">
<div class="flex flex-wrap justify-center gap-2">
<button class="h-9 w-9 rounded-full flex items-center justify-center bg-surface-container hover:bg-sky-500/20 hover:text-sky-200 transition-all duration-300 text-on-surface-variant" title="Ver comentario" data-admin-comment-view>
<span class="material-symbols-outlined text-lg">visibility</span>
</button>
<button class="h-9 w-9 rounded-full flex items-center justify-center bg-surface-container hover:bg-emerald-500/20 hover:text-emerald-200 transition-all duration-300 text-on-surface-variant" title="Marcar como revisado" data-admin-comment-review>
<span class="material-symbols-outlined text-lg">task_alt</span>
</button>
<button class="h-9 w-9 rounded-full flex items-center justify-center bg-surface-container hover:bg-error-container hover:text-white transition-all duration-300 text-on-surface-variant" title="Eliminar comentario" data-admin-comment-delete>
<span class="material-symbols-outlined text-lg">delete</span>
</button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
<div class="px-8 py-6 flex justify-between items-center bg-surface-container/50 border-t border-outline/10">
<p class="text-xs text-on-surface-variant" id="comments-footer">Cargando comentarios...</p>
<div class="flex items-center gap-3">
<button class="flex h-11 w-11 items-center justify-center rounded-full border border-white/10 bg-white/5 text-on-surface-variant transition hover:border-primary/40 hover:text-on-surface disabled:opacity-30 disabled:pointer-events-none" data-comments-prev type="button">
<span class="material-symbols-outlined">chevron_left</span>
</button>
<button class="flex h-11 w-11 items-center justify-center rounded-full border border-white/10 bg-white/5 text-on-surface-variant transition hover:border-primary/40 hover:text-on-surface disabled:opacity-30 disabled:pointer-events-none" data-comments-next type="button">
<span class="material-symbols-outlined">chevron_right</span>
</button>
</div>
</div>
</section>
</main>
<div class="fixed inset-0 z-[90] hidden items-center justify-center bg-black/70 px-6" data-comments-view-modal>
  <div class="w-full max-w-2xl rounded-[28px] border border-white/10 bg-[#171717] p-7 shadow-[0_24px_80px_rgba(0,0,0,0.45)]">
    <div class="flex items-start justify-between gap-4">
      <div>
        <p class="text-[11px] uppercase tracking-[0.28em] text-primary/80">Detalle de comentario</p>
        <h3 class="mt-2 text-2xl font-headline font-extrabold text-on-surface">Ver comentario</h3>
      </div>
      <button class="flex h-11 w-11 items-center justify-center rounded-full bg-white/5 text-on-surface-variant transition hover:bg-white/10 hover:text-on-surface" data-comments-view-close type="button">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>
    <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
      <div class="rounded-2xl border border-white/8 bg-white/4 px-4 py-4">
        <p class="text-xs uppercase tracking-[0.2em] text-on-surface-variant">Usuario</p>
        <p class="mt-2 text-sm font-semibold text-on-surface" data-comments-view-user>@usuario</p>
        <p class="mt-1 text-[11px] text-on-surface-variant">Rating: <span class="font-bold text-amber-300" data-comments-view-rating>0/5</span></p>
      </div>
      <div class="rounded-2xl border border-white/8 bg-white/4 px-4 py-4">
        <p class="text-xs uppercase tracking-[0.2em] text-on-surface-variant">Anime</p>
        <p class="mt-2 text-sm font-semibold text-on-surface" data-comments-view-anime>Anime</p>
        <p class="mt-1 text-[11px] text-on-surface-variant">Fuente: <span class="font-bold text-on-surface" data-comments-view-source>Usuario</span></p>
        <p class="mt-1 text-[11px] text-on-surface-variant">Fecha: <span class="font-bold text-on-surface" data-comments-view-date>Fecha</span></p>
<p class="mt-2 hidden rounded-xl border border-rose-400/25 bg-rose-500/10 px-3 py-2 text-[11px] text-rose-100" data-comments-view-report>Sin reporte</p>
<div class="mt-3 hidden rounded-xl border border-amber-400/25 bg-amber-500/10 px-3 py-3 text-[11px] text-amber-100 space-y-1" data-comments-view-review>
  <p>Estado: <span class="font-bold" data-comments-view-review-status>Revisado</span></p>
  <p>Revisado por: <span class="font-bold" data-comments-view-review-by>@admin</span></p>
  <p>Revisado en: <span class="font-bold" data-comments-view-review-at>Fecha</span></p>
</div>
      </div>
    </div>
    <div class="mt-4 rounded-2xl border border-white/8 bg-white/4 px-4 py-4">
      <p class="text-xs uppercase tracking-[0.2em] text-on-surface-variant">Comentario</p>
      <p class="mt-3 whitespace-pre-wrap text-sm leading-6 text-on-surface" data-comments-view-text>Comentario</p>
    </div>
    <div class="mt-6 flex justify-end">
      <button class="rounded-full border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-on-surface transition hover:bg-white/10" data-comments-view-close type="button">Cerrar</button>
    </div>
  </div>
</div><div class="fixed inset-0 z-[90] hidden items-center justify-center bg-black/70 px-6" data-comments-status-modal>
  <div class="flex h-[93vh] w-full max-w-4xl flex-col rounded-[28px] border border-white/10 bg-[#171717] p-7 shadow-[0_24px_80px_rgba(0,0,0,0.45)]">
    <div class="flex items-start justify-between gap-4">
      <div>
        <p class="text-[11px] uppercase tracking-[0.28em] text-primary/80" data-comments-status-kicker>Historial</p>
        <h3 class="mt-2 text-2xl font-headline font-extrabold text-on-surface" data-comments-status-title>Comentarios</h3>
      </div>
      <button class="flex h-11 w-11 items-center justify-center rounded-full bg-white/5 text-on-surface-variant transition hover:bg-white/10 hover:text-on-surface" data-comments-status-close type="button">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>
    <p class="mt-4 text-sm text-on-surface-variant" data-comments-status-description>Detalle</p>
    <div class="mt-5 grid gap-3 rounded-2xl border border-white/8 bg-white/4 px-4 py-4 md:grid-cols-[minmax(0,1.3fr)_minmax(180px,0.7fr)_minmax(180px,0.7fr)]">
      <label class="flex items-center gap-3 rounded-full border border-white/10 bg-black/20 px-4 py-3">
        <span class="material-symbols-outlined text-on-surface-variant">search</span>
        <input class="w-full border-0 bg-transparent p-0 text-sm text-on-surface placeholder:text-on-surface-variant focus:ring-0" data-comments-status-search placeholder="Buscar por ID o usuario" type="text" />
      </label>
      <label class="rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-sm text-on-surface-variant">
        <span class="mb-2 block text-[11px] uppercase tracking-[0.18em]">Desde</span>
        <input class="w-full border-0 bg-transparent p-0 text-sm text-on-surface focus:ring-0" data-comments-status-date-from inputmode="numeric" maxlength="10" placeholder="dd/mm/aaaa" type="text" />
      </label>
      <label class="rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-sm text-on-surface-variant">
        <span class="mb-2 block text-[11px] uppercase tracking-[0.18em]">Hasta</span>
        <input class="w-full border-0 bg-transparent p-0 text-sm text-on-surface focus:ring-0" data-comments-status-date-to inputmode="numeric" maxlength="10" placeholder="dd/mm/aaaa" type="text" />
      </label>
    </div>
    <div class="mt-6 min-h-0 flex-1 space-y-4 overflow-y-auto pr-2 hide-scrollbar" data-comments-status-list>
      <article class="rounded-2xl border border-white/8 bg-white/4 px-5 py-5 text-sm text-on-surface-variant">No hay elementos para mostrar.</article>
    </div>
  </div>
</div><div class="fixed inset-0 z-[90] hidden items-center justify-center bg-black/70 px-6" data-comments-review-modal>
  <div class="w-full max-w-md rounded-[28px] border border-white/10 bg-[#171717] p-7 shadow-[0_24px_80px_rgba(0,0,0,0.45)]">
    <div class="flex items-start justify-between gap-4">
      <div>
        <p class="text-[11px] uppercase tracking-[0.28em] text-amber-300/80">Marcar como revisado</p>
        <h3 class="mt-2 text-2xl font-headline font-extrabold text-on-surface">Confirmar revisión</h3>
      </div>
      <button class="flex h-11 w-11 items-center justify-center rounded-full bg-white/5 text-on-surface-variant transition hover:bg-white/10 hover:text-on-surface" data-comments-review-close type="button">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>
    <p class="mt-5 text-sm leading-6 text-on-surface-variant">¿Deseas marcar este comentario como revisado? Luego de hacerlo no se podrá cambiar.</p>
    <div class="mt-4 rounded-2xl border border-white/8 bg-white/4 px-4 py-4">
      <p class="text-xs uppercase tracking-[0.2em] text-on-surface-variant">Comentario seleccionado</p>
      <p class="mt-2 text-sm font-semibold text-on-surface" data-comments-review-user>@usuario</p>
      <p class="mt-1 text-sm text-on-surface-variant line-clamp-3" data-comments-review-text>Comentario</p>
    </div>
    <div class="mt-6 flex justify-end gap-3">
      <button class="rounded-full border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-on-surface transition hover:bg-white/10" data-comments-review-cancel type="button">Cancelar</button>
      <button class="rounded-full bg-gradient-to-r from-amber-400 to-yellow-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:brightness-110" data-comments-review-confirm type="button">Revisado</button>
    </div>
  </div>
</div><div class="fixed inset-0 z-[90] hidden items-center justify-center bg-black/70 px-6" data-comments-delete-modal>
  <div class="w-full max-w-md rounded-[28px] border border-white/10 bg-[#171717] p-7 shadow-[0_24px_80px_rgba(0,0,0,0.45)]">
    <div class="flex items-start justify-between gap-4">
      <div>
        <p class="text-[11px] uppercase tracking-[0.28em] text-error/80">Eliminar comentario</p>
        <h3 class="mt-2 text-2xl font-headline font-extrabold text-on-surface">Confirmar eliminación</h3>
      </div>
      <button class="flex h-11 w-11 items-center justify-center rounded-full bg-white/5 text-on-surface-variant transition hover:bg-white/10 hover:text-on-surface" data-comments-delete-close type="button">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>
    <p class="mt-5 text-sm leading-6 text-on-surface-variant">¿Deseas eliminar este comentario para siempre? Esta acción borrará el comentario de la base de datos.</p>
    <div class="mt-4 rounded-2xl border border-white/8 bg-white/4 px-4 py-4">
      <p class="text-xs uppercase tracking-[0.2em] text-on-surface-variant">Comentario seleccionado</p>
      <p class="mt-2 text-sm font-semibold text-on-surface" data-comments-delete-user>@usuario</p>
      <p class="mt-1 text-sm text-on-surface-variant line-clamp-3" data-comments-delete-text>Comentario</p>
    </div>
    <div class="mt-6 flex justify-end gap-3">
      <button class="rounded-full border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-on-surface transition hover:bg-white/10" data-comments-delete-cancel type="button">Cancelar</button>
      <button class="rounded-full bg-gradient-to-r from-[#ff8a8a] to-[#ff5f7d] px-5 py-3 text-sm font-semibold text-white transition hover:brightness-110" data-comments-delete-confirm type="button">Eliminar</button>
    </div>
  </div>
</div>
<script>
  (async function () {
    const tbody = document.getElementById('comments-tbody');
    const template = document.getElementById('row-template');
    const footer = document.getElementById('comments-footer');
    const prevBtn = document.querySelector('[data-comments-prev]');
    const nextBtn = document.querySelector('[data-comments-next]');
    const searchInput = document.getElementById('comments-search');
    const roleFilter = document.getElementById('comments-role-filter');
    const totalNode = document.getElementById('stat-total');
    const flaggedNode = document.getElementById('stat-flagged');
    const reviewedNode = document.getElementById('stat-reviewed');
    const reportHistoryNode = document.getElementById('stat-report-history');
    const reviewedTrigger = document.querySelector('[data-open-reviewed-modal]');
    const reportHistoryTrigger = document.querySelector('[data-open-report-history-modal]');
    const statusModal = document.querySelector('[data-comments-status-modal]');
    const statusKicker = document.querySelector('[data-comments-status-kicker]');
    const statusTitle = document.querySelector('[data-comments-status-title]');
    const statusDescription = document.querySelector('[data-comments-status-description]');
    const statusList = document.querySelector('[data-comments-status-list]');
    const statusSearch = document.querySelector('[data-comments-status-search]');
    const statusDateFrom = document.querySelector('[data-comments-status-date-from]');
    const statusDateTo = document.querySelector('[data-comments-status-date-to]');
    const viewModal = document.querySelector('[data-comments-view-modal]');
    const viewClose = document.querySelector('[data-comments-view-close]');
    const viewUser = document.querySelector('[data-comments-view-user]');
    const viewAnime = document.querySelector('[data-comments-view-anime]');
    const viewRating = document.querySelector('[data-comments-view-rating]');
    const viewSource = document.querySelector('[data-comments-view-source]');
    const viewDate = document.querySelector('[data-comments-view-date]');
    const viewText = document.querySelector('[data-comments-view-text]');
    const viewReport = document.querySelector('[data-comments-view-report]');
    const viewReview = document.querySelector('[data-comments-view-review]');
    const viewReviewStatus = document.querySelector('[data-comments-view-review-status]');
    const viewReviewBy = document.querySelector('[data-comments-view-review-by]');
    const viewReviewAt = document.querySelector('[data-comments-view-review-at]');
    const reviewModal = document.querySelector('[data-comments-review-modal]');
    const reviewUser = document.querySelector('[data-comments-review-user]');
    const reviewText = document.querySelector('[data-comments-review-text]');
    const reviewClose = document.querySelector('[data-comments-review-close]');
    const reviewCancel = document.querySelector('[data-comments-review-cancel]');
    const reviewConfirm = document.querySelector('[data-comments-review-confirm]');
    const deleteModal = document.querySelector('[data-comments-delete-modal]');
    const deleteUser = document.querySelector('[data-comments-delete-user]');
    const deleteText = document.querySelector('[data-comments-delete-text]');
    const deleteClose = document.querySelector('[data-comments-delete-close]');
    const deleteCancel = document.querySelector('[data-comments-delete-cancel]');
    const deleteConfirm = document.querySelector('[data-comments-delete-confirm]');

    let allComments = [];
    let deleteTarget = null;
    let reviewTarget = null;
    let currentPage = 1;
    let currentStatusMode = 'reviewed';
    const perPage = 50;

    function normalize(value) {
      return String(value || '').toLowerCase().trim();
    }

    function openViewModal(comment) {
      viewUser.textContent = comment.user;
      viewAnime.textContent = comment.anime;
      viewRating.textContent = `${comment.rating || 0}/5`;
      const source = String(comment.source || 'usuario').toLowerCase();
      viewSource.textContent = source === 'manual_seed' ? 'Manual' : (source === 'jikan' ? 'Jikan' : 'Usuario');
      viewDate.textContent = comment.date;
      viewText.textContent = comment.msg;
      viewReview.classList.add('hidden');
      if (comment.deleted_status) {
        viewReport.textContent = 'Eliminado' + (comment.deleted_by ? ' | Por: ' + comment.deleted_by : '');
        viewReport.classList.remove('hidden');
      } else if (comment.reviewed_status) {
        viewReport.classList.add('hidden');
        viewReviewStatus.textContent = 'Revisado';
        viewReviewBy.textContent = comment.reviewed_by || 'Admin';
        viewReviewAt.textContent = comment.reviewed_at || 'Sin fecha';
        viewReview.classList.remove('hidden');
      } else if (comment.flagged) {
        viewReport.textContent = 'Reporte: ' + (comment.report_reason || 'Sin detalle') + (comment.reported_by ? ' | Por: ' + comment.reported_by : '');
        viewReport.classList.remove('hidden');
      } else {
        viewReport.classList.add('hidden');
      }
      viewModal.classList.remove('hidden');
      viewModal.classList.add('flex');
    }

    function closeViewModal() {
      viewModal.classList.add('hidden');
      viewModal.classList.remove('flex');
    }

    function parseCommentDate(comment) {
      const raw = String(comment.reviewed_at || comment.date || '').trim();
      if (!raw) return null;

      const normalized = raw.replace(/\s+/g, ' ').replace(/([AP]M)$/i, ' $1');
      const parsed = Date.parse(normalized);
      if (!Number.isNaN(parsed)) return new Date(parsed);

      const isoCandidate = raw.match(/^(\d{4}-\d{2}-\d{2})/);
      if (isoCandidate) {
        const parsedIso = Date.parse(isoCandidate[1] + 'T00:00:00');
        if (!Number.isNaN(parsedIso)) return new Date(parsedIso);
      }

      return null;
    }

    function parseMaskedDate(value, endOfDay = false) {
      const digits = String(value || '').replace(/\D/g, '');
      if (digits.length !== 8) return null;

      const day = digits.slice(0, 2);
      const month = digits.slice(2, 4);
      const year = digits.slice(4, 8);
      const iso = `${year}-${month}-${day}T${endOfDay ? '23:59:59' : '00:00:00'}`;
      const parsed = Date.parse(iso);
      return Number.isNaN(parsed) ? null : new Date(parsed);
    }

    function applyDateMask(input) {
      if (!input) return;
      const digits = input.value.replace(/\D/g, '').slice(0, 8);
      let masked = digits.slice(0, 2);
      if (digits.length > 2) masked += '/' + digits.slice(2, 4);
      if (digits.length > 4) masked += '/' + digits.slice(4, 8);
      input.value = masked;
    }

    function formatStatusItems(items, isReviewed) {
      statusList.innerHTML = '';

      if (!items.length) {
        statusList.innerHTML = '<article class="rounded-2xl border border-white/8 bg-white/4 px-5 py-5 text-sm text-on-surface-variant">No hay elementos para mostrar.</article>';
        return;
      }

      items.forEach((comment) => {
        const stateClass = isReviewed
          ? 'border-amber-400/30 bg-amber-500/10 text-amber-200'
          : (comment.reviewed_status ? 'border-amber-400/30 bg-amber-500/10 text-amber-200' : 'border-rose-400/30 bg-rose-500/10 text-rose-200');
        const stateLabel = isReviewed ? 'Revisado' : (comment.reviewed_status ? 'Reportado y revisado' : 'Reportado');
        const meta = isReviewed
          ? `${comment.reviewed_by || 'Admin'}${comment.reviewed_at ? ' | ' + comment.reviewed_at : ''}`
          : `${comment.reported_by || 'Usuario'}${comment.report_reason ? ' | ' + comment.report_reason : ''}${comment.reviewed_status ? ' | Revisado por ' + (comment.reviewed_by || 'Admin') : ''}${comment.deleted_status ? ' | Eliminado' : ''}`;
        const item = document.createElement('article');
        item.className = 'rounded-2xl border border-white/8 bg-white/4 px-5 py-5';
        item.innerHTML = `
          <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
              <p class="text-[11px] uppercase tracking-[0.18em] text-on-surface-variant">ID ${comment.id || '-'}</p>
              <p class="mt-2 text-sm font-bold text-on-surface">${comment.user || '@usuario'}</p>
              <p class="mt-1 text-sm text-on-surface-variant">${comment.anime || 'Anime'}</p>
            </div>
            <span class="inline-flex rounded-full border px-3 py-1 text-[10px] font-bold uppercase tracking-[0.18em] ${stateClass}">${stateLabel}</span>
          </div>
          <p class="mt-4 text-[11px] uppercase tracking-[0.16em] text-on-surface-variant">${meta}</p>
          <p class="mt-4 whitespace-pre-wrap text-sm italic leading-6 text-on-surface">${comment.msg || ''}</p>
        `;
        statusList.appendChild(item);
      });
    }

    function refreshStatusModal() {
      if (!statusModal || !statusList) return;

      const isReviewed = currentStatusMode === 'reviewed';
      const query = normalize(statusSearch?.value || '');
      const fromDate = statusDateFrom?.value || '';
      const toDate = statusDateTo?.value || '';

      const items = allComments.filter((comment) => {
        const baseMatch = isReviewed
          ? !!comment.reviewed_status
          : (!!comment.flagged || !!comment.report_reason || !!comment.reported_by || !!comment.deleted_status);
        if (!baseMatch) return false;

        if (query) {
          const haystack = [String(comment.id || ''), comment.user, comment.anime].map(normalize);
          if (!haystack.some((value) => value.includes(query))) return false;
        }

        const parsedDate = parseCommentDate(comment);
        if ((fromDate || toDate) && !parsedDate) return false;

        if (fromDate) {
          const from = parseMaskedDate(fromDate, false);
          if (!from || parsedDate < from) return false;
        }

        if (toDate) {
          const to = parseMaskedDate(toDate, true);
          if (!to || parsedDate > to) return false;
        }

        return true;
      });

      formatStatusItems(items, isReviewed);
    }

    function openStatusModal(mode) {
      if (!statusModal || !statusList) return;

      const isReviewed = mode === 'reviewed';
      currentStatusMode = mode;

      statusKicker.textContent = isReviewed ? 'Moderacion aplicada' : 'Historial de reportes';
      statusTitle.textContent = isReviewed ? 'Comentarios revisados' : 'Comentarios reportados';
      statusDescription.textContent = isReviewed
        ? 'Aquí puedes ver los comentarios que ya fueron revisados por administración.'
        : 'Aquí se conserva el historial de usuarios y comentarios reportados.';

      if (statusSearch) statusSearch.value = '';
      if (statusDateFrom) statusDateFrom.value = '';
      if (statusDateTo) statusDateTo.value = '';

      refreshStatusModal();
      statusModal.classList.remove('hidden');
      statusModal.classList.add('flex');
    }

    function closeStatusModal() {
      if (!statusModal) return;
      statusModal.classList.add('hidden');
      statusModal.classList.remove('flex');
    }

    function openReviewModal(comment) {
      reviewTarget = comment;
      reviewUser.textContent = comment.user;
      reviewText.textContent = comment.msg;
      reviewModal.classList.remove('hidden');
      reviewModal.classList.add('flex');
    }

    function closeReviewModal() {
      reviewTarget = null;
      reviewModal.classList.add('hidden');
      reviewModal.classList.remove('flex');
    }

    function openDeleteModal(comment) {
      deleteTarget = comment;
      deleteUser.textContent = comment.user;
      deleteText.textContent = comment.msg;
      deleteModal.classList.remove('hidden');
      deleteModal.classList.add('flex');
    }

    function closeDeleteModal() {
      deleteTarget = null;
      deleteModal.classList.add('hidden');
      deleteModal.classList.remove('flex');
    }

    function getFilteredComments() {
      const q = normalize(searchInput.value);
      const role = normalize(roleFilter.value);

      return allComments.filter((comment) => {
        if (comment.deleted_status) return false;

        const normalizedTag = normalize(comment.tag);
        const roleOk =
          role === 'all' ||
          (role === 'reportado' && !!comment.flagged) ||
          (role === 'revisado' && !!comment.reviewed_status) ||
          normalizedTag === role;
        if (!roleOk) return false;

        if (!q) return true;

        return [comment.user, comment.tag, comment.anime, comment.msg, comment.date]
          .some((value) => normalize(value).includes(q));
      });
    }


    function renderComments(comments) {
      tbody.innerHTML = '';
      tbody.appendChild(template);

      if (!comments.length) {
        const emptyRow = document.createElement('tr');
        emptyRow.innerHTML = '<td colspan="7" class="px-8 py-16 text-center text-on-surface-variant">No hay comentarios para esos filtros.</td>'; 
        tbody.appendChild(emptyRow);
        return;
      }

      comments.forEach((c) => {
        const row = template.cloneNode(true);
        row.id = '';
        row.classList.remove('hidden');
        row.classList.remove('bg-rose-500/10', 'hover:bg-rose-500/15');
        row.dataset.commentId = c.id;

        row.querySelector('[data-user]').textContent = c.user;
        row.querySelector('[data-tag]').textContent = c.tag;
        row.querySelector('[data-anime]').textContent = c.anime;
        row.querySelector('[data-ep]').textContent = c.ep;
        row.querySelector('[data-msg]').textContent = c.msg;
        const reportMeta = row.querySelector('[data-report-meta]');
        reportMeta.classList.remove('text-rose-300', 'text-amber-300');
        reportMeta.classList.add('text-on-surface-variant');
        if (c.deleted_status) {
          reportMeta.textContent = 'Eliminado' + (c.deleted_by ? ' | ' + c.deleted_by : '');
          reportMeta.classList.remove('hidden');
          row.classList.add('bg-rose-500/10', 'hover:bg-rose-500/15');
          row.querySelector('[data-user]').classList.add('text-rose-200');
          row.querySelector('[data-tag]').classList.remove('text-primary/70');
          row.querySelector('[data-tag]').classList.add('text-rose-300');
          row.querySelector('[data-tag]').textContent = 'ELIMINADO';
          row.querySelector('[data-anime]').classList.add('text-rose-100');
          row.querySelector('[data-avatar]').classList.remove('bg-primary/20', 'text-primary');
          row.querySelector('[data-avatar]').classList.add('bg-rose-500/20', 'text-rose-200');
        } else if (c.reviewed_status) {
          reportMeta.classList.add('hidden');
          row.querySelector('[data-tag]').classList.remove('text-primary/70', 'text-rose-300');
          row.querySelector('[data-tag]').classList.add('text-amber-300');
          row.querySelector('[data-tag]').textContent = 'REVISADO';
        } else if (c.flagged) {
          reportMeta.classList.remove('text-on-surface-variant');
          reportMeta.classList.add('text-rose-300');
          reportMeta.textContent = 'Reportado' + (c.report_reason ? ': ' + c.report_reason : '') + (c.reported_by ? ' | ' + c.reported_by : '');
          reportMeta.classList.remove('hidden');
          row.classList.add('bg-rose-500/10', 'hover:bg-rose-500/15');
          row.querySelector('[data-user]').classList.add('text-rose-200');
          row.querySelector('[data-tag]').classList.remove('text-primary/70');
          row.querySelector('[data-tag]').classList.add('text-rose-300');
          row.querySelector('[data-anime]').classList.add('text-rose-100');
          row.querySelector('[data-avatar]').classList.remove('bg-primary/20', 'text-primary');
          row.querySelector('[data-avatar]').classList.add('bg-rose-500/20', 'text-rose-200');
        } else {
          reportMeta.classList.add('hidden');
        }
        row.querySelector('[data-rating]').textContent = `${c.rating || 0}/5`;
        const sourceNode = row.querySelector('[data-source]');
        const source = String(c.source || 'usuario').toLowerCase();
        sourceNode.textContent = source === 'manual_seed' ? 'MANUAL' : (source === 'jikan' ? 'JIKAN' : 'USUARIO');
        sourceNode.className = `inline-flex min-w-[116px] justify-center rounded-full border px-3 py-2 text-[11px] font-bold uppercase tracking-[0.18em] ${source === 'manual_seed' ? 'border-sky-400/30 bg-sky-500/10 text-sky-200' : (source === 'jikan' ? 'border-violet-400/30 bg-violet-500/10 text-violet-200' : 'border-emerald-400/30 bg-emerald-500/10 text-emerald-200')}`;
        row.querySelector('[data-date]').textContent = c.date;
        row.querySelector('[data-avatar]').textContent = c.user.replace('@', '').charAt(0).toUpperCase();

        tbody.appendChild(row);
      });
    }

    function refreshView(resetPage = false) {
      const filtered = getFilteredComments();
      const totalFiltered = filtered.length;
      const totalPages = Math.max(1, Math.ceil(totalFiltered / perPage));

      if (resetPage) currentPage = 1;
      if (currentPage > totalPages) currentPage = totalPages;
      if (currentPage < 1) currentPage = 1;

      const start = (currentPage - 1) * perPage;
      const pageItems = filtered.slice(start, start + perPage);

      renderComments(pageItems);
      totalNode.textContent = allComments.filter((c) => !c.deleted_status).length;
      flaggedNode.textContent = allComments.filter((c) => !c.deleted_status && c.flagged).length;
      reviewedNode.textContent = allComments.filter((c) => !c.deleted_status && c.reviewed_status).length;
      reportHistoryNode.textContent = allComments.filter((c) => c.flagged || !!c.report_reason || !!c.reported_by || !!c.deleted_status).length;

      if (!totalFiltered) {
        footer.textContent = allComments.length ? 'No hay comentarios para esos filtros.' : 'No hay comentarios registrados';
      } else {
        const shown = Math.min(start + pageItems.length, totalFiltered);
        footer.innerHTML = `Mostrando <span class="text-on-surface font-bold">${shown}</span> de <span class="text-on-surface font-bold">${totalFiltered}</span> comentarios`;
      }

      prevBtn.disabled = currentPage <= 1 || !totalFiltered;
      nextBtn.disabled = currentPage >= totalPages || !totalFiltered;
    }

    try {
      const res = await fetch('api/comments.php?action=list');
      const data = await res.json();
      if (data.success) {
        allComments = Array.isArray(data.data) ? data.data : [];
        refreshView(true);
      } else {
        console.error(data.error);
        footer.textContent = 'No se pudieron cargar los comentarios';
      }
    } catch (e) {
      console.error('Error fetching comments', e);
      footer.textContent = 'No se pudieron cargar los comentarios';
    }

    searchInput.addEventListener('input', () => refreshView(true));
    roleFilter.addEventListener('change', () => refreshView(true));
    prevBtn.addEventListener('click', () => { currentPage -= 1; refreshView(); });
    nextBtn.addEventListener('click', () => { currentPage += 1; refreshView(); });
    reviewedTrigger?.addEventListener('click', () => openStatusModal('reviewed'));
    reportHistoryTrigger?.addEventListener('click', () => openStatusModal('reported'));
    statusSearch?.addEventListener('input', refreshStatusModal);
    statusDateFrom?.addEventListener('input', () => { applyDateMask(statusDateFrom); refreshStatusModal(); });
    statusDateTo?.addEventListener('input', () => { applyDateMask(statusDateTo); refreshStatusModal(); });

    document.addEventListener('click', async (e) => {
      const viewBtn = e.target.closest('[data-admin-comment-view]');
      if (viewBtn) {
        e.preventDefault();
        const row = viewBtn.closest('tr');
        if (!row) return;
        const comment = allComments.find((item) => String(item.id) === String(row.dataset.commentId));
        if (!comment) return;
        openViewModal(comment);
        return;
      }

      const delBtn = e.target.closest('[data-admin-comment-delete]');
      if (delBtn) {
        e.preventDefault();
        const row = delBtn.closest('tr');
        if (!row) return;
        const comment = allComments.find((item) => String(item.id) === String(row.dataset.commentId));
        if (!comment) return;
        openDeleteModal(comment);
        return;
      }

      const reviewBtn = e.target.closest('[data-admin-comment-review]');
      if (reviewBtn) {
        e.preventDefault();
        const row = reviewBtn.closest('tr');
        if (!row) return;
        const comment = allComments.find((item) => String(item.id) === String(row.dataset.commentId));
        if (!comment) return;
        openReviewModal(comment);
        return;
      }

      if (e.target === reviewModal || e.target.closest('[data-comments-review-close]') || e.target.closest('[data-comments-review-cancel]')) {
        closeReviewModal();
        return;
      }

      if (e.target.closest('[data-comments-review-confirm]')) {
        if (!reviewTarget) return;

        try {
          const res = await fetch('api/comments.php?action=moderate', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'same-origin',
            body: JSON.stringify({ id: reviewTarget.id, decision: 'review' })
          });
          const data = await res.json();
          if (!data.success) {
            alert(data.error || 'No se pudo marcar el comentario como revisado');
            return;
          }

          const refreshed = await fetch('api/comments.php?action=list', { credentials: 'same-origin' });
          const refreshedData = await refreshed.json();
          allComments = Array.isArray(refreshedData.data) ? refreshedData.data : allComments;
          closeReviewModal();
          refreshView();
        } catch (err) {
          alert('No se pudo marcar el comentario como revisado');
        }
        return;
      }

      if (e.target === viewModal || e.target.closest('[data-comments-view-close]')) {
        closeViewModal();
        return;
      }

      if (e.target === statusModal || e.target.closest('[data-comments-status-close]')) {
        closeStatusModal();
        return;
      }

      if (e.target === deleteModal || e.target.closest('[data-comments-delete-close]') || e.target.closest('[data-comments-delete-cancel]')) {
        closeDeleteModal();
        return;
      }

      if (e.target.closest('[data-comments-delete-confirm]')) {
        if (!deleteTarget) return;

        try {
          const res = await fetch('api/comments.php?action=moderate', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'same-origin',
            body: JSON.stringify({ id: deleteTarget.id, decision: 'delete' })
          });
          const data = await res.json();
          if (!data.success) {
            alert(data.error || 'No se pudo eliminar el comentario');
            return;
          }

          const refreshed = await fetch('api/comments.php?action=list', { credentials: 'same-origin' });
          const refreshedData = await refreshed.json();
          allComments = Array.isArray(refreshedData.data) ? refreshedData.data : allComments;
          closeDeleteModal();
          refreshView();
        } catch (err) {
          alert('No se pudo eliminar el comentario');
        }
      }
    });
  })();
</script>
<script src="assets/js/admin-layout.js?v=20260330a"></script>
</body></html>





