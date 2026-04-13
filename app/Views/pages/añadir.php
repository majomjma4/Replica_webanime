<!DOCTYPE html>
<html class="dark" lang="es"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<link rel="icon" href="img/icon3.webp" />
<title>NekoraList - Añadir Nuevo Anime</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Inter:wght@400;500;600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
        }
        body {
            background-color: #0e0e0e;
            color: #e7e5e4;
            font-family: 'Inter', sans-serif;
        }
        .font-headline { font-family: 'Manrope', sans-serif; }
        .transparent-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.12) transparent;
        }
        .transparent-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .transparent-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .transparent-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.12);
            border-radius: 9999px;
            border: 2px solid transparent;
            background-clip: content-box;
        }
    </style>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "surface-variant": "#252626",
              "surface-container-highest": "#252626",
              "surface": "#0e0e0e",
              "primary-fixed": "#e8deff",
              "on-surface-variant": "#acabaa",
              "primary-dim": "#c0adff",
              "tertiary-container": "#ddddfe",
              "background": "#0e0e0e",
              "surface-container-lowest": "#000000",
              "secondary": "#a09da1",
              "error": "#ec7c8a",
              "outline": "#767575",
              "on-background": "#e7e5e4",
              "surface-container-low": "#131313",
              "primary-container": "#4f00d0",
              "surface-container-high": "#1f2020",
              "on-primary": "#4800bf",
              "surface-container": "#191a1a",
              "primary": "#cdbdff",
              "outline-variant": "#484848",
              "on-surface": "#e7e5e4"
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
<script src="assets/js/shared-utils.js?v=20260408a" defer></script>
<script src="assets/js/admin-layout.js?v=20260330a" defer></script>
</head>
<body class="flex min-h-screen" data-admin-page="add">
<!-- SideNavBar Component -->
<?php include __DIR__ . '/../partials/admin-layout.php'; ?>
<main class="ml-64 flex-1 relative min-h-screen">
<!-- Form Content -->
<section class="pt-20 pb-20 px-12 max-w-6xl mx-auto">
<div class="flex flex-col gap-2 mb-12">
<h1 class="text-5xl font-headline font-extrabold tracking-tight text-on-surface">Añadir Nuevo Anime</h1>
<p id="add-page-subtitle" class="text-on-surface-variant text-lg">Completa los detalles para catalogar una nueva obra maestra.</p>
</div>
<form id="form-añadir" class="grid grid-cols-12 gap-8">
<!-- Left Column: Core Data & Imagery -->
<div class="col-span-12 lg:col-span-8 space-y-8">
<!-- Basic Info Section -->
<div class="bg-surface-container-low p-8 rounded-lg shadow-sm space-y-6">
<div class="space-y-3">
<label class="text-sm font-bold uppercase tracking-wider text-primary">Tipo de contenido</label>
<div class="grid grid-cols-2 gap-3" id="in-tipo-toggle">
<button type="button" data-type-option="anime" class="rounded-2xl border border-primary/40 bg-primary/15 px-4 py-4 text-sm font-bold uppercase tracking-widest text-primary transition-all">Anime</button>
<button type="button" data-type-option="pelicula" class="rounded-2xl border border-outline-variant/30 bg-surface-container-lowest px-4 py-4 text-sm font-bold uppercase tracking-widest text-on-surface-variant transition-all hover:border-primary/30 hover:text-on-surface">Película</button>
</div>
<input id="in-tipo" type="hidden" value="anime" />
</div>
<div class="space-y-2">
<label class="text-sm font-bold uppercase tracking-wider text-primary">Título del Anime</label>
<input id="in-título" required class="w-full bg-surface-container-lowest border-none rounded-lg p-4 focus:ring-2 focus:ring-primary/40 text-on-surface" placeholder="Ej: Neon Genesis Evangelion" type="text"/>
</div>
<div class="space-y-2">
<label class="text-sm font-bold uppercase tracking-wider text-primary">Sinopsis / Descripción</label>
<textarea id="in-sinopsis" required class="w-full bg-surface-container-lowest border-none rounded-lg p-4 focus:ring-2 focus:ring-primary/40 text-on-surface resize-none transparent-scrollbar" placeholder="Escribe una descripción detallada..." rows="6"></textarea>
</div>
</div>
<!-- Media Section -->
<div class="bg-surface-container-low p-8 rounded-lg shadow-sm">
<label class="text-sm font-bold uppercase tracking-wider text-primary mb-6 block">Material Visual (URL)</label>
<div class="space-y-5">
<div class="grid grid-cols-2 gap-3" id="media-tab-toggle">
<button type="button" data-media-tab="poster" class="rounded-2xl border border-primary/40 bg-primary/15 px-4 py-3 text-sm font-bold uppercase tracking-widest text-primary transition-all">Portada</button>
<button type="button" data-media-tab="trailer" class="rounded-2xl border border-outline-variant/30 bg-surface-container-lowest px-4 py-3 text-sm font-bold uppercase tracking-widest text-on-surface-variant transition-all hover:border-primary/30 hover:text-on-surface">Trailer</button>
</div>
<div id="media-panel-poster" class="space-y-4">
<p class="text-sm font-medium text-on-surface-variant">Imagen Principal (URL Web)</p>
<input type="url" id="in-imagen" required class="w-full bg-surface-container-lowest border border-outline-variant/30 rounded-lg p-4 focus:ring-2 focus:ring-primary/40 text-on-surface" placeholder="https://ejemplo.com/poster.jpg" />
<div class="mt-4 flex justify-center">
<div id="poster-preview-frame" class="relative w-[220px] aspect-[2/3] rounded-xl overflow-hidden bg-surface-container-lowest border-2 border-dashed border-outline-variant/30 flex items-center justify-center">
<img id="poster-preview-image" alt="Previsualización del poster" class="hidden h-full w-full object-cover" />
<p id="poster-preview-empty" class="px-4 text-center text-xs text-outline font-bold uppercase">Previsualización del poster</p>
</div>
</div>
</div>
<div id="media-panel-trailer" class="hidden space-y-4">
<p class="text-sm font-medium text-on-surface-variant">Trailer (URL Web)</p>
<input type="url" id="in-trailer" class="w-full bg-surface-container-lowest border border-outline-variant/30 rounded-lg p-4 focus:ring-2 focus:ring-primary/40 text-on-surface" placeholder="https://youtube.com/watch?v=..." />
<div class="mt-4 flex justify-center">
<div id="trailer-preview-frame" class="relative w-full max-w-[420px] aspect-video rounded-xl overflow-hidden bg-surface-container-lowest border-2 border-dashed border-outline-variant/30 flex items-center justify-center">
<iframe id="trailer-preview-embed" class="hidden h-full w-full" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe>
<p id="trailer-preview-empty" class="px-4 text-center text-xs text-outline font-bold uppercase">Previsualización del trailer</p>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- Right Column: Metadata & Categorization -->
<div class="col-span-12 lg:col-span-4 space-y-8">
<!-- Technical Specs Section -->
<div class="bg-surface-container-low p-8 rounded-lg shadow-sm space-y-6">
<div class="space-y-2">
<label id="label-tipo-formato" class="text-sm font-bold uppercase tracking-wider text-primary">Tipo</label>
<select id="in-tipo-formato" class="w-full bg-surface-container-lowest border-none rounded-lg p-4 focus:ring-2 focus:ring-primary/40 text-on-surface appearance-none cursor-pointer">
<option value="ALL">Todos</option>
<option value="TV">Serie de TV</option>
<option value="OVA">OVA</option>
<option value="ONA">ONA</option>
<option value="SPECIAL">Especiales</option>
<option value="SHORT">Cortos</option>
</select>
</div>
<div class="space-y-2">
<label id="label-estado" class="text-sm font-bold uppercase tracking-wider text-primary">Estado</label>
<select id="in-estado" class="w-full bg-surface-container-lowest border-none rounded-lg p-4 focus:ring-2 focus:ring-primary/40 text-on-surface appearance-none cursor-pointer">
<option value="ALL">Todos</option>
<option value="Airing">En emisión</option>
<option value="Finished">Finalizado</option>
<option value="Upcoming">Próximamente</option>
<option value="Cancelled">Cancelado</option>
</select>
</div>
<div class="grid grid-cols-2 gap-4">
<div id="temporada-wrap" class="space-y-2">
<label class="text-sm font-bold uppercase tracking-wider text-primary">Temporada</label>
<select id="in-temporada" class="w-full bg-surface-container-lowest border-none rounded-lg p-4 focus:ring-2 focus:ring-primary/40 text-on-surface appearance-none cursor-pointer">
<option value="winter">Invierno</option>
<option value="spring">Primavera</option>
<option value="summer">Verano</option>
<option value="fall">Otoño</option>
</select>
</div>
<div class="space-y-2">
<label class="text-sm font-bold uppercase tracking-wider text-primary">Año</label>
<input id="in-anio" class="w-full bg-surface-container-lowest border-none rounded-lg p-4 focus:ring-2 focus:ring-primary/40 text-on-surface" type="number" value="2026"/>
</div>
</div>
<div class="grid grid-cols-2 gap-4">
<div id="duration-wrap" class="space-y-2">
<label id="label-duración" class="text-sm font-bold uppercase tracking-wider text-primary">Episodios</label>
<input id="in-episodios" class="w-full bg-surface-container-lowest border-none rounded-lg p-4 focus:ring-2 focus:ring-primary/40 text-on-surface" placeholder="0" type="number"/>
</div>
</div>
</div>
<!-- Genres Section -->
<div class="bg-surface-container-low p-8 rounded-lg shadow-sm">
<label class="text-sm font-bold uppercase tracking-wider text-primary mb-4 block">Géneros</label>
<div class="grid grid-cols-2 gap-3">
<label class="group flex min-h-[48px] w-full items-center gap-2 rounded-2xl border border-outline-variant/10 bg-surface-container-lowest px-4 py-3 cursor-pointer">
<input name="in-genero" value="Action" class="rounded text-primary focus:ring-primary/20 bg-transparent border-outline-variant" type="checkbox"/>
<span class="text-xs font-medium text-on-surface-variant leading-tight">Action</span>
</label>
<label class="group flex min-h-[48px] w-full items-center gap-2 rounded-2xl border border-outline-variant/10 bg-surface-container-lowest px-4 py-3 cursor-pointer">
<input name="in-genero" value="Adventure" class="rounded text-primary focus:ring-primary/20 bg-transparent border-outline-variant" type="checkbox"/>
<span class="text-xs font-medium text-on-surface-variant leading-tight">Adventure</span>
</label>
<label class="group flex min-h-[48px] w-full items-center gap-2 rounded-2xl border border-outline-variant/10 bg-surface-container-lowest px-4 py-3 cursor-pointer">
<input name="in-genero" value="Avant Garde" class="rounded text-primary focus:ring-primary/20 bg-transparent border-outline-variant" type="checkbox"/>
<span class="text-xs font-medium text-on-surface-variant leading-tight">Avant Garde</span>
</label>
<label class="group flex min-h-[48px] w-full items-center gap-2 rounded-2xl border border-outline-variant/10 bg-surface-container-lowest px-4 py-3 cursor-pointer">
<input name="in-genero" value="Award Winning" class="rounded text-primary focus:ring-primary/20 bg-transparent border-outline-variant" type="checkbox"/>
<span class="text-xs font-medium text-on-surface-variant leading-tight">Award Winning</span>
</label>
<label class="group flex min-h-[48px] w-full items-center gap-2 rounded-2xl border border-outline-variant/10 bg-surface-container-lowest px-4 py-3 cursor-pointer">
<input name="in-genero" value="Comedy" class="rounded text-primary focus:ring-primary/20 bg-transparent border-outline-variant" type="checkbox"/>
<span class="text-xs font-medium text-on-surface-variant leading-tight">Comedy</span>
</label>
<label class="group flex min-h-[48px] w-full items-center gap-2 rounded-2xl border border-outline-variant/10 bg-surface-container-lowest px-4 py-3 cursor-pointer">
<input name="in-genero" value="Drama" class="rounded text-primary focus:ring-primary/20 bg-transparent border-outline-variant" type="checkbox"/>
<span class="text-xs font-medium text-on-surface-variant leading-tight">Drama</span>
</label>
<label class="group flex min-h-[48px] w-full items-center gap-2 rounded-2xl border border-outline-variant/10 bg-surface-container-lowest px-4 py-3 cursor-pointer">
<input name="in-genero" value="Fantasy" class="rounded text-primary focus:ring-primary/20 bg-transparent border-outline-variant" type="checkbox"/>
<span class="text-xs font-medium text-on-surface-variant leading-tight">Fantasy</span>
</label>
<label class="group flex min-h-[48px] w-full items-center gap-2 rounded-2xl border border-outline-variant/10 bg-surface-container-lowest px-4 py-3 cursor-pointer">
<input name="in-genero" value="Horror" class="rounded text-primary focus:ring-primary/20 bg-transparent border-outline-variant" type="checkbox"/>
<span class="text-xs font-medium text-on-surface-variant leading-tight">Horror</span>
</label>
<label class="group flex min-h-[48px] w-full items-center gap-2 rounded-2xl border border-outline-variant/10 bg-surface-container-lowest px-4 py-3 cursor-pointer">
<input name="in-genero" value="Mystery" class="rounded text-primary focus:ring-primary/20 bg-transparent border-outline-variant" type="checkbox"/>
<span class="text-xs font-medium text-on-surface-variant leading-tight">Mystery</span>
</label>
<label class="group flex min-h-[48px] w-full items-center gap-2 rounded-2xl border border-outline-variant/10 bg-surface-container-lowest px-4 py-3 cursor-pointer">
<input name="in-genero" value="Romance" class="rounded text-primary focus:ring-primary/20 bg-transparent border-outline-variant" type="checkbox"/>
<span class="text-xs font-medium text-on-surface-variant leading-tight">Romance</span>
</label>
<label class="group flex min-h-[48px] w-full items-center gap-2 rounded-2xl border border-outline-variant/10 bg-surface-container-lowest px-4 py-3 cursor-pointer">
<input name="in-genero" value="Sci-Fi" class="rounded text-primary focus:ring-primary/20 bg-transparent border-outline-variant" type="checkbox"/>
<span class="text-xs font-medium text-on-surface-variant leading-tight">Sci-Fi</span>
</label>
<label class="group flex min-h-[48px] w-full items-center gap-2 rounded-2xl border border-outline-variant/10 bg-surface-container-lowest px-4 py-3 cursor-pointer">
<input name="in-genero" value="Slice of Life" class="rounded text-primary focus:ring-primary/20 bg-transparent border-outline-variant" type="checkbox"/>
<span class="text-xs font-medium text-on-surface-variant leading-tight">Slice of Life</span>
</label>
<label class="group flex min-h-[48px] w-full items-center gap-2 rounded-2xl border border-outline-variant/10 bg-surface-container-lowest px-4 py-3 cursor-pointer">
<input name="in-genero" value="Sports" class="rounded text-primary focus:ring-primary/20 bg-transparent border-outline-variant" type="checkbox"/>
<span class="text-xs font-medium text-on-surface-variant leading-tight">Sports</span>
</label>
<label class="group flex min-h-[48px] w-full items-center gap-2 rounded-2xl border border-outline-variant/10 bg-surface-container-lowest px-4 py-3 cursor-pointer">
<input name="in-genero" value="Supernatural" class="rounded text-primary focus:ring-primary/20 bg-transparent border-outline-variant" type="checkbox"/>
<span class="text-xs font-medium text-on-surface-variant leading-tight">Supernatural</span>
</label>
</div>
</div>
<!-- Actions -->
<div class="bg-surface-container-high p-4 rounded-lg flex flex-col gap-3 mt-4">
<button class="w-full py-4 bg-gradient-to-br from-[#cdbdff] to-[#4f00d0] text-white rounded-full font-headline font-extrabold text-sm uppercase tracking-widest hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-primary/20" type="submit">
                            <span id="submit-label">Subir Anime</span>
                        </button>
