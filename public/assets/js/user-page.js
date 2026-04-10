/* Extracted from app/Views/pages/user.php */

document.addEventListener("DOMContentLoaded", () => {
      if (window.AniDexI18n) window.AniDexI18n.init();
      if (window.AniDexTitleImages) window.AniDexTitleImages.init();
      if (window.AniDexSearch) window.AniDexSearch.init();
      if (window.AniDexDetailLinks) window.AniDexDetailLinks.init();
      if (window.AniDexFavorites) window.AniDexFavorites.init();
    });

/* MVC: User Profile (Model / View / Controller) */
    (function () {
      // ========================
      // MODEL: Data & Storage
      // ========================
      const editBtn = document.getElementById("profile-avatar-edit");
      const openBtn = document.getElementById("profile-avatar-open");
      const modal = document.getElementById("avatar-modal");
      const profileModal = document.getElementById("profile-modal");
      const grid = document.getElementById("avatar-grid");
      const avatarImg = document.querySelector("img[alt='Foto de perfil']");
      const previewImg = document.querySelector("[data-profile-avatar-preview]");
      const getK = (k) => (window.AniDexProfile && window.AniDexProfile.getIsolatedKey) ? window.AniDexProfile.getIsolatedKey(k) : k;

      if (!modal || !grid || !avatarImg) return;

      const DEFAULT_PROFILE_AVATAR = "https://upload.wikimedia.org/wikipedia/en/b/bd/Doraemon_character.png";
      const avatars = [
        DEFAULT_PROFILE_AVATAR
      ];

      let returnToProfile = false;
      const openModal = () => {
        returnToProfile = !!(profileModal && !profileModal.classList.contains("hidden"));
        if (returnToProfile) profileModal.classList.add("hidden");
        modal.classList.remove("hidden");
        document.body.style.overflow = "hidden";
      };
      const closeModal = () => {
        modal.classList.add("hidden");
        if (returnToProfile && profileModal) {
          profileModal.classList.remove("hidden");
          document.body.style.overflow = "hidden";
        } else {
          document.body.style.overflow = "";
        }
        returnToProfile = false;
      };

      const renderAvatars = () => {
        if (!avatars.length) {
          grid.innerHTML = Array.from({ length: 12 }).map(() => `
          <div class="aspect-square bg-surface-container-low/80 animate-pulse"></div>
        `).join("");
          return;
        }
        grid.innerHTML = avatars.map((src) => `
        <button type="button" class="group relative aspect-square overflow-hidden bg-surface-container-low hover:bg-surface-container-high transition-colors" data-avatar-src="${src}">
          <img src="${src}" alt="Avatar" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" decoding="async" />
        </button>
      `).join("");
      };
      renderAvatars();

      // Cargar avatares desde Jikan (personajes populares)
      Promise.all([
        fetch("https://api.jikan.moe/v4/characters?order_by=favorites&sort=desc&limit=24&page=1", { cache: "no-store" }),
        fetch("https://api.jikan.moe/v4/characters?order_by=favorites&sort=desc&limit=8&page=2", { cache: "no-store" })
      ])
        .then(async ([res1, res2]) => {
          const json1 = res1 && res1.ok ? await res1.json() : null;
          const json2 = res2 && res2.ok ? await res2.json() : null;
          const list = [
            ...(json1 && json1.data ? json1.data : []),
            ...(json2 && json2.data ? json2.data : [])
          ];
          list.forEach((it) => {
            const img = it?.images?.webp?.image_url || it?.images?.jpg?.image_url;
            if (img) avatars.push(img);
          });
          renderAvatars();
        })
        .catch(() => {
          grid.innerHTML = '<div class="col-span-full text-center text-xs text-on-surface-variant">No se pudieron cargar avatares.</div>';
        });

      const paintProfileAvatar = (src) => {
        const safeSrc = src || DEFAULT_PROFILE_AVATAR;
        if (avatarImg) {
          avatarImg.src = safeSrc;
          avatarImg.onerror = () => {
            avatarImg.onerror = null;
            avatarImg.src = DEFAULT_PROFILE_AVATAR;
          };
        }
        if (previewImg) {
          previewImg.src = safeSrc;
          previewImg.onerror = () => {
            previewImg.onerror = null;
            previewImg.src = DEFAULT_PROFILE_AVATAR;
          };
        }
        document.querySelectorAll("img[data-profile-avatar]").forEach((img) => {
          img.src = safeSrc;
          img.onerror = () => {
            img.onerror = null;
            img.src = DEFAULT_PROFILE_AVATAR;
          };
        });
      };

      const syncAvatars = (src) => {
        if (!src) return;
        paintProfileAvatar(src);
        try {
          localStorage.setItem(getK("anidex_profile_avatar"), src);
          localStorage.setItem("anidex_profile_avatar", src);
          if (window.AniDexProfile && typeof window.AniDexProfile.saveToDB === "function") { window.AniDexProfile.saveToDB().catch(() => {}); }
        } catch (e) { }
      };

      window.DEFAULT_PROFILE_AVATAR = DEFAULT_PROFILE_AVATAR;
      window.paintProfileAvatar = paintProfileAvatar;

      const saved = (() => { try { return localStorage.getItem(getK("anidex_profile_avatar")) || localStorage.getItem("anidex_profile_avatar"); } catch (e) { return null; } })();
      paintProfileAvatar(saved || DEFAULT_PROFILE_AVATAR);

      grid.addEventListener("click", (e) => {
        const btn = e.target.closest("[data-avatar-src]");
        if (!btn) return;
        const src = btn.getAttribute("data-avatar-src");
        if (src) {
          if (returnToProfile) {
            if (previewImg) previewImg.src = src;
            window.__profilePendingAvatar = src;
          } else {
            syncAvatars(src);
          }
        }
        closeModal();
      });

      if (editBtn) editBtn.addEventListener("click", openModal);
      if (openBtn) openBtn.addEventListener("click", openModal);
      modal.querySelectorAll("[data-avatar-close]").forEach((el) => el.addEventListener("click", closeModal));
    })();

