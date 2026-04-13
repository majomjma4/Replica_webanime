<!DOCTYPE html>

<html class="dark" lang="es"><head>
    <script data-ui-preload>document.documentElement.classList.add("preload-ui");</script>
    <style>
  .preload-ui body { opacity: 0; }
    </style>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>NekoraList - Inicio</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        html, body {
            overflow-x: hidden;
        }
        body {
            background-color: #0e0e0e;
        }

        .hero-mask {
            mask-image: linear-gradient(to bottom, black 60%, transparent 100%);
        }
        #hero-video,
        #hero-image {
            transition: opacity 0.7s ease;
        }
        #hero-video {
            object-position: center;
        }
        body.hero-video-on #hero-video {
            opacity: 0.75;
        }
        body.hero-video-on #hero-image {
            opacity: 0;
        }
        body.hero-video-off #hero-video {
            opacity: 0;
        }
        body.hero-video-off #hero-image {
            opacity: 1;
        }
        .text-shadow-glow {
            text-shadow: 0 0 20px rgba(205, 189, 255, 0.4);
        }
        .spotlight-section {
            position: relative;
            padding: 24px 20px 28px;
            border-radius: 28px;
            background:
                radial-gradient(1200px 300px at 10% -10%, rgba(0, 198, 255, 0.18), transparent 60%),
                radial-gradient(1200px 300px at 90% -20%, rgba(168, 85, 247, 0.18), transparent 60%),
                rgba(13, 13, 16, 0.75);
            border: none;
            box-shadow: 0 18px 50px rgba(0, 0, 0, 0.35);
        }
        .spotlight-section::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 28px;
            border: none;
            pointer-events: none;
            mix-blend-mode: screen;
            opacity: 0.6;
        }
        .spotlight-card {
            border: 1px solid rgba(168, 85, 247, 0.65);
            box-shadow:
                0 0 18px rgba(168, 85, 247, 0.45),
                0 14px 30px rgba(40, 0, 120, 0.35);
            transition: transform 0.45s ease, box-shadow 0.45s ease;
        }
        .spotlight-card.is-rotating {
            transform: translateY(-4px);
            box-shadow:
                0 0 22px rgba(56, 189, 248, 0.35),
                0 0 28px rgba(168, 85, 247, 0.45),
                0 14px 30px rgba(40, 0, 120, 0.35);
        }
        .spotlight-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0, 198, 255, 0.08), transparent 55%);
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none;
        }
        .spotlight-card:hover::before {
            opacity: 1;
        }
        .spotlight-title-em {
            color: #a855f7;
            text-shadow: 0 0 12px rgba(168, 85, 247, 0.45);
        }
        .section-title-em {
            font-weight: 800;
            letter-spacing: 0.01em;
        }
        .section-title-em.anime {
            color: #38bdf8;
            text-shadow: 0 0 12px rgba(56, 189, 248, 0.55);
        }
        .section-title-em.movie {
            color: #f472b6;
            text-shadow: 0 0 12px rgba(244, 114, 182, 0.6);
        }
        [data-spotlight-type] {
            background: linear-gradient(90deg, #38bdf8, #a855f7) !important;
            color: #fff !important;
            font-size: 0.75rem !important;
            padding: 0.35rem 0.85rem !important;
            letter-spacing: 0.14em;
            box-shadow: 0 0 14px rgba(56, 189, 248, 0.35), 0 0 18px rgba(168, 85, 247, 0.3);
        }
        .spotlight-fade {
            transition: opacity 0.45s ease;
            will-change: opacity;
        }
        .spotlight-fade.is-fading {
            opacity: 0;
        }
        .spotlight-subtitle {
            font-size: 1.4rem;
            line-height: 1.15;
            transform: translateY(-10px);
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.45);
        }
        .spotlight-card h3,
        .spotlight-card h4,
        .spotlight-card [data-spotlight-meta] {
            position: relative;
            z-index: 2;
            color: #f8fbff;
        }
        .spotlight-card h3,
        .spotlight-card h4 {
            text-shadow: 0 10px 24px rgba(7, 15, 30, 0.68);
        }
        .spotlight-card [data-spotlight-meta] {
            color: rgba(236, 243, 255, 0.88);
            text-shadow: 0 6px 18px rgba(7, 15, 30, 0.58);
        }
        html.light .spotlight-section {
            background:
                radial-gradient(1000px 260px at 12% -8%, rgba(96, 165, 250, 0.18), transparent 58%),
                radial-gradient(1000px 260px at 88% -12%, rgba(167, 139, 250, 0.16), transparent 56%),
                rgba(225, 233, 244, 0.92);
            box-shadow: 0 22px 46px rgba(81, 96, 122, 0.16);
        }
        html.light .spotlight-card {
            border-color: rgba(129, 140, 248, 0.4);
            box-shadow: 0 12px 28px rgba(99, 102, 241, 0.14), 0 12px 28px rgba(71, 85, 105, 0.12);
        }
        html.light .spotlight-card.is-rotating {
            box-shadow: 0 16px 34px rgba(96, 165, 250, 0.18), 0 18px 36px rgba(129, 140, 248, 0.14);
        }
        html.light .spotlight-card::before {
            opacity: 1;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.04), rgba(15, 23, 42, 0.18) 58%, rgba(15, 23, 42, 0.28) 100%);
        }
        html.light .spotlight-card img {
            filter: saturate(1.02) contrast(1.04) brightness(0.92);
        }
        html.light .spotlight-card .absolute.inset-0.bg-gradient-to-t,
        html.light .spotlight-card .absolute.inset-0.bg-black\/40,
        html.light .spotlight-card .absolute.inset-0.bg-primary-container\/0,
        html.light .spotlight-card .absolute.inset-0.group-hover\:bg-primary-container\/20 {
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.08) 0%, rgba(15, 23, 42, 0.16) 45%, rgba(15, 23, 42, 0.42) 100%) !important;
            opacity: 1 !important;
        }
        html.light .spotlight-card h3,
        html.light .spotlight-card h4 {
            color: #f8fbff !important;
            text-shadow: 0 10px 28px rgba(7, 15, 30, 0.78);
        }
        html.light .spotlight-card [data-spotlight-meta] {
            color: rgba(239, 244, 255, 0.92) !important;
            text-shadow: 0 8px 20px rgba(7, 15, 30, 0.7);
        }
        html.light .spotlight-card [data-spotlight-score] {
            background: rgba(248, 250, 252, 0.92) !important;
            color: #243247 !important;
            box-shadow: 0 10px 24px rgba(71, 85, 105, 0.18) !important;
        }        .carousel-row {
            --cards: 5;
            --gap: 2rem;
            --pad: 0.5rem;
            display: flex;
            gap: var(--gap);
            justify-content: flex-start;
            padding-inline: var(--pad);
            padding-top: 10px;
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
            min-width: 0;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .carousel-card:hover {
            z-index: 3;
        }
        .carousel-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            border-radius: 0.75rem;
        }
        .spotlight-section--tight {
            padding-bottom: 23px;
        }
        .spotlight-section--compact {
            padding-top: 19px;
            padding-bottom: 18px;
        }
        .spotlight-grid--compact {
            height: 595px;
        }
        .carousel-card > .relative {
              width: 100%;
              height: 0;
              padding-top: 150%;
              border: none;
              border-radius: 0.75rem;
              box-shadow: none;
              transform-origin: center top;
          }
        .carousel-wrap {
            position: relative;
        }
        .carousel-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease, transform 0.25s ease;
            z-index: 6;
        }
        .carousel-nav-zone {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 64px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 7;
            pointer-events: none;
        }
        .carousel-wrap:hover .carousel-nav-zone,
        .carousel-wrap:focus-within .carousel-nav-zone {
            pointer-events: auto;
        }
        .carousel-nav-zone.is-disabled {
            pointer-events: none;
        }
        .carousel-nav-btn.is-disabled {
            opacity: 0 !important;
            pointer-events: none;
            visibility: hidden;
        }
        .carousel-wrap:hover .carousel-nav-btn,
        .carousel-wrap:focus-within .carousel-nav-btn {
            opacity: 1;
            pointer-events: auto;
        }
        .carousel-nav-left { left: -18px; }
        .carousel-nav-right { right: -18px; }
        @media (max-width: 768px) {
            .carousel-nav-left { left: -6px; }
            .carousel-nav-right { right: -6px; }
        }
        .carousel-card > .relative > img {
            position: absolute;
            inset: 0;
        }
        @media (max-width: 1024px) {
            .carousel-row { --cards: 3; }
        }
        @media (max-width: 640px) {
            .carousel-row { --cards: 2; }
        }
        @media (max-width: 420px) {
            .carousel-row { --cards: 1; }
        }
    </style>
    <link rel="icon" href="img/icon3.webp" />


  </head>
  <body class="bg-surface text-on-surface font-body selection:bg-primary-container selection:text-on-primary-container">
    <!-- Navbar Component -->
    <div data-layout="header"></div>
    <main class="relative min-h-screen">
      <!-- Hero Section -->
      <section class="relative w-full h-[min(100vh,57.6rem)] flex items-end overflow-hidden">
        <div class="absolute inset-0 z-0">
          <video id="hero-video" class="absolute inset-0 w-full h-full object-cover opacity-0 pointer-events-none" playsinline muted loop></video>
          <img id="hero-image" alt="Fondo de anime destacado" class="w-full h-full object-cover hero-mask scale-105 translate-y-[60px]" data-alt="Escena cinematogr&aacute;fica de alta calidad con iluminaci&oacute;n et&eacute;rea y tonos p&uacute;rpura" src="https://lh3.googleusercontent.com/aida-public/AB6AXuASyl0CtuKLwGu8E0g6kJg-AFMonCwUb2HQ4hZvUGuD0_n-GwG61WBtLa1P4WxLShGtZ1WdK79L8IStuCkdamLElWUpBsSuyOjlLszhFAARPXJXAMh5O1B7nkmoUbt235bb4gzcno51HQSRTI6Oh2kkDxU1qcK6jFx8LgH0ImqNPAd91dTA_ZC-HvQVlTdYcRV6Atw2S7_DgDyx7j2rgSWD3OpPRb6OBprhy06fu2rokdR9xMrz_zaaSt7N3Zs--RXOE0QQpITwOxiB"/>
          <div class="absolute inset-0 bg-gradient-to-t from-background via-background/70 to-transparent"></div>
          <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-background/95 via-background/60 to-transparent"></div>
          <div class="absolute inset-0 bg-gradient-to-r from-background via-transparent to-transparent"></div>
        </div>
          <div class="relative z-10 px-8 md:px-16 pb-[86px] w-full md:w-1/2 max-w-none text-left translate-y-[70px]">
          <div class="flex items-center gap-2 mb-4">
            <span class="bg-gradient-to-r from-cyan-400 via-fuchsia-500 to-violet-500 text-white px-4 py-1.5 rounded-full text-sm font-extrabold tracking-widest uppercase shadow-[0_0_18px_rgba(34,211,238,0.55)]">Lo m&aacute;s nuevo</span>
            <span id="hero-meta" class="text-on-surface-variant text-sm font-medium"> 28 Episodios</span>
          </div>
          <h1 id="hero-title" class="text-5xl md:text-7xl font-extrabold font-headline tracking-tighter mb-6 leading-[0.9] text-shadow-glow">
            Frieren: M&aacute;s all&aacute; <br/><span class="text-primary-dim">del final del viaje</span>
          </h1>
          <p id="hero-description" class="text-lg md:text-xl text-on-surface-variant max-w-2xl mb-10 leading-relaxed font-body">
            Despu&eacute;s de que el grupo de h&eacute;roes derrotara al Rey Demonio, restauraron la paz en la tierra y regresaron a sus vidas separadas. Para la maga elfa Frieren, d&eacute;cadas pasan en un abrir y cerrar de ojos.
          </p>
          <div class="flex flex-wrap gap-4">
            <button id="hero-watch" class="bg-gradient-to-br from-primary to-primary-container px-7 py-3.5 rounded-full text-on-primary font-bold tracking-tight hover:scale-105 active:scale-95 transition-all flex items-center gap-2 shadow-lg shadow-primary-container/20"><span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">play_arrow</span> VER AHORA</button>
            <button id="hero-my-list" data-add-my-list data-dynamic-title="0" class="bg-surface-container-high/60 backdrop-blur-md px-7 py-3.5 rounded-full text-on-surface font-bold border border-outline-variant/20 hover:bg-surface-container-high transition-all flex items-center gap-2"><span class="material-symbols-outlined">playlist_add</span><span data-add-label>MI LISTA</span></button>
          </div>
          <div class="mt-6 flex items-center gap-4 py-1">
            <button id="hero-prev" class="rounded-full bg-surface-container-high/70 px-5 py-2.5 text-sm font-bold hover:bg-surface-container-high transition-colors" type="button"><</button>
            <div id="hero-dots" class="flex items-center gap-3"></div>
            <button id="hero-next" class="rounded-full bg-surface-container-high/70 px-5 py-2.5 text-sm font-bold hover:bg-surface-container-high transition-colors" type="button">></button>
          </div>
        </div>
      </section>
      <!-- Category Sections -->
      <div class="space-y-20 pb-20 -mt-[20px] relative z-20">
        <!-- Seasonal Spotlight (Asymmetric Bento Grid) -->
        <section class="px-8 md:px-16 mt-[20px] spotlight-section spotlight-section--tight spotlight-section--compact">
          <div class="mb-8 flex items-end justify-between">
            <div>
              <h2 class="text-3xl font-bold font-headline tracking-tight"><span class="spotlight-title-em">Destacados</span></h2>
              <div class="h-1 w-12 bg-primary mt-2 rounded-full"></div>
            </div>
            <a class="inline-flex items-center gap-2 rounded-full border border-primary/40 bg-primary/10 px-4 py-2 text-sm font-bold text-primary shadow-[0_0_12px_rgba(168,85,247,0.25)] transition-all hover:border-primary hover:bg-primary/20 hover:text-primary" href="<?= route_path('featured') ?>">
              <span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' 1;">arrow_forward</span>
              Ver m&aacute;s
            </a>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-4 grid-rows-2 gap-6 h-[min(60vh,37.5rem)] spotlight-grid spotlight-grid--compact">
            <div id="spotlight-card-1" class="md:col-span-2 md:row-span-2 relative rounded-lg overflow-hidden group cursor-pointer bg-surface-container-low border border-outline-variant/10 spotlight-card" data-mal-id="52991" data-title="Solo Leveling">
              <div class="absolute top-4 right-4 z-10">
                <div data-spotlight-score class="bg-surface-container-lowest/90 backdrop-blur px-4 py-2 rounded-full text-base font-bold text-primary flex items-center gap-2 shadow-xl shadow-black/35">
                  <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                  <span data-score-value>9.4</span>
                </div>
              </div>
              <div data-spotlight-type class="absolute top-4 left-4 z-10 bg-primary/80 text-white text-[11px] font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-lg">Anime</div>
              <img data-spotlight-rotating="1" alt="Spotlight de temporada" class="w-full h-full object-cover transition-transform duration-1000 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04]" data-alt="Escena dramática de batalla anime con efectos de energía brillante" data-fallback="img/fondoanime.webp" loading="lazy" referrerpolicy="no-referrer" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDfEs1IvGWNIZxiSWnnh8wCUr59ea4njuZckziZXqicnQPNvJkan5uFN_-BmFdNvpNSE7c6Gy-mHlJOyMU4hG1QNd2nk5WYUB4_uFh5jJL-K6arAlskDJ_8qihvbrLMvZNDOsSiBXcBUPHNcfenZNrmyKDhpAa0TRP_9gxyxTMIHLtWKcJBfTyyYK2OT7w8mggHKLEv6KE0h3MMHguW46V5ghR79auKbPcZFbiWP4qDRaPBSBDdTGBjWbacwEx2LnRwcBnZnp4l3j-4"/>
              <div class="absolute inset-0 bg-gradient-to-t from-background via-transparent to-transparent opacity-90"></div>
              <div class="absolute inset-0 bg-primary-container/0 group-hover:bg-primary-container/20 transition-colors"></div>
              <div class="absolute bottom-0 left-0 p-8">
                <h3 class="text-4xl font-extrabold font-headline mb-2">Solo Leveling</h3>
                <p data-spotlight-meta class="text-sm text-on-surface-variant">2024 - Studio</p>
              </div>
            </div>
            <div id="spotlight-card-2" class="md:col-span-2 relative rounded-lg overflow-hidden group cursor-pointer bg-surface-container-low border border-outline-variant/10 spotlight-card" data-mal-id="54492" data-title="The Apothecary Diaries">
              <div class="absolute top-4 right-4 z-10">
                <div data-spotlight-score class="bg-surface-container-lowest/90 backdrop-blur px-4 py-2 rounded-full text-base font-bold text-primary flex items-center gap-2 shadow-xl shadow-black/35">
                  <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                  <span data-score-value>9.1</span>
                </div>
              </div>
              <div data-spotlight-type class="absolute top-4 left-4 z-10 bg-primary/80 text-white text-[11px] font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-lg">Anime</div>
              <img data-spotlight-rotating="1" alt="Spotlight de temporada" class="w-full h-full object-cover transition-transform duration-1000 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04]" data-alt="Primer plano del rostro expresivo de un personaje anime" data-fallback="img/fondoanime.webp" loading="lazy" referrerpolicy="no-referrer" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDgJ-yNDPcau0sSBkw_cGTC3DcXxC0TQo-0fTUKahdE5DUWtAnr0hWZaO_7WJ1gNB1_h-q_W6GPkuV2mT5Pf_09bEmD4iNptwXJ15fHn_aPbRDzSebKsJAt3Nk2DuquWSC2OQRmssyZjmOVP4z44_mMFUKikgnzhW0yPGnZEZUAq-6sf8Ozv51ef5cqpnYy3s9V9geTRNL41WRE40oFQMp5zgTlJ8dXSOdEkpFMpZoFdCAp2wYDSdJqyEpWS1-JaM960BH1zhV7A813"/>
              <div class="absolute inset-0 bg-black/40 group-hover:bg-primary-container/20 transition-colors"></div>
              <div class="absolute inset-0 flex items-center justify-center">
                <h3 class="text-2xl font-bold text-center px-4">The Apothecary Diaries</h3>
              </div>
            </div>
            <div id="spotlight-card-3" class="relative rounded-lg overflow-hidden group cursor-pointer bg-surface-container-low border border-outline-variant/10 spotlight-card" data-mal-id="52002" data-title="Undead Unluck">
              <div class="absolute top-4 right-4 z-10">
                <div data-spotlight-score class="bg-surface-container-lowest/90 backdrop-blur px-4 py-2 rounded-full text-base font-bold text-primary flex items-center gap-2 shadow-xl shadow-black/35">
                  <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                  <span data-score-value>8.7</span>
                </div>
              </div>
              <div data-spotlight-type class="absolute top-4 left-4 z-10 bg-primary/80 text-white text-[11px] font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-lg">Anime</div>
              <img data-spotlight-rotating="1" alt="Spotlight de temporada" class="w-full h-full object-cover transition-transform duration-1000 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04]" data-alt="Bosque anime sereno con plantas m&iacute;sticas luminosas" data-fallback="img/fondoanime.webp" loading="lazy" referrerpolicy="no-referrer" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC2q_skNN7IEXINa_mQLWLtLooGZISRCJFcyFjR3-NhCFoQIV4rVukiI28cqzl7NwA1WRT10f9y_sBVA5KkJuprPzW2QkHsxVtQR7BFHYdKPirCvmU9EAQOC4weVwBfy07uTKoRhP1y13VRDG3hA9igt5f2qH88HGxQ7HJ0NF_FJ80hss_-Tj-Z198bRHpqi85zQe-swEt4fP6Cj4nJcWzWyRxEPOSaQv6kswMt5oDI9hJkaV25CtItXBgjs9EUI-R4wQ4uD4lO2a_z"/>
              <div class="absolute inset-0 bg-black/40 group-hover:bg-primary-container/20 transition-colors flex flex-col justify-end p-4">
                <h4 class="font-bold spotlight-subtitle">Undead Unluck</h4>
              </div>
            </div>
            <div id="spotlight-card-4" class="relative rounded-lg overflow-hidden group cursor-pointer bg-surface-container-low border border-outline-variant/10 spotlight-card" data-mal-id="54234" data-title="Haikyuu!! Final">
              <div class="absolute top-4 right-4 z-10">
                <div data-spotlight-score class="bg-surface-container-lowest/90 backdrop-blur px-4 py-2 rounded-full text-base font-bold text-primary flex items-center gap-2 shadow-xl shadow-black/35">
                  <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                  <span data-score-value>8.9</span>
                </div>
              </div>
              <div data-spotlight-type class="absolute top-4 left-4 z-10 bg-primary/80 text-white text-[11px] font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-lg">Anime</div>
              <img data-spotlight-rotating="1" alt="Spotlight de temporada" class="w-full h-full object-cover transition-transform duration-1000 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04]" data-alt="Escena de acción deportiva anime a ritmo frenético" data-fallback="img/fondoanime.webp" loading="lazy" referrerpolicy="no-referrer" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDJ5qAXtvlxTvCn09vDY4wAIFQ0pSixDM-EZ1_G2ikLShfPSIVgjE6BRxgPZEX_LsVn_WN_zHvw3ynIuf2qN3IVoT3ni8MkH0QMTcMI974YwQFJtcJeBMyvGHjDidzk9ppyNm3ysCA4gFA2aUBroWZ3GLFWIcpCLnxsJwGwJtIli6M5os4aZ5oTC_I4oEIhYCuZ55qZQomDSUFsexP297sinHqj50nQvCWlkqo4afO3JcqzF1tr5BSCvjidzOAnoBQUw-a_ATVuBrXKq"/>
              <div class="absolute inset-0 bg-black/40 group-hover:bg-primary-container/20 transition-colors flex flex-col justify-end p-4">
                <h4 class="font-bold spotlight-subtitle">Haikyuu!! Final</h4>
              </div>
            </div>
          </div>
        </section>
        <!-- Trending Now -->
        <section class="px-8 md:px-16 mt-[30px]">
          <div class="flex items-end justify-between mb-8">
            <div>
              <h2 class="text-3xl font-bold font-headline tracking-tight"><span class="section-title-em anime">Animes</span> Populares</h2>
              <div class="h-1 w-12 bg-primary mt-2 rounded-full"></div>
            </div>
            <a class="inline-flex items-center gap-2 rounded-full border border-primary/40 bg-primary/10 px-4 py-2 text-sm font-bold text-primary shadow-[0_0_12px_rgba(168,85,247,0.25)] transition-all hover:border-primary hover:bg-primary/20 hover:text-primary" href="<?= route_path('series') ?>">
              <span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' 1;">arrow_forward</span>
              Ver m&aacute;s
            </a>
          </div>
          <div class="carousel-wrap">
            <div class="carousel-nav-zone carousel-nav-left">
              <button id="trending-prev" class="carousel-nav-btn rounded-full bg-surface-container-high/90 px-4 py-3 text-2xl font-bold text-violet-200 hover:text-white hover:bg-surface-container-high transition-colors backdrop-blur" type="button" aria-label="Anterior">&lsaquo;</button>
            </div>
            <div class="carousel-nav-zone carousel-nav-right">
              <button id="trending-next" class="carousel-nav-btn rounded-full bg-surface-container-high/90 px-4 py-3 text-2xl font-bold text-violet-200 hover:text-white hover:bg-surface-container-high transition-colors backdrop-blur" type="button" aria-label="Siguiente">&rsaquo;</button>
            </div>
            <div id="trending-row" class="carousel-row overflow-x-auto no-scrollbar pb-8 scroll-smooth">
            <!-- Anime Card -->
            <div class="carousel-card group cursor-pointer" data-mal-id="21" data-title="One Punch Man">
              <div class="relative aspect-[2/3] rounded-lg overflow-hidden bg-surface-container-high mb-4 transition-all duration-500 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04] shadow-xl group-hover:shadow-primary-container/10">
                <img alt="P&oacute;ster de anime" class="w-full h-full object-cover" data-alt="Arte de personaje anime estilizado con colores vibrantes" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAf_5yu8ItByDxj1mtS86M1c4L-O9mK0YowNyDHJw9W3iBVZ8FyDg-EqWFT6-8OHsq_coAIAP0l958Z_MmlAFEJw4WhJphbR4l9vrMyWi2j3WVvXgYbjrEmsF1lNw_pei3FsECFfbJAjngFTubB3gifJd8ggZOoo0o4uF2DMo2NTvstvfpwoVeh2cIGu3izqmnqcGUvtn3bfztDQjI_3f_PqRWRxgPC6iAsoNpidsC32rJsZNIfPPYMhlo5KeRXtnq6jjUFrMUTk0d4"/>
                <div class="absolute inset-0 bg-primary-container/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                  <div class="flex flex-wrap gap-1 mb-2">
                    <span class="text-[10px] bg-background/80 px-2 py-0.5 rounded-full">Acci&oacute;n</span>
                    <span class="text-[10px] bg-background/80 px-2 py-0.5 rounded-full">Sobrenatural</span>
                  </div>
                </div>
                <div class="absolute top-3 right-3 bg-surface-container-lowest/80 backdrop-blur px-2 py-1 rounded text-xs font-bold text-primary flex items-center gap-1">
                  <span class="material-symbols-outlined text-[10px]" style="font-variation-settings: 'FILL' 1;">star</span> 9.2
                </div>
              </div>
              <h3 class="font-bold text-on-surface group-hover:text-primary transition-colors truncate">Jujutsu Kaisen</h3>
              <p class="text-xs text-on-surface-variant">Estudio MAPPA: Oto&ntilde;o 2023</p>
            </div>
            <div class="carousel-card group cursor-pointer" data-mal-id="21" data-title="One Punch Man">
              <div class="relative aspect-[2/3] rounded-lg overflow-hidden bg-surface-container-high mb-4 transition-all duration-500 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04] shadow-xl">
                <img alt="P&oacute;ster de anime" class="w-full h-full object-cover" data-alt="Ilustración dinámica de personaje anime con líneas definidas" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAG7j1khN7oa42GFgVO0GGKUGrbVAHUi69ljVSoVZw2JzGhXLGZPmMJVg-fuQthZ2BjRmQPHtiljA6oG5wD0e2QgsOauHs8rHo6CfVW_-qbdDPyc6WFV6NSVCR2bXYGfJANdbVZu3JCvQHDHUgQb-65j6hS6Sxh3KsQpp8TrqHwC-smxyfYY2kHVMRaba4VaaF-d0mASZkln-xJrLjreN-5Eryk7As7G1yYpz_0v5C6rK-TlGmV91TVOYBi2hNx43byaOojLl2FHhT9"/>
                <div class="absolute inset-0 bg-primary-container/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                  <div class="flex flex-wrap gap-1 mb-2">
                    <span class="text-[10px] bg-background/80 px-2 py-0.5 rounded-full">Psicol&oacute;gico</span>
                  </div>
                </div>
                <div class="absolute top-3 right-3 bg-surface-container-lowest/80 backdrop-blur px-2 py-1 rounded text-xs font-bold text-primary flex items-center gap-1">
                  <span class="material-symbols-outlined text-[10px]" style="font-variation-settings: 'FILL' 1;">star</span> 8.8
                </div>
              </div>
              <h3 class="font-bold text-on-surface group-hover:text-primary transition-colors truncate">Monster</h3>
              <p class="text-xs text-on-surface-variant">Estudio Madhouse: Cl&aacute;sico</p>
            </div>
            <div class="carousel-card group cursor-pointer" data-mal-id="21" data-title="One Punch Man">
              <div class="relative aspect-[2/3] rounded-lg overflow-hidden bg-surface-container-high mb-4 transition-all duration-500 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04] shadow-xl">
                <img alt="P&oacute;ster de anime" class="w-full h-full object-cover" data-alt="Ciudad anime detallada de noche con luces de neón" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAWK6GEmMfgg6EfbuobMqKnAGEoBR-JDGiUg00ci-kmNlIZtDkYI6Kn2Ke0sXVPIgNQCXFd-IsHB0kiCe8S2Zky7Vx8s8Yv0q9tzs5HYMvwxzBr6cc0oZ2o_86dXwOsGkjr4PDjv3ULdj9hX4Eanz5BPS7V2PEEqNSKPjrv61ZaMxz7RmFHx1Ah01hoqVReWKva7mpYkiw9VwRTTlov4AGu-uJhSG3Bln2hIug_IIGpsqGQXkCpk6x8T2qTeXcy2R7y7otUdEHtPV9T"/>
                <div class="absolute inset-0 bg-primary-container/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                  <div class="flex flex-wrap gap-1 mb-2">
                    <span class="text-[10px] bg-background/80 px-2 py-0.5 rounded-full">Ciberpunk</span>
                  </div>
                </div>
                <div class="absolute top-3 right-3 bg-surface-container-lowest/80 backdrop-blur px-2 py-1 rounded text-xs font-bold text-primary flex items-center gap-1">
                  <span class="material-symbols-outlined text-[10px]" style="font-variation-settings: 'FILL' 1;">star</span> 8.5
                </div>
              </div>
              <h3 class="font-bold text-on-surface group-hover:text-primary transition-colors truncate">Ciberpunk: Edgerunners</h3>
              <p class="text-xs text-on-surface-variant">Estudio Trigger: Limitado</p>
            </div>
            <div class="carousel-card group cursor-pointer" data-mal-id="21" data-title="One Punch Man">
              <div class="relative aspect-[2/3] rounded-lg overflow-hidden bg-surface-container-high mb-4 transition-all duration-500 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04] shadow-xl">
                <img alt="P&oacute;ster de anime" class="w-full h-full object-cover" data-alt="Silueta de espadachín anime contra una luna roja" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAR9x5iy6NpAOvezWnwzK2vesTKLBp29cvFNXUtwXht6dD1l_D5gxqns-j0rTWWHrKxrDe_gNz2hRv_bp72i7d3-B1XfbVcKL9UOdKUyxhkn7PVkTFK6gvR6FYot0OR1ZFv5kxq0aRiZ5uP-MPaYLYP3NbHMNLEF22YktmuRvb3820xC4ub0dVEhj3jZVy7-ZDMJBIgRaWhSUwsjWpaA_FPrQNkZspeF-urqaKWFj1HOZJbRz951KKRSdvfM9UeaS3zgtT822TzJSbT"/>
                <div class="absolute inset-0 bg-primary-container/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                  <div class="flex flex-wrap gap-1 mb-2">
                    <span class="text-[10px] bg-background/80 px-2 py-0.5 rounded-full">Aventura</span>
                  </div>
                </div>
                <div class="absolute top-3 right-3 bg-surface-container-lowest/80 backdrop-blur px-2 py-1 rounded text-xs font-bold text-primary flex items-center gap-1">
                  <span class="material-symbols-outlined text-[10px]" style="font-variation-settings: 'FILL' 1;">star</span>
                  <span>9.1</span>
                </div>
              </div>
              <h3 class="font-bold text-on-surface group-hover:text-primary transition-colors truncate">Demon Slayer</h3>
              <p class="text-xs text-on-surface-variant">Estudio ufotable: S3</p>
            </div>
            <div class="carousel-card group cursor-pointer" data-mal-id="21" data-title="One Punch Man">
              <div class="relative aspect-[2/3] rounded-lg overflow-hidden bg-surface-container-high mb-4 transition-all duration-500 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04] shadow-xl">
                <img alt="P&oacute;ster de anime" class="w-full h-full object-cover" data-alt="Paisaje rural anime tranquilo con luz suave" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCje1xHJ1WUA4g9yIIDEH_pR92E2kB6X1s837gGJD9UFj8E0mQUkxMN_rTpbmtI9LLd4eZnx3hIvb-U8EcBBpfSmNGAKWLzD_Eb1ppR87UR5wnTD3fPpOlY74X-I4P4p5J0Wpv-HTIa4kWox8n8dlfB6caMiMRLPuYkHJD_0lxfUZz4aT79i5VovpVuKkPEe3n5WiMUARuk4BRWUvsDr1xczcOQelSreIiJtPyQWQfnq0GKXptfgVdrHAE1Wxb4YsMhAapniXWKOFfH"/>
                <div class="absolute inset-0 bg-primary-container/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                  <div class="flex flex-wrap gap-1 mb-2">
                    <span class="text-[10px] bg-background/80 px-2 py-0.5 rounded-full">Recuentos de la vida</span>
                  </div>
                </div>
                <div class="absolute top-3 right-3 bg-surface-container-lowest/80 backdrop-blur px-2 py-1 rounded text-xs font-bold text-primary flex items-center gap-1">
                  <span class="material-symbols-outlined text-[10px]" style="font-variation-settings: 'FILL' 1;">star</span>
                  <span>8.9</span>
                </div>
              </div>
              <h3 class="font-bold text-on-surface group-hover:text-primary transition-colors truncate">Mushishi</h3>
              <p class="text-xs text-on-surface-variant">Estudio Artland: Cl&aacute;sico</p>
            </div>

            <div class="carousel-card group cursor-pointer" data-mal-id="21" data-title="One Punch Man">
              <div class="relative aspect-[2/3] rounded-lg overflow-hidden bg-surface-container-high mb-4 transition-all duration-500 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04] shadow-xl">
                <img alt="P&oacute;ster de anime" class="w-full h-full object-cover" src="https://cdn.myanimelist.net/images/anime/1171/109222l.jpg"/>
              </div>
              <h3 class="font-bold text-on-surface group-hover:text-primary transition-colors truncate">Hunter x Hunter</h3>
              <p class="text-xs text-on-surface-variant">Estudio Madhouse: Aventura</p>
            </div>
            <div class="carousel-card group cursor-pointer" data-mal-id="21" data-title="One Punch Man">
              <div class="relative aspect-[2/3] rounded-lg overflow-hidden bg-surface-container-high mb-4 transition-all duration-500 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04] shadow-xl">
                <img alt="P&oacute;ster de anime" class="w-full h-full object-cover" src="https://cdn.myanimelist.net/images/anime/1935/127974l.jpg"/>
              </div>
              <h3 class="font-bold text-on-surface group-hover:text-primary transition-colors truncate">Chainsaw Man</h3>
              <p class="text-xs text-on-surface-variant">Estudio MAPPA: Acci&oacute;n</p>
            </div>
            <div class="carousel-card group cursor-pointer" data-mal-id="21" data-title="One Punch Man">
              <div class="relative aspect-[2/3] rounded-lg overflow-hidden bg-surface-container-high mb-4 transition-all duration-500 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04] shadow-xl">
                <img alt="P&oacute;ster de anime" class="w-full h-full object-cover" src="https://cdn.myanimelist.net/images/anime/1208/94745l.jpg"/>
              </div>
              <h3 class="font-bold text-on-surface group-hover:text-primary transition-colors truncate">One Punch Man</h3>
              <p class="text-xs text-on-surface-variant">Estudio Madhouse: Comedia</p>
            </div>
            <div class="carousel-card group cursor-pointer" data-mal-id="21" data-title="One Punch Man">
              <div class="relative aspect-[2/3] rounded-lg overflow-hidden bg-surface-container-high mb-4 transition-all duration-500 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04] shadow-xl">
                <img alt="P&oacute;ster de anime" class="w-full h-full object-cover" src="https://cdn.myanimelist.net/images/anime/10/47347l.jpg"/>
              </div>
              <h3 class="font-bold text-on-surface group-hover:text-primary transition-colors truncate">Shingeki no Kyojin</h3>
              <p class="text-xs text-on-surface-variant">Estudio WIT: Drama</p>
            </div>
            </div>
          </div>
        </section>
        <!-- Popular Movies -->
        <section class="px-8 md:px-16">
          <div class="flex items-end justify-between mb-8">
            <div>
              <h2 class="text-3xl font-bold font-headline tracking-tight"><span class="section-title-em movie">Pel&iacute;culas</span> Populares</h2>
              <div class="h-1 w-12 bg-primary mt-2 rounded-full"></div>
            </div>
            <a class="inline-flex items-center gap-2 rounded-full border border-primary/40 bg-primary/10 px-4 py-2 text-sm font-bold text-primary shadow-[0_0_12px_rgba(168,85,247,0.25)] transition-all hover:border-primary hover:bg-primary/20 hover:text-primary" href="<?= route_path('movies') ?>">
              <span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' 1;">arrow_forward</span>
              Ver m&aacute;s
            </a>
          </div>
          <div class="carousel-wrap">
            <div class="carousel-nav-zone carousel-nav-left">
              <button id="movies-prev" class="carousel-nav-btn rounded-full bg-surface-container-high/90 px-4 py-3 text-2xl font-bold text-violet-200 hover:text-white hover:bg-surface-container-high transition-colors backdrop-blur" type="button" aria-label="Anterior">&lsaquo;</button>
            </div>
            <div class="carousel-nav-zone carousel-nav-right">
              <button id="movies-next" class="carousel-nav-btn rounded-full bg-surface-container-high/90 px-4 py-3 text-2xl font-bold text-violet-200 hover:text-white hover:bg-surface-container-high transition-colors backdrop-blur" type="button" aria-label="Siguiente">&rsaquo;</button>
            </div>
            <div id="movies-row" class="carousel-row overflow-x-auto no-scrollbar pb-8 scroll-smooth">
            <div class="carousel-card group cursor-pointer" data-mal-id="21" data-title="One Punch Man">
              <div class="relative aspect-[2/3] rounded-lg overflow-hidden bg-surface-container-high mb-4 transition-all duration-500 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04] shadow-xl">
                <img alt="P&oacute;ster de pel&iacute;cula" class="w-full h-full object-cover" src="https://cdn.myanimelist.net/images/anime/1598/118702l.jpg"/>
                <div class="absolute inset-0 bg-primary-container/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                  <div class="flex flex-wrap gap-1 mb-2"><span class="text-[10px] bg-background/80 px-2 py-0.5 rounded-full">Drama</span><span class="text-[10px] bg-background/80 px-2 py-0.5 rounded-full">Fantas&iacute;a</span></div>
                </div>
              </div>
              <h3 class="font-bold text-on-surface group-hover:text-primary transition-colors truncate">Suzume</h3>
              <p class="text-xs text-on-surface-variant">G&eacute;nero: Drama, Fantas&iacute;a</p>
            </div>
            <div class="carousel-card group cursor-pointer" data-mal-id="21" data-title="One Punch Man">
              <div class="relative aspect-[2/3] rounded-lg overflow-hidden bg-surface-container-high mb-4 transition-all duration-500 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04] shadow-xl">
                <img alt="P&oacute;ster de pel&iacute;cula" class="w-full h-full object-cover" src="https://cdn.myanimelist.net/images/anime/5/87048l.jpg"/>
                <div class="absolute inset-0 bg-primary-container/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                  <div class="flex flex-wrap gap-1 mb-2"><span class="text-[10px] bg-background/80 px-2 py-0.5 rounded-full">Romance</span><span class="text-[10px] bg-background/80 px-2 py-0.5 rounded-full">Drama</span></div>
                </div>
              </div>
              <h3 class="font-bold text-on-surface group-hover:text-primary transition-colors truncate">Tu Nombre.</h3>
              <p class="text-xs text-on-surface-variant">G&eacute;nero: Romance, Drama</p>
            </div>
            <div class="carousel-card group cursor-pointer" data-mal-id="21" data-title="One Punch Man">
              <div class="relative aspect-[2/3] rounded-lg overflow-hidden bg-surface-container-high mb-4 transition-all duration-500 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04] shadow-xl">
                <img alt="P&oacute;ster de pel&iacute;cula" class="w-full h-full object-cover" src="https://cdn.myanimelist.net/images/anime/6/79597l.jpg"/>
                <div class="absolute inset-0 bg-primary-container/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                  <div class="flex flex-wrap gap-1 mb-2"><span class="text-[10px] bg-background/80 px-2 py-0.5 rounded-full">Acci&oacute;n</span><span class="text-[10px] bg-background/80 px-2 py-0.5 rounded-full">Sci-Fi</span></div>
                </div>
              </div>
              <h3 class="font-bold text-on-surface group-hover:text-primary transition-colors truncate">Akira</h3>
              <p class="text-xs text-on-surface-variant">G&eacute;nero: Acci&oacute;n, Sci-Fi</p>
            </div>
            <div class="carousel-card group cursor-pointer" data-mal-id="21" data-title="One Punch Man">
              <div class="relative aspect-[2/3] rounded-lg overflow-hidden bg-surface-container-high mb-4 transition-all duration-500 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04] shadow-xl">
                <img alt="P&oacute;ster de pel&iacute;cula" class="w-full h-full object-cover" src="https://cdn.myanimelist.net/images/anime/5/75810l.jpg"/>
                <div class="absolute inset-0 bg-primary-container/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                  <div class="flex flex-wrap gap-1 mb-2"><span class="text-[10px] bg-background/80 px-2 py-0.5 rounded-full">Aventura</span><span class="text-[10px] bg-background/80 px-2 py-0.5 rounded-full">Fantas&iacute;a</span></div>
                </div>
              </div>
              <h3 class="font-bold text-on-surface group-hover:text-primary transition-colors truncate">El Viaje de Chihiro</h3>
              <p class="text-xs text-on-surface-variant">G&eacute;nero: Aventura, Fantas&iacute;a</p>
            </div>
            <div class="carousel-card group cursor-pointer" data-mal-id="21" data-title="One Punch Man">
              <div class="relative aspect-[2/3] rounded-lg overflow-hidden bg-surface-container-high mb-4 transition-all duration-500 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04] shadow-xl">
                <img alt="P&oacute;ster de pel&iacute;cula" class="w-full h-full object-cover" src="https://cdn.myanimelist.net/images/anime/7/75919l.jpg"/>
                <div class="absolute inset-0 bg-primary-container/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                  <div class="flex flex-wrap gap-1 mb-2"><span class="text-[10px] bg-background/80 px-2 py-0.5 rounded-full">Aventura</span><span class="text-[10px] bg-background/80 px-2 py-0.5 rounded-full">Drama</span></div>
                </div>
              </div>
              <h3 class="font-bold text-on-surface group-hover:text-primary transition-colors truncate">La Princesa Mononoke</h3>
              <p class="text-xs text-on-surface-variant">G&eacute;nero: Aventura, Drama</p>
            </div>
            <div class="carousel-card group cursor-pointer" data-mal-id="21" data-title="One Punch Man">
              <div class="relative aspect-[2/3] rounded-lg overflow-hidden bg-surface-container-high mb-4 transition-all duration-500 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04] shadow-xl">
                <img alt="P&oacute;ster de pel&iacute;cula" class="w-full h-full object-cover" src="https://cdn.myanimelist.net/images/anime/1775/111742l.jpg"/>
                <div class="absolute inset-0 bg-primary-container/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                  <div class="flex flex-wrap gap-1 mb-2"><span class="text-[10px] bg-background/80 px-2 py-0.5 rounded-full">Aventura</span><span class="text-[10px] bg-background/80 px-2 py-0.5 rounded-full">Fantas&iacute;a</span></div>
                </div>
              </div>
              <h3 class="font-bold text-on-surface group-hover:text-primary transition-colors truncate">El Castillo Ambulante</h3>
              <p class="text-xs text-on-surface-variant">G&eacute;nero: Aventura, Fantas&iacute;a</p>
            </div>
            <div class="carousel-card group cursor-pointer" data-mal-id="21" data-title="One Punch Man">
              <div class="relative aspect-[2/3] rounded-lg overflow-hidden bg-surface-container-high mb-4 transition-all duration-500 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04] shadow-xl">
                <img alt="P&oacute;ster de pel&iacute;cula" class="w-full h-full object-cover" src="https://cdn.myanimelist.net/images/anime/10/75815l.jpg"/>
                <div class="absolute inset-0 bg-primary-container/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                  <div class="flex flex-wrap gap-1 mb-2"><span class="text-[10px] bg-background/80 px-2 py-0.5 rounded-full">Familiar</span><span class="text-[10px] bg-background/80 px-2 py-0.5 rounded-full">Fantas&iacute;a</span></div>
                </div>
              </div>
              <h3 class="font-bold text-on-surface group-hover:text-primary transition-colors truncate">Mi Vecino Totoro</h3>
              <p class="text-xs text-on-surface-variant">G&eacute;nero: Familiar, Fantas&iacute;a</p>
            </div>
            <div class="carousel-card group cursor-pointer" data-mal-id="21" data-title="One Punch Man">
              <div class="relative aspect-[2/3] rounded-lg overflow-hidden bg-surface-container-high mb-4 transition-all duration-500 ease-[cubic-bezier(0.2,0,0,1)] group-hover:scale-[1.04] shadow-xl">
                <img alt="P&oacute;ster de pel&iacute;cula" class="w-full h-full object-cover" src="https://cdn.myanimelist.net/images/anime/1889/141004l.jpg"/>
                <div class="absolute inset-0 bg-primary-container/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                  <div class="flex flex-wrap gap-1 mb-2"><span class="text-[10px] bg-background/80 px-2 py-0.5 rounded-full">Drama</span><span class="text-[10px] bg-background/80 px-2 py-0.5 rounded-full">Romance</span></div>
                </div>
              </div>
              <h3 class="font-bold text-on-surface group-hover:text-primary transition-colors truncate">El Tiempo Contigo</h3>
              <p class="text-xs text-on-surface-variant">G&eacute;nero: Drama, Romance</p>
            </div>
            </div>
          </div>
        </section>
      </div>
    </main>
    <!-- Footer Component -->
    <div data-layout="footer"></div>
    <script src="assets/js/layout.js?v=theme1"></script>
    <script src="assets/js/shared-utils.js?v=1"></script>
    <script src="assets/js/i18n.js"></script>
    <script src="assets/js/title-images.js?v=3"></script>
    <script src="assets/js/search.js?v=popular7"></script>
    <script src="assets/js/favorites.js?v=4"></script>
    <script src="assets/js/detail-links.js?v=5"></script>
    <script>
  document.addEventListener("DOMContentLoaded", () => {
    const heroImage = document.getElementById("hero-image");
    const heroVideo = document.getElementById("hero-video");
    const heroTitle = document.getElementById("hero-title");
    const heroMeta = document.getElementById("hero-meta");
    const heroDescription = document.getElementById("hero-description");
    const dots = document.getElementById("hero-dots");
    const prevBtn = document.getElementById("hero-prev");
    const nextBtn = document.getElementById("hero-next");
    const heroWatchBtn = document.getElementById("hero-watch");
    const heroMyListBtn = document.getElementById("hero-my-list");
    let heroItems = [];
    let heroIndex = 0;
    let heroTimer = null;
    let heroRenderToken = 0;
    let heroVideoToken = 0;
    let heroVideoTimer = null;
    const summaryCache = new Map();
    const localHeroOverrides = [
      { match: "attack on titan", src: ["img/snk.jpg"] },
      { match: "shingeki no kyojin", src: ["img/snk.jpg"] },
      { match: "snk", src: ["img/snk.jpg"] },
      { match: "death note", src: ["img/death_note.webp", "img/death-note.jpg", "img/deathnote.jpg", "img/death_note.jpg"] },
      { match: "fullmetal alchemist", src: ["img/fullmetal.webp"] },
      { match: "fullmetal alchemist: brotherhood", src: ["img/fullmetal.webp"] },
      { match: "one punch man", src: ["img/OPM.webp"] },
      { match: "demon slayer", src: ["img/knY.webp"] },
      { match: "kimetsu no yaiba", src: ["img/knY.webp"] },
      { match: "sword art online", src: ["img/SaO.webp"] }
    ];

    const heroVideoConfigByTitle = {
      "Frieren: Beyond Journey's End Season 2": "https://files.catbox.moe/6w5jzl.mp4",
      "Jujutsu Kaisen: Shimetsu Kaiyuu - Zenpen": "https://files.catbox.moe/jn0t8b.mp4",
      "Hell's Paradise Season 2": "https://files.catbox.moe/gtdtry.mp4",
      "Sentenced to Be a Hero": "https://files.catbox.moe/vlcrpl.mp4",
      "Oshi no Ko Season 3": "https://files.catbox.moe/0k4krs.mp4"
    };

    const stopHeroVideo = () => {
      heroVideoToken += 1;
      if (heroVideoTimer) {
        clearTimeout(heroVideoTimer);
        heroVideoTimer = null;
      }
      document.body.classList.remove("hero-video-on");
      document.body.classList.add("hero-video-off");
      if (heroVideo) {
        heroVideo.pause();
        heroVideo.removeAttribute("src");
        heroVideo.load();
      }
    };

    const startHeroVideo = (src) => {
      if (!heroVideo || !src) {
        stopHeroVideo();
        return;
      }
      const token = ++heroVideoToken;
      const startAfterMs = 2000;
      const startAt = Date.now() + startAfterMs;
      if (heroVideoTimer) {
        clearTimeout(heroVideoTimer);
        heroVideoTimer = null;
      }
      document.body.classList.add("hero-video-off");
      document.body.classList.remove("hero-video-on");
      heroVideo.pause();
      heroVideo.removeAttribute("src");
      heroVideo.load();
      heroVideo.src = src;
      heroVideo.preload = "metadata";
      heroVideo.muted = true;
      heroVideo.loop = true;
      heroVideo.playsInline = true;
      const tryPlay = () => {
        if (token !== heroVideoToken) return;
        const remaining = startAt - Date.now();
        if (remaining > 0) {
          heroVideoTimer = setTimeout(tryPlay, remaining);
          return;
        }
        const playPromise = heroVideo.play();
        if (playPromise?.then) {
          playPromise
            .then(() => {
              if (token !== heroVideoToken) return;
              document.body.classList.add("hero-video-on");
              document.body.classList.remove("hero-video-off");
            })
            .catch(() => {});
        }
      };
      heroVideoTimer = setTimeout(tryPlay, startAfterMs);
      heroVideo.onloadeddata = () => {
        if (token !== heroVideoToken) return;
        tryPlay();
      };
      heroVideo.onerror = () => {
        if (token !== heroVideoToken) return;
        stopHeroVideo();
      };
    };

    const applyHeroVideo = (item, title) => {
      if (!heroVideo) return;
      const key = item?.__displayTitle || item?.title || title || "";
      const src = heroVideoConfigByTitle[key];
      if (src) startHeroVideo(src);
      else stopHeroVideo();
    };

    const pickHeroImage = async (item, fallback) => {
      const title = `${item?.title || ""} ${item?.title_english || ""}`.toLowerCase();
      const override = localHeroOverrides.find((x) => title.includes(x.match));
      if (!override) return fallback;
      const candidates = Array.isArray(override.src) ? override.src : [override.src];
      for (const candidate of candidates) {
        const chosen = await new Promise((resolve) => {
          const test = new Image();
          test.onload = () => resolve(candidate);
          test.onerror = () => resolve(null);
          test.src = candidate;
        });
        if (chosen) return chosen;
      }
      return fallback;
    };

    const shortSpanishSummary = async (text) => {
      const raw = (text || "").trim();
      if (!raw) return "Sinopsis no disponible.";
      if (summaryCache.has(raw)) return summaryCache.get(raw);
      const cleanSentences = (value) => {
        const normalized = (value || "").replace(/\s+/g, " ").trim();
        if (!normalized) return "Sinopsis no disponible.";
        const parts = normalized.match(/[^.!?]+[.!?]/g) || [normalized];
        const merged = parts.slice(0, 2).join(" ").trim();
        return merged.endsWith(".") || merged.endsWith("!") || merged.endsWith("?")
          ? merged
          : `${merged}.`;
      };
      try {
        const res = await fetch(
          "https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=es&dt=t&q=" +
            encodeURIComponent(raw)
        );
        const data = await res.json();
        const translated = (data?.[0] || []).map((r) => r?.[0] || "").join("").trim();
        const short = cleanSentences(translated);
        summaryCache.set(raw, short || "Sinopsis no disponible.");
        return summaryCache.get(raw);
      } catch {
        const short = cleanSentences(raw);
        summaryCache.set(raw, short || "Sinopsis no disponible.");
        return summaryCache.get(raw);
      }
    };

    const setHero = async (idx) => {
      if (!heroItems.length) return;
      heroIndex = (idx + heroItems.length) % heroItems.length;
      const renderToken = ++heroRenderToken;
      const item = heroItems[heroIndex];
      const bg =
        item?.__image ||
        item?.trailer?.images?.maximum_image_url ||
        item?.trailer?.images?.large_image_url ||
        item?.images?.webp?.large_image_url ||
        item?.images?.jpg?.large_image_url ||
        item?.images?.jpg?.image_url ||
        "";
      if (bg) heroImage.src = item?.__image ? bg : await pickHeroImage(item, bg);
      const heroDisplayTitle = item?.__displayTitle || item?.title || "Anime";
      if (heroDisplayTitle === "Oshi no Ko Season 3") {
        heroTitle.innerHTML = 'Oshi no Ko Season <span class="align-super text-[0.6em] ml-0.5">3</span>';
        heroTitle.classList.add("whitespace-nowrap");
      } else {
        heroTitle.textContent = heroDisplayTitle;
        heroTitle.classList.remove("whitespace-nowrap");
      }
      applyHeroVideo(item, heroDisplayTitle);
      heroMeta.textContent = `${item?.__episodes || item?.episodes || "?"} Episodios`;
      heroDescription.textContent = "Cargando sinopsis...";
      if (item?.__synopsis) {
        heroDescription.textContent = item.__synopsis;
      } else {
        const summary = await shortSpanishSummary(item?.synopsis || "");
        if (renderToken !== heroRenderToken) return;
        heroDescription.textContent = summary;
      }
      if (heroWatchBtn) {
        heroWatchBtn.onclick = () => {
          const title = item?.__displayTitle || item?.title || "anime";
          const malId = item?.mal_id || item?.__malId;
          if (malId || title) {
            window.location.href = window.AniDexShared.buildDetailUrl(malId, title);
          }
        };
      }
      if (heroMyListBtn) {
        heroMyListBtn.dataset.itemTitle = item?.__displayTitle || item?.title || "";
        heroMyListBtn.dataset.itemImage = bg || "";
        heroMyListBtn.dataset.itemType = "Anime";
        if (window.AniDexFavorites) window.AniDexFavorites.refresh();
      }
      Array.from(dots.children).forEach((dot, i) => {
        dot.className =
          i === heroIndex
            ? "h-3.5 w-8 rounded-full bg-primary"
            : "h-3.5 w-3.5 rounded-full bg-on-surface-variant/50";
      });
    };

    const heroSelections = [
      {
        id: 59978,
        displayTitle: "Frieren: Beyond Journey's End Season 2",
        image: "img/FBJE.webp"
      },
      {
        id: 57658,
        displayTitle: "Jujutsu Kaisen: Shimetsu Kaiyuu - Zenpen",
        episodes: 12,
        image: "img/jjk.webp",
        synopsis:
          "Tras las masacres de Shibuya, Itadori se aleja de la escuela mientras la cúpula jujutsu reactiva su ejecución y pone a Yuta Okkotsu tras él. Con el Juego de la Exterminación en marcha, hechiceros de distintas eras chocan y el destino de Fushiguro se vuelve el centro de la tormenta."
      },
      { id: 55825, displayTitle: "Hell's Paradise Season 2", image: "img/HPS.webp" },
      { id: 56009, displayTitle: "Sentenced to Be a Hero", image: "img/Sbh.webp" },
      { id: 60058, displayTitle: "Oshi no Ko Season 3", image: "img/Ok.webp" }
    ];

    const fetchHeroById = async (id) => {
      const url = "https://api.jikan.moe/v4/anime/" + id;
      try {
        let res = await fetch(url);
        if (res.status === 429) {
          await new Promise((r) => setTimeout(r, 700));
          res = await fetch(url);
        }
        if (!res.ok) return null;
        const json = await res.json();
        return json?.data || null;
      } catch {
        return null;
      }
    };

    const startHero = async () => {
      try {
        const results = [];
        for (const entry of heroSelections) {
          try {
            const item = await fetchHeroById(entry.id);
            if (item) {
              item.__displayTitle = entry.displayTitle || item.title || "Anime";
              item.__synopsis = entry.synopsis || "";
              item.__episodes = entry.episodes || null;
              item.__image = entry.image || "";
              item.__malId = entry.id;
              results.push(item);
            } else {
              results.push({
                title: entry.displayTitle || "Anime",
                __displayTitle: entry.displayTitle || "Anime",
                __synopsis: entry.synopsis || "",
                __episodes: entry.episodes || null,
                __image: entry.image || "",
                __malId: entry.id
              });
            }
          } catch {
            results.push({
              title: entry.displayTitle || "Anime",
              __displayTitle: entry.displayTitle || "Anime",
              __synopsis: entry.synopsis || "",
              __episodes: entry.episodes || null,
              __image: entry.image || "",
              __malId: entry.id
            });
          }
        }
        heroItems = results;
        dots.innerHTML = "";
        heroItems.forEach((_, i) => {
          const dot = document.createElement("button");
          dot.type = "button";
          dot.className = "h-3.5 w-3.5 rounded-full bg-on-surface-variant/50";
          dot.addEventListener("click", () => setHero(i));
          dots.appendChild(dot);
        });
        await setHero(0);
        heroTimer = setInterval(() => {
          setHero(heroIndex + 1);
        }, 30000);
      } catch {
        // fallback: keep static hero block
      }
    };

    if (heroVideo) document.body.classList.add("hero-video-off");
    if (prevBtn) prevBtn.addEventListener("click", () => setHero(heroIndex - 1));
    if (nextBtn) nextBtn.addEventListener("click", () => setHero(heroIndex + 1));
    startHero();


    const bindHorizontalArrows = (rowId, prevId, nextId) => {
      const row = document.getElementById(rowId);
      const prev = document.getElementById(prevId);
      const next = document.getElementById(nextId);
      if (!row || !prev || !next) return;
      const step = () => {
        const card = row.querySelector(".carousel-card") || row.firstElementChild;
        if (!card) return Math.max(280, Math.floor(row.clientWidth * 0.85));
        const styles = getComputedStyle(row);
        const gap = parseFloat(styles.columnGap || styles.gap || "0") || 0;
        const per = parseInt(styles.getPropertyValue("--cards")) || 5;
        const cardWidth = card.getBoundingClientRect().width;
        return Math.max(280, Math.round((cardWidth + gap) * per));
      };
      const setDisabled = (btn, disabled) => {
        btn.disabled = disabled;
        btn.classList.toggle("is-disabled", disabled);
        const zone = btn.closest(".carousel-nav-zone");
        if (zone) zone.classList.toggle("is-disabled", disabled);
      };
      const updateState = () => {
        const max = row.scrollWidth - row.clientWidth;
        if (max <= 2) {
          setDisabled(prev, true);
          setDisabled(next, true);
          return;
        }
        setDisabled(prev, row.scrollLeft <= 2);
        setDisabled(next, row.scrollLeft >= max - 2);
      };
      prev.addEventListener("click", () => row.scrollBy({ left: -step(), behavior: "smooth" }));
      next.addEventListener("click", () => row.scrollBy({ left: step(), behavior: "smooth" }));
      row.addEventListener("scroll", () => {
        requestAnimationFrame(updateState);
      });
      window.addEventListener("resize", updateState);
      setTimeout(updateState, 0);
    };

    bindHorizontalArrows("trending-row", "trending-prev", "trending-next");
    bindHorizontalArrows("movies-row", "movies-prev", "movies-next");

    const applyCardFallbacks = () => {
      const imgs = Array.from(document.querySelectorAll(".carousel-row img"));
      imgs.forEach((img) => {
        img.referrerPolicy = "no-referrer";
        img.loading = "lazy";
        img.decoding = "async";
        img.onerror = () => {
          img.onerror = null;
          img.src = "img/fondoanime.webp";
        };
      });
    };
    applyCardFallbacks();

    const imageOf = (item) =>
      item?.images?.webp?.large_image_url ||
      item?.images?.jpg?.large_image_url ||
      item?.images?.jpg?.image_url ||
      "";

    const API_BASE = "https://api.jikan.moe/v4";

    const normalize = (value) =>
      (value || "")
        .toString()
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .trim();

    const fetchTop = async (type, limit) => {
      const url = `${API_BASE}/top/anime?filter=bypopularity&type=${encodeURIComponent(type)}&limit=${limit}`;
      try {
        let res = await fetch(url);
        if (res.status === 429) {
          await new Promise((r) => setTimeout(r, 700));
          res = await fetch(url);
        }
        if (!res.ok) return null;
        const json = await res.json();
        return json?.data || [];
      } catch {
        return [];
      }
    };

    const ensureScoreBadge = (media) => {
      if (!media) return null;
      let badge = media.querySelector("[data-card-score], .anidex-score-badge");
      if (!badge) {
        badge = document.createElement("div");
        badge.setAttribute("data-card-score", "true");
        badge.className =
          "absolute top-3 right-3 bg-surface-container-lowest/80 backdrop-blur px-2 py-1 rounded text-xs font-bold text-primary flex items-center gap-1";
        badge.innerHTML =
          "<span class=\"material-symbols-outlined text-[10px]\" style=\"font-variation-settings: 'FILL' 1;\">star</span><span data-card-score-value>--</span>";
        media.appendChild(badge);
      }
      return badge;
    };

    const ensureGenreOverlay = (media) => {
      if (!media) return null;
      const candidates = Array.from(
        media.querySelectorAll("[data-card-genres], .absolute.inset-0")
      ).filter((el) => el instanceof HTMLElement);
      let overlay = candidates[0] || null;
      if (overlay) {
        overlay.setAttribute("data-card-genres", "true");
        if (!overlay.querySelector("[data-card-genres-list]")) {
          const list = overlay.querySelector(".flex") || document.createElement("div");
          list.className = list.className || "flex flex-wrap gap-1 mb-2";
          list.setAttribute("data-card-genres-list", "true");
          if (!overlay.contains(list)) overlay.appendChild(list);
        }
        candidates.slice(1).forEach((extra) => {
          if (extra.getAttribute("data-card-genres") === "true") extra.remove();
        });
        return overlay;
      }
      overlay = document.createElement("div");
      overlay.setAttribute("data-card-genres", "true");
      overlay.className =
        "absolute inset-0 bg-primary-container/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4";
      const list = document.createElement("div");
      list.setAttribute("data-card-genres-list", "true");
      list.className = "flex flex-wrap gap-1 mb-2";
      overlay.appendChild(list);
      media.appendChild(overlay);
      return overlay;
    };

    const updateGenreBadges = (media, genres, fallbackLabel) => {
      const overlay = ensureGenreOverlay(media);
      if (!overlay) return;
      const listEl = overlay.querySelector("[data-card-genres-list]");
      if (!listEl) return;
      const translateGenre = (label) => {
        const map = {
          "Action": "Acci&oacute;n",
          "Adventure": "Aventura",
          "Comedy": "Comedia",
          "Drama": "Drama",
          "Fantasy": "Fantas&iacute;a",
          "Sci-Fi": "Ciencia ficción",
          "Slice of Life": "Recuentos de la vida",
          "Supernatural": "Sobrenatural",
          "Romance": "Romance",
          "Mystery": "Misterio",
          "Thriller": "Suspenso",
          "Psychological": "Psicol&oacute;gico",
          "Horror": "Terror",
          "Sports": "Deportes",
          "Music": "Música",
          "School": "Escolar",
          "Historical": "Histórico",
          "Military": "Militar",
          "Space": "Espacio",
          "Mecha": "Mecha",
          "Game": "Juegos",
          "Vampire": "Vampiros",
          "Demons": "Demonios",
          "Samurai": "Samurai",
          "Police": "Policía",
          "Super Power": "Superpoderes",
          "Harem": "Harem",
          "Reverse Harem": "Harem inverso",
          "Magical Girl": "Chica mágica",
          "Kids": "Infantil",
          "Seinen": "Seinen",
          "Shounen": "Shounen",
          "Shoujo": "Shoujo",
          "Josei": "Josei",
          "Ecchi": "Ecchi"
        };
        const cleaned = (label || "").trim();
        return map[cleaned] || cleaned;
      };
      const labels = Array.isArray(genres) && genres.length
        ? genres.map(translateGenre)
        : [fallbackLabel];
      listEl.innerHTML = "";
      labels.slice(0, 2).forEach((label) => {
        if (!label) return;
        const span = document.createElement("span");
        span.className = "text-[10px] bg-background/80 px-2 py-0.5 rounded-full";
        span.textContent = label;
        listEl.appendChild(span);
      });
    };

    const updateCarouselFromTop = async (rowId, type) => {
      const row = document.getElementById(rowId);
      if (!row) return;
      const cards = Array.from(row.querySelectorAll(".carousel-card"));
      if (!cards.length) return;
      const listRaw = await fetchTop(type, cards.length + 6);
      if (!listRaw.length) {
        cards.forEach((card) => {
          const media =
            card.querySelector(".relative") ||
            card.querySelector(".aspect-\\[2/3\\]") ||
            card;
          if (media) ensureScoreBadge(media);
        });
        return;
      }
      let list = listRaw;
      if (rowId === "movies-row") {
        list = list.filter((it) => {
          const t = normalize(it?.title_english || it?.title || "");
          return !t.includes("mugen train") && !t.includes("kimetsu no yaiba");
        });
      }
      list = list.slice(0, cards.length);
      cards.forEach((card, idx) => {
        const item = list[idx] || null;
        const titleEl = card.querySelector("h3,h4,h5");
        const img = card.querySelector("img");
        const media =
          card.querySelector(".relative") ||
          card.querySelector(".aspect-\\[2/3\\]") ||
          card;
        const displayTitle = item?.title_english || item?.title || item?.title_japanese || "";
        if (item) {
          const src = imageOf(item) || "img/fondoanime.webp";
          if (img) {
            img.src = src;
            img.alt = displayTitle || img.alt || "Anime";
          }
          if (titleEl && displayTitle) titleEl.textContent = displayTitle;
          const genres = Array.isArray(item?.genres)
            ? item.genres.map((g) => g?.name).filter(Boolean)
            : [];
          const fallbackLabel = type === "movie" ? "Película" : "Anime";
          if (media) updateGenreBadges(media, genres, fallbackLabel);
        }
        if (media) {
          const badge = ensureScoreBadge(media);
          const score =
            typeof item?.score === "number" ? item.score.toFixed(1) : "--";
          const scoreValue = badge?.querySelector("[data-card-score-value]");
          if (scoreValue) scoreValue.textContent = score;
        }
        if (item?.mal_id) {
          card.dataset.malId = String(item.mal_id);
          card.onclick = () => {
            window.location.href = `detail?mal_id=${encodeURIComponent(String(item.mal_id))}&q=${encodeURIComponent(displayTitle || "")}`;
          };
        }
      });
    };

    updateCarouselFromTop("trending-row", "tv");
    updateCarouselFromTop("movies-row", "movie");

    const spotlightCards = ["spotlight-card-1", "spotlight-card-2", "spotlight-card-3", "spotlight-card-4"];
    const buildSpotlightPool = async () => {
      const [topAnime, topMovies] = await Promise.all([
        fetchTop("tv", 24),
        fetchTop("movie", 24)
      ]);
      const combined = [...(topAnime || []), ...(topMovies || [])];
      const seen = new Set();
      const pool = [];
      combined.forEach((it) => {
        const title = it?.title_english || it?.title || it?.title_japanese || "";
        const key = normalize(title);
        if (!key || seen.has(key)) return;
        seen.add(key);
        const score = typeof it?.score === "number" ? it.score.toFixed(1) : "--";
        const year =
          it?.year ||
          (it?.aired?.from ? new Date(it.aired.from).getFullYear() : "");
        const studio =
          (it?.studios && it.studios[0] && it.studios[0].name) ||
          (it?.producers && it.producers[0] && it.producers[0].name) ||
          "";
        const typeKey = normalize(it?.type).includes("movie") ? "movie" : "anime";
        const typeLabel = typeKey === "movie" ? "Película" : "Anime";
        pool.push({
          title,
          image: imageOf(it) || "img/fondoanime.webp",
          score,
          year,
          studio,
          typeLabel,
          typeKey,
          malId: it?.mal_id
        });
      });
      return pool;
    };
    const initSpotlightRotation = async () => {
      const cards = spotlightCards
        .map((id) => {
          const card = document.getElementById(id);
          if (!card) return null;
          const img = card.querySelector("img");
          const titleEl = card.querySelector("h3, h4");
          const scoreEl = card.querySelector("[data-spotlight-score]");
          const typeEl = card.querySelector("[data-spotlight-type]");
          const metaEl = card.querySelector("[data-spotlight-meta]");
          const fadeTargets = [img, titleEl].filter(Boolean);
          fadeTargets.forEach((el) => el.classList.add("spotlight-fade"));
          return { card, img, titleEl, scoreEl, typeEl, metaEl, fadeTargets };
        })
        .filter(Boolean);
      if (!cards.length) return;

      const pool = await buildSpotlightPool();
      if (!pool.length) return;

      const stepDelay = 5000;
      const stepsPerCycle = cards.length;
      const cycleInterval = stepDelay * stepsPerCycle;
      const fadeMs = 450;
      const cardOrder = [0, 1, 2, 3];
      const orderIndex = new Map(cardOrder.map((slotIndex, idx) => [slotIndex, idx]));
      const detailCache = new Map();
      const animePool = pool.filter((it) => it.typeKey === "anime");
      const moviePool = pool.filter((it) => it.typeKey === "movie");
      const idxRef = { anime: 0, movie: 0, all: 0 };
      const typePattern = ["anime", "movie", "anime", "movie"];
      const stateKey = "spotlightStateV3";
      const storageKey = "spotlightBaseTs";
      let stepTimer = null;
      let startTimer = null;
      let displayItems = new Array(cards.length).fill(null);

      const resolveDetails = async (item) => {
        if (!item?.malId) return null;
        if (detailCache.has(item.malId)) return detailCache.get(item.malId);
        try {
          const res = await fetch(`https://api.jikan.moe/v4/anime/${item.malId}`);
          if (!res.ok) return null;
          const json = await res.json();
          const data = json?.data || null;
          if (data) detailCache.set(item.malId, data);
          return data;
        } catch {
          return null;
        }
      };

      const applyItem = (slot, item) => {
        if (!slot || !item) return;
        const key = item.malId ? String(item.malId) : normalize(item.title);
        slot.card.dataset.spotlightKey = key;
        if (slot.titleEl) slot.titleEl.textContent = item.title;
        if (slot.img) {
          slot.img.src = item.image;
          slot.img.alt = item.title || slot.img.alt || "Anime destacado";
        }
        if (slot.scoreEl) {
          const scoreValue = slot.scoreEl.querySelector("[data-score-value]");
          if (scoreValue) scoreValue.textContent = item.score || "--";
        }
        if (slot.typeEl) slot.typeEl.textContent = item.typeLabel || "Anime";
        if (slot.metaEl) {
          const parts = [];
          if (item.year) parts.push(item.year);
          if (item.studio) parts.push(item.studio);
          slot.metaEl.textContent = parts.length ? parts.join(" - ") : "Datos no disponibles";
        }

        resolveDetails(item).then((detail) => {
          if (!detail) return;
          if (slot.card.dataset.spotlightKey !== key) return;
          const score =
            typeof detail?.score === "number" ? detail.score.toFixed(1) : "--";
          const year =
            detail?.year ||
            (detail?.aired?.from ? new Date(detail.aired.from).getFullYear() : "");
          const studio =
            (detail?.studios && detail.studios[0] && detail.studios[0].name) ||
            (detail?.producers && detail.producers[0] && detail.producers[0].name) ||
            "";
          const typeLabel = normalize(detail?.type).includes("movie") ? "Película" : "Anime";
          if (slot.scoreEl) {
            const scoreValue = slot.scoreEl.querySelector("[data-score-value]");
            if (scoreValue) scoreValue.textContent = score;
          }
          if (slot.typeEl) slot.typeEl.textContent = typeLabel;
          if (slot.metaEl) {
            const parts = [];
            if (year) parts.push(year);
            if (studio) parts.push(studio);
            slot.metaEl.textContent = parts.length ? parts.join(" - ") : "Datos no disponibles";
          }
        });
      };

      const takeUnique = (list, key, seen) => {
        if (!list.length) return null;
        const start = idxRef[key] || 0;
        for (let i = 0; i < list.length; i += 1) {
          const idx = (start + i) % list.length;
          const item = list[idx];
          const idKey = item?.malId ? String(item.malId) : normalize(item?.title);
          if (!idKey || seen.has(idKey)) continue;
          idxRef[key] = (idx + 1) % list.length;
          seen.add(idKey);
          return item;
        }
        return null;
      };

      const buildBaseSet = () => {
        const seen = new Set();
        const set = [];
        for (let i = 0; i < cards.length; i += 1) {
          const preferred = typePattern[i % typePattern.length] || "anime";
          const preferredList = preferred === "movie" ? moviePool : animePool;
          let item = takeUnique(preferredList, preferred, seen) || takeUnique(pool, "all", seen);
          if (item) set.push(item);
        }
        while (set.length < cards.length) {
          const item = takeUnique(pool, "all", seen);
          if (!item) break;
          set.push(item);
        }
        return set;
      };

      const loadState = () => {
        try {
          const raw = localStorage.getItem(stateKey);
          if (!raw) return null;
          const parsed = JSON.parse(raw);
          if (!parsed || !Array.isArray(parsed.items) || parsed.items.length !== cards.length) return null;
          if (!Array.isArray(parsed.prevItems) || parsed.prevItems.length !== cards.length) return null;
          if (!Number.isFinite(parsed.cycleIndex)) return null;
          return parsed;
        } catch {
          return null;
        }
      };

      const saveState = (items, prevItems, cycleIndex) => {
        try {
          localStorage.setItem(stateKey, JSON.stringify({ items, prevItems, cycleIndex }));
        } catch {}
      };

      const now = Date.now();
      let baseTs = now;
      try {
        const stored = Number(localStorage.getItem(storageKey));
        if (Number.isFinite(stored) && stored > 0) baseTs = stored;
        else localStorage.setItem(storageKey, String(baseTs));
      } catch {}
      if (baseTs > now) {
        baseTs = now;
        try { localStorage.setItem(storageKey, String(baseTs)); } catch {}
      }

      const elapsed = Math.max(0, now - baseTs);
      const globalStep = Math.floor(elapsed / stepDelay);
      const cycleIndex = Math.floor(globalStep / stepsPerCycle);
      const phase = globalStep % stepsPerCycle;
      const offset = Math.floor(cycleIndex / 3) % cards.length;
      let currentItems = new Array(cards.length).fill(null);
      let prevItems = new Array(cards.length).fill(null);

      const storedState = loadState();
      if (storedState && storedState.cycleIndex === cycleIndex) {
        currentItems = storedState.items;
        prevItems = storedState.prevItems || storedState.items;
      } else if (storedState && storedState.cycleIndex === cycleIndex - 1) {
        prevItems = storedState.items;
        const baseSet = buildBaseSet();
        currentItems = cards.map((_, i) => baseSet[(i + offset) % cards.length]);
      } else {
        const baseSet = buildBaseSet();
        currentItems = cards.map((_, i) => baseSet[(i + offset) % cards.length]);
        prevItems = currentItems.slice();
      }

      const renderPhase = (nextPhase, instant = false) => {
        cards.forEach((slot, i) => {
          const orderPos = orderIndex.get(i) ?? i;
          const nextItem = orderPos <= nextPhase ? currentItems[i] : prevItems[i];
          if (!nextItem) return;
          const nextKey = nextItem.malId ? String(nextItem.malId) : normalize(nextItem.title);
          const current = displayItems[i];
          const currentKey = current?.malId ? String(current.malId) : normalize(current?.title);
          if (currentKey === nextKey) return;
          if (instant) {
            applyItem(slot, nextItem);
            displayItems[i] = nextItem;
            return;
          }
          slot.fadeTargets.forEach((el) => el.classList.add("is-fading"));
          slot.card.classList.add("is-rotating");
          setTimeout(() => {
            applyItem(slot, nextItem);
            displayItems[i] = nextItem;
            slot.fadeTargets.forEach((el) => el.classList.remove("is-fading"));
            slot.card.classList.remove("is-rotating");
          }, fadeMs);
        });
      };

      renderPhase(phase, true);
      saveState(currentItems, prevItems, cycleIndex);

      cards.forEach((slot, i) => {
        slot.card.removeAttribute("onclick");
        slot.card.addEventListener("click", () => {
          const item = displayItems[i];
          if (!item) return;
          const title = item.title || "anime";
          if (item.malId) {
            window.location.href = `detail?mal_id=${encodeURIComponent(String(item.malId))}&q=${encodeURIComponent(title)}`;
          } else {
            window.location.href = `detail?q=${encodeURIComponent(title)}`;
          }
        });
      });

      const syncToTime = () => {
        const nowMs = Date.now();
        const elapsedMs = Math.max(0, nowMs - baseTs);
        const step = Math.floor(elapsedMs / stepDelay);
        const nextCycle = Math.floor(step / stepsPerCycle);
        const nextPhase = step % stepsPerCycle;
        const shift = Math.floor(nextCycle / 3) % cards.length;
        if (nextCycle !== cycleIndex || !currentItems.length) {
          prevItems = currentItems.length ? currentItems.slice() : prevItems;
          const baseSet = buildBaseSet();
          currentItems = cards.map((_, i) => baseSet[(i + shift) % cards.length]);
          saveState(currentItems, prevItems, nextCycle);
        }
        renderPhase(nextPhase, false);
      };

      const timeToNext = stepDelay - (elapsed % stepDelay || 0);
      const safeDelay = Math.max(timeToNext, 1200);
      startTimer = setTimeout(() => {
        syncToTime();
        stepTimer = setInterval(syncToTime, stepDelay);
      }, safeDelay);
    };

    initSpotlightRotation();

    if (window.AniDexI18n) window.AniDexI18n.init();
    if (window.AniDexTitleImages) window.AniDexTitleImages.init();
    if (window.AniDexSearch) window.AniDexSearch.init();
    if (window.AniDexDetailLinks) window.AniDexDetailLinks.init();
    if (window.AniDexFavorites) window.AniDexFavorites.init();
    if (window.AniDexDetailLinks) window.AniDexDetailLinks.init();
  });
    </script>
    <script data-ui-unlock>document.documentElement.classList.remove("preload-ui");</script>
  </body></html>










