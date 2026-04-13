<!DOCTYPE html>
<html class="dark" lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NekoraList - Registro</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
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
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .glass-effect { backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
    .auth-bg {
      background-image:
        linear-gradient(to bottom, rgba(14,14,14,0.75), rgba(14,14,14,0.95)),
        url('img/fondoanime.webp'),
        url(https://lh3.googleusercontent.com/aida-public/AB6AXuBLuWyRtfEWU2y5PdTv2Ss_SZbPEs9yQc0AmxQP3e_PJrxazRMc0Th_482PYo0XjqXN6GK6GFZT8lM5Ex_K1hChX0SjfDPp0lTWdJ41HPTrQAVhOi8yL_G_ginRX5Kiy9HhmlSHK_dDdQWqsNBxVUuw1zzDMVpdhxt194d7Qwq5_CP_9jSpHSEJFBEQgMlxmp-x-Vy92WQ7SxVS48_rOEeP3cVbpEyD_V9kR0CLyoQgTjXTHYuJIDxGfzeq0VkKiUXGrnWao-XxfVcG);
      background-size: cover;
      background-position: center;
    }
    .auth-page {
      background-image:
        linear-gradient(to bottom, rgba(14,14,14,0.55), rgba(14,14,14,0.85)),
        url('img/fondoanime.webp'),
        url(https://lh3.googleusercontent.com/aida-public/AB6AXuBLuWyRtfEWU2y5PdTv2Ss_SZbPEs9yQc0AmxQP3e_PJrxazRMc0Th_482PYo0XjqXN6GK6GFZT8lM5Ex_K1hChX0SjfDPp0lTWdJ41HPTrQAVhOi8yL_G_ginRX5Kiy9HhmlSHK_dDdQWqsNBxVUuw1zzDMVpdhxt194d7Qwq5_CP_9jSpHSEJFBEQgMlxmp-x-Vy92WQ7SxVS48_rOEeP3cVbpEyD_V9kR0CLyoQgTjXTHYuJIDxGfzeq0VkKiUXGrnWao-XxfVcG);
      background-size: cover;
      background-position: center;
    }
    .auth-page .auth-bg {
      position: relative;
      overflow: hidden;
    }
    .auth-page .auth-bg::after {
      content: "";
      position: absolute;
      left: 0;
      right: 0;
      bottom: 0;
      height: clamp(90px, 16vh, 180px);
      background: linear-gradient(to bottom, rgba(14,14,14,0) 0%, rgba(14,14,14,0.85) 65%, rgba(11,11,15,1) 100%);
      pointer-events: none;
    }
    .auth-page .footer-shell {
      margin-top: 0 !important;
      background: transparent;
      border-top-color: transparent;
      box-shadow: none;
    }
    .auth-page .footer-shell::after {
      background: linear-gradient(180deg, rgba(14,14,14,0) 0%, rgba(10,10,15,0.75) 55%, rgba(10,10,15,0.95) 100%);
    }
    .auth-page input:-webkit-autofill,
    .auth-page input:-webkit-autofill:hover,
    .auth-page input:-webkit-autofill:focus {
      -webkit-text-fill-color: #e7e5e4;
      box-shadow: 0 0 0 1000px #0b0b0f inset;
      caret-color: #e7e5e4;
      transition: background-color 5000s ease-in-out 0s;
    }
    .auth-page input:-moz-autofill {
      box-shadow: 0 0 0 1000px #0b0b0f inset;
      -moz-text-fill-color: #e7e5e4;
      caret-color: #e7e5e4;
    }
    </style>
    <link rel="icon" href="img/icon3.webp" />
  </head>
  <body class="bg-background text-on-background font-body min-h-screen flex flex-col auth-page overflow-hidden">
    <div class="fixed top-0 left-0 right-0 z-30 flex items-center justify-between px-6 py-6 pointer-events-none">
      <a href="<?= route_path('user') ?>" class="pointer-events-auto inline-flex items-center gap-2 rounded-full bg-sky-500/90 px-5 py-3 text-sm font-bold uppercase tracking-widest text-white drop-shadow-[0_2px_6px_rgba(0,0,0,0.45)] shadow-lg shadow-sky-500/35 hover:bg-sky-400/95 transition-all" data-auth-back>
        <span class="material-symbols-outlined text-base leading-none">arrow_back</span>
        Regresar
      </a>
      <a href="<?= route_path('login') ?>" class="pointer-events-auto inline-flex items-center gap-2 rounded-full bg-gradient-to-br from-primary to-primary-container text-white drop-shadow-[0_2px_6px_rgba(0,0,0,0.45)] px-6 py-3 text-sm font-bold uppercase tracking-widest shadow-lg shadow-primary/25 hover:scale-[1.02] active:scale-95 transition-all">
        Acceder
      </a>
    </div>
    <main class="auth-bg flex-1 flex items-center justify-center px-6 py-28">
      <section class="relative mx-auto w-[min(94vw,38rem)] max-w-none bg-surface-container-low/70 glass-effect border border-white/10 rounded-2xl p-[clamp(1.5rem,3vw,2.5rem)] shadow-2xl">
        <div class="space-y-2">
          <h1 class="font-headline font-extrabold text-[clamp(1.5rem,3.6vw,2.8rem)] tracking-tight leading-tight whitespace-nowrap">
            Únete a <span class="inline-flex whitespace-nowrap"><span class="text-white">Nekora</span><span class="text-violet-400">List</span></span>
          </h1>
          <p class="text-on-surface-variant max-w-md">Crea tu perfil y organiza, sigue o consulta la información de tus animes favoritos.</p>
        </div>

        <form id="register-form" class="mt-8 flex flex-col gap-6" autocomplete="off">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex flex-col gap-2">
              <label class="text-xs font-semibold uppercase tracking-widest text-on-surface-variant ml-1">Nombre de usuario</label>
              <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary">person</span>
                <input id="register-username" name="register-username" class="w-full bg-surface-container-lowest border-none rounded-xl py-4 pl-12 pr-4 text-on-surface placeholder:text-on-surface-variant/60 focus:ring-2 focus:ring-primary/20 transition-all outline-none" placeholder="Ej: Spike Spiegel" type="text" required autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" />
              </div>
            </div>
            <div class="flex flex-col gap-2">
              <label class="text-xs font-semibold uppercase tracking-widest text-on-surface-variant ml-1">Correo electrónico</label>
              <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary">alternate_email</span>
                <input name="register-email" class="w-full bg-surface-container-lowest border-none rounded-xl py-4 pl-12 pr-4 text-on-surface placeholder:text-on-surface-variant/60 focus:ring-2 focus:ring-primary/20 transition-all outline-none" placeholder="tu@anime.com" type="email" required autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" />
              </div>
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex flex-col gap-2">
              <label class="text-xs font-semibold uppercase tracking-widest text-on-surface-variant ml-1">Contraseña</label>
              <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary">lock</span>
                <input class="w-full bg-surface-container-lowest border-none rounded-xl py-4 pl-12 pr-4 text-on-surface placeholder:text-on-surface-variant/60 focus:ring-2 focus:ring-primary/20 transition-all outline-none" placeholder="••••••••" id="register-pass" name="register-pass" type="password" required autocomplete="new-password" />
              </div>
            </div>
            <div class="flex flex-col gap-2">
              <label class="text-xs font-semibold uppercase tracking-widest text-on-surface-variant ml-1">Confirmar contraseña</label>
              <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary">verified_user</span>
                <input class="w-full bg-surface-container-lowest border-none rounded-xl py-4 pl-12 pr-4 text-on-surface placeholder:text-on-surface-variant/60 focus:ring-2 focus:ring-primary/20 transition-all outline-none" placeholder="••••••••" id="register-pass-confirm" name="register-pass-confirm" type="password" required autocomplete="new-password" />
              </div>
            </div>
          </div>
          <button class="w-full bg-gradient-to-br from-primary to-primary-container text-on-primary py-4 rounded-full font-headline font-bold text-lg hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-primary/20" type="submit">
            Crear cuenta
          </button>
          <p class="text-center text-on-surface-variant text-sm">
            ¿Ya tienes cuenta? <a class="text-primary font-semibold hover:underline decoration-primary/40 transition-all" href="<?= route_path('login') ?>">Inicia sesión aquí</a>
          </p>
        </form>
      </section>
    </main>
    <script>
      (function () {
        const backBtn = document.querySelector("[data-auth-back]");
        const referrer = document.referrer || "";
        const isSameOriginReferrer = (() => {
          try {
            return !!referrer && new URL(referrer).origin === window.location.origin;
          } catch {
            return false;
          }
        })();
        const fallbackHref = "<?= route_path('home') ?>";
        const storedHref = (() => { try { return sessionStorage.getItem("anidex_auth_back") || ""; } catch { return ""; } })();
        const previousHref = storedHref || (isSameOriginReferrer && referrer !== window.location.href ? referrer : fallbackHref);

        if (backBtn) {
          backBtn.href = previousHref;
          backBtn.addEventListener("click", (event) => {
            if ((storedHref || isSameOriginReferrer) && window.history.length > 1) {
              event.preventDefault();
              window.history.back();
            }
          });
        }
      })();
    </script>

    <script src="assets/js/layout.js?v=theme1"></script>
    <script src="assets/js/shared-utils.js?v=1"></script>
    <div id="register-success" class="fixed inset-0 z-[80] hidden">
      <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
      <div class="relative mx-auto mt-[20vh] w-[92%] max-w-md rounded-2xl bg-surface-container-high/90 border border-violet-500/30 p-6 shadow-2xl text-center overflow-hidden">
        <span class="pointer-events-none absolute -top-20 -right-16 h-40 w-40 rounded-full bg-violet-500/20 blur-3xl"></span>
        <span class="pointer-events-none absolute -bottom-24 -left-16 h-44 w-44 rounded-full bg-fuchsia-500/20 blur-3xl"></span>
        <button type="button" id="register-close" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-surface-container-low text-on-surface-variant hover:text-on-surface flex items-center justify-center">x</button>
        <img src="img/doraemon.gif" alt="Doraemon feliz" class="w-20 h-20 mx-auto mb-4 rounded-full" />
        <h3 class="font-headline text-3xl font-extrabold text-white">&iexcl;Bienvenido a</h3>
        <h4 class="font-headline text-2xl font-extrabold text-violet-400 mt-2">
          NekoraList, <span id="register-name">Usuario</span>!
        </h4>
        <p class="text-white/80 text-sm leading-relaxed mt-4">
          Tu viaje por el mundo del anime comienza ahora.<br/>
          Agrega tus favoritos, organiza tu lista y descubre nuevas aventuras cada día.
        </p>
      </div>
    </div>
    <div id="auth-error" class="fixed inset-0 z-[80] hidden">
      <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
      <div class="relative mx-auto mt-[20vh] w-[92%] max-w-md rounded-2xl bg-surface-container-high/95 border border-error/30 p-8 shadow-2xl text-center overflow-hidden">
        <span class="pointer-events-none absolute -top-20 -right-16 h-40 w-40 rounded-full bg-error/10 blur-3xl"></span>
        <button type="button" id="error-close" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-surface-container-low text-on-surface-variant hover:text-on-surface flex items-center justify-center">
          <span class="material-symbols-outlined text-base">close</span>
        </button>
        <div class="w-16 h-16 bg-error/10 text-error rounded-full flex items-center justify-center mx-auto mb-4 border border-error/20">
          <span class="material-symbols-outlined text-3xl">warning</span>
        </div>
        <h3 class="font-headline text-xl font-extrabold text-error italic uppercase tracking-wider">¡Ups! Algo salió mal</h3>
        <p id="error-message" class="text-on-surface-variant text-sm mt-3 leading-relaxed"></p>
        <button type="button" id="error-btn" class="mt-6 w-full py-3 rounded-full bg-surface-container-low border border-white/5 text-on-surface font-bold text-sm hover:bg-surface-container-highest transition-colors uppercase tracking-widest">Aceptar</button>
      </div>
    </div>

    <script>
  (function () {
    const form = document.getElementById("register-form");
    const modal = document.getElementById("register-success");
    const errModal = document.getElementById("auth-error");
    const errMsg = document.getElementById("error-message");
    const nameEl = document.getElementById("register-name");
    const nameInput = document.getElementById("register-username");
    const closeBtn = document.getElementById("register-close");
    const errClose = document.getElementById("error-close");
    const errBtn = document.getElementById("error-btn");
    
    if (!form) return;

    const showError = (msg) => {
      if (errMsg) errMsg.textContent = msg;
      if (errModal) errModal.classList.remove("hidden");
    };

    const hideError = () => {
      if (errModal) errModal.classList.add("hidden");
    };

    if (errClose) errClose.addEventListener("click", hideError);
    if (errBtn) errBtn.addEventListener("click", hideError);
    
    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      
      const btn = form.querySelector('[type="submit"]');
      const textOrig = btn.innerText;
      btn.innerText = 'Creando cuenta...';
      btn.disabled = true;

      const pass = document.getElementById("register-pass");
      const passConfirm = document.getElementById("register-pass-confirm");
      if (pass && passConfirm && pass.value !== passConfirm.value) {
        showError("Las contraseñas no coinciden.");
        btn.innerText = textOrig;
        btn.disabled = false;
        return;
      }
      
      const name = (nameInput && nameInput.value.trim()) || "";
      const email = form.querySelector('[name="register-email"]').value.trim();
      
      try {
        const res = await fetch("<?= asset_path('api/auth') ?>?action=register", {
          method: 'POST',
          credentials: 'same-origin',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ username: name, email: email, password: pass.value })
        });
        const data = await res.json();
        
        if (data.success) {
            if (nameEl) nameEl.textContent = name;
            try {
                localStorage.setItem("nekora_logged_in", "true");
                localStorage.setItem("nekora_user", name);
                localStorage.setItem("anidex_profile_name", name);
                const yearNow = String(new Date().getFullYear());
                localStorage.setItem("anidex_profile_member_since", yearNow);
            } catch (e) {}
            if (modal) modal.classList.remove("hidden");
        } else {
            showError(data.error || 'Error al registrar el usuario.');
        }
      } catch (err) {
        showError('Error conectando al servidor.');
      } finally {
        btn.innerText = textOrig;
        btn.disabled = false;
      }
    });

    if (closeBtn) {
      closeBtn.addEventListener("click", () => {
        const urlParams = new URLSearchParams(window.location.search);
        const redirect = urlParams.get('redirect');
        window.location.href = redirect || "index";
      });
    }
  })();
    </script>
    <script src="assets/js/i18n.js"></script>
    <script>
  document.addEventListener("DOMContentLoaded", () => {
    if (window.AniDexI18n) window.AniDexI18n.init();
  });
    </script>
  </body>
</html>






