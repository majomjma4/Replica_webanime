
<!DOCTYPE html>
<html class="dark" lang="en"><head>
    <script data-ui-preload>document.documentElement.classList.add("preload-ui");</script>
    <style>
  .preload-ui body { opacity: 0; }
          @media (max-width: 1024px) {
        #detail-hero {
          height: auto;
          min-height: 0;
        }
        #detail-hero-content {
          gap: 2rem;
          padding-left: 1.25rem;
          padding-right: 1.25rem;
          padding-bottom: 2rem;
        }
        #detail-trailer-surface {
          width: 40%;
        }
      }
      @media (max-width: 768px) {
        #detail-hero {
          margin-top: 0;
        }
        #detail-hero-content {
          flex-direction: column;
          align-items: flex-start;
          justify-content: flex-end;
          padding-left: 1rem;
          padding-right: 1rem;
          padding-bottom: 1.5rem;
          gap: 1.5rem;
        }
        #detail-hero-content > div {
          width: 100%;
        }
        #detail-trailer-card {
          width: min(94vw, 1100px);
        }
      }
      @media (max-width: 640px) {
        #detail-trailer-surface {
          display: none !important;
        }
        #detail-hero-content h1 {
          font-size: 2.2rem !important;
          line-height: 1.05;
        }
        #detail-status-meta {
          flex-wrap: wrap;
          gap: 0.5rem 0.9rem;
        }
        #comments-total {
          min-width: 0;
          width: 100%;
        }
        .comments-filter-card,
        .comments-report-card {
          width: 100% !important;
          max-width: none !important;
        }
      }
