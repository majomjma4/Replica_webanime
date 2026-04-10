<template id="admin-sidebar">
  <style data-admin-logo>
    @import url("https://fonts.googleapis.com/css2?family=Nunito:wght@700;800&display=swap");
    .logo-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0.25rem 0.6rem;
      gap: 0.7rem;
      border-radius: 9999px;
      background: transparent;
    }
    .logo-badge--admin .logo-icon {
      width: 28px;
      height: 28px;
      border-radius: 10px;
    }
    .logo-icon {
      width: 32px;
      height: 32px;
      border-radius: 12px;
      object-fit: cover;
      box-shadow: 0 0 12px rgba(0, 198, 255, 0.35), 0 0 18px rgba(168, 85, 247, 0.35);
    }
    .logo-text {
      font-family: "Nunito", "Manrope", "Inter", system-ui, sans-serif;
      font-weight: 800;
      letter-spacing: 0.01em;
      font-size: 1.25rem;
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
    .logo-text::after {
      content: "";
      position: absolute;
      inset: -2px;
      background: linear-gradient(110deg, transparent 0%, rgba(172, 163, 68, 0.55) 50%, transparent 100%);
      opacity: 0.35;
      filter: blur(8px);
      mix-blend-mode: screen;
      transform: translateX(-120%);
      animation: logoSweep 4.5s ease-in-out infinite;
      pointer-events: none;
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
  </style>
  <aside class="h-screen w-64 fixed left-0 top-0 flex flex-col bg-[#0e0e0e] bg-[#131313] border-r border-[#484848]/20 shadow-[20px_0px_40px_rgba(0,0,0,0.4)] z-50">
    <div class="flex flex-col h-full py-8 gap-y-6">
      <div class="px-8 mb-4">
        <a class="logo-badge logo-badge--admin" href="<?= route_path('home') ?>" aria-label="NekoraList">
          <img src="img/icon3.png" alt="NekoraList" class="logo-icon" />
          <span class="logo-text">NekoraList</span>
        </a>
      </div>
      <nav class="flex-1 px-0">
        <ul class="space-y-1">
          <li>
            <a data-admin-item="requests" class="flex items-center gap-4 text-[#acabaa] hover:text-[#e7e5e4] hover:bg-[#131313] hover:scale-[1.02] py-4 px-6 mb-2 transition-all duration-200 font-headline font-bold tracking-tight text-sm uppercase" href="<?= route_path('admin') ?>">
              <span class="material-symbols-outlined" data-icon="pending_actions">pending_actions</span>
              <span>SOLICITUDES</span>
            </a>
          </li>
          <li>
            <a data-admin-item="manage" class="flex items-center gap-4 text-[#acabaa] hover:text-[#e7e5e4] hover:bg-[#131313] hover:scale-[1.02] py-4 px-6 mb-2 transition-all duration-200 font-headline font-bold tracking-tight text-sm uppercase" href="<?= route_path('admin_manage') ?>">
              <span class="material-symbols-outlined" data-icon="movie_filter">movie_filter</span>
              <span>GESTIONAR ANIME</span>
            </a>
          </li>
          <li>
            <a data-admin-item="users" class="flex items-center gap-4 text-[#acabaa] hover:text-[#e7e5e4] hover:bg-[#131313] hover:scale-[1.02] py-4 px-6 mb-2 transition-all duration-200 font-headline font-bold tracking-tight text-sm uppercase" href="<?= route_path('admin_users') ?>">
              <span class="material-symbols-outlined" data-icon="group">group</span>
              <span>USUARIOS</span>
            </a>
          </li>
          <li>
            <a data-admin-item="comments" class="flex items-center gap-4 text-[#acabaa] hover:text-[#e7e5e4] hover:bg-[#131313] hover:scale-[1.02] py-4 px-6 mb-2 transition-all duration-200 font-headline font-bold tracking-tight text-sm uppercase" href="<?= route_path('admin_comments') ?>">
              <span class="material-symbols-outlined" data-icon="forum">forum</span>
              <span>COMENTARIOS</span>
            </a>
          </li>
        </ul>
        <div class="px-6 mt-10">
          <a href="a%C3%B1adir.php" class="inline-flex w-full items-center justify-center rounded-full border border-[#9a8cff]/35 bg-gradient-to-r from-[#9a8cff] to-[#74d8ff] py-4 text-xs font-extrabold uppercase tracking-widest text-slate-950 shadow-xl shadow-[#7f8cff]/20 transition-all hover:brightness-110 active:scale-95">A&Ntilde;ADIR NUEVO ANIME</a>
        </div>
      </nav>
      <div class="px-6 pt-4 border-t border-outline-variant/10">
        <a data-admin-logout class="flex items-center gap-4 text-[#acabaa] hover:text-[#e7e5e4] py-3 px-3 transition-colors duration-200 font-headline font-bold tracking-tight text-sm uppercase" href="#">
          <span class="material-symbols-outlined" data-icon="logout">logout</span>
          <span>CERRAR MODO ADMIN</span>
        </a>
      </div>
    </div>
  </aside>
</template>