</div>
</div>
</form>
</section>
</main>
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40" data-admin-save-toast>
  <div class="rounded-2xl bg-surface-container-high px-6 py-3 text-sm font-bold text-emerald-400 shadow-xl border border-primary/20 flex items-center gap-2">
    <span class="material-symbols-outlined text-lg">check_circle</span>
    Anime guardado en la base de datos exitosamente
  </div>
</div>
<div id="admin-confirm-modal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/70 px-6">
  <div class="w-full max-w-md rounded-[28px] border border-primary/20 bg-surface-container p-6 shadow-[0_24px_80px_rgba(0,0,0,0.45)]">
    <div class="flex items-start gap-4">
      <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-primary/15 text-primary">
        <span class="material-symbols-outlined text-[24px]" id="admin-confirm-icon">warning</span>
      </div>
      <div class="flex-1 space-y-2">
        <h3 id="admin-confirm-title" class="text-xl font-headline font-extrabold text-on-surface">Confirmar acción</h3>
        <p id="admin-confirm-message" class="text-sm leading-relaxed text-on-surface-variant">?Deseas continuar?</p>
      </div>
    </div>
    <div class="mt-6 flex justify-end gap-3">
      <button type="button" id="admin-confirm-cancel" class="rounded-full border border-outline-variant/30 bg-surface-container-lowest px-5 py-3 text-xs font-bold uppercase tracking-widest text-on-surface-variant transition hover:border-outline/60 hover:text-on-surface">Cancelar</button>
      <button type="button" id="admin-confirm-ok" class="rounded-full bg-gradient-to-r from-[#cdbdff] to-[#4f00d0] px-5 py-3 text-xs font-bold uppercase tracking-widest text-white shadow-lg shadow-primary/20 transition hover:brightness-110">Continuar</button>
    </div>
  </div>