/* MVC: Session & Access (Model / View / Controller) */
    (function () {
      // MODEL: session flags from localStorage
      const modal = document.getElementById("lists-modal");
      const title = document.getElementById("lists-modal-title");
      const myWrap = document.getElementById("my-list-modal");
      const favWrap = document.getElementById("favorites-modal");
      const countMy = document.getElementById("lists-count-mylist");
      const countFav = document.getElementById("lists-count-favorites");
      if (!modal || !title || !myWrap || !favWrap) return;

      const getRegisterUrl = () => (window.AniDexLayout && typeof window.AniDexLayout.buildAppUrl === "function")
        ? window.AniDexLayout.buildAppUrl("registro")
        : "registro";

      const isLoggedIn = () => (window.AniDexLayout && typeof window.AniDexLayout.isLoggedIn === "function")
        ? window.AniDexLayout.isLoggedIn()
        : (localStorage.getItem("nekora_logged_in") === "true");

      const openModal = (which) => {
        modal.classList.remove("theme-mylist", "theme-favorites");
        modal.classList.add(which === "my-list" ? "theme-mylist" : "theme-favorites");
        if (which === "my-list") {
          title.textContent = "Mi Lista";
          myWrap.classList.remove("hidden");
          favWrap.classList.add("hidden");
          if (countMy) countMy.classList.remove("hidden");
          if (countFav) countFav.classList.add("hidden");
        } else {
          title.textContent = "Favoritos";
          favWrap.classList.remove("hidden");
          myWrap.classList.add("hidden");
          if (countFav) countFav.classList.remove("hidden");
          if (countMy) countMy.classList.add("hidden");
        }
        modal.classList.remove("hidden");
        modal.classList.add("flex");
        document.body.style.overflow = "hidden";
      };
      const closeModal = () => {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
        document.body.style.overflow = "";
      };

      document.querySelectorAll("[data-open-list]").forEach((btn) => {
        btn.addEventListener("click", (event) => {
          event.preventDefault();
          event.stopPropagation();
          if (!isLoggedIn()) {
            window.location.href = getRegisterUrl();
            return;
          }
          if (btn.disabled) return;
          openModal(btn.getAttribute("data-open-list"));
        });
      });
      document.addEventListener("click", (event) => {
        const trigger = event.target.closest("[data-open-list]");
        if (!trigger) return;
        event.preventDefault();
        event.stopPropagation();
        if (!isLoggedIn()) {
          window.location.href = getRegisterUrl();
          return;
        }
        if (trigger.disabled) return;
        openModal(trigger.getAttribute("data-open-list"));
      });
      modal.querySelectorAll("[data-lists-close]").forEach((el) => el.addEventListener("click", closeModal));
    })();

(function () {
      const modal = document.getElementById("status-modal");
      const title = document.getElementById("status-modal-title");
      const completed = document.getElementById("completed-section");
      const pending = document.getElementById("pending-section");
      const countCompleted = document.getElementById("status-count-completed");
      const countPending = document.getElementById("status-count-pending");
      if (!modal || !title || !completed || !pending) return;

      const getRegisterUrl = () => (window.AniDexLayout && typeof window.AniDexLayout.buildAppUrl === "function")
        ? window.AniDexLayout.buildAppUrl("registro")
        : "registro";

      const isLoggedIn = () => (window.AniDexLayout && typeof window.AniDexLayout.isLoggedIn === "function")
        ? window.AniDexLayout.isLoggedIn()
        : (localStorage.getItem("nekora_logged_in") === "true");

      const openModal = (which) => {
        modal.classList.remove("theme-completed", "theme-pending");
        modal.classList.add(which === "completed" ? "theme-completed" : "theme-pending");
        if (which === "completed") {
          title.textContent = "Completados";
          completed.classList.remove("hidden");
          pending.classList.add("hidden");
          if (countCompleted) countCompleted.classList.remove("hidden");
          if (countPending) countPending.classList.add("hidden");
        } else {
          title.textContent = "Pendientes";
          pending.classList.remove("hidden");
          completed.classList.add("hidden");
          if (countPending) countPending.classList.remove("hidden");
          if (countCompleted) countCompleted.classList.add("hidden");
        }
        modal.classList.remove("hidden");
        document.body.style.overflow = "hidden";
      };
      const closeModal = () => {
        modal.classList.add("hidden");
        document.body.style.overflow = "";
      };

      document.querySelectorAll("[data-open-status]").forEach((btn) => {
        btn.addEventListener("click", (event) => {
          event.preventDefault();
          event.stopPropagation();
          if (!isLoggedIn()) {
            window.location.href = getRegisterUrl();
            return;
          }
          if (btn.disabled) return;
          openModal(btn.getAttribute("data-open-status"));
        });
      });
      modal.querySelectorAll("[data-status-close]").forEach((el) => el.addEventListener("click", closeModal));
    })();

