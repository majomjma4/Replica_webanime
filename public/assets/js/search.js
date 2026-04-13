(() => {
  const appUrl = window.AniDexShared?.buildAppUrl || ((path = "") => String(path || ""));
  const buildDetailUrl = window.AniDexShared?.buildDetailUrl || ((malId = "", title = "") => {
    const numericId = String(malId || "").trim();
    if (/^\d+$/.test(numericId)) return appUrl(`detail/${encodeURIComponent(numericId)}`);
    const cleanTitle = String(title || "").trim();
    return cleanTitle ? appUrl(`detail?q=${encodeURIComponent(cleanTitle)}`) : appUrl("detail");
  });
  const API_BASE = appUrl("api/jikan_proxy");
  const suggestCache = new Map();
  const hasJapaneseChars = (v) => /[\u3040-\u30ff\u3400-\u4dbf\u4e00-\u9fff]/.test(v || "");

  let originalTitleText = "";
  const isLoggedIn = localStorage.getItem("nekora_logged_in") === "true";

  const logActivity = async (action, details = "") => {
    if (!isLoggedIn) return;
    try {
      await fetch(appUrl("api/activity"), {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action, details })
      });
    } catch (e) { console.error("Error logging activity:", e); }
  };

  const normalize = (value) => window.AniDexShared?.normalizeText ? window.AniDexShared.normalizeText(value) : String(value || "").toLowerCase().trim();

  const scoreMatch = (query, candidate) => {
    const q = normalize(query);
    const c = normalize(candidate);
    if (!q || !c) return 0;
    if (q === c) return 100;
    if (c.includes(q) || q.includes(c)) return 80;
    const qTokens = q.split(" ");
    const cTokens = c.split(" ");
    const overlap = qTokens.filter((t) => cTokens.includes(t)).length;
    if (!overlap) return 0;
    return Math.round((overlap / Math.max(qTokens.length, cTokens.length)) * 70);
  };

  const tokenMatchScore = (query, candidate) => {
    const qTokens = normalize(query).split(" ").filter(Boolean);
    const c = normalize(candidate);
    if (!qTokens.length || !c) return 0;
    const matched = qTokens.filter((t) => c.includes(t)).length;
    return matched / qTokens.length;
  };

  const getCardTitle = (card) => {
    const dataTitle = card.getAttribute("data-title");
    if (dataTitle) return dataTitle;
    const h = card.querySelector("h3,h4,h5");
    return h ? h.textContent : "";
  };

  const saveOriginalCardState = (card) => {
    if (card.dataset.stateSaved === "1") return;
    card.dataset.stateSaved = "1";
    const img = card.querySelector("img");
    if (img) card.dataset.originalSrc = img.src;
    const titleEl = card.querySelector("h3,h4,h5");
    if (titleEl) card.dataset.originalTitle = titleEl.textContent;
    const link = card.closest("a") || card.querySelector("a");
    if (link) card.dataset.originalHref = link.href;
    card.dataset.originalMalId = card.getAttribute("data-mal-id") || "";
  };

  const restoreOriginalCardState = (card) => {
    if (card.dataset.stateSaved !== "1") return;
    const img = card.querySelector("img");
    if (img && card.dataset.originalSrc) img.src = card.dataset.originalSrc;
    const titleEl = card.querySelector("h3,h4,h5");
    if (titleEl && card.dataset.originalTitle) titleEl.textContent = card.dataset.originalTitle;
    const link = card.closest("a") || card.querySelector("a");
    if (link && card.dataset.originalHref) link.href = card.dataset.originalHref;
    if (card.dataset.originalMalId) {
      card.setAttribute("data-mal-id", card.dataset.originalMalId);
    } else {
      card.removeAttribute("data-mal-id");
    }
  };

  const updateUrl = (term) => {
    const url = new URL(window.location.href);
    if (term.trim()) {
      url.searchParams.set("q", term.trim());
    } else {
      url.searchParams.delete("q");
    }
    window.history.replaceState({}, "", url);
  };

  const applyFilter = (term) => {
    const cards = Array.from(document.querySelectorAll("[data-anime-card]"));
    if (!cards.length) return false;
    const q = normalize(term);
    let shown = 0;

    const isSearching = !!term.trim();
    if (window.AniDexLoadMore) {
      if (isSearching) window.AniDexLoadMore.hide();
      else window.AniDexLoadMore.show();
    }
    updateUrl(term);

    cards.forEach((card) => {
      saveOriginalCardState(card);
      if (!isSearching) {
        restoreOriginalCardState(card);
        card.style.display = "";
        shown += 1;
      } else {
        const title = normalize(getCardTitle(card));
        const match = title.includes(q);
        card.style.display = match ? "" : "none";
        if (match) shown += 1;
      }
    });

    const target = document.querySelector("h1, h2");
    if (target) {
      if (!term.trim()) {
        if (originalTitleText) target.textContent = originalTitleText;
      } else {
        target.textContent = `Resultados para: ${term}`;
      }
    }

    return shown > 0;
  };

  const imageOf = (item) =>
    item?.images?.webp?.large_image_url ||
    item?.images?.jpg?.large_image_url ||
    item?.images?.jpg?.image_url ||
    "";

  const YEAR_OVERRIDES = {
    "jujutsu kaisen 0 movie": 2021,
    "jujutsu kaisen 0": 2021
  };
  const HIDE_CARD_YEARS = /series\.php|peliculas\.php/i.test(window.location.pathname || "");
  const HIDE_CARD_GENRES = HIDE_CARD_YEARS;

  const SUGGEST_LIMIT = 6;
  const API_SUGGEST_LIMIT = 12;
  const popularCache = new Map();

  const pageContext = () => {
    const path = (window.location.pathname || "").toLowerCase();
    if (path.includes("peliculas")) return "movies";
    if (path.includes("series")) return "series";
    return "mixed";
  };

  const normalizeSuggestItem = (item) => {
    const base = item?.title || "";
    const english = item?.title_english || "";
    const japanese = item?.title_japanese || "";
    const safeTitle = hasJapaneseChars(base)
      ? (english || (hasJapaneseChars(japanese) ? "" : japanese) || base)
      : base;
    return {
      title: safeTitle,
      titleEn: english,
      titleJp: "",
      mediaType: item?.type || "",
      malId: item?.mal_id || null,
      image:
        item?.images?.webp?.image_url ||
        item?.images?.jpg?.image_url ||
        ""
    };
  };

  const fetchPopularSuggestions = async (mode = "mixed") => {
    if (popularCache.has(mode)) return popularCache.get(mode);
    try {
      let items = [];
      if (mode === "movies") {
        const res = await fetch(`${API_BASE}?endpoint=${encodeURIComponent("top/anime?filter=bypopularity&type=movie&limit=5")}`);
        const json = res.ok ? await res.json() : null;
        items = (json?.data || []).map(normalizeSuggestItem).slice(0, 5);
      } else if (mode === "series") {
        const res = await fetch(`${API_BASE}?endpoint=${encodeURIComponent("top/anime?filter=bypopularity&type=tv&limit=5")}`);
        const json = res.ok ? await res.json() : null;
        items = (json?.data || []).map(normalizeSuggestItem).slice(0, 5);
      } else {
        const [tvRes, movieRes] = await Promise.all([
          fetch(`${API_BASE}?endpoint=${encodeURIComponent("top/anime?filter=bypopularity&type=tv&limit=5")}`),
          fetch(`${API_BASE}?endpoint=${encodeURIComponent("top/anime?filter=bypopularity&type=movie&limit=5")}`)
        ]);
        const tvJson = tvRes.ok ? await tvRes.json() : null;
        const movieJson = movieRes.ok ? await movieRes.json() : null;
        const tvItems = (tvJson?.data || []).map(normalizeSuggestItem);
        const movieItems = (movieJson?.data || []).map(normalizeSuggestItem);
        const mixed = [];
        for (let i = 0; i < 5; i += 1) {
          if (tvItems[i]) mixed.push(tvItems[i]);
          if (movieItems[i]) mixed.push(movieItems[i]);
        }
        items = mixed.slice(0, 5);
      }
      popularCache.set(mode, items);
      return items;
    } catch {
      return [];
    }
  };

  const fetchRelatedByQuery = async (term, mediaType) => {
    const q = (term || "").trim();
    if (!q) return [];
    const wantMovie = pageForType(mediaType) === "peliculas";
    try {
      const url = `${API_BASE}?endpoint=${encodeURIComponent('anime?q=' + encodeURIComponent(q) + '&limit=25&order_by=popularity&sort=asc')}`;
      const res = await fetch(url);
      if (!res.ok) return [];
      const json = await res.json();
      const rows = (json?.data || []).filter((it) => {
        const t = normalize(it?.type || "");
        return wantMovie ? t.includes("movie") : !t.includes("movie");
      });
      const scored = rows
        .map((it) => {
          const best = Math.max(
            scoreMatch(q, it?.title || ""),
            scoreMatch(q, it?.title_english || ""),
            scoreMatch(q, it?.title_japanese || ""),
            Math.round(tokenMatchScore(q, it?.title || "") * 100),
            Math.round(tokenMatchScore(q, it?.title_english || "") * 100)
          );
          return { it, best };
        })
        .filter((x) => x.best >= 45)
        .sort((a, b) => b.best - a.best);
      return scored.map((x) => x.it);
    } catch {
      return [];
    }
  };

  const hydrateCardsWithResults = (items, mediaType, query = "") => {
    const cards = Array.from(document.querySelectorAll("[data-anime-card]"));
    if (!cards.length || !items.length) return false;
    cards.forEach((card, idx) => {
      saveOriginalCardState(card);
      const item = items[idx];
      if (!item) {
        card.style.display = "none";
        return;
      }
      card.style.display = "";
      const img = card.querySelector("img");
      if (img) {
        img.src = imageOf(item) || img.src;
        img.alt = item?.title || img.alt || "Anime";
      }
      const titleEl = card.querySelector("h3,h4,h5");
      if (titleEl) titleEl.textContent = item?.title || titleEl.textContent;
      const p = card.querySelector("p");
      if (p) {
        const genres = (item?.genres || []).map((g) => g?.name).filter(Boolean).slice(0, 2).join(", ");
        if (genres && !HIDE_CARD_GENRES) p.textContent = `G\u00e9neros: ${genres}`;
        const y = card.dataset.year || "";
        if (y && !HIDE_CARD_YEARS && !/(19|20)\\d{2}/.test(p.textContent || "")) {
          const sep = pageForType(mediaType) === "peliculas" ? "  " : " ";
          p.textContent = `${p.textContent}${sep}${y}`.trim();
        }
      }
      card.setAttribute("data-title", (item?.title || "").toLowerCase());
      card.setAttribute("data-type", pageForType(mediaType) === "peliculas" ? "Pel\u00edcula" : "Anime");
      const cleanTitle = (item?.title || "").trim();
      const key = normalize(item?.title || item?.title_english || "");
      const year = YEAR_OVERRIDES[key] || item?.year || item?.aired?.prop?.from?.year || "";
      if (year) {
        card.setAttribute("data-year", String(year));
        card.setAttribute("data-year-original", String(year));
      } else {
        card.removeAttribute("data-year");
        card.removeAttribute("data-year-original");
      }
      
      // FIX: Actualizar el mal_id y el enlace para redireccionar correctamente
      card.setAttribute("data-mal-id", String(item?.mal_id || ""));
      const cardLink = card.closest("a") || card.querySelector("a") || (card.tagName === "A" ? card : null);
      if (cardLink && item?.mal_id) {
        cardLink.setAttribute("data-mal-id", String(item.mal_id));
        // Incluir q= para que detail-links y el backend tengan respaldo
        cardLink.href = buildDetailUrl(item.mal_id, cleanTitle);
      }
      const yearEl = card.querySelector("[data-card-year]");
      if (HIDE_CARD_YEARS) {
        if (yearEl) yearEl.remove();
      } else if (yearEl) {
        yearEl.textContent = year ? String(year) : "";
        if (!year) yearEl.remove();
      } else if (year && p) {
        const span = document.createElement("span");
        span.dataset.cardYear = "1";
        span.className = "block text-xs text-on-surface-variant/80 mt-0.5";
        span.textContent = String(year);
        p.insertAdjacentElement("afterend", span);
      }
    });
    const target = document.querySelector("h1, h2");
    if (target) {
      if (query) {
        target.textContent = `Resultados para: ${query}`;
      } else if (originalTitleText) {
        target.textContent = originalTitleText;
      }
    }
    return true;
  };

  const goToSearchPage = (term, page = "series") => {
    const q = encodeURIComponent(term.trim());
    logActivity("search", `B?squeda: ${term.trim()} en ${page}`);
    window.location.href = `${appUrl(page)}?q=${q}`;
  };
  const pageForType = (mediaType) => {
    const t = normalize(mediaType);
    if (t.includes("movie") || t.includes("película")) return "peliculas";
    return "series";
  };

  const bindInput = (input) => {
    if (!input || input.dataset.searchBound === "1") return;
    if (input.dataset.genreSearch === "1") return;
    if (input.closest("[data-genre-dropdown],[data-genre-panel]")) return;
    input.dataset.searchBound = "1";
    let box = null;
    let timer = null;

    const ensureBox = () => {
      if (box) return box;
      const host = input.closest(".relative") || input.parentElement;
      if (!host) return null;
      if (getComputedStyle(host).position === "static") host.style.position = "relative";
      box = document.createElement("div");
      box.className =
        "absolute left-0 right-0 top-full mt-2 hidden border border-zinc-700 bg-zinc-900/95 backdrop-blur p-1 z-[80] anidex-suggest-box";
      box.style.width = "min(18rem, calc(92vw - 180px))";
      box.style.left = "50%";
      box.style.right = "auto";
      box.style.transform = "translateX(-50%)";
      box.style.scrollbarWidth = "thin";
      box.style.scrollbarColor = "rgba(255,255,255,0.12) transparent";
      box.style.borderRadius = "0";
      const styleId = "anidex-suggest-style";
      if (!document.getElementById(styleId)) {
        const st = document.createElement("style");
        st.id = styleId;
        st.textContent = `
          .anidex-suggest-box::-webkit-scrollbar { width: 8px; }
          .anidex-suggest-box::-webkit-scrollbar-track { background: transparent; }
          .anidex-suggest-box::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.12); border-radius: 0; }
          .anidex-suggest-box::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
        `;
        document.head.appendChild(st);
      }
      host.appendChild(box);
      return box;
    };

    const closeBox = () => {
      if (!box) return;
      box.classList.add("hidden");
      box.innerHTML = "";
    };

    const openBox = () => {
      const b = ensureBox();
      if (!b) return;
      b.classList.remove("hidden");
    };

    const getLocalSuggestions = (term) => {
      const q = normalize(term);
      if (!q) return [];
      const cards = Array.from(document.querySelectorAll("[data-anime-card]"));
      const seen = new Set();
      const out = [];
      cards.forEach((card) => {
        const title = (card.querySelector("h3,h4,h5")?.textContent || card.getAttribute("data-title") || "").trim();
        const value = normalize(title);
        if (!title || !value.includes(q) || seen.has(value)) return;
        seen.add(value);
        const img = card.querySelector("img")?.src || "";
        out.push({
          title,
          image: img,
          titleEn: "",
          titleJp: "",
          mediaType: card.getAttribute("data-type") || "Anime",
          malId: card.getAttribute("data-mal-id") || null
        });
      });
      return out.slice(0, API_SUGGEST_LIMIT);
    };

    const fetchApiSuggestions = async (term) => {
      const q = term.trim();
      if (!q) return [];
      if (suggestCache.has(q)) return suggestCache.get(q);
      try {
        const blacklist = ["hentai", "futanari", "dick", "pussy", "sex", "porn", "cock", "blowjob"];
        const res = await fetch(`${API_BASE}?endpoint=${encodeURIComponent('anime?q=' + encodeURIComponent(q) + '&limit=' + API_SUGGEST_LIMIT + '&order_by=popularity&sort=asc&sfw=1')}`);
        if (!res.ok) return [];
        const json = await res.json();
        const rawList = (json?.data || []).map((item) => {
          const base = item?.title || "";
          const english = item?.title_english || "";
          const japanese = item?.title_japanese || "";
          const safeTitle = hasJapaneseChars(base)
            ? (english || (hasJapaneseChars(japanese) ? "" : japanese) || base)
            : base;
          return {
            title: safeTitle,
            titleEn: english,
            titleJp: "",
            mediaType: item?.type || "",
            malId: item?.mal_id || null,
            image:
              item?.images?.webp?.image_url ||
              item?.images?.jpg?.image_url ||
              ""
          };
        });

        // Filtro estricto de seguridad +18 (Local)
        const list = rawList.filter(it => {
          const lower = (it.title + " " + it.titleEn).toLowerCase();
          return !blacklist.some(word => lower.includes(word));
        });

        // Filtro estricto: evita sugerencias que no tienen relacin real.
        const filtered = list.filter((it) => {
          const best = Math.max(
            scoreMatch(q, it.title),
            scoreMatch(q, it.titleEn || ""),
            Math.round(tokenMatchScore(q, it.title) * 100),
            Math.round(tokenMatchScore(q, it.titleEn || "") * 100)
          );
          const tokenRatio = Math.max(
            tokenMatchScore(q, it.title),
            tokenMatchScore(q, it.titleEn || "")
          );
          return best >= 55 && tokenRatio >= 0.5;
        });

        suggestCache.set(q, filtered);
        return filtered;
      } catch {
        return [];
      }
    };

    const resolveBestTitle = async (term) => {
      const query = (term || "").trim();
      if (!query) return "";

      const local = getLocalSuggestions(query).map((x) => ({
        title: x.title,
        aliases: [x.title],
        mediaType: x.mediaType || "Anime"
      }));
      const api = (await fetchApiSuggestions(query)).map((x) => ({
        title: x.title,
        aliases: [x.title, x.titleEn].filter(Boolean),
        mediaType: x.mediaType || "",
        malId: x.malId || null
      }));

      const pool = [...local, ...api];
      if (!pool.length) return { title: query, mediaType: "Anime" };

      let bestTitle = query;
      let bestType = "Anime";
      let bestScore = 0;
      pool.forEach((item) => {
        item.aliases.forEach((alias) => {
          const score = scoreMatch(query, alias);
          if (score > bestScore) {
            bestScore = score;
            bestTitle = item.title || query;
            bestType = item.mediaType || "Anime";
          }
        });
      });

      return {
        title: bestScore >= 55 ? bestTitle : query,
        mediaType: bestType,
        malId: bestScore >= 55 ? (pool.find(p => p.title === bestTitle)?.malId || null) : null
      };
    };

    const resolveSuggestPage = (items) => {
      if (!items.length) return "series";
      let movieCount = 0;
      let tvCount = 0;
      items.forEach((it) => {
        const t = normalize(it.mediaType || "");
        if (t.includes("movie") || t.includes("pelicula") || t.includes("pel\u00edcula")) movieCount += 1;
        else tvCount += 1;
      });
      // index no procesa búsquedas; enviar a series por defecto.
      return movieCount > tvCount ? "peliculas" : "series";
    };

    const renderSuggestions = (items, term) => {
      const b = ensureBox();
      if (!b) return;
      b.innerHTML = "";
      if (!items.length) {
        closeBox();
        return;
      }
      const visible = items.slice(0, SUGGEST_LIMIT);
      visible.forEach((it) => {
        const btn = document.createElement("button");
        btn.type = "button";
        btn.className =
          "w-full text-left px-2 py-2 hover:bg-zinc-800 transition-colors";
        btn.style.borderRadius = "0";
        const subtitle = [it.titleEn].filter(Boolean).join("  ");
        const isMovie = normalize(it.mediaType || "").includes("movie") || normalize(it.mediaType || "").includes("pelicula") || normalize(it.mediaType || "").includes("pel\u00edcula");
        const typeBadge = isMovie 
          ? `<span class="inline-block px-1.5 py-0.5 rounded bg-sky-500/20 border border-sky-500/30 text-sky-400 text-[9px] font-bold uppercase tracking-widest shrink-0 w-[60px] text-center">Película</span>` 
          : `<span class="inline-block px-1.5 py-0.5 rounded bg-violet-500/20 border border-violet-500/30 text-violet-400 text-[9px] font-bold uppercase tracking-widest shrink-0 w-[60px] text-center">Anime</span>`;

        btn.innerHTML = `
          <div class="flex items-center gap-3 w-full">
            <img src="${it.image || ""}" alt="${it.title}" class="h-14 w-10 object-cover bg-zinc-800 shrink-0" />
            <div class="min-w-0 flex-1 overflow-hidden">
              <div class="text-sm text-zinc-100 flex items-center justify-between gap-2">
                <span class="truncate block" title="${it.title}">${it.title}</span>
                ${typeBadge}
              </div>
              ${subtitle ? `<div class="text-[11px] text-zinc-400 truncate mt-0.5">${subtitle}</div>` : ""}
            </div>
          </div>
        `;
        btn.addEventListener("click", () => {
          input.value = it.title;
          closeBox();
          logActivity("search_suggestion_click", `Sugerencia clicada: ${it.title} (ID: ${it.malId})`);
          if (it.malId) {
            window.location.href = buildDetailUrl(it.malId, it.title);
          } else {
            goToSearchPage(it.title, pageForType(it.mediaType));
          }
        });
        b.appendChild(btn);
      });
      // Botón de ver más eliminado a petición del usuario
      openBox();
    };

    input.addEventListener("focus", async () => {
      const term = (input.value || "").trim();
      if (term) return;
      const isHeaderSearch = input.id !== "filter-search" && input.getAttribute("data-catalog-search") !== "1";
      if (!isHeaderSearch) return;
      const items = await fetchPopularSuggestions("mixed");
      renderSuggestions(items, "");
    });

    input.addEventListener("input", () => {
      const isHeaderSearch = input.id !== "filter-search" && input.getAttribute("data-catalog-search") !== "1";
      if (!isHeaderSearch) return;

      const term = (input.value || "").trim();
      
      // Si está vacío, cerramos todo de inmediato
      if (!term) {
        closeBox();
        return;
      }
      
      // Si hay texto, limpiamos las recomendaciones viejas pero mantenemos la caja abierta 
      // con un indicador de carga para que el usuario sepa que está funcionando.
      const b = ensureBox();
      if (b) b.innerHTML = '<div class="px-3 py-3 text-xs text-zinc-500 italic">Buscando...</div>';
      openBox();

      clearTimeout(timer);
      timer = setTimeout(async () => {
        const local = getLocalSuggestions(term);
        const api = await fetchApiSuggestions(term);
        const merged = [];
        const seen = new Set();
        [...local, ...api].forEach((it) => {
          const key = normalize(it.title);
          if (!key || seen.has(key)) return;
          const quality = Math.max(
            scoreMatch(term, it.title),
            scoreMatch(term, it.titleEn || ""),
            Math.round(tokenMatchScore(term, it.title) * 100),
            Math.round(tokenMatchScore(term, it.titleEn || "") * 100)
          );
          if (quality < 50) return;
          seen.add(key);
          merged.push(it);
        });
        renderSuggestions(merged, term);
      }, 180);
    });

    document.addEventListener("click", (e) => {
      if (!box) return;
      const host = input.closest(".relative") || input.parentElement;
      if (host && !host.contains(e.target)) closeBox();
    });

    input.addEventListener("keydown", async (e) => {
      if (e.key !== "Enter") return;
      e.preventDefault();
      closeBox();
      const term = (input.value || "").trim();
      if (!term) return;
      const resolved = await resolveBestTitle(term);
      const resolvedTerm = resolved.title;
      input.value = resolvedTerm;
      const hasCards = document.querySelector("[data-anime-card]");
      if (hasCards) {
        const ok = applyFilter(resolvedTerm);
        if (!ok) {
          if (resolved.malId) {
            window.location.href = buildDetailUrl(resolved.malId, resolvedTerm);
          } else {
            goToSearchPage(resolvedTerm, pageForType(resolved.mediaType));
          }
        }
      } else {
        if (resolved.malId) {
          window.location.href = buildDetailUrl(resolved.malId, resolvedTerm);
        } else {
          goToSearchPage(resolvedTerm, pageForType(resolved.mediaType));
        }
      }
    });
  };

  const init = () => {
    const navInputs = Array.from(document.querySelectorAll('input[placeholder*="Buscar"]'))
      .filter((el) => el.id !== "filter-search");
    navInputs.forEach(bindInput);

    const filterInput = document.querySelector('[data-catalog-search="1"]') || document.getElementById("filter-search");
    const target = document.querySelector("h1, h2");
    if (target && !originalTitleText) {
      const current = target.textContent.trim();
      if (!current.includes("Resultados para:")) {
        originalTitleText = current;
      } else {
        // Fallback based on page
        originalTitleText = window.location.pathname.includes("peliculas") ? "Pel\u00edculas" : "Descubrimiento";
      }
    }

    if (filterInput) {
      bindInput(filterInput);
      const handleFilter = () => applyFilter(filterInput.value);
      filterInput.addEventListener("input", handleFilter);
      filterInput.addEventListener("search", handleFilter);
    }

    const params = new URLSearchParams(window.location.search);
    const q = params.get("q");
    if (q && filterInput) {
      filterInput.value = q;
      const ok = applyFilter(q);
      if (!ok) {
        const mediaType = window.location.pathname.toLowerCase().includes("peliculas") ? "movie" : "tv";
        fetchRelatedByQuery(q, mediaType).then((items) => hydrateCardsWithResults(items, mediaType, q));
      }
    } else if (q && document.querySelector("[data-anime-card]")) {
      const ok = applyFilter(q);
      if (!ok) {
        const mediaType = window.location.pathname.toLowerCase().includes("peliculas") ? "movie" : "tv";
        fetchRelatedByQuery(q, mediaType).then((items) => hydrateCardsWithResults(items, mediaType, q));
      }
    }
  };

  window.AniDexSearch = { init };
})();