</style>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <link rel="icon" href="<?= asset_path('img/icon3.webp') ?>" />
    <title><?= e(!empty($detailQuery) ? ucwords($detailQuery) : 'Cargando...') ?> | NekoraList</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&amp;family=Inter:wght@400;500;600&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
            borderRadius: {"DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px"},
          },
        },
      }
    </script>
    <style>
      .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
      }
      .hide-scrollbar::-webkit-scrollbar { display: none; }
      .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
      .carousel-row {
        --cards: 5;
        --gap: 1rem;
        --pad: 2.5rem;
        display: flex;
        gap: var(--gap);
        justify-content: flex-start;
        padding-inline: var(--pad);
        scroll-snap-type: x mandatory;
        scroll-padding-inline: var(--pad);
        overflow-x: auto !important;
        overflow-y: visible !important;
      }
      .carousel-card {
        flex: 0 0 calc((100% - (var(--cards) - 1) * var(--gap)) / var(--cards));
        scroll-snap-align: start;
        scroll-snap-stop: always;
        position: relative;
        z-index: 0;
      }
      .carousel-card:hover {
        z-index: 3;
      }
      body.trailer-open #detail-hero-content {
        filter: blur(4px);
        opacity: 0.55;
      }
      body.trailer-open #detail-trailer-modal {
        opacity: 1;
        pointer-events: auto;
      }
      body.trailer-open #detail-trailer-card {
        transform: scale(1);
      }
      body.trailer-open #detail-trailer-surface {
        opacity: 0;
        pointer-events: none;
      }
      body.trailer-open #detail-bg-video {
        opacity: 0 !important;
      }
      body.trailer-open #detail-bg-image {
        opacity: 0.9 !important;
      }
      #detail-bg-image,
      #detail-bg-video {
        object-position: center;
      }
      body.bg-video-off #detail-bg-video {
        opacity: 0 !important;
      }
      body.bg-video-off #detail-bg-image {
        opacity: 0.9 !important;
      }
      #detail-video-fade {
        position: absolute;
        inset: 0;
        z-index: 25;
        background: linear-gradient(90deg, rgba(0,0,0,1) 0%, rgba(0,0,0,0.95) 28%, rgba(0,0,0,0.6) 50%, rgba(0,0,0,0.25) 65%, rgba(0,0,0,0) 75%);
        pointer-events: none;
      }
      .video-fade-left {
        -webkit-mask-image: linear-gradient(90deg, transparent 0%, rgba(0,0,0,0.2) 15%, #000 45%, #000 100%);
        mask-image: linear-gradient(90deg, transparent 0%, rgba(0,0,0,0.2) 15%, #000 45%, #000 100%);
      }
      body.bg-video-on #detail-bg-video {
        opacity: 0.75;
      }
      body.bg-video-on #detail-bg-image {
        opacity: 0;
      }
      body.bg-video-original #detail-bg-video {
        opacity: 1;
        filter: none;
        transform: scale(1.2);
        object-fit: contain;
        object-position: right center;
      }
      .poster-fade-left::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(0deg, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.55) 35%, rgba(0,0,0,0) 70%);
        pointer-events: none;
      }
      .poster-fade-left {
        -webkit-mask-image: linear-gradient(0deg, transparent 0%, #000 20%, #000 100%);
        mask-image: linear-gradient(0deg, transparent 0%, #000 20%, #000 100%);
      }
    </style>
  </head>
  <body data-detail-ref="<?= e($detailRef ?? "") ?>" data-detail-query="<?= e($detailQuery ?? "") ?>" class="bg-background text-on-background font-body selection:bg-primary-container selection:text-on-primary-container">
    <!-- Navbar Component -->
    <div data-layout="header"></div>
    <main class="pt-20 overflow-x-hidden">
      <!-- Hero Section -->
      <section id="detail-hero" class="relative h-[614px] min-h-[500px] w-full mt-4 lg:mt-6">
        <!-- Background Backdrop -->
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
          <div class="absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-background/90 via-background/60 to-transparent z-10 pointer-events-none"></div>
          <video id="detail-bg-video" class="video-fade-left absolute inset-0 w-full h-full object-cover blur-sm scale-105 opacity-0 transition-all duration-700 pointer-events-none" playsinline muted loop></video>
          <img id="detail-bg-image" alt="Background Art" class="video-fade-left w-full h-full object-cover blur-sm scale-105 opacity-65 transition-all duration-700 pointer-events-none" data-alt="Cinematic abstract anime background with purple tones" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDrqwEEjoe5bagUJbWKcZWgC45ngsX0Z17k_3KXCOCfT-JxhGHibHsZF4XcWgUYFx9Cx8f28ZNY6rb5d7wQv2ZovPkapLlkV7YF3kgGjCosBXxE-XhQg955xcpecwdqgnUiE5W_A5a7FKB7JqqvzJscbnC9HKRKx64Vn0S1eatS7-omITY7gD9ku4SliLgqEvBHqdey4s3iIW35GUuucJiPf8TYxzlfsbIj6SEhhfz53BJ2H4xbXAJhCbej1WUWLeBDxQrnWicVY-cD"/>
          <div id="detail-video-fade"></div>
        </div>
        <div class="absolute inset-x-0 -bottom-16 h-56 bg-gradient-to-t from-black/90 via-background/65 to-transparent z-10 pointer-events-none"></div>
        <div class="absolute inset-x-0 -bottom-10 h-36 bg-gradient-to-t from-black/75 via-black/40 to-transparent z-10 pointer-events-none"></div>
        <button id="detail-trailer-surface" class="absolute inset-y-0 right-0 w-1/2 h-full z-30 hidden transition-all duration-500 cursor-pointer group" type="button" aria-label="Reproducir trailer">
          <span class="absolute inset-0 bg-transparent"></span>
          <span class="absolute inset-0 flex items-center justify-end pr-[20rem]">
            <span id="detail-trailer-icon" class="material-symbols-outlined text-8xl text-white/35 drop-shadow-[0_0_16px_rgba(0,0,0,0.7)] transition-all duration-500 opacity-30 group-hover:opacity-65 group-hover:text-white/70 group-hover:drop-shadow-[0_0_24px_rgba(59,130,246,0.45)] hidden">play_arrow</span>
          </span>
        </button>
        <div id="detail-trailer-modal" class="absolute inset-0 z-30 hidden flex items-center justify-center opacity-0 pointer-events-none transition-all duration-500">
          <div id="detail-trailer-overlay" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
          <div id="detail-trailer-card" class="relative w-[min(88vw,1100px)] aspect-video bg-black/80 rounded-2xl overflow-hidden border border-white/10 shadow-[0_0_30px_rgba(0,0,0,0.45)] scale-95 transition-all duration-500">
            <button id="detail-trailer-close" class="absolute top-3 right-3 z-20 w-9 h-9 rounded-full bg-black/60 text-white/80 hover:text-white hover:bg-black/80 transition-all duration-500" type="button" aria-label="Cerrar trailer">X</button>
            <video id="detail-trailer-video" class="w-full h-full object-cover" playsinline controls></video>
          </div>
        </div>
        <!-- Content Container -->
        <div id="detail-hero-content" class="relative z-20 max-w-7xl mx-auto h-full px-8 flex items-end pb-12 gap-12 transition-all duration-500 blur-0 opacity-100 pointer-events-none">
          <!-- Large Poster Card -->
          <div class="hidden md:block w-72 lg:w-80 shrink-0 transform -translate-y-4 pointer-events-auto">
            <div class="relative aspect-[2/3] rounded-lg overflow-hidden shadow-[0px_30px_60px_rgba(0,0,0,0.6)] bg-surface-container-high">
              <img alt="Solo Leveling Poster" class="w-full h-full object-cover" data-alt="Anime protagonist character art vertical poster" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBXTXqdDIpKJHKrJNqlN-s7KMfzwFvt6rOtGu823T9YwAdyi6UEeanLdk9bqVlZ8ho5vTFT7MDZ6vzPK4KY7BJP13NbLY_vk_gooV2eHcmjbyP9weV8jSsNAnMZ_QJT54fqop6etgY343bdhrCO--xxETu162oetRJCyr0HpWKNw2NpQCSK024fF7PvE7RiypLSPQDTXxOG-TO4cAplVg_TuTwzEooVYnPehX57TkY2_pMDoTFuAYFkM9vyCyuQz0AljMmSIz1r-XDr"/>
</div>
          </div>
          <!-- Info Block -->
          <div class="flex-1 flex flex-col gap-6 pointer-events-auto">
            <div class="space-y-2">
              <div class="flex items-center gap-3">
                <span id="detail-badge" class="px-3 py-1 bg-primary/10 border border-primary/20 text-primary-dim text-xs font-bold rounded-full tracking-wider uppercase">Destacado</span>
                <span class="flex items-center gap-1 text-primary-dim">
                  <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
                  <span class="font-bold text-lg">8.7</span>
                  <span class="text-on-surface-variant text-sm font-normal ml-1">Puntuaci&oacute;n</span>
                </span>
              </div>
              <h1 class="font-headline text-5xl lg:text-7xl font-extrabold tracking-tighter text-on-surface"><?php if(!empty($detailQuery)): ?><?= e(ucwords($detailQuery)) ?><?php else: ?><span class="inline-block animate-pulse bg-surface-container-higher/80 rounded-xl h-[48px] lg:h-[72px] w-[60%]"></span><?php endif; ?></h1>
              <p id="detail-status-meta" class="text-on-surface-variant font-medium flex gap-4 text-sm lg:text-base">
                <span class="flex items-center gap-2">Estado: <span class="inline-block animate-pulse bg-surface-container-highest rounded h-4 w-16"></span></span>
                <span class="text-outline-variant">&gt;&lt;</span>
                <span class="flex items-center gap-2">Episodios: <span class="inline-block animate-pulse bg-surface-container-highest rounded h-4 w-8"></span></span>
                <span class="text-outline-variant">&gt;&lt;</span>
                <span class="flex items-center gap-2">Duraci&oacute;n: <span class="inline-block animate-pulse bg-surface-container-highest rounded h-4 w-16"></span></span>
              </p>
            </div>
            <div id="detail-genres" class="flex flex-wrap gap-2">
              <span class="px-4 py-1.5 bg-surface-container-high text-on-surface-variant text-sm rounded-full border border-outline-variant/10">Acci&oacute;n</span>
              <span class="px-4 py-1.5 bg-surface-container-high text-on-surface-variant text-sm rounded-full border border-outline-variant/10">Fantas&iacute;a</span>
              <span class="px-4 py-1.5 bg-surface-container-high text-on-surface-variant text-sm rounded-full border border-outline-variant/10">Aventura</span>
            </div>
            <div class="flex flex-wrap gap-4 pt-2 items-center">
              <a data-add-my-list class="px-8 py-3.5 bg-transparent border border-sky-400/40 text-on-surface font-bold rounded-full hover:border-sky-400/70 hover:bg-surface-container-high transition-all flex items-center gap-2 shadow-[0_0_14px_rgba(56,189,248,0.25)] hover:shadow-[0_0_22px_rgba(56,189,248,0.45)]" href="<?= route_path('user') ?>"><span class="material-symbols-outlined">add</span><span data-add-label>A&ntilde;adir a Mi Lista</span></a>
<?php if ($isLoggedIn): ?>
              <button data-add-favorite class="hidden group w-12 h-12 p-0 bg-transparent border border-rose-400/40 text-on-surface-variant rounded-full hover:border-rose-400/70 hover:bg-surface-container-high transition-all flex items-center justify-center relative shadow-[0_0_14px_rgba(244,63,94,0.3)] hover:shadow-[0_0_22px_rgba(244,63,94,0.55)]" type="button"><span class="material-symbols-outlined text-[20px]">favorite</span><span data-fav-label class="pointer-events-none absolute left-1/2 top-full mt-2 -translate-x-1/2 whitespace-nowrap rounded-full bg-surface-container-high px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-on-surface opacity-0 transition-opacity duration-200 group-hover:opacity-100">Agregar a Favoritos</span></button>
              <button data-detail-status="completed" class="hidden group w-12 h-12 p-0 bg-transparent border border-emerald-400/40 text-on-surface-variant rounded-full hover:border-emerald-400/70 hover:bg-surface-container-high transition-all flex items-center justify-center relative shadow-[0_0_14px_rgba(16,185,129,0.3)] hover:shadow-[0_0_22px_rgba(16,185,129,0.55)]" type="button">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                <span data-detail-status-label class="pointer-events-none absolute left-1/2 top-full mt-2 -translate-x-1/2 whitespace-nowrap rounded-full bg-surface-container-high px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-on-surface opacity-0 transition-opacity duration-200 group-hover:opacity-100">Completado</span>
              </button>
              <button data-detail-status="pending" class="hidden group w-12 h-12 p-0 bg-transparent border border-amber-400/40 text-on-surface-variant rounded-full hover:border-amber-400/70 hover:bg-surface-container-high transition-all flex items-center justify-center relative shadow-[0_0_14px_rgba(251,191,36,0.3)] hover:shadow-[0_0_22px_rgba(251,191,36,0.55)]" type="button">
                <span class="material-symbols-outlined text-[20px]">schedule</span>
                <span data-detail-status-label class="pointer-events-none absolute left-1/2 top-full mt-2 -translate-x-1/2 whitespace-nowrap rounded-full bg-surface-container-high px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-on-surface opacity-0 transition-opacity duration-200 group-hover:opacity-100">Pendiente</span>
              </button>
<?php
endif; ?>
            </div>
          </div>
        </div>
      </section>
      <!-- Detailed Content Area -->
      <section class="max-w-7xl mx-auto px-8 py-20 grid grid-cols-1 lg:grid-cols-3 gap-16">
        <!-- Left Column: Sinopsis, Galería y Personajes -->
        <div class="lg:col-span-2 space-y-12 detail-left-col">
          <!-- Synopsis -->
          <div class="space-y-6">
            <h2 class="font-headline text-3xl font-bold">Sinopsis</h2>
            <p class="text-on-surface-variant leading-relaxed text-lg font-light max-w-3xl">
              In a world where hunters, humans who possess magical abilities, must battle deadly monsters to protect the human race from certain annihilation, a notoriously weak hunter named Sung Jinwoo finds himself in a seemingly endless struggle for survival.
              <br/><br/>
              After narrowly surviving an overwhelmingly powerful double dungeon that nearly wipes out his entire party, a mysterious program called the System chooses him as its sole player and in turn, gives him the extremely rare ability to level up in strength, possibly beyond any known limits.
            </p>
          </div>
        </div>
        <!-- Right Column: Información -->
        <div class="space-y-10">
          <div class="bg-surface-container-low rounded-lg p-8 border border-outline-variant/5 space-y-8">
            <div id="detail-info-block" class="space-y-6">
              <div class="flex flex-col gap-1">
                <h3 class="font-headline text-xl font-bold border-b border-outline-variant/10 pb-4">Información</h3>
                <span class="mt-2 text-on-surface font-medium inline-block animate-pulse bg-surface-container-highest rounded h-5 w-32"></span>
              </div>
              <div class="flex flex-col gap-1">
                <span class="text-xs text-on-surface-variant uppercase tracking-widest font-bold">Studio</span>
                <span class="text-primary font-medium inline-block animate-pulse bg-surface-container-highest rounded h-5 w-24"></span>
              </div>
              <div class="flex flex-col gap-1">
                <span class="text-xs text-on-surface-variant uppercase tracking-widest font-bold">Source</span>
                <span class="text-on-surface font-medium inline-block animate-pulse bg-surface-container-highest rounded h-5 w-28"></span>
              </div>
              <div class="flex flex-col gap-1">
                <span class="text-xs text-on-surface-variant uppercase tracking-widest font-bold">Aired</span>
                <span class="text-on-surface font-medium inline-block animate-pulse bg-surface-container-highest rounded h-5 w-20"></span>
              </div>
              <div class="flex flex-col gap-1">
                <span class="text-xs text-on-surface-variant uppercase tracking-widest font-bold">Rating</span>
                <span class="text-on-surface font-medium inline-block animate-pulse bg-surface-container-highest rounded h-5 w-32"></span>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- Gallery + Characters (Full Width) -->
      <section class="w-full max-w-none px-8 md:px-16 pb-16 space-y-12">
        <div id="detail-episodes-host"></div>
        <div id="detail-media" class="space-y-6"></div>
        <div class="space-y-8">
          <div class="flex items-center justify-between">
            <h2 class="font-headline text-3xl font-bold">Personajes</h2>
          </div>
          <div class="relative overflow-x-hidden">
            <button id="chars-prev" class="absolute left-0 top-1/2 -translate-y-1/2 z-10 w-9 h-9 rounded-full bg-surface-container-high/80 border border-outline-variant/20 text-primary flex items-center justify-center shadow-md" type="button"><span class="material-symbols-outlined text-base">chevron_left</span></button>
            <button id="chars-next" class="absolute right-0 top-1/2 -translate-y-1/2 z-10 w-9 h-9 rounded-full bg-surface-container-high/80 border border-outline-variant/20 text-primary flex items-center justify-center shadow-md" type="button"><span class="material-symbols-outlined text-base">chevron_right</span></button>
            <div id="chars-row" class="flex gap-4 overflow-x-auto pb-4 hide-scrollbar scroll-smooth">
            <div class="flex flex-col items-center gap-3 shrink-0">
              <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-primary/20 p-1">
                <img alt="Jinwoo" class="w-full h-full object-cover rounded-full" data-alt="Anime character portrait circular crop" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBjKtfx1NJtFTsDXvl4sLc-dIGd3LIH7h0Bdmu0SG3BL-T9eZrgPnb7OdButxKtXyal1v6NgHAKsx6sjhSEhB5bFopGB5Of_DUV-N66b_5ZppqVMWGIKxdHNEOVRUeGyIFM94SVqZSLaKZWkwYedNNfY0Ras76mQHXAruWbVw-WmDXedmiPPAKGlV9mXKq4_J5FwJZqpMDUVDpSlNTF40i-DYHdY_oj2Rj2FDEy3ZSavwbvrWRclJ1BNR3OaE6MxbkfXptHjmiKSgGc"/>
              </div>
              <span class="text-sm font-bold">Sung Jinwoo</span>
              <span class="text-xs text-on-surface-variant">Protagonista</span>
            </div>
            <div class="flex flex-col items-center gap-3 shrink-0">
              <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-transparent p-1">
                <img alt="Hae-In" class="w-full h-full object-cover rounded-full" data-alt="Anime character portrait circular crop" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCrHgfJFm12i5ndbns7J4SrJ8yleY2Q91SSq4ZLfdJVYipHQUH-5aLCKyvEum8dqcI9WIr-L1S_xpKXkDF49GRwYdmg-zR-AkxuCXcG8tIRse7qQJ2nykDY8vzk6jO-QBDPmBEV8gcHHU0FKp6UY1NqpCxwEe5Wbk1cztc_8dm9lvhkZMM2-cfyqjLpWD63nNx515O3YSzkOw51IfGaPW9E3vrR06Dx-krOzjgv0dGZAYyXqLpYljVwkpYMABzwDYvIii8PBvZ6xhnZ"/>
              </div>
              <span class="text-sm font-bold">Cha Hae-In</span>
              <span class="text-xs text-on-surface-variant">Cazador de Rango S</span>
            </div>
            <div class="flex flex-col items-center gap-3 shrink-0">
              <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-transparent p-1">
                <img alt="Gun-Hee" class="w-full h-full object-cover rounded-full" data-alt="Anime character portrait circular crop" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDVTnhUPiLiKxzepHIltmKZvgRv-amk6DpzKR4ZNslnHI5bq9VqxjDH5d0154ELAmO5OxoWDc2bLEQ6Fc-xJtmi9GU5nh6rmBFLTaNSMrHLhqWBFnoW2VwjvkXtH4PGtSl3JgvFUi7sa0mVXsaN42qw_j9fWgv714lsggyrzKbPGyjAO-VWwubYK0lDZvLQ-CQL-oYRoTP6u-4jXYD2Uj3-7Dd4J9VwEqMWyUJzKSUfzZHptsqeQWcy--KCkOG1D03PhYC_SwWxf7pm"/>
              </div>
              <span class="text-sm font-bold">Go Gun-Hee</span>
              <span class="text-xs text-on-surface-variant">Presidente</span>
            </div>
            <div class="flex flex-col items-center gap-3 shrink-0">
              <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-transparent p-1">
                <img alt="Jin-Ah" class="w-full h-full object-cover rounded-full" data-alt="Anime character portrait circular crop" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCbkd52ec0RY40oQaRpreQb-P4RfWsSUOtsy4QY58-xXI4uWKQXxtSqp01A8JjdTM0-AygX0ruKQY61eSEWXUPfRUwW3x52llAP2D9iJ7-djTlQjVAzvWtp8dPCDaaJLIV8VTscyK__8_8gvIA4niK0OyG_ME84yfbOk2x4lE76ghNdXh7dlD-SLOsPMEzUlHu1zB7nHMDS-LLBrhiSAwgY9sCT2tJtaq07AzfZdwRe3ND1lWc1eJi_K1LbVZBroZFag0fHtqIk2eOU"/>
              </div>
              <span class="text-sm font-bold">Sung Jin-Ah</span>
              <span class="text-xs text-on-surface-variant">Estudiante</span>
            </div>
            <div class="flex flex-col items-center gap-3 shrink-0">
              <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-transparent p-1">
                <img alt="Joo-Hee" class="w-full h-full object-cover rounded-full" data-alt="Anime character portrait circular crop" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBHX-e4mL71PhUWTl9q84UzFeYVQ9g8y50zUFPxJGAxGg5tcrOvkPOTPXvj8F-w4xyxY7ISh1f31yIbWjUlB9LGL357YjzbFhbiZ25iJXsmoYHscTT-CgjjszGa53_B97OdMWjOQtSpEpik3I4aTubNQA_xEpCuki_vXZu4YOxzBA2K8L2pHJh9BEz40IYqLIa0ZJy9FID01jymCJxkrH57n5mKBOzXxXD7G--5vC84VHr9mWjt6WYCGteFvrvRVYMFn39dIrAEl5Nx"/>
              </div>
              <span class="text-sm font-bold">Lee Joo-Hee</span>
              <span class="text-xs text-on-surface-variant">Sanador</span>
            </div>
          </div>
        </div>
        </div>
      </section>
      <!-- Recommended Section -->
      <section class="max-w-7xl mx-auto px-8 pb-32 space-y-10">
        <div class="flex items-end justify-between">
          <div>
            <h2 class="font-headline text-4xl font-extrabold tracking-tight">Recomendado para Ti</h2>
            <p class="text-on-surface-variant mt-2">Otros t&iacute;tulos que te pueden interesar</p>
          </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
          <!-- Card 1 -->
          <a class="group cursor-pointer" href="<?= detail_path('' , 'Tower of God') ?>"><div class="poster-fade-left relative aspect-[2/3] rounded-lg overflow-hidden bg-surface-container-high relative mb-4 transition-transform duration-500 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04]"><img alt="Tower of God" class="w-full h-full object-cover" data-alt="Anime fantasy poster vertical" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDtqxKcGTcymR4IJol4SxeiIj-BJNATnZcsnFB2ZMYRTa74Vr0wv3vAZ6CEWT1rbGPSDGJrUxs1F6uIcQHQ8iKXtmZIvFLACNlD4UPMFWExAc8CUG2cd_tFeTP0tpM1N_eQ0OkRniEpPz16OEyZjUhmIpXAREqccEnQwbppVkAmyIwXrZjM_7S4FmDqxkG50F5ZTWxzmiWTlDi8zorXn_C5j-ZqIId_lDc6EU00FED-4xW5ht_oYkS0j8kh05o0Kt6g_KJgoKmdrqvi"/><div class="absolute top-3 right-3 bg-neutral-950/80 backdrop-blur-md px-2 py-1 rounded-md flex items-center gap-1"><span class="material-symbols-outlined text-[14px] text-primary" style="font-variation-settings: 'FILL' 1;">star</span> <span class="text-xs font-bold">8.1</span></div></div><h4 class="font-bold text-on-surface group-hover:text-primary transition-colors truncate">Tower of God</h4><p class="text-xs text-on-surface-variant mt-1">Adventure, Mystery</p></a>
          <!-- Card 2 -->
          <a class="group cursor-pointer" href="<?= detail_path('' , 'Your Name') ?>"><div class="poster-fade-left relative aspect-[2/3] rounded-lg overflow-hidden bg-surface-container-high relative mb-4 transition-transform duration-500 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04]"><img alt="Your Name" class="w-full h-full object-cover" data-alt="Anime romance poster vertical" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDtqxKcGTcymR4IJol4SxeiIj-BJNATnZcsnFB2ZMYRTa74Vr0wv3vAZ6CEWT1rbGPSDGJrUxs1F6uIcQHQ8iKXtmZIvFLACNlD4UPMFWExAc8CUG2cd_tFeTP0tpM1N_eQ0OkRniEpPz16OEyZjUhmIpXAREqccEnQwbppVkAmyIwXrZjM_7S4FmDqxkG50F5ZTWxzmiWTlDi8zorXn_C5j-ZqIId_lDc6EU00FED-4xW5ht_oYkS0j8kh05o0Kt6g_KJgoKmdrqvi"/><div class="absolute top-3 right-3 bg-neutral-950/80 backdrop-blur-md px-2 py-1 rounded-md flex items-center gap-1"><span class="material-symbols-outlined text-[14px] text-primary" style="font-variation-settings: 'FILL' 1;">star</span> <span class="text-xs font-bold">8.9</span></div></div><h4 class="font-bold text-on-surface group-hover:text-primary transition-colors truncate">Your Name</h4><p class="text-xs text-on-surface-variant mt-1">Romance, Drama</p></a>
          <!-- Card 3 -->
          <a class="group cursor-pointer" href="<?= detail_path('' , 'Solo Leveling') ?>"><div class="poster-fade-left relative aspect-[2/3] rounded-lg overflow-hidden bg-surface-container-high relative mb-4 transition-transform duration-500 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04]"><img alt="Solo Leveling" class="w-full h-full object-cover" data-alt="Anime action poster vertical" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBXTXqdDIpKJHKrJNqlN-s7KMfzwFvt6rOtGu823T9YwAdyi6UEeanLdk9bqVlZ8ho5vTFT7MDZ6vzPK4KY7BJP13NbLY_vk_gooV2eHcmjbyP9weV8jSsNAnMZ_QJT54fqop6etgY343bdhrCO--xxETu162oetRJCyr0HpWKNw2NpQCSK024fF7PvE7RiypLSPQDTXxOG-TO4cAplVg_TuTwzEooVYnPehX57TkY2_pMDoTFuAYFkM9vyCyuQz0AljMmSIz1r-XDr"/><div class="absolute top-3 right-3 bg-neutral-950/80 backdrop-blur-md px-2 py-1 rounded-md flex items-center gap-1"><span class="material-symbols-outlined text-[14px] text-primary" style="font-variation-settings: 'FILL' 1;">star</span> <span class="text-xs font-bold">8.7</span></div></div><h4 class="font-bold text-on-surface group-hover:text-primary transition-colors truncate">Solo Leveling</h4><p class="text-xs text-on-surface-variant mt-1">Action, Fantasy</p></a>
          <!-- Card 4 -->
          <a class="group cursor-pointer" href="<?= detail_path('' , 'Chainsaw Man') ?>"><div class="poster-fade-left relative aspect-[2/3] rounded-lg overflow-hidden bg-surface-container-high relative mb-4 transition-transform duration-500 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04]"><img alt="Chainsaw Man" class="w-full h-full object-cover" data-alt="Anime horror poster vertical" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB3K_I8d_R8f6D6S8o7E4U6P7uN9eX5zT6wQ7zT6wQ7zT6wQ7zT6wQ7zT6wQ7zT6wQ7zT6wQ7zT6wQ7zT6wQ7zT6w"/><div class="absolute top-3 right-3 bg-neutral-950/80 backdrop-blur-md px-2 py-1 rounded-md flex items-center gap-1"><span class="material-symbols-outlined text-[14px] text-primary" style="font-variation-settings: 'FILL' 1;">star</span> <span class="text-xs font-bold">8.5</span></div></div><h4 class="font-bold text-on-surface group-hover:text-primary transition-colors truncate">Chainsaw Man</h4><p class="text-xs text-on-surface-variant mt-1">Action, Horror</p></a>
          <!-- Card 5 -->
          <a class="group cursor-pointer" href="<?= detail_path('' , 'Spy x Family') ?>"><div class="poster-fade-left relative aspect-[2/3] rounded-lg overflow-hidden bg-surface-container-high relative mb-4 transition-transform duration-500 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04]"><img alt="Spy x Family" class="w-full h-full object-cover" data-alt="Anime comedy poster vertical" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDtqxKcGTcymR4IJol4SxeiIj-BJNATnZcsnFB2ZMYRTa74Vr0wv3vAZ6CEWT1rbGPSDGJrUxs1F6uIcQHQ8iKXtmZIvFLACNlD4UPMFWExAc8CUG2cd_tFeTP0tpM1N_eQ0OkRniEpPz16OEyZjUhmIpXAREccEnQwbppVkAmyIwXrZjM_7S4FmDqxkG50F5ZTWxzmiWTlDi8zorXn_C5j-ZqIId_lDc6EU00FED-4xW5ht_oYkS0j8kh05o0Kt6g_KJgoKmdrqvi"/><div class="absolute top-3 right-3 bg-neutral-950/80 backdrop-blur-md px-2 py-1 rounded-md flex items-center gap-1"><span class="material-symbols-outlined text-[14px] text-primary" style="font-variation-settings: 'FILL' 1;">star</span> <span class="text-xs font-bold">8.6</span></div></div><h4 class="font-bold text-on-surface group-hover:text-primary transition-colors truncate">Spy x Family</h4><p class="text-xs text-on-surface-variant mt-1">Comedy, Action</p></a>
        </div>
      </section>
      <!-- Comments Section -->
      <section class="max-w-7xl mx-auto px-8 pb-32 space-y-8" id="comments-section">
        <div class="flex flex-wrap items-end justify-between gap-4">
          <div>
            <h2 class="font-headline text-4xl font-extrabold tracking-tight">Comentarios</h2>
            <p class="text-on-surface-variant mt-2">Comparte tu opini&oacute;n y califica el t&iacute;tulo con estrellitas.</p>
          </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-[1.1fr,0.9fr] gap-8">
          <form id="comment-form" class="relative rounded-3xl border border-white/10 bg-surface-container-low/60 p-6 md:p-8 shadow-[0_20px_50px_rgba(0,0,0,0.4)] space-y-6 overflow-hidden">
            <!-- Bloqueo Premium Estilo Episodios Como Enlace Real -->
            <a id="comment-lock-overlay" href="<?= route_path('payment') ?>" class="absolute inset-0 bg-black/65 backdrop-blur-md flex flex-col items-center justify-center z-[40] transition-all duration-500 rounded-3xl border border-violet-500/30 cursor-pointer group/lock no-underline <?= $sessionPremium ? "hidden" : "" ?>" <?= $sessionPremium ? "style=\"display:none;pointer-events:none\" aria-hidden=\"true\"" : "" ?>>
              <div class="relative flex flex-col items-center justify-center">
                 <div class="absolute -inset-4 bg-gradient-to-r from-violet-600 to-fuchsia-600 rounded-2xl blur-xl opacity-20 group-hover/lock:opacity-50 transition duration-700"></div>
                 <div class="relative flex flex-col items-center gap-4 bg-surface-container-high/95 px-10 py-8 rounded-2xl border border-white/10 shadow-2xl transform transition-all group-hover/lock:scale-[1.03] group-hover/lock:border-violet-400/40">
                    <div class="w-16 h-16 rounded-full bg-violet-500/10 flex items-center justify-center border border-violet-500/20 text-violet-400 group-hover/lock:bg-violet-500 group-hover/lock:text-white transition-all duration-300">
                      <span class="material-symbols-outlined text-4xl">lock</span>
                    </div>
                    <div class="text-center px-4">
                      <span class="block text-white font-bold text-lg tracking-tight mb-1" id="lock-message">INICIA SESI&Oacute;N PARA COMENTAR</span>
                      <span class="text-[11px] text-on-surface-variant/70 uppercase tracking-widest leading-none">DISPONIBLE PARA MIEMBROS NEKORA PREMIUM</span>
                    </div>
                 </div>
              </div>
            </a>

            <div>
              <h3 class="font-headline text-2xl font-bold">Deja tu comentario</h3>
              <p class="text-sm text-on-surface-variant mt-1">S&eacute; breve, claro y respetuoso.</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 space-y-3">
              <div class="flex items-center justify-between gap-3">
                <span class="text-xs uppercase tracking-[0.3em] text-on-surface-variant">Puntuaci&oacute;n</span>
                <span id="rating-value" class="text-sm font-semibold text-on-surface">0/5</span>
              </div>
              <div class="flex items-center gap-2" id="rating-stars">
                <button type="button" class="comment-star text-on-surface-variant transition-all duration-200" data-star="1" aria-label="1 estrella">
                  <span class="material-symbols-outlined text-[24px] transition-transform duration-200" style="font-variation-settings: 'FILL' 1;">star</span>
                </button>
                <button type="button" class="comment-star text-on-surface-variant transition-all duration-200" data-star="2" aria-label="2 estrellas">
                  <span class="material-symbols-outlined text-[24px] transition-transform duration-200" style="font-variation-settings: 'FILL' 1;">star</span>
                </button>
                <button type="button" class="comment-star text-on-surface-variant transition-all duration-200" data-star="3" aria-label="3 estrellas">
                  <span class="material-symbols-outlined text-[24px] transition-transform duration-200" style="font-variation-settings: 'FILL' 1;">star</span>
                </button>
                <button type="button" class="comment-star text-on-surface-variant transition-all duration-200" data-star="4" aria-label="4 estrellas">
                  <span class="material-symbols-outlined text-[24px] transition-transform duration-200" style="font-variation-settings: 'FILL' 1;">star</span>
                </button>
                <button type="button" class="comment-star text-on-surface-variant transition-all duration-200" data-star="5" aria-label="5 estrellas">
                  <span class="material-symbols-outlined text-[24px] transition-transform duration-200" style="font-variation-settings: 'FILL' 1;">star</span>
                </button>
              </div>
              <p id="rating-help" class="text-xs text-on-surface-variant">Selecciona tu calificaci&oacute;n antes de comentar.</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 space-y-2">
              <label for="comment-text" class="text-xs uppercase tracking-[0.3em] text-on-surface-variant">Tu comentario</label>
              <textarea id="comment-text" rows="4" maxlength="400" class="w-full bg-transparent border border-white/10 rounded-2xl px-4 py-3 text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-primary/40" placeholder="Escribe algo que ayude a otros fans..."></textarea>
              <div class="flex items-center justify-between text-xs text-on-surface-variant">
                <span id="comment-error" class="hidden text-rose-300">Agrega una puntuación y un comentario.</span>
                <span id="comment-count">0/400</span>
              </div>
            </div>
            <div class="flex flex-wrap items-center gap-4">
              <button type="submit" id="comment-submit" class="rounded-full bg-gradient-to-br from-sky-500 to-violet-500 px-6 py-3 text-sm font-bold uppercase tracking-widest text-white shadow-lg hover:scale-105 active:scale-95 transition-transform">Publicar</button>
              <span class="text-xs text-on-surface-variant">Tu opini&oacute;n ayuda a la comunidad.</span>
            </div>
          </form>
          <div class="rounded-3xl border border-white/10 bg-surface-container-low/60 p-6 md:p-8 shadow-[0_20px_50px_rgba(0,0,0,0.4)] space-y-4">
            <div class="flex items-center justify-between">
              <h3 class="font-headline text-2xl font-bold">Opiniones recientes</h3>
              <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 rounded-full border border-white/10 bg-surface-container-high/70 px-3 py-1 text-xs text-on-surface-variant shadow-[0_8px_18px_rgba(0,0,0,0.25)]">
                  <span class="uppercase tracking-widest">Filtro</span>
                  <select id="comments-filter" class="bg-surface-container-high text-xs text-on-surface border border-white/10 rounded-full px-2 py-0.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <option value="0">Todas</option>
                    <option value="5">5 estrellas</option>
                    <option value="4">4 estrellas</option>
                    <option value="3">3 estrellas</option>
                    <option value="2">2 estrellas</option>
                    <option value="1">1 estrella</option>
                  </select>
                </label>
                <span id="comments-total" class="min-w-[8.5rem] whitespace-nowrap rounded-full border border-white/10 bg-white/5 px-4 py-1 text-center text-xs text-on-surface-variant">0 comentarios</span>
              </div>
            </div>
            <div id="comments-list" class="space-y-4"></div>
            <div id="comments-more-wrap" class="hidden pt-2 flex justify-center">
              <button id="comments-more" type="button" class="rounded-full border border-white/10 bg-white/5 px-5 py-2 text-xs font-semibold uppercase tracking-widest text-on-surface-variant hover:border-primary/50 hover:text-primary transition-all">Ver m&aacute;s comentarios</button>
            </div>
          </div>
          <div id="delete-comment-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-6">
            <div class="comments-filter-card w-[300px] max-w-[300px] rounded-2xl border border-white/10 bg-surface-container-high p-5 shadow-[0_24px_60px_rgba(0,0,0,0.45)] space-y-4">
              <div class="flex items-center justify-between">
                <h4 class="text-lg font-semibold">Eliminar comentario</h4>
                <button id="delete-comment-close" type="button" class="w-8 h-8 rounded-full border border-white/10 bg-white/5 text-on-surface-variant hover:text-on-surface">X</button>
              </div>
              <p class="text-sm text-on-surface-variant leading-6">&iquest;Est&aacute;s seguro de que deseas borrar este comentario? Esta acci&oacute;n no se puede deshacer.</p>
              <div class="flex items-center justify-end gap-2 pt-2">
                <button id="delete-comment-cancel" type="button" class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs uppercase tracking-widest text-on-surface-variant hover:text-on-surface">Cancelar</button>
                <button id="delete-comment-confirm" type="button" class="rounded-full bg-gradient-to-br from-rose-500 to-red-500 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white">Eliminar</button>
              </div>
            </div>
          </div>
          <div id="report-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-6">
            <div class="comments-report-card w-[260px] max-w-[260px] rounded-2xl border border-white/10 bg-surface-container-high p-4 shadow-[0_24px_60px_rgba(0,0,0,0.45)] space-y-3">
              <div class="flex items-center justify-between">
                <h4 class="text-lg font-semibold">Reportar comentario</h4>
                <button id="report-close" type="button" class="w-8 h-8 rounded-full border border-white/10 bg-white/5 text-on-surface-variant hover:text-on-surface">X</button>
              </div>
              <p id="report-snippet" class="text-xs text-on-surface-variant line-clamp-3"></p>
              <div class="space-y-2 text-sm">
                <label class="flex items-center gap-2"><input type="radio" name="report-reason" value="Spam o publicidad" class="accent-primary">Spam o publicidad</label>
                <label class="flex items-center gap-2"><input type="radio" name="report-reason" value="Lenguaje ofensivo" class="accent-primary">Lenguaje ofensivo</label>
                <label class="flex items-center gap-2"><input type="radio" name="report-reason" value="Spoilers" class="accent-primary">Spoilers</label>
                <label class="flex items-center gap-2"><input type="radio" name="report-reason" value="Contenido inapropiado" class="accent-primary">Contenido inapropiado</label>
                <label class="flex items-center gap-2"><input type="radio" name="report-reason" value="Otro motivo" class="accent-primary">Otro motivo</label>
              </div>
              <div id="report-other-wrap" class="hidden space-y-2">
                <label for="report-other-text" class="text-xs uppercase tracking-widest text-on-surface-variant">Escribe el motivo</label>
                <textarea id="report-other-text" rows="3" maxlength="220" class="w-full rounded-2xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-primary/40" placeholder="Cu&eacute;ntanos por qu&eacute; quieres reportar este comentario..."></textarea>
                <p id="report-other-error" class="hidden text-xs text-rose-300">Escribe un motivo para continuar.</p>
              </div>
              <div class="flex items-center justify-end gap-2 pt-2">
                <button id="report-cancel" type="button" class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs uppercase tracking-widest text-on-surface-variant hover:text-on-surface">Cancelar</button>
                <button id="report-submit" type="button" class="rounded-full bg-gradient-to-br from-sky-500 to-violet-500 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white opacity-50 pointer-events-none">Enviar reporte</button>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
    <!-- Footer Component -->
    <div data-layout="footer" class="relative z-20"></div>
    <script src="<?= asset_path('assets/js/layout.js?v=theme1') ?>"></script>
    <script src="<?= asset_path('assets/js/shared-utils.js?v=3') ?>"></script>
    <script src="<?= asset_path('assets/js/i18n.js') ?>"></script>
    <script src="<?= asset_path('assets/js/title-images.js?v=3') ?>"></script>
    <script src="<?= asset_path('assets/js/search.js?v=popular7') ?>"></script>
    <script src="<?= asset_path('assets/js/favorites.js?v=4') ?>"></script>
    <script src="<?= asset_path('assets/js/detail-links.js?v=6') ?>"></script>
    <script>window.__DETAIL_ROUTE_INFO = { ref: <?= json_encode($detailRef ?? "") ?>, query: <?= json_encode($detailQuery ?? "") ?> };</script>
    <script src="<?= asset_path('assets/js/detail-data.js?v=1.1.5') ?>"></script>
    <script>
  document.addEventListener("DOMContentLoaded", () => {
    if (window.AniDexI18n) window.AniDexI18n.init();
    if (window.AniDexTitleImages) window.AniDexTitleImages.init();
    if (window.AniDexSearch) window.AniDexSearch.init();
    if (window.AniDexFavorites) window.AniDexFavorites.init();
    if (window.AniDexDetailLinks) window.AniDexDetailLinks.init();
    if (window.AniDexDetailData) window.AniDexDetailData.init();
  });
    </script>
    <script>
  (function () {
    const form = document.getElementById("comment-form");
    const textarea = document.getElementById("comment-text");
    const count = document.getElementById("comment-count");
    const error = document.getElementById("comment-error");
    const list = document.getElementById("comments-list");
    const total = document.getElementById("comments-total");
    const moreWrap = document.getElementById("comments-more-wrap");
    const moreBtn = document.getElementById("comments-more");
    const filterSelect = document.getElementById("comments-filter");
    const reportModal = document.getElementById("report-modal");
    const reportClose = document.getElementById("report-close");
    const reportCancel = document.getElementById("report-cancel");
    const reportSubmit = document.getElementById("report-submit");
    const reportSnippet = document.getElementById("report-snippet");
    const reportOtherWrap = document.getElementById("report-other-wrap");
    const reportOtherText = document.getElementById("report-other-text");
    const reportOtherError = document.getElementById("report-other-error");
    const deleteCommentModal = document.getElementById("delete-comment-modal");
    const deleteCommentClose = document.getElementById("delete-comment-close");
    const deleteCommentCancel = document.getElementById("delete-comment-cancel");
    const deleteCommentConfirm = document.getElementById("delete-comment-confirm");
    const stars = Array.from(document.querySelectorAll(".comment-star"));
    const starsWrap = document.getElementById("rating-stars");
    const ratingValue = document.getElementById("rating-value");
    const submitBtn = document.getElementById("comment-submit");
    const serverLogged = <?= $isLoggedIn ? "true" : "false" ?>;
    const serverPremium = <?= $sessionPremium ? "true" : "false" ?>;
    let isLogged = serverLogged || (window.AniDexLayout ? window.AniDexLayout.isLoggedIn() : (localStorage.getItem("nekora_logged_in") === "true"));
    let isPremium = serverPremium || (window.AniDexLayout 
      ? window.AniDexLayout.isPremium() 
      : (isLogged && (localStorage.getItem("nekora_premium") === "true" || localStorage.getItem("nekora_admin") === "true" || localStorage.getItem("nekora_user") === "Admin99")));

    // Registrar vista de anime
    const logActivity = async (action, extraData = {}) => {
      if (!isLogged) return;
      const routeInfo = window.AniDexShared?.getDetailRouteInfo ? window.AniDexShared.getDetailRouteInfo() : { malId: "", query: "" };
      let animeId = routeInfo.malId || document.body.dataset.detailId || "";
      
      // Si no hay ID en la URL, intentamos obtenerlo del dataset que llena detail-data.js
      if (!animeId && action === "view") {
        let retries = 0;
        const checkId = setInterval(async () => {
          animeId = document.body.dataset.detailId;
          if (animeId || retries > 10) {
            clearInterval(checkId);
            if (animeId) {
                fetch("<?= asset_path('api/activity') ?>", {
                  method: "POST",
                  headers: { "Content-Type": "application/json" },
                  body: JSON.stringify({ action, anime_id: animeId, ...extraData })
                }).catch(() => {});
            }
          }
          retries++;
        }, 500);
        return;
      }

      if (!animeId) return;

      try {
        await fetch("<?= asset_path('api/activity') ?>", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ action, anime_id: animeId, ...extraData })
        });
      } catch (e) { console.error("Error logging activity:", e); }
    };

    // Log view on load
    logActivity("view");

    const hasPremiumAccess = () => {
      const layoutPremium = !!window.AniDexLayout?.isPremium?.();
      const layoutLogged = !!window.AniDexLayout?.isLoggedIn?.();
      const logged = isLogged || layoutLogged || localStorage.getItem("nekora_logged_in") === "true";
      const localPremium = logged && localStorage.getItem("nekora_premium") === "true";
      const localAdmin = logged && localStorage.getItem("nekora_admin") === "true";
      const localRoleAdmin = logged && ["Admin99", "Admin", "Administrador"].includes(localStorage.getItem("nekora_user") || "");
      const premium = isPremium || layoutPremium || localPremium || localAdmin || localRoleAdmin;
      isLogged = logged;
      isPremium = premium;
      return { logged, premium };
    };

    const checkCanComment = () => {
      const access = hasPremiumAccess();
      return access.logged && access.premium;
    };
    if (!form || !textarea || !list || !total || !ratingValue) return;

    let rating = 0;
    let hoverRating = 0;
    const key = `anidex_comments_${document.title || "detail"}`;
    let visibleCount = 3;
    let filterRating = 0;
    let reportTargetButton = null;
    let reportTargetCommentId = null;
    let reportTargetLocalKey = "";
    let pendingDeleteCommentId = null;
    let pendingDeleteLocalIndex = null;
    let backendCommentsCache = null;
    const lockOverlay = document.getElementById("comment-lock-overlay");
    const lockMsg = document.getElementById("lock-message");

    const forceUnlockComments = () => {
      if (!lockOverlay) return;
      lockOverlay.classList.add("hidden");
      lockOverlay.classList.remove("flex");
      lockOverlay.style.display = "none";
      lockOverlay.style.pointerEvents = "none";
      lockOverlay.setAttribute("aria-hidden", "true");
      textarea.removeAttribute("disabled");
      if (submitBtn) {
        submitBtn.removeAttribute("disabled");
        submitBtn.classList.remove("opacity-30", "pointer-events-none", "grayscale", "opacity-50");
      }
      starsWrap?.classList.remove("pointer-events-none", "opacity-40");
      textarea.classList.remove("opacity-40", "grayscale");
    };

    const applyLock = (isLogged, isPremium) => {
      if (!lockOverlay) return;
      const access = hasPremiumAccess();
      if (access.premium) {
        forceUnlockComments();
      } else {
        lockOverlay.style.display = "";
                const targetUrl = isLogged
          ? "<?= route_path('payment') ?>"
          : "<?= route_path('register') ?>";
        lockOverlay.href = targetUrl;
        lockOverlay.onclick = (event) => { event.preventDefault(); event.stopPropagation(); window.location.href = targetUrl; };
        lockOverlay.classList.add("flex");
        textarea.setAttribute("disabled", "true");
        if (submitBtn) {
          submitBtn.setAttribute("disabled", "true");
          submitBtn.classList.add("opacity-30", "pointer-events-none", "grayscale");
        }
        starsWrap?.classList.add("pointer-events-none", "opacity-40");
        textarea.classList.add("opacity-40", "grayscale");
      }
    };

    if (window.AniDexLayout && typeof window.AniDexLayout.onReady === "function") {
      const syncCommentAccess = async () => {
        try {
          const res = await fetch("<?= asset_path('api/auth') ?>?action=check", { cache: "no-store", credentials: "same-origin" });
          if (!res.ok) return;
          const auth = await res.json();
          isLogged = isLogged || auth.logged === true;
          isPremium = isPremium || auth.isPremium === true || auth.isAdmin === true;
        } catch {
        }
      };

      window.AniDexLayout.onReady(async () => {
        isLogged = window.AniDexLayout.isLoggedIn();
        isPremium = window.AniDexLayout.isPremium();
        await syncCommentAccess();
        const access = hasPremiumAccess();
        applyLock(access.logged, access.premium);
        if (isLogged) logActivity("view");
        
        const favoriteBtns = document.querySelectorAll("[data-add-favorite]");
        const statusBtns = document.querySelectorAll("[data-detail-status]");
        if (!isLogged) {
          favoriteBtns.forEach((btn) => btn.classList.add("hidden"));
          statusBtns.forEach((btn) => btn.classList.add("hidden"));
        }
      });
    } else {
      const syncCommentAccess = async () => {
        try {
          const res = await fetch("<?= asset_path('api/auth') ?>?action=check", { cache: "no-store", credentials: "same-origin" });
          if (!res.ok) return;
          const auth = await res.json();
          isLogged = isLogged || auth.logged === true;
          isPremium = isPremium || auth.isPremium === true || auth.isAdmin === true;
        } catch {
        }
      };

      isLogged = localStorage.getItem("nekora_logged_in") === "true";
      isPremium = localStorage.getItem("nekora_premium") === "true" || localStorage.getItem("nekora_admin") === "true" || localStorage.getItem("nekora_user") === "Admin99";
      syncCommentAccess().finally(() => {
        const access = hasPremiumAccess();
        applyLock(access.logged, access.premium);
      });
    }

    const ensureCommentAccess = () => {
      const access = hasPremiumAccess();
      applyLock(access.logged, access.premium);
      if (access.premium) forceUnlockComments();
    };

    ensureCommentAccess();
    window.addEventListener("load", ensureCommentAccess);
    document.addEventListener("visibilitychange", () => {
      if (!document.hidden) ensureCommentAccess();
    });

    if (filterSelect) {
      filterSelect.addEventListener("change", () => {
        filterRating = Number(filterSelect.value || 0);
        visibleCount = 3;
        renderComments();
      });
    }
    if (moreBtn) {
      moreBtn.addEventListener("click", () => {
        visibleCount += 3;
        renderComments();
      });
    }
    const getMalId = () => {
      const routeInfo = window.AniDexShared?.getDetailRouteInfo ? window.AniDexShared.getDetailRouteInfo() : { malId: "" };
      const candidates = [
        routeInfo.malId,
        document.body?.dataset?.detailId,
        document.documentElement?.dataset?.detailId
      ];
      const id = candidates.find((value) => value && /^\d+$/.test(String(value)));
      return id ? String(id) : null;
    };
    const waitForMalId = async (retries = 20, delayMs = 250) => {
      let malId = getMalId();
      while (!malId && retries > 0) {
        await new Promise((resolve) => setTimeout(resolve, delayMs));
        retries -= 1;
        malId = getMalId();
      }
      return malId;
    };
    const getProfileName = () => {
      const profileName = (localStorage.getItem("anidex_profile_name") || "").trim();
      const username = (localStorage.getItem("nekora_user") || "").trim();
      const clean = profileName || username;
      return clean || "NekoraUser";
    };
    const normalizeHandle = (value) => String(value || "").trim().replace(/^@+/, "").toLowerCase();
    const updateSubmitState = () => {
      if (!submitBtn) return;
      const disabled = rating === 0 || textarea.value.trim().length === 0;
      submitBtn.disabled = disabled;
      submitBtn.classList.toggle("opacity-50", disabled);
      submitBtn.classList.toggle("pointer-events-none", disabled);
    };

    const paintStars = (value, isHover) => {
      stars.forEach((btn) => {
        const val = Number(btn.dataset.star || 0);
        const active = val <= value;
        btn.classList.toggle("text-yellow-300", active && isHover);
        btn.classList.toggle("text-yellow-400", active && !isHover);
        btn.classList.toggle("text-on-surface-variant", !active);
        const icon = btn.querySelector(".material-symbols-outlined");
        if (icon) {
          icon.classList.toggle("-translate-y-0.5", active);
        }
      });
    };

    const setRating = (value) => {
      rating = value;
      paintStars(rating, false);
      ratingValue.textContent = `${rating}/5`;
      updateSubmitState();
    };

    const loadComments = () => {
      try {
        const raw = localStorage.getItem(key);
        return raw ? JSON.parse(raw) : [];
      } catch {
        return [];
      }
    };
    const saveComments = (items) => {
      try {
        localStorage.setItem(key, JSON.stringify(items));
      } catch {}
    };
    const reportStoreKey = `${key}_reported`;
    const loadReportedComments = () => {
      try {
        const raw = localStorage.getItem(reportStoreKey);
        const parsed = raw ? JSON.parse(raw) : [];
        return Array.isArray(parsed) ? parsed : [];
      } catch {
        return [];
      }
    };
    const saveReportedComments = (items) => {
      try {
        localStorage.setItem(reportStoreKey, JSON.stringify(items));
      } catch {}
    };
    const buildCommentLocalKey = (item) => {
      if (!item) return "";
      const source = String(item.source || "");
      const author = normalizeHandle(item.author || "");
      const text = String(item.text || "").trim().toLowerCase();
      const date = String(item.date || "").trim().toLowerCase();
      const localIndex = Number(item.localIndex);
      const indexPart = Number.isFinite(localIndex) && localIndex >= 0 ? `:${localIndex}` : "";
      return `${source}:${author}:${date}:${text}${indexPart}`;
    };
    const isCommentReported = (item) => {
      if (item?.flagged) return true;
      const localKey = buildCommentLocalKey(item);
      if (!localKey) return false;
      return loadReportedComments().includes(localKey);
    };
    const fetchBackendComments = async () => {
      const malId = await waitForMalId();
      if (!malId) return [];
      try {
        const res = await fetch(`<?= asset_path('api/comments') ?>?action=list&anime_mal_id=${encodeURIComponent(malId)}`, {
          cache: "no-store",
          credentials: "same-origin"
        });
        if (!res.ok) return [];
        const json = await res.json();
        if (!json?.success || !Array.isArray(json?.data)) return [];
        return json.data.map((item) => ({
          id: Number(item.id || 0),
          author: item.user || "",
          rating: Number(item.rating || 0),
          date: item.date || "",
          text: item.msg || "",
          source: item.source || "usuario",
          flagged: item.flagged === true,
          reportReason: item.report_reason || "",
          reportedBy: item.reported_by || ""
        }));
      } catch {
        return [];
      }
    };

    let jikanCache = null;
    const formatDate = (value) => {
      if (!value) return "";
      try {
        return new Date(value).toLocaleDateString("es-ES", { day: "2-digit", month: "short", year: "numeric" });
      } catch {
        return String(value);
      }
    };
    const truncate = (text, max = 200) => {
      const clean = String(text || "").replace(/\s+/g, " ").trim();
      if (!clean) return "";
      if (clean.length <= max) return clean;
      const slice = clean.slice(0, max).trim();
      const lastPeriod = slice.lastIndexOf(".");
      if (lastPeriod > 20) {
        return slice.slice(0, lastPeriod + 1).trim();
      }
      const trimmed = slice.replace(/[.!?]+$/, "").trim();
      return trimmed ? `${trimmed}.` : ".";
    };
    const translationCacheKey = "jikan_review_translations_v1";
    const loadTranslationCache = () => {
      try {
        const raw = localStorage.getItem(translationCacheKey);
        return raw ? JSON.parse(raw) : {};
      } catch {
        return {};
      }
    };
    const saveTranslationCache = (cache) => {
      try {
        localStorage.setItem(translationCacheKey, JSON.stringify(cache));
      } catch {}
    };
    const isLikelySpanish = (text) => {
     const lower = String(text || "").toLowerCase();
      if (/[\u00E1\u00E9\u00ED\u00F3\u00FA\u00F1\u00BF\u00A1]/.test(lower)) return true;
      const hits = [" el ", " la ", " de ", " que ", " y ", " en ", " los ", " las ", " un ", " una ", " para ", " con ", " por ", " como "];
      let count = 0;
      hits.forEach((word) => {
        if (lower.includes(word)) count += 1;
      });
      return count >= 2;
    };
    let translationCache = loadTranslationCache();
    const translateToSpanish = async (text) => {
      const clean = String(text || "").replace(/\s+/g, " ").trim();
      if (!clean) return { text: "", translated: false };
      if (isLikelySpanish(clean)) return { text: clean, translated: false };
      if (translationCache[clean]) return { text: translationCache[clean], translated: true };

      try {
        if (window.AniDexShared && typeof window.AniDexShared.translateAutoToEs === "function") {
          const translated = await window.AniDexShared.translateAutoToEs(clean);
          if (translated && translated !== clean) {
            translationCache[clean] = translated;
            saveTranslationCache(translationCache);
            return { text: translated, translated: true };
          }
        }
      } catch (e) {
        console.warn("Error en traducción:", e);
      }
      return { text: clean, translated: false };
    };
    const delay = (ms) => new Promise((resolve) => setTimeout(resolve, ms));
    const fetchJikanReviews = async (retries = 2) => {
      const malId = await waitForMalId();
      if (!malId) return [];
      try {
        const res = await fetch(`https://api.jikan.moe/v4/anime/${malId}/reviews?page=1&preliminary=true`);
        if (res.status === 429 && retries > 0) {
          await delay(1000);
          return fetchJikanReviews(retries - 1);
        }
        if (!res.ok) return [];
        const json = await res.json();
        const data = Array.isArray(json?.data) ? json.data : [];
        const mapped = data.slice(0, 6).map((review) => {
          const score = Number(review?.score || 0);
          const rating = score ? Math.max(1, Math.round(score / 2)) : 0;
          const rawText = String(review?.review || "").trim();
          return {
            author: review?.user?.username || "",
            rating,
            date: formatDate(review?.date),
            text: rawText || "",
            source: "jikan"
          };
        });
        for (const item of mapped) {
          if (!item.text) continue;
          const res = await translateToSpanish(item.text);
          item.text = truncate(res.text);
          item.isTranslated = res.translated;
        }
        return mapped;
      } catch {
        return [];
      }
    };
    const loadJikanReviews = async () => {
      if (jikanCache) return jikanCache;
      jikanCache = await fetchJikanReviews();
      return jikanCache;
    };

    const renderComments = async () => {
      const rawItems = loadComments();
      const localItems = rawItems.filter((item) => (item.author || "").trim().length > 0);
      if (localItems.length !== rawItems.length) {
        saveComments(localItems);
      }
      const localMapped = localItems.map((item, idx) => ({ ...item, source: "local", localIndex: idx }));
      if (!backendCommentsCache) {
        backendCommentsCache = await fetchBackendComments();
      }
      const remoteItems = backendCommentsCache.length ? backendCommentsCache : await loadJikanReviews();
      const items = backendCommentsCache.length ? remoteItems : [...localMapped, ...remoteItems];
      const filtered = filterRating ? items.filter((item) => Number(item.rating) === filterRating) : items;
      total.textContent = `${filtered.length} comentario${filtered.length === 1 ? "" : "s"}`;
      if (!filtered.length) {
        list.innerHTML = '<div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-on-surface-variant">Todav&iacute;a no hay comentarios para este filtro.</div>';
        if (moreWrap) moreWrap.classList.add("hidden");
        return;
      }
      const currentUser = getProfileName();
      const visibleItems = filtered.slice(0, Math.min(visibleCount, filtered.length));
      list.innerHTML = visibleItems.map((item) => {
        const ratingNum = Number(item.rating) || 0;
        const starsHtml = Array.from({ length: 5 }).map((_, i) => {
          const on = i < ratingNum;
          return `<span class="material-symbols-outlined text-[16px] ${on ? "text-yellow-400" : "text-on-surface-variant"}" style="font-variation-settings: 'FILL' 1;">star</span>`;
        }).join("");
        const author = (item.author || "").trim();
        const currentUserHandle = normalizeHandle(currentUser);
        const authorHandle = normalizeHandle(author);
        const isOwnComment = Boolean(authorHandle && authorHandle === currentUserHandle);
        const isReported = isCommentReported(item);
        const canDelete = item.source !== "jikan" && isOwnComment;
        const authorHtml = author ? `<div class="text-sm font-semibold text-amber-200">${author}</div>` : "";
        const reportLabel = isReported ? "Reportado" : "Reportar";
        const canReport = isLogged && !isOwnComment;
        const reportClasses = isReported
          ? "inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/10 bg-white/5 text-on-surface-variant cursor-default"
          : "inline-flex h-9 w-9 items-center justify-center rounded-full border border-sky-400/20 bg-sky-500/10 text-sky-200 transition-all duration-200 hover:-translate-y-0.5 hover:border-sky-300/50 hover:bg-sky-400/20 hover:text-sky-100";
        const deleteClasses = "inline-flex h-9 w-9 items-center justify-center rounded-full border border-rose-400/20 bg-rose-500/10 text-rose-300 transition-all duration-200 hover:-translate-y-0.5 hover:border-rose-300/50 hover:bg-rose-400/20 hover:text-rose-100";
        const commentLocalKey = buildCommentLocalKey(item).replace(/"/g, "&quot;");
        return `
          <div class="rounded-2xl border border-white/10 bg-white/5 p-4 space-y-2">
            <div class="flex items-center justify-between gap-3">
              <div>
                ${authorHtml}
                <div class="flex items-center gap-1">${starsHtml}</div>
              </div>
              <div class="flex flex-col items-end gap-2">
                <span class="text-xs text-on-surface-variant">${item.date}</span>
                <div class="flex items-center gap-2">
                  ${canReport ? `<button type="button" class="${reportClasses}" ${isReported ? "disabled" : ""} title="${reportLabel}" aria-label="${reportLabel}" data-comment-report data-comment-id="${Number(item.id || 0)}" data-comment-local-key="${commentLocalKey}" data-comment-author="${author}" data-comment-text="${String(item.text || "").slice(0, 120).replace(/"/g, "&quot;")}"><span class="material-symbols-outlined text-[18px]">${isReported ? 'flag' : 'outlined_flag'}</span></button>` : ""}
                  ${canDelete ? `<button type="button" class="${deleteClasses}" title="Eliminar" aria-label="Eliminar" data-comment-delete-id="${Number(item.id || 0)}" data-comment-delete-local="${item.localIndex ?? -1}"><span class="material-symbols-outlined text-[18px]">delete</span></button>` : ""}
                </div>
              </div>
            </div>
            <p class="text-sm text-on-surface leading-relaxed">${item.text}${item.isTranslated ? ' <span class="text-[10px] font-bold text-sky-400/80 italic ml-1" title="Traducido autom&aacute;ticamente">(Traducido)</span>' : ''}</p>
          </div>`;
      }).join("");
      list.querySelectorAll("[data-comment-delete-id]").forEach((btn) => {
        btn.addEventListener("click", () => {
          pendingDeleteCommentId = Number(btn.getAttribute("data-comment-delete-id") || 0);
          pendingDeleteLocalIndex = Number(btn.getAttribute("data-comment-delete-local") || -1);
          if (!deleteCommentModal) return;
          deleteCommentModal.classList.remove("hidden");
          deleteCommentModal.classList.add("flex");
        });
      });
      list.querySelectorAll("[data-comment-report]").forEach((btn) => {
        btn.addEventListener("click", () => {
          if (!reportModal) return;
          reportTargetButton = btn;
          reportTargetCommentId = Number(btn.getAttribute("data-comment-id") || 0);
          reportTargetLocalKey = btn.getAttribute("data-comment-local-key") || "";
          const preview = btn.getAttribute("data-comment-text") || "";
          reportSnippet.textContent = preview;
          reportModal.classList.remove("hidden");
          reportModal.classList.add("flex");
          reportSubmit.classList.add("opacity-50", "pointer-events-none");
          reportModal.querySelectorAll("input[name='report-reason']").forEach((input) => {
            input.checked = false;
          });
        });
      });
      if (moreWrap) {
        if (filtered.length > visibleCount) {
          moreWrap.classList.remove("hidden");
        } else {
          moreWrap.classList.add("hidden");
        }
      }
    };
    const closeDeleteConfirm = () => {
      if (!deleteCommentModal) return;
      deleteCommentModal.classList.add("hidden");
      deleteCommentModal.classList.remove("flex");
      pendingDeleteCommentId = null;
      pendingDeleteLocalIndex = null;
    };
    if (deleteCommentModal) {
      deleteCommentModal.addEventListener("click", (event) => {
        if (event.target === deleteCommentModal) closeDeleteConfirm();
      });
    }
    if (deleteCommentClose) deleteCommentClose.addEventListener("click", closeDeleteConfirm);
    if (deleteCommentCancel) deleteCommentCancel.addEventListener("click", closeDeleteConfirm);
    if (deleteCommentConfirm) {
      deleteCommentConfirm.addEventListener("click", async () => {
        if (pendingDeleteCommentId) {
          try {
            const res = await fetch("<?= asset_path('api/comments') ?>?action=delete", {
              method: "POST",
              credentials: "same-origin",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({ id: pendingDeleteCommentId })
            });
            const data = await res.json();
            if (!data.success) {
              alert(data.error || "No se pudo ocultar el comentario.");
              return;
            }
            backendCommentsCache = null;
          } catch (deleteError) {
            alert("No se pudo ocultar el comentario.");
            return;
          }
        } else if (pendingDeleteLocalIndex !== null && pendingDeleteLocalIndex >= 0) {
          const itemsNow = loadComments();
          itemsNow.splice(pendingDeleteLocalIndex, 1);
          saveComments(itemsNow);
        }
        closeDeleteConfirm();
        renderComments();
      });
    }

    const closeReport = () => {
      if (!reportModal) return;
      reportModal.classList.add("hidden");
      reportModal.classList.remove("flex");
      reportOtherWrap?.classList.add("hidden");
      if (reportOtherText) reportOtherText.value = "";
      reportOtherError?.classList.add("hidden");
      reportTargetButton = null;
      reportTargetCommentId = null;
      reportTargetLocalKey = "";
    };
    if (reportModal) {
      reportModal.addEventListener("click", (event) => {
        if (event.target === reportModal) closeReport();
      });
    }
    if (reportClose) reportClose.addEventListener("click", closeReport);
    if (reportCancel) reportCancel.addEventListener("click", closeReport);
    if (reportModal) {
      reportModal.querySelectorAll("input[name='report-reason']").forEach((input) => {
        input.addEventListener("change", () => {
          const isOther = input.checked && input.value === "Otro motivo";
          reportOtherWrap?.classList.toggle("hidden", !isOther);
          reportOtherError?.classList.add("hidden");
          if (!isOther && reportOtherText) reportOtherText.value = "";
          if (!reportSubmit) return;
          const needsText = isOther && !String(reportOtherText?.value || "").trim();
          reportSubmit.classList.toggle("opacity-50", needsText);
          reportSubmit.classList.toggle("pointer-events-none", needsText);
          if (!needsText) {
            reportSubmit.classList.remove("opacity-50", "pointer-events-none");
          }
        });
      });
    }
    if (reportOtherText) {
      reportOtherText.addEventListener("input", () => {
        const selectedReason = reportModal?.querySelector("input[name='report-reason']:checked")?.value || "";
        const needsText = selectedReason === "Otro motivo" && !reportOtherText.value.trim();
        reportOtherError?.classList.toggle("hidden", !needsText);
        reportSubmit?.classList.toggle("opacity-50", needsText);
        reportSubmit?.classList.toggle("pointer-events-none", needsText);
        if (!needsText) {
          reportSubmit?.classList.remove("opacity-50", "pointer-events-none");
        }
      });
    }
    if (reportSubmit) {
      reportSubmit.addEventListener("click", async () => {
        const selectedReason = reportModal.querySelector("input[name='report-reason']:checked")?.value || "";
        const customReason = String(reportOtherText?.value || "").trim();
        if (!selectedReason) return;
        const reason = selectedReason === "Otro motivo" ? customReason : selectedReason;
        if (!reason) {
          reportOtherWrap?.classList.remove("hidden");
          reportOtherError?.classList.remove("hidden");
          return;
        }
        if (!reportTargetCommentId && !reportTargetLocalKey) return;
        try {
          if (reportTargetCommentId) {
            const res = await fetch("<?= asset_path('api/comments') ?>?action=report", {
              method: "POST",
              credentials: "same-origin",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({ comment_id: reportTargetCommentId, reason })
            });
            const data = await res.json();
            if (!data.success) {
              alert(data.error || "No se pudo reportar el comentario.");
              return;
            }
          } else if (reportTargetLocalKey) {
            const reportedItems = loadReportedComments();
            if (!reportedItems.includes(reportTargetLocalKey)) {
              reportedItems.push(reportTargetLocalKey);
              saveReportedComments(reportedItems);
            }
          }
          logActivity("report", { message: `Reporte de comentario: ${reason}` });
          if (reportTargetButton) {
            reportTargetButton.disabled = true;
          }
          backendCommentsCache = null;
          closeReport();
          renderComments();
        } catch (errorReport) {
          alert("No se pudo reportar el comentario.");
        }
      });
    }
    stars.forEach((btn) => {
      btn.addEventListener("click", () => {
        hoverRating = 0;
        setRating(Number(btn.dataset.star || 0));
      });
      btn.addEventListener("mouseenter", () => {
        hoverRating = Number(btn.dataset.star || 0);
        paintStars(hoverRating, true);
        ratingValue.textContent = `${hoverRating}/5`;
      });
    });
    if (starsWrap) {
      starsWrap.addEventListener("mousemove", (e) => {
        const target = e.target.closest(".comment-star");
        if (!target) return;
        const val = Number(target.dataset.star || 0);
        if (val !== hoverRating) {
          hoverRating = val;
          paintStars(hoverRating, true);
          ratingValue.textContent = `${hoverRating}/5`;
        }
        renderComments();
      });
      starsWrap.addEventListener("mouseleave", () => {
        hoverRating = 0;
        paintStars(rating, false);
        ratingValue.textContent = `${rating}/5`;
      });
    }

    const normalizeWords = (text) =>
      text
        .trim()
        .split(/\s+/)
        .filter(Boolean);
    const clampWords = (text, limit) => {
      const words = normalizeWords(text);
      if (words.length <= limit) return { text, words };
      const trimmed = words.slice(0, limit).join(" ");
      return { text: trimmed, words: trimmed ? normalizeWords(trimmed) : [] };
    };
    const updateCount = () => {
      const words = normalizeWords(textarea.value);
      count.textContent = `${words.length}/200 palabras`;
      updateSubmitState();
    };
        textarea.addEventListener("input", () => {
      const { text, words } = clampWords(textarea.value, 200);
      if (textarea.value !== text) textarea.value = text;
      count.textContent = `${words.length}/200 palabras`;
      updateSubmitState();
    });
    form.addEventListener("submit", async (event) => {
      event.preventDefault();
      if (!checkCanComment()) {
        alert(isLogged ? "Necesitas Premium para comentar." : "Debes iniciar sesión para comentar.");
        return;
      }
      const malId = await waitForMalId();
      const message = textarea.value.trim();
      if (!malId) {
        alert("No se pudo identificar este anime.");
        return;
      }
      if (!rating || !message) {
        error.classList.remove("hidden");
        updateSubmitState();
        return;
      }

      error.classList.add("hidden");
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.classList.add("opacity-50", "pointer-events-none");
      }

      try {
        const res = await fetch("<?= asset_path('api/comments') ?>?action=add", {
          method: "POST",
          credentials: "same-origin",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            anime_mal_id: Number(malId),
            rating,
            message
          })
        });
        const data = await res.json();
        if (!data.success) {
          alert(data.error || "No se pudo publicar el comentario.");
          return;
        }

        textarea.value = "";
        rating = 0;
        hoverRating = 0;
        paintStars(0, false);
        ratingValue.textContent = "0/5";
        backendCommentsCache = null;
        visibleCount = 3;
        updateCount();
        await renderComments();
        logActivity("comment", { message });
      } catch (submitError) {
        alert("No se pudo publicar el comentario.");
      } finally {
        updateSubmitState();
      }
    });
    updateSubmitState();
    updateCount();
    renderComments();

    window.AniDexPages = {
      refreshProfileUI: () => {
        if (typeof applyState === "function") applyState();
        if (typeof loadProfile === "function") loadProfile();
        console.log("UI Refreshed (Detail)");
      }
    };
    
    // Initialize detail data if object is present
    const initDetail = () => {
      // Movido a la inicialización global en la parte superior
    };
    initDetail();
  })();
    </script>
    <script data-ui-unlock>document.documentElement.classList.remove("preload-ui");</script>
  </body></html>


















