(function () {
      const bindCarousel = (rowId) => {
        const row = document.getElementById(rowId);
        const prev = document.querySelector(`[data-scroll-left="${rowId}"]`);
        const next = document.querySelector(`[data-scroll-right="${rowId}"]`);
        if (!row || !prev || !next) return null;

        const step = () => {
          const card = row.querySelector(":scope > *");
          if (!card) return Math.max(260, Math.round(row.clientWidth * 0.8));
          const styles = getComputedStyle(row);
          const gap = parseFloat(styles.columnGap || styles.gap || "0") || 0;
          const cardWidth = card.getBoundingClientRect().width;
          return Math.round(cardWidth + gap);
        };
        const setDisabled = (btn, disabled) => {
          btn.disabled = disabled;
          btn.classList.toggle("is-disabled", disabled);
          const zone = btn.closest(".user-carousel-nav-zone");
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
        row.addEventListener("scroll", () => requestAnimationFrame(updateState));
        window.addEventListener("resize", updateState);
        setTimeout(updateState, 0);
        return updateState;
      };

      const updates = [
        bindCarousel("my-list-grid"),
        bindCarousel("favorites-grid")
      ].filter(Boolean);

      window.refreshUserCarousels = () => {
        updates.forEach((fn) => fn());
      };
    })();

(function () {
      // DOM Elements
      const settingsBtn = document.getElementById("profile-settings-btn");
      const editBtn = document.getElementById("edit-profile-btn");
      const modal = document.getElementById("profile-modal");
      const closeEls = modal ? modal.querySelectorAll("[data-profile-close]") : [];

      const requestBtn = document.getElementById("request-title-btn");
      const requestModal = document.getElementById("request-title-modal");
      const requestForm = document.getElementById("request-title-form");
      const requestInput = document.getElementById("request-title-input");
      const requestType = document.getElementById("request-title-type");
      const requestToast = document.getElementById("request-toast");
      const requestCloseEls = requestModal ? requestModal.querySelectorAll("[data-request-close]") : [];

      const idDisplay = document.querySelector("[data-profile-id-display]");
      console.log("AniDex DEBUG: idDisplay =", idDisplay);
      const avatarImg = document.querySelector("img[alt='Foto de perfil']");
      const previewImg = document.querySelector("[data-profile-avatar-preview]");
      const paintProfileAvatar = (src) => {
        const safeSrc = src || DEFAULT_PROFILE_AVATAR;
        if (avatarImg) {
          avatarImg.src = safeSrc;
          avatarImg.onerror = () => {
            avatarImg.onerror = null;
            avatarImg.src = DEFAULT_PROFILE_AVATAR;
          };
        }
        if (previewImg) {
          previewImg.src = safeSrc;
          previewImg.onerror = () => {
            previewImg.onerror = null;
            previewImg.src = DEFAULT_PROFILE_AVATAR;
          };
        }
        document.querySelectorAll("img[data-profile-avatar]").forEach((img) => {
          img.src = safeSrc;
          img.onerror = () => {
            img.onerror = null;
            img.src = DEFAULT_PROFILE_AVATAR;
          };
        });
      };
      const nameInput = document.getElementById("profile-name-input");
      const descInput = document.getElementById("profile-desc-input");
      const saveBtn = document.getElementById("profile-save-btn");
      const descCount = document.getElementById("profile-desc-count");

      const nameEl = document.querySelector("h1.font-headline");
      const descEl = document.querySelector("p.text-on-surface-variant.font-medium");
      const cardEl = document.getElementById("profile-card");
      const memberEl = document.querySelector("[data-profile-member]");
      const lastEl = document.querySelector("[data-profile-last]");
      const hoursEl = document.querySelector("[data-profile-hours]");

      const colorOptions = document.getElementById("profile-color-options");
      const prefsWrap = document.getElementById("preferences-picker");
      const prefsDisplay = document.querySelector("[data-pref-display]");

      // Constants
      const PREF_KEY = "anidex_profile_prefs";
      const PREF_GROUPS = {
        "Idioma": ["Sub. espa\u00f1ol", "Doblaje espa\u00f1ol", "Japon\u00e9s", "Ingl\u00e9s"],
        "G\u00e9nero": ["Acci\u00f3n", "Fantas\u00eda", "Drama", "Comedia", "Romance", "Terror", "Sci-Fi"]
      };
      const PREF_DEFAULTS = { "Idioma": [], "G\u00e9nero": [] };
      const PREF_GROUP_STYLES = {
        "Idioma": { baseBorder: "border-blue-400/70", baseBg: "bg-blue-500/45", activeBorder: "border-blue-300/90", activeBg: "bg-blue-500/70", glow: "" },
        "G\u00e9nero": { baseBorder: "border-violet-400/70", baseBg: "bg-violet-500/45", activeBorder: "border-violet-300/90", activeBg: "bg-violet-500/70", glow: "" }
      };

      const getK = (k) => (window.AniDexProfile && window.AniDexProfile.getIsolatedKey) ? window.AniDexProfile.getIsolatedKey(k) : k;
      const colorClasses = [
        "bg-gradient-to-br", "from-zinc-950", "from-zinc-900", "via-zinc-900", "to-zinc-800",
        "from-violet-900/50", "via-indigo-900/40", "to-slate-900/30",
        "from-fuchsia-900/50", "via-rose-900/40", "from-emerald-900/50", "via-teal-900/40",
        "from-sky-900/50", "via-cyan-900/40", "from-amber-900/50", "via-orange-900/40",
        "from-lime-900/50", "via-green-900/40", "from-red-900/50", "from-blue-900/50",
        "from-purple-900/50", "via-pink-900/40"
      ];

      let pendingPrefs = null;
      const getIsLogged = () => (window.AniDexLayout && typeof window.AniDexLayout.isLoggedIn === 'function')
        ? window.AniDexLayout.isLoggedIn()
        : (localStorage.getItem("nekora_logged_in") === "true");

      // Functions
      const prefStyleFor = (group, isActive) => {
        const palette = PREF_GROUP_STYLES[group] || {};
        const base = `${palette.baseBg || "bg-white/10"} ${palette.baseBorder || "border-white/20"} text-white opacity-85 hover:opacity-100 hover:ring-2 hover:ring-white/20`;
        const active = `${palette.activeBg || "bg-slate-500/70"} ${palette.activeBorder || "border-white/40"} text-white ring-2 ring-white/40 shadow-[0_0_12px_rgba(255,255,255,0.2)]`;
        return isActive ? active : base;
      };

      const normalizePrefs = (prefs) => {
        const out = {};
        Object.keys(PREF_GROUPS).forEach((k) => { out[k] = prefs && Array.isArray(prefs[k]) ? prefs[k] : []; });
        return out;
      };

      const loadPrefs = () => {
        try {
          const raw = localStorage.getItem(getK(PREF_KEY));
          if (raw) return normalizePrefs(JSON.parse(raw));
        } catch (e) { }
        return normalizePrefs(PREF_DEFAULTS);
      };

      const renderPrefDisplay = (prefs) => {
        if (!prefsDisplay) return;
        const chips = [];
        Object.keys(PREF_GROUPS).forEach((group) => {
          (prefs[group] || []).forEach((value) => {
            chips.push({ value, style: prefStyleFor(group, false) });
          });
        });
        prefsDisplay.classList.add("justify-center");
        if (chips.length > 0) {
          prefsDisplay.innerHTML = chips.map(c => `<span class="rounded-full border ${c.style} inline-flex items-center justify-center px-1 py-1 text-[10px] text-center h-7 truncate shrink-0 w-[110px]">${c.value}</span>`).join("");
        } else {
          prefsDisplay.innerHTML = '<span class="rounded-full border border-white/10 bg-white/10 inline-flex items-center justify-center px-1 py-1 text-[10px] text-center text-on-surface-variant h-7 truncate shrink-0 w-[110px]">Sin preferencias</span>';
        }
      };

      const renderPrefPicker = (prefs) => {
        if (!prefsWrap) return;
        let html = Object.entries(PREF_GROUPS).map(([group, values]) => `
        <div class="space-y-3">
          <h4 class="text-xs uppercase tracking-[0.2em] text-on-surface-variant font-bold">${group}</h4>
          <div class="flex flex-wrap gap-2">
            ${values.map(val => {
          const active = (prefs[group] || []).includes(val);
          return `<button type="button" data-pref-cat="${group}" data-pref-val="${val}" class="rounded-full border ${prefStyleFor(group, active)} inline-flex items-center justify-center px-1 py-1 text-[10px] text-center h-7 truncate shrink-0 w-[110px] transition-all duration-300 hover:scale-105 active:scale-95">${val}</button>`;
        }).join("")}
          </div>
        </div>
      `).join("");

        html += `
        <div class="pt-2 flex justify-center">
            <button id="clear-prefs-btn" type="button" class="text-[10px] uppercase tracking-widest text-on-surface-variant hover:text-rose-400 font-bold flex items-center gap-1.5 transition-colors">
                <span class="material-symbols-outlined text-sm">filter_alt_off</span>
                Limpiar todo
            </button>
        </div>
      `;
        prefsWrap.innerHTML = html;

        const clearBtn = document.getElementById("clear-prefs-btn");
        if (clearBtn) {
          clearBtn.addEventListener("click", () => {
            pendingPrefs = normalizePrefs({});
            renderPrefPicker(pendingPrefs);
          });
        }
      };

      const loadProfile = (skipRestore = false) => {
        try {
          const userSuffix = (window.AniDexProfile && window.AniDexProfile.getOrCreateUserSuffix) ? AniDexProfile.getOrCreateUserSuffix() : (localStorage.getItem("anidex_user_suffix") || "22");
          const defaultName = `NekoraUser_${userSuffix}`;
          const publicUserId = localStorage.getItem("anidex_public_user_id");
          const fallbackUserId = localStorage.getItem("anidex_user_id") || "";
          const userId = publicUserId || (fallbackUserId.startsWith("NK-") ? fallbackUserId : "NK-000000");

          const savedName = localStorage.getItem("anidex_profile_name");
          const savedDesc = localStorage.getItem(getK("anidex_profile_desc"));
          const savedColor = localStorage.getItem(getK("anidex_profile_color"));
          const savedMember = localStorage.getItem(getK("anidex_profile_member_since"));
          const savedHours = localStorage.getItem(getK("anidex_profile_hours"));

          if (idDisplay) idDisplay.textContent = `ID: ${userId}`;
          if (nameEl) nameEl.textContent = (savedName || defaultName).trim();
          if (descEl) descEl.textContent = (savedDesc || "Explorador de animes en NekoraList").trim();

          if (memberEl) {
            const yearNow = new Date().getFullYear();
            memberEl.textContent = savedMember || String(yearNow);
          }

          if (hoursEl) {
            const hoursVal = (savedHours && savedHours.trim() ? savedHours.trim() : "0");
            hoursEl.textContent = (window.AniDexProfile && window.AniDexProfile.formatHours)
              ? window.AniDexProfile.formatHours(hoursVal)
              : `${hoursVal} h`;
          }

          if (savedColor && cardEl) {
            colorClasses.forEach((c) => cardEl.classList.remove(c));
            savedColor.split(" ").forEach((c) => cardEl.classList.add(c));
          }

          const savedAvatar = localStorage.getItem(getK("anidex_profile_avatar")) || localStorage.getItem("anidex_profile_avatar");
          paintProfileAvatar(savedAvatar || window.DEFAULT_PROFILE_AVATAR || "https://upload.wikimedia.org/wikipedia/en/b/bd/Doraemon_character.png");

          const prefs = loadPrefs();
          pendingPrefs = prefs;
          renderPrefPicker(prefs);
          renderPrefDisplay(prefs);

          const logged = getIsLogged();
          if (logged && !skipRestore && !window.__restoreAttempted) {
            window.__restoreAttempted = true;
            if (window.AniDexProfile && typeof window.AniDexProfile.restoreFromDB === 'function') {
              window.AniDexProfile.restoreFromDB();
            }
          }
        } catch (e) { console.error("loadProfile error:", e); }
      };

      const openModal = () => {
        if (!modal) return;
        if (nameInput && nameEl) nameInput.value = nameEl.textContent.trim();
        if (descInput && descEl) descInput.value = descEl.textContent.trim();
        if (previewImg && avatarImg) previewImg.src = avatarImg.src;
        if (descInput && descCount) descCount.textContent = `${descInput.value.length}/200`;
        pendingPrefs = loadPrefs();
        renderPrefPicker(pendingPrefs);
        modal.classList.remove("hidden");
        modal.classList.add("flex");
        document.body.style.overflow = "hidden";
      };

      const closeModal = () => {
        if (modal) {
          modal.classList.add("hidden");
          modal.classList.remove("flex");
        }
        document.body.style.overflow = "";
      };

      const openRequestModal = () => {
        if (requestModal) {
          requestModal.classList.remove("hidden");
          requestModal.classList.add("flex");
          document.body.style.overflow = "hidden";
        }
      };

      const closeRequestModal = () => {
        if (requestModal) {
          requestModal.classList.add("hidden");
          requestModal.classList.remove("flex");
          document.body.style.overflow = "";
        }
      };

      const saveProfileToDB = async () => {
        if (window.__anidex_restoring) return;
        if (window.AniDexProfile && typeof window.AniDexProfile.saveToDB === "function") {
          await window.AniDexProfile.saveToDB();
        }
      };

      // Listeners
      if (editBtn) editBtn.addEventListener("click", (e) => { e.preventDefault(); openModal(); });
      if (settingsBtn) settingsBtn.addEventListener("click", (e) => { e.preventDefault(); openModal(); });
      closeEls.forEach(el => el.addEventListener("click", (e) => { e.preventDefault(); closeModal(); }));

      document.addEventListener("click", (e) => {
        const openTrigger = e.target.closest("#profile-settings-btn, #edit-profile-btn");
        if (openTrigger) {
          e.preventDefault();
          openModal();
          return;
        }
        const closeTrigger = e.target.closest("[data-profile-close]");
        if (closeTrigger) {
          e.preventDefault();
          closeModal();
        }
      });
      if (requestBtn) requestBtn.addEventListener("click", openRequestModal);
      requestCloseEls.forEach(el => el.addEventListener("click", closeRequestModal));

      if (descInput && descCount) {
        descInput.addEventListener("input", () => {
          if (descInput.value.length > 200) descInput.value = descInput.value.slice(0, 200);
          descCount.textContent = `${descInput.value.length}/200`;
        });
      }

      if (saveBtn) {
        saveBtn.addEventListener("click", async () => {
          try {
            const newName = (nameInput?.value || "").trim() || (nameEl?.textContent || "").trim();
            const newDesc = (descInput?.value || "").trim() || (descEl?.textContent || "Explorador de animes en NekoraList").trim();

            if (nameEl) nameEl.textContent = newName;
            if (descEl) descEl.textContent = newDesc;

            localStorage.setItem("anidex_profile_name", newName);
            localStorage.setItem(getK("anidex_profile_desc"), newDesc);

            if (pendingPrefs) {
              localStorage.setItem(getK(PREF_KEY), JSON.stringify(pendingPrefs));
              renderPrefDisplay(pendingPrefs);
            }

            // Aplicar avatar pendiente si existe
            if (window.__profilePendingAvatar) {
              const src = window.__profilePendingAvatar;
              localStorage.setItem(getK("anidex_profile_avatar"), src);
              localStorage.setItem("anidex_profile_avatar", src);
              paintProfileAvatar(src);
              window.__profilePendingAvatar = null;
            }

            // Sincronizar en segundo plano sin bloquear el cierre del modal
            saveProfileToDB().catch(err => console.error("Sync error:", err));

            closeModal();
          } catch (err) {
            console.error("Save Error:", err);
            closeModal(); // Asegurar que el modal se cierre ante cualquier error
          }
        });
      }

      if (prefsWrap) {
        prefsWrap.addEventListener("click", (e) => {
          const btn = e.target.closest("[data-pref-cat]");
          if (!btn) return;
          const cat = btn.getAttribute("data-pref-cat");
          const val = btn.getAttribute("data-pref-val");
          const prefs = pendingPrefs || loadPrefs();
          const list = new Set(prefs[cat] || []);
          if (list.has(val)) list.delete(val); else list.add(val);
          prefs[cat] = Array.from(list);
          pendingPrefs = prefs;
          renderPrefPicker(prefs);
        });
      }

      if (colorOptions && cardEl) {
        colorOptions.addEventListener("click", (e) => {
          const btn = e.target.closest("[data-color-class]");
          if (!btn) return;
          const colorClass = btn.getAttribute("data-color-class");
          colorClasses.forEach(c => cardEl.classList.remove(c));
          colorClass.split(" ").forEach(c => cardEl.classList.add(c));
          localStorage.setItem(getK("anidex_profile_color"), colorClass);
          saveProfileToDB();
        });
      }

      if (requestForm) {
        requestForm.addEventListener("submit", async (e) => {
          e.preventDefault();
          const title = (requestInput?.value || "").trim();
          const tipo = (requestType?.value || "Anime").trim();
          if (!title) return;
          try {
            const res = await fetch("api/requests.php?action=create", {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({ t\u00edtulo: title, tipo })
            });
            const json = await res.json();
            if (!json.success) throw new Error(json.error || "No se pudo enviar la solicitud");
            closeRequestModal();
            if (requestToast) {
              requestToast.classList.remove("hidden");
              requestToast.classList.add("flex");
              setTimeout(() => { requestToast.classList.add("hidden"); }, 2000);
            }
            if (requestInput) requestInput.value = "";
          } catch (err) {
            console.error("Request error:", err);
          }
        });
      }

      // Initial load & Export refresh (Registry pattern)
      if (window.AniDexLayout && typeof window.AniDexLayout.onReady === "function") {
        window.AniDexLayout.onReady(() => {
          console.log("[NekoraProfile] Autenticaci\u00f3n lista. Cargando datos con ID:", localStorage.getItem("anidex_user_id"));
          loadProfile();
        });
        window.AniDexLayout.registerRefresh(() => {
          console.log("[NekoraProfile] Se\u00f1al de refresco recibida.");
          loadProfile(true);
        });
      } else {
        loadProfile();
      }
    })();

(function () {
      const createBtn = document.getElementById("auth-create-btn");
      const loginBtn = document.getElementById("auth-login-btn");
      const logoutBtn = document.getElementById("profile-logout-btn");
      const profileActions = document.getElementById("profile-actions");
      const profileLists = document.getElementById("profile-lists");
      const requestTitleBtn = document.getElementById("request-title-btn");
      const continueSection = document.getElementById("continue-section");
      const logoutModal = document.getElementById("logout-confirm");
      const logoutClose = logoutModal ? logoutModal.querySelectorAll("[data-logout-close]") : [];
      const logoutCancel = logoutModal ? logoutModal.querySelector("[data-logout-cancel]") : null;
      const logoutConfirm = logoutModal ? logoutModal.querySelector("[data-logout-confirm]") : null;
      const premiumAccessBtn = document.getElementById("premium-access-btn");
      const premiumCancelBtn = document.getElementById("premium-cancel-btn");
      const applyState = () => {
        const isLogged = window.AniDexLayout ? window.AniDexLayout.isLoggedIn() : (localStorage.getItem("nekora_logged_in") === "true");
        if (createBtn) createBtn.classList.toggle("hidden", isLogged);
        if (loginBtn) loginBtn.classList.toggle("hidden", isLogged);
        if (logoutBtn) logoutBtn.classList.toggle("hidden", !isLogged);
        const isPremium = window.AniDexLayout ? window.AniDexLayout.isPremium() : (localStorage.getItem("nekora_premium") === "true");
        const isAdmin = window.AniDexLayout ? window.AniDexLayout.isAdmin() : (localStorage.getItem("nekora_admin") === "true");
        
        if (premiumAccessBtn) premiumAccessBtn.classList.toggle("hidden", isPremium);
        if (premiumCancelBtn) premiumCancelBtn.classList.toggle("hidden", !isPremium);
        if (continueSection) continueSection.classList.toggle("hidden", !isPremium);
        if (requestTitleBtn) requestTitleBtn.classList.toggle("hidden", !isPremium || isAdmin);
        if (profileLists) {
          profileLists.style.marginTop = isPremium ? "-6rem" : "1.5rem";
        }
        if (profileActions) {
          const isAdmin = localStorage.getItem("nekora_admin") === "true";
          const alignLeft = (isLogged && !isPremium) || isAdmin;
          profileActions.classList.toggle("justify-start", alignLeft);
          profileActions.classList.toggle("pl-2", alignLeft);
          profileActions.classList.toggle("justify-center", !alignLeft);
          profileActions.classList.toggle("pl-0", !alignLeft);
        }
        const badge = document.querySelector("[data-identity-badge]");
        const badgeIcon = document.querySelector("[data-identity-icon]");
        const badgeText = document.querySelector("[data-identity-text]");
        if (badge && badgeIcon && badgeText) {
          if (isPremium) {
            if (isAdmin) {
              badge.className = "badge-shimmer text-white px-3 py-1 rounded-full font-label text-[10px] font-bold tracking-widest uppercase inline-flex items-center gap-1.5 shadow-[0_0_16px_rgba(56,189,248,0.9)] border border-sky-300/90";
              badgeIcon.textContent = "crown";
              badgeIcon.className = "material-symbols-outlined text-[14px] leading-none text-white";
              badgeText.textContent = "Modo super blue";
            } else {
              badge.className = "badge-shimmer-gold text-white px-3 py-1 rounded-full font-label text-[10px] font-bold tracking-widest uppercase inline-flex items-center gap-1.5 shadow-[0_0_12px_rgba(251,191,36,0.30)] border border-amber-300/50";
              badgeIcon.textContent = "bolt";
              badgeIcon.className = "material-symbols-outlined text-[14px] leading-none text-white";
              badgeText.textContent = "Modo limit break";
            }
          } else {

            badge.className = "bg-emerald-500/15 text-emerald-300 px-3 py-1 rounded-full font-label text-[10px] font-bold tracking-widest uppercase inline-flex items-center gap-1.5";
            badgeIcon.textContent = "nightlight";
            badgeIcon.className = "material-symbols-outlined text-[14px] leading-none";
            badgeText.textContent = "Modo Dream";
          }
        }
      };
      const doLogout = async () => {
        // CRITICO: guardar en la BD ANTES de borrar localStorage.
        // Si no se hace esto, los datos agregados desde el \u00faltimo auto-sync
        // (hasta 60 seg) se pierden permanentemente al limpiar el storage.
        try {
          if (window.AniDexProfile && typeof window.AniDexProfile.saveToDB === "function") {
            await window.AniDexProfile.saveToDB();
          }
        } catch (e) { console.error("Pre-logout save failed", e); }

        try {
          await fetch((window.AniDexLayout?.buildAppUrl ? window.AniDexLayout.buildAppUrl("api/auth?action=logout") : "api/auth?action=logout"), { method: "POST", credentials: "same-origin" });
        } catch (e) { console.error("Logout API failed", e); }

        Object.keys(localStorage).forEach((key) => {
          if (key.startsWith("anidex_") || key.startsWith("nekora_")) {
            localStorage.removeItem(key);
          }
        });
        window.location.href = (window.AniDexLayout?.buildAppUrl ? window.AniDexLayout.buildAppUrl("index") : "index");
      };
      const openLogoutModal = () => {
        if (!logoutModal) return;
        logoutModal.classList.remove("hidden");
        logoutModal.classList.add("flex");
        document.body.style.overflow = "hidden";
      };
      const closeLogoutModal = () => {
        if (!logoutModal) return;
        logoutModal.classList.add("hidden");
        logoutModal.classList.remove("flex");
        document.body.style.overflow = "";
      };
      if (logoutBtn) {
        logoutBtn.addEventListener("click", () => {
          openLogoutModal();
        });
      }
      logoutClose.forEach((el) => el.addEventListener("click", closeLogoutModal));
      if (logoutCancel) logoutCancel.addEventListener("click", closeLogoutModal);
      if (logoutConfirm) logoutConfirm.addEventListener("click", async () => {
        closeLogoutModal();
        await doLogout();
      });
      if (premiumCancelBtn) {
        premiumCancelBtn.addEventListener("click", () => {
          localStorage.removeItem("nekora_premium");
          applyState();
        });
      }
      if (window.AniDexLayout && typeof window.AniDexLayout.onReady === "function") {
        window.AniDexLayout.onReady(applyState);
      } else {
        applyState();
      }
    })();

/* MVC: Continue Watching (Model / View / Controller) */
    window.renderContinueWatching = async function () {
      // MODEL: continue items stored in localStorage
      const grid = document.getElementById("continue-grid");
      const empty = document.getElementById("continue-empty");
      if (!grid || !empty) return;
      const getK = (k) => (window.AniDexProfile && window.AniDexProfile.getIsolatedKey) ? window.AniDexProfile.getIsolatedKey(k) : k;
      let items = [];
      try {
        items = JSON.parse(localStorage.getItem(getK("anidex_continue_v1")) || "[]");
      } catch {
        items = [];
      }
      const norm = (v) =>
        (v || "")
          .toLowerCase()
          .normalize("NFD")
          .replace(/[\u0300-\u036f]/g, "")
          .replace(/[^\w\s]/g, " ")
          .replace(/\s+/g, " ")
          .trim();
      let titleMap = {};
      try {
        titleMap = JSON.parse(localStorage.getItem("anidex_title_id_map_v1") || "{}");
      } catch {
        titleMap = {};
      }
      items = (items || [])
        .filter((it) => it && (it.title || it.query))
        .sort((a, b) => (b.lastSeen || 0) - (a.lastSeen || 0))
        .slice(0, 4);
      const resolveMissingIds = async () => {
        const needs = items.filter((it) => !it.malId);
        if (!needs.length) return;
        for (const item of needs) {
          const title = item.title || item.query || "";
          if (!title) continue;
          try {
            const res = await fetch(
              `https://api.jikan.moe/v4/anime?q=${encodeURIComponent(title)}&limit=1&order_by=popularity&sort=asc`
            );
            if (!res.ok) continue;
            const data = await res.json();
            const found = data?.data?.[0];
            if (found?.mal_id) {
              item.malId = found.mal_id;
              titleMap[norm(title)] = found.mal_id;
              item.detailUrl = `detail?mal_id=${encodeURIComponent(found.mal_id)}`;
            }
          } catch { }
        }
        try {
          localStorage.setItem("anidex_title_id_map_v1", JSON.stringify(titleMap));
        } catch { }
      };
      if (!items.length) {
        empty.classList.remove("hidden");
        grid.classList.add("hidden");
        return;
      }
      await resolveMissingIds();
      items.forEach((item) => {
        const title = item.title || item.query || "";
        const knownId = item.malId || item.sourceId || null;
        if (knownId && title) {
          titleMap[norm(title)] = knownId;
        }
        if (!item.detailUrl) {
          if (knownId) {
            item.detailUrl = `detail?mal_id=${encodeURIComponent(knownId)}`;
          } else if (title) {
            item.detailUrl = `detail?q=${encodeURIComponent(title)}`;
          }
        }
      });
      // Solo guardamos si hay items o si queremos resetear explcitamente. 
      // Para evitar sobreescribir datos sincronizados con una lista vaca local:
      if (items.length > 0) {
        try {
          localStorage.setItem(getK("anidex_continue_v1"), JSON.stringify(items));
        } catch { }
      }

      if (items.length === 0) {
        // Intentar recuperar de localStorage si el render local fall pero hay datos sincronizados
        const synced = JSON.parse(localStorage.getItem(getK("anidex_continue_v1")) || "[]");
        if (synced.length > 0) {
          items = synced;
        }
      }

      if (items.length === 0) {
        empty.classList.remove("hidden");
        grid.classList.add("hidden");
        return;
      }

      empty.classList.add("hidden");
      grid.classList.remove("hidden");
      grid.innerHTML = items.map((item) => {
        const title = item.title || item.query || "Anime";
        const fallbackId = item.malId || item.sourceId || titleMap[norm(title)] || titleMap[norm(item.query)] || null;
        if (!item.malId && fallbackId) {
          item.malId = fallbackId;
        }
        const cover = item.cover || "https://via.placeholder.com/160x220?text=Anime";
        const href = item.detailUrl || (fallbackId
          ? `detail?mal_id=${encodeURIComponent(fallbackId)}`
          : `detail?q=${encodeURIComponent(title)}`);
        return `
        <a href="${href}" data-title="${title.replace(/"/g, "&quot;")}" data-mal-id="${fallbackId ? String(fallbackId).replace(/"/g, "&quot;") : ''}" class="group relative w-full rounded-2xl border border-white/10 bg-surface-container-low/70 p-3 shadow-[0_12px_26px_rgba(0,0,0,0.3)] transition-all hover:-translate-y-1 z-0 hover:z-20 overflow-visible">
          <span class="pointer-events-none absolute -inset-2 rounded-2xl border border-violet-400/0 opacity-0 transition-all duration-200 group-hover:opacity-100 group-hover:border-violet-400/60"></span>
          <div class="relative aspect-square rounded-none overflow-hidden bg-surface-container-high">
            <img src="${cover}" alt="${title}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.06] rounded-none" />
          </div>
          <div class="mt-3">
            <p class="text-[11px] text-on-surface-variant truncate">${title}</p>
          </div>
        </a>
      `;
      }).join("");

      grid.addEventListener("click", (e) => {
        const link = e.target.closest("a[data-title]");
        if (!link) return;
        e.preventDefault();
        const malId = (link.getAttribute("data-mal-id") || "").trim();
        if (malId) {
          window.location.href = `detail?mal_id=${encodeURIComponent(malId)}`;
          return;
        }
        window.location.href = link.getAttribute("href");
      });

      if (window.AniDexLayout && typeof window.AniDexLayout.registerRefresh === "function") {
        window.AniDexLayout.registerRefresh(window.renderContinueWatching);
      }
    };
    
    // Initial call synchronized with Layout
    if (window.AniDexLayout && typeof window.AniDexLayout.onReady === "function") {
      window.AniDexLayout.onReady(() => {
        console.log("[NekoraProfile] Iniciando render de Continuar viendo...");
        window.renderContinueWatching();
      });
    } else {
      window.renderContinueWatching();
    }

