(() => {
  const defaultAvatar = "https://upload.wikimedia.org/wikipedia/en/b/bd/Doraemon_character.png";

  const buildAppUrl = (path = "") => {
    if (window.AniDexLayout?.buildAppUrl) return window.AniDexLayout.buildAppUrl(path);
    if (window.AniDexShared?.buildAppUrl) return window.AniDexShared.buildAppUrl(path);
    return String(path || "");
  };

  const getKey = (baseKey) => window.AniDexProfile?.getIsolatedKey ? window.AniDexProfile.getIsolatedKey(baseKey) : baseKey;

  const readJson = (baseKey) => {
    try { return JSON.parse(localStorage.getItem(getKey(baseKey)) || "[]"); }
    catch { return []; }
  };

  const readValue = (baseKey, fallback = "") => {
    try { return localStorage.getItem(getKey(baseKey)) || fallback; }
    catch { return fallback; }
  };

  const getDefaultName = () => {
    const suffix = (window.AniDexProfile?.getOrCreateUserSuffix && window.AniDexProfile.getOrCreateUserSuffix()) || localStorage.getItem("anidex_user_suffix") || "22";
    return `NekoraUser_${suffix}`;
  };

  const formatHours = (value) => {
    const total = parseFloat(value || "0") || 0;
    const totalMinutes = Math.max(0, Math.round(total * 60));
    const h = Math.floor(totalMinutes / 60);
    const m = totalMinutes % 60;
    if (h <= 0) return `${m} min`;
    if (m === 0) return `${h} h`;
    return `${h} h ${String(m).padStart(2, "0")} min`;
  };

  const buildDetailHref = (item) => {
    const malId = String(item?.mal_id || item?.malId || item?.sourceId || "").trim();
    const title = String(item?.title || item?.query || "Anime").trim();
    if (window.AniDexShared?.buildDetailUrl && malId) return window.AniDexShared.buildDetailUrl(malId, title);
    if (malId) return `detail?mal_id=${encodeURIComponent(malId)}`;
    return `detail?q=${encodeURIComponent(title)}`;
  };

  const renderProfile = () => {
    const nameEl = document.querySelector("h1.font-headline");
    const descEl = document.querySelector("p.text-on-surface-variant.font-medium");
    const idEl = document.querySelector("[data-profile-id-display]");
    const memberEl = document.querySelector("[data-profile-member]");
    const hoursEl = document.querySelector("[data-profile-hours]");

    const savedName = localStorage.getItem("anidex_profile_name") || localStorage.getItem("nekora_user") || getDefaultName();
    const savedDesc = readValue("anidex_profile_desc", "Explorador de animes en NekoraList");
    const publicUserId = localStorage.getItem("anidex_public_user_id");
    const fallbackUserId = localStorage.getItem("anidex_user_id") || "NK-000000";
    const userId = publicUserId || fallbackUserId;
    const memberSince = readValue("anidex_profile_member_since", String(new Date().getFullYear()));
    const hours = readValue("anidex_profile_hours", "0");
    const avatar = readValue("anidex_profile_avatar", defaultAvatar) || defaultAvatar;

    if (nameEl) nameEl.textContent = savedName;
    if (descEl) descEl.textContent = savedDesc;
    if (idEl) idEl.textContent = `ID: ${userId}`;
    if (memberEl) memberEl.textContent = memberSince;
    if (hoursEl) hoursEl.textContent = formatHours(hours);

    document.querySelectorAll("img[data-profile-avatar]").forEach((img) => {
      img.src = avatar;
      img.onerror = () => { img.onerror = null; img.src = defaultAvatar; };
    });
  };

  const setCount = (selector, value) => {
    document.querySelectorAll(selector).forEach((el) => { el.textContent = String(value); });
  };

  const emptyCard = (message) => `
    <div class="col-span-full rounded-2xl border border-white/10 bg-white/5 px-4 py-8 text-center text-sm text-on-surface-variant">
      ${message}
    </div>
  `;

  const renderCard = (item) => {
    const title = String(item?.title || item?.query || "Anime");
    const image = String(item?.image || item?.cover || defaultAvatar);
    const href = buildDetailHref(item);
    return `
      <a href="${href}" class="group relative w-[10.5rem] sm:w-[11rem] shrink-0 rounded-2xl border border-white/10 bg-surface-container-low/70 p-2.5 shadow-[0_12px_26px_rgba(0,0,0,0.3)] transition-all hover:-translate-y-1 overflow-hidden">
        <div class="aspect-[3/4] overflow-hidden rounded-md bg-surface-container-high">
          <img src="${image}" alt="${title.replace(/"/g, "&quot;")}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.04]">
        </div>
        <p class="mt-2 truncate text-xs font-semibold text-on-surface">${title}</p>
      </a>
    `;
  };

  const renderCollection = ({ baseKey, gridId, fullGridId, countSelector, emptyMessage, limit = 8 }) => {
    const grid = document.getElementById(gridId);
    const fullGrid = document.getElementById(fullGridId);
    const items = readJson(baseKey).filter(Boolean);
    setCount(countSelector, items.length);
    document.querySelectorAll(`[data-open-list="${baseKey === "anidex_favorites_v1" ? "favorites" : "my-list"}"]`).forEach((btn) => {
      const disabled = items.length === 0;
      btn.disabled = disabled;
      btn.classList.toggle("is-disabled", disabled);
      btn.setAttribute("aria-disabled", disabled ? "true" : "false");
    });
    if (grid) grid.innerHTML = items.length ? items.slice(0, limit).map(renderCard).join("") : emptyCard(emptyMessage);
    if (fullGrid) fullGrid.innerHTML = items.length ? items.map(renderCard).join("") : emptyCard(emptyMessage);
  };

  const renderStatuses = () => {
    const items = readJson("anidex_status_v1").filter(Boolean);
    const completed = items.filter((item) => item.status === "completed");
    const pending = items.filter((item) => item.status === "pending");
    setCount("[data-count-completed]", completed.length);
    setCount("[data-count-pending]", pending.length);
    const completedGrid = document.getElementById("completed-grid");
    const pendingGrid = document.getElementById("pending-grid");
    if (completedGrid) completedGrid.innerHTML = completed.length ? completed.map(renderCard).join("") : emptyCard("Aún no tienes títulos completados.");
    if (pendingGrid) pendingGrid.innerHTML = pending.length ? pending.map(renderCard).join("") : emptyCard("Aún no tienes títulos pendientes.");
  };

  const renderContinueEmpty = () => {
    const isPremium = window.AniDexLayout?.isPremium
      ? window.AniDexLayout.isPremium()
      : localStorage.getItem("nekora_premium") === "true";
    const section = document.getElementById("continue-section");
    const grid = document.getElementById("continue-grid");
    const empty = document.getElementById("continue-empty");
    if (section) section.classList.toggle("hidden", !isPremium);
    if (!isPremium || !grid || !empty) return;
    const items = readJson("anidex_continue_v1").filter(Boolean);
    if (items.length === 0) {
      empty.classList.remove("hidden");
      grid.classList.add("hidden");
    }
  };

  const bindLogout = () => {
    const confirmBtn = document.querySelector("[data-logout-confirm]");
    if (!confirmBtn || confirmBtn.dataset.rescueBound === "1") return;
    confirmBtn.dataset.rescueBound = "1";
    confirmBtn.addEventListener("click", async (event) => {
      event.preventDefault();
      const modal = document.getElementById("logout-confirm");
      if (modal) {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
      }
      try { await fetch(buildAppUrl("api/auth?action=logout"), { method: "POST", credentials: "same-origin" }); } catch {}
      Object.keys(localStorage).forEach((key) => {
        if (key.startsWith("anidex_") || key.startsWith("nekora_")) localStorage.removeItem(key);
      });
      window.location.href = buildAppUrl("index");
    });
  };

  const bindModalClose = (modalId, closeSelector) => {
    const modal = document.getElementById(modalId);
    if (!modal || modal.dataset.rescueCloseBound === "1") return;
    modal.dataset.rescueCloseBound = "1";

    const close = () => {
      modal.classList.add("hidden");
      modal.classList.remove("flex");
      document.body.style.overflow = "";
    };

    modal.querySelectorAll(closeSelector).forEach((el) => {
      el.addEventListener("click", (event) => {
        event.preventDefault();
        close();
      });
    });

    modal.addEventListener("click", (event) => {
      const shell = event.target.closest(".modal-shell");
      if (!shell) close();
    });
  };

  const bindListOpen = () => {
    const modal = document.getElementById("lists-modal");
    const title = document.getElementById("lists-modal-title");
    const myWrap = document.getElementById("my-list-modal");
    const favWrap = document.getElementById("favorites-modal");
    const countMy = document.getElementById("lists-count-mylist");
    const countFav = document.getElementById("lists-count-favorites");
    if (!modal || modal.dataset.rescueOpenBound === "1") return;
    modal.dataset.rescueOpenBound = "1";

    const isLoggedIn = () => window.AniDexLayout?.isLoggedIn
      ? window.AniDexLayout.isLoggedIn()
      : (localStorage.getItem("nekora_logged_in") === "true");

    const getRegisterUrl = () => window.AniDexLayout?.buildAppUrl
      ? window.AniDexLayout.buildAppUrl("registro")
      : "registro";

    document.querySelectorAll("[data-open-list]").forEach((btn) => {
      btn.addEventListener("click", (event) => {
        event.stopPropagation();
        if (!isLoggedIn()) {
          event.preventDefault();
          window.location.href = getRegisterUrl();
          return;
        }

        const which = btn.getAttribute("data-open-list");
        const total = which === "favorites" ? readJson("anidex_favorites_v1").filter(Boolean).length : readJson("anidex_my_list_v1").filter(Boolean).length;
        if (total === 0) {
          btn.disabled = true;
          btn.classList.add("is-disabled");
          btn.setAttribute("aria-disabled", "true");
          event.preventDefault();
          return;
        }

        event.preventDefault();
        modal.classList.remove("hidden", "theme-mylist", "theme-favorites");
        modal.classList.add("flex", which === "favorites" ? "theme-favorites" : "theme-mylist");
        if (title) title.textContent = which === "favorites" ? "Favoritos" : "Mi Lista";
        if (favWrap) favWrap.classList.toggle("hidden", which !== "favorites");
        if (myWrap) myWrap.classList.toggle("hidden", which !== "my-list");
        if (countFav) countFav.classList.toggle("hidden", which !== "favorites");
        if (countMy) countMy.classList.toggle("hidden", which !== "my-list");
        document.body.style.overflow = "hidden";
      });
    });
  };

  const init = () => {
    renderProfile();
    renderCollection({ baseKey: "anidex_favorites_v1", gridId: "favorites-grid", fullGridId: "favorites-grid-full", countSelector: "[data-count-favorites]", emptyMessage: "Aún no tienes títulos en favoritos." });
    renderCollection({ baseKey: "anidex_my_list_v1", gridId: "my-list-grid", fullGridId: "my-list-grid-full", countSelector: "[data-count-mylist]", emptyMessage: "Aún no tienes títulos en tu lista." });
    renderStatuses();
    renderContinueEmpty();
    bindLogout();
    bindListOpen();
    bindModalClose("lists-modal", "[data-lists-close]");
    bindModalClose("status-modal", "[data-status-close]");
  };

  if (window.AniDexLayout?.onReady) window.AniDexLayout.onReady(() => { setTimeout(init, 80); });
  else if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", () => setTimeout(init, 80));
  else setTimeout(init, 80);
})();