</div>
<script>
  (function () {
    const form = document.getElementById('form-a?adir') || document.querySelector('form[id^="form-a"]');
    const toast = document.querySelector('[data-admin-save-toast]');
    const confirmModal = document.getElementById('admin-confirm-modal');
    const confirmTitle = document.getElementById('admin-confirm-title');
    const confirmMessage = document.getElementById('admin-confirm-message');
    const confirmOk = document.getElementById('admin-confirm-ok');
    const confirmCancel = document.getElementById('admin-confirm-cancel');
    const confirmIcon = document.getElementById('admin-confirm-icon');
    const typeInput = document.getElementById('in-tipo');
    const typeButtons = Array.from(document.querySelectorAll('[data-type-option]'));
    const pageTitle = document.getElementById('add-page-title');
    const pageSubtitle = document.getElementById('add-page-subtitle');
    const labelTítulo = document.getElementById('label-título');
    const títuloInput = document.getElementById('in-título');
    const submitLabel = document.getElementById('submit-label');
    const tipoFormatoSelect = document.getElementById('in-tipo-formato');
    const estadoSelect = document.getElementById('in-estado');
    const temporadaWrap = document.getElementById('temporada-wrap');
    const durationLabel = document.getElementById('label-duración');
    const durationInput = document.getElementById('in-episodios');
    let isDirty = false;
    let isSubmitting = false;
    const imageInput = document.getElementById('in-imagen');
    const trailerInput = document.getElementById('in-trailer');
    const posterPreviewImage = document.getElementById('poster-preview-image');
    const posterPreviewEmpty = document.getElementById('poster-preview-empty');
    const trailerPreviewEmbed = document.getElementById('trailer-preview-embed');
    const trailerPreviewEmpty = document.getElementById('trailer-preview-empty');
    const mediaTabButtons = Array.from(document.querySelectorAll('[data-media-tab]'));
    const mediaPosterPanel = document.getElementById('media-panel-poster');
    const mediaTrailerPanel = document.getElementById('media-panel-trailer');

    let confirmResolver = null;

    const closeConfirmModal = (result) => {
      if (!confirmModal) return;
      confirmModal.classList.add('hidden');
      confirmModal.classList.remove('flex');
      document.body.style.overflow = '';
      if (confirmResolver) {
        confirmResolver(result);
        confirmResolver = null;
      }
    };

    const openConfirmModal = ({ title, message, confirmText = 'Continuar', cancelText = 'Cancelar', icon = 'warning' }) => {
      if (!confirmModal || !confirmTitle || !confirmMessage || !confirmOk || !confirmCancel || !confirmIcon) {
        return Promise.resolve(window.confirm(message || '?Deseas continuar?'));
      }
      confirmTitle.textContent = title || 'Confirmar acción';
      confirmMessage.textContent = message || '?Deseas continuar?';
      confirmOk.textContent = confirmText;
      confirmCancel.textContent = cancelText;
      confirmIcon.textContent = icon;
      confirmModal.classList.remove('hidden');
      confirmModal.classList.add('flex');
      document.body.style.overflow = 'hidden';
      return new Promise((resolve) => {
        confirmResolver = resolve;
      });
    };


    const resetPosterPreview = () => {
      if (!posterPreviewImage || !posterPreviewEmpty) return;
      posterPreviewImage.classList.add('hidden');
      posterPreviewImage.removeAttribute('src');
      posterPreviewEmpty.classList.remove('hidden');
    };

    const updatePosterPreview = () => {
      const value = String(imageInput?.value || '').trim();
      if (!posterPreviewImage || !posterPreviewEmpty) return;
      if (!value) {
        resetPosterPreview();
        return;
      }
      posterPreviewImage.onload = () => {
        posterPreviewImage.classList.remove('hidden');
        posterPreviewEmpty.classList.add('hidden');
      };
      posterPreviewImage.onerror = () => {
        resetPosterPreview();
      };
      posterPreviewImage.src = value;
    };

    const toEmbedUrl = (value) => {
      const url = String(value || '').trim();
      if (!url) return '';
      const watchMatch = url.match(/[?&]v=([^&]+)/);
      if (watchMatch) return `https://www.youtube.com/embed/${watchMatch[1]}`;
      const shortMatch = url.match(/youtu\.be\/([^?&/]+)/);
      if (shortMatch) return `https://www.youtube.com/embed/${shortMatch[1]}`;
      const embedMatch = url.match(/youtube\.com\/embed\/([^?&/]+)/);
      if (embedMatch) return `https://www.youtube.com/embed/${embedMatch[1]}`;
      return url;
    };

    const resetTrailerPreview = () => {
      if (!trailerPreviewEmbed || !trailerPreviewEmpty) return;
      trailerPreviewEmbed.classList.add('hidden');
      trailerPreviewEmbed.removeAttribute('src');
      trailerPreviewEmpty.classList.remove('hidden');
    };

    const updateTrailerPreview = () => {
      const value = toEmbedUrl(trailerInput?.value || '');
      if (!trailerPreviewEmbed || !trailerPreviewEmpty) return;
      if (!value) {
        resetTrailerPreview();
        return;
      }
      trailerPreviewEmbed.src = value;
      trailerPreviewEmbed.classList.remove('hidden');
      trailerPreviewEmpty.classList.add('hidden');
    };

    const setMediaTab = (tab) => {
      const showTrailer = tab === 'trailer';
      mediaPosterPanel?.classList.toggle('hidden', showTrailer);
      mediaTrailerPanel?.classList.toggle('hidden', !showTrailer);
      mediaTabButtons.forEach((btn) => {
        const active = btn.dataset.mediaTab === tab;
        btn.classList.toggle('border-primary/40', active);
        btn.classList.toggle('bg-primary/15', active);
        btn.classList.toggle('text-primary', active);
        btn.classList.toggle('border-outline-variant/30', !active);
        btn.classList.toggle('bg-surface-container-lowest', !active);
        btn.classList.toggle('text-on-surface-variant', !active);
      });
    };

    const applyTypeUI = (type) => {
      const isMovie = type === 'pelicula';
      if (typeInput) typeInput.value = isMovie ? 'pelicula' : 'anime';
      typeButtons.forEach((btn) => {
        const active = btn.dataset.typeOption === (isMovie ? 'pelicula' : 'anime');
        btn.classList.toggle('border-primary/40', active);
        btn.classList.toggle('bg-primary/15', active);
        btn.classList.toggle('text-primary', active);
        btn.classList.toggle('border-outline-variant/30', !active);
        btn.classList.toggle('bg-surface-container-lowest', !active);
        btn.classList.toggle('text-on-surface-variant', !active);
      });
      if (pageTitle) pageTitle.textContent = isMovie ? 'A&ntilde;adir Nueva Película' : 'A&ntilde;adir Nuevo Anime';
      if (pageSubtitle) pageSubtitle.textContent = isMovie ? 'Completa los detalles para catalogar una nueva película.' : 'Completa los detalles para catalogar una nueva obra maestra.';
      if (labelTítulo) labelTítulo.textContent = isMovie ? 'T&iacute;tulo de la Película' : 'T&iacute;tulo del Anime';
      if (títuloInput) títuloInput.placeholder = isMovie ? 'Ej: Koe no Katachi' : 'Ej: Neon Genesis Evangelion';
      if (submitLabel) submitLabel.textContent = isMovie ? 'Subir Película' : 'Subir Anime';

      if (tipoFormatoSelect) {
        tipoFormatoSelect.innerHTML = isMovie
          ? `<option value="ORIGINAL">Película original</option><option value="BASED_ON_SERIES">Basada en serie</option><option value="COMPILATION">Recopilatoria</option><option value="SEQUEL">Secuela</option><option value="PREQUEL">Precuela</option><option value="SPIN_OFF">Spin-off</option>`
          : `<option value="ALL">Todos</option><option value="TV">Serie de TV</option><option value="OVA">OVA</option><option value="ONA">ONA</option><option value="SPECIAL">Especiales</option><option value="SHORT">Cortos</option>`;
      }

      if (estadoSelect) {
        estadoSelect.innerHTML = isMovie
          ? `<option value="Upcoming">Pr&oacute;ximamente</option><option value="In Theaters">En cartelera</option><option value="Finished">Finalizado</option><option value="Cancelled">Cancelado</option>`
          : `<option value="ALL">Todos</option><option value="Airing">En emisión</option><option value="Finished">Finalizado</option><option value="Upcoming">Pr&oacute;ximamente</option><option value="Cancelled">Cancelado</option>`;
      }

      if (temporadaWrap) {
        temporadaWrap.classList.toggle('hidden', isMovie);
      }
      if (durationLabel) {
        durationLabel.textContent = isMovie ? 'Duración' : 'Episodios';
      }
      if (durationInput) {
        durationInput.placeholder = isMovie ? 'Ej: 120 min' : '0';
        durationInput.value = '';
      }
    };

    typeButtons.forEach((btn) => {
      btn.addEventListener('click', () => {
        applyTypeUI(btn.dataset.typeOption);
        isDirty = true;
      });
    });
    mediaTabButtons.forEach((btn) => {
      btn.addEventListener('click', () => setMediaTab(btn.dataset.mediaTab || 'poster'));
    });

    applyTypeUI(typeInput?.value || 'anime');
    setMediaTab('poster');

    form.addEventListener('input', () => { isDirty = true; }, true);

    const warnUnsaved = () => 'Si sales de esta pantalla se borrarán los cambios no guardados. ¿Quieres continuar?';

    window.addEventListener('beforeunload', (event) => {
      if (!isDirty || isSubmitting) return;
      event.preventDefault();
      event.returnValue = '';
    });

    document.addEventListener('click', async (event) => {
      if (!isDirty || isSubmitting) return;
      const link = event.target.closest('a[href]');
      if (!link) return;
      const href = link.getAttribute('href') || '';
      if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
      const url = new URL(href, window.location.href);
      if (url.href === window.location.href) return;
      event.preventDefault();
      event.stopPropagation();
      const ok = await openConfirmModal({
        title: 'Salir sin guardar',
        message: warnUnsaved(),
        confirmText: 'Salir',
        cancelText: 'Quedarme',
        icon: 'warning'
      });
      if (ok) {
        isDirty = false;
        window.location.href = url.href;
      }
    }, true);

    document.addEventListener('keydown', async (event) => {
      if (!isDirty || isSubmitting) return;
      const wantsRefresh = event.key === 'F5' || ((event.ctrlKey || event.metaKey) && String(event.key).toLowerCase() === 'r');
      if (!wantsRefresh) return;
      event.preventDefault();
      const ok = await openConfirmModal({
        title: 'Recargar p?gina',
        message: 'Si recargas esta pantalla, se borrar? el contenido no guardado. ?Quieres continuar?',
        confirmText: 'Recargar',
        cancelText: 'Quedarme',
        icon: 'refresh'
      });
      if (ok) {
        isDirty = false;
        window.location.reload();
      }
    });
    confirmModal?.addEventListener('click', (event) => {
      if (event.target === confirmModal) closeConfirmModal(false);
    });
    confirmCancel?.addEventListener('click', () => closeConfirmModal(false));
    confirmOk?.addEventListener('click', () => closeConfirmModal(true));

    imageInput?.addEventListener('input', updatePosterPreview);
    trailerInput?.addEventListener('input', updateTrailerPreview);
    updatePosterPreview();
    updateTrailerPreview();

    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const confirmed = await openConfirmModal({
        title: 'Confirmar subida',
        message: '¿Estás seguro de subir este anime a NekoraList?',
        confirmText: 'Sí, subir',
        cancelText: 'Cancelar',
        icon: 'upload'
      });
      if (!confirmed) {
        return;
      }

      isSubmitting = true;
      const btn = form.querySelector('[type="submit"]');
      const textOrig = btn.innerText;
      btn.innerText = 'PROCESANDO...';
      btn.disabled = true;

      const generosChecks = document.querySelectorAll('input[name="in-genero"]:checked');
      const generos = Array.from(generosChecks).map(cb => cb.value);

      const payload = {
        tipo_contenido: typeInput?.value || 'anime',
        título: document.getElementById('in-título').value,
        sinopsis: document.getElementById('in-sinopsis').value,
        tipo_formato: document.getElementById('in-tipo-formato').value,
        estado: document.getElementById('in-estado').value,
        temporada: document.getElementById('in-temporada').value,
        anio: document.getElementById('in-anio').value,
        episodios: document.getElementById('in-episodios').value,
        imagen_url: document.getElementById('in-imagen').value,
        trailer_url: document.getElementById('in-trailer').value,
        generos: generos
      };

      try {
        const res = await fetch("<?= asset_path('api/admin') ?>?action=add_anime", {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        const data = await res.json();

        if (data.success) {
          isDirty = false;
          isSubmitting = false;
          toast.classList.remove('hidden');
          toast.classList.add('flex');
          form.reset();
          applyTypeUI('anime');
          setMediaTab('poster');
          updatePosterPreview();
          updateTrailerPreview();
          setTimeout(() => {
            toast.classList.add('hidden');
            toast.classList.remove('flex');
          }, 3000);
        } else {
          alert('Error del Servidor: ' + data.error);
        }
      } catch (err) {
        console.error(err);
        alert('Error de red al conectar con el servidor.');
      } finally {
        isSubmitting = false;
        btn.innerText = textOrig;
        btn.disabled = false;
      }
    });
  })();
</script>

</body></html>


