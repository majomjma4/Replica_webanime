(() => {
  const BASE_MY_LIST   = "anidex_my_list_v1";
  const BASE_FAVORITES = "anidex_favorites_v1";
  const BASE_STATUS    = "anidex_status_v1";
  const norm = (v) => (v || "").toLowerCase().trim();
  const pathName = window.location.pathname.toLowerCase();
  const isDetailPage = pathName.includes("detail");
  const isIndexPage = pathName.endsWith("/index.php") || pathName.endsWith("index") || pathName === "/" || pathName === "";
  const getIsLoggedIn = () => {
    if (window.AniDexLayout && typeof window.AniDexLayout.isLoggedIn === "function") {
      return window.AniDexLayout.isLoggedIn();
    }
    return localStorage.getItem("nekora_logged_in") === "true";
  };
  
  const isLoggedIn = () => getIsLoggedIn();

  // â”€â”€ Clave aislada por usuario â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
  // Delega en AniDexProfile.getIsolatedKey para garantizar que la clave ES
  // exactamente la misma que usa layout.js al salvar/restaurar desde la BD.
  // Para invitados usa la clave base (sin sufijo) como fallback.
  const resolveKey = (baseKey) => {
    if (window.AniDexProfile && typeof window.AniDexProfile.getIsolatedKey === "function") {
      return window.AniDexProfile.getIsolatedKey(baseKey);
    }
    return baseKey;
  };

  // Getters de clave dinámica (se evalúan en tiempo de llamada, no al inicio)
  const KEY_MY_LIST   = () => resolveKey(BASE_MY_LIST);
  const KEY_FAVORITES = () => resolveKey(BASE_FAVORITES);
  const KEY_STATUS    = () => resolveKey(BASE_STATUS);

  const readKey = (kFn) => {
    try { return JSON.parse(localStorage.getItem(kFn()) || "[]"); }
    catch { return []; }
  };
  
  const logActivity = async (action, item, details = "") => {
    if (!isLoggedIn()) return;
    try {
      await fetch("api/activity.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ 
          action, 
          anime_id: item.mal_id || "", 
          details: `${details} (Anime: ${item.title})` 
        })
      });
    } catch (e) { console.error("Error logging activity:", e); }
  };
  const writeKey = (kFn, v) => localStorage.setItem(kFn(), JSON.stringify(v));
  const upsert = (keyFn, item) => {
    const id = norm(item.title);
    if (!id) return;
    const list = readKey(keyFn).filter((x) => norm(x.title) !== id);
    list.unshift({ ...item, status: item.status || "", savedAt: Date.now() });
    writeKey(keyFn, list.slice(0, 120));
    if (window.AniDexProfile && typeof window.AniDexProfile.saveToDB === "function") {
      window.AniDexProfile.saveToDB();
    }
  };
  const remove = (keyFn, title) => {
    writeKey(keyFn, readKey(keyFn).filter((x) => norm(x.title) !== norm(title)));
    if (window.AniDexProfile && typeof window.AniDexProfile.saveToDB === "function") {
      window.AniDexProfile.saveToDB();
    }
  };
  const exists = (keyFn, title) => readKey(keyFn).some((x) => norm(x.title) === norm(title));
  const readStatus  = () => readKey(KEY_STATUS);
  const writeStatus = (v) => writeKey(KEY_STATUS, v);


  const getStatusForTitle = (title) => {
    const id = norm(title);
    if (!id) return "";
    const hit = readStatus().find((x) => norm(x.title) === id);
    return hit?.status || "";
  };

  const upsertStatus = (item, status) => {
    const id = norm(item.title);
    if (!id) return;
    const list = readStatus().filter((x) => norm(x.title) !== id);
    if (status) {
      list.unshift({
        title: item.title,
        image: item.image || "",
        type: item.type || "Anime",
        mal_id: item.mal_id || "",
        status
      });
    }
    writeStatus(list);
    if (window.AniDexProfile && typeof window.AniDexProfile.saveToDB === "function") {
      window.AniDexProfile.saveToDB();
    }
  };

  const migrateStatusFromLists = () => {
    const statusList = readStatus();
    const hasStatus = (title) => statusList.some((x) => norm(x.title) === norm(title));
    const seed = (keyFn) => {
      readKey(keyFn).forEach((it) => {
        if (it?.status && !hasStatus(it.title)) {
          statusList.unshift({
            title: it.title,
            image: it.image || "",
            type: it.type || "Anime",
            mal_id: it.mal_id || "",
            status: it.status
          });
        }
      });
    };
    seed(KEY_MY_LIST);
    seed(KEY_FAVORITES);
    writeStatus(statusList);
  };

  const calcStatusCounts = () => {
    let completed = 0;
    let pending = 0;
    readStatus().forEach((it) => {
      if (it.status === "completed") completed += 1;
      else if (it.status === "pending") pending += 1;
    });
    return { completed, pending };
  };
  const updateListCounters = () => {
    const logged = isLoggedIn();
    const myCount = readKey(KEY_MY_LIST).length;
    const favCount = readKey(KEY_FAVORITES).length;
    document.querySelectorAll("[data-count-mylist]").forEach((el) => {
      el.textContent = String(myCount);
    });
    document.querySelectorAll("[data-count-favorites]").forEach((el) => {
      el.textContent = String(favCount);
    });
    document.querySelectorAll('[data-open-list="my-list"]').forEach((btn) => {
      const shouldDisable = logged && myCount === 0;
      btn.disabled = shouldDisable;
      btn.classList.toggle("is-disabled", shouldDisable);
    });
    document.querySelectorAll('[data-open-list="favorites"]').forEach((btn) => {
      const shouldDisable = logged && favCount === 0;
      btn.disabled = shouldDisable;
      btn.classList.toggle("is-disabled", shouldDisable);
    });
    return { myCount, favCount };
  };
  const closeIfOpen = (modal) => {
    if (!modal) return;
    modal.classList.add("hidden");
    document.body.style.overflow = "";
  };
  const closeEmptyModals = ({ myCount, favCount, completed, pending }) => {
    const listsModal = document.getElementById("lists-modal");
    if (listsModal && !listsModal.classList.contains("hidden")) {
      const myWrap = document.getElementById("my-list-modal");
      const favWrap = document.getElementById("favorites-modal");
      if (myWrap && !myWrap.classList.contains("hidden") && myCount === 0) {
        closeIfOpen(listsModal);
      }
      if (favWrap && !favWrap.classList.contains("hidden") && favCount === 0) {
        closeIfOpen(listsModal);
      }
    }
    const statusModal = document.getElementById("status-modal");
    if (statusModal && !statusModal.classList.contains("hidden")) {
      const completedWrap = document.getElementById("completed-section");
      const pendingWrap = document.getElementById("pending-section");
      if (completedWrap && !completedWrap.classList.contains("hidden") && completed === 0) {
        closeIfOpen(statusModal);
      }
      if (pendingWrap && !pendingWrap.classList.contains("hidden") && pending === 0) {
        closeIfOpen(statusModal);
      }
    }
  };
  const updateStatusCounters = () => {
    const logged = isLoggedIn();
    const { completed, pending } = calcStatusCounts();
    document.querySelectorAll("[data-count-completed]").forEach((el) => {
      el.textContent = String(completed);
    });
    document.querySelectorAll("[data-count-pending]").forEach((el) => {
      el.textContent = String(pending);
    });
    document.querySelectorAll('[data-open-status="completed"]').forEach((btn) => {
      const shouldDisable = logged && completed === 0;
      btn.disabled = shouldDisable;
      btn.classList.toggle("is-disabled", shouldDisable);
    });
    document.querySelectorAll('[data-open-status="pending"]').forEach((btn) => {
      const shouldDisable = logged && pending === 0;
      btn.disabled = shouldDisable;
      btn.classList.toggle("is-disabled", shouldDisable);
    });
    const { myCount, favCount } = updateListCounters();
    closeEmptyModals({ myCount, favCount, completed, pending });
  };

  const syncStatusEverywhere = (item, status) => {
    [KEY_MY_LIST, KEY_FAVORITES].forEach((keyFn) => {
      const listNow = readKey(keyFn).map((x) => {
        if (norm(x.title) !== norm(item.title)) return x;
        return { ...x, status };
      });
      writeKey(keyFn, listNow);
    });
    upsertStatus(item, status);
    updateStatusCounters();
  };

  const DETAIL_ACTIVE_CLASSES = {
    completed: ["bg-emerald-500/90", "text-white", "border-emerald-400/80"],
    pending: ["bg-amber-400/90", "text-black", "border-amber-300/80"]
  };
  const DETAIL_INACTIVE_CLASSES = ["bg-transparent", "text-on-surface-variant", "border-outline-variant"];

  const applyDetailStatusState = (btn, currentStatus) => {
    const kind = btn.getAttribute("data-detail-status");
    if (!kind) return;
    btn.classList.remove(
      ...DETAIL_ACTIVE_CLASSES.completed,
      ...DETAIL_ACTIVE_CLASSES.pending
    );
    btn.classList.add(...DETAIL_INACTIVE_CLASSES);
    const label = btn.querySelector("[data-detail-status-label]");
    if (currentStatus === kind) {
      btn.classList.remove(...DETAIL_INACTIVE_CLASSES);
      btn.classList.add(...DETAIL_ACTIVE_CLASSES[kind]);
      if (label) {
        label.textContent =
          kind === "completed" ? "Eliminar de Completados" : "Eliminar de Pendientes";
      }
    } else if (label) {
      label.textContent = kind === "completed" ? "Completado" : "Pendiente";
    }
  };

  const refreshDetailStatusButtons = () => {
    const buttons = document.querySelectorAll("[data-detail-status]");
    if (!buttons.length) return;

    // HIDE FOR GUESTS
    if (!isLoggedIn) {
      buttons.forEach(btn => btn.classList.add("hidden"));
      return;
    }

    const title = getButtonTitle(buttons[0]) || detectFromPage()?.title || "";
    const current = getStatusForTitle(title);
    buttons.forEach((btn) => {
      btn.classList.remove("hidden");
      applyDetailStatusState(btn, current);
    });
  };

  const bindDetailStatusButtons = () => {
    document.querySelectorAll("[data-detail-status]").forEach((btn) => {
      if (btn.dataset.boundDetailStatus === "1") return;
      btn.dataset.boundDetailStatus = "1";
      btn.addEventListener("click", (e) => {
        e.preventDefault();
        const item = getButtonItem(btn);
        if (!item.title) return;
        const kind = btn.getAttribute("data-detail-status");
        const current = getStatusForTitle(item.title);
        const next = current === kind ? "" : kind;
        syncStatusEverywhere(item, next);
        refreshDetailStatusButtons();
        logActivity("status_update", item, `Cambio de estado a: ${next || 'Ninguno'}`);
      });
    });
  };

  const detectFromPage = () => {
    const path = (location.pathname || "").toLowerCase();
    if (path.includes("detail")) {
      const bodyTitle = document.body?.dataset?.detailTitle;
      const bodyImage = document.body?.dataset?.detailImage;
      const bodyType = document.body?.dataset?.detailType;
      const qTitle = new URLSearchParams(location.search).get("q");
      const title = (qTitle && qTitle.trim()) || document.querySelector("h1")?.textContent?.trim() || "Anime";
      const poster =
        document.querySelector("section .aspect-\\[2\\/3\\] img") ||
        document.querySelector("section img[alt*='Pster']") ||
        document.querySelector("section img[alt*='Poster']");
      const image = poster?.src || document.querySelector("main img")?.src || "";
      return {
        title: bodyTitle || title,
        image: bodyImage || image,
        type: bodyType || "Anime"
      };
    }
    const heroTitle = document.getElementById("hero-title")?.textContent?.trim();
    const heroImage = document.getElementById("hero-image")?.src;
    if (heroTitle) return { title: heroTitle, image: heroImage || "", type: "Anime" };
    return null;
  };

  const getButtonTitle = (btn) => {
    const path = (location.pathname || "").toLowerCase();
    if (path.includes("detail")) {
      const live = detectFromPage()?.title || document.querySelector("h1")?.textContent?.trim() || "";
      if (live) btn.dataset.itemTitle = live;
      return live;
    }
    const explicit = (btn.dataset.itemTitle || "").trim();
    if (explicit) return explicit;
    const pageTitle = detectFromPage()?.title || "";
    if (pageTitle) btn.dataset.itemTitle = pageTitle;
    return pageTitle;
  };

  const getButtonItem = (btn) => {
    const pageItem = detectFromPage() || {};
    return {
      title: getButtonTitle(btn) || pageItem.title || "",
      image: (btn.dataset.itemImage || "").trim() || pageItem.image || "",
      type: (btn.dataset.itemType || "").trim() || pageItem.type || "Anime",
      mal_id: (btn.dataset.itemId || "").trim() || pageItem.mal_id || ""
    };
  };

  const setMyListAddedState = (btn) => {
    const icon = btn.querySelector(".material-symbols-outlined");
    if (icon) icon.textContent = "checklist";
    const text = btn.querySelector("[data-add-label]");
    if (text) text.textContent = "AGREGADO";
    btn.classList.add("opacity-80");
  };
  const setMyListDefaultState = (btn) => {
    const icon = btn.querySelector(".material-symbols-outlined");
    if (icon) icon.textContent = "playlist_add";
    const text = btn.querySelector("[data-add-label]");
    if (text) text.textContent = "MI LISTA";
    btn.classList.remove("opacity-80");
  };

  const setFavoriteAddedState = (btn) => {
    const icon = btn.querySelector(".material-symbols-outlined");
    if (icon) icon.style.fontVariationSettings = "'FILL' 1";
    btn.classList.add("text-violet-400");
    btn.classList.remove("text-on-surface-variant");
    btn.style.color = "#a855f7";
    const tip = btn.querySelector("[data-fav-label]");
    if (tip) tip.textContent = "Eliminar de Favoritos";
  };
  const setFavoriteDefaultState = (btn) => {
    const icon = btn.querySelector(".material-symbols-outlined");
    if (icon) icon.style.fontVariationSettings = "'FILL' 0";
    btn.classList.remove("text-violet-400");
    btn.classList.add("text-on-surface-variant");
    btn.style.color = "";
    const tip = btn.querySelector("[data-fav-label]");
    if (tip) tip.textContent = "Agregar a Favoritos";
  };

  const refreshMyListButtonState = (btn) => {
    if (!isLoggedIn()) {
      btn.classList.remove("hidden");
      setMyListDefaultState(btn);
      return;
    }
    const t = getButtonTitle(btn);
    if (!t) return;
    btn.classList.remove("hidden");
    if (exists(KEY_MY_LIST, t)) setMyListAddedState(btn);
    else setMyListDefaultState(btn);
  };
   const refreshFavoriteButtonState = (btn) => {
    if (!isLoggedIn()) {
      btn.classList.add("hidden");
      return;
    }
    const t = getButtonTitle(btn);
    if (!t) return;
    btn.classList.remove("hidden");
    if (exists(KEY_FAVORITES, t)) setFavoriteAddedState(btn);
    else setFavoriteDefaultState(btn);
  };

  const bindMyListButtons = () => {
    document.querySelectorAll("[data-add-my-list]").forEach((btn) => {
      if (btn.dataset.boundMyList === "1") return;
      btn.dataset.boundMyList = "1";
      
      if (!isLoggedIn()) {
        btn.classList.remove("hidden");
        btn.addEventListener("click", (e) => {
          e.preventDefault();
          const appUrl = window.AniDexShared?.buildAppUrl;
          try { sessionStorage.setItem("anidex_auth_back", window.location.href); } catch {}
          window.location.href = appUrl ? appUrl("registro") : "/registro";
        });
        return;
      }
      
      refreshMyListButtonState(btn);
      btn.addEventListener("click", (e) => {
        e.preventDefault();
        const item = getButtonItem(btn);
        if (!item.title) return;
        if (exists(KEY_MY_LIST, item.title)) {
          remove(KEY_MY_LIST, item.title);
          setMyListDefaultState(btn);
          logActivity("list_remove", item, "Eliminado de Mi Lista");
        } else {
          upsert(KEY_MY_LIST, item);
          setMyListAddedState(btn);
          logActivity("list_add", item, "Agregado a Mi Lista");
        }
      });
    });
  };

  const bindFavoriteButtons = () => {
    document.querySelectorAll("[data-add-favorite]").forEach((btn) => {
      if (btn.dataset.boundFav === "1") return;
      btn.dataset.boundFav = "1";
      
      if (!isLoggedIn()) {
        btn.classList.add("hidden");
        return;
      }
      
      refreshFavoriteButtonState(btn);
      btn.addEventListener("click", (e) => {
        e.preventDefault();
        const item = getButtonItem(btn);
        if (!item.title) return;
        if (exists(KEY_FAVORITES, item.title)) {
          remove(KEY_FAVORITES, item.title);
          setFavoriteDefaultState(btn);
          logActivity("favorite_remove", item, "Eliminado de Favoritos");
        } else {
          upsert(KEY_FAVORITES, item);
          setFavoriteAddedState(btn);
          logActivity("favorite_add", item, "Agregado a Favoritos");
        }
      });
    });
  };

  const renderMyListGrid = (grid, list, showAll) => {
    const visible = showAll ? list : list.slice(0, 8);
    if (!list.length) {
      grid.innerHTML = `
        <div class="col-span-full bg-surface-container-low rounded-2xl p-6 flex flex-col items-center text-center gap-3 w-full max-w-md mx-auto justify-self-center">
          <img src="https://media1.tenor.com/m/2jDTzP6EqAwAAAAd/doraemon-cries.gif" alt="Doraemon triste" class="w-20 h-20 rounded-full opacity-90" />
          <p class="text-sm text-on-surface-variant font-semibold">No hay nada agregado.</p>
        </div>`;
      return;
    }
    grid.innerHTML = visible.map((it) => {
      const cardClass = showAll
        ? "group cursor-pointer relative z-0 transition-transform duration-300 hover:scale-[1.02] rounded-2xl bg-surface-container-low/60 border border-white/5 p-1.5 hover:bg-surface-container-high/80 hover:border-violet-400/40 hover:shadow-[0_0_24px_rgba(139,92,246,0.35)] flex flex-col"
        : "group cursor-pointer overflow-visible relative z-0 hover:z-50 flex-none basis-[calc((100%-4rem)/5)] max-w-[calc((100%-4rem)/5)] transition-transform duration-300 hover:scale-[1.04] rounded-2xl bg-surface-container-low/50 border border-white/5 p-2 hover:bg-surface-container-high/70 hover:border-violet-400/40 hover:shadow-[0_0_24px_rgba(139,92,246,0.35)] hover:ring-2 hover:ring-violet-400/80 snap-start";
      const titleClass = showAll
        ? "text-[12px] font-semibold leading-tight mt-1"
        : "text-sm font-bold truncate px-1";
      const status = getStatusForTitle(it.title) || it.status || "";
      const isCompleted = status === "completed";
      const isPending = status === "pending";
      const completedBtnClass = isCompleted
        ? "bg-emerald-500/80 text-white"
        : "bg-black/40";
      const pendingBtnClass = isPending
        ? "bg-amber-400/80 text-black"
        : "bg-black/40";
      const completedTip = isCompleted ? "Eliminar de Completados" : "Completar";
      const pendingTip = isPending ? "Eliminar de Pendientes" : "Pendiente";
      const removeTip = "Eliminar de Mi Lista";
      return `
      <div class="${cardClass}" data-detail-title="${it.title}" data-detail-id="${it.mal_id || ""}" data-detail-img="${it.image || ""}">
        <div class="${showAll ? "aspect-[5/8]" : "aspect-[2/3]"} rounded-lg relative mb-2 z-0 isolate transition-all duration-300 ring-1 ring-white/10 group-hover:ring-violet-400/70 group-hover:shadow-[0_0_25px_rgba(139,92,246,0.45)]">
          <div class="absolute inset-0 rounded-lg overflow-hidden bg-surface-container-high z-0 pointer-events-none">
            <img alt="${it.title}" class="w-full h-full object-cover" src="${it.image || ""}"/>
          </div>
          <span class="absolute top-2 left-2 rounded-full bg-violet-500/90 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-widest text-white z-30">${(it.type || "Anime").toLowerCase().includes("pel") ? "Película" : "Anime"}</span>
          <button type="button" data-remove-my-list data-title="${it.title}" class="absolute top-2 right-2 p-1.5 glass-effect bg-black/40 rounded-full group/remove z-30">
            <span class="material-symbols-outlined text-primary text-sm" style="font-variation-settings: 'FILL' 1;">checklist</span>
            <span class="pointer-events-none absolute left-1/2 top-full mt-2 -translate-x-1/2 whitespace-nowrap rounded-md bg-black/90 px-2 py-1 text-[10px] font-semibold uppercase tracking-widest text-white opacity-0 transition-opacity duration-150 group-hover/remove:opacity-100 z-50">${removeTip}</span>
          </button>
          <div class="absolute bottom-2 right-2 flex flex-col gap-2 items-end">
            <button type="button" data-set-status="completed" data-title="${it.title}" class="group/comp relative p-1.5 glass-effect ${completedBtnClass} rounded-full z-30">
              <span class="material-symbols-outlined text-green-400 text-sm">check_circle</span>
              <span class="pointer-events-none absolute left-full top-1/2 ml-2 -translate-y-1/2 whitespace-nowrap rounded-md bg-black/90 px-2 py-1 text-[10px] font-semibold uppercase tracking-widest text-white opacity-0 transition-opacity duration-150 group-hover/comp:opacity-100 z-50">${completedTip}</span>
            </button>
            <button type="button" data-set-status="pending" data-title="${it.title}" class="group/pend relative p-1.5 glass-effect ${pendingBtnClass} rounded-full z-30">
              <span class="material-symbols-outlined text-amber-300 text-sm">schedule</span>
              <span class="pointer-events-none absolute left-full top-1/2 ml-2 -translate-y-1/2 whitespace-nowrap rounded-md bg-black/90 px-2 py-1 text-[10px] font-semibold uppercase tracking-widest text-white opacity-0 transition-opacity duration-150 group-hover/pend:opacity-100 z-50">${pendingTip}</span>
            </button>
          </div>
        </div>
        <h5 class="${titleClass}">${it.title}</h5>
      </div>`;
    }).join("");
    if (!showAll && list.length > 8) {
      grid.insertAdjacentHTML(
        "beforeend",
        `<div class="w-36 shrink-0 rounded-xl border border-dashed border-violet-400/40 bg-surface-container-low p-3 text-xs text-on-surface-variant flex items-center justify-center text-center">
          Mostrando 8 de ${list.length}. Usa "Ver todo".
        </div>`
      );
    }

    grid.querySelectorAll("[data-remove-my-list]").forEach((b) => {
      b.addEventListener("click", (e) => {
        e.preventDefault(); e.stopPropagation();
        const t = b.getAttribute("data-title") || "";
          remove(KEY_MY_LIST, t);
          renderUserMyList();
          renderStatusSections();
          updateStatusCounters();
        document.querySelectorAll("[data-add-my-list]").forEach((btn) => {
          if (norm(getButtonTitle(btn)) === norm(t)) setMyListDefaultState(btn);
        });
      });
    });
    grid.querySelectorAll("[data-detail-title]").forEach((card) => {
      if (card.dataset.boundDetail === "1") return;
      card.dataset.boundDetail = "1";
      card.addEventListener("click", (e) => {
        if (e.target.closest("button")) return;
        const title = card.getAttribute("data-detail-title") || "";
        const id = card.getAttribute("data-detail-id") || "";
        let url = "detail";
        if (id) {
          url += `?mal_id=${encodeURIComponent(id)}`;
          if (title) url += `&q=${encodeURIComponent(title)}`;
        } else if (title) {
          url += `?q=${encodeURIComponent(title)}`;
        }
        window.location.href = url;
      });
    });
    grid.querySelectorAll("[data-set-status]").forEach((b) => {
      b.addEventListener("click", (e) => {
        e.preventDefault(); e.stopPropagation();
        const t = b.getAttribute("data-title") || "";
        const target = b.getAttribute("data-set-status") || "";
        const listNow = readKey(KEY_MY_LIST).map((x) => {
          if (norm(x.title) !== norm(t)) return x;
          const nextStatus = x.status === target ? "" : target;
          return { ...x, status: nextStatus };
        });
        writeKey(KEY_MY_LIST, listNow);
        const item = listNow.find((x) => norm(x.title) === norm(t)) || { title: t };
        upsertStatus(item, item.status || "");
        renderUserMyList();
        renderStatusSections();
        updateStatusCounters();
      });
    });
  };

  const renderUserMyList = () => {
    const grids = [
      document.getElementById("my-list-grid"),
      document.getElementById("my-list-grid-full")
    ].filter(Boolean);
    if (!grids.length) return;
    const list = readKey(KEY_MY_LIST);
    grids.forEach((grid) => {
      const showAll = document.body?.dataset?.showAllLists === "1" || grid.dataset.showAll === "1";
      renderMyListGrid(grid, list, showAll);
    });
    if (window.refreshUserCarousels) window.refreshUserCarousels();
  };

  const renderFavoritesGrid = (grid, list, showAll) => {
    const visible = showAll ? list : list.slice(0, 8);
    if (!list.length) {
      grid.innerHTML = `
        <div class="col-span-full bg-surface-container-low rounded-2xl p-6 flex flex-col items-center text-center gap-3 w-full max-w-md mx-auto justify-self-center">
          <img src="https://media1.tenor.com/m/2jDTzP6EqAwAAAAd/doraemon-cries.gif" alt="Doraemon triste" class="w-20 h-20 rounded-full opacity-90" />
          <p class="text-sm text-on-surface-variant font-semibold">No hay nada agregado.</p>
        </div>`;
      return;
    }
    grid.innerHTML = visible.map((it) => {
      const cardClass = showAll
        ? "group cursor-pointer relative z-0 transition-transform duration-300 hover:scale-[1.02] rounded-2xl bg-surface-container-low/60 border border-white/5 p-1.5 hover:bg-surface-container-high/80 hover:border-violet-400/40 hover:shadow-[0_0_24px_rgba(139,92,246,0.35)] flex flex-col"
        : "group cursor-pointer overflow-visible relative z-0 hover:z-50 flex-none basis-[calc((100%-4rem)/5)] max-w-[calc((100%-4rem)/5)] transition-transform duration-300 hover:scale-[1.04] rounded-2xl bg-surface-container-low/50 border border-white/5 p-2 hover:bg-surface-container-high/70 hover:border-violet-400/40 hover:shadow-[0_0_24px_rgba(139,92,246,0.35)] hover:ring-2 hover:ring-violet-400/80 snap-start";
      const titleClass = showAll
        ? "text-[12px] font-semibold leading-tight mt-1"
        : "text-sm font-bold truncate px-1";
      const status = getStatusForTitle(it.title) || it.status || "";
      const isCompleted = status === "completed";
      const isPending = status === "pending";
      const completedBtnClass = isCompleted
        ? "bg-emerald-500/80 text-white"
        : "bg-black/40";
      const pendingBtnClass = isPending
        ? "bg-amber-400/80 text-black"
        : "bg-black/40";
      const completedTip = isCompleted ? "Eliminar de Completados" : "Completar";
      const pendingTip = isPending ? "Eliminar de Pendientes" : "Pendiente";
      const removeTip = "Eliminar de Favoritos";
      return `
      <div class="${cardClass}" data-detail-title="${it.title}" data-detail-id="${it.mal_id || ""}" data-detail-img="${it.image || ""}">
        <div class="${showAll ? "aspect-[5/8]" : "aspect-[2/3]"} rounded-lg relative mb-2 z-0 isolate transition-all duration-300 ring-1 ring-white/10 group-hover:ring-violet-400/70 group-hover:shadow-[0_0_25px_rgba(139,92,246,0.45)]">
          <div class="absolute inset-0 rounded-lg overflow-hidden bg-surface-container-high z-0 pointer-events-none">
            <img alt="${it.title}" class="w-full h-full object-cover" src="${it.image || ""}"/>
          </div>
          <span class="absolute top-2 left-2 rounded-full bg-violet-500/90 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-widest text-white z-30">${(it.type || "Anime").toLowerCase().includes("pel") ? "Película" : "Anime"}</span>
          <button type="button" data-remove-favorite data-title="${it.title}" class="absolute top-2 right-2 p-1.5 glass-effect bg-black/40 rounded-full group/heart z-30">
            <span class="material-symbols-outlined text-primary text-sm" style="font-variation-settings: 'FILL' 1;">favorite</span>
            <span class="pointer-events-none absolute left-1/2 top-full mt-2 -translate-x-1/2 whitespace-nowrap rounded-md bg-black/90 px-2 py-1 text-[10px] font-semibold uppercase tracking-widest text-white opacity-0 transition-opacity duration-150 group-hover/heart:opacity-100 z-50">${removeTip}</span>
          </button>
          <div class="absolute bottom-2 right-2 flex flex-col gap-2 items-end">
            <button type="button" data-set-status="completed" data-title="${it.title}" class="group/comp relative p-1.5 glass-effect ${completedBtnClass} rounded-full z-30">
              <span class="material-symbols-outlined text-green-400 text-sm">check_circle</span>
              <span class="pointer-events-none absolute left-full top-1/2 ml-2 -translate-y-1/2 whitespace-nowrap rounded-md bg-black/90 px-2 py-1 text-[10px] font-semibold uppercase tracking-widest text-white opacity-0 transition-opacity duration-150 group-hover/comp:opacity-100 z-50">${completedTip}</span>
            </button>
            <button type="button" data-set-status="pending" data-title="${it.title}" class="group/pend relative p-1.5 glass-effect ${pendingBtnClass} rounded-full z-30">
              <span class="material-symbols-outlined text-amber-300 text-sm">schedule</span>
              <span class="pointer-events-none absolute left-full top-1/2 ml-2 -translate-y-1/2 whitespace-nowrap rounded-md bg-black/90 px-2 py-1 text-[10px] font-semibold uppercase tracking-widest text-white opacity-0 transition-opacity duration-150 group-hover/pend:opacity-100 z-50">${pendingTip}</span>
            </button>
          </div>
        </div>
        <h5 class="${titleClass}">${it.title}</h5>
      </div>`;
    }).join("");
    if (!showAll && list.length > 8) {
      grid.insertAdjacentHTML(
        "beforeend",
        `<div class="w-36 shrink-0 rounded-xl border border-dashed border-violet-400/40 bg-surface-container-low p-3 text-xs text-on-surface-variant flex items-center justify-center text-center">
          Mostrando 8 de ${list.length}. Usa "Ver todo".
        </div>`
      );
    }

    grid.querySelectorAll("[data-remove-favorite]").forEach((b) => {
      b.addEventListener("click", (e) => {
        e.preventDefault(); e.stopPropagation();
        const t = b.getAttribute("data-title") || "";
          remove(KEY_FAVORITES, t);
          renderUserFavorites();
          renderStatusSections();
          updateStatusCounters();
        document.querySelectorAll("[data-add-favorite]").forEach((btn) => {
          if (norm(getButtonTitle(btn)) === norm(t)) setFavoriteDefaultState(btn);
        });
      });
    });
    grid.querySelectorAll("[data-detail-title]").forEach((card) => {
      if (card.dataset.boundDetail === "1") return;
      card.dataset.boundDetail = "1";
      card.addEventListener("click", (e) => {
        if (e.target.closest("button")) return;
        const title = card.getAttribute("data-detail-title") || "";
        const id = card.getAttribute("data-detail-id") || "";
        let url = "detail";
        if (id) {
          url += `?mal_id=${encodeURIComponent(id)}`;
          if (title) url += `&q=${encodeURIComponent(title)}`;
        } else if (title) {
          url += `?q=${encodeURIComponent(title)}`;
        }
        window.location.href = url;
      });
    });
    grid.querySelectorAll("[data-set-status]").forEach((b) => {
      b.addEventListener("click", (e) => {
        e.preventDefault(); e.stopPropagation();
        const t = b.getAttribute("data-title") || "";
        const target = b.getAttribute("data-set-status") || "";
        const listNow = readKey(KEY_FAVORITES).map((x) => {
          if (norm(x.title) !== norm(t)) return x;
          const nextStatus = x.status === target ? "" : target;
          return { ...x, status: nextStatus };
        });
        writeKey(KEY_FAVORITES, listNow);
        const item = listNow.find((x) => norm(x.title) === norm(t)) || { title: t };
        upsertStatus(item, item.status || "");
        renderUserFavorites();
        renderStatusSections();
        updateStatusCounters();
      });
    });
  };

  const renderUserFavorites = () => {
    const grids = [
      document.getElementById("favorites-grid"),
      document.getElementById("favorites-grid-full")
    ].filter(Boolean);
    if (!grids.length) return;
    const list = readKey(KEY_FAVORITES);
    grids.forEach((grid) => {
      const showAll = document.body?.dataset?.showAllLists === "1" || grid.dataset.showAll === "1";
      renderFavoritesGrid(grid, list, showAll);
    });
    if (window.refreshUserCarousels) window.refreshUserCarousels();
  };

  const renderStatusGrid = (grid, statusFilter) => {
    const list = readStatus().filter((x) => x.status === statusFilter);
    if (!list.length) {
      grid.innerHTML = `
        <div class="col-span-full bg-surface-container-low rounded-2xl p-6 flex flex-col items-center text-center gap-3">
          <img src="https://media1.tenor.com/m/2jDTzP6EqAwAAAAd/doraemon-cries.gif" alt="Doraemon triste" class="w-20 h-20 rounded-full opacity-90" />
          <p class="text-sm text-on-surface-variant font-semibold">No hay nada agregado.</p>
        </div>`;
      return;
    }
    grid.innerHTML = list.map((it) => `
      <div class="group cursor-pointer relative z-0 transition-transform duration-300 hover:scale-[1.02] rounded-2xl bg-surface-container-low/60 border border-white/5 p-1.5 hover:bg-surface-container-high/80 hover:border-violet-400/40 hover:shadow-[0_0_24px_rgba(139,92,246,0.35)] flex flex-col" data-detail-title="${it.title}" data-detail-id="${it.mal_id || ""}" data-detail-img="${it.image || ""}">
        <div class="aspect-[5/8] rounded-lg relative mb-2 z-0 isolate transition-all duration-300 ring-1 ring-white/10 group-hover:ring-violet-400/70 group-hover:shadow-[0_0_25px_rgba(139,92,246,0.45)]">
          <div class="absolute inset-0 rounded-lg overflow-hidden bg-surface-container-high z-0 pointer-events-none">
            <img alt="${it.title}" class="w-full h-full object-cover" src="${it.image || ""}"/>
          </div>
          <span class="absolute top-2 left-2 rounded-full bg-violet-500/90 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-widest text-white z-30">${(it.type || "Anime").toLowerCase().includes("pel") ? "Película" : "Anime"}</span>
          <button type="button" data-remove-status data-title="${it.title}" class="absolute top-2 right-2 p-1.5 glass-effect bg-black/40 rounded-full group/remove z-30">
            <span class="material-symbols-outlined text-primary text-sm">delete</span>
            <span class="pointer-events-none absolute left-1/2 top-full mt-2 -translate-x-1/2 whitespace-nowrap rounded-md bg-black/90 px-2 py-1 text-[10px] font-semibold uppercase tracking-widest text-white opacity-0 transition-opacity duration-150 group-hover/remove:opacity-100 z-50">${statusFilter === "completed" ? "Eliminar de Completados" : "Eliminar de Pendientes"}</span>
          </button>
        </div>
        <h5 class="text-[12px] font-semibold leading-tight mt-1">${it.title}</h5>
      </div>`).join("");

    grid.querySelectorAll("[data-remove-status]").forEach((b) => {
      b.addEventListener("click", (e) => {
        e.preventDefault(); e.stopPropagation();
        const t = b.getAttribute("data-title") || "";
        const nextStatus = readStatus().filter((x) => norm(x.title) !== norm(t));
        writeStatus(nextStatus);
        [KEY_MY_LIST, KEY_FAVORITES].forEach((keyFn) => {
          const listNow = readKey(keyFn).map((x) => (norm(x.title) === norm(t) ? { ...x, status: "" } : x));
          writeKey(keyFn, listNow);
        });
        renderUserMyList();
        renderUserFavorites();
        renderStatusSections();
        updateStatusCounters();
      });
    });
    grid.querySelectorAll("[data-detail-title]").forEach((card) => {
      if (card.dataset.boundDetail === "1") return;
      card.dataset.boundDetail = "1";
      card.addEventListener("click", (e) => {
        if (e.target.closest("button")) return;
        const title = card.getAttribute("data-detail-title") || "";
        const id = card.getAttribute("data-detail-id") || "";
        let url = "detail";
        if (id) {
          url += `?mal_id=${encodeURIComponent(id)}`;
          if (title) url += `&q=${encodeURIComponent(title)}`;
        } else if (title) {
          url += `?q=${encodeURIComponent(title)}`;
        }
        window.location.href = url;
      });
    });
  };

  const renderStatusSections = () => {
    const completedGrid = document.getElementById("completed-grid");
    const pendingGrid = document.getElementById("pending-grid");
    if (completedGrid) renderStatusGrid(completedGrid, "completed");
    if (pendingGrid) renderStatusGrid(pendingGrid, "pending");
  };

  window.AniDexFavorites = {
    init() {
      const start = () => {
        migrateStatusFromLists();
        bindMyListButtons();
        bindFavoriteButtons();
        bindDetailStatusButtons();
        renderUserMyList();
        renderUserFavorites();
        renderStatusSections();
        updateStatusCounters();
        setTimeout(() => {
          document.querySelectorAll("[data-add-my-list]").forEach(refreshMyListButtonState);
          document.querySelectorAll("[data-add-favorite]").forEach(refreshFavoriteButtonState);
          refreshDetailStatusButtons();
        }, 300);
      };

      if (window.AniDexLayout && typeof window.AniDexLayout.onReady === "function") {
        window.AniDexLayout.onReady(start);
      } else {
        start();
      }
    },
    refresh() {
      document.querySelectorAll("[data-add-my-list]").forEach(refreshMyListButtonState);
      document.querySelectorAll("[data-add-favorite]").forEach(refreshFavoriteButtonState);
      refreshDetailStatusButtons();
    }
  };
})();



