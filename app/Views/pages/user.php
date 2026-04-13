<!DOCTYPE html>
<html class="dark" lang="es">

<head>
  <script data-ui-preload>document.documentElement.classList.add("preload-ui");</script>
  <style>
    .badge-shimmer-gold {
      background: linear-gradient(120deg, rgba(251, 191, 36, 0.30) 0%, rgba(252, 211, 77, 0.5) 35%, rgba(245, 158, 11, 0.45) 60%, rgba(251, 191, 36, 0.30) 100%);
      background-size: 200% 200%;
      animation: badgeGoldShimmer 4.5s ease-in-out infinite;
    }

    @keyframes badgeGoldShimmer {
      0% {
        background-position: 0% 50%;
      }

      50% {
        background-position: 100% 50%;
      }

      100% {
        background-position: 0% 50%;
      }
    }

    .badge-shimmer {
      background: linear-gradient(120deg, rgba(56, 189, 248, 0.75) 0%, rgba(167, 139, 250, 0.9) 35%, rgba(59, 130, 246, 0.85) 60%, rgba(56, 189, 248, 0.75) 100%);
      background-size: 200% 200%;
      animation: badgeShimmer 4.5s ease-in-out infinite;
    }

    @keyframes badgeShimmer {
      0% {
        background-position: 0% 50%;
      }

      50% {
        background-position: 100% 50%;
      }

      100% {
        background-position: 0% 50%;
      }
    }

    .preload-ui body {
      opacity: 0;
    }

    .user-carousel-wrap {
      position: relative;
    }

    .user-carousel-nav-zone {
      position: absolute;
      top: 0;
      width: 64px;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 70;
      pointer-events: auto;
      background: rgba(0, 0, 0, 0);
    }

    .user-carousel-nav-zone.is-disabled {
      pointer-events: none;
    }

    .user-carousel-nav-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      opacity: 1;
      pointer-events: auto;
      transition: opacity 0.25s ease, transform 0.25s ease;
      z-index: 71;
    }

    .user-carousel-nav-btn.is-disabled {
      opacity: 0.35 !important;
      pointer-events: none;
    }

    .is-disabled {
      opacity: 0.45 !important;
      pointer-events: none !important;
      filter: grayscale(1);
    }

    .user-carousel-nav-left {
      left: -24px;
    }

    .user-carousel-nav-right {
      right: -24px;
    }

    @media (max-width: 768px) {
      .user-carousel-nav-left {
        left: -12px;
      }

      .user-carousel-nav-right {
        right: -12px;
      }
    }
  </style>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>NekoraList - Usuario</title>
  <link rel="icon" href="<?= asset_path('img/icon3.png') ?>" />
  <link
    href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Inter:wght@400;500;600&display=swap"
    rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
    rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
          borderRadius: { "DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px" }
        }
      }
    }
  </script>
  <style>
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    .glass-effect {
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
    }

    .glass-panel {
      background: linear-gradient(135deg, rgba(19, 19, 19, 0.92), rgba(36, 32, 46, 0.9));
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
    }

    .hide-scrollbar::-webkit-scrollbar {
      display: none;
    }

    .no-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }

    .transparent-scrollbar {
      scrollbar-width: thin;
      scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
    }

    .transparent-scrollbar::-webkit-scrollbar {
      width: 8px;
    }

    .transparent-scrollbar::-webkit-scrollbar-track {
      background: transparent;
    }

    .transparent-scrollbar::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.2);
      border-radius: 999px;
    }

    .modal-shell {
      background: linear-gradient(135deg, #1c1a2a 0%, #202236 45%, #24192d 100%);
      min-height: 98vh;
      height: 98vh;
      display: flex;
      flex-direction: column;
    }

    .modal-grid {
      grid-auto-rows: 1fr;
    }

    .modal-accent {
      height: 4px;
      width: 100%;
      border-radius: 999px;
      margin-bottom: 1rem;
    }

    .soft-accent {
      border: 1px solid rgba(139, 92, 246, 0.22);
      background: rgba(255, 255, 255, 0.03);
      box-shadow: 0 0 12px rgba(139, 92, 246, 0.12);
    }

    .soft-button {
      border: 1px solid rgba(139, 92, 246, 0.25);
      background: rgba(139, 92, 246, 0.08);
      box-shadow: 0 8px 20px rgba(139, 92, 246, 0.12);
    }

    #lists-modal.theme-mylist .modal-shell {
      border-color: rgba(139, 92, 246, 0.55);
      box-shadow: 0 20px 50px rgba(139, 92, 246, 0.22);
    }

    #lists-modal.theme-mylist .modal-accent {
      background: linear-gradient(90deg, rgba(139, 92, 246, 0.9), rgba(79, 70, 229, 0.65));
    }

    #lists-modal.theme-favorites .modal-shell {
      border-color: rgba(236, 72, 153, 0.55);
      box-shadow: 0 20px 50px rgba(236, 72, 153, 0.22);
    }

    #lists-modal.theme-favorites .modal-accent {
      background: linear-gradient(90deg, rgba(236, 72, 153, 0.9), rgba(168, 85, 247, 0.6));
    }

    #status-modal.theme-pending .modal-shell {
      border-color: rgba(251, 191, 36, 0.6);
      box-shadow: 0 20px 50px rgba(251, 191, 36, 0.2);
    }

    #status-modal.theme-pending .modal-accent {
      background: linear-gradient(90deg, rgba(251, 191, 36, 0.95), rgba(245, 158, 11, 0.7));
    }

    #status-modal.theme-completed .modal-shell {
      border-color: rgba(52, 211, 153, 0.6);
      box-shadow: 0 20px 50px rgba(52, 211, 153, 0.2);
    }

    #status-modal.theme-completed .modal-accent {
      background: linear-gradient(90deg, rgba(52, 211, 153, 0.95), rgba(16, 185, 129, 0.7));
    }
  </style>
  <link rel="icon" href="<?= asset_path('img/icon3.png') ?>" />


</head>

