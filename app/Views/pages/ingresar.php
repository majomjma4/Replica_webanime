<!DOCTYPE html>
<html class="dark" lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NekoraList - Ingresar</title>
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
        url(https://lh3.googleusercontent.com/aida-public/AB6AXuD3ytvgUmt57CRt0afVvpQ0-GHIIPkdzkKWeggGF9-2QfAFg1KfLtYfbvUmzXHI9-jsBNyu819HWIKk4RwxZ6sKHkAQGo-F2EdL5KW_Xe8SS6JfPNo6a0pkJi6x35GdY5uWXk8RF51RpF4cjbjz1Sf20sieqviNoDgM7mGxSQgBsCrKE1cNySASqIGQ9XZzHKXwU2RCQNBF0p6UxGRwfwQfhdP6YU2YgM0MEE5H9br5y_zAv68ddKPwgkJqWYTBxPbNA4ySFAh2WR8I);
      background-size: cover;
      background-position: center;
    }
    .auth-page {
      background-image:
        linear-gradient(to bottom, rgba(14,14,14,0.55), rgba(14,14,14,0.85)),
        url('img/fondoanime.webp'),
        url(https://lh3.googleusercontent.com/aida-public/AB6AXuD3ytvgUmt57CRt0afVvpQ0-GHIIPkdzkKWeggGF9-2QfAFg1KfLtYfbvUmzXHI9-jsBNyu819HWIKk4RwxZ6sKHkAQGo-F2EdL5KW_Xe8SS6JfPNo6a0pkJi6x35GdY5uWXk8RF51RpF4cjbjz1Sf20sieqviNoDgM7mGxSQgBsCrKE1cNySASqIGQ9XZzHKXwU2RCQNBF0p6UxGRwfwQfhdP6YU2YgM0MEE5H9br5y_zAv68ddKPwgkJqWYTBxPbNA4ySFAh2WR8I);
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
  <body class="bg-background text-on-background font-body min-h-screen flex flex-col auth-page">
    <div class="fixed top-0 left-0 right-0 z-30 flex items-center justify-between px-6 py-6 pointer-events-none">
      <a href="<?= route_path('user') ?>" class="pointer-events-auto inline-flex items-center gap-2 rounded-full bg-sky-500/90 px-5 py-3 text-sm font-bold uppercase tracking-widest text-white drop-shadow-[0_2px_6px_rgba(0,0,0,0.45)] shadow-lg shadow-sky-500/35 hover:bg-sky-400/95 transition-all" data-auth-back>
        <span class="material-symbols-outlined text-base leading-none">arrow_back</span>
        Regresar
      </a>
      <a href="<?= route_path('register') ?>" class="pointer-events-auto inline-flex items-center gap-2 rounded-full bg-gradient-to-br from-primary to-primary-container text-white drop-shadow-[0_2px_6px_rgba(0,0,0,0.45)] px-6 py-3 text-sm font-bold uppercase tracking-widest shadow-lg shadow-primary/25 hover:scale-[1.02] active:scale-95 transition-all">
        Crear cuenta
      </a>
    </div>
    <main class="auth-bg flex-1 flex items-center justify-center px-6 py-28">
      <section class="mx-auto w-[min(94vw,38rem)] max-w-none bg-surface-container-low/70 glass-effect border border-white/10 rounded-2xl p-[clamp(1.5rem,3vw,2.5rem)] shadow-2xl">
        <div class="space-y-2">
          <h1 class="font-headline font-extrabold text-[clamp(1.5rem,3.6vw,2.8rem)] tracking-tight leading-tight whitespace-nowrap">
            Bienvenido a <span class="inline-flex whitespace-nowrap"><span class="text-white">Nekora</span><span class="text-violet-400">List</span></span>
          </h1>
          <p class="text-on-surface-variant">Accede a tu perfil para guardar y organizar tus t&iacute;tulos.</p>
        </div>

        <form id="login-form" class="mt-8 flex flex-col gap-6" autocomplete="off">
          <div class="flex flex-col gap-2">
            <label class="text-xs font-semibold uppercase tracking-widest text-on-surface-variant ml-1" for="login-user">Correo o usuario</label>
            <div class="relative group">
              <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary">person</span>
              <input id="login-user" name="login-user" class="w-full bg-surface-container-lowest border-none rounded-xl py-4 pl-12 pr-4 text-on-surface placeholder:text-on-surface-variant/60 focus:ring-2 focus:ring-primary/20 transition-all outline-none" placeholder="nombre@ejemplo.com" type="text" required autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" />
            </div>
          </div>
          <div class="flex flex-col gap-2">
            <label class="text-xs font-semibold uppercase tracking-widest text-on-surface-variant ml-1" for="login-pass">Contraseña</label>
            <div class="relative group">
              <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary">lock</span>
              <input id="login-pass" name="login-pass" class="w-full bg-surface-container-lowest border-none rounded-xl py-4 pl-12 pr-4 text-on-surface placeholder:text-on-surface-variant/60 focus:ring-2 focus:ring-primary/20 transition-all outline-none" placeholder="••••••••" type="password" required autocomplete="new-password" />
            </div>
            <div class="flex justify-end">
              <a class="text-xs text-primary-dim hover:text-primary transition-colors cursor-pointer" id="btn-forgot-password" tabindex="0">¿Olvidaste tu contraseña?</a>
            </div>
          </div>
          <button class="w-full bg-gradient-to-br from-primary to-primary-container text-on-primary py-4 rounded-full font-headline font-bold text-lg hover:scale-[1.02] active:scale-95 transition-all duration-300 shadow-lg shadow-primary/20" type="submit">
            Iniciar sesión
          </button>
        </form>

        <p class="mt-6 text-center text-sm text-on-surface-variant">
          ¿No tienes cuenta? <a class="text-primary-dim font-bold hover:underline" href="<?= route_path('register') ?>">Regístrate ahora</a>
        </p>
      </section>
    </main>
    <script>
      (function () {
        const logged = localStorage.getItem("nekora_logged_in") === "true";
        const backBtn = document.querySelector("[data-auth-back]");
        if (!logged) {
          if (backBtn) backBtn.href = "index";
          try {
            history.pushState({ guest: true }, "", location.href);
            window.addEventListener("popstate", () => {
              window.location.href = "index";
            });
          } catch {}
        }
      })();
    </script>

    <script src="assets/js/layout.js?v=theme1"></script>
    <script src="assets/js/shared-utils.js?v=1"></script>
    <div id="login-success" class="fixed inset-0 z-[80] hidden">
      <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
      <div class="relative mx-auto mt-[20vh] w-[92%] max-w-md rounded-2xl bg-surface-container-high/90 border border-violet-500/30 p-6 shadow-2xl text-center overflow-hidden">
        <span class="pointer-events-none absolute -top-20 -right-16 h-40 w-40 rounded-full bg-violet-500/20 blur-3xl"></span>
        <span class="pointer-events-none absolute -bottom-24 -left-16 h-44 w-44 rounded-full bg-fuchsia-500/20 blur-3xl"></span>
        <button type="button" id="login-close" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-surface-container-low text-on-surface-variant hover:text-on-surface flex items-center justify-center">x</button>
        <img src="img/doraemon.gif" alt="Doraemon feliz" class="w-20 h-20 mx-auto mb-4 rounded-full" />
        <h3 id="login-title" class="font-headline text-2xl font-extrabold text-violet-400">
          ¡Bienvenido de nuevo, <span id="login-name">Usuario</span>!
        </h3>
        <p id="login-subtitle" class="text-white/80 text-sm leading-relaxed mt-4">
          Tu colección de animes te estaba esperando.
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

    <div id="forgot-password-modal" class="fixed inset-0 z-[80] hidden">
      <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" id="forgot-bg"></div>
      <div class="relative mx-auto mt-[20vh] w-[92%] max-w-md rounded-2xl bg-surface-container-high/95 border border-primary/30 p-8 shadow-2xl text-center overflow-hidden">
        <span class="pointer-events-none absolute -top-20 -right-16 h-40 w-40 rounded-full bg-primary/10 blur-3xl"></span>
        <button type="button" id="forgot-close" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-surface-container-low text-on-surface-variant hover:text-on-surface flex items-center justify-center">
          <span class="material-symbols-outlined text-base">close</span>
        </button>
        <div class="w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto mb-4 border border-primary/20">
          <span class="material-symbols-outlined text-3xl">mark_email_read</span>
        </div>
        <h3 class="font-headline text-xl font-extrabold text-white tracking-wider">Recuperar Contraseña</h3>
        <p class="text-on-surface-variant text-sm mt-3 leading-relaxed">Ingresa tu correo o usuario y te enviaremos las instrucciones para restablecer tu acceso.</p>
        <form id="forgot-form" class="mt-6 flex flex-col gap-4" autocomplete="off">
          <input id="forgot-user" class="w-full bg-surface-container-lowest border border-white/10 rounded-xl py-3 px-4 text-on-surface placeholder:text-on-surface-variant/60 focus:ring-2 focus:ring-primary/20 transition-all outline-none" placeholder="nombre@ejemplo.com" type="text" required />
          <button type="submit" id="forgot-btn" class="w-full py-3 rounded-full bg-gradient-to-br from-primary to-primary-container text-on-primary font-bold text-sm hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-primary/20 uppercase tracking-widest">Enviar enlace</button>
        </form>
      </div>
    </div>
    
    <div id="forgot-success" class="fixed inset-0 z-[80] hidden">
      <div class="absolute inset-0 bg-black/70 backdrop-blur-sm shadow-2xl"></div>
      <div class="relative mx-auto mt-[20vh] w-[92%] max-w-md rounded-2xl bg-surface-container-high/95 border border-emerald-500/30 p-8 shadow-2xl text-center overflow-hidden">
        <span class="pointer-events-none absolute -top-20 -right-16 h-40 w-40 rounded-full bg-emerald-500/10 blur-3xl"></span>
        <button type="button" id="forgot-success-close" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-surface-container-low text-on-surface-variant hover:text-on-surface flex items-center justify-center">
          <span class="material-symbols-outlined text-base">close</span>
        </button>
        <div class="w-16 h-16 bg-emerald-500/10 text-emerald-400 rounded-full flex items-center justify-center mx-auto mb-4 border border-emerald-500/20">
          <span class="material-symbols-outlined text-3xl">check_circle</span>
        </div>
        <h3 class="font-headline text-xl font-extrabold text-emerald-400 tracking-wider">?Correo enviado!</h3>
        <p class="text-on-surface-variant text-sm mt-3 leading-relaxed">Se ha enviado un mensaje al correo asociado a tu cuenta con las instrucciones para restablecer tu contrase?a.</p>
        <button type="button" id="forgot-success-btn" class="mt-6 w-full py-3 rounded-full bg-surface-container-low border border-white/5 text-on-surface font-bold text-sm hover:bg-surface-container-highest transition-colors uppercase tracking-widest">Aceptar</button>
      </div>
    </div>
    

    <script>
  (function () {
    const form = document.getElementById("login-form");
    const modal = document.getElementById("login-success");
    const errModal = document.getElementById("auth-error");
    const errMsg = document.getElementById("error-message");
    const nameEl = document.getElementById("login-name");
    const nameInput = document.getElementById("login-user");
    const titleEl = document.getElementById("login-title");
    const subtitleEl = document.getElementById("login-subtitle");
    const passInput = document.getElementById("login-pass");
    const closeBtn = document.getElementById("login-close");
    const errClose = document.getElementById("error-close");
    const errBtn = document.getElementById("error-btn");
    
    const forgotLink = document.getElementById("btn-forgot-password");
    const forgotModal = document.getElementById("forgot-password-modal");
    const forgotClose = document.getElementById("forgot-close");
    const forgotBg = document.getElementById("forgot-bg");
    const forgotForm = document.getElementById("forgot-form");
    const forgotSuccess = document.getElementById("forgot-success");
    const forgotSuccessClose = document.getElementById("forgot-success-close");
    const forgotSuccessBtn = document.getElementById("forgot-success-btn");

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
      btn.innerText = 'Verificando...';
      btn.disabled = true;

      const userOrEmail = (nameInput && nameInput.value.trim()) || "";
      const pass = (passInput && passInput.value.trim()) || "";
      
      try {
        const res = await fetch("<?= asset_path('api/auth') ?>?action=login", {
          method: 'POST',
          credentials: 'same-origin',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ userOrEmail: userOrEmail, password: pass })
        });
        const data = await res.json();
        
        if (data.success) {
            const isAdmin = data.isAdmin === true;
            const name = data.username;
            
            if (nameEl) nameEl.textContent = name;
            if (isAdmin) {
                if (titleEl) titleEl.textContent = "Bienvenido, administrador";
                if (subtitleEl) subtitleEl.textContent = "Tienes el control total de NekoraList";
            } else {
                if (titleEl) titleEl.innerHTML = `¡Bienvenido de nuevo, <span id="login-name">${name}</span>!`;
                if (subtitleEl) subtitleEl.textContent = "Tu colección de animes te estaba esperando.";
            }
            
            try {
                localStorage.setItem("nekora_logged_in", "true");
                localStorage.setItem("nekora_user", name);
                if (isAdmin) {
                    localStorage.setItem("nekora_admin", "true");
                    localStorage.setItem("nekora_premium", "true");
                } else {
                    localStorage.removeItem("nekora_admin");
                }
                localStorage.setItem("anidex_profile_name", name);
                
                const yearNow = String(new Date().getFullYear());
                if (!localStorage.getItem("anidex_profile_member_since")) {
                    localStorage.setItem("anidex_profile_member_since", yearNow);
                }
            } catch (e) {}
            
            if (modal) modal.classList.remove("hidden");
        } else {
            showError(data.error || 'Credenciales incorrectas');
        }
      } catch (err) {
        showError('Error de red u error interno del servidor.');
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

    if (forgotLink && forgotModal) {
      forgotLink.addEventListener("click", (e) => {
        e.preventDefault();
        forgotModal.classList.remove("hidden");
      });
      const hideForgot = () => {
        forgotModal.classList.add("hidden");
        const input = document.getElementById("forgot-user");
        if (input) input.value = '';
      };
      if (forgotClose) forgotClose.addEventListener("click", hideForgot);
      if (forgotBg) forgotBg.addEventListener("click", hideForgot);
      
      if (forgotForm) {
        forgotForm.addEventListener("submit", async (e) => {
          e.preventDefault();
          const btn = document.getElementById("forgot-btn");
          const input = document.getElementById("forgot-user");
          const origText = btn.innerText;
          btn.innerText = "Enviando...";
          btn.disabled = true;
          
          try {
            const res = await fetch("<?= asset_path('api/auth') ?>?action=forgot_password", {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ userOrEmail: input.value.trim() })
            });
            const data = await res.json();
            
            // Hide modal and clear input first
            hideForgot();
            
            if (data.success) {
              if (forgotSuccess) forgotSuccess.classList.remove("hidden");
            } else {
              showError(data.error || 'No se pudo procesar la solicitud.');
            }
          } catch (err) {
            hideForgot();
            showError('Error de conexi?n al enviar el correo.');
          } finally {
            btn.innerText = origText;
            btn.disabled = false;
          }
        });
      }
    }
    
    if (forgotSuccessClose) forgotSuccessClose.addEventListener("click", () => forgotSuccess.classList.add("hidden"));
    if (forgotSuccessBtn) forgotSuccessBtn.addEventListener("click", () => forgotSuccess.classList.add("hidden"));

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






