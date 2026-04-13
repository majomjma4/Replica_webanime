const AniDexDetailDataBoot = () => {
  const getFallbackBasePath = () => {
    const loc = window.location.pathname;
    if (loc.includes("/WebAnime/")) return "/WebAnime/";
    if (loc.includes("/WebAnime_CI4_Replica/")) return "/WebAnime_CI4_Replica/";
    return "/";
  };
  const appUrl = (path = "") => {
    if (window.AniDexShared?.buildAppUrl) return window.AniDexShared.buildAppUrl(path);
    const base = getFallbackBasePath();
    return base + (path.startsWith("/") ? path.slice(1) : path);
  };
  const API = appUrl("api/jikan_proxy");
  const DETAIL_OVERRIDES = {
    57658: {
      episodes: 12,
      synopsis:
        "Tras las masacres de Shibuya, Itadori, cargado de culpa y preocupado por el inter\u00e9s de Sukuna en Fushiguro, decide no volver a la Preparatoria de Hechicer\u00eda. Se une a Choso para exorcizar los esp\u00edritus liberados por Noritoshi Kamo. En medio del caos, la c\u00fapula jujutsu reactiva la ejecuci\u00f3n de Itadori y asigna a Yuta Okkotsu como su verdugo. Hechiceros modernos y antiguos, ahora jugadores del Juego de la Exterminaci\u00f3n, chocan con motivos opuestos y empujan al mundo hacia una nueva era dominada por la hechicer\u00eda."
    },
    60058: {
      title: "Oshi no Ko Season 3"
    }
  };
  const GENRE_ES = {
    Action: "Acci\u00f3n",
    Adventure: "Aventura",
    Comedy: "Comedia",
    Drama: "Drama",
    Fantasy: "Fantas\u00eda",
    Romance: "Romance",
    Suspense: "Suspenso",
    Mystery: "Misterio",
    SciFi: "Ciencia ficci\u00f3n",
    "Sci-Fi": "Ciencia ficci\u00f3n",
    Horror: "Terror",
    Sports: "Deportes",
    "Slice of Life": "Recuentos de la vida",
    Supernatural: "Sobrenatural",
    "Award Winning": "Premiado"
  };

  const baseNormalize = window.AniDexShared?.normalizeText || ((value) => String(value || "").toLowerCase().trim());
  const norm = (v) =>
    baseNormalize(v)
      .replace(/[^\w\s]/g, " ")
      .replace(/\s+/g, " ")
      .trim();

  const score = (q, t) => {
    const a = norm(q);
    const b = norm(t);
    if (!a || !b) return 0;
    if (a === b) return 100;
    if (b.includes(a) || a.includes(b)) return 80;
    const at = a.split(" ");
    const bt = b.split(" ");
    const overlap = at.filter((x) => bt.includes(x)).length;
    return Math.round((overlap / Math.max(at.length, bt.length)) * 70);
  };

  const pickTitle = async (query, retries = 2) => {
    const r = await fetch(`${API}?endpoint=${encodeURIComponent('anime?q=' + encodeURIComponent(query) + '&limit=10&order_by=popularity&sort=asc')}`);
    if (r.status === 429 && retries > 0) {
      await delay(1000);
      return pickTitle(query, retries - 1);
    }
    if (!r.ok) return null;
    const j = await r.json();
    const list = j?.data || [];
    const best = list
      .map((it) => ({
        it,
        s: Math.max(score(query, it?.title), score(query, it?.title_english), score(query, it?.title_japanese))
      }))
      .sort((a, b) => b.s - a.s)[0];
    return best?.it || list[0] || null;
  };

  const delay = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

  const byId = async (id, suffix = "full", retries = 2) => {
    try {
      const r = await fetch(`${API}?endpoint=${encodeURIComponent('anime/' + id + '/' + suffix)}`);
      if (r.status === 429 && retries > 0) {
        await delay(1000);
        return byId(id, suffix, retries - 1);
      }
      if (!r.ok) return null;
      const j = await r.json();
      return j?.data || null;
    } catch {
      return null;
    }
  };

  const fetchEpisodeMeta = async (id, retries = 2) => {
    if (!id) return [];
    try {
      const r = await fetch(`${API}?endpoint=${encodeURIComponent('anime/' + id + '/episodes')}`);
      if (r.status === 429 && retries > 0) {
        await delay(1000);
        return fetchEpisodeMeta(id, retries - 1);
      }
      if (!r.ok) return [];
      const j = await r.json();
      return Array.isArray(j?.data) ? j.data : [];
    } catch {
      return [];
    }
  };

  const fetchEpisodeDetail = async (id, episodeNumber, retries = 1) => {
    if (!id || !episodeNumber) return null;
    try {
      const r = await fetch(`${API}?endpoint=${encodeURIComponent('anime/' + id + '/episodes/' + episodeNumber)}`);
      if (r.status === 429 && retries > 0) {
        await delay(1000);
        return fetchEpisodeDetail(id, episodeNumber, retries - 1);
      }
      if (!r.ok) return null;
      const j = await r.json();
      return j?.data || null;
    } catch {
      return null;
    }
  };

  const fetchTopByType = async (type, limit = 10, retries = 2) => {
    try {
      const r = await fetch(`${API}?endpoint=${encodeURIComponent('top/anime?filter=bypopularity&type=' + encodeURIComponent(type) + '&limit=' + limit)}`);
      if (r.status === 429 && retries > 0) {
        await delay(1000);
        return fetchTopByType(type, limit, retries - 1);
      }
      if (!r.ok) return [];
      const j = await r.json();
      return j?.data || [];
    } catch {
      return [];
    }
  };

  const imgOf = (it) =>
    it?.images?.webp?.large_image_url ||
    it?.images?.jpg?.large_image_url ||
    it?.images?.jpg?.image_url ||
    "";

  const toSpanishStatus = (value) => {
    const v = (value || "").toLowerCase();
    if (v.includes("finished")) return "Finalizado";
    if (v.includes("currently")) return "En emisi\u00f3n";
    if (v.includes("not yet")) return "Pr\u00f3ximamente";
    return value || "N/A";
  };

  const toSpanishType = (value) => {
    const v = (value || "").toLowerCase();
    if (v === "tv") return "Anime";
    if (v === "movie") return "Pel\u00edcula";
    if (v === "ova") return "OVA";
    if (v === "special") return "Especial";
    return value || "N/A";
  };

  const toSpanishDuration = (value) => {
    const raw = value || "";
    return raw.replace("min per ep", "min por episodio").replace("hr", "h");
  };
  const toSpanishRating = (value) => {
    const v = value || "";
    return v
      .replace("R - 17+ (violence & profanity)", "R - 17+ (violencia y lenguaje expl\u00edcito)")
      .replace("PG-13 - Teens 13 or older", "PG-13 - Mayores de 13 a\u00f1os")
      .replace("PG - Children", "PG - P\u00fablico general")
      .replace("G - All Ages", "G - Todas las edades")
      .replace("Rx - Hentai", "Rx - Adultos");
  };

  const translateToEs = async (text) => (window.AniDexShared?.translateAutoToEs ? window.AniDexShared.translateAutoToEs(text) : String(text || "").trim());

  const renderMetaBlock = (container, label, value) => {
    const wrap = document.createElement("div");
    wrap.className = "flex flex-col gap-1";
    wrap.innerHTML = `<span class="text-xs text-on-surface-variant uppercase tracking-widest font-bold">${label}</span><span class="text-on-surface font-medium">${value || "N/A"}</span>`;
    container.appendChild(wrap);
  };

  const shortenEpisodeText = (text, maxLength = 170) => {
    const clean = (text || "").replace(/\s+/g, " ").trim();
    if (!clean) return "";
    if (clean.length <= maxLength) return clean;

    const sentenceMatch = clean.match(/[^.!?]+[.!?]+/g) || [];
    let built = "";
    for (const sentence of sentenceMatch) {
      const next = `${built} ${sentence}`.trim();
      if (next.length > maxLength) break;
      built = next;
    }
    if (built && built.length >= 80) return built.trim();

    const trimmed = clean.slice(0, maxLength);
    const punctuationIndex = Math.max(
      trimmed.lastIndexOf('.'),
      trimmed.lastIndexOf('!'),
      trimmed.lastIndexOf('?')
    );
    if (punctuationIndex >= 80) {
      return trimmed.slice(0, punctuationIndex + 1).trim();
    }

    const separators = [',', ';', ':'];
    const separatorIndex = Math.max(...separators.map((sep) => trimmed.lastIndexOf(sep)));
    if (separatorIndex >= 90) {
      return `${trimmed.slice(0, separatorIndex).trim()}.`;
    }

    const lastSpace = trimmed.lastIndexOf(' ');
    const base = (lastSpace > 80 ? trimmed.slice(0, lastSpace) : trimmed).trim().replace(/[,:;\-\s]+$/, '');
    return `${base}.`;
  };

  const bindHorizontalArrows = (row, prev, next, perDefault = 5) => {
    if (!row || !prev || !next) return;
    const step = () => {
      const card = row.querySelector(".carousel-card") || row.firstElementChild;
      if (!card) return Math.max(260, Math.floor(row.clientWidth * 0.85));
      const styles = getComputedStyle(row);
      const gap = parseFloat(styles.columnGap || styles.gap || "0") || 0;
      const per = parseInt(styles.getPropertyValue("--cards")) || perDefault;
      const cardWidth = card.getBoundingClientRect().width;
      return Math.max(260, Math.round((cardWidth + gap) * per));
    };
    const setDisabled = (btn, disabled) => {
      btn.disabled = disabled;
      btn.classList.toggle("opacity-40", disabled);
      btn.classList.toggle("pointer-events-none", disabled);
    };
    const updateState = () => {
      const max = row.scrollWidth - row.clientWidth;
      const totalItems = row.querySelectorAll(".carousel-card").length;
      if (totalItems <= 4 || max <= 2) {
        prev.classList.add("hidden");
        next.classList.add("hidden");
        setDisabled(prev, true);
        setDisabled(next, true);
        return;
      }
      prev.classList.remove("hidden");
      next.classList.remove("hidden");
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

    const slider = (title, items, renderItemHtml, idBase) => {
      const section = document.createElement("div");
      section.className = "space-y-4";
      section.innerHTML = `<div class="flex items-center justify-between"><h3 class="font-headline text-3xl font-bold">${title}</h3></div>`;
    const rowWrap = document.createElement("div");
    rowWrap.className = "relative overflow-x-hidden";
    const prev = document.createElement("button");
    prev.id = `${idBase}-prev`;
    prev.type = "button";
    prev.className = "absolute left-0 top-1/2 -translate-y-1/2 z-10 w-9 h-9 rounded-full bg-surface-container-high/80 border border-outline-variant/20 text-primary flex items-center justify-center shadow-md";
    prev.innerHTML = '<span class="material-symbols-outlined text-base">chevron_left</span>';
    const next = document.createElement("button");
    next.id = `${idBase}-next`;
    next.type = "button";
    next.className = "absolute right-0 top-1/2 -translate-y-1/2 z-10 w-9 h-9 rounded-full bg-surface-container-high/80 border border-outline-variant/20 text-primary flex items-center justify-center shadow-md";
    next.innerHTML = '<span class="material-symbols-outlined text-base">chevron_right</span>';
      const row = document.createElement("div");
      row.id = `${idBase}-row`;
      row.className = "carousel-row no-scrollbar pb-2 scroll-smooth";
      row.style.setProperty("--cards", idBase === "detail-images" ? "4" : "5");
      row.style.setProperty("--gap", "1rem");
      row.style.setProperty("--pad", "0rem");
      row.style.paddingInline = "0";
    items.forEach((it) => {
      const card = document.createElement("div");
      card.className = "carousel-card shrink-0";
      card.innerHTML = renderItemHtml(it);
      row.appendChild(card);
    });
    rowWrap.appendChild(prev);
    rowWrap.appendChild(next);
    rowWrap.appendChild(row);
    section.appendChild(rowWrap);
    setTimeout(() => {
      bindHorizontalArrows(row, prev, next, idBase === "detail-images" ? 4 : 5);
    }, 0);
    return section;
  };

  const fetchLocalAnime = async (malId, title, dbId) => {
    let url = `${appUrl("api/anime_data")}?`;
    if (dbId) url += `id=${encodeURIComponent(dbId)}&`;
    if (malId) url += `mal_id=${encodeURIComponent(malId)}&`;
    if (title) url += `q=${encodeURIComponent(title)}`;
    try {
      const res = await fetch(url.replace(/&$/, ""));
      if (!res.ok) return null;
      const json = await res.json();
      return json.success ? json.data : null;
    } catch {
      return null;
    }
  };

  const init = async () => {
    if (window.AniDexLayout && typeof window.AniDexLayout.onReady === "function") {
      window.AniDexLayout.onReady(_runInit);
    } else {
      _runInit();
    }
  };

  const _runInit = async () => {
    const isLogged = window.AniDexLayout ? window.AniDexLayout.isLoggedIn() : (localStorage.getItem("nekora_logged_in") === "true");
    const isPremium = window.AniDexLayout ? window.AniDexLayout.isPremium() : (localStorage.getItem("nekora_premium") === "true" || localStorage.getItem("nekora_admin") === "true");
    const canWatchEpisodes = isLogged && isPremium;
    const routeInfo = window.__DETAIL_ROUTE_INFO || (window.AniDexShared?.getDetailRouteInfo ? window.AniDexShared.getDetailRouteInfo() : { malId: "", query: "" });
    const dbIdParam = "";
    const malIdParam = String(routeInfo.ref || routeInfo.malId || "").match(/^\d+$/) ? String(routeInfo.ref || routeInfo.malId || "") : "";
    const query = (document.body?.dataset?.detailQuery || routeInfo.query || (!malIdParam ? String(routeInfo.ref || "").replace(/-/g, " ") : "") || document.querySelector("h1")?.textContent?.trim() || "").trim();
    const preTitle = query || "";
    if (preTitle && document.querySelector("h1")) {
      document.querySelector("h1").textContent = preTitle;
    }
    let full = null;
    let selectedId = null;
    let isLocal = false;

    const localData = await fetchLocalAnime(malIdParam, query, dbIdParam);
    if (localData && localData.anime) {
      full = localData.anime;
      // Inyectamos datos profundos si vinieron de la DB
      if (localData.characters) full.characters = localData.characters;
      if (localData.videos) full.videos = localData.videos;
      if (localData.pictures) full.pictures = localData.pictures;
      if (localData.episodes) full.episodes_data = localData.episodes;
      
      selectedId = full.mal_id;
      isLocal = true;
      
      const needsComplement = !full.studios?.length || !full.genres?.length || (full.synopsis || "").length < 50 || !full.title_english || !full.title_japanese;
      if (needsComplement && selectedId) {
        const jikanFull = await byId(selectedId, "full");
        if (jikanFull) {
            if (!full.studios?.length && jikanFull.studios?.length) full.studios = jikanFull.studios;
            if (!full.genres?.length && jikanFull.genres?.length) full.genres = jikanFull.genres;
            if ((!full.synopsis || full.synopsis.length < 50) && jikanFull.synopsis) full.synopsis = jikanFull.synopsis;
            if (!full.duration && jikanFull.duration) full.duration = jikanFull.duration;
            if (!full.rating && jikanFull.rating) full.rating = jikanFull.rating;
            if (!full.rank && jikanFull.rank) full.rank = jikanFull.rank;
            if (!full.score && jikanFull.score) full.score = jikanFull.score;
            if (!full.title_english && jikanFull.title_english) full.title_english = jikanFull.title_english;
            if (!full.title_japanese && jikanFull.title_japanese) full.title_japanese = jikanFull.title_japanese;
        }
      }
      if (!full.title_english) full.title_english = full.title;
      if (!full.title_japanese) full.title_japanese = full.title;
    } else {
      if (malIdParam) {
        selectedId = Number(malIdParam);
        if (selectedId) {
          const res = await byId(selectedId, "full");
          full = res?.data || res; // Manejo de Jikan v4
        }
      }
      if (!full && query) {
        const found = await pickTitle(query);
        if (found) {
          selectedId = found.mal_id;
          const res = await byId(selectedId, "full");
          full = res?.data || res; // Manejo de Jikan v4
        }
      }
    }

    if (!full) {
      console.warn("NekoraDetail: No se pudo obtener información del anime.");
      return;
    }

    // Normalizar selectedId y mal_id
    if (full.mal_id) selectedId = Number(full.mal_id);
    else if (selectedId) full.mal_id = selectedId;
    
    console.log("NekoraDetail: Iniciando sincronización para MAL_ID:", selectedId);

    try {
      const mapKey = "anidex_title_id_map_v1";
      const mapRaw = localStorage.getItem(mapKey);
      const map = mapRaw ? JSON.parse(mapRaw) : {};
      const addMap = (val) => {
        const key = norm(val || "");
        if (key) map[key] = selectedId;
      };
      addMap(full.title);
      addMap(full.title_english);
      addMap(full.title_japanese);
      addMap(query);
      addMap(preTitle);
      localStorage.setItem(mapKey, JSON.stringify(map));
    } catch {}

    // 1. PERSISTENCIA INMEDIATA (FASE 1: Datos Básicos)
    // Guardamos lo esencial primero para asegurarnos de que el anime exista en la BD
    if (full && full.mal_id) {
      const saveUrl = appUrl("api/save_anime");
      console.log("NekoraDetail: Enviando Fase 1 (Persistencia Rápida)...");
      try {
        const res1 = await window.fetch(saveUrl, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(full)
        }).then(r => r.json());

        if (res1.success) {
          console.log(`NekoraDetail: Fase 1 [${res1.action}] exitosa. DB_ID: ${res1.id}`);
          selectedId = Number(res1.mal_id || selectedId); // Refuerzo
        } else {
          console.warn("NekoraDetail: Fase 1 no reportó éxito:", res1.error);
        }
      } catch (e) {
        console.error("NekoraDetail: Error crítico en Fase 1:", e);
      }
    }

    // 2. CARGA PROFUNDA (FASE 2: Personajes, Videos, Fotos)
    // Se ejecuta DESPUÉS de asegurar que la Fase 1 terminó o falló, para no colisionar
    const [chars, vids, pics] = await Promise.all([
      (isLocal && full.characters && full.characters.length > 0) ? Promise.resolve(full.characters) : byId(selectedId, "characters").then((data) => data || []),
      (isLocal && full.videos && (full.videos.promo?.length > 0)) ? Promise.resolve(full.videos) : byId(selectedId, "videos").then((data) => data || {}),
      (isLocal && full.pictures && full.pictures.length > 0) ? Promise.resolve(full.pictures) : byId(selectedId, "pictures").then((data) => data || [])
    ]);

    if (full && full.mal_id) {
      const deepData = {
        ...full,
        characters: chars,
        videos: vids,
        pictures: pics
      };
      
      const saveUrl = appUrl("api/save_anime");
      console.log("NekoraDetail: Enviando Fase 2 (Persistencia Profunda)...");

      try {
        const res2 = await window.fetch(saveUrl, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(deepData)
        }).then(r => r.json());

        if (res2.success) {
          console.log(`NekoraDetail: Fase 2 [${res2.action}] exitosa. DB_ID: ${res2.id}`);
        } else {
          console.error("NekoraDetail: Error en Fase 2:", res2.error);
        }
      } catch (err) {
        console.error("NekoraDetail: Fallo de red en Fase 2:", err);
      }
    }
    const forceTitles = [
      "Jujutsu Kaisen: Shimetsu Kaiyuu - Zenpen"
    ];
    const requestedTitle = preTitle || query || "";
    const forceTitle = forceTitles.find((t) => norm(t) === norm(requestedTitle));
    const override = DETAIL_OVERRIDES[selectedId];
    if (override) {
      if (override.title) {
        full.title = override.title;
        full.title_english = override.title;
      }
      if (override.synopsis) full.synopsis = override.synopsis;
      if (override.episodes) full.episodes = override.episodes;
    }
    if (forceTitle) {
      full.title = forceTitle;
      full.title_english = forceTitle;
    }

    const preferredTitle = full.title_english || full.title || full.title_japanese || "Anime";
    const originalTitle = full.title || full.title_japanese || full.title_english || "N/A";
    const titleMain = document.querySelector("h1");
    if (titleMain) {
      if (preferredTitle === "Frieren: Beyond Journey's End Season 2") {
        titleMain.innerHTML = "Frieren: Beyond<br/>Journey&#39;s End<br/>Season 2";
      } else if (preferredTitle === "Jujutsu Kaisen: The Culling Game Part 1") {
        titleMain.innerHTML = "Jujutsu kaisen:<br/>The culling Game<br/>Parte 1";
      } else if (preferredTitle === "Jujutsu Kaisen: Shimetsu Kaiyuu - Zenpen") {
        titleMain.innerHTML = "Jujutsu Kaisen:<br/>Shimetsu Kaiyuu - Zenpen";
      } else if (preferredTitle === "Hell's Paradise Season 2") {
        titleMain.innerHTML = "Hell&#39;s Paradise<br/>Season 2";
      } else if (preferredTitle === "Sentenced to Be a Hero") {
        titleMain.innerHTML = "Sentenced to<br/>Be a Hero";
      } else {
        titleMain.textContent = preferredTitle;
      }
      let sub = document.getElementById("detail-original-title");
      if (!sub) {
        sub = document.createElement("p");
        sub.id = "detail-original-title";
        sub.className = "text-sm text-on-surface-variant font-medium mt-1";
        titleMain.insertAdjacentElement("afterend", sub);
      }
      sub.textContent = originalTitle && originalTitle !== preferredTitle ? originalTitle : "";
      sub.style.display = sub.textContent ? "block" : "none";
    }
    document.title = `${preferredTitle} | NekoraList`;
    const cleanDetailUrl = window.AniDexShared?.buildDetailUrl
      ? window.AniDexShared.buildDetailUrl(String(selectedId || ""), preferredTitle || query || "")
      : "";
    if (cleanDetailUrl) {
      const currentUrl = `${window.location.pathname}${window.location.search}`;
      const cleanUrlObj = new URL(cleanDetailUrl, window.location.origin);
      const desiredUrl = `${cleanUrlObj.pathname}${cleanUrlObj.search}`;
      if (currentUrl !== desiredUrl) {
        window.history.replaceState({ detailId: selectedId || "" }, "", cleanDetailUrl);
      }
    }

    const poster = document.querySelector("section .aspect-\\[2\\/3\\] img");
    const bg = document.querySelector("section img[alt='Background Art']");
    const src = imgOf(full);
    if (poster && src) poster.src = src;
    if (bg && src) bg.src = src;
    document.body.dataset.detailId = selectedId || "";
    document.body.dataset.detailTitle = preferredTitle;
    document.body.dataset.detailImage = src || "";
    document.body.dataset.detailType = full?.type === "Movie" ? "Pel\u00edcula" : "Anime";
    const isMovie = (full.type || "").toLowerCase() === "movie";
    const badge = document.getElementById("detail-badge");
    if (badge) {
      if (
        preferredTitle === "Frieren: Beyond Journey's End Season 2" ||
        preferredTitle === "Jujutsu Kaisen: The Culling Game Part 1" ||
        preferredTitle === "Jujutsu Kaisen: Shimetsu Kaiyuu - Zenpen" ||
        preferredTitle === "Hell's Paradise Season 2" ||
        preferredTitle === "Sentenced to Be a Hero" ||
        preferredTitle === "Oshi no Ko Season 3"
      ) {
        badge.textContent = "Lo m\u00e1s Nuevo";
        badge.className = "px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase text-white/90 bg-gradient-to-r from-fuchsia-500/40 via-sky-400/30 to-emerald-400/30 border border-fuchsia-300/60 shadow-[0_0_16px_rgba(139,92,246,0.45)]";
      } else {
        badge.textContent = toSpanishType(full.type) || "Anime";
        badge.className = "px-3 py-1 bg-primary/10 border border-primary/20 text-primary-dim text-xs font-bold rounded-full tracking-wider uppercase";
      }
    }

    const trailerSurface = document.getElementById("detail-trailer-surface");
    const trailerModal = document.getElementById("detail-trailer-modal");
    const trailerCard = document.getElementById("detail-trailer-card");
    const trailerVideo = document.getElementById("detail-trailer-video");
    const trailerYt = document.getElementById("detail-trailer-yt");
    const trailerClose = document.getElementById("detail-trailer-close");
    const trailerOverlay = document.getElementById("detail-trailer-overlay");
    const bgVideo = document.getElementById("detail-bg-video");
    const bgYt = document.getElementById("detail-bg-yt");
    const trailerConfigByTitle = {
      "Frieren: Beyond Journey's End Season 2": {
        src: "https://files.catbox.moe/6w5jzl.mp4",
        bgVideo: true,
        showPlay: false,
        originalQuality: true
      },
      "Jujutsu Kaisen: Shimetsu Kaiyuu - Zenpen": {
        src: "https://files.catbox.moe/jn0t8b.mp4",
        bgVideo: true,
        showPlay: false,
        originalQuality: true
      },
      "Hell's Paradise Season 2": {
        src: "https://files.catbox.moe/gtdtry.mp4",
        bgVideo: true,
        showPlay: false,
        originalQuality: true
      },
      "Sentenced to Be a Hero": {
        src: "https://files.catbox.moe/vlcrpl.mp4",
        bgVideo: true,
        showPlay: false,
        originalQuality: true
      },
      "Oshi no Ko Season 3": {
        src: "https://files.catbox.moe/0k4krs.mp4",
        bgVideo: true,
        showPlay: false,
        originalQuality: true
      }
    };
    const trailerConfig = trailerConfigByTitle[preferredTitle];
    const trailerSrc = trailerConfig?.src;
    const youtubeId = trailerConfig?.youtubeId || full?.trailer?.youtube_id || full?.trailer?.youtubeId;
    const hasTrailer = Boolean(trailerSrc || youtubeId);
    const buildYoutubeEmbed = (id, opts = {}) => {
      const params = new URLSearchParams({
        autoplay: opts.autoplay ? "1" : "0",
        mute: opts.mute ? "1" : "0",
        controls: opts.controls ? "1" : "0",
        playsinline: "1",
        loop: opts.loop ? "1" : "0",
        playlist: opts.loop ? id : "",
        modestbranding: "1",
        rel: "0",
        iv_load_policy: "3"
      });
      return `https://www.youtube.com/embed/${encodeURIComponent(id)}?${params.toString()}`;
    };
    if (hasTrailer && trailerSurface && trailerModal) {
      document.body.classList.remove("bg-video-on");
      document.body.classList.remove("bg-video-original");
      document.body.classList.remove("bg-video-youtube");
      trailerSurface.classList.remove("hidden");
      trailerModal.classList.remove("hidden");
      if (trailerVideo) {
        if (trailerSrc) trailerVideo.src = trailerSrc;
        else {
          trailerVideo.pause();
          trailerVideo.removeAttribute("src");
          trailerVideo.load();
        }
        if (src) trailerVideo.poster = src;
        trailerVideo.preload = "metadata";
      }
      if (trailerYt) {
        trailerYt.src = "about:blank";
      }
      const useYouTube = Boolean(youtubeId);
      if (useYouTube) {
        document.body.classList.add("bg-video-youtube");
        if (trailerVideo) trailerVideo.classList.add("hidden");
        if (trailerYt) trailerYt.classList.remove("hidden");
      } else {
        if (trailerVideo) trailerVideo.classList.remove("hidden");
        if (trailerYt) trailerYt.classList.add("hidden");
      }
      let bgPlayTimer = null;
      const trailerIcon = document.getElementById("detail-trailer-icon");
      if (trailerIcon) {
        if (trailerConfig?.showPlay) {
          trailerIcon.classList.remove("hidden");
        } else {
          trailerIcon.classList.add("hidden");
        }
      }
      if (trailerConfig?.bgVideo) {
        document.body.classList.add("bg-video-off");
        if (useYouTube && bgYt && youtubeId) {
          if (bgVideo) {
            bgVideo.pause();
            bgVideo.removeAttribute("src");
            bgVideo.load();
          }
          bgYt.src = "about:blank";
          bgPlayTimer = setTimeout(() => {
            const ytSrc = buildYoutubeEmbed(youtubeId, { autoplay: true, mute: true, controls: false, loop: true });
            bgYt.src = ytSrc;
            document.body.classList.add("bg-video-on");
            document.body.classList.remove("bg-video-off");
          }, 2000);
        } else if (bgVideo && trailerSrc) {
          bgVideo.src = trailerSrc;
          bgVideo.preload = "metadata";
          bgVideo.muted = true;
          bgVideo.loop = false;
          bgVideo.playsInline = true;
          if (trailerConfig?.originalQuality) {
            document.body.classList.add("bg-video-original");
          }
          bgPlayTimer = setTimeout(() => {
            const playBg = bgVideo.play();
            if (playBg?.then) {
              playBg.then(() => {
                document.body.classList.add("bg-video-on");
                document.body.classList.remove("bg-video-off");
              }).catch(() => {});
            }
          }, 2000);
          bgVideo.onended = () => {
            document.body.classList.remove("bg-video-on");
            document.body.classList.add("bg-video-off");
            if (bgPlayTimer) clearTimeout(bgPlayTimer);
            bgPlayTimer = setTimeout(() => {
              const playBg = bgVideo.play();
              if (playBg?.then) {
                playBg.then(() => {
                  document.body.classList.add("bg-video-on");
                  document.body.classList.remove("bg-video-off");
                }).catch(() => {});
              }
            }, 2000);
          };
        }
      } else {
        if (bgVideo) {
          bgVideo.pause();
          bgVideo.removeAttribute("src");
          bgVideo.load();
        }
        if (bgYt) bgYt.src = "about:blank";
      }
      const openTrailer = () => {
        document.body.classList.add("trailer-open");
        document.body.classList.remove("bg-video-on");
        document.body.classList.add("bg-video-off");
        if (bgPlayTimer) {
          clearTimeout(bgPlayTimer);
          bgPlayTimer = null;
        }
        if (bgVideo) bgVideo.pause();
        if (useYouTube && trailerYt && youtubeId) {
          trailerYt.src = buildYoutubeEmbed(youtubeId, { autoplay: true, mute: false, controls: true, loop: false });
        } else if (trailerVideo) {
          trailerVideo.muted = false;
          trailerVideo.volume = 0.9;
          const playPromise = trailerVideo.play();
          if (playPromise?.catch) playPromise.catch(() => {});
        }
      };
      const closeTrailer = () => {
        document.body.classList.remove("trailer-open");
        if (useYouTube && trailerYt) {
          trailerYt.src = "about:blank";
        }
        if (trailerVideo) {
          trailerVideo.pause();
          trailerVideo.currentTime = 0;
        }
        if (trailerConfig?.bgVideo) {
          document.body.classList.remove("bg-video-on");
          document.body.classList.add("bg-video-off");
          if (bgPlayTimer) {
            clearTimeout(bgPlayTimer);
          }
          bgPlayTimer = setTimeout(() => {
            if (useYouTube && bgYt && youtubeId) {
              bgYt.src = buildYoutubeEmbed(youtubeId, { autoplay: true, mute: true, controls: false, loop: true });
              document.body.classList.add("bg-video-on");
              document.body.classList.remove("bg-video-off");
              return;
            }
            if (bgVideo) {
              const playBg = bgVideo.play();
              if (playBg?.then) {
                playBg.then(() => {
                  document.body.classList.add("bg-video-on");
                  document.body.classList.remove("bg-video-off");
                }).catch(() => {});
              }
            }
          }, 2000);
        }
      };
      trailerSurface.addEventListener("click", openTrailer);
      if (trailerOverlay) trailerOverlay.addEventListener("click", closeTrailer);
      if (trailerClose) trailerClose.addEventListener("click", (e) => {
        e.stopPropagation();
        closeTrailer();
      });
      if (trailerCard) trailerCard.addEventListener("click", (e) => e.stopPropagation());
    }
    if (!full) {
      console.error("AniDex Detail: No se encontraron datos para", selectedId);
      return;
    }

    const syn = document.querySelector("h2 + p");
    if (syn) {
      const synText = full.synopsis || "Sinopsis no disponible.";
      syn.textContent = await translateToEs(synText);
    }

    const statusMeta = document.getElementById("detail-status-meta");
    if (statusMeta) {
      const statusText = toSpanishStatus(full.status);
      const epsText = full.episodes ? `${full.episodes}` : "N/A";
      const durText = toSpanishDuration(full.duration);
      statusMeta.innerHTML = `
        <span>Estado: ${statusText}</span>
        <span class="text-outline-variant">&gt;&lt;</span>
        <span>Episodios: ${epsText}</span>
        <span class="text-outline-variant">&gt;&lt;</span>
        <span>Duraci\u00f3n: ${durText}</span>
      `;
    }

    const genreContainer = document.getElementById("detail-genres");
    if (genreContainer && full.genres) {
      genreContainer.innerHTML = full.genres.map(g => `
        <span class="px-4 py-1.5 bg-surface-container-high text-on-surface-variant text-sm rounded-full border border-outline-variant/10">${GENRE_ES[g.name] || g.name}</span>
      `).join("");
    }

    const infoBlock = document.getElementById("detail-info-block");
    if (infoBlock) {
      infoBlock.innerHTML = "";
      renderMetaBlock(infoBlock, "T\u00edtulo nativo", full.title_japanese || full.title_japanese_full);
      renderMetaBlock(infoBlock, "Estudio", (full.studios || []).map(s => s.name).join(", "));
      renderMetaBlock(infoBlock, "Fuente", await translateToEs(full.source));
      renderMetaBlock(infoBlock, "Emisi\u00f3n", full.aired?.string || "N/A");
      renderMetaBlock(infoBlock, "Clasificaci\u00f3n", toSpanishRating(full.rating));
      if (full.score) renderMetaBlock(infoBlock, "Puntuaci\u00f3n", `${full.score} (${full.scored_by || 0} votos)`);
    }

    const synopsisBlockEl = Array.from(document.querySelectorAll("h2"))
      .find((x) => /sinopsis/i.test(x.textContent || ""))?.parentElement;
    const titleKey = [preferredTitle, query, preTitle].filter(Boolean).join(" ");
    const isFrieren = selectedId === 59978 || /Frieren:\s*Beyond Journey's End Season 2/i.test(titleKey);
    const isJujutsuZenpen = selectedId === 57658 || /Jujutsu Kaisen:\s*Shimetsu Kaiyuu\s*-\s*Zenpen|Jujutsu Kaisen:\s*The Culling Game Part 1/i.test(titleKey);
    const isHellsParadise = selectedId === 55825 || /Hell's Paradise Season 2/i.test(titleKey);
    const isSentencedHero = selectedId === 56009 || /Sentenced to\s*Be a Hero/i.test(titleKey);
    const isOshiNoKo = selectedId === 60058 || /Oshi no Ko Season 3/i.test(titleKey);
    if (
      !isMovie &&
      synopsisBlockEl &&
      !document.getElementById("detail-episodes")
    ) {
      const episodesTotal = Number(full.episodes) || 12;
      const cover =
        full.images?.jpg?.large_image_url ||
        full.images?.webp?.large_image_url ||
        full.images?.jpg?.image_url ||
        "";
      const episodePrefix = isFrieren
        ? "2"
        : isJujutsuZenpen
          ? "3"
          : isHellsParadise
            ? "2"
            : isSentencedHero
              ? "1"
              : isOshiNoKo
                ? "3"
                : "1";
      const decodeLink = (b64) => {
        try {
          return b64 ? atob(b64) : "";
        } catch {
          return b64 || "";
        }
      };
      const linksForTitle = isFrieren
        ? {
            1: "aHR0cHM6Ly91cWxvYWQuaXMvMmVlZXh2OWJ6eGE5Lmh0bWw=",
            2: "aHR0cHM6Ly91cWxvYWQuaXMvNXRlcTJjNjF0Y2s1Lmh0bWw=",
            3: "aHR0cHM6Ly91cWxvYWQuaXMvN3VzNHNlbDdreXh1Lmh0bWw="
          }
        : isJujutsuZenpen
          ? {
              1: "aHR0cHM6Ly91cWxvYWQuaXMvcHJhaWlmb3JmMGl1Lmh0bWw=",
              2: "aHR0cHM6Ly91cWxvYWQuaXMvOTNlaW9kZnhmNDIuaHRtbA==",
              3: "aHR0cHM6Ly91cWxvYWQuaXMvYTZ1OTdyc2s2bTQ1Lmh0bWw="
            }
          : isHellsParadise
            ? {
                1: "aHR0cHM6Ly91cWxvYWQuaXMvcnhiY2FrNHc4ZnYyLmh0bWw=",
                2: "aHR0cHM6Ly91cWxvYWQuaXMvMG11N2IybGcyYjl5Lmh0bWw=",
                3: "aHR0cHM6Ly91cWxvYWQuaXMvZTJ2aHFiM21wNHY4Lmh0bWw="
              }
            : isSentencedHero
              ? {
                  1: "aHR0cHM6Ly91cWxvYWQuaXMvbDdxcXFiaWUxZGFnLmh0bWw=",
                  2: "aHR0cHM6Ly91cWxvYWQuaXMvczJzdzVxbTF3cTM1Lmh0bWw=",
                  3: "aHR0cHM6Ly91cWxvYWQuaXMvaGR2MjJvd3E2bnc0Lmh0bWw="
                }
              : isOshiNoKo
                ? {
                    1: "aHR0cHM6Ly91cWxvYWQuaXMvOHA0Z2VsNXl0eG0wLmh0bWw=",
                    2: "aHR0cHM6Ly91cWxvYWQuaXMvdXBtbzdneWJmY2FwLmh0bWw=",
                    3: "aHR0cHM6Ly91cWxvYWQuaXMvMjJvZHVzZXBkZTE0Lmh0bWw="
                  }
                : {};
      const toEmbed = (link) => {
        const match = link.match(/\/([a-z0-9]+)\.html/i);
        if (!match) return link;
        return `https://uqload.is/embed-${match[1]}.html`;
      };
      const localEpisodesData = Array.isArray(full.episodes_data) ? full.episodes_data : [];
      const localEpisodeMap = new Map(
        localEpisodesData.map((episode) => [Number(episode?.number || episode?.episode_number), episode])
      );
      const rawEpisodeMeta = localEpisodesData.length ? [] : await fetchEpisodeMeta(selectedId);
      const episodeMetaMap = new Map(
        (rawEpisodeMeta || []).map((episode) => [Number(episode?.mal_id || episode?.mal_episode_id || episode?.episode_id || episode?.number), episode])
      );
      const fallbackEpisodeSynopsis = (epNumber) => {
        const animeLabel = preferredTitle || full.title || "este anime";
        return `Sinopsis no disponible todav\u00eda para el episodio ${epNumber} de ${animeLabel}.`;
      };
      const episodeItems = Array.from({ length: episodesTotal }).map((_, index) => {
        const epNumber = index + 1;
        const epCode = `${episodePrefix}-${epNumber}`;
        const episodeMeta = localEpisodeMap.get(epNumber) || episodeMetaMap.get(epNumber) || {};
        const epTitle = episodeMeta.title || episodeMeta.title_japanese || `Episodio ${epNumber}`;
        const rawSynopsis = (episodeMeta.synopsis || "").trim() || fallbackEpisodeSynopsis(epNumber);
        const epSynopsis = shortenEpisodeText(rawSynopsis);
        const linkUrl = linksForTitle[epNumber];
        return { epNumber, epCode, epTitle, epSynopsis, linkUrl };
      });

      const getK = (k) => (window.AniDexProfile && window.AniDexProfile.getIsolatedKey) ? AniDexProfile.getIsolatedKey(k) : k;

      const seenBaseKey = selectedId
        ? `anidex_seen_eps_${selectedId}`
        : `anidex_seen_eps_${norm(preferredTitle || query || "anime")}`;
      
      const seenKey = getK(seenBaseKey);
      const continueKey = getK("anidex_continue_v1");
      const loadSeen = () => {
        try {
          const raw = localStorage.getItem(seenKey);
          return raw ? JSON.parse(raw) : {};
        } catch {
          return {};
        }
      };
      const saveSeen = (map) => {
        try { 
          localStorage.setItem(seenKey, JSON.stringify(map));
          if (window.AniDexProfile && typeof window.AniDexProfile.saveToDB === "function") {
            window.AniDexProfile.saveToDB().catch(e => console.warn("Save seen to DB error:", e));
          }
        } catch {}
      };
      let seenMap = loadSeen();
      const loadContinue = () => {
        try {
          const raw = localStorage.getItem(continueKey);
          const arr = raw ? JSON.parse(raw) : [];
          const map = {};
          (arr || []).forEach((it) => {
            if (!it) return;
            const key = `${it.malId || norm(it.title || "")}-${it.episode}`;
            if (key && it.episode) map[key] = it;
          });
          return map;
        } catch {
          return {};
        }
      };
      const saveContinue = (map) => {
        try { 
          localStorage.setItem(continueKey, JSON.stringify(Object.values(map))); 
          if (window.AniDexProfile && typeof window.AniDexProfile.saveToDB === "function") {
            window.AniDexProfile.saveToDB().catch(e => console.warn("Save continue to DB error:", e));
          }
        } catch {}
      };
      let continueMap = loadContinue();
      const isSeen = (ep) => {
        if (seenMap[String(ep)]) return true;
        const key = `${selectedId || norm(preferredTitle || "")}-${ep}`;
        return Boolean(continueMap[key]);
      };
      const setSeen = (ep, seen) => {
        if (seen) seenMap[String(ep)] = true;
        else delete seenMap[String(ep)];
        saveSeen(seenMap);
      };
      const upsertContinue = (card) => {
        const ep = card?.getAttribute("data-episode");
        if (!ep) return;
        const epTitle =
          card.getAttribute("data-episode-title") ||
          card.querySelector("h3")?.textContent?.trim() ||
          `Episodio ${ep}`;
        const detailUrl = selectedId
          ? `detail?mal_id=${encodeURIComponent(selectedId)}`
          : `detail?q=${encodeURIComponent(preferredTitle || query || "")}`;
        const key = `${selectedId || norm(preferredTitle || "")}-${ep}`;
        continueMap[key] = {
          sourceId: selectedId || null,
          malId: selectedId || null,
          title: preferredTitle,
          query: preferredTitle || query || "",
          episode: Number(ep),
          episodeTitle: epTitle,
          cover: src || "",
          detailUrl,
          lastSeen: Date.now()
        };
        saveContinue(continueMap);
      };
      const removeContinue = (card) => {
        const ep = card?.getAttribute("data-episode");
        if (!ep) return;
        const key = `${selectedId || norm(preferredTitle || "")}-${ep}`;
        if (continueMap[key]) {
          delete continueMap[key];
          saveContinue(continueMap);
        }
      };
      const updateSeenUI = (card, seen) => {
        card.classList.toggle("border-emerald-400/60", seen);
        card.classList.toggle("bg-emerald-500/10", seen);
        card.classList.toggle("shadow-[0_0_18px_rgba(16,185,129,0.25)]", seen);
        const btn = card.querySelector("[data-episode-seen]");
        if (btn) {
          btn.setAttribute("aria-pressed", seen ? "true" : "false");
          btn.classList.toggle("text-emerald-300", seen);
          btn.classList.toggle("border-emerald-300/40", seen);
          btn.classList.toggle("bg-emerald-500/15", seen);
          btn.classList.toggle("text-white/60", !seen);
          btn.classList.toggle("border-white/10", !seen);
          btn.classList.toggle("bg-white/5", !seen);
        }
      };
      const markSeenByCard = (card) => {
        const ep = card?.getAttribute("data-episode");
        if (!ep) return;
        setSeen(ep, true);
        upsertContinue(card);
        updateSeenUI(card, true);
      };

      const escapeHtml = (value) =>
        String(value ?? "").replace(/[&<>"']/g, (char) => ({
          "&": "&amp;",
          "<": "&lt;",
          ">": "&gt;",
          '"': "&quot;",
          "'": "&#39;"
        }[char] || char));

      const renderEpisodeCard = (item) => {
        const safeLinkUrl = item.linkUrl || "";
        const safeEmbedUrl = item.linkUrl ? toEmbed(decodeLink(item.linkUrl)) : "";
        const safeEpisodeTitle = escapeHtml(item.epTitle);
        const safeEpisodeSynopsis = escapeHtml(item.epSynopsis);
        const safeImageSrc = escapeHtml(src);
        const episodeAttrs = item.linkUrl
          ? `data-episode-link="${safeLinkUrl}" data-episode-embed="${safeEmbedUrl}" data-episode-title="${safeEpisodeTitle}"`
          : `data-episode-image="${safeImageSrc}" data-episode-title="${safeEpisodeTitle}"`;
        const seenBtnClass = canWatchEpisodes ? "" : "hidden";
        return `
          <div class="episode-card flex flex-col gap-4 rounded-2xl border border-white/5 bg-surface-container-low/70 p-4 transition-all duration-300 hover:border-violet-400/70 hover:shadow-[0_0_18px_rgba(139,92,246,0.35)] hover:-translate-y-0.5 cursor-pointer md:flex-row md:items-start" data-episode="${item.epNumber}" ${episodeAttrs}>
            <div class="flex items-center gap-4 flex-shrink-0 md:w-[9.5rem]">
              <div class="w-20 h-20 rounded-[6px] overflow-hidden bg-surface-container-high">
                <img src="${safeImageSrc}" alt="Episodio ${escapeHtml(item.epCode)}" class="w-full h-full object-cover" />
              </div>
              <span class="min-w-[3.75rem] whitespace-nowrap text-center px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-[0.18em] leading-none text-violet-100 bg-violet-500/25 border border-violet-400/50">${escapeHtml(`${episodePrefix}-${item.epNumber}`)}</span>
            </div>
            <div class="min-w-0 flex-1 flex flex-col justify-center">
              <h3 class="font-semibold text-white text-base" data-episode-title-text>${safeEpisodeTitle}</h3>
              <p class="text-on-surface-variant text-sm mt-2" data-episode-synopsis>${safeEpisodeSynopsis}</p>
            </div>
            <div class="flex items-center justify-end md:ml-auto">
              <button type="button" class="episode-seen-btn ${seenBtnClass} w-12 h-12 rounded-full border border-white/10 bg-white/5 text-white/60 flex items-center justify-center transition-all duration-200 hover:text-emerald-200 hover:border-emerald-300/50 hover:bg-emerald-500/10" data-episode-seen aria-pressed="false" title="Marcar como visto">
                <span class="material-symbols-outlined text-[28px]">visibility</span>
              </button>
            </div>
          </div>
        `;
      };

      const episodesSection = document.createElement("div");
      episodesSection.id = "detail-episodes";
      episodesSection.className = "space-y-6";
      episodesSection.innerHTML = `
        <h2 class="font-headline text-3xl font-bold">Episodios</h2>
        <div class="space-y-4" data-episodes-list></div>
        <div class="pt-2 flex justify-center">
          <button type="button" data-episodes-more class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-full border border-violet-400/40 bg-violet-500/10 px-6 py-3 text-sm font-bold uppercase tracking-widest text-violet-100 shadow-[0_0_14px_rgba(139,92,246,0.25)] transition-all duration-300 hover:bg-violet-500/20 hover:border-violet-300 hover:shadow-[0_0_22px_rgba(139,92,246,0.45)] hover:-translate-y-0.5">
            Ver m\u00e1s episodios
          </button>
        </div>
      `;

      const listEl = episodesSection.querySelector("[data-episodes-list]");
      const moreBtn = episodesSection.querySelector("[data-episodes-more]");
      let shown = 0;
      const pageSize = 10;
      let bindEpisodeCards = null;
      const canAccessEpisode = (card) => {
        if (canWatchEpisodes) return true;
        const episodeNumber = Number(card?.getAttribute("data-episode") || 0);
        return Boolean(isLogged && episodeNumber === 1);
      };
      const lockEpisodes = () => {
        const lockText = isLogged
          ? "Activa el modo premium para ver los episodios"
          : "Inicia sesi\u00f3n y accede al modo premium para ver los episodios";
        const goPremium = () => {
                    const appUrl = window.AniDexShared?.buildAppUrl;
          window.location.href = appUrl
            ? appUrl(isLogged ? "pago" : "registro")
            : (isLogged ? "/pago" : "/registro");
        };
        const cards = Array.from(episodesSection.querySelectorAll(".episode-card"));
        cards.forEach((card) => {
          if (canAccessEpisode(card)) return;
          if (card.dataset.locked) return;
          card.dataset.locked = "1";
          card.classList.add("relative", "overflow-hidden", "cursor-not-allowed", "transition-transform", "duration-300", "hover:-translate-y-1", "hover:shadow-[0_10px_26px_rgba(0,0,0,0.4)]");
          card.insertAdjacentHTML(
            "beforeend",
            `<div class="absolute inset-0 z-20 bg-black/72 backdrop-blur-[3.5px] flex flex-col items-center justify-center gap-2 text-center px-4">
              <span class="material-symbols-outlined text-[28px] text-white">lock</span>
              <button type="button" class="text-xs font-semibold uppercase tracking-widest text-on-surface hover:text-white transition-colors" data-episode-login>
                ${lockText}
              </button>
            </div>`
          );
          const loginBtn = card.querySelector("[data-episode-login]");
          const overlay = card.querySelector(".absolute.inset-0");
          const goRegister = (e) => {
            if (e) {
              e.preventDefault();
              e.stopPropagation();
              if (typeof e.stopImmediatePropagation === "function") e.stopImmediatePropagation();
            }
            goPremium();
          };
          card.addEventListener("click", goRegister, true);
          if (loginBtn) loginBtn.addEventListener("click", goRegister);
          if (overlay) overlay.addEventListener("click", goRegister);
        });
      };
      const ensureEpisodeHoverFix = () => {
        if (document.getElementById("episode-hover-fix")) return;
        const style = document.createElement("style");
        style.id = "episode-hover-fix";
        style.textContent = `
          body.episode-scroll-fix .episode-card{
            transform:none !important;
            box-shadow:none !important;
            border-color: rgba(255,255,255,0.05) !important;
          }
        `;
        document.head.appendChild(style);
        let scrollTimer = null;
        window.addEventListener("scroll", () => {
          document.body.classList.add("episode-scroll-fix");
          if (scrollTimer) clearTimeout(scrollTimer);
          scrollTimer = setTimeout(() => {
            document.body.classList.remove("episode-scroll-fix");
          }, 140);
        }, { passive: true });
      };
      ensureEpisodeHoverFix();
      const episodeDetailCache = new Map();
      let episodeCacheSaveStarted = false;
      const pendingEpisodeCache = new Map();
      const queueEpisodeCacheSave = (episodeNumber, title, synopsis) => {
        if (!episodeNumber) return;
        const safeTitle = (title || '').trim();
        const safeSynopsis = (synopsis || '').trim();
        if (!safeTitle && !safeSynopsis) return;
        pendingEpisodeCache.set(String(episodeNumber), {
          number: Number(episodeNumber),
          title: safeTitle,
          synopsis: safeSynopsis
        });
      };
      const flushEpisodeCache = async () => {
        if (episodeCacheSaveStarted || !pendingEpisodeCache.size || !selectedId) return;
        episodeCacheSaveStarted = true;
        const payload = Array.from(pendingEpisodeCache.values());
        pendingEpisodeCache.clear();
        try {
          await fetch(appUrl("api/save_anime"), {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              mal_id: selectedId,
              title: full.title || preferredTitle || "",
              title_english: full.title_english || preferredTitle || "",
              episodes_data: payload
            })
          });
        } catch {}
      };
      const enrichEpisodeCards = async (cards) => {
        if (!selectedId || !Array.isArray(cards) || !cards.length) return;
        await Promise.all(cards.map(async (card) => {
          if (!card || card.dataset.episodeDetailLoaded === "1") return;
          const epNumber = Number(card.getAttribute("data-episode") || 0);
          if (!epNumber || localEpisodeMap.has(epNumber)) return;
          card.dataset.episodeDetailLoaded = "1";
          let detail = episodeDetailCache.get(epNumber);
          if (detail === undefined) {
            detail = await fetchEpisodeDetail(selectedId, epNumber);
            episodeDetailCache.set(epNumber, detail || null);
          }
          if (!detail) return;
          const titleText = card.querySelector("[data-episode-title-text]");
          const synopsisText = card.querySelector("[data-episode-synopsis]");
          const nextTitle = (detail.title || detail.title_japanese || "").trim();
          const nextSynopsis = (detail.synopsis || "").trim();
          let finalTitle = nextTitle;
          let finalSynopsis = nextSynopsis;
          if (nextTitle && titleText) {
            const translatedTitle = await translateToEs(nextTitle);
            finalTitle = (translatedTitle || nextTitle).trim();
            titleText.textContent = finalTitle;
            card.setAttribute("data-episode-title", finalTitle);
          }
          if (nextSynopsis && synopsisText) {
            const translatedSynopsis = await translateToEs(nextSynopsis);
            finalSynopsis = shortenEpisodeText(translatedSynopsis || nextSynopsis);
            synopsisText.textContent = finalSynopsis;
          }
          if (finalTitle || finalSynopsis) {
            queueEpisodeCacheSave(epNumber, finalTitle, finalSynopsis);
            localEpisodeMap.set(epNumber, {
              number: epNumber,
              title: finalTitle,
              synopsis: finalSynopsis
            });
          }
        }));
        flushEpisodeCache();
      };
      const renderNextEpisodes = () => {
        const next = episodeItems.slice(shown, shown + pageSize);
        if (!next.length) return;
        const fragment = document.createDocumentFragment();
        next.forEach((item) => {
          const div = document.createElement("div");
          div.innerHTML = renderEpisodeCard(item);
          fragment.appendChild(div.firstElementChild);
        });
        listEl.appendChild(fragment);
        const addedCards = Array.from(listEl.children).slice(shown);
        addedCards.forEach((card) => {
          const ep = card.getAttribute("data-episode");
          if (ep && isSeen(ep)) {
            updateSeenUI(card, true);
          }
        });
        shown += next.length;
        if (shown >= episodeItems.length) {
          if (moreBtn) moreBtn.classList.add("hidden");
        } else {
          if (moreBtn) moreBtn.classList.remove("hidden");
        }
        if (canWatchEpisodes || isLogged) {
          if (bindEpisodeCards) bindEpisodeCards();
          enrichEpisodeCards(addedCards);
        }
        lockEpisodes();
      };
      if (moreBtn) moreBtn.addEventListener("click", renderNextEpisodes);
      renderNextEpisodes();

      const episodesHost = document.getElementById("detail-episodes-host");
      if (episodesHost) {
        episodesHost.appendChild(episodesSection);
      } else {
        synopsisBlockEl.insertAdjacentElement("afterend", episodesSection);
      }

      const episodeModalId = "detail-episode-modal";
      let episodeModal = document.getElementById(episodeModalId);
      if (!episodeModal) {
        episodeModal = document.createElement("div");
        episodeModal.id = episodeModalId;
        episodeModal.className = "fixed inset-0 z-[110] hidden flex items-center justify-center p-4 sm:p-6";
        episodeModal.innerHTML = `
          <div class="absolute inset-0 bg-black/80 backdrop-blur-md"></div>
          <div class="relative w-[min(92vw,720px)] rounded-2xl bg-black shadow-2xl border border-white/10 transform translate-y-6 sm:translate-y-8" data-episode-shell>
            <button type="button" data-episode-close class="absolute -top-3 -right-3 w-9 h-9 rounded-full bg-violet-600 text-white hover:bg-violet-500 flex items-center justify-center z-50 transition-all duration-300 shadow-[0_0_15px_rgba(139,92,246,0.5)] border border-white/20" aria-label="Cerrar">
              <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
            <video data-episode-video-player class="w-full aspect-video bg-black rounded-2xl" controls playsinline preload="metadata">
              <source data-episode-video-source type="video/mp4" />
            </video>
          </div>
        `;
        document.body.appendChild(episodeModal);
      }

      const videoCard = episodesSection.querySelector("[data-episode-video]");
      const ensureLinkModal = () => {
        let linkModal = document.getElementById("detail-episode-link-modal");
        if (!linkModal) {
          linkModal = document.createElement("div");
          linkModal.id = "detail-episode-link-modal";
          linkModal.className = "fixed inset-0 z-[110] hidden flex items-center justify-center p-4 sm:p-6";
          linkModal.innerHTML = `
            <div class="absolute inset-0 bg-black/80 backdrop-blur-md" data-link-backdrop></div>
            <div class="relative w-[min(94vw,1100px)] h-[min(80vh,720px)] rounded-2xl bg-black shadow-2xl border border-white/10 transform translate-y-6 sm:translate-y-8" data-link-shell>
              <button type="button" data-link-close class="absolute -top-3 -right-3 w-10 h-10 rounded-full bg-violet-600 text-white hover:bg-violet-500 flex items-center justify-center z-50 transition-all duration-300 shadow-[0_0_20px_rgba(139,92,246,0.5)] border border-white/20" aria-label="Cerrar">
                <span class="material-symbols-outlined text-[24px]">close</span>
              </button>
              <iframe data-link-frame class="w-full h-full border-none rounded-2xl" allow="autoplay; fullscreen" allowfullscreen referrerpolicy="no-referrer"></iframe>
            </div>
          `;
          document.body.appendChild(linkModal);
        }
        return linkModal;
      };

      const ensureImageModal = () => {
        let imageModal = document.getElementById("detail-episode-image-modal");
        if (!imageModal) {
          imageModal = document.createElement("div");
          imageModal.id = "detail-episode-image-modal";
          imageModal.className = "fixed inset-0 z-[110] hidden flex items-center justify-center p-4 sm:p-6";
          imageModal.innerHTML = `
            <div class="absolute inset-0 bg-black/80 backdrop-blur-md"></div>
            <div class="relative w-full h-full flex items-center justify-center p-6">
              <div class="relative inline-flex overflow-visible" data-image-shell>
                <button type="button" data-image-close class="absolute -top-8 -right-12 w-10 h-10 rounded-full bg-violet-500/60 text-white text-lg font-bold shadow-lg shadow-violet-500/40 backdrop-blur hover:bg-violet-400/70 flex items-center justify-center">&times;</button>
                <img data-image-frame class="max-w-[92vw] max-h-[82vh] rounded-2xl object-contain" alt="Portada episodio" />
              </div>
            </div>
          `;
          document.body.appendChild(imageModal);
        }
        return imageModal;
      };

      bindEpisodeCards = () => {
        const linkModal = ensureLinkModal();
        const imageModal = ensureImageModal();
        const linkFrame = linkModal.querySelector("[data-link-frame]");
        const linkCloseBtn = linkModal.querySelector("[data-link-close]");
        const linkBackdrop = linkModal.firstElementChild;
        const closeLink = () => {
          if (linkFrame) linkFrame.src = "";
          linkModal.classList.add("hidden");
          document.body.style.overflow = "";
        };
        const openLink = (card) => {
          const embedLink = card.getAttribute("data-episode-embed") || "";
          const directLink = card.getAttribute("data-episode-link") || "";
          const link = decodeLink(embedLink || directLink);
          if (!link || !linkFrame) return;
          markSeenByCard(card);
          linkFrame.src = link;
          linkModal.classList.remove("hidden");
          document.body.style.overflow = "hidden";
        };
        const imageFrame = imageModal.querySelector("[data-image-frame]");
        const imageCloseBtn = imageModal.querySelector("[data-image-close]");
        const imageBackdrop = imageModal.firstElementChild;
        const closeImage = () => {
          if (imageFrame) imageFrame.removeAttribute("src");
          imageModal.classList.add("hidden");
          document.body.style.overflow = "";
        };
        const openImage = (card) => {
          const src = card.getAttribute("data-episode-image");
          const title = card.getAttribute("data-episode-title") || "Portada episodio";
          if (!src || !imageFrame) return;
          markSeenByCard(card);
          imageFrame.src = src;
          imageFrame.alt = title;
          imageModal.classList.remove("hidden");
          document.body.style.overflow = "hidden";
        };
        const linkCards = Array.from(episodesSection.querySelectorAll("[data-episode-link]")).filter((card) => canAccessEpisode(card));
        const imageCards = Array.from(episodesSection.querySelectorAll("[data-episode-image]")).filter((card) => canAccessEpisode(card));
        linkCards.forEach((card) => {
          if (card.dataset.episodeBound) return;
          card.dataset.episodeBound = "1";
          card.addEventListener("click", () => openLink(card));
        });
        imageCards.forEach((card) => {
          if (card.dataset.episodeBound) return;
          card.dataset.episodeBound = "1";
          card.addEventListener("click", () => openImage(card));
        });
        if (linkCloseBtn && !linkCloseBtn.dataset.bound) {
          linkCloseBtn.dataset.bound = "1";
          linkCloseBtn.addEventListener("click", closeLink);
        }
        if (linkBackdrop && !linkBackdrop.dataset.bound) {
          linkBackdrop.dataset.bound = "1";
          linkBackdrop.addEventListener("click", closeLink);
        }
        if (!linkModal.dataset.outsideBound) {
          linkModal.dataset.outsideBound = "1";
          linkModal.addEventListener("click", (e) => {
            if (!e.target.closest("[data-link-shell]")) closeLink();
          });
        }
        if (imageCloseBtn && !imageCloseBtn.dataset.bound) {
          imageCloseBtn.dataset.bound = "1";
          imageCloseBtn.addEventListener("click", closeImage);
        }
        if (imageBackdrop && !imageBackdrop.dataset.bound) {
          imageBackdrop.dataset.bound = "1";
          imageBackdrop.addEventListener("click", closeImage);
        }
        if (!imageModal.dataset.outsideBound) {
          imageModal.dataset.outsideBound = "1";
          imageModal.addEventListener("click", (e) => {
            if (!e.target.closest("[data-image-shell]")) closeImage();
          });
        }
      };

      if (canWatchEpisodes || isLogged) {
        bindEpisodeCards();
      }
      if (videoCard && episodeModal && canAccessEpisode(videoCard)) {
        const player = episodeModal.querySelector("[data-episode-video-player]");
        const source = episodeModal.querySelector("[data-episode-video-source]");
        const closeBtn = episodeModal.querySelector("[data-episode-close]");
        const backdrop = episodeModal.firstElementChild;
        const openEpisode = () => {
          if (!player || !source) return;
          markSeenByCard(videoCard);
          source.src = decodeLink(videoCard.getAttribute("data-episode-video") || "");
          player.load();
          player.currentTime = 0;
          episodeModal.classList.remove("hidden");
          document.body.style.overflow = "hidden";
          const playPromise = player.play();
          if (playPromise?.catch) playPromise.catch(() => {});
        };
        const closeEpisode = () => {
          if (player && source) {
            player.pause();
            source.removeAttribute("src");
            player.load();
          }
          episodeModal.classList.add("hidden");
          document.body.style.overflow = "";
        };
        videoCard.addEventListener("click", openEpisode);
        if (closeBtn) closeBtn.addEventListener("click", closeEpisode);
        if (backdrop) backdrop.addEventListener("click", closeEpisode);
        if (!episodeModal.dataset.outsideBound) {
          episodeModal.dataset.outsideBound = "1";
          episodeModal.addEventListener("click", (e) => {
            if (!e.target.closest("[data-episode-shell]")) closeEpisode();
          });
        }
      }
    }

    const scoreEl = document.querySelector(".font-bold.text-lg");
    if (scoreEl) scoreEl.textContent = (full.score || "N/A").toString();

    const statusLine = document.getElementById("detail-status-meta") || document.querySelector("h1 + p");
    if (statusLine) {
      const yearValue = full.year || "";
      const episodesValue = full.episodes || "";
      statusLine.className = "text-on-surface-variant font-medium space-y-2 text-sm lg:text-base";
      const yearBlock = (!isMovie || yearValue)
        ? `<span class="flex flex-col"><span class="text-primary-dim text-xs uppercase tracking-wider">A\u00f1o</span><span>${yearValue || "N/A"}</span></span>`
        : "";
      const episodesBlock = !isMovie
        ? `<div><span class="flex flex-col"><span class="text-primary-dim text-xs uppercase tracking-wider">Episodios</span><span>${episodesValue || "N/A"}</span></span></div>`
        : "";
      statusLine.innerHTML = `
        <div class="flex flex-wrap items-start gap-x-10 gap-y-2">
          <span class="flex flex-col"><span class="text-primary-dim text-xs uppercase tracking-wider">Estado</span><span>${toSpanishStatus(full.status)}</span></span>
          ${yearBlock}
        </div>
        ${episodesBlock}
      `;
    }

    const genres = document.querySelector("h1")?.parentElement?.querySelector(".flex.flex-wrap.gap-2");
    if (genres) {
      genres.innerHTML = (full.genres || [])
        .map((g) => `<span class="px-4 py-1.5 rounded-full text-sm font-semibold text-white/90 bg-gradient-to-r from-violet-500/25 via-sky-500/10 to-fuchsia-500/20 border border-violet-400/30 shadow-[0_0_12px_rgba(139,92,246,0.35)]">${GENRE_ES[g.name] || g.name}</span>`)
        .join("");
    }

    const synopsisBlock = Array.from(document.querySelectorAll("h2"))
      .find((x) => /sinopsis/i.test(x.textContent || ""))?.parentElement;
    const infoBox = document.getElementById("detail-info-block");
    const infoList = infoBox;
    if (infoList) {
      infoList.innerHTML = `<h3 class="font-headline text-xl font-bold border-b border-outline-variant/10 pb-4">Informaci&oacute;n</h3>`;
      renderMetaBlock(infoList, "T\u00edtulo (EN)", full.title_english || preferredTitle || "N/A");
      renderMetaBlock(infoList, "T\u00edtulo (JP)", full.title_japanese || originalTitle || "N/A");
      renderMetaBlock(infoList, "Duraci&oacute;n", toSpanishDuration(full.duration) || "N/A");
      renderMetaBlock(infoList, "Clasificaci&oacute;n", toSpanishRating(full.rating) || "N/A");
      renderMetaBlock(infoList, "Ranking", full.rank ? `# ${full.rank}` : "N/A");
      renderMetaBlock(infoList, "Estudio", full.studios?.[0]?.name || "N/A");
    }

    const alignMediaToInfo = () => {
      if (window.innerWidth < 1024) {
        if (synopsisBlock) synopsisBlock.style.minHeight = "";
        return;
      }
      const infoCard = infoBox?.parentElement || null;
      const leftCol = synopsisBlock?.closest(".detail-left-col") || null;
      if (!infoCard || !synopsisBlock || !leftCol) {
        if (synopsisBlock) {
          synopsisBlock.style.minHeight = "";
          synopsisBlock.style.display = "";
          synopsisBlock.style.flexDirection = "";
          synopsisBlock.style.justifyContent = "";
        }
        if (leftCol) {
          leftCol.style.minHeight = "";
          leftCol.style.display = "";
          leftCol.style.flexDirection = "";
          leftCol.style.justifyContent = "";
        }
        return;
      }

      const synRect = synopsisBlock.getBoundingClientRect();
      const infoRect = infoCard.getBoundingClientRect();
      const gap = infoRect.height - synRect.height;
      const episodesSection = document.getElementById("detail-episodes");

      if (episodesSection) {
        leftCol.style.minHeight = "";
        leftCol.style.display = "";
        leftCol.style.flexDirection = "";
        leftCol.style.justifyContent = "";
        synopsisBlock.style.marginTop = "0px";

        const leftTop = leftCol.getBoundingClientRect().top;
        const synRectNow = synopsisBlock.getBoundingClientRect();
        const centerOffset = Math.round((infoRect.height - synRectNow.height) / 2);
        if (centerOffset > 0) {
          const desiredTop = leftTop + centerOffset;
          const offset = Math.round(desiredTop - synRectNow.top);
          synopsisBlock.style.marginTop = offset > 0 ? `${offset}px` : "0px";
        }

        episodesSection.style.marginTop = "0px";
        const episodesRect = episodesSection.getBoundingClientRect();
        const delta = infoRect.bottom - episodesRect.top;
        if (Math.abs(delta) > 1) {
          episodesSection.style.marginTop = `${Math.round(delta)}px`;
        } else {
          episodesSection.style.marginTop = "0px";
        }
        return;
      }

      if (gap > 0) {
        leftCol.style.minHeight = `${Math.round(infoRect.height)}px`;
        if (gap > 80) {
          leftCol.style.display = "flex";
          leftCol.style.flexDirection = "column";
          leftCol.style.justifyContent = "center";
        } else {
          leftCol.style.display = "";
          leftCol.style.flexDirection = "";
          leftCol.style.justifyContent = "";
        }
      } else {
        leftCol.style.minHeight = "";
        leftCol.style.display = "";
        leftCol.style.flexDirection = "";
        leftCol.style.justifyContent = "";
        synopsisBlock.style.marginTop = "";
      }
    };

    requestAnimationFrame(() => {
      alignMediaToInfo();
      setTimeout(alignMediaToInfo, 120);
      window.addEventListener("load", alignMediaToInfo);
      window.addEventListener("resize", alignMediaToInfo);
    });

    const charsRow = document.getElementById("chars-row") || document.querySelector(".hide-scrollbar");
    if (charsRow) {
      const mains = chars.filter((c) => /main/i.test(c?.role || ""));
      const supports = chars.filter((c) => !/main/i.test(c?.role || ""));
      const topChars = [...mains, ...supports].slice(0, 20);
      charsRow.classList.add("carousel-row");
      charsRow.style.setProperty("--cards", "5");
      charsRow.style.setProperty("--gap", "1rem");
      charsRow.style.setProperty("--pad", "0rem");
      charsRow.style.paddingInline = "0";
      charsRow.innerHTML = topChars.map((c, idx) => {
        const cleanName = (c.character?.name || "Personaje").replace(/,/g, "");
        const roleLabel = /main/i.test(c?.role || "") ? "principal" : "secundario";
        const fallbackMini = `<div class="w-full space-y-1.5 mt-1"><div class="h-2.5 bg-surface-container-highest rounded w-[80%] mx-auto animate-pulse"></div><div class="h-2.5 bg-surface-container-highest rounded w-[60%] mx-auto animate-pulse"></div></div>`;
        const desc = fallbackMini;
        const charId = c.character?.mal_id || "";
        const charImg = c.character?.images?.jpg?.image_url || "";
        return `
        <div class="carousel-card flex flex-col items-center gap-2 shrink-0 text-center cursor-pointer" data-char-id="${charId}" data-char-name="${cleanName}" data-char-role="${roleLabel}" data-char-img="${charImg}">
          <div class="w-24 h-24 overflow-hidden border border-primary/20 rounded-full">
            <img alt="${c.character?.name || "Personaje"}" class="w-full h-full object-cover" src="${c.character?.images?.jpg?.image_url || ""}"/>
          </div>
          <span class="text-sm font-bold break-words whitespace-normal">${cleanName}</span>
          <span class="text-[11px] font-semibold text-primary-dim">${roleLabel}</span>
          <span data-char-mini="${charId}" class="text-xs text-on-surface-variant leading-snug break-words whitespace-normal">${desc}</span>
        </div>`;
      }).join("");

      const charsToLoad = topChars.slice(0, 10);
      const loadBios = async () => {
         for (const c of charsToLoad) {
            const charId = c.character?.mal_id;
            const cardMini = document.body.querySelector(`[data-char-mini="${charId}"]`);
            if (!charId || !cardMini) continue;
            try {
               const r = await fetch(`${API}?endpoint=${encodeURIComponent('characters/' + charId + '/full')}`);
               if (r.status === 429) { await delay(1500); continue; }
               if (r.ok) {
                  const j = await r.json();
                  const rawAbout = (j?.data?.about || "").replace(/\\n/g, " ").replace(/\r\n/g, " ").trim();
                  if (!rawAbout) {
                      cardMini.textContent = "Sin descripci\u00f3n biogr\u00e1fica registrada.";
                      await delay(350);
                      continue; 
                  }
                  const miniSummary = await getMiniSummary(rawAbout);
                  if (!miniSummary) {
                      cardMini.textContent = "Sin descripci\u00f3n biogr\u00e1fica registrada.";
                      await delay(350);
                      continue; 
                  }
                  cardMini.textContent = miniSummary;
               } else {
                  cardMini.textContent = "Sin descripci\u00f3n biogr\u00e1fica registrada.";
               }
               await delay(450);
            } catch {
               cardMini.textContent = "Error de conexi\u00f3n temporal.";
            }
         }
      };
      loadBios();
      const prev = document.getElementById("chars-prev");
      const next = document.getElementById("chars-next");
      bindHorizontalArrows(charsRow, prev, next, 5);

      const characterCache = new Map();
      const cleanVoiceName = (name) => {
        const raw = (name || "").trim();
        if (!raw) return "";
        if (raw.includes(",")) {
          const parts = raw.split(",").map((p) => p.trim()).filter(Boolean);
          const last = parts[0] || "";
          const first = parts[1] || "";
          const firstToken = first.split(/\s+/)[0] || "";
          const lastToken = last.split(/\s+/)[0] || "";
          return [firstToken, lastToken].filter(Boolean).join(" ").trim();
        }
        const tokens = raw.split(/\s+/).filter(Boolean);
        if (tokens.length === 1) return tokens[0];
        return `${tokens[0]} ${tokens[tokens.length - 1]}`.trim();
      };
      const stripBioFields = (value) => {
        let out = (value || "").replace(/\s+/g, " ").trim();
        if (!out) return "";
        out = out.replace(/\((?:[^)]*?cm[^)]*?|[^)]*?kg[^)]*?)\)/gi, "");
        out = out.replace(
          /\b(?:Age|Birthday|Height|Weight|Blood type|Affiliations|Occupation|Rank|Graduation Rank|Species)\s*:\s*[^.\n]+[.\n]?/gi,
          ""
        );
        return out.replace(/\s+/g, " ").trim();
      };
      const isPhysical = (s) =>
        /(aos|edad|altura|peso|cm|kg|estatura|apariencia|fsic|cuerpo|complexin|rostro|cabell|ojos|pelo|color de|mide|naci|nacido)/i.test(
          s
        );
      const getMiniSummary = async (aboutText) => {
        const cleaned = stripBioFields(aboutText || "");
        if (!cleaned) return "";
        const aboutEs = await translateToEs(cleaned);
        const sentences = aboutEs
          .split(/(?<=[.!?])\s+/)
          .map((s) => s.trim())
          .filter(Boolean);
        let chosen = "";
        for (const s of sentences) {
          if (isPhysical(s)) continue;
          const w = s.split(" ").filter(Boolean);
          if (w.length >= 5 && w.length <= 18) {
            chosen = s;
            break;
          }
        }
        if (!chosen) return "";
        const words = chosen.split(" ").filter(Boolean);
        if (words.length > 24) {
          chosen = words.slice(0, 24).join(" ");
          if (!/[.!?]$/.test(chosen)) chosen = `${chosen}.`;
        }
        return chosen;
      };
      const ensureCharacterModal = () => {
        let modal = document.getElementById("detail-character-modal");
        if (modal) return modal;
        modal = document.createElement("div");
        modal.id = "detail-character-modal";
        modal.className = "fixed inset-0 z-[120] hidden flex items-center justify-center p-4 sm:p-6";
        modal.innerHTML = `
          <div class="absolute inset-0 bg-black/80 backdrop-blur-md" data-char-backdrop></div>
          <div class="relative w-full max-w-2xl rounded-2xl bg-surface-container-high shadow-2xl overflow-hidden border border-white/10" data-char-shell>
            <button type="button" data-char-close class="absolute top-4 right-4 w-11 h-11 rounded-full bg-black/50 text-white/90 hover:text-white hover:bg-violet-600 flex items-center justify-center backdrop-blur-md z-30 transition-all duration-300 shadow-xl border border-white/10" aria-label="Cerrar">
              <span class="material-symbols-outlined text-[28px]">close</span>
            </button>
            <div class="grid grid-cols-1 sm:grid-cols-[240px_1fr] gap-6 p-6 items-stretch">
              <div class="w-full h-full min-h-[260px] sm:min-h-[320px] rounded-2xl overflow-hidden bg-black/30 shadow-[0_0_24px_rgba(0,0,0,0.35)] flex items-center justify-center">
                <img data-char-img class="max-w-full max-h-full object-contain block" alt="Personaje" />
              </div>
              <div class="space-y-4">
                <div>
                  <h3 data-char-name class="text-2xl font-extrabold font-headline text-on-surface">Personaje</h3>
                  <p data-char-role class="text-xs uppercase tracking-widest text-primary-dim font-semibold mt-1">Rol</p>
                </div>
                <div class="h-px w-16 bg-violet-400/40"></div>
                <div>
                  <h4 class="text-xs uppercase tracking-widest text-on-surface-variant font-semibold">Descripci\u00f3n</h4>
                  <p data-char-info class="text-sm text-on-surface-variant leading-relaxed mt-2">Cargando informaci\u00f3n del personaje...</p>
                </div>
                <div class="h-px w-16 bg-violet-400/40"></div>
                <div data-char-fields-wrap>
                  <h4 class="text-xs uppercase tracking-widest text-on-surface-variant font-semibold">Datos personales</h4>
                  <div data-char-fields class="mt-2 grid grid-cols-[auto_1fr] gap-x-3 gap-y-2 text-sm text-on-surface-variant"></div>
                </div>
              </div>
            </div>
          </div>
        `;
        document.body.appendChild(modal);
        return modal;
      };

      const closeCharacterModal = () => {
        const modal = document.getElementById("detail-character-modal");
        if (modal) modal.classList.add("hidden");
        document.body.style.overflow = "";
      };

      const openCharacterModal = async (card) => {
        const modal = ensureCharacterModal();
        const imgEl = modal.querySelector("[data-char-img]");
        const nameEl = modal.querySelector("[data-char-name]");
        const roleEl = modal.querySelector("[data-char-role]");
        const infoEl = modal.querySelector("[data-char-info]");
        const fieldsWrap = modal.querySelector("[data-char-fields-wrap]");
        const fieldsEl = modal.querySelector("[data-char-fields]");
        
        const charId = card.getAttribute("data-char-id");
        const charName = card.getAttribute("data-char-name") || "Personaje";
        const charRole = card.getAttribute("data-char-role") || "";
        const charImg = card.getAttribute("data-char-img") || "";
        const miniFromCard = card.querySelector("[data-char-mini]")?.textContent?.trim() || "";

        if (nameEl) nameEl.textContent = charName;
        if (roleEl) roleEl.textContent = charRole ? `Rol: ${charRole}` : "Rol";
        if (imgEl && charImg) imgEl.src = charImg;
        if (infoEl) infoEl.textContent = miniFromCard; 
        
        if (fieldsWrap) fieldsWrap.classList.add("hidden");
        if (fieldsEl) fieldsEl.innerHTML = "";
        modal.classList.remove("hidden");
        document.body.style.overflow = "hidden";

        if (!charId) return;
        
        if (characterCache.has(charId)) {
          const cached = characterCache.get(charId);
          if (cached.full) {
            if (imgEl && cached.img) imgEl.src = cached.img;
            if (fieldsEl) fieldsEl.innerHTML = cached.fieldsHtml || "";
            if (fieldsWrap) {
              if (cached.fieldsHtml) fieldsWrap.classList.remove("hidden");
              else fieldsWrap.classList.add("hidden");
            }
            return;
          }
          if (imgEl && cached.img) imgEl.src = cached.img;
        }

        try {
          const res = await fetch(`${API}?endpoint=${encodeURIComponent('characters/' + charId + '/full')}`);
          if (!res.ok) throw new Error("fetch failed");
          const json = await res.json();
          const data = json?.data || {};
          
          const fieldMap = {
            "Birthday": "Cumplea\u00f1os",
            "Height": "Altura"
          };
          const fields = [];
          const labelSplitRe = /\b(?:Birthday|Height)\b\s*:/i;
          const sanitizeValue = (label, value) => {
            if (value === undefined || value === null) return "";
            let clean = String(value).replace(/\s+/g, " ").trim();
            if (!clean) return "";
            if (labelSplitRe.test(clean)) clean = clean.split(labelSplitRe)[0].trim();
            if (label === "Altura") {
              const m = clean.match(/(\d+(?:[.,]\d+)?)\s*cm/i);
              if (m) return `${m[1].replace(",", ".")} cm`;
            }
            return clean;
          };
          const addField = (label, value) => {
            if (!label) return;
            const clean = sanitizeValue(label, value);
            if (!clean) return;
            if (fields.some((f) => f.label === label)) return;
            fields.push({ label, value: clean });
          };
          if (typeof data?.favorites === "number") {
             addField("Popularidad", ` ${data.favorites} favoritos`);
          }
          
          const voices = Array.isArray(data?.voices) ? data.voices : [];
          const voiceNames = (lang) =>
            voices
              .filter((v) => (v?.language || "").toLowerCase().includes(lang))
              .map((v) => cleanVoiceName(v?.person?.name))
              .filter(Boolean)
              .join(" ");
          const voiceJp = voiceNames("japanese");
          const voiceEs = voiceNames("spanish") || voiceNames("espa\u00f1ol");
          addField("Doblaje japon\u00e9s", voiceJp);
          addField("Doblaje espa\u00f1ol", voiceEs);

          const escapeHtml = (value) =>
            (value || "").replace(/[&<>"']/g, (ch) => ({
              "&": "&amp;",
              "<": "&lt;",
              ">": "&gt;",
              '"': "&quot;",
              "'": "&#39;"
            }[ch]));

          const noTranslateLabels = new Set(["Doblaje japon\u00e9s", "Doblaje espa\u00f1ol"]);
          const translateValue = async (label, value) => {
            if (!value) return value;
            if (noTranslateLabels.has(label)) return value;
            if (!/[a-zA-Z]/.test(value)) return value;
            try {
              return await translateToEs(value);
            } catch {
              return value;
            }
          };

          const fieldsHtml = fields.length
            ? (await Promise.all(
                fields.map(async (f) => {
                  const val = await translateValue(f.label, f.value);
                  return `<span class="text-primary-dim font-semibold text-right">${escapeHtml(f.label)}:</span><span>${escapeHtml(val)}</span>`;
                })
              )).join("")
            : "";
          const img =
            data?.images?.webp?.image_url ||
            data?.images?.jpg?.image_url ||
            charImg ||
            "";

          characterCache.set(charId, { fieldsHtml, img, full: true });
          if (imgEl && img) imgEl.src = img;
          if (fieldsEl) fieldsEl.innerHTML = fieldsHtml;
          if (fieldsWrap) {
            if (fieldsHtml) fieldsWrap.classList.remove("hidden");
            else fieldsWrap.classList.add("hidden");
          }
        } catch {
          if (infoEl) infoEl.textContent = miniFromCard || "";
        }
      };

      const modal = ensureCharacterModal();
      const modalClose = modal.querySelector("[data-char-close]");
      const modalBackdrop = modal.querySelector("[data-char-backdrop]");
      if (modalClose && !modalClose.dataset.bound) {
        modalClose.dataset.bound = "1";
        modalClose.addEventListener("click", closeCharacterModal);
      }
      if (modalBackdrop && !modalBackdrop.dataset.bound) {
        modalBackdrop.dataset.bound = "1";
        modalBackdrop.addEventListener("click", closeCharacterModal);
      }
      if (!modal.dataset.outsideBound) {
        modal.dataset.outsideBound = "1";
        modal.addEventListener("click", (e) => {
          if (!e.target.closest("[data-char-shell]")) closeCharacterModal();
        });
      }
      Array.from(charsRow.querySelectorAll("[data-char-id]")).forEach((card) => {
        if (card.dataset.bound) return;
        card.dataset.bound = "1";
        card.addEventListener("click", () => openCharacterModal(card));
      });

      const populateMiniSummaries = async () => {
        const cards = Array.from(charsRow.querySelectorAll("[data-char-id]"));
        for (const card of cards) {
          const miniEl = card.querySelector("[data-char-mini]");
          const charId = card.getAttribute("data-char-id");
          if (!miniEl || !charId) continue;
          const cached = characterCache.get(charId);
          if (cached?.miniSummary) {
            miniEl.textContent = cached.miniSummary;
            continue;
          }
          try {
            const res = await fetch(`${API}?endpoint=${encodeURIComponent('characters/' + charId + '/full')}`);
            if (!res.ok) throw new Error("fetch failed");
            const json = await res.json();
            const data = json?.data || {};
            const aboutRaw = (data?.about || "").trim();
            const miniSummary = await getMiniSummary(aboutRaw);
            if (miniSummary) miniEl.textContent = miniSummary;
            const cachedImg =
              data?.images?.webp?.image_url ||
              data?.images?.jpg?.image_url ||
              card.getAttribute("data-char-img") ||
              "";
            characterCache.set(charId, { miniSummary, img: cachedImg, full: false });
          } catch {
          }
          await new Promise((r) => setTimeout(r, 180));
        }
      };
      populateMiniSummaries();
    }

    const mediaBlock = document.getElementById("detail-media") || synopsisBlock;
    if (mediaBlock) {
      const images = pics.slice(0, 20);
      if (images.length) {
        mediaBlock.appendChild(slider("Galer\u00eda", images, (it) => `
          <img data-zoomable src="${it?.jpg?.large_image_url || it?.jpg?.image_url || ""}" class="h-56 w-full object-cover cursor-zoom-in rounded-2xl" />
        `, "detail-images"));
      }
      const clips = [vids?.promo?.[0], ...(vids?.promo || []).slice(1, 8)]
        .filter(Boolean)
        .filter((it) => it?.trailer?.url || it?.trailer?.youtube_id);
      if (clips.length) {
        mediaBlock.appendChild(slider("Videos / Clips", clips, (it) => `
          <a href="${it?.trailer?.url || (it?.trailer?.youtube_id ? `https://www.youtube.com/watch?v=${it.trailer.youtube_id}` : "#")}" target="_blank" rel="noopener noreferrer" class="block h-36 w-36 overflow-hidden border border-outline-variant/20 bg-surface-container-high relative">
            <img src="${it?.trailer?.images?.medium_image_url || it?.trailer?.images?.image_url || ""}" class="h-full w-full object-cover"/>
            <span class="absolute inset-0 bg-black/30 flex items-center justify-center text-xs font-bold">Ver clip</span>
          </a>
        `, "detail-videos"));
      }
    }

    const external = Array.from(document.querySelectorAll("h3")).find((x) => /enlaces externos/i.test((x.textContent || "").toLowerCase()));
    if (external) external.parentElement?.remove();

    const trailerBtn = document.getElementById("detail-trailer-btn");
    if (trailerBtn) {
      const turl =
        (full?.trailer?.youtube_id ? `https://www.youtube.com/watch?v=${full.trailer.youtube_id}` : "") ||
        full?.trailer?.url ||
        `https://www.youtube.com/results?search_query=${encodeURIComponent((full?.title || query || "") + " trailer")}`;
      trailerBtn.href = turl;
    }

    const actionBtns = document.querySelectorAll("[data-add-my-list], [data-add-favorite]");
    actionBtns.forEach((btn) => {
      btn.dataset.itemTitle = preferredTitle;
      btn.dataset.itemImage = src || "";
      btn.dataset.itemType = (full.type || "").toLowerCase() === "movie" ? "Pel\u00edcula" : "Anime";
      btn.dataset.itemId = String(selectedId || "");
    });
    document.body.dataset.detailTitle = preferredTitle;
    document.body.dataset.detailImage = src || "";
    document.body.dataset.detailType = (full.type || "").toLowerCase() === "movie" ? "Pel\u00edcula" : "Anime";
    if (window.AniDexFavorites) window.AniDexFavorites.refresh();

    const recCards = Array.from(document.querySelectorAll("section a.group.cursor-pointer"));
    if (recCards.length) {
      const recType = (full.type || "").toLowerCase() === "movie" ? "movie" : "tv";
      const recItems = await fetchTopByType(recType, recCards.length);
      recCards.forEach((a, i) => {
        const it = recItems[i];
        if (!it) return;
        const img = a.querySelector("img");
        const h = a.querySelector("h4,h3,h5");
        const p = a.querySelector("p");
        const scoreEl = a.querySelector(".text-xs.font-bold");
        if (img) img.src = imgOf(it) || img.src;
        if (h) h.textContent = it.title || h.textContent;
        if (p) p.textContent = (it.genres || []).map((g) => g.name).slice(0, 2).join(", ");
        if (scoreEl && typeof it.score === "number") scoreEl.textContent = it.score.toFixed(1);
        if (it?.mal_id) a.setAttribute("data-mal-id", String(it.mal_id));
        a.href = window.AniDexShared?.buildDetailUrl ? window.AniDexShared.buildDetailUrl(String(it.mal_id || ""), it.title || "") : `detail/${encodeURIComponent(String(it.mal_id || ""))}`;
      });
    }

    document.addEventListener("click", (e) => {
      const img = e.target.closest("img[data-zoomable]");
      if (!img) return;
      const overlay = document.createElement("div");
      overlay.className = "fixed inset-0 z-[120] bg-black/90 flex items-center justify-center p-6";
      overlay.innerHTML = `<img src="${img.src}" class="max-h-[92vh] max-w-[92vw] object-contain border border-zinc-700" />`;
      overlay.addEventListener("click", () => overlay.remove());
      document.body.appendChild(overlay);
    });
  };

  window.AniDexDetailData = { init };
};

AniDexDetailDataBoot();