<body
  class="bg-background text-on-background font-body overflow-x-hidden selection:bg-primary-container selection:text-on-primary-container">
  <!-- Navbar Component -->
  <div data-layout="header"></div>

  <!-- VIEW: User Profile Layout -->
  <main class="relative pt-28 sm:pt-32 pb-20 px-4 sm:px-6 max-w-7xl mx-auto overflow-x-hidden overflow-y-visible">
    <div class="pointer-events-none absolute -top-40 right-[-10%] h-72 w-72 rounded-full bg-violet-500/15 blur-3xl">
    </div>
    <div class="pointer-events-none absolute top-16 left-[-10%] h-72 w-72 rounded-full bg-sky-500/15 blur-3xl"></div>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start overflow-visible">
      <section class="relative z-[200] isolate group lg:col-span-6">
        <div id="profile-card"
          class="glass-panel relative z-[210] w-full rounded-3xl overflow-hidden border border-white/10 shadow-[0_30px_80px_rgba(0,0,0,0.45)] overflow-visible">
          <div class="h-1 w-full bg-gradient-to-r from-sky-400 via-violet-400 to-primary"></div>
          <div class="p-8 md:p-10 pb-0 space-y-8">
            <div class="flex flex-wrap items-start justify-between gap-4">
              <div>
                <h2 class="font-headline text-2xl md:text-3xl font-black italic tracking-tight text-primary uppercase">
                  IDENTIDAD NEKORA</h2>
                <p class="text-on-surface-variant font-label text-[10px] tracking-widest" data-profile-id-display>ID:
                  ...</p>
              </div>
              <div class="flex items-center gap-2 -mr-4">
                <span
                  class="bg-emerald-500/15 text-emerald-300 px-3 py-1 rounded-full font-label text-[10px] font-bold tracking-widest uppercase inline-flex items-center gap-1.5"
                  data-identity-badge>
                  <span class="material-symbols-outlined text-[14px] leading-none" data-identity-icon>nightlight</span>
                  <span data-identity-text>Modo Dream</span>
                </span>
                <button
                  class="rounded-full bg-surface-container-low/70 border border-white/10 w-8 h-8 flex items-center justify-center text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-colors -mt-1 -ml-1"
                  type="button" id="profile-settings-btn" aria-label="Ajustes de perfil">
                  <span class="material-symbols-outlined text-[18px]">settings</span>
                </button>
              </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-[auto,1fr] gap-8 items-start">
              <div class="flex flex-col items-center lg:items-start gap-4">
                <div
                  class="relative w-36 h-36 rounded-2xl p-1 bg-gradient-to-br from-violet-500 via-fuchsia-500 to-sky-500 shadow-[0_0_30px_rgba(139,92,246,0.45)] flex-shrink-0">
                  <div class="w-full h-full rounded-2xl bg-surface-container-lowest p-1">
                    <img alt="Foto de perfil" class="w-full h-full object-cover rounded-2xl" data-profile-avatar
                      src="https://upload.wikimedia.org/wikipedia/en/b/bd/Doraemon_character.png" />
                  </div>
                </div>
              </div>
              <div class="space-y-5 text-center lg:text-left">
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3">
                  <h1 class="font-headline text-4xl md:text-5xl font-extrabold tracking-tight text-on-surface">
                    Cargando...</h1>
                </div>
                <p class="text-on-surface-variant font-medium whitespace-pre-line">...</p>
              </div>
              <div class="lg:col-span-2 space-y-3">
                <div class="grid w-full grid-cols-3 gap-2 text-left">
                  <div class="rounded-2xl border border-white/10 bg-white/5 px-3 py-1.5 w-full">
                    <div class="text-[10px] uppercase tracking-widest text-on-surface-variant">Miembro desde</div>
                    <div class="mt-1 text-sm font-semibold text-on-surface" data-profile-member>2018</div>
                  </div>
                  <div class="rounded-2xl border border-white/10 bg-white/5 px-3 py-1.5 w-full">
                    <div class="text-[10px] uppercase tracking-widest text-on-surface-variant">&Uacute;ltimo acceso</div>
                    <div class="mt-1 text-sm font-semibold text-on-surface" data-profile-last>Hoy</div>
                  </div>
                  <div class="rounded-2xl border border-white/10 bg-white/5 px-3 py-1.5 w-full">
                    <div class="text-[10px] uppercase tracking-widest text-on-surface-variant">Horas vistas</div>
                    <div class="mt-1 text-sm font-semibold text-on-surface" data-profile-hours>0 h</div>
                  </div>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 overflow-hidden">
                  <div class="text-[10px] uppercase tracking-widest text-on-surface-variant mb-2 text-center w-full">
                    Preferencias</div>
                  <div class="flex flex-wrap gap-2 items-center justify-center min-h-[32px]" data-pref-display>
                    <span
                      class="rounded-full border border-white/10 bg-white/10 inline-flex items-center justify-center px-4 py-1 text-xs text-center text-on-surface-variant h-7 truncate shrink-0">Sin
                      preferencias</span>
                  </div>
                </div>
                <div class="relative z-[220] mt-3 flex w-full flex-wrap items-center justify-center gap-x-3 gap-y-2 translate-y-2 pointer-events-auto"
                  id="profile-actions">
                  <button id="request-title-btn" type="button" aria-label="Solicitar t&iacute;tulo"
                    class="hidden rounded-full border border-white/10 bg-gradient-to-r from-sky-400 to-violet-400 px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-900 transition-all duration-200 hover:border-sky-200/60 hover:-translate-y-0.5 hover:shadow-[0_10px_24px_rgba(56,189,248,0.25)]">
                    Solicitar t&iacute;tulo
                  </button>
                  <button id="profile-logout-btn" type="button" aria-label="Cerrar sesi&oacute;n" onclick="var m=document.getElementById('logout-confirm'); if(m){m.classList.remove('hidden'); m.classList.add('flex'); document.body.style.overflow='hidden';} return false;"
                    class="relative z-[230] pointer-events-auto rounded-full border border-white/10 bg-gradient-to-r from-rose-400 to-orange-300 px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-900 transition-all duration-200 hover:border-rose-200/60 hover:-translate-y-0.5 hover:shadow-[0_10px_24px_rgba(248,113,113,0.25)] inline-flex items-center justify-center group/logout">Cerrar sesi&oacute;n</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="relative z-0 lg:col-span-6 lg:h-full grid grid-cols-1 sm:grid-cols-2 gap-5 mt-0 overflow-visible">
        <button type="button" data-open-list="my-list"
          class="group rounded-3xl border border-white/10 bg-surface-container-low/70 p-6 text-left shadow-[0_16px_40px_rgba(0,0,0,0.35)] transition-all hover:-translate-y-1 hover:border-violet-400/60 hover:shadow-[0_24px_60px_rgba(139,92,246,0.35)]">
          <div class="flex items-center justify-between">
            <div class="text-xs uppercase tracking-[0.2em] text-on-surface-variant">Mi lista</div>
            <span class="material-symbols-outlined text-violet-400">playlist_add</span>
          </div>
          <div class="mt-4 text-4xl font-bold text-on-surface" data-count-mylist>0</div>
          <p class="mt-2 text-sm text-on-surface-variant">T&iacute;tulos guardados para ver despu&eacute;s.</p>
        </button>
        <button type="button" data-open-list="favorites"
          class="group rounded-3xl border border-white/10 bg-surface-container-low/70 p-6 text-left shadow-[0_16px_40px_rgba(0,0,0,0.35)] transition-all hover:-translate-y-1 hover:border-fuchsia-400/60 hover:shadow-[0_24px_60px_rgba(236,72,153,0.35)]">
          <div class="flex items-center justify-between">
            <div class="text-xs uppercase tracking-[0.2em] text-on-surface-variant">Favoritos</div>
            <span class="material-symbols-outlined text-fuchsia-400">favorite</span>
          </div>
          <div class="mt-4 text-4xl font-bold text-on-surface" data-count-favorites>0</div>
          <p class="mt-2 text-sm text-on-surface-variant">Tus historias imprescindibles.</p>
        </button>
        <button type="button" data-open-status="pending"
          class="group rounded-3xl border border-white/10 bg-surface-container-low/70 p-6 text-left shadow-[0_16px_40px_rgba(0,0,0,0.35)] transition-all hover:-translate-y-1 hover:border-amber-400/60 hover:shadow-[0_24px_60px_rgba(251,191,36,0.35)]">
          <div class="flex items-center justify-between">
            <div class="text-xs uppercase tracking-[0.2em] text-on-surface-variant">Pendientes</div>
            <span class="material-symbols-outlined text-amber-300">schedule</span>
          </div>
          <div class="mt-4 text-4xl font-bold text-on-surface" data-count-pending>0</div>
          <p class="mt-2 text-sm text-on-surface-variant">Lo pr&oacute;ximo en tu marat&oacute;n.</p>
        </button>
        <button type="button" data-open-status="completed"
          class="group rounded-3xl border border-white/10 bg-surface-container-low/70 p-6 text-left shadow-[0_16px_40px_rgba(0,0,0,0.35)] transition-all hover:-translate-y-1 hover:border-emerald-400/60 hover:shadow-[0_24px_60px_rgba(52,211,153,0.35)]">
          <div class="flex items-center justify-between">
            <div class="text-xs uppercase tracking-[0.2em] text-on-surface-variant">Completados</div>
            <span class="material-symbols-outlined text-emerald-400">done_all</span>
          </div>
          <div class="mt-4 text-4xl font-bold text-on-surface" data-count-completed>0</div>
          <p class="mt-2 text-sm text-on-surface-variant">Historias finalizadas con &eacute;xito.</p>
        </button>
        <section id="continue-section"
          class="sm:col-span-2 rounded-3xl border border-white/5 bg-surface-container-low/60 p-5 shadow-[0_18px_40px_rgba(0,0,0,0.35)] overflow-visible min-h-[260px]">
          <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
              <h2 class="font-headline text-2xl font-bold">Continuar viendo</h2>
            </div>
          </div>
          <div class="mt-3 flex min-h-[180px] items-center justify-center overflow-visible">
            <div id="continue-empty"
              class="rounded-2xl border border-white/10 bg-white/5 px-4 py-8 text-center text-sm text-on-surface-variant">
              A&uacute;n no tienes t&iacute;tulos en progreso.
            </div>
            <div id="continue-grid" class="hidden w-full grid grid-cols-4 gap-4 overflow-visible pb-2 pt-3 px-4"></div>
          </div>
        </section>
        <div class="sm:col-span-2 flex min-h-[120px] items-center justify-center py-0">
          <a id="premium-access-btn" href="<?= route_path('payment') ?>"
            class="rounded-full bg-gradient-to-br from-sky-500 to-violet-500 px-9 py-4 text-base font-bold tracking-wide text-white shadow-lg transition-transform hover:scale-105 active:scale-95 hover:ring-2 hover:ring-white/40 hover:shadow-[0_0_18px_rgba(129,140,248,0.5)] inline-flex items-center justify-center">
            Acceso Premium
          </a>
        </div>
      </section>
    </div>

    <div id="profile-lists" class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-0">
      <div class="lg:col-span-8 space-y-8 mt-3">
        <!-- Favoritos: Movido arriba para mayor relevancia -->
        <section
          class="space-y-6 rounded-3xl border border-white/5 bg-surface-container-low/60 p-6 shadow-[0_18px_40px_rgba(0,0,0,0.35)]">
          <div class="relative z-[120] flex flex-wrap justify-between items-end gap-4">
            <div>
              <h2 class="font-headline text-2xl font-bold">Favoritos</h2>
              <p class="text-sm text-on-surface-variant">Las historias que te marcaron.</p>
            </div>
            <div class="relative z-[130] flex items-center gap-2 pointer-events-auto">
              <button
                class="relative z-[140] cursor-pointer pointer-events-auto text-primary text-sm font-bold underline-offset-4 hover:underline px-3 py-1.5 rounded-full border border-primary/30 bg-primary/10 hover:bg-primary/20 transition inline-flex items-center gap-1.5"
                type="button" data-open-list="favorites" aria-disabled="false"
                onclick="var m=document.getElementById('lists-modal');var t=document.getElementById('lists-modal-title');var fav=document.getElementById('favorites-modal');var my=document.getElementById('my-list-modal');var cFav=document.getElementById('lists-count-favorites');var cMy=document.getElementById('lists-count-mylist');if(m){m.classList.remove('theme-mylist','theme-favorites','hidden');m.classList.add('theme-favorites','flex');if(t)t.textContent='Favoritos';if(fav)fav.classList.remove('hidden');if(my)my.classList.add('hidden');if(cFav)cFav.classList.remove('hidden');if(cMy)cMy.classList.add('hidden');document.body.style.overflow='hidden';}return false;">
                <span>Mi lista</span>
                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
              </button>
            </div>
          </div>
          <div class="user-carousel-wrap">
            <div class="user-carousel-nav-zone user-carousel-nav-left">
              <button
                class="user-carousel-nav-btn rounded-full bg-surface-container-high/90 px-4 py-3 text-2xl font-bold text-violet-200 hover:text-white hover:bg-surface-container-high transition-colors backdrop-blur"
                type="button" data-scroll-left="favorites-grid" aria-label="Desplazar a la izquierda">&lsaquo;</button>
            </div>
            <div class="user-carousel-nav-zone user-carousel-nav-right">
              <button
                class="user-carousel-nav-btn rounded-full bg-surface-container-high/90 px-4 py-3 text-2xl font-bold text-violet-200 hover:text-white hover:bg-surface-container-high transition-colors backdrop-blur"
                type="button" data-scroll-right="favorites-grid" aria-label="Desplazar a la derecha">&rsaquo;</button>
            </div>
            <div id="favorites-grid"
              class="flex gap-4 overflow-x-auto no-scrollbar overflow-visible py-6 scroll-smooth snap-x snap-mandatory">
            </div>
          </div>
        </section>

        <!-- Mi Lista: Movido abajo -->
        <section
          class="space-y-6 rounded-3xl border border-white/5 bg-surface-container-low/60 p-6 shadow-[0_18px_40px_rgba(0,0,0,0.35)]">
          <div class="flex flex-wrap justify-between items-end gap-4">
            <div>
              <h2 class="font-headline text-2xl font-bold">Mi Lista</h2>
              <p class="text-sm text-on-surface-variant">Tus t&iacute;tulos listos para continuar.</p>
            </div>
            <div class="flex items-center gap-2">
              <button
                class="text-primary text-sm font-bold underline-offset-4 hover:underline px-3 py-1.5 rounded-full border border-primary/30 bg-primary/10 hover:bg-primary/20 transition inline-flex items-center gap-1.5"
                type="button" data-open-list="my-list" aria-disabled="false">
                <span>Mi lista</span>
                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
              </button>
            </div>
          </div>
          <div class="user-carousel-wrap">
            <div class="user-carousel-nav-zone user-carousel-nav-left">
              <button
                class="user-carousel-nav-btn rounded-full bg-surface-container-high/90 px-4 py-3 text-2xl font-bold text-violet-200 hover:text-white hover:bg-surface-container-high transition-colors backdrop-blur"
                type="button" data-scroll-left="my-list-grid" aria-label="Desplazar a la izquierda">&lsaquo;</button>
            </div>
            <div class="user-carousel-nav-zone user-carousel-nav-right">
              <button
                class="user-carousel-nav-btn rounded-full bg-surface-container-high/90 px-4 py-3 text-2xl font-bold text-violet-200 hover:text-white hover:bg-surface-container-high transition-colors backdrop-blur"
                type="button" data-scroll-right="my-list-grid" aria-label="Desplazar a la derecha">&rsaquo;</button>
            </div>
            <div id="my-list-grid"
              class="flex gap-4 overflow-x-auto no-scrollbar overflow-visible py-6 scroll-smooth snap-x snap-mandatory">
            </div>
          </div>
        </section>

      </div>

      <aside class="lg:col-span-4 space-y-6 mt-3">
        <section
          class="space-y-6 rounded-3xl border border-white/5 bg-gradient-to-b from-surface-container-low to-surface-container-high/70 p-6 shadow-lg">
          <div class="flex items-center justify-between">
            <h2 class="font-headline text-xl font-bold text-on-surface">Recomendados</h2>
            <a class="text-[10px] font-bold uppercase tracking-widest text-primary hover:text-primary-dim transition-colors"
                href="<?= route_path('featured') ?>">Ver todo</a>
          </div>
          <div class="grid grid-cols-1 gap-2 mt-4">
            <!-- Anime 1: FMAB -->
            <button
              class="w-full bg-surface-container-low/80 hover:bg-surface-container-high rounded-2xl p-2.5 flex items-center gap-4 border border-white/5 hover:border-violet-400/40 transition-all cursor-pointer relative group"
              onclick="window.location.href='<?= detail_path('5114', 'Fullmetal Alchemist: Brotherhood') ?>'">
              <span class="absolute top-2.5 right-3 rounded-full bg-violet-500/90 px-3 py-1 text-[8px] font-extrabold uppercase tracking-widest text-white shadow-sm">Anime</span>
              <img src="https://cdn.myanimelist.net/images/anime/1223/96541.jpg" alt="FMAB" class="w-11 h-15 object-cover rounded-lg shadow-md group-hover:scale-105 transition-transform" />
              <div class="text-left flex-1 min-w-0">
                <p class="text-[13px] font-bold text-on-surface leading-tight truncate">F.M.A. Brotherhood</p>
                <p class="text-[10px] text-on-surface-variant mt-0.5">Acci&oacute;n - Fantas&iacute;a</p>
              </div>
            </button>
            <!-- Movie 1: Your Name -->
            <button
              class="w-full bg-surface-container-low/80 hover:bg-surface-container-high rounded-2xl p-2.5 flex items-center gap-4 border border-white/5 hover:border-fuchsia-400/40 transition-all cursor-pointer relative group"
              onclick="window.location.href='<?= detail_path('32281', 'Kimi no Na wa.') ?>'">
              <span class="absolute top-2.5 right-3 rounded-full bg-fuchsia-500/90 px-3 py-1 text-[8px] font-extrabold uppercase tracking-widest text-white shadow-sm">Pel&iacute;cula</span>
              <img src="https://cdn.myanimelist.net/images/anime/5/87048.jpg" alt="Your Name" class="w-11 h-15 object-cover rounded-lg shadow-md group-hover:scale-105 transition-transform" />
              <div class="text-left flex-1 min-w-0">
                <p class="text-[13px] font-bold text-on-surface leading-tight truncate">Your Name</p>
                <p class="text-[10px] text-on-surface-variant mt-0.5">Drama - Romance</p>
              </div>
            </button>
            <!-- Anime 2: Hunter x Hunter -->
            <button
              class="w-full bg-surface-container-low/80 hover:bg-surface-container-high rounded-2xl p-2.5 flex items-center gap-4 border border-white/5 hover:border-violet-400/40 transition-all cursor-pointer relative group"
              onclick="window.location.href='<?= detail_path('11061', 'Hunter x Hunter') ?>'">
              <span class="absolute top-2.5 right-3 rounded-full bg-violet-500/90 px-3 py-1 text-[8px] font-extrabold uppercase tracking-widest text-white shadow-sm">Anime</span>
              <img src="https://cdn.myanimelist.net/images/anime/1337/99013.jpg" alt="HxH" class="w-11 h-15 object-cover rounded-lg shadow-md group-hover:scale-105 transition-transform" />
              <div class="text-left flex-1 min-w-0">
                <p class="text-[13px] font-bold text-on-surface leading-tight truncate">Hunter x Hunter</p>
                <p class="text-[10px] text-on-surface-variant mt-0.5">Acci&oacute;n - Aventura</p>
              </div>
            </button>
            <!-- Movie 2: Suzume -->
            <button
              class="w-full bg-surface-container-low/80 hover:bg-surface-container-high rounded-2xl p-2.5 flex items-center gap-4 border border-white/5 hover:border-fuchsia-400/40 transition-all cursor-pointer relative group"
              onclick="window.location.href='<?= detail_path('50796', 'Suzume') ?>'">
              <span class="absolute top-2.5 right-3 rounded-full bg-fuchsia-500/90 px-3 py-1 text-[8px] font-extrabold uppercase tracking-widest text-white shadow-sm">Pel&iacute;cula</span>
              <img src="https://image.tmdb.org/t/p/w200/m99O9idLpYv4hC8pDAbL68Y6N02.jpg" alt="Suzume" class="w-11 h-15 object-cover rounded-lg shadow-md group-hover:scale-105 transition-transform" />
              <div class="text-left flex-1 min-w-0">
                <p class="text-[13px] font-bold text-on-surface leading-tight truncate">Suzume no Tojimari</p>
                <p class="text-[10px] text-on-surface-variant mt-0.5">Aventura - Fantas&iacute;a</p>
              </div>
            </button>
            <!-- Anime 3: Jujutsu Kaisen -->
            <button
              class="w-full bg-surface-container-low/80 hover:bg-surface-container-high rounded-2xl p-2.5 flex items-center gap-4 border border-white/5 hover:border-violet-400/40 transition-all cursor-pointer relative group"
              onclick="window.location.href='<?= detail_path('40748', 'Jujutsu Kaisen') ?>'">
              <span class="absolute top-2.5 right-3 rounded-full bg-violet-500/90 px-3 py-1 text-[8px] font-extrabold uppercase tracking-widest text-white shadow-sm">Anime</span>
              <img src="https://cdn.myanimelist.net/images/anime/1171/109222.jpg" alt="JJK" class="w-11 h-15 object-cover rounded-lg shadow-md group-hover:scale-105 transition-transform" />
              <div class="text-left flex-1 min-w-0">
                <p class="text-[13px] font-bold text-on-surface leading-tight truncate">Jujutsu Kaisen</p>
                <p class="text-[10px] text-on-surface-variant mt-0.5">Acci&oacute;n - Sobrenatural</p>
              </div>
            </button>
            <!-- Movie 3: A Silent Voice -->
            <button
              class="w-full bg-surface-container-low/80 hover:bg-surface-container-high rounded-2xl p-2.5 flex items-center gap-4 border border-white/5 hover:border-fuchsia-400/40 transition-all cursor-pointer relative group"
              onclick="window.location.href='<?= detail_path('28851', 'Koe no Katachi') ?>'">
              <span class="absolute top-2.5 right-3 rounded-full bg-fuchsia-500/90 px-3 py-1 text-[8px] font-extrabold uppercase tracking-widest text-white shadow-sm">Pel&iacute;cula</span>
              <img src="https://cdn.myanimelist.net/images/anime/1122/96435.jpg" alt="Silent Voice" class="w-11 h-15 object-cover rounded-lg shadow-md group-hover:scale-105 transition-transform" />
              <div class="text-left flex-1 min-w-0">
                <p class="text-[13px] font-bold text-on-surface leading-tight truncate">A Silent Voice</p>
                <p class="text-[10px] text-on-surface-variant mt-0.5">Drama - Escolar</p>
              </div>
            </button>
            <!-- Anime 4: Steins;Gate -->
            <button
              class="w-full bg-surface-container-low/80 hover:bg-surface-container-high rounded-2xl p-2.5 flex items-center gap-4 border border-white/5 hover:border-violet-400/40 transition-all cursor-pointer relative group"
              onclick="window.location.href='<?= detail_path('9253', 'Steins;Gate') ?>'">
              <span class="absolute top-2.5 right-3 rounded-full bg-violet-500/90 px-3 py-1 text-[8px] font-extrabold uppercase tracking-widest text-white shadow-sm">Anime</span>
              <img src="https://cdn.myanimelist.net/images/anime/5/73199.jpg" alt="Steins;Gate" class="w-11 h-15 object-cover rounded-lg shadow-md group-hover:scale-105 transition-transform" />
              <div class="text-left flex-1 min-w-0">
                <p class="text-[13px] font-bold text-on-surface leading-tight truncate">Steins;Gate</p>
                <p class="text-[10px] text-on-surface-variant mt-0.5">Sci-Fi - Suspenso</p>
              </div>
            </button>
          </div>
          <button 
            onclick="window.location.href='destacados'"
            class="mt-4 w-full rounded-2xl bg-violet-500/10 py-3 text-xs font-bold uppercase tracking-widest text-violet-400 hover:bg-violet-500/20 hover:text-violet-300 hover:scale-[1.01] transition-all border border-violet-500/20 shadow-lg shadow-violet-500/5">
            Ver m&aacute;s
          </button>
        </section>
      </aside>
    </div>
  </main>

  <!-- Avatar Picker Modal -->
  <div id="avatar-modal" class="fixed inset-0 z-[2000] hidden">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" data-avatar-close></div>
    <div class="relative mx-auto mt-20 sm:mt-28 w-[92%] sm:w-[86%] max-w-[580px] rounded-lg bg-surface-container-high p-4 shadow-2xl">
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-headline text-xl font-bold">Elige tu nuevo avatar</h3>
        <button
          class="rounded-full bg-surface-container-low w-8 h-8 flex items-center justify-center text-xs font-bold text-on-surface-variant hover:text-on-surface"
          type="button" data-avatar-close aria-label="Cerrar">x</button>
      </div>
      <p class="text-sm text-on-surface-variant mb-4">Selecciona una imagen para tu perfil.</p>
      <div class="max-h-[420px] overflow-y-auto pr-2 transparent-scrollbar">
        <div id="avatar-grid" class="grid grid-cols-5 gap-3"></div>
      </div>
    </div>
  </div>

  <!-- Edit Profile Modal -->
  <div id="profile-modal" class="fixed inset-0 z-[2000] hidden flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" data-profile-close></div>
    <div
      class="relative w-full max-w-[29rem] rounded-2xl border border-white/10 bg-surface-container-high p-5 shadow-[0_24px_60px_rgba(0,0,0,0.5)]">
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-headline text-xl font-bold">Editar perfil</h3>
        <button
          class="rounded-full bg-surface-container-low w-8 h-8 flex items-center justify-center text-xs font-bold text-on-surface-variant hover:text-on-surface"
          type="button" data-profile-close aria-label="Cerrar">x</button>
      </div>
      <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-2 transparent-scrollbar">
        <div class="flex items-center gap-4 rounded-2xl p-4 soft-accent">
          <img data-profile-avatar-preview src="https://upload.wikimedia.org/wikipedia/en/b/bd/Doraemon_character.png"
            alt="Avatar actual" class="w-14 h-14 rounded-xl object-cover" />
          <div class="flex-1">
            <p class="text-xs uppercase tracking-widest text-on-surface-variant">Foto de perfil</p>
            <button id="profile-avatar-open"
              class="mt-2 inline-flex items-center gap-2 rounded-full px-4 py-2 text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:text-on-surface soft-button"
              type="button">
              <span class="material-symbols-outlined text-[16px]">image</span>
              Cambiar avatar
            </button>
          </div>
        </div>
        <div class="space-y-2 soft-accent p-4 rounded-2xl">
          <label class="text-xs uppercase tracking-widest text-on-surface-variant font-semibold">Nombre de
            usuario</label>
          <input id="profile-name-input"
            class="w-full rounded-xl bg-surface-container-low border border-outline-variant/60 px-4 py-3 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40"
            type="text" placeholder="Tu nombre" />
        </div>
        <div class="space-y-2 soft-accent p-4 rounded-2xl">
          <label class="text-xs uppercase tracking-widest text-on-surface-variant font-semibold">Descripci&oacute;n</label>
          <textarea id="profile-desc-input" maxlength="200"
            class="w-full rounded-xl bg-surface-container-low border border-outline-variant/60 px-4 py-3 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 min-h-[120px]"
            placeholder="Escribe una breve descripci&oacute;n (m&aacute;x. 200 caracteres)"></textarea>
          <div class="text-[11px] text-on-surface-variant text-right" id="profile-desc-count">0/200</div>
        </div>
        <div class="space-y-3 soft-accent p-4 rounded-2xl">
          <label
            class="text-xs uppercase tracking-widest text-on-surface-variant font-semibold text-center block w-full">Preferencias</label>
          <div id="preferences-picker" class="space-y-3"></div>
          <p class="text-[11px] text-on-surface-variant text-center">Selecciona varias opciones y se ver&aacute;n
            reflejadas en tu perfil.</p>
        </div>
        <div class="space-y-2">
          <label class="text-xs uppercase tracking-widest text-on-surface-variant font-semibold">Color del
            recuadro</label>
          <div id="profile-color-options" class="flex flex-wrap gap-3">
            <button type="button" data-color-class="bg-gradient-to-br from-zinc-950 via-zinc-900 to-zinc-800"
              class="w-9 h-9 rounded-full bg-gradient-to-br from-zinc-950 via-zinc-900 to-zinc-800 border border-zinc-700 transition-transform duration-300 hover:scale-110 hover:shadow-[0_0_18px_rgba(255,255,255,0.18)]"></button>
            <button type="button"
              data-color-class="bg-gradient-to-br from-violet-900/50 via-indigo-900/40 to-slate-900/30"
              class="w-9 h-9 rounded-full bg-gradient-to-br from-violet-900/50 via-indigo-900/40 to-slate-900/30 border border-violet-400/40 transition-transform duration-300 hover:scale-110 hover:shadow-[0_0_18px_rgba(139,92,246,0.35)]"></button>
            <button type="button"
              data-color-class="bg-gradient-to-br from-fuchsia-900/50 via-rose-900/40 to-slate-900/30"
              class="w-9 h-9 rounded-full bg-gradient-to-br from-fuchsia-900/50 via-rose-900/40 to-slate-900/30 border border-rose-400/40 transition-transform duration-300 hover:scale-110 hover:shadow-[0_0_18px_rgba(244,114,182,0.35)]"></button>
            <button type="button"
              data-color-class="bg-gradient-to-br from-emerald-900/50 via-teal-900/40 to-slate-900/30"
              class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-900/50 via-teal-900/40 to-slate-900/30 border border-emerald-400/40 transition-transform duration-300 hover:scale-110 hover:shadow-[0_0_18px_rgba(52,211,153,0.35)]"></button>
            <button type="button" data-color-class="bg-gradient-to-br from-sky-900/50 via-cyan-900/40 to-slate-900/30"
              class="w-9 h-9 rounded-full bg-gradient-to-br from-sky-900/50 via-cyan-900/40 to-slate-900/30 border border-sky-400/40 transition-transform duration-300 hover:scale-110 hover:shadow-[0_0_18px_rgba(56,189,248,0.35)]"></button>
            <button type="button"
              data-color-class="bg-gradient-to-br from-amber-900/50 via-orange-900/40 to-slate-900/30"
              class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-900/50 via-orange-900/40 to-slate-900/30 border border-amber-400/40 transition-transform duration-300 hover:scale-110 hover:shadow-[0_0_18px_rgba(251,191,36,0.35)]"></button>
            <button type="button" data-color-class="bg-gradient-to-br from-lime-900/50 via-green-900/40 to-slate-900/30"
              class="w-9 h-9 rounded-full bg-gradient-to-br from-lime-900/50 via-green-900/40 to-slate-900/30 border border-lime-400/40 transition-transform duration-300 hover:scale-110 hover:shadow-[0_0_18px_rgba(163,230,53,0.35)]"></button>
            <button type="button" data-color-class="bg-gradient-to-br from-red-900/50 via-orange-900/40 to-slate-900/30"
              class="w-9 h-9 rounded-full bg-gradient-to-br from-red-900/50 via-orange-900/40 to-slate-900/30 border border-red-400/40 transition-transform duration-300 hover:scale-110 hover:shadow-[0_0_18px_rgba(248,113,113,0.35)]"></button>
            <button type="button"
              data-color-class="bg-gradient-to-br from-blue-900/50 via-indigo-900/40 to-slate-900/30"
              class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-900/50 via-indigo-900/40 to-slate-900/30 border border-blue-400/40 transition-transform duration-300 hover:scale-110 hover:shadow-[0_0_18px_rgba(96,165,250,0.35)]"></button>
            <button type="button"
              data-color-class="bg-gradient-to-br from-purple-900/50 via-pink-900/40 to-slate-900/30"
              class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-900/50 via-pink-900/40 to-slate-900/30 border border-pink-400/40 transition-transform duration-300 hover:scale-110 hover:shadow-[0_0_18px_rgba(236,72,153,0.35)]"></button>
          </div>
        </div>
        <div class="flex justify-end gap-3 pt-2">
          <button
            class="rounded-full border border-white/10 bg-surface-container-low px-4 py-2 text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:text-on-surface"
            type="button" data-profile-close>Cancelar</button>
          <button id="profile-save-btn"
            class="rounded-full bg-primary/90 text-on-primary px-6 py-2 text-xs font-bold uppercase tracking-widest shadow-[0_10px_24px_rgba(139,92,246,0.3)] hover:scale-105 active:scale-95 transition-transform"
            type="button">Guardar</button>
        </div>
      </div>


    </div>
  </div>

  <!-- Request Title Modal -->
  <div id="request-title-modal" class="fixed inset-0 z-[2000] hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" data-request-close></div>
    <div
      class="relative w-[92%] sm:w-[70%] max-w-[340px] rounded-2xl border border-white/10 bg-surface-container-high p-5 shadow-[0_20px_50px_rgba(0,0,0,0.45)]">
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-headline text-base font-bold">Solicitar t&iacute;tulo</h3>
        <button
          class="rounded-full bg-surface-container-low w-7 h-7 flex items-center justify-center text-xs font-bold text-on-surface-variant hover:text-on-surface"
          type="button" data-request-close aria-label="Cerrar">x</button>
      </div>
      <form id="request-title-form" class="space-y-4">
        <div class="space-y-1.5">
          <label class="text-xs uppercase tracking-widest text-on-surface-variant font-semibold">Anime o pel&iacute;cula</label>
          <input id="request-title-input" type="text"
            class="w-full rounded-xl border border-white/10 bg-surface-container-low/70 px-3 py-2 text-sm text-on-surface outline-none focus:border-primary/50 focus:ring-2 focus:ring-primary/20"
            placeholder="Ej: Attack on Titan" />
        </div>
        <div class="space-y-1.5">
          <label class="text-xs uppercase tracking-widest text-on-surface-variant font-semibold">Tipo</label>
          <select id="request-title-type"
            class="w-full rounded-xl border border-white/10 bg-surface-container-low/70 px-3 py-2 text-sm text-on-surface outline-none focus:border-primary/50 focus:ring-2 focus:ring-primary/20">
            <option value="Anime">Anime</option>
            <option value="Pel&iacute;cula">Pel&iacute;cula</option>
          </select>
        </div>
        <div class="flex items-center justify-end gap-2 pt-1">
          <button type="button" data-request-close
            class="rounded-full border border-white/10 bg-surface-container-low/70 px-4 py-2 text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:text-on-surface transition-colors">Cancelar</button>
          <button type="submit"
            class="rounded-full border border-white/10 bg-gradient-to-r from-sky-400 to-violet-400 px-4 py-2 text-xs font-bold uppercase tracking-widest text-slate-900 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-[0_8px_20px_rgba(56,189,248,0.3)]">Solicitar</button>
        </div>
      </form>
    </div>
  </div>


  <!-- Request Toast -->
  <div id="request-toast" class="fixed inset-0 z-[2000] hidden items-center justify-center">
    <div
      class="rounded-full border border-white/10 bg-surface-container-high/90 px-6 py-3 text-sm font-semibold text-on-surface shadow-[0_18px_40px_rgba(0,0,0,0.45)]">
      T&iacute;tulo solicitado
    </div>
  </div>

  <!-- Logout Confirm Modal -->
  <div id="logout-confirm" class="fixed inset-0 z-[2000] hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" data-logout-close onclick="var m=document.getElementById('logout-confirm'); if(m){m.classList.add('hidden'); m.classList.remove('flex');} return false;"></div>
    <div
      class="relative mx-auto w-[92%] sm:w-[86%] max-w-xs rounded-2xl border border-white/10 bg-surface-container-high p-5 text-center shadow-[0_20px_50px_rgba(0,0,0,0.45)]">
      <h3 class="font-headline text-lg font-bold">&iquest;Seguro que quieres cerrar sesi&oacute;n?</h3>
      <div class="mt-4 flex items-center justify-center gap-3">
        <button type="button" data-logout-cancel onclick="var m=document.getElementById('logout-confirm'); if(m){m.classList.add('hidden'); m.classList.remove('flex');} return false;"
          class="rounded-full border border-white/10 bg-surface-container-low/70 px-4 py-2 text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:text-on-surface transition-colors">
          cancelar
        </button>
        <button type="button" data-logout-confirm
          class="rounded-full border border-white/10 bg-gradient-to-r from-rose-400 to-orange-300 px-4 py-2 text-xs font-bold uppercase tracking-widest text-slate-900 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-[0_10px_24px_rgba(248,113,113,0.25)]">
          cerrar
        </button>
      </div>
    </div>
  </div>

  <!-- Full Lists Modal -->
  <div id="lists-modal" class="fixed inset-0 z-[500] hidden theme-mylist flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" data-lists-close></div>
    <div class="relative w-[94%] sm:w-[82%] md:w-[72%] lg:w-[62%] max-w-lg rounded-3xl border border-white/10 p-4 md:p-4 modal-shell">
      <div class="modal-accent"></div>
      <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
        <div>
          <h3 class="font-headline text-2xl font-bold" id="lists-modal-title">Mi Lista</h3>
          <p class="text-sm text-on-surface-variant">Explora todos tus t&iacute;tulos guardados.</p>
        </div>
        <div class="flex items-center gap-2">
          <span id="lists-count-mylist"
            class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-on-surface-variant">Mi lista:
            <span data-count-mylist>0</span></span>
          <span id="lists-count-favorites"
            class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-on-surface-variant">Favoritos:
            <span data-count-favorites>0</span></span>
          <button
            class="rounded-full bg-surface-container-low w-9 h-9 flex items-center justify-center text-xs font-bold text-on-surface-variant hover:text-on-surface"
            type="button" data-lists-close aria-label="Cerrar">
            <span class="material-symbols-outlined text-[18px]">close</span>
          </button>
        </div>
      </div>
      <div id="lists-modal-body" class="flex-1 overflow-y-auto overflow-x-hidden pr-1 pb-2 transparent-scrollbar">
        <div id="my-list-modal" class="space-y-4 hidden">
          <div id="my-list-grid-full" data-show-all="1" class="grid grid-cols-5 gap-2 modal-grid"></div>
        </div>
        <div id="favorites-modal" class="space-y-4 hidden">
          <div id="favorites-grid-full" data-show-all="1" class="grid grid-cols-5 gap-2 modal-grid"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Status Modal -->
  <div id="status-modal" class="fixed inset-0 z-[500] hidden theme-completed flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" data-status-close></div>
    <div class="relative w-[94%] sm:w-[82%] md:w-[72%] lg:w-[62%] max-w-lg rounded-3xl border border-white/10 p-4 md:p-4 modal-shell">
      <div class="modal-accent"></div>
      <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
        <div>
          <h3 class="font-headline text-2xl font-bold" id="status-modal-title">Completados</h3>
          <p class="text-sm text-on-surface-variant">Tus avances organizados por estado.</p>
        </div>
        <div class="flex items-center gap-2">
          <span id="status-count-completed"
            class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-on-surface-variant">Completados:
            <span data-count-completed>0</span></span>
          <span id="status-count-pending"
            class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-on-surface-variant">Pendientes:
            <span data-count-pending>0</span></span>
          <button
            class="rounded-full bg-surface-container-low w-9 h-9 flex items-center justify-center text-xs font-bold text-on-surface-variant hover:text-on-surface"
            type="button" data-status-close aria-label="Cerrar">
            <span class="material-symbols-outlined text-[18px]">close</span>
          </button>
        </div>
      </div>
      <div id="status-modal-body" class="flex-1 overflow-y-auto overflow-x-hidden pr-1 pb-2 transparent-scrollbar">
        <div id="completed-section" class="space-y-4 hidden">
          <div id="completed-grid" class="grid grid-cols-5 gap-2 modal-grid"></div>
        </div>
        <div id="pending-section" class="space-y-4 hidden">
          <div id="pending-grid" class="grid grid-cols-5 gap-2 modal-grid"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer Component -->
  <div data-layout="footer"></div>
  <script src="<?= asset_path('assets/js/shared-utils.js?v=1') ?>"></script>
  <script src="<?= asset_path('assets/js/layout.js?v=theme1') ?>"></script>
  <script src="<?= asset_path('assets/js/i18n.js') ?>"></script>
  <script src="<?= asset_path('assets/js/title-images.js?v=3') ?>"></script>
  <script src="<?= asset_path('assets/js/search.js?v=popular7') ?>"></script>
  <script src="<?= asset_path('assets/js/favorites.js?v=4') ?>"></script>
  <script src="<?= asset_path('assets/js/detail-links.js?v=6') ?>"></script>
  <script src="<?= asset_path('assets/js/user-page.js?v=2') ?>"></script>
  <script src="<?= asset_path('assets/js/user-page-rescue.js?v=10') ?>"></script>
  <script data-ui-unlock>document.documentElement.classList.remove("preload-ui");</script>
</body>

</html>



















