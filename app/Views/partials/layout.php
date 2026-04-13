<style data-layout-style="layout-logo">
  .logo-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.8rem;
    text-decoration: none;
  }




  .logo-icon {
    width: 44px;
    height: 44px;
    border-radius: 16px;
    object-fit: cover;
    box-shadow: 0 0 12px rgba(0, 198, 255, 0.35), 0 0 18px rgba(168, 85, 247, 0.35);
  }

  .logo-text {
    font-family: "Nunito", "Manrope", "Inter", system-ui, sans-serif;
    font-weight: 800;
    letter-spacing: 0.01em;
    font-size: 1.7rem;
    line-height: 1;
    background: linear-gradient(120deg, #00c6ff 0%, #58d0ff 25%, #a855f7 60%, #00c6ff 100%);
    background-size: 200% 200%;
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    text-shadow: 0 0 10px rgba(0, 198, 255, 0.45), 0 0 24px rgba(168, 85, 247, 0.4);
    position: relative;
    animation: logoShimmer 3.5s ease-in-out infinite;
  }

  .logo-text-stack {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    line-height: 1.1;
  }

  .logo-tagline {
    margin-top: 0.3rem;
    font-family: "Inter", "Manrope", system-ui, sans-serif;
    font-size: 0.82rem;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: none;
    color: rgba(210, 232, 255, 0.75);
    text-shadow: 0 0 10px rgba(0, 198, 255, 0.18);
  }

  .logo-text::after {
    content: "";
    position: absolute;
    inset: -2px;
    background: linear-gradient(110deg, transparent 0%, rgba(172, 163, 68, 0.871) 50%, transparent 100%);
    opacity: 0.4;
    filter: blur(8px);
    mix-blend-mode: screen;
    transform: translateX(-120%);
    animation: logoSweep 4.5s ease-in-out infinite;
    pointer-events: none;
  }

  .logo-badge.logo-badge--footer {
    padding: 0.25rem 0.65rem;
    gap: 1rem;
  }

  .logo-badge.logo-badge--footer .logo-text {
    font-size: 1.28rem;
  }

  .logo-badge.logo-badge--footer .logo-icon {
    width: 20px;
    height: 20px;
    border-radius: 14px;
  }

  .logo-badge.logo-badge--footer .logo-tagline {
    font-size: 0.74rem;
  }

  .footer-shell {
    position: relative;
    overflow: hidden;
    background: #0b0b0f;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 28px 28px 0 0;
    box-shadow: 0 -18px 36px rgba(0, 0, 0, 0.45), 0 0 18px rgba(168, 85, 247, 0.25);
  }

  .footer-shell::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: url("<?= asset_path('img/footer.webp') ?>");
    background-repeat: no-repeat;
    background-size: 100% auto;
    background-position: center bottom;
    background-position-y: bottom;
    opacity: 1;
    filter: none;
    z-index: 0;
  }

  .footer-shell::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(10, 10, 15, 0.95) 0%, rgba(10, 10, 15, 0.85) 55%, rgba(10, 10, 15, 0.95) 100%);
    z-index: 1;
  }

  .footer-content {
    position: relative;
    z-index: 2;
  }


  .footer-center {
    min-height: 180px;
  }

  .footer-brand {
    text-shadow: 0 0 18px rgba(56, 189, 248, 0.25), 0 0 28px rgba(168, 85, 247, 0.25);
  }

  .footer-brand-stack {
    text-align: center;
  }

  .footer-logo {
    width:  36px;
    height:  36px;
    border-radius: 12px;
    object-fit: cover;
    box-shadow: 0 0 12px rgba(0, 198, 255, 0.35), 0 0 18px rgba(168, 85, 247, 0.35);
  }

  .footer-name {
    font-family: "Nunito", "Manrope", "Inter", system-ui, sans-serif;
    font-weight: 800;
    font-size:  1.5rem;
    letter-spacing: 0.03em;
    background: linear-gradient(120deg, #00c6ff 0%, #58d0ff 25%, #a855f7 60%, #00c6ff 100%);
    background-size: 200% 200%;
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    animation: logoShimmer 3.8s ease-in-out infinite;
  }

  .footer-nav a {
    position: relative;
    display: inline-block;
    padding-bottom: 0.15rem;
    background: linear-gradient(120deg, #00c6ff 0%, #58d0ff 25%, #a855f7 60%, #00c6ff 100%);
    background-size: 200% 200%;
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: transparent;
    text-shadow: 0 0 8px rgba(56, 189, 248, 0.25);
    transition: text-shadow 0.25s ease-in-out, transform 0.25s ease-in-out, opacity 0.25s ease-in-out;
  }

  .footer-nav a::after {
    content: "";
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    height: 2px;
    border-radius: 999px;
    background: linear-gradient(90deg, #38bdf8, #a855f7);
    transform: scaleX(1);
    transform-origin: center;
    opacity: 0.6;
    transition: opacity 0.25s ease-in-out, transform 0.25s ease-in-out;
  }

  .footer-nav a:hover {
    background: linear-gradient(120deg, #00c6ff 0%, #58d0ff 25%, #a855f7 60%, #00c6ff 100%);
    background-size: 200% 200%;
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: transparent;
    text-shadow: 0 0 16px rgba(56, 189, 248, 0.65), 0 0 26px rgba(168, 85, 247, 0.55);
    transform: translateY(-2px) scale(1.03);
  }

  .footer-nav a:hover::after {
    opacity: 1;
    transform: scaleX(1.15);
  }

  .footer-tagline {
    font-size:  1.2rem;
    color: rgba(203, 213, 225, 0.85);
    letter-spacing: 0.01em;
    text-shadow: 0 0 16px rgba(56, 189, 248, 0.45);
  }

  .footer-nav .footer-icon {
    font-size: 0.95rem;
    opacity: 0.85;
    transition: transform 0.25s ease-in-out, opacity 0.25s ease-in-out;
  }

  .footer-nav a:hover .footer-icon {
    transform: translateY(-1px) scale(1.08);
    opacity: 1;
  }
    .footer-orbit--left,
    .footer-orbit--right {
      animation: none;
    }
  .footer-side-right {
    position: absolute;
    right: calc(clamp(20px, 4vw, 60px) + 140px);
    top: 50%;
    transform: translateY(-50%);
    margin-top: -28px;
    z-index: 6;
    display: flex;
    flex-direction: column;
    gap: calc(1.1rem + 6px);
    align-items: center;
    min-width: 220px;
  }

  @media (max-width: 900px) {
    .footer-side-right {
      position: static;
      transform: none;
      margin: 1.25rem 0 0 0;
    }
  }

  .footer-side-left {
    display: flex;
    flex-direction: column;
    gap: calc(1.1rem + 6px);
    position: absolute;
    left: calc(clamp(20px, 4vw, 60px) + 140px);
    top: 50%;
    transform: translateY(-50%);
    margin-top: -0px;
    z-index: 6;
  }

  @media (max-width: 900px) {
    .footer-side-left {
    display: flex;
    flex-direction: column;
    gap: calc(1.1rem + 6px);
      position: static;
      transform: none;
      margin: 0 0 1.25rem 0;
    }
  }

  .footer-side-left {
    display: flex;
    flex-direction: column;
    gap: calc(1.1rem + 6px);
    align-items: center;
    min-width: 220px;
  }

  .footer-side-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 44px !important;
    column-gap: 44px !important;
  }

  .footer-social-text {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.75rem;
  }

  .footer-social-text button,
  .footer-side-left button,
  .footer-side-right button {
    width: 46px;
    height: 46px;
    padding: 0;
    border-radius: 50% !important;
    justify-content: center;
    border: 1px solid rgba(168, 85, 247, 0.8);
    box-shadow:
      0 0 14px rgba(168, 85, 247, 0.6),
      0 0 22px rgba(56, 189, 248, 0.55),
      0 0 34px rgba(168, 85, 247, 0.4);
    animation: footerFloat 3.2s ease-in-out infinite, glowShift 4.2s ease-in-out infinite;
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease, color 0.25s ease;
    will-change: transform;
  }

  .footer-side-left button:nth-child(1) { animation-delay: 0s; }
  .footer-side-left button:nth-child(2) { animation-delay: 0.2s; }
  .footer-side-left button:nth-child(3) { animation-delay: 0.4s; }


  .footer-social-text button:hover,
  .footer-side-left button:hover,
  .footer-side-right button:hover {
    animation: none;
    transform: translateY(-1px) scale(1.04);
    border-color: rgba(56, 189, 248, 0.7);
    color: #f5f3ff;
    box-shadow: 0 0 16px rgba(56, 189, 248, 0.45), 0 0 22px rgba(168, 85, 247, 0.35);
  }
  .footer-btn-icon {
    display: inline-flex;
    width: 8px;
    height: 8px;
    align-items: center;
    justify-content: center;
  }

  .footer-btn-icon svg {
    width: 8px;
    height: 8px;
    fill: currentColor;
  }

  .footer-side-left button,
  .footer-side-right button {
    width: 46px;
    height: 46px;
    padding: 0;
    border-radius: 50%;
    justify-content: center;
  }

  .footer-side-left .footer-btn-label {
    display: none;
  }

  .footer-copy {
    margin-top: 0.5rem;
    font-size: 0.75rem;
    color: rgba(161, 161, 170, 0.85);
  }
  .footer-links a {
    color: rgba(212, 212, 216, 0.7);
    transition: color 0.2s ease, opacity 0.2s ease;
  }

  .footer-links a:hover {
    opacity: 1;
    text-shadow: 0 0 10px rgba(56, 189, 248, 0.35), 0 0 16px rgba(168, 85, 247, 0.35);
  }

  .footer-pill {
    background: rgba(24, 24, 27, 0.7);
    border: 1px solid rgba(168, 85, 247, 0.35);
    box-shadow: 0 0 12px rgba(168, 85, 247, 0.15);
  }

  .nav-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .nav-icon--active {
    animation: navPulse 2.2s ease-in-out infinite;
    filter: drop-shadow(0 0 6px rgba(0, 198, 255, 0.25));
  }

  @keyframes logoShimmer {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  @keyframes logoSweep {
    0% { transform: translateX(-140%); opacity: 0; }
    25% { opacity: 0.6; }
    50% { transform: translateX(140%); opacity: 0; }
    100% { transform: translateX(140%); opacity: 0; }
  }

  @keyframes glowShift {
    0% { box-shadow: 0 0 14px rgba(168, 85, 247, 0.75), 0 0 24px rgba(56, 189, 248, 0.35), 0 0 36px rgba(168, 85, 247, 0.45); border-color: rgba(168, 85, 247, 0.9); }
    50% { box-shadow: 0 0 14px rgba(56, 189, 248, 0.75), 0 0 26px rgba(168, 85, 247, 0.35), 0 0 38px rgba(56, 189, 248, 0.45); border-color: rgba(56, 189, 248, 0.9); }
    100% { box-shadow: 0 0 14px rgba(168, 85, 247, 0.75), 0 0 24px rgba(56, 189, 248, 0.35), 0 0 36px rgba(168, 85, 247, 0.45); border-color: rgba(168, 85, 247, 0.9); }
  }

@keyframes footerFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
  }

@keyframes navPulse {
    0%, 100% { transform: translateY(0) scale(1); }
    50% { transform: translateY(-1px) scale(1.08); }
  }

  @media (prefers-reduced-motion: reduce) {
    .nav-icon--active {
      animation: none;
    }
  }
  html, body {
    width: 100%;
    margin: 0;
    padding: 0;
  }

  body {
    overflow-x: hidden;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }

  main, .page, .app {
    width: 100%;
    flex: 1 0 auto;
  }

  footer {
    margin-top: auto;
  }

  .max-w-screen-2xl,
  .max-w-screen-xl,
  .max-w-screen-lg,
  .max-w-7xl,
  .max-w-6xl,
  .max-w-5xl,
  .max-w-4xl,
  .max-w-3xl,
  .max-w-2xl,
  .max-w-xl,
  .max-w-lg,
  .max-w-md,
  .max-w-sm,
  .max-w-xs,
  .max-w-48xl {
    max-width: 100% !important;
  }


  /* footer icon size override */
  .footer-btn-icon, .footer-btn-icon svg {
    width: 8px !important;
    height: 8px !important;
  }
  .footer-side-left button,
  .footer-side-right button {
    width: 46px !important;
    height: 46px !important;
    border-radius: 50% !important;
  }


  .footer-side-right .footer-btn-icon,
  .footer-side-right .footer-btn-icon svg {
    width: 5px !important;
    height: 5px !important;
  }


  /* footer hover override */
  .footer-shell:hover {
    border-top-color: rgba(56, 189, 248, 0.95) !important;
    box-shadow: 0 -20px 40px rgba(0, 0, 0, 0.5), 0 0 46px rgba(56, 189, 248, 0.6), 0 0 18px rgba(168, 85, 247, 0.25) !important;
  }


  /* footer hover effect (smooth) */
  .footer-shell {
    transition: box-shadow 0.9s ease, transform 0.9s ease, border-top-color 0.9s ease, filter 0.9s ease;
  }
  .footer-shell:hover {
    transform: translateY(-4px);
    filter: brightness(1.05);
    border-top-color: rgba(56, 189, 248, 0.95) !important;
    box-shadow: 0 -24px 44px rgba(0, 0, 0, 0.55), 0 0 48px rgba(56, 189, 248, 0.65), 0 0 20px rgba(168, 85, 247, 0.3) !important;
  }


  /* footer static glow */
  .footer-shell {
    box-shadow: 0 -18px 36px rgba(0, 0, 0, 0.45), 0 0 26px rgba(168, 85, 247, 0.7), 0 0 18px rgba(56, 189, 248, 0.35);
  }

  @media (max-width: 1024px) {
    .footer-content {
      padding-left: 1.25rem;
      padding-right: 1.25rem;
    }
    .footer-center {
      min-height: auto;
      gap: 1.25rem;
    }
    .footer-side-left,
    .footer-side-right {
      min-width: 0;
      width: 100%;
    }
  }
  @media (max-width: 768px) {
    nav.fixed > div {
      height: auto;
      min-height: 5rem;
      padding-top: 0.85rem;
      padding-bottom: 0.85rem;
      gap: 0.9rem;
    }
    .logo-badge {
      gap: 0.65rem;
      padding: 0.2rem 0.3rem;
      max-width: calc(100vw - 8rem);
    }
    .logo-icon {
      width: 38px;
      height: 38px;
      border-radius: 14px;
      flex-shrink: 0;
    }
    .logo-text {
      font-size: 1.25rem;
    }
    .logo-tagline {
      display: none;
    }
    .footer-shell {
      border-radius: 22px 22px 0 0;
    }
    .footer-content {
      padding-top: 2.5rem;
      padding-bottom: 2.5rem;
    }
    .footer-side-row {
      gap: 1rem !important;
      column-gap: 1rem !important;
      flex-wrap: wrap;
    }
    .footer-brand {
      flex-wrap: wrap;
      justify-content: center;
      text-align: center;
    }
    .footer-tagline {
      font-size: 1rem;
      max-width: 22rem;
    }
    .footer-nav {
      gap: 1rem;
    }
  }
  @media (max-width: 520px) {
    nav.fixed > div {
      padding-left: 0.85rem;
      padding-right: 0.85rem;
    }
    .logo-badge {
      max-width: calc(100vw - 7rem);
    }
    .logo-text {
      font-size: 1.08rem;
    }
    [data-guest-menu],
    [role="menu"][data-lang-menu],
    #layout-user-menu > div,
    [data-layout="header"] [role="menu"] {
      max-width: calc(100vw - 1rem);
    }
  }
</style>

<!-- Navbar Component -->
<template id="layout-header">
  <nav class="fixed top-0 w-full z-[1000] bg-neutral-950/70 backdrop-blur-xl shadow-[0px_20px_40px_rgba(0,0,0,0.4)] transition-all duration-300">
    <div class="flex items-center justify-between px-5 h-[5.6rem] pt-2 w-full font-['Manrope'] antialiased">
      <a class="logo-badge" href="<?= route_path('home') ?>" aria-label="NekoraList">
        <img src="<?= asset_path('img/icon3.webp') ?>" alt="NekoraList" class="logo-icon" />
        <span class="logo-text-stack">
          <span class="logo-text">NekoraList</span>
          <span class="logo-tagline">Tu portal a infinitas historias de anime</span>
        </span>
      </a>

      <div class="hidden md:flex items-center gap-8" id="main-menu"></div>
      <a id="admin-mode-btn" href="<?= route_path('admin') ?>" class="hidden md:inline-flex items-center gap-2 rounded-full border border-amber-400/40 bg-amber-500/10 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-amber-200 hover:bg-amber-500/20 hover:border-amber-300/70 transition-colors" style="display:none;">
        <span class="material-symbols-outlined text-[16px]">shield_person</span>
        Modo Admin
      </a>

      <div class="flex items-center gap-6">
        <div class="hidden lg:flex items-center bg-surface-container-high rounded-full px-4 py-2 w-36 group focus-within:ring-1 focus-within:ring-primary/30 transition-all">
          <span class="material-symbols-outlined text-violet-400 text-sm">search</span>
          <input
            id="layout-search-input"
            data-header-search="1"
            class="bg-transparent border-none focus:ring-0 text-sm w-full placeholder:text-on-surface-variant/50"
            placeholder="Buscar..."
            type="text"
            autocomplete="off"
            spellcheck="false"
          />
        </div>
<div class="relative">
          <button
            class="flex items-center gap-2 rounded-full bg-surface-container-high px-3 py-2 text-xs font-semibold text-on-surface-variant hover:text-on-surface transition-colors"
            type="button"
            aria-haspopup="menu"
            aria-expanded="false"
            data-lang-toggle
          >
            <img src="<?= asset_path('img/espana.webp') ?>" alt="ES" class="w-4 h-4" />
            <span class="material-symbols-outlined text-[16px]">expand_more</span>
          </button>
          <div
            class="absolute left-0 mt-2 hidden min-w-[145px] -translate-x-[5px] rounded-md bg-zinc-800 p-2 shadow-elevated border border-white/5"
            role="menu"
            data-lang-menu
          >
            <button
              class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-xs font-semibold text-zinc-200 hover:bg-zinc-700 hover:text-white"
              type="button"
              data-lang="es"
              role="menuitem"
            >
              <img src="<?= asset_path('img/espana.webp') ?>" alt="ES" class="w-4 h-4" /><span class="uppercase tracking-widest">ES</span>
            </button>
            <button
              class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-xs font-semibold text-zinc-200 hover:bg-zinc-700 hover:text-white"
              type="button"
              data-lang="en"
              role="menuitem"
            >
              <img src="<?= asset_path('img/reino-unido.webp') ?>" alt="EN" class="w-4 h-4" /><span class="uppercase tracking-widest">EN</span>
            </button>
          </div>
        </div>

        <div class="flex items-center gap-4">
          <div class="relative">
            <button
              class="w-10 h-10 rounded-full overflow-hidden border-2 border-violet-500/30 hover:border-violet-500 transition-colors cursor-pointer"
              type="button"
              data-profile-trigger
              aria-haspopup="menu"
              aria-expanded="false"
            >
              <img
                alt="Perfil de usuario"
                class="w-full h-full object-cover"
                data-profile-avatar
                data-alt="Primer plano de un avatar minimalista moderno"
                src="https://upload.wikimedia.org/wikipedia/en/b/bd/Doraemon_character.png"
              />
            </button>
            <div
              class="absolute right-0 mt-3 hidden w-56 rounded-2xl border border-white/10 bg-zinc-900/95 bg-surface-container-high p-4 shadow-[0_20px_45px_rgba(0,0,0,0.45)]"
              data-guest-menu
              role="menu"
            >
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl overflow-hidden border border-white/10">
                  <img
                    alt="Avatar invitado"
                    class="w-full h-full object-cover"
                    data-profile-avatar
                    src="https://upload.wikimedia.org/wikipedia/en/b/bd/Doraemon_character.png"
                  />
                </div>
                <div>
                  <div class="text-sm font-bold text-on-surface" data-profile-name>NekoraUser_62</div>
                  <div class="text-xs text-on-surface-variant">Invitado</div>
                </div>
              </div>
              <div class="mt-4 flex flex-col gap-2">
                <a href="<?= route_path('register') ?>" class="rounded-full bg-primary/20 text-primary px-4 py-2 text-xs font-semibold uppercase tracking-widest text-center hover:bg-primary/30">Crear cuenta</a>
                <a href="<?= route_path('login') ?>" class="rounded-full border border-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-center text-on-surface-variant hover:text-on-surface hover:border-primary/40">Ingresar</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<!-- Logged User Menu Component -->
<template id="layout-user-menu">
  <div class="absolute right-0 mt-3 w-64 rounded-2xl border border-white/10 bg-surface-container-high p-4 shadow-[0_20px_45px_rgba(0,0,0,0.45)]" role="menu">
    <div class="flex items-center gap-3 pb-3 border-b border-white/5">
      <div class="w-12 h-12 rounded-2xl overflow-hidden border border-violet-500/30">
        <img alt="Avatar" class="w-full h-full object-cover" data-profile-avatar src="https://upload.wikimedia.org/wikipedia/en/b/bd/Doraemon_character.png" />
      </div>
      <div class="min-w-0">
        <div class="text-sm font-bold text-on-surface truncate" data-profile-name>Usuario</div>
        <div class="text-[10px] font-bold uppercase tracking-widest text-primary flex items-center gap-1">
          <span class="material-symbols-outlined text-[12px]" data-role-icon>person</span>
          <span data-profile-role>Registrado</span>
        </div>
      </div>
    </div>
    
    <div class="mt-3 flex flex-col gap-1">
      <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-[0.2em] px-3 py-1">Mi Biblioteca</div>
      <a href="<?= route_path('user') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-primary hover:text-white hover:bg-primary/20 transition-all shadow-[0_0_15px_rgba(139,92,246,0.15)] border border-primary/20 bg-primary/10">
        <span class="material-symbols-outlined text-lg">account_circle</span>
        Mi Perfil
      </a>
      <a href="<?= route_path('user', ['tab' => 'favoritos']) ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-on-surface-variant hover:text-on-surface hover:bg-white/5 transition-all">
        <span class="material-symbols-outlined text-lg">favorite</span>
        Favoritos
      </a>
      <a href="<?= route_path('user', ['tab' => 'lista']) ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-on-surface-variant hover:text-on-surface hover:bg-white/5 transition-all">
        <span class="material-symbols-outlined text-lg">format_list_bulleted</span>
        Mi Lista
      </a>
      <a href="<?= route_path('user', ['tab' => 'pendientes']) ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-on-surface-variant hover:text-on-surface hover:bg-white/5 transition-all">
        <span class="material-symbols-outlined text-lg">schedule</span>
        Pendientes
      </a>
      <a href="<?= route_path('user', ['tab' => 'completados']) ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-on-surface-variant hover:text-on-surface hover:bg-white/5 transition-all">
        <span class="material-symbols-outlined text-lg">check_circle</span>
        Completados
      </a>
    </div>

    <!-- Secciones Premium/Admin Ocultas por defecto -->
    <div data-premium-only class="mt-2 pt-2 border-t border-white/5 flex flex-col gap-1 hidden">
      <div class="text-[10px] font-bold text-amber-400 uppercase tracking-[0.2em] px-3 py-1">Premium Perks</div>
      <a href="<?= route_path('payment') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-amber-200 hover:text-white hover:bg-amber-500/10 transition-all">
        <span class="material-symbols-outlined text-lg">workspace_premium</span>
        Beneficios Gold
      </a>
    </div>

    <div data-admin-only class="mt-2 pt-2 border-t border-white/5 flex flex-col gap-1 hidden">
      <div class="text-[10px] font-bold text-rose-400 uppercase tracking-[0.2em] px-3 py-1">Administraci&oacute;n</div>
      <a href="<?= route_path('admin') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-rose-200 hover:text-white hover:bg-rose-500/10 transition-all">
        <span class="material-symbols-outlined text-lg">admin_panel_settings</span>
        Panel Admin
      </a>
    </div>

    <div class="mt-4 pt-4 border-t border-white/5">
      <button id="logout-btn" class="w-full flex items-center justify-center gap-2 rounded-xl border border-white/5 bg-white/5 px-4 py-3 text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:text-error hover:border-error/30 hover:bg-error/5 transition-all">
        <span class="material-symbols-outlined text-sm">logout</span>
        Cerrar Sesi&oacute;n
      </button>
    </div>
  </div>
</template>

<!-- Footer Component -->
<template id="layout-footer">
  <footer class="footer-shell w-full mt-20 font-['Inter'] text-sm tracking-wide">
    <div class="footer-content w-full px-6 md:px-12 py-14">
     <div class="footer-side-left">
      <button type="button" data-external-url="https://discord.com">
        <img src="<?= asset_path('img/discord.webp') ?>" alt="Discord" class="footer-icon" />
      </button>
      <div class="footer-side-row">
          <button type="button" data-external-url="https://facebook.com">
            <img src="<?= asset_path('img/facebook.webp') ?>" alt="Facebook" class="footer-icon" />
          </button>
          <button type="button" data-external-url="https://instagram.com">
            <img src="<?= asset_path('img/instagram.webp') ?>" alt="Instagram" class="footer-icon" />
          </button>
        </div>
      </div>
      <div class="footer-side-right">
        <button type="button" data-external-url="https://www.youtube.com">
          <img src="<?= asset_path('img/y.webp') ?>" alt="YouTube" class="footer-icon" />
        </button>
        <div class="footer-side-row">
          <button type="button" data-external-url="https://www.tiktok.com">
            <img src="<?= asset_path('img/tt.webp') ?>" alt="TikTok" class="footer-icon" />
          </button>
          <button type="button" data-external-url="https://twitter.com">
            <img src="<?= asset_path('img/x.webp') ?>" alt="X" class="footer-icon" />
          </button>
        </div>
      </div> 
<div class="footer-center flex flex-col items-center text-center gap-6">
        <div class="footer-brand-stack flex flex-col items-center gap-2">
          <a class="footer-brand inline-flex items-center gap-3" href="<?= route_path('home') ?>" aria-label="NekoraList">
            <img src="<?= asset_path('img/icon3.webp') ?>" alt="NekoraList" class="footer-logo" />
            <span class="footer-name">NekoraList</span>
          </a>
          <div class="footer-tagline">Tu portal a infinitas historias de anime</div>
        </div>
        <nav class="footer-nav footer-links flex flex-wrap justify-center gap-6 text-sm font-semibold">
          <a href="<?= route_path('home') ?>">Inicio</a>
          <a href="<?= route_path('featured') ?>">Destacados</a>
          <a href="<?= route_path('ranking') ?>">Ranking</a>
        </nav>
        <div class="footer-copy">@ 2026 NekoraList - Todos los derechos reservados</div>
      </div>
    </div>
  </footer>
</template>































